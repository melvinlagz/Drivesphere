<?php
// app/controllers/ProfileController.php

require_once BASE_PATH . '/app/helpers/functions.php';
require_once BASE_PATH . '/app/helpers/csrf.php';
require_once BASE_PATH . '/app/models/User.php';

class ProfileController
{
    private PDO $db;
    private User $userModel;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->connect();
        $this->userModel = new User($this->db);
    }

    public function showProfile(): void
    {
        requireLogin();
        $user = $this->userModel->findById($_SESSION['user_id']);
        $csrfToken = generateCsrfToken();
        require BASE_PATH . '/app/views/profile/profile.php';
    }

    public function updateProfile(): void
    {
        requireLogin();

        if (!verifyCsrfToken($_POST['csrf_token'] ?? null)) {
            die('Invalid CSRF token.');
        }

        $fullName = sanitize($_POST['full_name'] ?? '');
        $phone = sanitize($_POST['phone'] ?? '');

        if ($fullName === '') {
            die('Full name is required.');
        }

        $this->userModel->updateProfile($_SESSION['user_id'], $fullName, $phone);
        $_SESSION['user_name'] = $fullName;

        redirect('/profile?updated=1');
    }

    public function showSettings(): void
    {
        requireLogin();
        $csrfToken = generateCsrfToken();
        require BASE_PATH . '/app/views/profile/settings.php';
    }

    public function updatePassword(): void
    {
        requireLogin();

        if (!verifyCsrfToken($_POST['csrf_token'] ?? null)) {
            die('Invalid CSRF token.');
        }

        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        $user = $this->userModel->findById($_SESSION['user_id']);

        $errors = [];

        if (!password_verify($currentPassword, $user['password_hash'])) {
            $errors[] = "Current password is incorrect.";
        }
        if (strlen($newPassword) < 8) {
            $errors[] = "New password must be at least 8 characters.";
        }
        if ($newPassword !== $confirmPassword) {
            $errors[] = "New passwords do not match.";
        }

        if (!empty($errors)) {
            $csrfToken = generateCsrfToken();
            require BASE_PATH . '/app/views/profile/settings.php';
            return;
        }

        $newHash = password_hash($newPassword, PASSWORD_BCRYPT);
        $this->userModel->updatePassword($_SESSION['user_id'], $newHash);

        redirect('/settings?updated=1');
    }
}