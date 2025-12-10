<?php

namespace Core\Models;

use Core\Config\Database;
use PDO;

abstract class Model
{
    protected string $table;
    protected string $primaryKey = 'id';
    protected array $fillable = [];
    protected array $attributes = [];
    protected bool $timestamps = true;

    public function __construct(array $data = [])
    {
        // Use __set so the normal fill rules apply when constructing manually
        foreach ($data as $key => $value) {
            $this->__set($key, $value);
        }
    }

    /**
     * Hydrate model directly from DB row (bypass fillable)
     * Useful for get() / joins / raw selects
     */
    public function fillFromDb(array $data): void
    {
        foreach ($data as $k => $v) {
            $this->attributes[$k] = $v;
        }
    }

    public function connection(): ?PDO
    {
        return Database::connection();
    }

    public function getTable(): string
    {
        return $this->table;
    }

    public function __get($key)
    {
        // allow access to computed / relation placeholders too
        return $this->attributes[$key] ?? null;
    }

    public function __set($key, $value)
    {
        // respect fillable for "normal" assignment, but allow primaryKey
        if (in_array($key, $this->fillable) || $key === $this->primaryKey) {
            $this->attributes[$key] = $value;
        }
    }

    /* ------------------------------------------
     |  Basic static helpers (existing behavior)
     | ------------------------------------------ */

    public static function find($id)
    {
        $instance = new static();
        $table = $instance->getTable();

        $stmt = $instance->connection()->prepare("
            SELECT * FROM {$table}
            WHERE {$instance->primaryKey} = ?
            LIMIT 1
        ");

        $stmt->execute([$id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$data) return null;

        $model = new static();
        $model->fillFromDb($data);
        return $model;
    }

    public static function query()
    {
        return new QueryBuilder(new static());
    }

    public static function where(string $column, $operator, $value)
    {
        return (new QueryBuilder(new static()))->where($column, $operator, $value);
    }

    public static function create(array $data)
    {
        $instance = new static;
        $table = $instance->getTable();

        $fillable = array_intersect_key($data, array_flip($instance->fillable));

        if ($instance->timestamps) {
            $fillable['created_at'] = date("Y-m-d H:i:s");
            $fillable['updated_at'] = date("Y-m-d H:i:s");
        }

        $columns = implode(", ", array_keys($fillable));
        $placeholders = implode(", ", array_fill(0, count($fillable), '?'));

        $stmt = $instance->connection()->prepare("
            INSERT INTO {$table} ($columns)
            VALUES ($placeholders)
        ");

        $stmt->execute(array_values($fillable));

        $id = $instance->connection()->lastInsertId();

        return static::find($id);
    }

    public function save()
    {
        $fields = $this->attributes;

        if ($this->timestamps && isset($fields['created_at'])) {
            $fields['updated_at'] = date("Y-m-d H:i:s");
        }

        $id = $fields[$this->primaryKey] ?? null;
        if (!$id) {
            // insert fallback
            $toInsert = array_intersect_key($fields, array_flip($this->fillable));
            if ($this->timestamps) {
                $toInsert['created_at'] = date("Y-m-d H:i:s");
                $toInsert['updated_at'] = date("Y-m-d H:i:s");
            }
            $columns = implode(", ", array_keys($toInsert));
            $placeholders = implode(", ", array_fill(0, count($toInsert), '?'));
            $stmt = $this->connection()->prepare("INSERT INTO {$this->table} ($columns) VALUES ($placeholders)");
            $stmt->execute(array_values($toInsert));
            $this->{$this->primaryKey} = $this->connection()->lastInsertId();
            return true;
        }

        unset($fields[$this->primaryKey]);

        if (empty($fields)) return false;

        $set = implode(" = ?, ", array_keys($fields)) . " = ?";

        $stmt = $this->connection()->prepare("
            UPDATE {$this->table}
            SET $set
            WHERE {$this->primaryKey} = ?
        ");

        return $stmt->execute([...array_values($fields), $id]);
    }

    public static function all(): array
    {
        $instance = new static();
        $table = $instance->getTable();

        $stmt = $instance->connection()->prepare("SELECT * FROM {$table}");
        $stmt->execute();

        $results = [];
        while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $model = new static();
            $model->fillFromDb($data);
            $results[] = $model;
        }

        return $results;
    }

    /* ------------------------------------------
     |  Relationship helpers (used inside model methods)
     |  Each returns an array ['type' => 'one'|'many', 'query' => QueryBuilder]
     |  The child's model should define methods like:
     |
     |  public function posts() {
     |      return $this->hasMany(\App\Models\Post::class, 'user_id', 'id');
     |  }
     |
     |  public function profile() {
     |      return $this->hasOne(\App\Models\Profile::class, 'user_id', 'id');
     |  }
     |
     |  public function owner() {
     |      return $this->belongsTo(\App\Models\User::class, 'user_id', 'id');
     |  }
     | ------------------------------------------ */

    public function hasMany(string $relatedClass, string $foreignKey, string $localKey = null): array
    {
        $localKey = $localKey ?? $this->primaryKey;
        /** @var Model $relatedClass */
        $qb = $relatedClass::query()->where($foreignKey, '=', $this->{$localKey});
        return ['type' => 'many', 'query' => $qb];
    }

    public function hasOne(string $relatedClass, string $foreignKey, string $localKey = null): array
    {
        $localKey = $localKey ?? $this->primaryKey;
        $qb = $relatedClass::query()->where($foreignKey, '=', $this->{$localKey});
        return ['type' => 'one', 'query' => $qb];
    }

    public function belongsTo(string $relatedClass, string $foreignKey, string $ownerKey = null): array
    {
        $ownerKey = $ownerKey ?? (new $relatedClass())->primaryKey;
        // the foreignKey sits on this model
        $foreignValue = $this->{$foreignKey};
        $qb = $relatedClass::query()->where($ownerKey, '=', $foreignValue);
        return ['type' => 'one', 'query' => $qb];
    }

    /**
     * belongsToMany: simple pivot implementation
     * $pivot: pivot table
     * $foreignPivotKey: column on pivot that references this model (e.g. user_id)
     * $relatedPivotKey: column on pivot that references related model (e.g. role_id)
     */
    public function belongsToMany(string $relatedClass, string $pivot, string $foreignPivotKey, string $relatedPivotKey, string $localKey = null, string $relatedKey = null): array
    {
        $localKey = $localKey ?? $this->primaryKey;
        $relatedKey = $relatedKey ?? (new $relatedClass())->primaryKey;

        // build a query for relatedClass that joins the pivot
        $relatedTable = (new $relatedClass())->getTable();
        $qb = $relatedClass::query()
            ->join($pivot, "{$relatedTable}.{$relatedKey}", '=', "{$pivot}.{$relatedPivotKey}")
            ->where("{$pivot}.{$foreignPivotKey}", '=', $this->{$localKey});

        return ['type' => 'many', 'query' => $qb];
    }

    /**
     * loadRelation is called by QueryBuilder when eager-loading.
     * It will look for a method on the model with the same name as $relation.
     * That method should return one of the above helper arrays.
     */
    public function loadRelation(string $relation)
    {
        if (!method_exists($this, $relation)) return;

        $res = $this->{$relation}();

        if (!is_array($res) || !isset($res['query'])) return;

        /** @var \Core\Models\QueryBuilder $qb */
        $qb = $res['query'];

        if ($res['type'] === 'many') {
            $data = $qb->get(); // array of models
            $this->attributes[$relation] = $data;
        } else { // one
            $data = $qb->first(); // single model or null
            $this->attributes[$relation] = $data;
        }
    }

    public function delete(): bool
    {
        $id = $this->attributes[$this->primaryKey] ?? null;
        if (!$id) return false;

        $stmt = $this->connection()->prepare("
        DELETE FROM {$this->table}
        WHERE {$this->primaryKey} = ?
        LIMIT 1
    ");

        return $stmt->execute([$id]);
    }
}
