<?php

namespace App\Controllers;

use App\Services\OtpService;
use App\Services\UserService;
use Core\Controller\Controller;
use Core\Helpers\Helper;
use Core\Http\Request;
use Core\Http\Session;
use Core\Security\Hash;

class AuthController extends Controller
{
    protected UserService $service;
    protected OtpService $otpService;

    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->layout('auth');
        $this->service = new UserService();
        $this->otpService = new OtpService(new \Core\Mail\Mailer());
    }

    public function showLogin()
    {
        return $this->view('auth/login', ['title' => 'Login']);
    }


    public function login()
    {
        $data = $this->request->only(['email', 'password']);
        // var_dump($data) ;exit;
        $user = $this->service->findByEmail($data['email']);
        $input = trim($data['email']); // email or student_id
        $password = trim($data['password']);


        $user = $this->service->findByEmail($input);

        if (!$user) {
            $user = $this->service->getStudentById($input);
        }


        if (!$user || !Hash::verify($password, $user->password)) {
            Session::flash('error', 'Invalid email or password');
            return Helper::redirect('/');
        }


        Session::set('user_id', $user->id);
        Session::set('user_role', $user->role);

        $role = strtolower(trim($user->role));
        $rolesRequiringOtp = ['admin', 'supervisor', 'teacher'];
        if (in_array($role, $rolesRequiringOtp)) {
            $otp = $this->otpService->generateOtp($user->id);
            $this->otpService->sendOtp($user->email, $otp);
            return Helper::redirect('/verify-otp');
        }
        if (in_array($role, $rolesRequiringOtp)) {
            $otp = $this->otpService->generateOtp($user->id);
            $this->otpService->sendOtp($user->email, $otp);

            return Helper::redirect('/verify-otp');
        }


        switch ($role) {
            case 'student':
                return Helper::redirect('/portal');
            case 'parent':
                return Helper::redirect('/parent/dashboard');
            default:
                return Helper::redirect('/dashboard'); // fallback for others
        }
    }


    public function showRegister()
    {

        $errors = Session::getFlash('errors', []);
        $old = $_SESSION['_old_input'] ?? [];

        return $this->view('auth/register', [
            'title' => 'Register',
            'errors' => $errors,
            'old' => $old
        ]);
    }





    public function register()
    {
        Session::saveOldInput($this->request->all());

        $data = $this->request->only(['name', 'email', 'password']);
        $errors = [];

        if (!$data['name']) $errors['name'][] = "Name is required";
        if (!$data['email']) $errors['email'][] = "Email is required";
        if (!$data['password']) $errors['password'][] = "Password is required";


        if ($this->service->findByEmail($data['email'])) {
            $errors['email'][] = "Email is already taken";
        }

        if (!empty($errors)) {
            Session::flash('errors', value: $errors);
            return Helper::redirect('/register');
        }

        $this->service->register($data);
        Helper::clearOldInput();

        return Helper::redirect('/');
    }


    public function logout()
    {
        Session::remove('user_id');
        Session::remove('user_role');
        Session::remove('otp');
        Session::remove('otp_user_id');
        Session::remove('otp_expires');
        Session::remove('otp_attempts');

        return Helper::redirect('/');
    }


    public function showVerifyOtp()
    {
        $error = Session::getFlash('error', '');
        return $this->view('auth/verify-otp', [
            'title' => 'Verify OTP',
            'error' => $error
        ]);
    }

    public function verifyOtp()
    {
        $inputOtp = $this->request->post('otp');

        if (!$inputOtp) {
            Session::flash('error', 'OTP is required');
            return Helper::redirect('/verify-otp');
        }

        $userId = Session::get('otp_user_id');

        if (!$userId) {
            Session::flash('error', 'Session expired. Please login again.');
            return Helper::redirect('/login');
        }

        if ($this->otpService->validateOtp($inputOtp)) {
            $user = $this->service->findById((int)$userId);

            Session::set('user_id', $user->id);
            Session::set('user_role', $user->role);

            // Clear OTP session
            Session::forget('otp');
            Session::forget('otp_user_id');
            Session::forget('otp_expires');

            return Helper::redirect('/dashboard');
        }

        Session::flash('error', 'Invalid or expired OTP');
        return Helper::redirect('/verify-otp');
    }
}
