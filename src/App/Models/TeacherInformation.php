<?php

namespace App\Models;

use Core\Models\Model;

class TeacherInformation extends Model
{
    protected $table = 'teacher_information';

    protected array $fillable = [
        'user_id',
        'department_id',
        'position',
        'specialization',
        'employment_status',
        'date_hired',
    ];

    protected $timestamps = true;

    
}
