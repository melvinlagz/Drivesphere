<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($car['brand'] . ' ' . $car['model']) ?> - DriveSphere</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-dark bg-dark px-3">
    <a href="<?= BASE_URL ?>/" class="navbar-brand mb-0 h1">DriveSphere</a>
    <a href="<?= BASE_URL ?>/cars" class="btn btn-outline-light btn-sm">Back to Browse</a>
</nav>

<div class="container mt-4 mb-5">
    <div class="row">
        <div class="col-md-7">
            <?php if (!empty($images)): ?>
                <div id="carCarousel" class="carousel slide mb-3">
                    <div class="carousel-inner">
                        <?php foreach ($images as $i => $img): ?>
                            <div class="carousel-item <?= $i === 0 ? 'active' : '' ?>">
                                <img src="<?= BASE_URL ?>/<?= htmlspecialchars($img['image_path']) ?>" class="d-block w-100" style="height:400px; object-fit:cover;">
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <?php if (count($images) > 1): ?>
                        <button class="carousel-control-prev" type="button" data-bs-target="#carCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon"></span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon"></span>
                        </button>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <div class="bg-secondary text-white text-center py-5 mb-3">No Images Available</div>
            <?php endif; ?>


            
            <div class="card p-3 mt-3">
                <h5>
                    Reviews
                    <?php if ($ratingData['total_reviews'] > 0): ?>
                        <span class="text-warning">★ <?= $ratingData['avg_rating'] ?></span>
                        <span class="text-muted small">(<?= $ratingData['total_reviews'] ?> review<?= $ratingData['total_reviews'] > 1 ? 's' : '' ?>)</span>
                    <?php endif; ?>
                </h5>

                <?php if (isset($_GET['reviewed'])): ?>
                    <div class="alert alert-success">Thank you for your review!</div>
                <?php endif; ?>
                <?php if (isset($_GET['reported'])): ?>
                    <div class="alert alert-info">Review reported. Our team will look into it.</div>
                <?php endif; ?>

                <?php if ($canReview): ?>
                    <div class="border rounded p-3 mb-3 bg-light">
                        <h6>Leave a Review</h6>
                        <form method="POST" action="<?= BASE_URL ?>/reviews/store">
                            <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
                            <input type="hidden" name="car_id" value="<?= $car['id'] ?>">

                            <div class="mb-2">
                                <label class="form-label">Rating</label>
                                <select name="rating" class="form-select" required style="max-width:150px;">
                                    <option value="5">★★★★★ (5)</option>
                                    <option value="4">★★★★ (4)</option>
                                    <option value="3">★★★ (3)</option>
                                    <option value="2">★★ (2)</option>
                                    <option value="1">★ (1)</option>
                                </select>
                            </div>
                            <div class="mb-2">
                                <label class="form-label">Comment</label>
                                <textarea name="comment" class="form-control" rows="3" placeholder="Share your experience..."></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm">Submit Review</button>
                        </form>
                    </div>
                <?php endif; ?>

                <?php if (empty($reviews)): ?>
                    <p class="text-muted">No reviews yet for this vehicle.</p>
                <?php else: ?>
                    <?php foreach ($reviews as $rev): ?>
                        <div class="border-bottom pb-2 mb-2">
                            <div class="d-flex justify-content-between">
                                <strong><?= htmlspecialchars($rev['full_name']) ?></strong>
                                <span class="text-warning"><?= str_repeat('★', $rev['rating']) . str_repeat('☆', 5 - $rev['rating']) ?></span>
                            </div>
                            <p class="mb-1"><?= nl2br(htmlspecialchars($rev['comment'])) ?></p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted small"><?= htmlspecialchars(date('d M Y', strtotime($rev['created_at']))) ?></span>
                                <?php if (isLoggedIn()): ?>
                                    <form method="POST" action="<?= BASE_URL ?>/reviews/report" onsubmit="return confirm('Report this review as inappropriate?');">
                                        <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
                                        <input type="hidden" name="review_id" value="<?= $rev['id'] ?>">
                                        <input type="hidden" name="car_id" value="<?= $car['id'] ?>">
                                        <button type="submit" class="btn btn-sm btn-link text-muted p-0">Report</button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <div class="col-md-5">
            <div class="card p-4">
                <h3><?= htmlspecialchars($car['brand'] . ' ' . $car['model']) ?></h3>
                <p class="text-muted"><?= $car['year'] ?> · <?= htmlspecialchars($car['location'] ?? '') ?></p>

                <?php if ($car['price']): ?>
                    <h4 class="text-primary">KES <?= number_format($car['price']) ?></h4>
                <?php endif; ?>
                <?php if ($car['rental_price_per_day']): ?>
                    <p>Rental: <strong>KES <?= number_format($car['rental_price_per_day']) ?>/day</strong></p>
                <?php endif; ?>

                <table class="table table-sm mt-3">
                    <tr><th>Mileage</th><td><?= $car['mileage'] ? number_format($car['mileage']) . ' km' : '-' ?></td></tr>
                    <tr><th>Fuel Type</th><td><?= htmlspecialchars(ucfirst($car['fuel_type'])) ?></td></tr>
                    <tr><th>Transmission</th><td><?= htmlspecialchars(ucfirst($car['transmission'])) ?></td></tr>
                    <tr><th>Body Type</th><td><?= htmlspecialchars($car['body_type'] ?? '-') ?></td></tr>
                    <tr><th>Seats</th><td><?= htmlspecialchars($car['seats'] ?? '-') ?></td></tr>
                    <tr><th>Condition</th><td><?= htmlspecialchars(ucfirst($car['condition_status'])) ?></td></tr>
                    <tr><th>Exterior Color</th><td><?= htmlspecialchars($car['exterior_color'] ?? '-') ?></td></tr>
                </table>

                <hr>
                <h6>Seller</h6>
                <p class="mb-1"><?= htmlspecialchars($car['business_name'] ?: $car['seller_name']) ?></p>
                <p class="text-muted small"><?= htmlspecialchars(ucfirst($car['seller_type'])) ?></p>

               <?php if (isLoggedIn() && getUserRole() === 'customer'): ?>
                    <form method="POST" action="<?= BASE_URL ?>/customer/favorites/toggle" class="d-inline">
                        <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
                        <input type="hidden" name="car_id" value="<?= $car['id'] ?>">
                        <input type="hidden" name="redirect_to" value="/cars/view?id=<?= $car['id'] ?>">
                        <button type="submit" class="btn <?= $isFavorited ? 'btn-danger' : 'btn-outline-danger' ?> mt-2">
                            <?= $isFavorited ? '♥ Remove from Favorites' : '♡ Save to Favorites' ?>
                        </button>
                    </form>
                    <br>
                    <form method="POST" action="<?= BASE_URL ?>/messages/start-from-car" class="d-inline">
                        <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
                        <input type="hidden" name="car_id" value="<?= $car['id'] ?>">
                        <button type="submit" class="btn btn-primary mt-2">Contact Seller</button>
                    </form>
                    <?php if (($car['listing_type'] === 'rent' || $car['listing_type'] === 'both') && $rental): ?>
                        <button class="btn btn-success mt-2" data-bs-toggle="modal" data-bs-target="#bookRentalModal">Book Rental</button>
                    <?php endif; ?>
                    <?php if ($car['listing_type'] === 'sale' || $car['listing_type'] === 'both'): ?>
                        <?php if ($hasPendingPurchase): ?>
                            <button class="btn btn-outline-secondary mt-2" disabled>Reservation Pending</button>
                        <?php else: ?>
                            <button class="btn btn-outline-primary mt-2" data-bs-toggle="modal" data-bs-target="#reserveModal">Reserve to Buy</button>
                        <?php endif; ?>
                    <?php endif; ?>
                <?php elseif (!isLoggedIn()): ?>
                    <a href="<?= BASE_URL ?>/login" class="btn btn-primary mt-2">Login to Contact Seller</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php if (isLoggedIn() && getUserRole() === 'customer' && ($car['listing_type'] === 'rent' || $car['listing_type'] === 'both') && $rental): ?>
<div class="modal fade" id="bookRentalModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="<?= BASE_URL ?>/rentals/book">
                <div class="modal-header">
                    <h5 class="modal-title">Book <?= htmlspecialchars($car['brand'] . ' ' . $car['model']) ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
                    <input type="hidden" name="car_id" value="<?= $car['id'] ?>">

                    <div class="mb-2">
                        <label class="form-label">Start Date</label>
                        <input type="date" name="start_date" class="form-control" required min="<?= date('Y-m-d') ?>">
                    </div>
                    <div class="mb-2">
                        <label class="form-label">End Date</label>
                        <input type="date" name="end_date" class="form-control" required min="<?= date('Y-m-d') ?>">
                    </div>
                    <p class="small text-muted">Minimum rental: <?= $rental['min_rental_days'] ?> day(s). Rate: KES <?= number_format($car['rental_price_per_day']) ?>/day. Security deposit: KES <?= number_format($rental['security_deposit']) ?>.</p>

                    <?php if ($rental['driver_option'] === 'with_driver' || $rental['driver_option'] === 'both'): ?>
                        <div class="form-check mb-2">
                            <input type="checkbox" name="driver_requested" class="form-check-input" id="driverCheck">
                            <label class="form-check-label" for="driverCheck">Request a driver</label>
                        </div>
                    <?php endif; ?>

                    <div class="mb-2">
                        <label class="form-label">Pickup Location</label>
                        <input type="text" name="pickup_location" class="form-control" value="<?= htmlspecialchars($rental['pickup_location'] ?? '') ?>">
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Return Location</label>
                        <input type="text" name="return_location" class="form-control" value="<?= htmlspecialchars($rental['return_location'] ?? '') ?>">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Confirm Booking Request</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>
<?php if (isLoggedIn() && getUserRole() === 'customer' && ($car['listing_type'] === 'sale' || $car['listing_type'] === 'both') && !$hasPendingPurchase): ?>
<div class="modal fade" id="reserveModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="<?= BASE_URL ?>/purchases/reserve">
                <div class="modal-header">
                    <h5 class="modal-title">Reserve <?= htmlspecialchars($car['brand'] . ' ' . $car['model']) ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
                    <input type="hidden" name="car_id" value="<?= $car['id'] ?>">

                    <p>Vehicle Price: <strong>KES <?= number_format($car['price']) ?></strong></p>

                    <div class="form-check mb-2">
                        <input type="radio" name="payment_type" value="booking_fee" class="form-check-input" id="bookingFeeOption" checked <?= empty($car['booking_fee']) ? 'disabled' : '' ?>>
                        <label class="form-check-label" for="bookingFeeOption">
                            Pay Booking Fee Now
                            <?php if (!empty($car['booking_fee'])): ?>
                                (KES <?= number_format($car['booking_fee']) ?>)
                            <?php else: ?>
                                <span class="text-muted small">(not available for this vehicle)</span>
                            <?php endif; ?>
                        </label>
                    </div>
                    <div class="form-check mb-2">
                        <input type="radio" name="payment_type" value="full_payment" class="form-check-input" id="fullPaymentOption" <?= empty($car['booking_fee']) ? 'checked' : '' ?>>
                        <label class="form-check-label" for="fullPaymentOption">Pay Full Price</label>
                    </div>

                    <p class="small text-muted mt-2">This creates a reservation request. The seller will confirm before payment is finalized.</p>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Submit Reservation</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>