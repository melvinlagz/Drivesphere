<?php
// app/controllers/PurchaseController.php

require_once BASE_PATH . '/app/helpers/functions.php';
require_once BASE_PATH . '/app/helpers/csrf.php';
require_once BASE_PATH . '/app/models/Purchase.php';
require_once BASE_PATH . '/app/models/Car.php';

class PurchaseController
{
    private PDO $db;
    private Purchase $purchaseModel;
    private Car $carModel;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->connect();
        $this->purchaseModel = new Purchase($this->db);
        $this->carModel = new Car($this->db);
    }

    public function reserve(): void
    {
        requireRole('customer');

        if (!verifyCsrfToken($_POST['csrf_token'] ?? null)) {
            die('Invalid CSRF token.');
        }

        $carId = (int) ($_POST['car_id'] ?? 0);
        $paymentType = $_POST['payment_type'] ?? 'booking_fee';

        $car = $this->carModel->findById($carId);

        if (!$car || !$car['price']) {
            die('This vehicle is not available for purchase.');
        }

        if ($this->purchaseModel->hasPendingOrActivePurchase($carId)) {
            die('This vehicle already has a pending or confirmed purchase reservation.');
        }

        $bookingFee = (float) ($car['booking_fee'] ?? 0);
        $totalPrice = (float) $car['price'];

        $this->purchaseModel->create([
            'car_id'       => $carId,
            'customer_id'  => $_SESSION['user_id'],
            'booking_fee'  => $paymentType === 'booking_fee' ? $bookingFee : 0,
            'total_price'  => $totalPrice,
            'payment_type' => $paymentType,
        ]);

        // Notify the seller of a new purchase reservation
        $stmt = $this->db->prepare(
            "SELECT u.id FROM sellers s JOIN users u ON s.user_id = u.id
             JOIN cars c ON c.seller_id = s.id WHERE c.id = :car_id"
        );
        $stmt->execute(['car_id' => $carId]);
        $sellerUser = $stmt->fetch();
        if ($sellerUser) {
            notify((int) $sellerUser['id'], 'booking', 'New Purchase Reservation', "You have a new purchase reservation for {$car['brand']} {$car['model']}.");
        }
        $stmt = $this->db->prepare(
            "SELECT u.id FROM sellers s JOIN users u ON s.user_id = u.id
             JOIN cars c ON c.seller_id = s.id WHERE c.id = :car_id"
        );
        $stmt->execute(['car_id' => $carId]);
        $sellerUser = $stmt->fetch();
        if ($sellerUser) {
            notify((int) $sellerUser['id'], 'booking', 'New Purchase Reservation', "You have a new purchase reservation for {$car['brand']} {$car['model']}.");
        }
        redirect('/customer/purchases?reserved=1');
    }

    public function myPurchases(): void
    {
        requireRole('customer');
        $purchases = $this->purchaseModel->getByCustomer($_SESSION['user_id']);
        require BASE_PATH . '/app/views/customer/purchases.php';
    }

    public function sellerSales(): void
    {
        requireRole('seller');
        $sellerId = $this->carModel->getSellerIdByUserId($this->db, $_SESSION['user_id']);
        $purchases = $sellerId ? $this->purchaseModel->getBySeller($sellerId) : [];
        $csrfToken = generateCsrfToken();
        require BASE_PATH . '/app/views/seller/sales.php';
    }

    public function updateStatus(): void
    {
        requireRole('seller');

        if (!verifyCsrfToken($_POST['csrf_token'] ?? null)) {
            die('Invalid CSRF token.');
        }

        $purchaseId = (int) ($_POST['purchase_id'] ?? 0);
        $status = $_POST['status'] ?? '';
        $sellerId = $this->carModel->getSellerIdByUserId($this->db, $_SESSION['user_id']);

        $allowedStatuses = ['confirmed', 'completed', 'cancelled'];

        $purchase = $this->purchaseModel->findByIdForSeller($purchaseId, $sellerId);

        if ($purchase && in_array($status, $allowedStatuses, true)) {
            $this->purchaseModel->updateStatus($purchaseId, $status);
            notify((int) $purchase['customer_id'], 'booking', 'Purchase ' . ucfirst($status), "Your vehicle purchase reservation has been {$status}.");
        }

        redirect('/seller/sales');
    }
}