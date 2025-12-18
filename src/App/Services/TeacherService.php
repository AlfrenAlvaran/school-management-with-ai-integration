<?php

namespace App\Services;

use App\Base\BaseService;
use App\Models\TeacherInformation;

class TeacherService extends BaseService
{
    public function __construct()
    {
        parent::__construct(TeacherInformation::class);
    }

    public function createTeacher() 
    {
        
    }


}
