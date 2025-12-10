<?php

namespace App\Services;

use App\Base\BaseService;
use App\Models\Students;
use App\Models\User;
use App\Services\UserService;
use Core\Security\Hash;

class StudentService extends BaseService
{

    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        parent::__construct(Students::class);
        $this->userService = $userService;
    }

    public function createStudent(array $data)
    {
        $data['student_id'] = $this->generateStudentId();
        if (!empty($data['birthdate'])) {
           var_dump($data['birthdate']); $data['birthdate'] = date('Y-m-d', strtotime($data['birthdate']));
        }
        $student = $this->model->create($data);
        $this->createUserForStudent($student);
        return $student;
    }

    public function generateStudentId()
    {
        $year = date('y');
        $last = $this->model->where('student_id', 'LIKE', "s{$year}-%")
            ->orderBy('id', 'DESC')
            ->first();

        $lastNumber = $last ? intval(substr($last->student_id, -4)) : 0;
        $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        return "s{$year}-{$newNumber}";
    }

    protected function createUserForStudent($student)
    {
        $this->userService->register([
            'name' => $student->firstname . ' ' . $student->lastname,
            'email' => $student->student_id,
            'password' => $student->birthdate,
            'role' => 'student'
        ]);
    }

    public function getAllStudents()
    {
        return $this->model->all();
    }

    public function getLocationName($type, $code, $parentCode = null)
    {
        if (!$code) return null;
        $base = "https://psgc.gitlab.io/api/";

        switch ($type) {
            case 'region':
                $url = $base . "regions/$code/";
                break;
            case 'province':
                $url = $base . "provinces/$code/";
                break;
            case 'city':
                $url = $base . "cities-municipalities/$code/";
                break;
            case 'barangay':
                if (!$parentCode) return null;
                $url = $base . "cities-municipalities/$parentCode/barangays/";
                break;
            default:
                return null;
        }

        $json = @file_get_contents($url);
        if (!$json) return null;
        $data = json_decode($json, true);

        if ($type === 'barangay') {

            foreach ($data as $b) {
                if ($b['code'] == $code) return $b['name'];
            }
            return null;
        }

        return $data['name'] ?? null;
    }


    public function countStudents()
    {
        return $this->model->query()->count();
    }

    public function deleteStudent(int $id)
    {
        $student = $this->model->find($id);
        if (!$student) return false;
        $this->userService->deleteUser($student->student_id);
        return $student->delete();
    }

    public function showStudentList()
    {
        return $this->model->all();
    }
}

