<?php
// app/models/Purchase.php

class Purchase
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare(
            "INSERT INTO purchases (car_id, customer_id, booking_fee, total_price, payment_type, status)
             VALUES (:car_id, :customer_id, :booking_fee, :total_price, :payment_type, 'pending')"
        );
        $stmt->execute([
            'car_id'       => $data['car_id'],
            'customer_id'  => $data['customer_id'],
            'booking_fee'  => $data['booking_fee'],
            'total_price'  => $data['total_price'],
            'payment_type' => $data['payment_type'],
        ]);
        return (int) $this->db->lastInsertId();
    }

    public function hasPendingOrActivePurchase(int $carId): bool
    {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) as total FROM purchases WHERE car_id = :car_id AND status IN ('pending','confirmed')"
        );
        $stmt->execute(['car_id' => $carId]);
        return (int) $stmt->fetch()['total'] > 0;
    }

    public function getByCustomer(int $customerId): array
    {
        $stmt = $this->db->prepare(
            "SELECT p.*, c.brand, c.model, c.year,
                    (SELECT image_path FROM car_images WHERE car_id = c.id AND is_primary = 1 LIMIT 1) AS primary_image
             FROM purchases p
             JOIN cars c ON p.car_id = c.id
             WHERE p.customer_id = :customer_id
             ORDER BY p.created_at DESC"
        );
        $stmt->execute(['customer_id' => $customerId]);
        return $stmt->fetchAll();
    }

        public function getAllForAdmin(): array
    {
        $stmt = $this->db->query(
            "SELECT p.*, c.brand, c.model, c.year, u.full_name AS customer_name,
                    su.full_name AS seller_name
             FROM purchases p
             JOIN cars c ON p.car_id = c.id
             JOIN users u ON p.customer_id = u.id
             JOIN sellers s ON c.seller_id = s.id
             JOIN users su ON s.user_id = su.id
             ORDER BY p.created_at DESC"
        );
        return $stmt->fetchAll();
    }

    public function getBySeller(int $sellerId): array
    {
        $stmt = $this->db->prepare(
            "SELECT p.*, c.brand, c.model, c.year, u.full_name AS customer_name, u.phone AS customer_phone,
                    (SELECT image_path FROM car_images WHERE car_id = c.id AND is_primary = 1 LIMIT 1) AS primary_image
             FROM purchases p
             JOIN cars c ON p.car_id = c.id
             JOIN users u ON p.customer_id = u.id
             WHERE c.seller_id = :seller_id
             ORDER BY p.created_at DESC"
        );
        $stmt->execute(['seller_id' => $sellerId]);
        return $stmt->fetchAll();
    }

    public function findByIdForSeller(int $purchaseId, int $sellerId): array|false
    {
        $stmt = $this->db->prepare(
            "SELECT p.* FROM purchases p
             JOIN cars c ON p.car_id = c.id
             WHERE p.id = :id AND c.seller_id = :seller_id LIMIT 1"
        );
        $stmt->execute(['id' => $purchaseId, 'seller_id' => $sellerId]);
        return $stmt->fetch();
    }

    public function updateStatus(int $purchaseId, string $status): void
    {
        $stmt = $this->db->prepare("UPDATE purchases SET status = :status WHERE id = :id");
        $stmt->execute(['status' => $status, 'id' => $purchaseId]);

        // If purchase is completed, mark the car as sold
        if ($status === 'completed') {
            $stmt = $this->db->prepare(
                "UPDATE cars SET availability_status = 'sold' WHERE id = (SELECT car_id FROM purchases WHERE id = :id)"
            );
            $stmt->execute(['id' => $purchaseId]);
        }
    }
        public function getSellerEarnings(int $sellerId): array
    {
        $stmt = $this->db->prepare(
            "SELECT SUM(total_price) as total_sales, COUNT(*) as completed_sales
             FROM purchases p JOIN cars c ON p.car_id = c.id
             WHERE c.seller_id = :seller_id AND p.status = 'completed'"
        );
        $stmt->execute(['seller_id' => $sellerId]);
        return $stmt->fetch();
    }
}