<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>All Rentals - DriveSphere Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-dark bg-dark px-3">
    <span class="navbar-brand mb-0 h1">DriveSphere Admin</span>
    <a href="<?= BASE_URL ?>/admin/dashboard" class="btn btn-outline-light btn-sm">Back to Dashboard</a>
</nav>

<div class="container mt-4 mb-5">
    <h3>All Rentals</h3>

    <?php if (empty($bookings)): ?>
        <div class="alert alert-info">No rental bookings yet.</div>
    <?php else: ?>
        <table class="table table-bordered bg-white">
            <thead>
                <tr>
                    <th>Vehicle</th>
                    <th>Customer</th>
                    <th>Seller</th>
                    <th>Dates</th>
                    <th>Total Price</th>
                    <th>Status</th>
                    <th>Payment</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($bookings as $b): ?>
                    <tr>
                        <td><?= htmlspecialchars($b['brand'] . ' ' . $b['model']) ?> (<?= $b['year'] ?>)</td>
                        <td><?= htmlspecialchars($b['customer_name']) ?></td>
                        <td><?= htmlspecialchars($b['seller_name']) ?></td>
                        <td><?= htmlspecialchars($b['start_date']) ?> to <?= htmlspecialchars($b['end_date']) ?></td>
                        <td>KES <?= number_format($b['total_price']) ?></td>
                        <td>
                            <?php
                                $badge = match($b['status']) {
                                    'confirmed' => 'bg-success',
                                    'pending'   => 'bg-warning text-dark',
                                    'ongoing'   => 'bg-info text-dark',
                                    'completed' => 'bg-secondary',
                                    'cancelled' => 'bg-danger',
                                    default     => 'bg-secondary',
                                };
                            ?>
                            <span class="badge <?= $badge ?>"><?= htmlspecialchars($b['status']) ?></span>
                        </td>
                        <td>
                            <span class="badge <?= $b['payment_status'] === 'paid' ? 'bg-success' : 'bg-secondary' ?>">
                                <?= htmlspecialchars($b['payment_status']) ?>
                            </span>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
</body>
</html>