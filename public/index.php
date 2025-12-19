<?php

use Core\Http\Kernel;
use Core\Http\Request;
use Core\Http\Response;
use Core\Router\Router;
use Dotenv\Dotenv;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require __DIR__ . "/../vendor/autoload.php";

// Load .env
$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->safeLoad();

// Capture request
$request = Request::capture();

// Initialize Router
$router = new Router();

// Load Routes
require __DIR__ . "/../routes/web.php";

// Initialize Kernel for global middleware
$kernel = new Kernel();

// Handle middleware
$response = $kernel->handle($request);


if ($response instanceof Response) {
    $response->send();
    exit;
}


$controllerResponse = $router->dispatch($request);

if ($controllerResponse instanceof Response) {
    $controllerResponse->send();
    exit;
}