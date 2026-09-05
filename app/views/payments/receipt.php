<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Receipt - DriveSphere</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-dark bg-dark px-3">
    <span class="navbar-brand mb-0 h1">DriveSphere</span>
    <a href="<?= BASE_URL ?>/<?= getUserRole() ?>/dashboard" class="btn btn-outline-light btn-sm">Back to Dashboard</a>
</nav>

<div class="container mt-4 mb-5" style="max-width: 500px;">
    <div class="card p-4">
        <?php if ($payment['status'] === 'successful'): ?>
            <div class="text-center mb-3">
                <h2 class="text-success">✓ Payment Successful</h2>
            </div>
        <?php else: ?>
            <div class="text-center mb-3">
                <h2 class="text-danger">✗ Payment Failed</h2>
            </div>
        <?php endif; ?>

        <table class="table table-sm">
            <tr><th>Receipt No.</th><td><?= htmlspecialchars($payment['transaction_ref']) ?></td></tr>
            <tr><th>Vehicle</th><td><?= htmlspecialchars(($vehicle['brand'] ?? '') . ' ' . ($vehicle['model'] ?? '')) ?> (<?= htmlspecialchars($vehicle['year'] ?? '') ?>)</td></tr>
            <tr><th>Type</th><td><?= htmlspecialchars(ucfirst($payment['reference_type'])) ?></td></tr>
            <tr><th>Amount</th><td>KES <?= number_format($payment['amount']) ?></td></tr>
            <tr><th>Method</th><td><?= htmlspecialchars(strtoupper($payment['method'])) ?></td></tr>
            <tr><th>Status</th><td><?= htmlspecialchars(ucfirst($payment['status'])) ?></td></tr>
            <tr><th>Date</th><td><?= htmlspecialchars(date('d M Y, H:i', strtotime($payment['created_at']))) ?></td></tr>
        </table>

        <button onclick="window.print()" class="btn btn-outline-secondary w-100 mt-2">Print Receipt</button>
    </div>
</div>
</body>
</html>