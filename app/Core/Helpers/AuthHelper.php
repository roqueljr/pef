<?php
namespace App\Core\Helpers;

class Auth
{
    public static function check(): bool
    {
        return isset($_SESSION['user']);
    }

    public static function user()
    {
        return $_SESSION['user'] ?? null;
    }

    public static function id()
    {
        return $_SESSION['user']['id'] ?? null;
    }
}