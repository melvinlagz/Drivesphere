<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - DriveSphere</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-dark bg-dark px-3">
    <span class="navbar-brand mb-0 h1">DriveSphere Admin</span>
    <div>
        <a href="<?= BASE_URL ?>/cars" class="btn btn-outline-light btn-sm me-2">Browse Cars</a>
        <a href="<?= BASE_URL ?>/notifications" class="btn btn-outline-light btn-sm me-2">
            🔔 Notifications
            <?php if ($unreadCount > 0): ?>
                <span class="badge bg-danger"><?= $unreadCount ?></span>
            <?php endif; ?>
        </a>
        <a href="<?= BASE_URL ?>/logout" class="btn btn-outline-light btn-sm">Logout</a>
    </div>
</nav>
<div class="container mt-4">
    <h2>Welcome, <?= htmlspecialchars($_SESSION['user_name']) ?>!</h2>
    <p class="text-muted">Admin Dashboard</p>

    <div class="row mt-2 g-3">
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
                <div class="text-muted small">Pending Approval</div>
                <h4><?= $pendingCars ?></h4>
            </div>
        </div>
    </div>

    <div class="row mt-4 g-3">
        <div class="col-md-3">
            <a href="<?= BASE_URL ?>/admin/users" class="text-decoration-none"><div class="card p-3 text-center">Users</div></a>
        </div>
        <div class="col-md-3">
            <a href="<?= BASE_URL ?>/admin/sellers" class="text-decoration-none"><div class="card p-3 text-center">Sellers</div></a>
        </div>
        <div class="col-md-3">
            <a href="<?= BASE_URL ?>/admin/vehicles" class="text-decoration-none"><div class="card p-3 text-center">Vehicles</div></a>
        </div>
        <div class="col-md-3">
            <a href="<?= BASE_URL ?>/admin/payments" class="text-decoration-none"><div class="card p-3 text-center">Payments</div></a>
        </div>
        <div class="col-md-3"><a href="<?= BASE_URL ?>/admin/rentals" class="text-decoration-none"><div class="card p-3 text-center">Rentals</div></a></div>
        <div class="col-md-3"><a href="<?= BASE_URL ?>/admin/sales" class="text-decoration-none"><div class="card p-3 text-center">Sales</div></a></div>
        <div class="col-md-3">
            <a href="<?= BASE_URL ?>/admin/reports" class="text-decoration-none"><div class="card p-3 text-center">Reports</div></a>
        <div class="col-md-3"><a href="<?= BASE_URL ?>/settings" class="text-decoration-none"><div class="card p-3 text-center">Settings</div></a></div>
</div>
</body>
</html>