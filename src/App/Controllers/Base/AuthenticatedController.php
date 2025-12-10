<?php

namespace App\Controllers\Base;

use App\Services\UserService;
use Core\Controller\Controller;
use Core\Helpers\Helper;
use Core\Http\Request;
use Core\Http\Session;
use Core\Views\Views;

abstract class AuthenticatedController extends Controller
{
    protected UserService $service;
    protected $currentUser;
    protected string $layout = 'main';

    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->service = new UserService();
        $this->requireAuthentication();
    }

    public function requireAuthentication(): void
    {
        $userId = Session::get('user_id');

        if (!$userId) {
            Helper::redirect('/');
            exit;
        }

        $this->currentUser = $this->service->findById($userId);
    }

    protected function view(string $name, array $data = [])
    {
        // Determine layout dynamically based on user role
        $this->layoutByRole($this->currentUser->role);

        // Pass current user to view
        $data['user'] = $this->currentUser;

        // Render view with your Views class
        $views = new Views();
        $views->layout($this->layout);  // set layout
        $views->render($name, $data);
    }

    private function layoutByRole(string $role): void
    {
        $layouts = [
            'supervisor' => 'supervisor',
            'admin'      => 'admin',
            'teacher'    => 'teacher',
            'student'    => 'student',
            'parent'     => 'parent'
        ];

        $this->layout = $layouts[$role] ?? 'main';
    }
}
