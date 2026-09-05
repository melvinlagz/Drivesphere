<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Overview - DriveSphere</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-dark bg-dark px-3">
    <span class="navbar-brand mb-0 h1">DriveSphere</span>
    <a href="<?= BASE_URL ?>/customer/dashboard" class="btn btn-outline-light btn-sm">Back to Dashboard</a>
</nav>

<div class="container mt-4 mb-5">
    <h3>Overview</h3>

    <div class="row g-3 mt-2">
        <div class="col-md-3">
            <div class="card p-3 text-center">
                <div class="text-muted small">Active Rentals</div>
                <h4><?= $activeRentals ?></h4>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 text-center">
                <div class="text-muted small">Pending Purchases</div>
                <h4><?= $pendingPurchases ?></h4>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 text-center">
                <div class="text-muted small">Favorites</div>
                <h4><?= $favoritesCount ?></h4>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 text-center">
                <div class="text-muted small">Unread Messages</div>
                <h4><?= $unreadMessages ?></h4>
            </div>
        </div>
    </div>

    <div class="row mt-4 g-3">
        <div class="col-md-4"><a href="<?= BASE_URL ?>/customer/rentals" class="text-decoration-none"><div class="card p-3 text-center">View My Rentals</div></a></div>
        <div class="col-md-4"><a href="<?= BASE_URL ?>/customer/purchases" class="text-decoration-none"><div class="card p-3 text-center">View My Purchases</div></a></div>
        <div class="col-md-4"><a href="<?= BASE_URL ?>/cars" class="text-decoration-none"><div class="card p-3 text-center">Browse Cars</div></a></div>
    </div>
</div>
</body>
</html>