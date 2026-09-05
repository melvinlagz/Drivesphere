<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Listings - DriveSphere</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-dark bg-dark px-3">
    <span class="navbar-brand mb-0 h1">DriveSphere</span>
    <a href="<?= BASE_URL ?>/seller/dashboard" class="btn btn-outline-light btn-sm">Back to Dashboard</a>
</nav>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>My Listings</h3>
        <a href="<?= BASE_URL ?>/seller/listings/create" class="btn btn-primary">+ New Listing</a>
    </div>

    <?php if (empty($cars)): ?>
        <p class="text-muted">You haven't listed any vehicles yet.</p>
    <?php else: ?>
        <div class="row g-3">
            <?php foreach ($cars as $car): ?>
                <div class="col-md-4">
                    <div class="card">
                        <?php if ($car['primary_image']): ?>
                            <img src="<?= BASE_URL ?>/<?= htmlspecialchars($car['primary_image']) ?>" class="card-img-top" style="height:180px; object-fit:cover;">
                        <?php else: ?>
                            <div class="bg-secondary text-white text-center py-5">No Image</div>
                        <?php endif; ?>
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($car['brand'] . ' ' . $car['model']) ?> (<?= $car['year'] ?>)</h5>
                            <p class="mb-1">Status: <span class="badge bg-info text-dark"><?= htmlspecialchars($car['status']) ?></span></p>
                            <p class="mb-2">Type: <?= htmlspecialchars($car['listing_type']) ?></p>
                            <a href="<?= BASE_URL ?>/seller/listings/edit?id=<?= $car['id'] ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                            <form method="POST" action="<?= BASE_URL ?>/seller/listings/delete" style="display:inline;" onsubmit="return confirm('Delete this listing permanently?');">
                                <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
                                <input type="hidden" name="car_id" value="<?= $car['id'] ?>">
                                <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
</body>
</html>