<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Browse Cars - DriveSphere</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-dark bg-dark px-3">
    <a href="<?= BASE_URL ?>/" class="navbar-brand mb-0 h1">DriveSphere</a>
    <div>
        <?php if (isLoggedIn()): ?>
            <a href="<?= BASE_URL ?>/<?= getUserRole() ?>/dashboard" class="btn btn-outline-light btn-sm">Dashboard</a>
        <?php else: ?>
            <a href="<?= BASE_URL ?>/login" class="btn btn-outline-light btn-sm">Login</a>
        <?php endif; ?>
    </div>
</nav>

<div class="container mt-4">
    <h3>Browse Vehicles</h3>

    <form method="GET" action="<?= BASE_URL ?>/cars" class="card p-3 mb-4">
        <div class="row g-2">
            <div class="col-md-2">
                <input type="text" name="brand" class="form-control" placeholder="Brand" value="<?= htmlspecialchars($filters['brand']) ?>">
            </div>
            <div class="col-md-2">
                <input type="text" name="model" class="form-control" placeholder="Model" value="<?= htmlspecialchars($filters['model']) ?>">
            </div>
            <div class="col-md-2">
                <input type="number" name="min_price" class="form-control" placeholder="Min Price" value="<?= htmlspecialchars($filters['min_price']) ?>">
            </div>
            <div class="col-md-2">
                <input type="number" name="max_price" class="form-control" placeholder="Max Price" value="<?= htmlspecialchars($filters['max_price']) ?>">
            </div>
            <div class="col-md-2">
                <select name="fuel_type" class="form-select">
                    <option value="">Any Fuel</option>
                    <option value="petrol" <?= $filters['fuel_type']==='petrol'?'selected':'' ?>>Petrol</option>
                    <option value="diesel" <?= $filters['fuel_type']==='diesel'?'selected':'' ?>>Diesel</option>
                    <option value="electric" <?= $filters['fuel_type']==='electric'?'selected':'' ?>>Electric</option>
                    <option value="hybrid" <?= $filters['fuel_type']==='hybrid'?'selected':'' ?>>Hybrid</option>
                    <option value="lpg" <?= $filters['fuel_type']==='lpg'?'selected':'' ?>>LPG</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="transmission" class="form-select">
                    <option value="">Any Transmission</option>
                    <option value="automatic" <?= $filters['transmission']==='automatic'?'selected':'' ?>>Automatic</option>
                    <option value="manual" <?= $filters['transmission']==='manual'?'selected':'' ?>>Manual</option>
                </select>
            </div>

            <div class="col-md-2">
                <select name="listing_type" class="form-select">
                    <option value="">Sale or Rent</option>
                    <option value="sale" <?= $filters['listing_type']==='sale'?'selected':'' ?>>For Sale</option>
                    <option value="rent" <?= $filters['listing_type']==='rent'?'selected':'' ?>>For Rent</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="condition_status" class="form-select">
                    <option value="">Any Condition</option>
                    <option value="new" <?= $filters['condition_status']==='new'?'selected':'' ?>>New</option>
                    <option value="used" <?= $filters['condition_status']==='used'?'selected':'' ?>>Used</option>
                    <option value="certified" <?= $filters['condition_status']==='certified'?'selected':'' ?>>Certified</option>
                </select>
            </div>
            <div class="col-md-2">
                <input type="text" name="location" class="form-control" placeholder="Location" value="<?= htmlspecialchars($filters['location']) ?>">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">Search</button>
            </div>
            <div class="col-md-2">
                <a href="<?= BASE_URL ?>/cars" class="btn btn-outline-secondary w-100">Clear</a>
            </div>
        </div>
    </form>

    <p class="text-muted"><?= $totalCars ?> vehicles found</p>

    <?php if (empty($cars)): ?>
        <div class="alert alert-info">No vehicles match your search. Try adjusting your filters.</div>
    <?php else: ?>
        <div class="row g-3">
            <?php foreach ($cars as $car): ?>
                <div class="col-md-4">
                    <div class="card h-100">
                        <?php if ($car['primary_image']): ?>
                            <img src="<?= BASE_URL ?>/<?= htmlspecialchars($car['primary_image']) ?>" class="card-img-top" style="height:200px; object-fit:cover;">
                        <?php else: ?>
                            <div class="bg-secondary text-white text-center py-5">No Image</div>
                        <?php endif; ?>
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($car['brand'] . ' ' . $car['model']) ?> (<?= $car['year'] ?>)</h5>
                            <p class="mb-1">
                                <?php if ($car['price']): ?>
                                    <strong>KES <?= number_format($car['price']) ?></strong>
                                <?php endif; ?>
                                <?php if ($car['rental_price_per_day']): ?>
                                    <span class="text-muted"> | KES <?= number_format($car['rental_price_per_day']) ?>/day</span>
                                <?php endif; ?>
                            </p>
                            <p class="text-muted mb-2 small"><?= htmlspecialchars($car['location'] ?? '') ?></p>
                            <a href="<?= BASE_URL ?>/cars/view?id=<?= $car['id'] ?>" class="btn btn-sm btn-outline-primary">View Details</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <?php if ($totalPages > 1): ?>
            <nav class="mt-4">
                <ul class="pagination">
                    <?php for ($p = 1; $p <= $totalPages; $p++): ?>
                        <li class="page-item <?= $p === $page ? 'active' : '' ?>">
                            <a class="page-link" href="?<?= http_build_query(array_merge($filters, ['page' => $p])) ?>"><?= $p ?></a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>
        <?php endif; ?>
    <?php endif; ?>
</div>
</body>
</html>