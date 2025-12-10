<?php

namespace Core\Models;

use PDO;

class QueryBuilder
{
    protected Model $model;
    protected string $table;

    protected array $wheres = [];
    protected array $bindings = [];

    protected array $joins = [];
    protected string $select = '*';
    protected string $orderBy = '';
    protected string $limit = '';
    protected string $offset = '';
    protected string $groupBy = '';
    protected string $having = '';

    /** eager load relations */
    protected array $with = [];

    public function __construct(Model $model)
    {
        $this->model  = $model;
        $this->table  = $model->getTable();
    }

    /* ---------------------------------------------
     * RESET Query State
     * --------------------------------------------- */
    protected function reset(): void
    {
        $this->wheres = [];
        $this->bindings = [];
        $this->joins = [];
        $this->select = '*';
        $this->orderBy = '';
        $this->limit = '';
        $this->offset = '';
        $this->groupBy = '';
        $this->having = '';
    }

    /* ---------------------------------------------
     * SELECT & RAW
     * --------------------------------------------- */
    public function select(...$columns): self
    {
        $this->select = implode(', ', $columns);
        return $this;
    }

    /**
     * Executes a completely custom SQL query.
     */
    public function raw(string $sql, array $bindings = []): array
    {
        $stmt = $this->model->connection()->prepare($sql);
        $stmt->execute($bindings);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /* ---------------------------------------------
     * WHERE CLAUSES
     * --------------------------------------------- */
    public function where(string $column, string $operator, $value): self
    {
        $this->wheres[] = ['AND', "$column $operator ?"];
        $this->bindings[] = $value;
        return $this;
    }

    public function orWhere(string $column, string $operator, $value): self
    {
        $this->wheres[] = ['OR', "$column $operator ?"];
        $this->bindings[] = $value;
        return $this;
    }

    public function whereIn(string $column, array $values): self
    {
        if (empty($values)) {
            $this->wheres[] = ['AND', '0 = 1']; // always false
            return $this;
        }

        $placeholders = implode(',', array_fill(0, count($values), '?'));
        $this->wheres[] = ['AND', "$column IN ($placeholders)"];

        foreach ($values as $v) {
            $this->bindings[] = $v;
        }

        return $this;
    }

    public function whereNotIn(string $column, array $values): self
    {
        if (empty($values)) return $this;

        $placeholders = implode(',', array_fill(0, count($values), '?'));
        $this->wheres[] = ['AND', "$column NOT IN ($placeholders)"];

        foreach ($values as $v) $this->bindings[] = $v;

        return $this;
    }

    public function whereNull(string $column): self
    {
        $this->wheres[] = ['AND', "$column IS NULL"];
        return $this;
    }

    public function whereNotNull(string $column): self
    {
        $this->wheres[] = ['AND', "$column IS NOT NULL"];
        return $this;
    }

    /* ---------------------------------------------
     * JOINS
     * --------------------------------------------- */
    public function join(string $table, string $first, string $operator, string $second): self
    {
        $this->joins[] = "INNER JOIN $table ON $first $operator $second";
        return $this;
    }

    public function leftJoin(string $table, string $first, string $operator, string $second): self
    {
        $this->joins[] = "LEFT JOIN $table ON $first $operator $second";
        return $this;
    }

    public function rightJoin(string $table, string $first, string $operator, string $second): self
    {
        $this->joins[] = "RIGHT JOIN $table ON $first $operator $second";
        return $this;
    }

    /* ---------------------------------------------
     * ORDER, LIMIT, OFFSET, GROUP, HAVING
     * --------------------------------------------- */
    public function orderBy(string $column, string $direction = 'ASC'): self
    {
        $direction = strtoupper($direction);
        $this->orderBy = "ORDER BY $column $direction";
        return $this;
    }

    public function limit(int $limit): self
    {
        $this->limit = "LIMIT $limit";
        return $this;
    }

    public function offset(int $offset): self
    {
        $this->offset = "OFFSET $offset";
        return $this;
    }

    public function groupBy(string $columns): self
    {
        $this->groupBy = "GROUP BY $columns";
        return $this;
    }

    public function having(string $condition): self
    {
        $this->having = "HAVING $condition";
        return $this;
    }

    /* ---------------------------------------------
     * EAGER LOADING
     * --------------------------------------------- */
    public function with(array|string $relations): self
    {
        if (is_string($relations)) $relations = [$relations];
        $this->with = array_merge($this->with, $relations);
        return $this;
    }

    /* ---------------------------------------------
     * BUILD SQL
     * --------------------------------------------- */
    protected function buildSelect(): string
    {
        return "SELECT {$this->select} FROM {$this->table}";
    }

    protected function buildWheres(): string
    {
        if (empty($this->wheres)) return '';

        $sql = "WHERE ";

        foreach ($this->wheres as $i => $cond) {
            [$type, $fragment] = $cond;
            $sql .= ($i === 0 ? '' : " $type ") . $fragment;
        }

        return $sql;
    }

    protected function buildQuery(): string
    {
        $sql = $this->buildSelect();

        if (!empty($this->joins)) {
            $sql .= ' ' . implode(' ', $this->joins);
        }

        $where = $this->buildWheres();
        if ($where) $sql .= " $where";

        if ($this->groupBy) $sql .= " {$this->groupBy}";
        if ($this->having)  $sql .= " {$this->having}";
        if ($this->orderBy) $sql .= " {$this->orderBy}";
        if ($this->limit)   $sql .= " {$this->limit}";
        if ($this->offset)  $sql .= " {$this->offset}";

        return $sql;
    }

    /* ---------------------------------------------
     * EXECUTION
     * --------------------------------------------- */
    public function get(): array
    {
        $sql = $this->buildQuery();

        $stmt = $this->model->connection()->prepare($sql);
        $stmt->execute($this->bindings);

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $class = get_class($this->model);
        $models = array_map(function ($row) use ($class) {
            $obj = new $class();
            $obj->fillFromDb($row);
            return $obj;
        }, $rows);

        // EAGER LOAD
        if (!empty($this->with) && !empty($models)) {
            foreach ($this->with as $relation) {
                foreach ($models as $model) {
                    $model->loadRelation($relation);
                }
            }
        }

        return $models;
    }

    public function first()
    {
        $backupLimit = $this->limit;

        $this->limit(1);
        $result = $this->get()[0] ?? null;

        $this->limit = $backupLimit; // restore
        return $result;
    }

    public function count(): int
    {
        $sql = "SELECT COUNT(*) FROM {$this->table}";

        if (!empty($this->joins)) {
            $sql .= " " . implode(' ', $this->joins);
        }

        $where = $this->buildWheres();
        if ($where) $sql .= " $where";

        $stmt = $this->model->connection()->prepare($sql);
        $stmt->execute($this->bindings);

        return (int)$stmt->fetchColumn();
    }

    /* ---------------------------------------------
     * PAGINATION
     * --------------------------------------------- */
    public function paginate(int $perPage = 15, int $page = 1): array
    {
        $page = max(1, $page);

        $total = $this->count();
        $lastPage = (int)ceil($total / $perPage);
        $offset = ($page - 1) * $perPage;

        $this->limit($perPage)->offset($offset);
        $data = $this->get();

        return [
            'data' => $data,
            'meta' => [
                'total'        => $total,
                'per_page'     => $perPage,
                'current_page' => $page,
                'last_page'    => $lastPage,
            ],
        ];
    }

    /* ---------------------------------------------
     * DELETE
     * --------------------------------------------- */
    public function delete(): bool
    {
        $sql = "DELETE FROM {$this->table}";

        $where = $this->buildWheres();
        if ($where) $sql .= " $where";

        $stmt = $this->model->connection()->prepare($sql);
        return $stmt->execute($this->bindings);
    }
}
