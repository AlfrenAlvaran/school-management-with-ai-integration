<?php

namespace App\Services;

use App\Base\BaseService;
use App\Models\Enrollment;

class EnrollmentService extends BaseService
{
    public function __construct()
    {
        parent::__construct(Enrollment::class);
    }


    public function enrollStudent(array $data)
    {
        return $this->model->create($data);
    }

    public function showEnrollmentList()
    {
        return $this->model->all();
    }
}
