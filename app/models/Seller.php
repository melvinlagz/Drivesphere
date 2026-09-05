<?php
// app/models/Seller.php

class Seller
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function getAll(string $verifiedFilter = ''): array
    {
        $sql = "SELECT s.*, u.full_name, u.email, u.phone, u.status AS user_status,
                    (SELECT COUNT(*) FROM cars WHERE seller_id = s.id) AS total_listings
                FROM sellers s
                JOIN users u ON s.user_id = u.id";

        $params = [];
        if ($verifiedFilter === 'verified') {
            $sql .= " WHERE s.is_verified = 1";
        } elseif ($verifiedFilter === 'unverified') {
            $sql .= " WHERE s.is_verified = 0";
        }

        $sql .= " ORDER BY s.created_at DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function toggleVerification(int $sellerId): void
    {
        $stmt = $this->db->prepare("UPDATE sellers SET is_verified = NOT is_verified WHERE id = :id");
        $stmt->execute(['id' => $sellerId]);
    }
}