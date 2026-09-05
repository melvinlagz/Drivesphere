<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Earnings - DriveSphere</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-dark bg-dark px-3">
    <span class="navbar-brand mb-0 h1">DriveSphere</span>
    <a href="<?= BASE_URL ?>/seller/dashboard" class="btn btn-outline-light btn-sm">Back to Dashboard</a>
</nav>

<div class="container mt-4 mb-5">
    <h3>Earnings</h3>

    <div class="row g-3 mt-2">
        <div class="col-md-3">
            <div class="card p-3 text-center">
                <div class="text-muted small">Total Sales Revenue</div>
                <h4>KES <?= number_format($saleEarnings['total_sales'] ?? 0) ?></h4>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 text-center">
                <div class="text-muted small">Completed Sales</div>
                <h4><?= $saleEarnings['completed_sales'] ?? 0 ?></h4>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 text-center">
                <div class="text-muted small">Total Rental Income</div>
                <h4>KES <?= number_format($rentalEarnings['total_rental_income'] ?? 0) ?></h4>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 text-center">
                <div class="text-muted small">Completed Rentals</div>
                <h4><?= $rentalEarnings['completed_rentals'] ?? 0 ?></h4>
            </div>
        </div>
    </div>

    <div class="card p-3 mt-4">
        <h5>Grand Total Earnings</h5>
        <h3 class="text-success">
            KES <?= number_format(($saleEarnings['total_sales'] ?? 0) + ($rentalEarnings['total_rental_income'] ?? 0)) ?>
        </h3>
    </div>
</div>
</body>
</html>