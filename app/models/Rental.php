<?php
// app/models/Rental.php

class Rental
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function getByCarId(int $carId): array|false
    {
        $stmt = $this->db->prepare("SELECT * FROM rentals WHERE car_id = :car_id LIMIT 1");
        $stmt->execute(['car_id' => $carId]);
        return $stmt->fetch();
    }

    public function upsertSettings(int $carId, array $data): int
    {
        $existing = $this->getByCarId($carId);

        if ($existing) {
            $stmt = $this->db->prepare(
                "UPDATE rentals SET security_deposit = :security_deposit, driver_option = :driver_option,
                    pickup_location = :pickup_location, return_location = :return_location, min_rental_days = :min_rental_days
                 WHERE car_id = :car_id"
            );
            $stmt->execute([
                'security_deposit' => $data['security_deposit'] ?: 0,
                'driver_option'    => $data['driver_option'] ?? 'self_drive',
                'pickup_location'  => $data['pickup_location'] ?: null,
                'return_location'  => $data['return_location'] ?: null,
                'min_rental_days'  => $data['min_rental_days'] ?: 1,
                'car_id'           => $carId,
            ]);
            return (int) $existing['id'];
        }

        $stmt = $this->db->prepare(
            "INSERT INTO rentals (car_id, security_deposit, driver_option, pickup_location, return_location, min_rental_days)
             VALUES (:car_id, :security_deposit, :driver_option, :pickup_location, :return_location, :min_rental_days)"
        );
        $stmt->execute([
            'car_id'           => $carId,
            'security_deposit' => $data['security_deposit'] ?: 0,
            'driver_option'    => $data['driver_option'] ?? 'self_drive',
            'pickup_location'  => $data['pickup_location'] ?: null,
            'return_location'  => $data['return_location'] ?: null,
            'min_rental_days'  => $data['min_rental_days'] ?: 1,
        ]);
        return (int) $this->db->lastInsertId();
    }

    public function hasOverlap(int $rentalId, string $startDate, string $endDate): bool
    {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) as total FROM rental_bookings
             WHERE rental_id = :rental_id
             AND status IN ('pending','confirmed','ongoing')
             AND start_date <= :end_date AND end_date >= :start_date"
        );
        $stmt->execute([
            'rental_id'  => $rentalId,
            'start_date' => $startDate,
            'end_date'   => $endDate,
        ]);
        return (int) $stmt->fetch()['total'] > 0;
    }

    public function createBooking(array $data): int
    {
        $stmt = $this->db->prepare(
            "INSERT INTO rental_bookings (
                rental_id, customer_id, start_date, end_date, total_days, total_price,
                deposit_paid, driver_requested, pickup_location, return_location, status
            ) VALUES (
                :rental_id, :customer_id, :start_date, :end_date, :total_days, :total_price,
                :deposit_paid, :driver_requested, :pickup_location, :return_location, 'pending'
            )"
        );
        $stmt->execute([
            'rental_id'        => $data['rental_id'],
            'customer_id'      => $data['customer_id'],
            'start_date'       => $data['start_date'],
            'end_date'         => $data['end_date'],
            'total_days'       => $data['total_days'],
            'total_price'      => $data['total_price'],
            'deposit_paid'     => 0,
            'driver_requested' => $data['driver_requested'] ? 1 : 0,
            'pickup_location'  => $data['pickup_location'] ?: null,
            'return_location'  => $data['return_location'] ?: null,
        ]);
        return (int) $this->db->lastInsertId();
    }

    public function getBookingsByCustomer(int $customerId): array
    {
        $stmt = $this->db->prepare(
            "SELECT rb.*, c.brand, c.model, c.year,
                    (SELECT image_path FROM car_images WHERE car_id = c.id AND is_primary = 1 LIMIT 1) AS primary_image
             FROM rental_bookings rb
             JOIN rentals r ON rb.rental_id = r.id
             JOIN cars c ON r.car_id = c.id
             WHERE rb.customer_id = :customer_id
             ORDER BY rb.created_at DESC"
        );
        $stmt->execute(['customer_id' => $customerId]);
        return $stmt->fetchAll();
    }

    public function getBookingsForSeller(int $sellerId): array
    {
        $stmt = $this->db->prepare(
            "SELECT rb.*, c.brand, c.model, c.year, u.full_name AS customer_name, u.phone AS customer_phone,
                    (SELECT image_path FROM car_images WHERE car_id = c.id AND is_primary = 1 LIMIT 1) AS primary_image
             FROM rental_bookings rb
             JOIN rentals r ON rb.rental_id = r.id
             JOIN cars c ON r.car_id = c.id
             JOIN users u ON rb.customer_id = u.id
             WHERE c.seller_id = :seller_id
             ORDER BY rb.created_at DESC"
        );
        $stmt->execute(['seller_id' => $sellerId]);
        return $stmt->fetchAll();
    }

    public function getBookingByIdForSeller(int $bookingId, int $sellerId): array|false
    {
        $stmt = $this->db->prepare(
            "SELECT rb.* FROM rental_bookings rb
             JOIN rentals r ON rb.rental_id = r.id
             JOIN cars c ON r.car_id = c.id
             WHERE rb.id = :booking_id AND c.seller_id = :seller_id LIMIT 1"
        );
        $stmt->execute(['booking_id' => $bookingId, 'seller_id' => $sellerId]);
        return $stmt->fetch();
    }

    public function updateBookingStatus(int $bookingId, string $status): void
    {
        $stmt = $this->db->prepare("UPDATE rental_bookings SET status = :status WHERE id = :id");
        $stmt->execute(['status' => $status, 'id' => $bookingId]);
    }

        public function getAllForAdmin(): array
    {
        $stmt = $this->db->query(
            "SELECT rb.*, c.brand, c.model, c.year, u.full_name AS customer_name,
                    su.full_name AS seller_name
             FROM rental_bookings rb
             JOIN rentals r ON rb.rental_id = r.id
             JOIN cars c ON r.car_id = c.id
             JOIN users u ON rb.customer_id = u.id
             JOIN sellers s ON c.seller_id = s.id
             JOIN users su ON s.user_id = su.id
             ORDER BY rb.created_at DESC"
        );
        return $stmt->fetchAll();
    }
        public function getSellerEarnings(int $sellerId): array
    {
        $stmt = $this->db->prepare(
            "SELECT SUM(rb.total_price) as total_rental_income, COUNT(*) as completed_rentals
             FROM rental_bookings rb
             JOIN rentals r ON rb.rental_id = r.id
             JOIN cars c ON r.car_id = c.id
             WHERE c.seller_id = :seller_id AND rb.status = 'completed'"
        );
        $stmt->execute(['seller_id' => $sellerId]);
        return $stmt->fetch();
    }
}