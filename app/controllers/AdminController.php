<?php
// app/controllers/AdminController.php

require_once BASE_PATH . '/app/helpers/functions.php';
require_once BASE_PATH . '/app/helpers/csrf.php';
require_once BASE_PATH . '/app/models/Car.php';
require_once BASE_PATH . '/app/models/User.php';
require_once BASE_PATH . '/app/models/Seller.php';
require_once BASE_PATH . '/app/models/Payment.php';
require_once BASE_PATH . '/app/models/Rental.php';
require_once BASE_PATH . '/app/models/Purchase.php';

class AdminController
{
    private PDO $db;
    private Car $carModel;
    private User $userModel;
    private Seller $sellerModel;
    private Payment $paymentModel;
    private Rental $rentalModel;
    private Purchase $purchaseModel;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->connect();
        $this->carModel = new Car($this->db);
        $this->userModel = new User($this->db);
        $this->sellerModel = new Seller($this->db);
        $this->paymentModel = new Payment($this->db);
        $this->rentalModel = new Rental($this->db);
        $this->purchaseModel = new Purchase($this->db);
    }

    public function dashboard(): void
    {
        requireRole('admin');

        // Basic report stats for dashboard overview
        $userCounts = $this->userModel->getCounts();
        $totalRevenue = $this->paymentModel->getTotalRevenue();
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM cars");
        $totalCars = (int) $stmt->fetch()['total'];
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM cars WHERE status = 'pending'");
        $pendingCars = (int) $stmt->fetch()['total'];
        require_once BASE_PATH . '/app/models/Notification.php';
        $notificationModel = new Notification($this->db);
        $unreadCount = $notificationModel->getUnreadCount($_SESSION['user_id']);

        require BASE_PATH . '/app/views/admin/dashboard.php';
    }

    // ===================== VEHICLES =====================

    public function manageVehicles(): void
    {
        requireRole('admin');
        $statusFilter = $_GET['status'] ?? '';
        $cars = $this->carModel->getAllForAdmin($statusFilter);
        $csrfToken = generateCsrfToken();
        require BASE_PATH . '/app/views/admin/vehicles.php';
    }

        public function approveVehicle(): void
    {
        requireRole('admin');
        if (!verifyCsrfToken($_POST['csrf_token'] ?? null)) {
            die('Invalid CSRF token.');
        }
        $carId = (int) ($_POST['car_id'] ?? 0);
        if ($carId > 0) {
            $this->carModel->updateStatus($carId, 'published');
            $car = $this->carModel->getByIdAny($carId);
            $stmt = $this->db->prepare("SELECT u.id FROM sellers s JOIN users u ON s.user_id = u.id WHERE s.id = :seller_id");
            $stmt->execute(['seller_id' => $car['seller_id']]);
            $sellerUser = $stmt->fetch();
            if ($sellerUser) {
                notify((int) $sellerUser['id'], 'system', 'Listing Approved', "Your listing for {$car['brand']} {$car['model']} has been approved and is now live.");
            }
        }
        redirect('/admin/vehicles');
    }

        public function rejectVehicle(): void
    {
        requireRole('admin');
        if (!verifyCsrfToken($_POST['csrf_token'] ?? null)) {
            die('Invalid CSRF token.');
        }
        $carId = (int) ($_POST['car_id'] ?? 0);
        if ($carId > 0) {
            $this->carModel->updateStatus($carId, 'rejected');
            $car = $this->carModel->getByIdAny($carId);
            $stmt = $this->db->prepare("SELECT u.id FROM sellers s JOIN users u ON s.user_id = u.id WHERE s.id = :seller_id");
            $stmt->execute(['seller_id' => $car['seller_id']]);
            $sellerUser = $stmt->fetch();
            if ($sellerUser) {
                notify((int) $sellerUser['id'], 'system', 'Listing Rejected', "Your listing for {$car['brand']} {$car['model']} was not approved. Please review and edit it.");
            }
        }
        redirect('/admin/vehicles');
    }

    // ===================== USERS =====================

    public function manageUsers(): void
    {
        requireRole('admin');
        $roleFilter = $_GET['role'] ?? '';
        $statusFilter = $_GET['status'] ?? '';
        $users = $this->userModel->getAll($roleFilter, $statusFilter);
        $csrfToken = generateCsrfToken();
        require BASE_PATH . '/app/views/admin/users.php';
    }

    public function updateUserStatus(): void
    {
        requireRole('admin');
        if (!verifyCsrfToken($_POST['csrf_token'] ?? null)) {
            die('Invalid CSRF token.');
        }

        $userId = (int) ($_POST['user_id'] ?? 0);
        $status = $_POST['status'] ?? '';

        if ($userId === (int) $_SESSION['user_id']) {
            die('You cannot change the status of your own account.');
        }

        $allowed = ['active', 'suspended'];
        if ($userId > 0 && in_array($status, $allowed, true)) {
            $this->userModel->updateStatus($userId, $status);
        }

        redirect('/admin/users');
    }

    // ===================== SELLERS =====================

    public function manageSellers(): void
    {
        requireRole('admin');
        $filter = $_GET['filter'] ?? '';
        $sellers = $this->sellerModel->getAll($filter);
        $csrfToken = generateCsrfToken();
        require BASE_PATH . '/app/views/admin/sellers.php';
    }

    public function toggleSellerVerification(): void
    {
        requireRole('admin');
        if (!verifyCsrfToken($_POST['csrf_token'] ?? null)) {
            die('Invalid CSRF token.');
        }

        $sellerId = (int) ($_POST['seller_id'] ?? 0);
        if ($sellerId > 0) {
            $this->sellerModel->toggleVerification($sellerId);
        }

        redirect('/admin/sellers');
    }

    // ===================== PAYMENTS =====================

    public function managePayments(): void
    {
        requireRole('admin');
        $statusFilter = $_GET['status'] ?? '';
        $payments = $this->paymentModel->getAllForAdmin($statusFilter);
        $totalRevenue = $this->paymentModel->getTotalRevenue();
        require BASE_PATH . '/app/views/admin/payments.php';
    }

    // ===================== REPORTS =====================

    public function reports(): void
    {
        requireRole('admin');

        $userCounts = $this->userModel->getCounts();
        $totalRevenue = $this->paymentModel->getTotalRevenue();

        $stmt = $this->db->query("SELECT COUNT(*) as total FROM cars");
        $totalCars = (int) $stmt->fetch()['total'];

        $stmt = $this->db->query("SELECT COUNT(*) as total FROM cars WHERE status = 'published'");
        $publishedCars = (int) $stmt->fetch()['total'];

        $stmt = $this->db->query("SELECT COUNT(*) as total FROM cars WHERE status = 'pending'");
        $pendingCars = (int) $stmt->fetch()['total'];

        $stmt = $this->db->query("SELECT COUNT(*) as total FROM cars WHERE availability_status = 'sold'");
        $soldCars = (int) $stmt->fetch()['total'];

        $stmt = $this->db->query("SELECT COUNT(*) as total FROM rental_bookings");
        $totalRentals = (int) $stmt->fetch()['total'];

        $stmt = $this->db->query("SELECT COUNT(*) as total FROM purchases");
        $totalPurchases = (int) $stmt->fetch()['total'];

        $stmt = $this->db->query(
            "SELECT brand, model, COUNT(*) as views_total FROM cars GROUP BY brand, model ORDER BY views_count DESC LIMIT 5"
        );
        $topViewed = $this->db->query(
            "SELECT brand, model, views_count FROM cars ORDER BY views_count DESC LIMIT 5"
        )->fetchAll();

        require BASE_PATH . '/app/views/admin/reports.php';
    }
        public function overview(): void
    {
        requireRole('admin');

        $userCounts = $this->userModel->getCounts();
        $totalRevenue = $this->paymentModel->getTotalRevenue();

        $stmt = $this->db->query("SELECT COUNT(*) as total FROM cars");
        $totalCars = (int) $stmt->fetch()['total'];

        $stmt = $this->db->query("SELECT COUNT(*) as total FROM cars WHERE status = 'pending'");
        $pendingCars = (int) $stmt->fetch()['total'];

        $stmt = $this->db->query("SELECT COUNT(*) as total FROM sellers WHERE is_verified = 0");
        $unverifiedSellers = (int) $stmt->fetch()['total'];

        require BASE_PATH . '/app/views/admin/overview.php';
    }
        public function allRentals(): void
    {
        requireRole('admin');
        $bookings = $this->rentalModel->getAllForAdmin();
        require BASE_PATH . '/app/views/admin/rentals.php';
    }

    public function allSales(): void
    {
        requireRole('admin');
        $purchases = $this->purchaseModel->getAllForAdmin();
        require BASE_PATH . '/app/views/admin/sales.php';
    }
}