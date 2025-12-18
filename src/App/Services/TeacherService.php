<?php

namespace App\Services;

use App\Base\BaseService;
use App\Models\TeacherInformation;

class TeacherService extends BaseService
{
    protected UserService $userService;
    public function __construct(UserService $userService)
    {
        parent::__construct(TeacherInformation::class);
        $this->userService = $userService;
    }

    public function createTeacher(array $data)
    {
        if (empty($data['email'])) {
            throw new \InvalidArgumentException('Email is required');
        }

        $teacher = $this->model->create($data);
        $this->creteUserTeacherAccount($teacher, $data['role']);

        return $teacher;
    }


    public function creteUserTeacherAccount($data ,$role)
    {
        $this->userService->register(
            array(
                'name' => "{$data->firstname} {$data->middlename} {$data->lastname}",
                'email' => $data->email,
                'password' => $data->birthdate,
                'role' => $role
            )
        );
    }
}
