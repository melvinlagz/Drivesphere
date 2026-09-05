<?php
// app/controllers/ReviewController.php

require_once BASE_PATH . '/app/helpers/functions.php';
require_once BASE_PATH . '/app/helpers/csrf.php';
require_once BASE_PATH . '/app/models/Review.php';

class ReviewController
{
    private PDO $db;
    private Review $reviewModel;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->connect();
        $this->reviewModel = new Review($this->db);
    }

    public function store(): void
    {
        requireRole('customer');

        if (!verifyCsrfToken($_POST['csrf_token'] ?? null)) {
            die('Invalid CSRF token.');
        }

        $carId = (int) ($_POST['car_id'] ?? 0);
        $rating = (int) ($_POST['rating'] ?? 0);
        $comment = sanitize($_POST['comment'] ?? '');

        if ($rating < 1 || $rating > 5) {
            die('Invalid rating.');
        }

        if (!$this->reviewModel->hasCompletedTransaction($_SESSION['user_id'], $carId)) {
            die('You can only review vehicles you have completed a rental or purchase for.');
        }

        if ($this->reviewModel->hasAlreadyReviewed($_SESSION['user_id'], $carId)) {
            die('You have already reviewed this vehicle.');
        }

        $this->reviewModel->create($_SESSION['user_id'], $carId, $rating, $comment);

        redirect('/cars/view?id=' . $carId . '&reviewed=1');
    }

    public function report(): void
    {
        requireLogin();

        if (!verifyCsrfToken($_POST['csrf_token'] ?? null)) {
            die('Invalid CSRF token.');
        }

        $reviewId = (int) ($_POST['review_id'] ?? 0);
        $carId = (int) ($_POST['car_id'] ?? 0);

        if ($reviewId > 0) {
            $this->reviewModel->reportReview($reviewId);
        }

        redirect('/cars/view?id=' . $carId . '&reported=1');
    }

    public function myReviewable(): void
    {
        requireRole('customer');
        $reviewableCars = $this->reviewModel->getReviewableTransactions($_SESSION['user_id']);
        require BASE_PATH . '/app/views/customer/reviewable.php';
    }
}