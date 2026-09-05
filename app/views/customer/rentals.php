<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Rentals - DriveSphere</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-dark bg-dark px-3">
    <span class="navbar-brand mb-0 h1">DriveSphere</span>
    <a href="<?= BASE_URL ?>/customer/dashboard" class="btn btn-outline-light btn-sm">Back to Dashboard</a>
</nav>

<div class="container mt-4 mb-5">
    <h3>My Rentals</h3>

    <?php if (isset($_GET['booked'])): ?>
        <div class="alert alert-success">Booking request submitted! Waiting for seller confirmation.</div>
    <?php endif; ?>

    <?php if (empty($bookings)): ?>
        <div class="alert alert-info">You have no rental bookings yet.</div>
    <?php else: ?>
        <table class="table bg-white">
            <thead>
                <tr>
                    <th>Vehicle</th>
                    <th>Dates</th>
                    <th>Days</th>
                    <th>Total Price</th>
                    <th>Status</th>
                    <th>Payment</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($bookings as $b): ?>
                    <tr>
                        <td><?= htmlspecialchars($b['brand'] . ' ' . $b['model']) ?> (<?= $b['year'] ?>)</td>
                        <td><?= htmlspecialchars($b['start_date']) ?> to <?= htmlspecialchars($b['end_date']) ?></td>
                        <td><?= $b['total_days'] ?></td>
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
                    </tr>
                        <tr>
                        <td><?= htmlspecialchars($b['brand'] . ' ' . $b['model']) ?> (<?= $b['year'] ?>)</td>
                        <td><?= htmlspecialchars($b['start_date']) ?> to <?= htmlspecialchars($b['end_date']) ?></td>
                        <td><?= $b['total_days'] ?></td>
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
                            <?php if ($b['status'] === 'confirmed' && $b['payment_status'] === 'unpaid'): ?>
                                <a href="<?= BASE_URL ?>/payments/rental?booking_id=<?= $b['id'] ?>" class="btn btn-sm btn-success">Pay Now</a>
                            <?php elseif ($b['payment_status'] === 'paid'): ?>
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