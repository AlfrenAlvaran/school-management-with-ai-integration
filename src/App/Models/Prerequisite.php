<?php

namespace App\Models;

use Core\Models\Model;

class Prerequisite extends Model
{
    protected string $table = 'prerequisites';

    protected array $fillable = [
        'subject_id',
        'prerequisite_id'
    ];

    protected bool $timestamps = true;

    public function subject()
    {
        return $this->belongsTo(Subjects::class, 'subject_id', 'id');
    }

    public function prerequisite()
    {
        return $this->belongsTo(Subjects::class, 'prerequisite_id', 'id');
    }
}
