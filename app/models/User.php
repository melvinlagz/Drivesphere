<?php
// app/models/User.php

class User
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function findByEmail(string $email): array|false
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
        $stmt->execute(['email' => $email]);
        return $stmt->fetch();
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare(
            "INSERT INTO users (role_id, full_name, email, phone, password_hash)
             VALUES (:role_id, :full_name, :email, :phone, :password_hash)"
        );
        $stmt->execute([
            'role_id'       => $data['role_id'],
            'full_name'     => $data['full_name'],
            'email'         => $data['email'],
            'phone'         => $data['phone'] ?? null,
            'password_hash' => $data['password_hash'],
        ]);
        return (int) $this->db->lastInsertId();
    }

    public function findById(int $id): array|false
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }
        public function getAll(string $roleFilter = '', string $statusFilter = ''): array
    {
        $where = [];
        $params = [];

        if ($roleFilter !== '') {
            $where[] = "r.name = :role";
            $params['role'] = $roleFilter;
        }
        if ($statusFilter !== '') {
            $where[] = "u.status = :status";
            $params['status'] = $statusFilter;
        }

        $sql = "SELECT u.*, r.name AS role_name
                FROM users u
                JOIN roles r ON u.role_id = r.id";

        if (!empty($where)) {
            $sql .= " WHERE " . implode(' AND ', $where);
        }

        $sql .= " ORDER BY u.created_at DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function updateStatus(int $userId, string $status): void
    {
        $stmt = $this->db->prepare("UPDATE users SET status = :status WHERE id = :id");
        $stmt->execute(['status' => $status, 'id' => $userId]);
    }

    public function getCounts(): array
    {
        $stmt = $this->db->query(
            "SELECT r.name AS role_name, COUNT(u.id) AS total
             FROM users u JOIN roles r ON u.role_id = r.id
             GROUP BY r.name"
        );
        $rows = $stmt->fetchAll();

        $counts = ['customer' => 0, 'seller' => 0, 'admin' => 0];
        foreach ($rows as $row) {
            $counts[$row['role_name']] = (int) $row['total'];
        }
        return $counts;
    }

        public function updateProfile(int $userId, string $fullName, string $phone): void
    {
        $stmt = $this->db->prepare("UPDATE users SET full_name = :full_name, phone = :phone WHERE id = :id");
        $stmt->execute(['full_name' => $fullName, 'phone' => $phone, 'id' => $userId]);
    }

    public function updatePassword(int $userId, string $newPasswordHash): void
    {
        $stmt = $this->db->prepare("UPDATE users SET password_hash = :password_hash WHERE id = :id");
        $stmt->execute(['password_hash' => $newPasswordHash, 'id' => $userId]);
    }
}