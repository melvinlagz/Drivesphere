<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Favorites - DriveSphere</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-dark bg-dark px-3">
    <span class="navbar-brand mb-0 h1">DriveSphere</span>
    <a href="<?= BASE_URL ?>/customer/dashboard" class="btn btn-outline-light btn-sm">Back to Dashboard</a>
</nav>

<div class="container mt-4 mb-5">
    <h3>My Favorites</h3>

    <?php if (empty($favorites)): ?>
        <div class="alert alert-info">You haven't saved any vehicles yet. <a href="<?= BASE_URL ?>/cars">Browse cars</a> to add some.</div>
    <?php else: ?>
        <div class="row g-3">
            <?php foreach ($favorites as $car): ?>
                <div class="col-md-4">
                    <div class="card h-100">
                        <?php if ($car['primary_image']): ?>
                            <img src="<?= BASE_URL ?>/<?= htmlspecialchars($car['primary_image']) ?>" class="card-img-top" style="height:200px; object-fit:cover;">
                        <?php else: ?>
                            <div class="bg-secondary text-white text-center py-5">No Image</div>
                        <?php endif; ?>
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($car['brand'] . ' ' . $car['model']) ?> (<?= $car['year'] ?>)</h5>
                            <p class="mb-2">
                                <?php if ($car['price']): ?><strong>KES <?= number_format($car['price']) ?></strong><?php endif; ?>
                                <?php if ($car['status'] !== 'published'): ?>
                                    <span class="badge bg-secondary ms-1">No longer available</span>
                                <?php endif; ?>
                            </p>
                            <a href="<?= BASE_URL ?>/cars/view?id=<?= $car['id'] ?>" class="btn btn-sm btn-outline-primary">View Details</a>
                            <form method="POST" action="<?= BASE_URL ?>/customer/favorites/toggle" style="display:inline;">
                                <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
                                <input type="hidden" name="car_id" value="<?= $car['id'] ?>">
                                <input type="hidden" name="redirect_to" value="/customer/favorites">
                                <button type="submit" class="btn btn-sm btn-outline-danger">Remove</button>
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