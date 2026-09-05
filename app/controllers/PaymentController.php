<?php
// app/controllers/PaymentController.php

require_once BASE_PATH . '/app/helpers/functions.php';
require_once BASE_PATH . '/app/helpers/csrf.php';
require_once BASE_PATH . '/app/models/Payment.php';

class PaymentController
{
    private PDO $db;
    private Payment $paymentModel;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->connect();
        $this->paymentModel = new Payment($this->db);
    }

    public function showPayRental(): void
    {
        requireRole('customer');

        $bookingId = (int) ($_GET['booking_id'] ?? 0);

        $stmt = $this->db->prepare(
            "SELECT rb.*, c.brand, c.model, c.year
             FROM rental_bookings rb
             JOIN rentals r ON rb.rental_id = r.id
             JOIN cars c ON r.car_id = c.id
             WHERE rb.id = :id AND rb.customer_id = :customer_id LIMIT 1"
        );
        $stmt->execute(['id' => $bookingId, 'customer_id' => $_SESSION['user_id']]);
        $booking = $stmt->fetch();

        if (!$booking) {
            http_response_code(404);
            echo "Booking not found.";
            return;
        }

        if ($booking['status'] !== 'confirmed') {
            die('This booking must be confirmed by the seller before payment.');
        }

        if ($booking['payment_status'] === 'paid') {
            die('This booking has already been paid for.');
        }

        $type = 'rental';
        $referenceId = $bookingId;
        $amount = (float) $booking['total_price'];
        $description = $booking['brand'] . ' ' . $booking['model'] . ' (' . $booking['year'] . ') — Rental';

        $csrfToken = generateCsrfToken();
        require BASE_PATH . '/app/views/payments/pay.php';
    }

    public function showPayPurchase(): void
    {
        requireRole('customer');

        $purchaseId = (int) ($_GET['purchase_id'] ?? 0);

        $stmt = $this->db->prepare(
            "SELECT p.*, c.brand, c.model, c.year
             FROM purchases p
             JOIN cars c ON p.car_id = c.id
             WHERE p.id = :id AND p.customer_id = :customer_id LIMIT 1"
        );
        $stmt->execute(['id' => $purchaseId, 'customer_id' => $_SESSION['user_id']]);
        $purchase = $stmt->fetch();

        if (!$purchase) {
            http_response_code(404);
            echo "Purchase not found.";
            return;
        }

        if ($purchase['status'] !== 'confirmed') {
            die('This purchase must be confirmed by the seller before payment.');
        }

        if ($purchase['payment_status'] === 'paid') {
            die('This purchase has already been paid for.');
        }

        $type = 'purchase';
        $referenceId = $purchaseId;
        $amount = $purchase['payment_type'] === 'booking_fee' ? (float) $purchase['booking_fee'] : (float) $purchase['total_price'];
        $description = $purchase['brand'] . ' ' . $purchase['model'] . ' (' . $purchase['year'] . ') — ' . ($purchase['payment_type'] === 'booking_fee' ? 'Booking Fee' : 'Full Payment');

        $csrfToken = generateCsrfToken();
        require BASE_PATH . '/app/views/payments/pay.php';
    }

    public function processPayment(): void
    {
        requireRole('customer');

        if (!verifyCsrfToken($_POST['csrf_token'] ?? null)) {
            die('Invalid CSRF token.');
        }

        $type = $_POST['type'] ?? '';
        $referenceId = (int) ($_POST['reference_id'] ?? 0);
        $amount = (float) ($_POST['amount'] ?? 0);
        $method = $_POST['method'] ?? 'mpesa';
        $phone = sanitize($_POST['phone'] ?? '');

        if ($amount <= 0 || $referenceId <= 0 || !in_array($type, ['rental', 'purchase'], true)) {
            die('Invalid payment request.');
        }

        $transactionRef = $this->paymentModel->generateTransactionRef();

        // ===== SIMULATED PAYMENT PROCESSING =====
        // This is where a real M-Pesa Daraja STK Push API call would go.
        // For now we simulate a successful payment immediately.
        $paymentStatus = 'successful';
        // ==========================================

        $paymentId = $this->paymentModel->create([
            'user_id'         => $_SESSION['user_id'],
            'reference_type'  => $type,
            'reference_id'    => $referenceId,
            'amount'          => $amount,
            'method'          => $method,
            'transaction_ref' => $transactionRef,
            'status'          => $paymentStatus,
        ]);

        if ($paymentStatus === 'successful') {
            if ($type === 'rental') {
                $stmt = $this->db->prepare(
                    "UPDATE rental_bookings SET payment_status = 'paid', deposit_paid = deposit_paid + :amount WHERE id = :id"
                );
                $stmt->execute(['amount' => $amount, 'id' => $referenceId]);
            } else {
                $stmt = $this->db->prepare(
                    "UPDATE purchases SET payment_status = 'paid' WHERE id = :id"
                );
                $stmt->execute(['id' => $referenceId]);
            }
        }
       if ($paymentStatus === 'successful') {
            notify((int) $_SESSION['user_id'], 'payment', 'Payment Successful', "Your payment of KES " . number_format($amount) . " was processed successfully.");
        }
        redirect('/payments/receipt?payment_id=' . $paymentId);
        
    }

    public function receipt(): void
    {
        requireLogin();

        $paymentId = (int) ($_GET['payment_id'] ?? 0);
        $payment = $this->paymentModel->findById($paymentId);

        if (!$payment || $payment['user_id'] != $_SESSION['user_id']) {
            http_response_code(404);
            echo "Receipt not found.";
            return;
        }

        // Fetch related vehicle info for display
        if ($payment['reference_type'] === 'rental') {
            $stmt = $this->db->prepare(
                "SELECT c.brand, c.model, c.year FROM rental_bookings rb
                 JOIN rentals r ON rb.rental_id = r.id
                 JOIN cars c ON r.car_id = c.id
                 WHERE rb.id = :id"
            );
        } else {
            $stmt = $this->db->prepare(
                "SELECT c.brand, c.model, c.year FROM purchases p
                 JOIN cars c ON p.car_id = c.id
                 WHERE p.id = :id"
            );
        }
        $stmt->execute(['id' => $payment['reference_id']]);
        $vehicle = $stmt->fetch();

        require BASE_PATH . '/app/views/payments/receipt.php';
    }

    public function history(): void
    {
        requireLogin();
        $payments = $this->paymentModel->getByUser($_SESSION['user_id']);
        require BASE_PATH . '/app/views/customer/payments.php';
    }
}