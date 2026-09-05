<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Make Payment - DriveSphere</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-dark bg-dark px-3">
    <span class="navbar-brand mb-0 h1">DriveSphere</span>
    <a href="<?= BASE_URL ?>/customer/dashboard" class="btn btn-outline-light btn-sm">Back to Dashboard</a>
</nav>

<div class="container mt-4 mb-5" style="max-width: 500px;">
    <div class="card p-4">
        <h4>Make Payment</h4>
        <p class="text-muted"><?= htmlspecialchars($description) ?></p>
        <h3 class="text-primary">KES <?= number_format($amount) ?></h3>

        <form method="POST" action="<?= BASE_URL ?>/payments/process" class="mt-3">
            <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
            <input type="hidden" name="type" value="<?= $type ?>">
            <input type="hidden" name="reference_id" value="<?= $referenceId ?>">
            <input type="hidden" name="amount" value="<?= $amount ?>">

            <div class="mb-3">
                <label class="form-label">Payment Method</label>
                <select name="method" class="form-select" required>
                    <option value="mpesa">M-Pesa</option>
                    <option value="visa">Visa</option>
                    <option value="mastercard">Mastercard</option>
                    <option value="bank_transfer">Bank Transfer</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Phone Number (for M-Pesa)</label>
                <input type="text" name="phone" class="form-control" placeholder="e.g. 0712345678">
            </div>

            <div class="alert alert-info small">
                This is a simulated payment for development/testing. No real money will be charged.
            </div>

            <button type="submit" class="btn btn-success w-100">Pay Now</button>
        </form>
    </div>
</div>
</body>
</html>