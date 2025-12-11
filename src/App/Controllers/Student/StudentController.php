<?php

namespace App\Controllers\Student;

use App\Controllers\Base\AuthenticatedController;
use App\Services\StudentService;
use App\Services\UserService;
use Core\Helpers\Helper;
use Core\Http\Request;
use Core\Http\Session;

class StudentController extends AuthenticatedController
{
    protected StudentService $studentService;

    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->studentService = new StudentService(new UserService());
    }

    public function studentPage()
    {
        return $this->view($this->currentUser->role . '/index', [
            'title' => 'Student Portal',
            'showTopbar' => false
        ]);
    }

    public function AdminStudentPage()
    {
        return $this->view('admin/students', [
            'title'     => 'Student Management',
            'showTopbar'=> false,
            'students'  => $this->studentService->getAllStudents()
        ]);
    }

    public function addStudentPage()
    {
        return $this->view('admin/form_student', [
            'title' => 'Student Form',
            'showTopbar' => false
        ]);
    }

    public function createStudentAdmin()
    {
        $data = $this->request->only([
            'firstname','lastname','middlename','email','contact',
            'region','province','city','barangay','address',
            'birthdate','sex',

            'parent_firstname','parent_lastname','parent_middlename',
            'parent_contact','parent_occupation',
            'parent_region','parent_province','parent_city',
            'parent_barangay','parent_house_no',

            'same_as_student'
        ]);

        // Set readable names
        $data['region_name']   = $this->studentService->getLocationName('region', $data['region']);
        $data['province_name'] = $this->studentService->getLocationName('province', $data['province']);
        $data['city_name']     = $this->studentService->getLocationName('city', $data['city']);
        $data['barangay_name'] = $this->studentService->getLocationName('barangay', $data['barangay'], $data['city']);

        if (!empty($data['birthdate'])) {
            $data['birthdate'] = date('Y-m-d', strtotime($data['birthdate']));
        }

        $this->studentService->createStudent($data);

        return Helper::redirect('/students');
    }

    public function deleteStudent($id)
    {
        if (!Session::validateCsrfToken($this->request->post('csrf_token'))) {
            Session::flash('error', 'Invalid CSRF token.');
            return Helper::redirect('/students');
        }

        if ($this->studentService->deleteStudent((int)$id)) {
            Session::flash('success', 'Student deleted successfully.');
        } else {
            Session::flash('error', 'Student not found.');
        }

        return Helper::redirect('/students');
    }

    public function viewStudent($id)
    {
        $student = $this->studentService->getStudentById((int)$id);

        if (!$student) {
            Session::flash('error', 'Student not found.');
            return Helper::redirect('/students');
        }

        return $this->view('admin/view_student', [
            'title' => 'View Student',
            'showTopbar' => false,
            'student' => $student
        ]);
    }

    
}
