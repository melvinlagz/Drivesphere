<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Customer Dashboard - DriveSphere</title>
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
</nav>
<div class="container mt-4">
    <h2>Welcome, <?= htmlspecialchars($_SESSION['user_name']) ?>!</h2>
    <p class="text-muted">Customer Dashboard</p>

    <div class="row mt-4 g-3">
        <div class="col-md-3"><a href="<?= BASE_URL ?>/customer/overview" class="text-decoration-none"><div class="card p-3 text-center">Overview</div></a></div>
        <div class="col-md-3">
    <a href="<?= BASE_URL ?>/customer/rentals" class="text-decoration-none">
        <div class="card p-3 text-center">My Rentals</div>
    </a>
</div>
        <div class="col-md-3">
    <a href="<?= BASE_URL ?>/customer/purchases" class="text-decoration-none">
        <div class="card p-3 text-center">My Purchases</div>
    </a>
</div>
        <div class="col-md-3">
    <a href="<?= BASE_URL ?>/customer/favorites" class="text-decoration-none">
        <div class="card p-3 text-center">Favorites</div>
    </a>
</div>
             <div class="col-md-3">
                <a href="<?= BASE_URL ?>/payments/history" class="text-decoration-none">
                    <div class="card p-3 text-center">Payments</div>
                </a>
            </div>
        <div class="col-md-3">
    <a href="<?= BASE_URL ?>/messages" class="text-decoration-none">
        <div class="card p-3 text-center">Messages</div>
    </a>
</div>
        <div class="col-md-3"><a href="<?= BASE_URL ?>/profile" class="text-decoration-none"><div class="card p-3 text-center">Profile</div></a></div>
        <div class="col-md-3"><a href="<?= BASE_URL ?>/settings" class="text-decoration-none"><div class="card p-3 text-center">Settings</div></a></div>
    </div>
</div>
</body>
</html>