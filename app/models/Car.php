<?php
// app/models/Car.php

class Car
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

        public function create(array $data): int
    {
        $stmt = $this->db->prepare(
            "INSERT INTO cars (
                seller_id, brand, model, year, price, booking_fee, rental_price_per_day,
                mileage, fuel_type, transmission, engine_size, drive_type,
                body_type, seats, exterior_color, interior_color, condition_status,
                vin, registration_number, description, features, listing_type,
                location, status
            ) VALUES (
                :seller_id, :brand, :model, :year, :price, :booking_fee, :rental_price_per_day,
                :mileage, :fuel_type, :transmission, :engine_size, :drive_type,
                :body_type, :seats, :exterior_color, :interior_color, :condition_status,
                :vin, :registration_number, :description, :features, :listing_type,
                :location, :status
            )"
        );

        $stmt->execute([
            'seller_id'             => $data['seller_id'],
            'brand'                 => $data['brand'],
            'model'                 => $data['model'],
            'year'                  => $data['year'],
            'price'                 => $data['price'] ?: null,
            'booking_fee'           => $data['booking_fee'] ?: null,
            'rental_price_per_day'  => $data['rental_price_per_day'] ?: null,
            'mileage'               => $data['mileage'] ?: null,
            'fuel_type'             => $data['fuel_type'],
            'transmission'          => $data['transmission'],
            'engine_size'           => $data['engine_size'] ?: null,
            'drive_type'            => $data['drive_type'],
            'body_type'             => $data['body_type'] ?: null,
            'seats'                 => $data['seats'] ?: null,
            'exterior_color'        => $data['exterior_color'] ?: null,
            'interior_color'        => $data['interior_color'] ?: null,
            'condition_status'      => $data['condition_status'],
            'vin'                   => $data['vin'] ?: null,
            'registration_number'   => $data['registration_number'] ?: null,
            'description'           => $data['description'] ?: null,
            'features'              => $data['features'] ?: null,
            'listing_type'          => $data['listing_type'],
            'location'              => $data['location'] ?: null,
            'status'                => 'pending',
        ]);

        return (int) $this->db->lastInsertId();
    }
public function getPublishedCars(array $filters = [], int $limit = 12, int $offset = 0): array
    {
        $where = ["c.status = 'published'"];
        $params = [];

        if (!empty($filters['brand'])) {
            $where[] = "c.brand LIKE :brand";
            $params['brand'] = '%' . $filters['brand'] . '%';
        }
        if (!empty($filters['model'])) {
            $where[] = "c.model LIKE :model";
            $params['model'] = '%' . $filters['model'] . '%';
        }
        if (!empty($filters['min_price'])) {
            $where[] = "c.price >= :min_price";
            $params['min_price'] = $filters['min_price'];
        }
        if (!empty($filters['max_price'])) {
            $where[] = "c.price <= :max_price";
            $params['max_price'] = $filters['max_price'];
        }
        if (!empty($filters['fuel_type'])) {
            $where[] = "c.fuel_type = :fuel_type";
            $params['fuel_type'] = $filters['fuel_type'];
        }
        if (!empty($filters['transmission'])) {
            $where[] = "c.transmission = :transmission";
            $params['transmission'] = $filters['transmission'];
        }
        if (!empty($filters['body_type'])) {
            $where[] = "c.body_type LIKE :body_type";
            $params['body_type'] = '%' . $filters['body_type'] . '%';
        }
        if (!empty($filters['condition_status'])) {
            $where[] = "c.condition_status = :condition_status";
            $params['condition_status'] = $filters['condition_status'];
        }
        if (!empty($filters['listing_type'])) {
            if ($filters['listing_type'] === 'sale') {
                $where[] = "(c.listing_type = 'sale' OR c.listing_type = 'both')";
            } elseif ($filters['listing_type'] === 'rent') {
                $where[] = "(c.listing_type = 'rent' OR c.listing_type = 'both')";
            }
        }
        if (!empty($filters['location'])) {
            $where[] = "c.location LIKE :location";
            $params['location'] = '%' . $filters['location'] . '%';
        }

        $whereClause = implode(' AND ', $where);

        $sql = "SELECT c.*,
                    (SELECT image_path FROM car_images WHERE car_id = c.id AND is_primary = 1 LIMIT 1) AS primary_image
                FROM cars c
                WHERE {$whereClause}
                ORDER BY c.created_at DESC
                LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($sql);

        foreach ($params as $key => $value) {
            $stmt->bindValue(':' . $key, $value);
        }
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function countPublishedCars(array $filters = []): int
    {
        $where = ["status = 'published'"];
        $params = [];

        if (!empty($filters['brand'])) {
            $where[] = "brand LIKE :brand";
            $params['brand'] = '%' . $filters['brand'] . '%';
        }
        if (!empty($filters['min_price'])) {
            $where[] = "price >= :min_price";
            $params['min_price'] = $filters['min_price'];
        }
        if (!empty($filters['max_price'])) {
            $where[] = "price <= :max_price";
            $params['max_price'] = $filters['max_price'];
        }
        if (!empty($filters['fuel_type'])) {
            $where[] = "fuel_type = :fuel_type";
            $params['fuel_type'] = $filters['fuel_type'];
        }

        $whereClause = implode(' AND ', $where);
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM cars WHERE {$whereClause}");
        foreach ($params as $key => $value) {
            $stmt->bindValue(':' . $key, $value);
        }
        $stmt->execute();
        return (int) $stmt->fetch()['total'];
    }

    public function findById(int $id): array|false
    {
        $stmt = $this->db->prepare(
            "SELECT c.*, s.business_name, s.seller_type, s.rating_avg, u.full_name AS seller_name, u.phone AS seller_phone, u.email AS seller_email
             FROM cars c
             JOIN sellers s ON c.seller_id = s.id
             JOIN users u ON s.user_id = u.id
             WHERE c.id = :id AND c.status = 'published'
             LIMIT 1"
        );
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function getImagesByCarId(int $carId): array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM car_images WHERE car_id = :car_id ORDER BY is_primary DESC, id ASC"
        );
        $stmt->execute(['car_id' => $carId]);
        return $stmt->fetchAll();
    }

    public function incrementViews(int $carId): void
    {
        $stmt = $this->db->prepare("UPDATE cars SET views_count = views_count + 1 WHERE id = :id");
        $stmt->execute(['id' => $carId]);
    }
    public function getAllForAdmin(string $statusFilter = ''): array
    {
        $sql = "SELECT c.*, u.full_name AS seller_name, s.business_name
                FROM cars c
                JOIN sellers s ON c.seller_id = s.id
                JOIN users u ON s.user_id = u.id";
        $params = [];

        if ($statusFilter !== '') {
            $sql .= " WHERE c.status = :status";
            $params['status'] = $statusFilter;
        }

        $sql .= " ORDER BY c.created_at DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function updateStatus(int $carId, string $status): void
    {
        $stmt = $this->db->prepare("UPDATE cars SET status = :status WHERE id = :id");
        $stmt->execute(['status' => $status, 'id' => $carId]);
    }
    public function getSellerIdByUserId(PDO $db, int $userId): ?int
    {
        $stmt = $db->prepare("SELECT id FROM sellers WHERE user_id = :user_id LIMIT 1");
        $stmt->execute(['user_id' => $userId]);
        $row = $stmt->fetch();
        return $row ? (int) $row['id'] : null;
    }
    public function getByIdAny(int $id): array|false
    {
        $stmt = $this->db->prepare(
            "SELECT c.*, u.full_name AS seller_name, s.business_name
             FROM cars c
             JOIN sellers s ON c.seller_id = s.id
             JOIN users u ON s.user_id = u.id
             WHERE c.id = :id LIMIT 1"
        );
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }
    public function findByIdForSeller(int $carId, int $sellerId): array|false
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM cars WHERE id = :id AND seller_id = :seller_id LIMIT 1"
        );
        $stmt->execute(['id' => $carId, 'seller_id' => $sellerId]);
        return $stmt->fetch();
    }

        public function update(int $carId, array $data): void
    {
        $stmt = $this->db->prepare(
            "UPDATE cars SET
                brand = :brand, model = :model, year = :year, price = :price, booking_fee = :booking_fee,
                rental_price_per_day = :rental_price_per_day, mileage = :mileage,
                fuel_type = :fuel_type, transmission = :transmission, engine_size = :engine_size,
                drive_type = :drive_type, body_type = :body_type, seats = :seats,
                exterior_color = :exterior_color, interior_color = :interior_color,
                condition_status = :condition_status, vin = :vin, registration_number = :registration_number,
                description = :description, features = :features, listing_type = :listing_type,
                location = :location, status = 'pending'
             WHERE id = :id"
        );

        $stmt->execute([
            'brand'                => $data['brand'],
            'model'                => $data['model'],
            'year'                 => $data['year'],
            'price'                => $data['price'] ?: null,
            'booking_fee'          => $data['booking_fee'] ?: null,
            'rental_price_per_day' => $data['rental_price_per_day'] ?: null,
            'mileage'              => $data['mileage'] ?: null,
            'fuel_type'            => $data['fuel_type'],
            'transmission'         => $data['transmission'],
            'engine_size'          => $data['engine_size'] ?: null,
            'drive_type'           => $data['drive_type'],
            'body_type'            => $data['body_type'] ?: null,
            'seats'                => $data['seats'] ?: null,
            'exterior_color'       => $data['exterior_color'] ?: null,
            'interior_color'       => $data['interior_color'] ?: null,
            'condition_status'     => $data['condition_status'],
            'vin'                  => $data['vin'] ?: null,
            'registration_number'  => $data['registration_number'] ?: null,
            'description'          => $data['description'] ?: null,
            'features'             => $data['features'] ?: null,
            'listing_type'         => $data['listing_type'],
            'location'             => $data['location'] ?: null,
            'id'                   => $carId,
        ]);
    }
    public function findBySeller(int $sellerId): array
    {
        $stmt = $this->db->prepare(
            "SELECT c.*, 
                    (SELECT image_path FROM car_images WHERE car_id = c.id AND is_primary = 1 LIMIT 1) AS primary_image
             FROM cars c
             WHERE c.seller_id = :seller_id
             ORDER BY c.created_at DESC"
        );
        $stmt->execute(['seller_id' => $sellerId]);
        return $stmt->fetchAll();
    }
    public function delete(int $carId): void
    {
        // Fetch image paths first so we can delete the physical files
        $stmt = $this->db->prepare("SELECT image_path FROM car_images WHERE car_id = :id");
        $stmt->execute(['id' => $carId]);
        $images = $stmt->fetchAll();

        foreach ($images as $img) {
            $fullPath = BASE_PATH . '/public/' . $img['image_path'];
            if (file_exists($fullPath)) {
                unlink($fullPath);
            }
        }

        // car_images rows are removed automatically via ON DELETE CASCADE
        $stmt = $this->db->prepare("DELETE FROM cars WHERE id = :id");
        $stmt->execute(['id' => $carId]);
    }

    public function deleteImage(int $imageId, int $carId): void
    {
        $stmt = $this->db->prepare("SELECT image_path FROM car_images WHERE id = :id AND car_id = :car_id");
        $stmt->execute(['id' => $imageId, 'car_id' => $carId]);
        $img = $stmt->fetch();

        if ($img) {
            $fullPath = BASE_PATH . '/public/' . $img['image_path'];
            if (file_exists($fullPath)) {
                unlink($fullPath);
            }
            $stmt = $this->db->prepare("DELETE FROM car_images WHERE id = :id");
            $stmt->execute(['id' => $imageId]);
        }
    }
    public function toggleFavorite(int $userId, int $carId): string
    {
        $stmt = $this->db->prepare("SELECT id FROM favorites WHERE user_id = :user_id AND car_id = :car_id");
        $stmt->execute(['user_id' => $userId, 'car_id' => $carId]);
        $existing = $stmt->fetch();

        if ($existing) {
            $stmt = $this->db->prepare("DELETE FROM favorites WHERE id = :id");
            $stmt->execute(['id' => $existing['id']]);
            return 'removed';
        }

        $stmt = $this->db->prepare("INSERT INTO favorites (user_id, car_id) VALUES (:user_id, :car_id)");
        $stmt->execute(['user_id' => $userId, 'car_id' => $carId]);
        return 'added';
    }

    public function isFavorited(int $userId, int $carId): bool
    {
        $stmt = $this->db->prepare("SELECT id FROM favorites WHERE user_id = :user_id AND car_id = :car_id");
        $stmt->execute(['user_id' => $userId, 'car_id' => $carId]);
        return (bool) $stmt->fetch();
    }

    public function getFavoritesByUser(int $userId): array
    {
        $stmt = $this->db->prepare(
            "SELECT c.*,
                    (SELECT image_path FROM car_images WHERE car_id = c.id AND is_primary = 1 LIMIT 1) AS primary_image,
                    f.created_at AS favorited_at
             FROM favorites f
             JOIN cars c ON f.car_id = c.id
             WHERE f.user_id = :user_id
             ORDER BY f.created_at DESC"
        );
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchAll();
    }
        public function getFeatured(int $limit = 6): array
    {
        $stmt = $this->db->prepare(
            "SELECT c.*,
                    (SELECT image_path FROM car_images WHERE car_id = c.id AND is_primary = 1 LIMIT 1) AS primary_image
             FROM cars c
             WHERE c.status = 'published' AND c.is_featured = 1
             ORDER BY c.created_at DESC
             LIMIT :limit"
        );
        $stmt->bindValue('limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getLatest(int $limit = 8): array
    {
        $stmt = $this->db->prepare(
            "SELECT c.*,
                    (SELECT image_path FROM car_images WHERE car_id = c.id AND is_primary = 1 LIMIT 1) AS primary_image
             FROM cars c
             WHERE c.status = 'published'
             ORDER BY c.created_at DESC
             LIMIT :limit"
        );
        $stmt->bindValue('limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getPopularForRent(int $limit = 6): array
    {
        $stmt = $this->db->prepare(
            "SELECT c.*,
                    (SELECT image_path FROM car_images WHERE car_id = c.id AND is_primary = 1 LIMIT 1) AS primary_image
             FROM cars c
             WHERE c.status = 'published' AND (c.listing_type = 'rent' OR c.listing_type = 'both')
             ORDER BY c.views_count DESC
             LIMIT :limit"
        );
        $stmt->bindValue('limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getDistinctBrands(): array
    {
        $stmt = $this->db->query(
            "SELECT DISTINCT brand FROM cars WHERE status = 'published' ORDER BY brand ASC LIMIT 12"
        );
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function getStats(): array
    {
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM cars WHERE status = 'published'");
        $totalCars = (int) $stmt->fetch()['total'];

        $stmt = $this->db->query("SELECT COUNT(DISTINCT seller_id) as total FROM cars WHERE status = 'published'");
        $totalSellers = (int) $stmt->fetch()['total'];

        $stmt = $this->db->query("SELECT COUNT(*) as total FROM cars WHERE availability_status = 'sold'");
        $totalSold = (int) $stmt->fetch()['total'];

        return [
            'total_cars'    => $totalCars,
            'total_sellers' => $totalSellers,
            'total_sold'    => $totalSold,
        ];
    }
}