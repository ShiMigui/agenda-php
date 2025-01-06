<?php
class Auth
{
    public static function isLogged(): bool
    {
        return isset($_SESSION['login_cd']);
    }

    public static function login(int $cd): void
    {
        $_SESSION['login_cd'] = $cd;
    }

    public static function logout(): void
    {
        unset($_SESSION['login_cd']);
        session_destroy();
    }

    public static function getLogin(): ?int
    {
        return $_SESSION['login_cd'] ?? null;
    }
}
