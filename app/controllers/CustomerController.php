<?php
// app/controllers/CustomerController.php

require_once BASE_PATH . '/app/helpers/functions.php';
require_once BASE_PATH . '/app/helpers/csrf.php';
require_once BASE_PATH . '/app/models/Car.php';

class CustomerController
{
    private PDO $db;
    private Car $carModel;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->connect();
        $this->carModel = new Car($this->db);
    }

       public function dashboard(): void
    {
        requireRole('customer');
        require_once BASE_PATH . '/app/models/Notification.php';
        $notificationModel = new Notification($this->db);
        $unreadCount = $notificationModel->getUnreadCount($_SESSION['user_id']);
        require BASE_PATH . '/app/views/customer/dashboard.php';
    }
    public function favorites(): void
    {
        requireRole('customer');
        $favorites = $this->carModel->getFavoritesByUser($_SESSION['user_id']);
        $csrfToken = generateCsrfToken();
        require BASE_PATH . '/app/views/customer/favorites.php';
    }

    public function toggleFavorite(): void
    {
        requireRole('customer');

        if (!verifyCsrfToken($_POST['csrf_token'] ?? null)) {
            die('Invalid CSRF token.');
        }

        $carId = (int) ($_POST['car_id'] ?? 0);
        $redirectTo = $_POST['redirect_to'] ?? '/customer/favorites';

        if ($carId > 0) {
            $this->carModel->toggleFavorite($_SESSION['user_id'], $carId);
        }

        redirect($redirectTo);
    }
        public function overview(): void
    {
        requireRole('customer');

        require_once BASE_PATH . '/app/models/Rental.php';
        require_once BASE_PATH . '/app/models/Purchase.php';
        require_once BASE_PATH . '/app/models/Message.php';

        $rentalModel = new Rental($this->db);
        $purchaseModel = new Purchase($this->db);
        $messageModel = new Message($this->db);

        $rentals = $rentalModel->getBookingsByCustomer($_SESSION['user_id']);
        $purchases = $purchaseModel->getByCustomer($_SESSION['user_id']);
        $favoritesCount = count($this->carModel->getFavoritesByUser($_SESSION['user_id']));
        $unreadMessages = $messageModel->getUnreadCount($_SESSION['user_id']);

        $activeRentals = count(array_filter($rentals, fn($r) => in_array($r['status'], ['pending', 'confirmed', 'ongoing'])));
        $pendingPurchases = count(array_filter($purchases, fn($p) => $p['status'] === 'pending'));

        require BASE_PATH . '/app/views/customer/overview.php';
    }
    
}