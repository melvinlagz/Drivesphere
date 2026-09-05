<?php
// app/routes.php

$router->get('/', 'HomeController@index');

$router->get('/register', 'AuthController@showRegister');
$router->post('/register', 'AuthController@register');

$router->get('/login', 'AuthController@showLogin');
$router->post('/login', 'AuthController@login');

$router->get('/logout', 'AuthController@logout');

$router->get('/customer/dashboard', 'CustomerController@dashboard');
$router->get('/seller/dashboard', 'SellerController@dashboard');
$router->get('/admin/dashboard', 'AdminController@dashboard');
$router->get('/seller/listings/create', 'SellerController@showCreateListing');
$router->post('/seller/listings/store', 'SellerController@storeListing');
$router->get('/seller/listings', 'SellerController@myListings');

$router->get('/cars', 'CarController@browse');
$router->get('/cars/view', 'CarController@show');

$router->get('/admin/vehicles', 'AdminController@manageVehicles');
$router->post('/admin/vehicles/approve', 'AdminController@approveVehicle');
$router->post('/admin/vehicles/reject', 'AdminController@rejectVehicle');

$router->get('/seller/listings/edit', 'SellerController@showEditListing');
$router->post('/seller/listings/update', 'SellerController@updateListing');
$router->post('/seller/listings/delete', 'SellerController@deleteListing');
$router->post('/seller/listings/delete-image', 'SellerController@deleteImage');

$router->get('/customer/favorites', 'CustomerController@favorites');
$router->post('/customer/favorites/toggle', 'CustomerController@toggleFavorite');

$router->get('/messages', 'MessageController@inbox');
$router->get('/messages/thread', 'MessageController@thread');
$router->post('/messages/send', 'MessageController@send');
$router->post('/messages/start-from-car', 'MessageController@startFromCar');

$router->post('/rentals/book', 'RentalController@bookNow');
$router->get('/customer/rentals', 'RentalController@myRentals');
$router->get('/seller/rentals', 'RentalController@sellerRentals');
$router->post('/seller/rentals/update-status', 'RentalController@updateBookingStatus');

$router->post('/purchases/reserve', 'PurchaseController@reserve');
$router->get('/customer/purchases', 'PurchaseController@myPurchases');
$router->get('/seller/sales', 'PurchaseController@sellerSales');
$router->post('/seller/sales/update-status', 'PurchaseController@updateStatus');

$router->get('/payments/rental', 'PaymentController@showPayRental');
$router->get('/payments/purchase', 'PaymentController@showPayPurchase');
$router->post('/payments/process', 'PaymentController@processPayment');
$router->get('/payments/receipt', 'PaymentController@receipt');
$router->get('/payments/history', 'PaymentController@history');

$router->post('/reviews/store', 'ReviewController@store');
$router->post('/reviews/report', 'ReviewController@report');
$router->get('/customer/reviewable', 'ReviewController@myReviewable');

$router->get('/admin/users', 'AdminController@manageUsers');
$router->post('/admin/users/update-status', 'AdminController@updateUserStatus');

$router->get('/admin/sellers', 'AdminController@manageSellers');
$router->post('/admin/sellers/toggle-verification', 'AdminController@toggleSellerVerification');

$router->get('/admin/payments', 'AdminController@managePayments');
$router->get('/admin/reports', 'AdminController@reports');

$router->get('/notifications', 'NotificationController@index');

$router->get('/profile', 'ProfileController@showProfile');
$router->post('/profile/update', 'ProfileController@updateProfile');
$router->get('/settings', 'ProfileController@showSettings');
$router->post('/settings/update-password', 'ProfileController@updatePassword');

$router->get('/customer/overview', 'CustomerController@overview');
$router->get('/seller/overview', 'SellerController@overview');
$router->get('/admin/overview', 'AdminController@overview');

$router->get('/admin/rentals', 'AdminController@allRentals');
$router->get('/admin/sales', 'AdminController@allSales');

$router->get('/seller/earnings', 'SellerController@earnings');