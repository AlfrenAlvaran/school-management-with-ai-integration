<?php

namespace App\Controllers\SuperAdmin;

use App\Controllers\Base\AuthenticatedController;
use App\Services\TeacherService;
use App\Services\UserService;
use Core\Helpers\Helper;
use Core\Http\Request;

class UserController extends AuthenticatedController
{
    protected TeacherService $teacherService;
    protected UserService $userService;

    public function __construct(Request $request)
    {
        parent::__construct($request);

        $this->userService = new UserService();
        $this->teacherService = new TeacherService($this->userService);
    }

    public function users()
    {
        return $this->view($this->currentUser->role . '/users', [
            'title' => "Users",
            'showTopbar' => false,
            'users' => $this->userService->getAllUsers(),
        ]);
    }

    public function showFormTeacher()
    {
        return $this->view(
            $this->currentUser->role . '/teacher_form',
            [
                'title' => "Teacher Information",
                'showTopbar' => false,

            ]
        );
    }

    public function createUser()
    {
        $data = $this->request->only([
            'name',
            'email',
            'password',
        ]);

        $this->teacherService->createTeacher($data);

        Helper::redirect('/users', 201);
    }

    // public function createTeacher()
    // {
    //     $data = $this->request->only([
    //         'firstname',
    //         'middlename',
    //         'lastname',
    //         'email',
    //         'birthdate',
    //         'department',
    //         'role',
    //     ]);

    //     $this->teacherService->createTeacher($data);

    //     return Helper::redirect('/users', 201);
    // }


    public function createTeacher()
    {
        $data = $this->request->only(
            array(
                'firstname',
                'lastname',
                'email',
                'birthdate',
                'department',
                'role',
                'position',
                'specialization',
                'employment_status'
            )
        );

        $this->teacherService->createTeacher($data);
        return Helper::redirect('/users');
    }
}
