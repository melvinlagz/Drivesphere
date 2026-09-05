<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Listing - DriveSphere</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-dark bg-dark px-3">
    <span class="navbar-brand mb-0 h1">DriveSphere</span>
    <a href="<?= BASE_URL ?>/seller/listings" class="btn btn-outline-light btn-sm">Back to Listings</a>
</nav>

<div class="container mt-4 mb-5">
    <h3>Edit Listing</h3>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="alert alert-warning">Editing this listing will set its status back to <strong>pending</strong> for admin re-approval.</div>

    <?php if (!empty($images)): ?>
        <div class="card p-3 mb-3">
            <h5>Current Images</h5>
            <div class="row g-2">
                <?php foreach ($images as $img): ?>
                    <div class="col-md-2 text-center">
                        <img src="<?= BASE_URL ?>/<?= htmlspecialchars($img['image_path']) ?>" class="img-fluid rounded mb-1" style="height:100px; object-fit:cover; width:100%;">
                        <form method="POST" action="<?= BASE_URL ?>/seller/listings/delete-image" onsubmit="return confirm('Delete this image?');">
                            <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
                            <input type="hidden" name="image_id" value="<?= $img['id'] ?>">
                            <input type="hidden" name="car_id" value="<?= $car['id'] ?>">
                            <button type="submit" class="btn btn-sm btn-outline-danger w-100">Remove</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

    <form method="POST" action="<?= BASE_URL ?>/seller/listings/update" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
        <input type="hidden" name="car_id" value="<?= $car['id'] ?>">

        <div class="card p-4 mb-3">
            <h5>Basic Info</h5>
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Brand *</label>
                    <input type="text" name="brand" class="form-control" value="<?= htmlspecialchars($car['brand']) ?>" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Model *</label>
                    <input type="text" name="model" class="form-control" value="<?= htmlspecialchars($car['model']) ?>" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Year *</label>
                    <input type="number" name="year" class="form-control" value="<?= htmlspecialchars($car['year']) ?>" min="1950" max="2027" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Listing Type *</label>
                    <select name="listing_type" class="form-select" required>
                        <option value="sale" <?= $car['listing_type']==='sale'?'selected':'' ?>>For Sale</option>
                        <option value="rent" <?= $car['listing_type']==='rent'?'selected':'' ?>>For Rent</option>
                        <option value="both" <?= $car['listing_type']==='both'?'selected':'' ?>>Both</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Price (Sale)</label>
                    <input type="number" step="0.01" name="price" class="form-control" value="<?= htmlspecialchars($car['price'] ?? '') ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Booking Fee (Sale reservation)</label>
                    <input type="number" step="0.01" name="booking_fee" class="form-control" value="<?= htmlspecialchars($car['booking_fee'] ?? '') ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Rental Price / Day</label>
                    <input type="number" step="0.01" name="rental_price_per_day" class="form-control" value="<?= htmlspecialchars($car['rental_price_per_day'] ?? '') ?>">
                </div>
            </div>
        </div>

        <div class="card p-4 mb-3">
            <h5>Specifications</h5>
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Mileage (km)</label>
                    <input type="number" name="mileage" class="form-control" value="<?= htmlspecialchars($car['mileage'] ?? '') ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Fuel Type</label>
                    <select name="fuel_type" class="form-select">
                        <?php foreach (['petrol','diesel','electric','hybrid','lpg'] as $ft): ?>
                            <option value="<?= $ft ?>" <?= $car['fuel_type']===$ft?'selected':'' ?>><?= ucfirst($ft) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Transmission</label>
                    <select name="transmission" class="form-select">
                        <option value="automatic" <?= $car['transmission']==='automatic'?'selected':'' ?>>Automatic</option>
                        <option value="manual" <?= $car['transmission']==='manual'?'selected':'' ?>>Manual</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Engine Size</label>
                    <input type="text" name="engine_size" class="form-control" value="<?= htmlspecialchars($car['engine_size'] ?? '') ?>">
                </div>

                <div class="col-md-3">
                    <label class="form-label">Drive Type</label>
                    <select name="drive_type" class="form-select">
                        <?php foreach (['fwd','rwd','awd','4wd'] as $dt): ?>
                            <option value="<?= $dt ?>" <?= $car['drive_type']===$dt?'selected':'' ?>><?= strtoupper($dt) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Body Type</label>
                    <input type="text" name="body_type" class="form-control" value="<?= htmlspecialchars($car['body_type'] ?? '') ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Seats</label>
                    <input type="number" name="seats" class="form-control" value="<?= htmlspecialchars($car['seats'] ?? '') ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Condition</label>
                    <select name="condition_status" class="form-select">
                        <?php foreach (['new','used','certified'] as $cs): ?>
                            <option value="<?= $cs ?>" <?= $car['condition_status']===$cs?'selected':'' ?>><?= ucfirst($cs) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Exterior Color</label>
                    <input type="text" name="exterior_color" class="form-control" value="<?= htmlspecialchars($car['exterior_color'] ?? '') ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Interior Color</label>
                    <input type="text" name="interior_color" class="form-control" value="<?= htmlspecialchars($car['interior_color'] ?? '') ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Location</label>
                    <input type="text" name="location" class="form-control" value="<?= htmlspecialchars($car['location'] ?? '') ?>">
                </div>

                <div class="col-md-6">
                    <label class="form-label">VIN (optional)</label>
                    <input type="text" name="vin" class="form-control" value="<?= htmlspecialchars($car['vin'] ?? '') ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Registration Number (optional)</label>
                    <input type="text" name="registration_number" class="form-control" value="<?= htmlspecialchars($car['registration_number'] ?? '') ?>">
                </div>
            </div>
        </div>

        <div class="card p-4 mb-3">
            <h5>Description & Features</h5>
            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="4"><?= htmlspecialchars($car['description'] ?? '') ?></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Features (comma-separated)</label>
                <input type="text" name="features" class="form-control" value="<?= htmlspecialchars($car['features'] ?? '') ?>">
            </div>
        </div>
        <div class="card p-4 mb-3">
            <h5>Rental Settings <span class="text-muted small">(only used if listing type is Rent or Both)</span></h5>
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Security Deposit</label>
                    <input type="number" step="0.01" name="security_deposit" class="form-control" value="<?= htmlspecialchars($rental['security_deposit'] ?? '') ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Driver Option</label>
                    <select name="driver_option" class="form-select">
                        <?php $do = $rental['driver_option'] ?? 'self_drive'; ?>
                        <option value="self_drive" <?= $do==='self_drive'?'selected':'' ?>>Self Drive</option>
                        <option value="with_driver" <?= $do==='with_driver'?'selected':'' ?>>With Driver</option>
                        <option value="both" <?= $do==='both'?'selected':'' ?>>Both</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Min Rental Days</label>
                    <input type="number" name="min_rental_days" class="form-control" value="<?= htmlspecialchars($rental['min_rental_days'] ?? 1) ?>" min="1">
                </div>
                <div class="col-md-3"></div>
                <div class="col-md-6">
                    <label class="form-label">Default Pickup Location</label>
                    <input type="text" name="pickup_location" class="form-control" value="<?= htmlspecialchars($rental['pickup_location'] ?? '') ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Default Return Location</label>
                    <input type="text" name="return_location" class="form-control" value="<?= htmlspecialchars($rental['return_location'] ?? '') ?>">
                </div>
            </div>
        </div>

        <div class="card p-4 mb-3">
            <h5>Add More Images</h5>
            <input type="file" name="images[]" class="form-control" multiple accept="image/jpeg,image/png,image/webp">
            <small class="text-muted">Optional — adds to existing images. JPG, PNG, or WEBP, max 5MB each.</small>
        </div>

        <button type="submit" class="btn btn-primary">Update Listing</button>
    </form>
</div>
</body>
</html>