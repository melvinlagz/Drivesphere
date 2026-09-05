<?php
// app/controllers/NotificationController.php

require_once BASE_PATH . '/app/helpers/functions.php';
require_once BASE_PATH . '/app/helpers/csrf.php';
require_once BASE_PATH . '/app/models/Notification.php';

class NotificationController
{
    private PDO $db;
    private Notification $notificationModel;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->connect();
        $this->notificationModel = new Notification($this->db);
    }

    public function index(): void
    {
        requireLogin();
        $notifications = $this->notificationModel->getByUser($_SESSION['user_id']);
        $this->notificationModel->markAllAsRead($_SESSION['user_id']);
        require BASE_PATH . '/app/views/notifications/index.php';
    }
}