<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment History - DriveSphere</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-dark bg-dark px-3">
    <span class="navbar-brand mb-0 h1">DriveSphere</span>
    <a href="<?= BASE_URL ?>/customer/dashboard" class="btn btn-outline-light btn-sm">Back to Dashboard</a>
</nav>

<div class="container mt-4 mb-5">
    <h3>Payment History</h3>

    <?php if (empty($payments)): ?>
        <div class="alert alert-info">No payments yet.</div>
    <?php else: ?>
        <table class="table bg-white">
            <thead>
                <tr>
                    <th>Reference</th>
                    <th>Type</th>
                    <th>Amount</th>
                    <th>Method</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($payments as $p): ?>
                    <tr>
                        <td><?= htmlspecialchars($p['transaction_ref']) ?></td>
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
                        <td><?= htmlspecialchars(date('d M Y', strtotime($p['created_at']))) ?></td>
                        <td><a href="<?= BASE_URL ?>/payments/receipt?payment_id=<?= $p['id'] ?>" class="btn btn-sm btn-outline-primary">Receipt</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
</body>
</html>