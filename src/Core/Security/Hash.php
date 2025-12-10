<?php

namespace Core\Security;

class Hash
{
    protected const COST = 12;

    public static function make(string $password): string
    {
        return password_hash($password, PASSWORD_BCRYPT, [
            'cost' => self::COST,
        ]);
    }

    public static function verify(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }

    public static function needsRehash(string $hash): bool
    {
        return password_needs_rehash($hash, PASSWORD_BCRYPT, [
            'cost' => self::COST,
        ]);
    }

    public static function secureCompare(string $a, string $b): bool
    {
        if (strlen($a) !== strlen($b)) {
            return false;
        }
        return hash_equals($a, $b);
    }
}
