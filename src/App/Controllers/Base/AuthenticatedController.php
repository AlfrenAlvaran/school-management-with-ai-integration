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
    protected object $currentUser;
    protected string $layout = 'main';

    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->service = new UserService();
        $this->authenticate();
    }

    private function authenticate(): void
    {
        Session::start();

        $userId = Session::get('user_id');

        if (!$userId || !is_numeric($userId)) {
            $this->forceLogout();
        }

        $user = $this->service->findById((int) $userId);

        if (!$user || !isset($user->role)) {
            $this->forceLogout();
        }

        // Prevent session fixation
        if (!Session::get('_regenerated')) {
            session_regenerate_id(true);
            Session::put('_regenerated', true);
        }

        $this->currentUser = $user;
    }

    protected function view(string $name, array $data = []): void
    {
        $this->setLayoutByRole($this->currentUser->role);
        $data['user'] = $this->currentUser;

        $views = new Views();
        $views->layout($this->layout);
        $views->render($name, $data);
    }

    private function setLayoutByRole(string $role): void
    {
        $this->layout = match ($role) {
            'supervisor' => 'supervisor',
            'admin'      => 'admin',
            'teacher'    => 'teacher',
            'student'    => 'student',
            'parent'     => 'parent',
            default      => 'main'
        };
    }

    protected function authorize(array $roles): void
    {
        if (!in_array($this->currentUser->role, $roles, true)) {
            Session::flash('error', 'Unauthorized access.');
            Helper::redirect('/403');
            exit;
        }
    }

    private function forceLogout(): void
    {
        Session::destroy();
        Session::flash('error', 'Your session has expired. Please login again.');
        Helper::redirect('/');
        exit;
    }
}
