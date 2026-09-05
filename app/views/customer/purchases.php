<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Purchases - DriveSphere</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-dark bg-dark px-3">
    <span class="navbar-brand mb-0 h1">DriveSphere</span>
    <a href="<?= BASE_URL ?>/customer/dashboard" class="btn btn-outline-light btn-sm">Back to Dashboard</a>
</nav>

<div class="container mt-4 mb-5">
    <h3>My Purchases</h3>

    <?php if (isset($_GET['reserved'])): ?>
        <div class="alert alert-success">Reservation submitted! Waiting for seller confirmation.</div>
    <?php endif; ?>

    <?php if (empty($purchases)): ?>
        <div class="alert alert-info">You have no purchase reservations yet.</div>
    <?php else: ?>
        <table class="table bg-white">
            <thead>
                <tr>
                    <th>Vehicle</th>
                    <th>Payment Type</th>
                    <th>Amount Due Now</th>
                    <th>Total Price</th>
                    <th>Status</th>
                    <th>Payment</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($purchases as $p): ?>
                    <tr>
                        <td><?= htmlspecialchars($p['brand'] . ' ' . $p['model']) ?> (<?= $p['year'] ?>)</td>
                        <td><?= $p['payment_type'] === 'booking_fee' ? 'Booking Fee' : 'Full Payment' ?></td>
                        <td>KES <?= number_format($p['payment_type'] === 'booking_fee' ? $p['booking_fee'] : $p['total_price']) ?></td>
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
                    </tr>
                        <tr>
                        <td><?= htmlspecialchars($p['brand'] . ' ' . $p['model']) ?> (<?= $p['year'] ?>)</td>
                        <td><?= $p['payment_type'] === 'booking_fee' ? 'Booking Fee' : 'Full Payment' ?></td>
                        <td>KES <?= number_format($p['payment_type'] === 'booking_fee' ? $p['booking_fee'] : $p['total_price']) ?></td>
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
                            <?php if ($p['status'] === 'confirmed' && $p['payment_status'] === 'unpaid'): ?>
                                <a href="<?= BASE_URL ?>/payments/purchase?purchase_id=<?= $p['id'] ?>" class="btn btn-sm btn-success">Pay Now</a>
                            <?php elseif ($p['payment_status'] === 'paid'): ?>
                                <span class="badge bg-primary">Paid</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
</body>
</html>