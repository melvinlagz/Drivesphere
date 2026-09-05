<?php
// app/controllers/HomeController.php

require_once BASE_PATH . '/app/helpers/functions.php';
require_once BASE_PATH . '/app/models/Car.php';

class HomeController
{
    private PDO $db;
    private Car $carModel;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->connect();
        $this->carModel = new Car($this->db);
    }

    public function index(): void
    {
        $featuredCars = $this->carModel->getFeatured(6);
        $latestCars = $this->carModel->getLatest(8);
        $popularRentals = $this->carModel->getPopularForRent(6);
        $brands = $this->carModel->getDistinctBrands();
        $stats = $this->carModel->getStats();

        require BASE_PATH . '/app/views/home/index.php';
    }
}