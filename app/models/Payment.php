<?php
// app/models/Payment.php

class Payment
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare(
            "INSERT INTO payments (user_id, reference_type, reference_id, amount, method, transaction_ref, status, paid_at)
             VALUES (:user_id, :reference_type, :reference_id, :amount, :method, :transaction_ref, :status, :paid_at)"
        );
        $stmt->execute([
            'user_id'         => $data['user_id'],
            'reference_type'  => $data['reference_type'],
            'reference_id'    => $data['reference_id'],
            'amount'          => $data['amount'],
            'method'          => $data['method'],
            'transaction_ref' => $data['transaction_ref'],
            'status'          => $data['status'],
            'paid_at'         => $data['status'] === 'successful' ? date('Y-m-d H:i:s') : null,
        ]);
        return (int) $this->db->lastInsertId();
    }

    public function findById(int $id): array|false
    {
        $stmt = $this->db->prepare("SELECT * FROM payments WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function getByUser(int $userId): array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM payments WHERE user_id = :user_id ORDER BY created_at DESC"
        );
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchAll();
    }

    public function generateTransactionRef(): string
    {
        return 'DS' . strtoupper(bin2hex(random_bytes(5))) . time();
    }

        public function getAllForAdmin(string $statusFilter = ''): array
    {
        $sql = "SELECT p.*, u.full_name, u.email
                FROM payments p
                JOIN users u ON p.user_id = u.id";

        $params = [];
        if ($statusFilter !== '') {
            $sql .= " WHERE p.status = :status";
            $params['status'] = $statusFilter;
        }

        $sql .= " ORDER BY p.created_at DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getTotalRevenue(): float
    {
        $stmt = $this->db->query("SELECT SUM(amount) as total FROM payments WHERE status = 'successful'");
        $result = $stmt->fetch();
        return (float) ($result['total'] ?? 0);
    }
}