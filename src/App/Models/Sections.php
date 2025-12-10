<?php

namespace App\Models;

use Core\Models\Model;


class Sections extends Model 
{
    protected string $table = 'sections';

    protected array $fillable = [
        "program_id",
        "year_level",
        "section_code",
        "capacity",
    ];

    protected bool $timestamps = true;

    public function program() {
        return $this->belongsTo(Programs::class, 'program_id', 'id');
    }
}