<?php

namespace App\Models;

use Core\Models\Model;

class User extends Model {
    protected string $table = 'users';

    protected array $fillable = [
        'name',
        'email',
        'password',
        'role'
    ];

    protected bool $timestamps = true;
}