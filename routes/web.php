<?php

use App\Controllers\Admin\CurriculumController;
use App\Controllers\Admin\EnrollmentController;
use App\Controllers\Admin\ProgramController;
use App\Controllers\Admin\SectionController;
use App\Controllers\Admin\SubjectController;
use App\Controllers\AuthController;
use App\Controllers\DashboardController;
use App\Controllers\Student\StudentController;
use App\Controllers\SuperAdmin\DepartmentController;
use App\Controllers\SuperAdmin\UserController;

// Authentication Routes

$router->get('/', [AuthController::class, 'showLogin']);

$router->get('/register', [AuthController::class, 'showRegister']);
$router->post('/register', [AuthController::class, 'register']);
$router->post('/login', [AuthController::class, 'login']);
$router->get('/logout', [AuthController::class, 'logout']);
$router->get('/verify-otp', [AuthController::class, 'showVerifyOtp']);
$router->post('/verify-otp', [AuthController::class, 'verifyOtp']);

// Authenticated admin Routes
$router->get('/curriculum', [CurriculumController::class, 'curriculumPage']);
// programs Routes
$router->get('/program', [ProgramController::class, 'programPage']);
$router->post('/create-programs', [ProgramController::class, 'createProgram']);
$router->post('/program/delete/{id}', [ProgramController::class, 'deleteProgram']);

// Subjects Routes
$router->get('/subjects', [SubjectController::class, 'subjectsPage']);
$router->post('/create-subject', [SubjectController::class, 'createSubject']);

// sections Routes
$router->get('/sections', [SectionController::class, 'sectionPage']);
$router->post('/create-section', [SectionController::class, 'createSection']);
$router->post('/delete/section/{id}', [SectionController::class, 'deleteSection']);


// students Routes
$router->get('/students', [StudentController::class, 'AdminStudentPage']);
$router->get('/students/add-student', [StudentController::class, 'addStudentPage']);
$router->post('/students/create-student', [StudentController::class, 'createStudentAdmin']);
$router->get('/view/{id}', [StudentController::class, 'viewStudent']);
// Enrollments Routes 
$router->get('/enrollments', [EnrollmentController::class, 'enrollmentPage']);

// End Admin Routes

$router->get('/dashboard', [DashboardController::class, 'dashboard']);
$router->post('/student/delete/{id}', [StudentController::class, 'deleteStudent']);


// Student Routes
$router->get('/portal', [StudentController::class, 'studentPage']);


// Users List



// Super Admin
$router->get('/add-teacher', [UserController::class, 'showFormTeacher']);
$router->get('/department', [DepartmentController::class, 'showDepartmentList']);
$router->get('/users', [UserController::class, 'users']);
$router->post('/create-teacher', [UserController::class, 'createTeacher']);