<?php

namespace App\Services;

use App\Base\BaseService;
use App\Models\Parents;
use App\Models\Students;
use App\Services\UserService;

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
            $data['birthdate'] = date('Y-m-d', strtotime($data['birthdate']));
        }

        $this->normalizeParentFields($data);

        $student = $this->model->create($data);

        $this->createUserForStudent($student);
        $this->createParentRecord($student, $data);

        return $student;
    }

    private function normalizeParentFields(&$data)
    {
        $parentFields = [
            'parent_firstname',
            'parent_lastname',
            'parent_middlename',
            'parent_contact',
            'parent_occupation',
            'parent_region',
            'parent_province',
            'parent_city',
            'parent_barangay',
            'parent_house_no'
        ];

        foreach ($parentFields as $field) {
            $data[$field] = $data[$field] ?? null;
        }
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
            'name'     => "{$student->firstname} {$student->lastname}",
            'email'    => $student->student_id,
            'password' => $student->birthdate,
            'role'     => 'student'
        ]);
    }

    private function createParentRecord($student, array $data)
    {
        if (!empty($data['same_as_student'])) {
            $data['parent_region']   = $data['region'] ?? '';
            $data['parent_province'] = $data['province'] ?? '';
            $data['parent_city']     = $data['city'] ?? '';
            $data['parent_barangay'] = $data['barangay'] ?? '';
            $data['parent_house_no'] = $data['address'] ?? '';
        }

        Parents::create([
            'student_id'     => $student->id,
            'first_name'     => $data['parent_firstname'] ?? '',
            'last_name'      => $data['parent_lastname'] ?? '',
            'middle_name'    => $data['parent_middlename'] ?? '',
            'contact_number' => $data['parent_contact'] ?? '',
            'occupation'     => $data['parent_occupation'] ?? '',

            'region_code'    => $data['parent_region'] ?? '',
            'province_code'  => $data['parent_province'] ?? '',
            'city_code'      => $data['parent_city'] ?? '',
            'barangay_code'  => $data['parent_barangay'] ?? '',
            'house_no'       => $data['parent_house_no'] ?? '',

            'region'   => $this->getLocationName('region', $data['parent_region']) ?? 'N/A',
            'province' => $this->getLocationName('province', $data['parent_province']) ?? 'N/A',
            'city'     => $this->getLocationName('city', $data['parent_city']) ?? 'N/A',
            'barangay' => $data['parent_barangay'] ?
                $this->getLocationName('barangay', $data['parent_barangay'], $data['parent_city']) : 'N/A',
        ]);
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
        Parents::where('student_id', '=', $id)->delete();

        return $student->delete();
    }

    public function getAllStudents()
    {
        return $this->model->all();
    }

    public function getStudentById(int $id)
    {
        return $this->model->find($id);
    }

    public function getStudentInfo(int $id)
    {
        $student = $this->model->find($id);  

        if(!$student) return null;

        $parent = Parents::where("student_id" ,"=", $id)->first();

        return [
            'student' => $student,
            "parent" => $parent,
        ];
    }
}
