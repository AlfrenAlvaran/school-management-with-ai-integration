<?php

namespace App\Controllers\SuperAdmin;

use App\Controllers\Base\AuthenticatedController;
use App\Services\UserService;
use Core\Http\Request;

class UserController extends AuthenticatedController
{
    protected UserService $userService;
    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->userService = new UserService();
    }


    public function users()
    {
        return $this->view($this->currentUser->role . '/users', [
            'title' => "Users",
            'showTopbar' => false,
            'users' => $this->userService->getAllUsers(),
        ]);
    }

    public function createUser() {
        
    }
}
