<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Rental Requests - DriveSphere</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-dark bg-dark px-3">
    <span class="navbar-brand mb-0 h1">DriveSphere</span>
    <a href="<?= BASE_URL ?>/seller/dashboard" class="btn btn-outline-light btn-sm">Back to Dashboard</a>
</nav>

<div class="container mt-4 mb-5">
    <h3>Rental Requests</h3>

    <?php if (empty($bookings)): ?>
        <div class="alert alert-info">No rental bookings yet.</div>
    <?php else: ?>
        <table class="table bg-white">
            <thead>
                <tr>
                    <th>Vehicle</th>
                    <th>Customer</th>
                    <th>Dates</th>
                    <th>Total Price</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($bookings as $b): ?>
                    <tr>
                        <td><?= htmlspecialchars($b['brand'] . ' ' . $b['model']) ?> (<?= $b['year'] ?>)</td>
                        <td><?= htmlspecialchars($b['customer_name']) ?><br><span class="text-muted small"><?= htmlspecialchars($b['customer_phone'] ?? '') ?></span></td>
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
                            <?php if ($b['status'] === 'pending'): ?>
                                <form method="POST" action="<?= BASE_URL ?>/seller/rentals/update-status" style="display:inline;">
                                    <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
                                    <input type="hidden" name="booking_id" value="<?= $b['id'] ?>">
                                    <input type="hidden" name="status" value="confirmed">
                                    <button type="submit" class="btn btn-sm btn-success">Confirm</button>
                                </form>
                                <form method="POST" action="<?= BASE_URL ?>/seller/rentals/update-status" style="display:inline;">
                                    <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
                                    <input type="hidden" name="booking_id" value="<?= $b['id'] ?>">
                                    <input type="hidden" name="status" value="cancelled">
                                    <button type="submit" class="btn btn-sm btn-danger">Reject</button>
                                </form>
                            <?php elseif ($b['status'] === 'confirmed'): ?>
                                <form method="POST" action="<?= BASE_URL ?>/seller/rentals/update-status" style="display:inline;">
                                    <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
                                    <input type="hidden" name="booking_id" value="<?= $b['id'] ?>">
                                    <input type="hidden" name="status" value="completed">
                                    <button type="submit" class="btn btn-sm btn-secondary">Mark Completed</button>
                                </form>
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