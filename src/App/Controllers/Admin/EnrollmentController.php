<?php

namespace App\Controllers\Admin;

use App\Controllers\Base\AuthenticatedController;
use App\Services\StudentService;
use App\Services\UserService;
use Core\Http\Request;

class EnrollmentController extends AuthenticatedController
{
    protected $studentService;
    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->studentService = new StudentService(new UserService());
    }

    public function enrollmentPage()
    {
        return $this->view(
            $this->currentUser->role . '/enrollments',
            [
                'title' => 'Enrollment Management',
                'showTopbar' => false,
            ]
        );
    }
}
