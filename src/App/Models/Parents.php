<?php

namespace App\Models;

use Core\Models\Model;

class Parents extends Model
{
    protected string $table = 'parents';

    protected array $fillable = [
        'student_id',
        'first_name',
        'last_name',
        'middle_name',
        'contact_number',
        'occupation',
        'region_code',
        'province_code',
        'city_code',
        'barangay_code',
        'region',
        'province',
        'city',
        'barangay',
        'house_no'
    ];

    protected bool $timestamps = true;

    public function student()
    {
        return $this->belongsTo(Students::class, 'student_id', 'student_id');
    }
}
