<?php

namespace App\Models;

use Core\Models\Model;

class Programs extends Model
{
    protected string $table = 'programs';

    protected array $fillable = [
        'code',
        'description'
    ];

    protected bool $timestamps = true;
}
