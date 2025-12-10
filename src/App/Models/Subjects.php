<?php

namespace App\Models;

use Core\Models\Model;

class Subjects  extends Model
{
    protected string $table = 'subjects';
    protected array $fillable = [
        'code',
        'title',
        'units',
        'category'
    ];
    protected bool $timestamps = true;

    public function prerequisites()
    {
        return $this->hasMany(Prerequisite::class, 'subject_id', 'id');
    }
    public function isPrerequisiteFor()
    {
        return $this->hasMany(Prerequisite::class, 'prerequisite_id', 'id');
    }
}
