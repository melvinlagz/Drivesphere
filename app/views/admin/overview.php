<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Overview - DriveSphere Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-dark bg-dark px-3">
    <span class="navbar-brand mb-0 h1">DriveSphere Admin</span>
    <a href="<?= BASE_URL ?>/admin/dashboard" class="btn btn-outline-light btn-sm">Back to Dashboard</a>
</nav>

<div class="container mt-4 mb-5">
    <h3>Overview</h3>

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
                <div class="text-muted small">Pending Vehicle Approvals</div>
                <h4><?= $pendingCars ?></h4>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 text-center">
                <div class="text-muted small">Unverified Sellers</div>
                <h4><?= $unverifiedSellers ?></h4>
            </div>
        </div>
    </div>

    <div class="row mt-4 g-3">
        <div class="col-md-4"><a href="<?= BASE_URL ?>/admin/vehicles" class="text-decoration-none"><div class="card p-3 text-center">Review Pending Vehicles</div></a></div>
        <div class="col-md-4"><a href="<?= BASE_URL ?>/admin/sellers" class="text-decoration-none"><div class="card p-3 text-center">Verify Sellers</div></a></div>
        <div class="col-md-4"><a href="<?= BASE_URL ?>/admin/reports" class="text-decoration-none"><div class="card p-3 text-center">Full Reports</div></a></div>
    </div>
</div>
</body>
</html>