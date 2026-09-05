<?php
// app/helpers/functions.php

function sanitize(string $input): string
{
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

function redirect(string $path): void
{
    header("Location: " . BASE_URL . $path);
    exit;
}

function isLoggedIn(): bool
{
    return isset($_SESSION['user_id']);
}

function getUserRole(): ?string
{
    return $_SESSION['user_role'] ?? null;
}

function requireLogin(): void
{
    if (!isLoggedIn()) {
        redirect('/login');
    }
}

function requireRole(string $role): void
{
    requireLogin();
    if (getUserRole() !== $role) {
        http_response_code(403);
        echo "403 - Forbidden. You don't have access to this page.";
        exit;
    }
}

function notify(int $userId, string $type, string $title, string $body = ''): void
{
    require_once BASE_PATH . '/app/models/Notification.php';
    $database = new Database();
    $db = $database->connect();
    $notification = new Notification($db);
    $notification->create($userId, $type, $title, $body);
}