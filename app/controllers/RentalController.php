<?php
// app/controllers/RentalController.php

require_once BASE_PATH . '/app/helpers/functions.php';
require_once BASE_PATH . '/app/helpers/csrf.php';
require_once BASE_PATH . '/app/models/Rental.php';
require_once BASE_PATH . '/app/models/Car.php';

class RentalController
{
    private PDO $db;
    private Rental $rentalModel;
    private Car $carModel;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->connect();
        $this->rentalModel = new Rental($this->db);
        $this->carModel = new Car($this->db);
    }

    public function bookNow(): void
    {
        requireRole('customer');

        if (!verifyCsrfToken($_POST['csrf_token'] ?? null)) {
            die('Invalid CSRF token.');
        }

        $carId = (int) ($_POST['car_id'] ?? 0);
        $startDate = $_POST['start_date'] ?? '';
        $endDate = $_POST['end_date'] ?? '';
        $driverRequested = isset($_POST['driver_requested']) ? 1 : 0;
        $pickupLocation = sanitize($_POST['pickup_location'] ?? '');
        $returnLocation = sanitize($_POST['return_location'] ?? '');

        $car = $this->carModel->findById($carId);
        $rental = $this->rentalModel->getByCarId($carId);

        if (!$car || !$rental) {
            die('This vehicle is not available for rent.');
        }

        $start = DateTime::createFromFormat('Y-m-d', $startDate);
        $end = DateTime::createFromFormat('Y-m-d', $endDate);
        $today = new DateTime('today');

        if (!$start || !$end || $start < $today || $end <= $start) {
            die('Invalid rental dates. End date must be after start date, and start date cannot be in the past.');
        }

        $totalDays = (int) $start->diff($end)->days;

        if ($totalDays < (int) $rental['min_rental_days']) {
            die("Minimum rental period is {$rental['min_rental_days']} day(s).");
        }

        if ($this->rentalModel->hasOverlap((int) $rental['id'], $startDate, $endDate)) {
            die('This vehicle is already booked for part of the selected dates. Please choose different dates.');
        }

        $totalPrice = $totalDays * (float) $car['rental_price_per_day'];

        // Notify the seller of a new booking request
        $stmt = $this->db->prepare(
            "SELECT u.id FROM sellers s JOIN users u ON s.user_id = u.id
             JOIN cars c ON c.seller_id = s.id WHERE c.id = :car_id"
        );
        $stmt->execute(['car_id' => $carId]);
        $sellerUser = $stmt->fetch();
        if ($sellerUser) {
            notify((int) $sellerUser['id'], 'booking', 'New Rental Request', "You have a new rental request for {$car['brand']} {$car['model']}.");
        }

        $bookingId = $this->rentalModel->createBooking([
            'rental_id'        => $rental['id'],
            'customer_id'      => $_SESSION['user_id'],
            'start_date'       => $startDate,
            'end_date'         => $endDate,
            'total_days'       => $totalDays,
            'total_price'      => $totalPrice,
            'driver_requested' => $driverRequested,
            'pickup_location'  => $pickupLocation ?: $rental['pickup_location'],
            'return_location'  => $returnLocation ?: $rental['return_location'],
        ]);
        // Notify the seller of a new booking request
        $stmt = $this->db->prepare(
            "SELECT u.id FROM sellers s JOIN users u ON s.user_id = u.id
             JOIN cars c ON c.seller_id = s.id WHERE c.id = :car_id"
        );
        $stmt->execute(['car_id' => $carId]);
        $sellerUser = $stmt->fetch();
        if ($sellerUser) {
            notify((int) $sellerUser['id'], 'booking', 'New Rental Request', "You have a new rental request for {$car['brand']} {$car['model']}.");
        }
        redirect('/customer/rentals?booked=1');
    }

    public function myRentals(): void
    {
        requireRole('customer');
        $bookings = $this->rentalModel->getBookingsByCustomer($_SESSION['user_id']);
        require BASE_PATH . '/app/views/customer/rentals.php';
    }

    public function sellerRentals(): void
    {
        requireRole('seller');
        $sellerId = $this->carModel->getSellerIdByUserId($this->db, $_SESSION['user_id']);
        $bookings = $sellerId ? $this->rentalModel->getBookingsForSeller($sellerId) : [];
        $csrfToken = generateCsrfToken();
        require BASE_PATH . '/app/views/seller/rentals.php';
    }

    public function updateBookingStatus(): void
    {
        requireRole('seller');

        if (!verifyCsrfToken($_POST['csrf_token'] ?? null)) {
            die('Invalid CSRF token.');
        }

        $bookingId = (int) ($_POST['booking_id'] ?? 0);
        $status = $_POST['status'] ?? '';
        $sellerId = $this->carModel->getSellerIdByUserId($this->db, $_SESSION['user_id']);

        $allowedStatuses = ['confirmed', 'ongoing', 'completed', 'cancelled'];

        $booking = $this->rentalModel->getBookingByIdForSeller($bookingId, $sellerId);

            $statusLabels = ['confirmed' => 'confirmed', 'cancelled' => 'cancelled', 'completed' => 'completed'];
            if (isset($statusLabels[$status])) {
                notify((int) $booking['customer_id'], 'booking', 'Rental Booking ' . ucfirst($statusLabels[$status]), "Your rental booking has been {$statusLabels[$status]}.");
            }

        if ($booking && in_array($status, $allowedStatuses, true)) {
            $this->rentalModel->updateBookingStatus($bookingId, $status);
            notify((int) $booking['customer_id'], 'booking', 'Rental Booking ' . ucfirst($status), "Your rental booking has been {$status}.");
        }
        redirect('/seller/rentals');
    }
}