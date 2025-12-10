<?php

namespace Core\Helpers;

use Core\Http\Response;
use Core\Http\Session;

class Helper
{
    public static function redirect(string $url, int $status = 302): Response
    {
        return (new Response())->redirect($url, $status);
    }

    public static function flash(string $key, $value = null)
    {
        Session::flash($key, $value);
    }

    public static function old(string $key, $default = null)
    {
        return $_SESSION['_old_input'][$key] ?? $default;
    }

    public static function errors(): array
    {
        $errors = Session::getFlash('errors', []);
        Session::deleteFlash(); 
        return $errors;
    }

    public static function clearOldInput(): void
    {
        Session::clearOldInput();
    }
}
