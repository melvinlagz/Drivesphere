<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Listing - DriveSphere</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-dark bg-dark px-3">
    <span class="navbar-brand mb-0 h1">DriveSphere</span>
    <a href="<?= BASE_URL ?>/seller/dashboard" class="btn btn-outline-light btn-sm">Back to Dashboard</a>
</nav>

<div class="container mt-4 mb-5">
    <h3>Create New Listing</h3>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="POST" action="<?= BASE_URL ?>/seller/listings/store" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">

        <div class="card p-4 mb-3">
            <h5>Basic Info</h5>
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Brand *</label>
                    <input type="text" name="brand" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Model *</label>
                    <input type="text" name="model" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Year *</label>
                    <input type="number" name="year" class="form-control" min="1950" max="2027" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Listing Type *</label>
                    <select name="listing_type" class="form-select" required>
                        <option value="sale">For Sale</option>
                        <option value="rent">For Rent</option>
                        <option value="both">Both</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Price (Sale)</label>
                    <input type="number" step="0.01" name="price" class="form-control">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Booking Fee (Sale reservation)</label>
                    <input type="number" step="0.01" name="booking_fee" class="form-control">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Rental Price / Day</label>
                    <input type="number" step="0.01" name="rental_price_per_day" class="form-control">
                </div>
            </div>
        </div>

        <div class="card p-4 mb-3">
            <h5>Specifications</h5>
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Mileage (km)</label>
                    <input type="number" name="mileage" class="form-control">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Fuel Type</label>
                    <select name="fuel_type" class="form-select">
                        <option value="petrol">Petrol</option>
                        <option value="diesel">Diesel</option>
                        <option value="electric">Electric</option>
                        <option value="hybrid">Hybrid</option>
                        <option value="lpg">LPG</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Transmission</label>
                    <select name="transmission" class="form-select">
                        <option value="automatic">Automatic</option>
                        <option value="manual">Manual</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Engine Size</label>
                    <input type="text" name="engine_size" class="form-control" placeholder="e.g. 2.0L">
                </div>

                <div class="col-md-3">
                    <label class="form-label">Drive Type</label>
                    <select name="drive_type" class="form-select">
                        <option value="fwd">FWD</option>
                        <option value="rwd">RWD</option>
                        <option value="awd">AWD</option>
                        <option value="4wd">4WD</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Body Type</label>
                    <input type="text" name="body_type" class="form-control" placeholder="e.g. SUV, Sedan">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Seats</label>
                    <input type="number" name="seats" class="form-control">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Condition</label>
                    <select name="condition_status" class="form-select">
                        <option value="used">Used</option>
                        <option value="new">New</option>
                        <option value="certified">Certified Pre-Owned</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Exterior Color</label>
                    <input type="text" name="exterior_color" class="form-control">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Interior Color</label>
                    <input type="text" name="interior_color" class="form-control">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Location</label>
                    <input type="text" name="location" class="form-control">
                </div>

                <div class="col-md-6">
                    <label class="form-label">VIN (optional)</label>
                    <input type="text" name="vin" class="form-control">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Registration Number (optional)</label>
                    <input type="text" name="registration_number" class="form-control">
                </div>
            </div>
        </div>

        <div class="card p-4 mb-3">
            <h5>Description & Features</h5>
            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="4"></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Features (comma-separated)</label>
                <input type="text" name="features" class="form-control" placeholder="Air conditioning, Sunroof, Bluetooth...">
            </div>
        </div>
        <div class="card p-4 mb-3">
            <h5>Rental Settings <span class="text-muted small">(only used if listing type is Rent or Both)</span></h5>
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Security Deposit</label>
                    <input type="number" step="0.01" name="security_deposit" class="form-control">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Driver Option</label>
                    <select name="driver_option" class="form-select">
                        <option value="self_drive">Self Drive</option>
                        <option value="with_driver">With Driver</option>
                        <option value="both">Both</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Min Rental Days</label>
                    <input type="number" name="min_rental_days" class="form-control" value="1" min="1">
                </div>
                <div class="col-md-3"></div>
                <div class="col-md-6">
                    <label class="form-label">Default Pickup Location</label>
                    <input type="text" name="pickup_location" class="form-control">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Default Return Location</label>
                    <input type="text" name="return_location" class="form-control">
                </div>
            </div>
        </div>

        <div class="card p-4 mb-3">
            <h5>Vehicle Images</h5>
            <input type="file" name="images[]" class="form-control" multiple accept="image/jpeg,image/png,image/webp">
            <small class="text-muted">You can select multiple images. JPG, PNG, or WEBP, max 5MB each.</small>
        </div>

        <button type="submit" class="btn btn-primary">Submit Listing</button>
    </form>
</div>
</body>
</html>