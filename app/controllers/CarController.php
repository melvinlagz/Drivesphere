<?php
// app/controllers/CarController.php

require_once BASE_PATH . '/app/models/Car.php';
require_once BASE_PATH . '/app/helpers/functions.php';
require_once BASE_PATH . '/app/helpers/csrf.php';
require_once BASE_PATH . '/app/models/Rental.php';
require_once BASE_PATH . '/app/models/Purchase.php';
require_once BASE_PATH . '/app/models/Review.php';

class CarController
{
    private PDO $db;
    private Car $carModel;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->connect();
        $this->carModel = new Car($this->db);
    }

    public function browse(): void
    {
        $filters = [
            'brand'            => $_GET['brand'] ?? '',
            'model'            => $_GET['model'] ?? '',
            'min_price'        => $_GET['min_price'] ?? '',
            'max_price'        => $_GET['max_price'] ?? '',
            'fuel_type'        => $_GET['fuel_type'] ?? '',
            'transmission'     => $_GET['transmission'] ?? '',
            'body_type'        => $_GET['body_type'] ?? '',
            'condition_status' => $_GET['condition_status'] ?? '',
            'listing_type'     => $_GET['listing_type'] ?? '',
            'location'         => $_GET['location'] ?? '',
        ];

        $page = max(1, (int) ($_GET['page'] ?? 1));
        $perPage = 9;
        $offset = ($page - 1) * $perPage;

        $cars = $this->carModel->getPublishedCars($filters, $perPage, $offset);
        $totalCars = $this->carModel->countPublishedCars($filters);
        $totalPages = (int) ceil($totalCars / $perPage);

        require BASE_PATH . '/app/views/cars/browse.php';
    }

   public function show(): void
    {
        $id = (int) ($_GET['id'] ?? 0);

        if ($id <= 0) {
            http_response_code(404);
            echo "Car not found.";
            return;
        }

        $car = $this->carModel->findById($id);

        if (!$car) {
            http_response_code(404);
            echo "Car not found or no longer available.";
            return;
        }

        $this->carModel->incrementViews($id);
        $images = $this->carModel->getImagesByCarId($id);

        $isFavorited = false;
        if (isLoggedIn() && getUserRole() === 'customer') {
            $isFavorited = $this->carModel->isFavorited($_SESSION['user_id'], $id);
        }

               $rentalModel = new Rental($this->db);
        $rental = ($car['listing_type'] === 'rent' || $car['listing_type'] === 'both')
            ? $rentalModel->getByCarId($id)
            : null;

        $purchaseModel = new Purchase($this->db);
        $hasPendingPurchase = false;
        if ($car['listing_type'] === 'sale' || $car['listing_type'] === 'both') {
            $hasPendingPurchase = $purchaseModel->hasPendingOrActivePurchase($id);
        }

        $reviewModel = new Review($this->db);
        $reviews = $reviewModel->getByCarId($id);
        $ratingData = $reviewModel->getAverageRating($id);

        $canReview = false;
        if (isLoggedIn() && getUserRole() === 'customer') {
            $canReview = $reviewModel->hasCompletedTransaction($_SESSION['user_id'], $id)
                && !$reviewModel->hasAlreadyReviewed($_SESSION['user_id'], $id);
        }

        $csrfToken = generateCsrfToken();
        require BASE_PATH . '/app/views/cars/show.php';
    }

}
