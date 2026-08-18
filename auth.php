<?php
/**
 * Helper otentikasi berbasis session.
 */

function current_user(): ?array
{
    return $_SESSION['user'] ?? null;
}

function require_login(?string $role = null): void
{
    $user = current_user();

    if (!$user) {
        redirect('/auth/login.php');
    }

    if ($role !== null && $user['role'] !== $role) {
        redirect($user['role'] === 'admin' ? '/admin/dashboard.php' : '/warga/dashboard.php');
    }
}

function login_user(array $row): void
{
    $_SESSION['user'] = [
        'id'    => $row['id'],
        'role'  => $row['role'],
        'nama'  => $row['nama'],
        'email' => $row['email'],
    ];
}
