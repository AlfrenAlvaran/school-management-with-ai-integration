<?php

namespace Core\Http;

class Session
{
    public static function start()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function get(string $key, $default = null)
    {
        return $_SESSION[$key] ?? $default;
    }

    public static function put(string $key, $value): void
    {
        $_SESSION[$key] = $value;
    }

    public static function forget(string $key): void
    {
        unset($_SESSION[$key]);
    }

    public static function flash(string $key, $value): void
    {
        $_SESSION['_flash'][$key] = $value;
    }

    public static function getFlash(string $key, $default = null)
    {
        if (isset($_SESSION['_flash'][$key])) {
            $value = $_SESSION['_flash'][$key];
            unset($_SESSION['_flash'][$key]);
            return $value;
        }
        return $default;
    }

    public static function deleteFlash(): void
    {
        if (isset($_SESSION['_flash'])) {
            unset($_SESSION['_flash']);
        }
    }

    // Called automatically at end of request
    public static function clearFlashIfNeeded()
    {
        if (!empty($_SESSION['_flash'])) {
            self::deleteFlash();
        }
    }

    public static function saveOldInput(array $data)
    {
        $_SESSION['_old_input'] = $data;
    }

    public static function clearOldInput()
    {
        unset($_SESSION['_old_input']);
    }


    public static function set(string $key, $value): void
    {
        self::put($key, $value);
    }

    public static function remove(string $key): void
    {
        self::forget($key);
    }
    public static function csrfToken(): string
    {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    public static function validateCsrfToken($token): bool
    {
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }
}
