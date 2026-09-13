<?php

declare(strict_types=1);

function require_login(): void
{
    if (!current_user()) {
        $_SESSION['after_login'] = $_SERVER['REQUEST_URI'] ?? 'index.php';
        redirect('login.php');
    }
}

function login_user(array $user): void
{
    session_regenerate_id(true);
    $_SESSION['user'] = [
        'id' => (int) $user['id'],
        'email' => $user['email'],
        'name' => $user['name'],
        'avatar_url' => $user['avatar_url'] ?? null,
    ];
}

function after_login_location(): string
{
    $location = $_SESSION['after_login'] ?? 'index.php';
    unset($_SESSION['after_login']);
    return str_starts_with($location, '/') ? 'index.php' : $location;
}