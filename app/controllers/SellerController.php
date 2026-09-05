<?php
// app/controllers/SellerController.php

require_once BASE_PATH . '/app/helpers/functions.php';
require_once BASE_PATH . '/app/helpers/csrf.php';
require_once BASE_PATH . '/app/helpers/upload.php';
require_once BASE_PATH . '/app/models/Car.php';
require_once BASE_PATH . '/app/models/Rental.php';
require_once BASE_PATH . '/app/models/Purchase.php';

class SellerController
{
    private PDO $db;
    private Car $carModel;
    private Rental $rentalModel;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->connect();
        $this->carModel = new Car($this->db);
        $this->rentalModel = new Rental($this->db);
    }

        public function dashboard(): void
    {
        requireRole('seller');
        require_once BASE_PATH . '/app/models/Notification.php';
        $notificationModel = new Notification($this->db);
        $unreadCount = $notificationModel->getUnreadCount($_SESSION['user_id']);
        require BASE_PATH . '/app/views/seller/dashboard.php';
    }

    public function showCreateListing(): void
    {
        requireRole('seller');
        $csrfToken = generateCsrfToken();
        require BASE_PATH . '/app/views/seller/create_listing.php';
    }

    public function storeListing(): void
    {
        requireRole('seller');

        if (!verifyCsrfToken($_POST['csrf_token'] ?? null)) {
            die('Invalid CSRF token. Please refresh and try again.');
        }

        $sellerId = $this->carModel->getSellerIdByUserId($this->db, $_SESSION['user_id']);

        if (!$sellerId) {
            die('Seller profile not found for this account.');
        }

        $errors = [];

        $brand = sanitize($_POST['brand'] ?? '');
        $model = sanitize($_POST['model'] ?? '');
        $year  = (int) ($_POST['year'] ?? 0);
        $listingType = $_POST['listing_type'] ?? 'sale';

        if ($brand === '') $errors[] = "Brand is required.";
        if ($model === '') $errors[] = "Model is required.";
        if ($year < 1950 || $year > (int) date('Y') + 1) $errors[] = "Enter a valid year.";

        if ($listingType === 'sale' && empty($_POST['price'])) {
            $errors[] = "Price is required for vehicles listed for sale.";
        }
        if (($listingType === 'rent' || $listingType === 'both') && empty($_POST['rental_price_per_day'])) {
            $errors[] = "Rental price per day is required for rental listings.";
        }

        if (!empty($errors)) {
            $csrfToken = generateCsrfToken();
            require BASE_PATH . '/app/views/seller/create_listing.php';
            return;
        }

        $data = [
            'seller_id'            => $sellerId,
            'brand'                => $brand,
            'model'                => $model,
            'year'                 => $year,
            'price'                => $_POST['price'] ?? null,
            'booking_fee'          => $_POST['booking_fee'] ?? null,
            'rental_price_per_day' => $_POST['rental_price_per_day'] ?? null,
            'mileage'              => $_POST['mileage'] ?? null,
            'fuel_type'            => $_POST['fuel_type'] ?? 'petrol',
            'transmission'         => $_POST['transmission'] ?? 'automatic',
            'engine_size'          => sanitize($_POST['engine_size'] ?? ''),
            'drive_type'           => $_POST['drive_type'] ?? 'fwd',
            'body_type'            => sanitize($_POST['body_type'] ?? ''),
            'seats'                => $_POST['seats'] ?? null,
            'exterior_color'       => sanitize($_POST['exterior_color'] ?? ''),
            'interior_color'       => sanitize($_POST['interior_color'] ?? ''),
            'condition_status'     => $_POST['condition_status'] ?? 'used',
            'vin'                  => sanitize($_POST['vin'] ?? ''),
            'registration_number'  => sanitize($_POST['registration_number'] ?? ''),
            'description'          => sanitize($_POST['description'] ?? ''),
            'features'             => sanitize($_POST['features'] ?? ''),
            'listing_type'         => $listingType,
            'location'             => sanitize($_POST['location'] ?? ''),
        ];

        $carId = $this->carModel->create($data);

        // Handle multiple image uploads
        if (!empty($_FILES['images']['name'][0])) {
            $fileCount = count($_FILES['images']['name']);
            for ($i = 0; $i < $fileCount; $i++) {
                if ($_FILES['images']['error'][$i] === UPLOAD_ERR_OK) {
                    $file = [
                        'name'     => $_FILES['images']['name'][$i],
                        'type'     => $_FILES['images']['type'][$i],
                        'tmp_name' => $_FILES['images']['tmp_name'][$i],
                        'error'    => $_FILES['images']['error'][$i],
                        'size'     => $_FILES['images']['size'][$i],
                    ];
                    $path = handleCarImageUpload($file, $carId);
                    if ($path) {
                        $this->carModel->addImage($carId, $path, $i === 0);
                    }
                }
            }
        }

        // Save rental settings if applicable
        if ($listingType === 'rent' || $listingType === 'both') {
            $this->rentalModel->upsertSettings($carId, [
                'security_deposit' => $_POST['security_deposit'] ?? 0,
                'driver_option'    => $_POST['driver_option'] ?? 'self_drive',
                'pickup_location'  => sanitize($_POST['pickup_location'] ?? ''),
                'return_location'  => sanitize($_POST['return_location'] ?? ''),
                'min_rental_days'  => $_POST['min_rental_days'] ?? 1,
            ]);
        }

        redirect('/seller/listings');
    }

    public function myListings(): void
    {
        requireRole('seller');
        $sellerId = $this->carModel->getSellerIdByUserId($this->db, $_SESSION['user_id']);
        $cars = $sellerId ? $this->carModel->findBySeller($sellerId) : [];
        $csrfToken = generateCsrfToken();
        require BASE_PATH . '/app/views/seller/my_listings.php';
    }

    public function showEditListing(): void
    {
        requireRole('seller');

        $carId = (int) ($_GET['id'] ?? 0);
        $sellerId = $this->carModel->getSellerIdByUserId($this->db, $_SESSION['user_id']);
        $car = $this->carModel->findByIdForSeller($carId, $sellerId);

        if (!$car) {
            http_response_code(404);
            echo "Listing not found or you don't have permission to edit it.";
            return;
        }

        $images = $this->carModel->getImagesByCarId($carId);
        $rental = $this->rentalModel->getByCarId($carId) ?: [];
        $csrfToken = generateCsrfToken();
        require BASE_PATH . '/app/views/seller/edit_listing.php';
    }

    public function updateListing(): void
    {
        requireRole('seller');

        if (!verifyCsrfToken($_POST['csrf_token'] ?? null)) {
            die('Invalid CSRF token.');
        }

        $carId = (int) ($_POST['car_id'] ?? 0);
        $sellerId = $this->carModel->getSellerIdByUserId($this->db, $_SESSION['user_id']);
        $car = $this->carModel->findByIdForSeller($carId, $sellerId);

        if (!$car) {
            http_response_code(404);
            echo "Listing not found or you don't have permission to edit it.";
            return;
        }

        $errors = [];
        $brand = sanitize($_POST['brand'] ?? '');
        $model = sanitize($_POST['model'] ?? '');
        $year  = (int) ($_POST['year'] ?? 0);
        $listingType = $_POST['listing_type'] ?? 'sale';

        if ($brand === '') $errors[] = "Brand is required.";
        if ($model === '') $errors[] = "Model is required.";
        if ($year < 1950 || $year > (int) date('Y') + 1) $errors[] = "Enter a valid year.";

        if (!empty($errors)) {
            $images = $this->carModel->getImagesByCarId($carId);
            $rental = $this->rentalModel->getByCarId($carId) ?: [];
            $csrfToken = generateCsrfToken();
            require BASE_PATH . '/app/views/seller/edit_listing.php';
            return;
        }

        $data = [
            'brand'                => $brand,
            'model'                => $model,
            'year'                 => $year,
            'price'                => $_POST['price'] ?? null,
            'booking_fee'          => $_POST['booking_fee'] ?? null,
            'rental_price_per_day' => $_POST['rental_price_per_day'] ?? null,
            'mileage'              => $_POST['mileage'] ?? null,
            'fuel_type'            => $_POST['fuel_type'] ?? 'petrol',
            'transmission'         => $_POST['transmission'] ?? 'automatic',
            'engine_size'          => sanitize($_POST['engine_size'] ?? ''),
            'drive_type'           => $_POST['drive_type'] ?? 'fwd',
            'body_type'            => sanitize($_POST['body_type'] ?? ''),
            'seats'                => $_POST['seats'] ?? null,
            'exterior_color'       => sanitize($_POST['exterior_color'] ?? ''),
            'interior_color'       => sanitize($_POST['interior_color'] ?? ''),
            'condition_status'     => $_POST['condition_status'] ?? 'used',
            'vin'                  => sanitize($_POST['vin'] ?? ''),
            'registration_number'  => sanitize($_POST['registration_number'] ?? ''),
            'description'          => sanitize($_POST['description'] ?? ''),
            'features'             => sanitize($_POST['features'] ?? ''),
            'listing_type'         => $listingType,
            'location'             => sanitize($_POST['location'] ?? ''),
        ];

        $this->carModel->update($carId, $data);

        // Handle newly added images
        if (!empty($_FILES['images']['name'][0])) {
            $existingImages = $this->carModel->getImagesByCarId($carId);
            $hasPrimary = false;
            foreach ($existingImages as $img) {
                if ($img['is_primary']) {
                    $hasPrimary = true;
                    break;
                }
            }

            $fileCount = count($_FILES['images']['name']);
            for ($i = 0; $i < $fileCount; $i++) {
                if ($_FILES['images']['error'][$i] === UPLOAD_ERR_OK) {
                    $file = [
                        'name'     => $_FILES['images']['name'][$i],
                        'type'     => $_FILES['images']['type'][$i],
                        'tmp_name' => $_FILES['images']['tmp_name'][$i],
                        'error'    => $_FILES['images']['error'][$i],
                        'size'     => $_FILES['images']['size'][$i],
                    ];
                    $path = handleCarImageUpload($file, $carId);
                    if ($path) {
                        $makePrimary = !$hasPrimary && $i === 0;
                        $this->carModel->addImage($carId, $path, $makePrimary);
                    }
                }
            }
        }

        // Save rental settings if applicable
        if ($listingType === 'rent' || $listingType === 'both') {
            $this->rentalModel->upsertSettings($carId, [
                'security_deposit' => $_POST['security_deposit'] ?? 0,
                'driver_option'    => $_POST['driver_option'] ?? 'self_drive',
                'pickup_location'  => sanitize($_POST['pickup_location'] ?? ''),
                'return_location'  => sanitize($_POST['return_location'] ?? ''),
                'min_rental_days'  => $_POST['min_rental_days'] ?? 1,
            ]);
        }

        redirect('/seller/listings');
    }

    public function deleteListing(): void
    {
        requireRole('seller');

        if (!verifyCsrfToken($_POST['csrf_token'] ?? null)) {
            die('Invalid CSRF token.');
        }

        $carId = (int) ($_POST['car_id'] ?? 0);
        $sellerId = $this->carModel->getSellerIdByUserId($this->db, $_SESSION['user_id']);
        $car = $this->carModel->findByIdForSeller($carId, $sellerId);

        if ($car) {
            $this->carModel->delete($carId);
        }

        redirect('/seller/listings');
    }

    public function deleteImage(): void
    {
        requireRole('seller');

        if (!verifyCsrfToken($_POST['csrf_token'] ?? null)) {
            die('Invalid CSRF token.');
        }

        $imageId = (int) ($_POST['image_id'] ?? 0);
        $carId = (int) ($_POST['car_id'] ?? 0);
        $sellerId = $this->carModel->getSellerIdByUserId($this->db, $_SESSION['user_id']);
        $car = $this->carModel->findByIdForSeller($carId, $sellerId);

        if ($car) {
            $this->carModel->deleteImage($imageId, $carId);
        }

        redirect('/seller/listings/edit?id=' . $carId);
    }
        public function overview(): void
    {
        requireRole('seller');

        require_once BASE_PATH . '/app/models/Rental.php';
        require_once BASE_PATH . '/app/models/Purchase.php';
        require_once BASE_PATH . '/app/models/Message.php';

        $sellerId = $this->carModel->getSellerIdByUserId($this->db, $_SESSION['user_id']);
        $rentalModel = new Rental($this->db);
        $purchaseModel = new Purchase($this->db);
        $messageModel = new Message($this->db);

        $listings = $sellerId ? $this->carModel->findBySeller($sellerId) : [];
        $rentalBookings = $sellerId ? $rentalModel->getBookingsForSeller($sellerId) : [];
        $sales = $sellerId ? $purchaseModel->getBySeller($sellerId) : [];
        $unreadMessages = $messageModel->getUnreadCount($_SESSION['user_id']);

        $pendingRentals = count(array_filter($rentalBookings, fn($r) => $r['status'] === 'pending'));
        $pendingSales = count(array_filter($sales, fn($s) => $s['status'] === 'pending'));
        $totalListings = count($listings);

        require BASE_PATH . '/app/views/seller/overview.php';
    }
        public function earnings(): void
    {
        requireRole('seller');

        $sellerId = $this->carModel->getSellerIdByUserId($this->db, $_SESSION['user_id']);
        $purchaseModel = new Purchase($this->db);

        $saleEarnings = $sellerId ? $purchaseModel->getSellerEarnings($sellerId) : ['total_sales' => 0, 'completed_sales' => 0];
        $rentalEarnings = $sellerId ? $this->rentalModel->getSellerEarnings($sellerId) : ['total_rental_income' => 0, 'completed_rentals' => 0];

        require BASE_PATH . '/app/views/seller/earnings.php';
    }
}