<?php

namespace App\Controllers\SuperAdmin;

use App\Controllers\Base\AuthenticatedController;
use App\Services\UserService;
use Core\Http\Request;

class DepartmentController extends AuthenticatedController
{
    protected UserService $userService;
    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->userService = new UserService();

    }

    public function showDepartmentList()
    {
        return $this->view($this->currentUser->role . '/department', [
            'title' => "Department Management",
            'showTopbar' => false,
            'teachers'=> $this->userService->getTeacher()
        ]);
    }


    
}
