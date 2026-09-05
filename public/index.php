<?php
// public/index.php - Front Controller

session_start();

define('BASE_PATH', dirname(__DIR__));
define('BASE_URL', '');

require_once BASE_PATH . '/config/database.php';
require_once BASE_PATH . '/app/core/Router.php';

$router = new Router();

// Routes will be registered here as we build each module
require_once BASE_PATH . '/app/routes.php';

$router->dispatch();