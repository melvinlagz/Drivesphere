<?php
// app/controllers/MessageController.php

require_once BASE_PATH . '/app/helpers/functions.php';
require_once BASE_PATH . '/app/helpers/csrf.php';
require_once BASE_PATH . '/app/models/Message.php';
require_once BASE_PATH . '/app/models/Car.php';

class MessageController
{
    private PDO $db;
    private Message $messageModel;
    private Car $carModel;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->connect();
        $this->messageModel = new Message($this->db);
        $this->carModel = new Car($this->db);
    }

    public function inbox(): void
    {
        requireLogin();
        $conversations = $this->messageModel->getConversations($_SESSION['user_id']);
        require BASE_PATH . '/app/views/messages/inbox.php';
    }

    public function thread(): void
    {
        requireLogin();

        $otherUserId = (int) ($_GET['user'] ?? 0);
        $carId = isset($_GET['car']) && $_GET['car'] !== '' ? (int) $_GET['car'] : null;

        if ($otherUserId <= 0) {
            http_response_code(404);
            echo "Conversation not found.";
            return;
        }

        $stmt = $this->db->prepare("SELECT full_name FROM users WHERE id = :id");
        $stmt->execute(['id' => $otherUserId]);
        $otherUser = $stmt->fetch();

        if (!$otherUser) {
            http_response_code(404);
            echo "User not found.";
            return;
        }

        $car = $carId ? $this->carModel->getByIdAny($carId) : null;

        $this->messageModel->markThreadAsRead($_SESSION['user_id'], $otherUserId, $carId);
        $messages = $this->messageModel->getThread($_SESSION['user_id'], $otherUserId, $carId);
        $csrfToken = generateCsrfToken();

        require BASE_PATH . '/app/views/messages/thread.php';
    }

    public function send(): void
    {
        requireLogin();

        if (!verifyCsrfToken($_POST['csrf_token'] ?? null)) {
            die('Invalid CSRF token.');
        }

        $receiverId = (int) ($_POST['receiver_id'] ?? 0);
        $carId = isset($_POST['car_id']) && $_POST['car_id'] !== '' ? (int) $_POST['car_id'] : null;
        $messageText = sanitize($_POST['message'] ?? '');

        if ($receiverId > 0 && $messageText !== '') {
            $this->messageModel->send($_SESSION['user_id'], $receiverId, $carId, $messageText);
                    if ($receiverId > 0 && $messageText !== '') {
            $this->messageModel->send($_SESSION['user_id'], $receiverId, $carId, $messageText);
            notify($receiverId, 'message', 'New Message', "You have a new message.");
        }
        }

        $redirectUrl = '/messages/thread?user=' . $receiverId;
        if ($carId !== null) {
            $redirectUrl .= '&car=' . $carId;
        }
        redirect($redirectUrl);
    }

    // Called from the "Contact Seller" button on car details page
    public function startFromCar(): void
    {
        requireLogin();

        $carId = (int) ($_POST['car_id'] ?? 0);
        $car = $this->carModel->getByIdAny($carId);

        if (!$car) {
            http_response_code(404);
            echo "Car not found.";
            return;
        }

        // Get seller's user_id from sellers table
        $stmt = $this->db->prepare("SELECT user_id FROM sellers WHERE id = :id");
        $stmt->execute(['id' => $car['seller_id']]);
        $seller = $stmt->fetch();

        if (!$seller) {
            http_response_code(404);
            echo "Seller not found.";
            return;
        }

        redirect('/messages/thread?user=' . $seller['user_id'] . '&car=' . $carId);
    }
}