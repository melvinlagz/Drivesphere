<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>All Sales - DriveSphere Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-dark bg-dark px-3">
    <span class="navbar-brand mb-0 h1">DriveSphere Admin</span>
    <a href="<?= BASE_URL ?>/admin/dashboard" class="btn btn-outline-light btn-sm">Back to Dashboard</a>
</nav>

<div class="container mt-4 mb-5">
    <h3>All Sales</h3>

    <?php if (empty($purchases)): ?>
        <div class="alert alert-info">No purchase reservations yet.</div>
    <?php else: ?>
        <table class="table table-bordered bg-white">
            <thead>
                <tr>
                    <th>Vehicle</th>
                    <th>Customer</th>
                    <th>Seller</th>
                    <th>Payment Type</th>
                    <th>Total Price</th>
                    <th>Status</th>
                    <th>Payment</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($purchases as $p): ?>
                    <tr>
                        <td><?= htmlspecialchars($p['brand'] . ' ' . $p['model']) ?> (<?= $p['year'] ?>)</td>
                        <td><?= htmlspecialchars($p['customer_name']) ?></td>
                        <td><?= htmlspecialchars($p['seller_name']) ?></td>
                        <td><?= $p['payment_type'] === 'booking_fee' ? 'Booking Fee' : 'Full Payment' ?></td>
                        <td>KES <?= number_format($p['total_price']) ?></td>
                        <td>
                            <?php
                                $badge = match($p['status']) {
                                    'confirmed' => 'bg-success',
                                    'pending'   => 'bg-warning text-dark',
                                    'completed' => 'bg-primary',
                                    'cancelled' => 'bg-danger',
                                    default     => 'bg-secondary',
                                };
                            ?>
                            <span class="badge <?= $badge ?>"><?= htmlspecialchars($p['status']) ?></span>
                        </td>
                        <td>
                            <span class="badge <?= $p['payment_status'] === 'paid' ? 'bg-success' : 'bg-secondary' ?>">
                                <?= htmlspecialchars($p['payment_status']) ?>
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