<?php
// app/models/Review.php

class Review
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function hasCompletedTransaction(int $userId, int $carId): bool
    {
        // Check completed rental
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) as total FROM rental_bookings rb
             JOIN rentals r ON rb.rental_id = r.id
             WHERE r.car_id = :car_id AND rb.customer_id = :user_id AND rb.status = 'completed'"
        );
        $stmt->execute(['car_id' => $carId, 'user_id' => $userId]);
        if ((int) $stmt->fetch()['total'] > 0) {
            return true;
        }

        // Check completed purchase
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) as total FROM purchases WHERE car_id = :car_id AND customer_id = :user_id AND status = 'completed'"
        );
        $stmt->execute(['car_id' => $carId, 'user_id' => $userId]);
        return (int) $stmt->fetch()['total'] > 0;
    }

    public function hasAlreadyReviewed(int $userId, int $carId): bool
    {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) as total FROM reviews WHERE car_id = :car_id AND user_id = :user_id"
        );
        $stmt->execute(['car_id' => $carId, 'user_id' => $userId]);
        return (int) $stmt->fetch()['total'] > 0;
    }

    public function create(int $userId, int $carId, int $rating, string $comment): int
    {
        $stmt = $this->db->prepare(
            "INSERT INTO reviews (car_id, user_id, rating, comment) VALUES (:car_id, :user_id, :rating, :comment)"
        );
        $stmt->execute([
            'car_id'  => $carId,
            'user_id' => $userId,
            'rating'  => $rating,
            'comment' => $comment,
        ]);
        return (int) $this->db->lastInsertId();
    }

    public function getByCarId(int $carId): array
    {
        $stmt = $this->db->prepare(
            "SELECT r.*, u.full_name
             FROM reviews r
             JOIN users u ON r.user_id = u.id
             WHERE r.car_id = :car_id AND r.status = 'visible'
             ORDER BY r.created_at DESC"
        );
        $stmt->execute(['car_id' => $carId]);
        return $stmt->fetchAll();
    }

    public function getAverageRating(int $carId): array
    {
        $stmt = $this->db->prepare(
            "SELECT AVG(rating) as avg_rating, COUNT(*) as total_reviews
             FROM reviews WHERE car_id = :car_id AND status = 'visible'"
        );
        $stmt->execute(['car_id' => $carId]);
        $result = $stmt->fetch();
        return [
            'avg_rating'    => $result['avg_rating'] ? round((float) $result['avg_rating'], 1) : 0,
            'total_reviews' => (int) $result['total_reviews'],
        ];
    }

    public function reportReview(int $reviewId): void
    {
        $stmt = $this->db->prepare("UPDATE reviews SET is_reported = 1 WHERE id = :id");
        $stmt->execute(['id' => $reviewId]);
    }

    public function getReviewableTransactions(int $userId): array
    {
        // Returns cars the customer completed a transaction with but hasn't reviewed yet
        $stmt = $this->db->prepare(
            "SELECT DISTINCT c.id, c.brand, c.model, c.year,
                    (SELECT image_path FROM car_images WHERE car_id = c.id AND is_primary = 1 LIMIT 1) AS primary_image
             FROM cars c
             WHERE c.id IN (
                SELECT r.car_id FROM rental_bookings rb
                JOIN rentals r ON rb.rental_id = r.id
                WHERE rb.customer_id = :user_id1 AND rb.status = 'completed'
                UNION
                SELECT p.car_id FROM purchases p
                WHERE p.customer_id = :user_id2 AND p.status = 'completed'
             )
             AND c.id NOT IN (
                SELECT car_id FROM reviews WHERE user_id = :user_id3
             )"
        );
        $stmt->execute(['user_id1' => $userId, 'user_id2' => $userId, 'user_id3' => $userId]);
        return $stmt->fetchAll();
    }
}