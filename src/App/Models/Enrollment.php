<?php

namespace App\Models;

use Core\Models\Model;

class Enrollment extends Model 
{
    protected $table = 'enrollments';

    protected array $fillable = [
        'student_id',
        'program_id',
        'year_level',
        'section_id',
        'semester',
        'enrollment_date',
        'status',
        'date_enrolled'
    ];

    protected bool $timestamps = true;

}