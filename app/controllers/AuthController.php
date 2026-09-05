<?php
// app/controllers/AuthController.php

require_once BASE_PATH . '/app/models/User.php';
require_once BASE_PATH . '/app/helpers/functions.php';
require_once BASE_PATH . '/app/helpers/csrf.php';

class AuthController
{
    private PDO $db;
    private User $userModel;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->connect();
        $this->userModel = new User($this->db);
    }

    public function showRegister(): void
    {
        $csrfToken = generateCsrfToken();
        require BASE_PATH . '/app/views/auth/register.php';
    }

    public function register(): void
    {
        if (!verifyCsrfToken($_POST['csrf_token'] ?? null)) {
            die('Invalid CSRF token. Please refresh and try again.');
        }

        $fullName = sanitize($_POST['full_name'] ?? '');
        $email    = sanitize($_POST['email'] ?? '');
        $phone    = sanitize($_POST['phone'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirm  = $_POST['confirm_password'] ?? '';
        $roleId   = (int) ($_POST['role_id'] ?? 1); // 1 = customer by default

        $errors = [];

        if (strlen($fullName) < 2) {
            $errors[] = "Full name is required.";
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Valid email is required.";
        }
        if (strlen($password) < 8) {
            $errors[] = "Password must be at least 8 characters.";
        }
        if ($password !== $confirm) {
            $errors[] = "Passwords do not match.";
        }
        if ($this->userModel->findByEmail($email)) {
            $errors[] = "Email is already registered.";
        }

        if (!empty($errors)) {
            $csrfToken = generateCsrfToken();
            require BASE_PATH . '/app/views/auth/register.php';
            return;
        }

        $passwordHash = password_hash($password, PASSWORD_BCRYPT);

        $userId = $this->userModel->create([
            'role_id'       => $roleId,
            'full_name'     => $fullName,
            'email'         => $email,
            'phone'         => $phone,
            'password_hash' => $passwordHash,
        ]);

        // If registering as seller, create a seller record too
        if ($roleId === 2) {
            $stmt = $this->db->prepare(
                "INSERT INTO sellers (user_id, seller_type) VALUES (:user_id, 'individual')"
            );
            $stmt->execute(['user_id' => $userId]);
        }

        redirect('/login');
    }

    public function showLogin(): void
    {
        $csrfToken = generateCsrfToken();
        require BASE_PATH . '/app/views/auth/login.php';
    }

    public function login(): void
    {
        if (!verifyCsrfToken($_POST['csrf_token'] ?? null)) {
            die('Invalid CSRF token. Please refresh and try again.');
        }

        $email    = sanitize($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        $user = $this->userModel->findByEmail($email);

        if (!$user || !password_verify($password, $user['password_hash'])) {
            $errors = ["Invalid email or password."];
            $csrfToken = generateCsrfToken();
            require BASE_PATH . '/app/views/auth/login.php';
            return;
        }

        if ($user['status'] !== 'active') {
            $errors = ["Your account is not active. Please contact support."];
            $csrfToken = generateCsrfToken();
            require BASE_PATH . '/app/views/auth/login.php';
            return;
        }

        // Regenerate session ID to prevent session fixation
        session_regenerate_id(true);

        $_SESSION['user_id']    = $user['id'];
        $_SESSION['user_name']  = $user['full_name'];
        $_SESSION['user_email'] = $user['email'];

        // Map role_id to role name
        $roleNames = [1 => 'customer', 2 => 'seller', 3 => 'admin'];
        $_SESSION['user_role'] = $roleNames[$user['role_id']] ?? 'customer';

        switch ($_SESSION['user_role']) {
            case 'admin':
                redirect('/admin/dashboard');
                break;
            case 'seller':
                redirect('/seller/dashboard');
                break;
            default:
                redirect('/customer/dashboard');
        }
    }

    public function logout(): void
    {
        $_SESSION = [];
        session_destroy();
        redirect('/login');
    }
}