<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reports - DriveSphere Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-dark bg-dark px-3">
    <span class="navbar-brand mb-0 h1">DriveSphere Admin</span>
    <a href="<?= BASE_URL ?>/admin/dashboard" class="btn btn-outline-light btn-sm">Back to Dashboard</a>
</nav>

<div class="container mt-4 mb-5">
    <h3>Reports & Analytics</h3>

    <div class="row g-3 mt-2">
        <div class="col-md-3">
            <div class="card p-3 text-center">
                <div class="text-muted small">Total Users</div>
                <h4><?= $userCounts['customer'] + $userCounts['seller'] + $userCounts['admin'] ?></h4>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 text-center">
                <div class="text-muted small">Total Revenue</div>
                <h4>KES <?= number_format($totalRevenue) ?></h4>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 text-center">
                <div class="text-muted small">Total Vehicles</div>
                <h4><?= $totalCars ?></h4>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 text-center">
                <div class="text-muted small">Vehicles Sold</div>
                <h4><?= $soldCars ?></h4>
            </div>
        </div>
    </div>

    <div class="row g-3 mt-1">
        <div class="col-md-3">
            <div class="card p-3 text-center">
                <div class="text-muted small">Customers</div>
                <h4><?= $userCounts['customer'] ?></h4>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 text-center">
                <div class="text-muted small">Sellers</div>
                <h4><?= $userCounts['seller'] ?></h4>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 text-center">
                <div class="text-muted small">Published Vehicles</div>
                <h4><?= $publishedCars ?></h4>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 text-center">
                <div class="text-muted small">Pending Approval</div>
                <h4><?= $pendingCars ?></h4>
            </div>
        </div>
    </div>

    <div class="row g-3 mt-1">
        <div class="col-md-3">
            <div class="card p-3 text-center">
                <div class="text-muted small">Total Rentals</div>
                <h4><?= $totalRentals ?></h4>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 text-center">
                <div class="text-muted small">Total Purchase Reservations</div>
                <h4><?= $totalPurchases ?></h4>
            </div>
        </div>
    </div>

    <div class="card p-3 mt-4">
        <h5>Most Viewed Vehicles</h5>
        <?php if (empty($topViewed)): ?>
            <p class="text-muted">No view data yet.</p>
        <?php else: ?>
            <table class="table table-sm">
                <thead><tr><th>Vehicle</th><th>Views</th></tr></thead>
                <tbody>
                    <?php foreach ($topViewed as $tv): ?>
                        <tr>
                            <td><?= htmlspecialchars($tv['brand'] . ' ' . $tv['model']) ?></td>
                            <td><?= $tv['views_count'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>
</body>
</html>