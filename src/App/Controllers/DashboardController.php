<?php

namespace App\Controllers;

use App\Controllers\Base\AuthenticatedController;
use App\Services\StudentService;
use App\Services\UserService;
use Core\Controller\Controller;
use Core\Http\Request;
use Core\Http\Session;

class DashboardController extends AuthenticatedController
{
    protected UserService $userService;
    protected StudentService $studentService;
    protected int $studentCount;

    public function __construct(Request $request)
    {
        parent::__construct($request);

        $this->userService = new UserService();
        $this->studentService = new StudentService($this->userService);
    }


    public function dashboard()
    {
        $userId = Session::get('user_id');
        $student_count = $this->studentService->countStudents();
        if (!$userId) {
            header('Location: /');
            exit;
        }

        $user = $this->userService->findById($userId);
        $this->loadLayoutByRole($user->role);

        return $this->view($user->role . '/index', [
            'title' => 'Dashboard',
            'user' => $user,
            'student_count' => $student_count
        ]);
    }

    private function loadLayoutByRole(string $role): void
    {
        $layouts = [
            'supervisor' => 'supervisor',
            'admin' => 'admin',
            'teacher' => 'teacher',
            'student' => 'student',
            'parent' => 'parent'
        ];
        $layout = $layouts[$role] ?? 'main';
        $this->layout($layout);
    }
}
