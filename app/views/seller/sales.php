<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sales - DriveSphere</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-dark bg-dark px-3">
    <span class="navbar-brand mb-0 h1">DriveSphere</span>
    <a href="<?= BASE_URL ?>/seller/dashboard" class="btn btn-outline-light btn-sm">Back to Dashboard</a>
</nav>

<div class="container mt-4 mb-5">
    <h3>Sales / Reservations</h3>

    <?php if (empty($purchases)): ?>
        <div class="alert alert-info">No purchase reservations yet.</div>
    <?php else: ?>
        <table class="table bg-white">
            <thead>
                <tr>
                    <th>Vehicle</th>
                    <th>Customer</th>
                    <th>Payment Type</th>
                    <th>Total Price</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($purchases as $p): ?>
                    <tr>
                        <td><?= htmlspecialchars($p['brand'] . ' ' . $p['model']) ?> (<?= $p['year'] ?>)</td>
                        <td><?= htmlspecialchars($p['customer_name']) ?><br><span class="text-muted small"><?= htmlspecialchars($p['customer_phone'] ?? '') ?></span></td>
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
                            <?php if ($p['status'] === 'pending'): ?>
                                <form method="POST" action="<?= BASE_URL ?>/seller/sales/update-status" style="display:inline;">
                                    <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
                                    <input type="hidden" name="purchase_id" value="<?= $p['id'] ?>">
                                    <input type="hidden" name="status" value="confirmed">
                                    <button type="submit" class="btn btn-sm btn-success">Confirm</button>
                                </form>
                                <form method="POST" action="<?= BASE_URL ?>/seller/sales/update-status" style="display:inline;">
                                    <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
                                    <input type="hidden" name="purchase_id" value="<?= $p['id'] ?>">
                                    <input type="hidden" name="status" value="cancelled">
                                    <button type="submit" class="btn btn-sm btn-danger">Cancel</button>
                                </form>
                            <?php elseif ($p['status'] === 'confirmed'): ?>
                                <form method="POST" action="<?= BASE_URL ?>/seller/sales/update-status" style="display:inline;">
                                    <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
                                    <input type="hidden" name="purchase_id" value="<?= $p['id'] ?>">
                                    <input type="hidden" name="status" value="completed">
                                    <button type="submit" class="btn btn-sm btn-primary">Mark Sale Completed</button>
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