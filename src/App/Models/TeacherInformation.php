<?php

namespace App\Models;

use Core\Models\Model;

class TeacherInformation extends Model
{
    protected string $table = 'teacher_information';

    protected array $fillable = [
        'firstname',
        'lastname',
        'middlename',
        'gender',
        'email',
        'department_id',
        'position',
        'specialization',
        'employment_status',
        'date_hired',
    ];

    protected bool $timestamps = true;

    
}
