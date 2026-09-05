<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payments - DriveSphere Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-dark bg-dark px-3">
    <span class="navbar-brand mb-0 h1">DriveSphere Admin</span>
    <a href="<?= BASE_URL ?>/admin/dashboard" class="btn btn-outline-light btn-sm">Back to Dashboard</a>
</nav>

<div class="container mt-4 mb-5">
    <h3>Payments</h3>
    <p class="text-muted">Total Revenue (successful payments): <strong>KES <?= number_format($totalRevenue) ?></strong></p>

    <div class="mb-3">
        <a href="?status=" class="btn btn-sm btn-outline-secondary">All</a>
        <a href="?status=successful" class="btn btn-sm btn-outline-success">Successful</a>
        <a href="?status=pending" class="btn btn-sm btn-outline-warning">Pending</a>
        <a href="?status=failed" class="btn btn-sm btn-outline-danger">Failed</a>
        <a href="?status=refunded" class="btn btn-sm btn-outline-dark">Refunded</a>
    </div>

    <?php if (empty($payments)): ?>
        <div class="alert alert-info">No payments found.</div>
    <?php else: ?>
        <table class="table table-bordered bg-white">
            <thead>
                <tr>
                    <th>Reference</th>
                    <th>User</th>
                    <th>Type</th>
                    <th>Amount</th>
                    <th>Method</th>
                    <th>Status</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($payments as $p): ?>
                    <tr>
                        <td><?= htmlspecialchars($p['transaction_ref']) ?></td>
                        <td><?= htmlspecialchars($p['full_name']) ?><br><span class="text-muted small"><?= htmlspecialchars($p['email']) ?></span></td>
                        <td><?= htmlspecialchars(ucfirst($p['reference_type'])) ?></td>
                        <td>KES <?= number_format($p['amount']) ?></td>
                        <td><?= htmlspecialchars(strtoupper($p['method'])) ?></td>
                        <td>
                            <?php
                                $badge = match($p['status']) {
                                    'successful' => 'bg-success',
                                    'pending'    => 'bg-warning text-dark',
                                    'failed'     => 'bg-danger',
                                    'refunded'   => 'bg-secondary',
                                    default      => 'bg-secondary',
                                };
                            ?>
                            <span class="badge <?= $badge ?>"><?= htmlspecialchars($p['status']) ?></span>
                        </td>
                        <td><?= htmlspecialchars(date('d M Y, H:i', strtotime($p['created_at']))) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
</body>
</html>