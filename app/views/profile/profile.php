<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Profile - DriveSphere</title>
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
        <h4>My Profile</h4>

        <?php if (isset($_GET['updated'])): ?>
            <div class="alert alert-success">Profile updated successfully.</div>
        <?php endif; ?>

        <form method="POST" action="<?= BASE_URL ?>/profile/update">
            <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">

            <div class="mb-3">
                <label class="form-label">Full Name</label>
                <input type="text" name="full_name" class="form-control" value="<?= htmlspecialchars($user['full_name']) ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" disabled>
                <small class="text-muted">Email cannot be changed.</small>
            </div>

            <div class="mb-3">
                <label class="form-label">Phone</label>
                <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($user['phone'] ?? '') ?>">
            </div>

            <div class="mb-3">
                <label class="form-label">Role</label>
                <input type="text" class="form-control" value="<?= htmlspecialchars(ucfirst(getUserRole())) ?>" disabled>
            </div>

            <button type="submit" class="btn btn-primary">Save Changes</button>
        </form>
    </div>
</div>
</body>
</html>