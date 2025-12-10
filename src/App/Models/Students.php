<?php

namespace App\Models;

use Core\Models\Model;

class Students extends Model
{
    protected string $table = 'students';

    protected array $fillable = [
        'student_id',
        'firstname',
        'lastname',
        'middlename',
        'email',
        'contact',
        'birthdate',
        'sex',
        'address',
        'region',
        'province',
        'city',
        'barangay',
        'region_name',
        'province_name',
        'city_name',
        'barangay_name'
    ];

    protected bool $timestamps = true;
}
