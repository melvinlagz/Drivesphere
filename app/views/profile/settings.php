<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Settings - DriveSphere</title>
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
        <h4>Account Settings</h4>

        <?php if (isset($_GET['updated'])): ?>
            <div class="alert alert-success">Password changed successfully.</div>
        <?php endif; ?>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php foreach ($errors as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <h6 class="mt-3">Change Password</h6>
        <form method="POST" action="<?= BASE_URL ?>/settings/update-password">
            <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">

            <div class="mb-3">
                <label class="form-label">Current Password</label>
                <input type="password" name="current_password" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">New Password</label>
                <input type="password" name="new_password" class="form-control" required minlength="8">
            </div>

            <div class="mb-3">
                <label class="form-label">Confirm New Password</label>
                <input type="password" name="confirm_password" class="form-control" required minlength="8">
            </div>

            <button type="submit" class="btn btn-primary">Change Password</button>
        </form>
    </div>
</div>
</body>
</html>