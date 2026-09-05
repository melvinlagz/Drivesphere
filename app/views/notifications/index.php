<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Notifications - DriveSphere</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-dark bg-dark px-3">
    <span class="navbar-brand mb-0 h1">DriveSphere</span>
    <a href="<?= BASE_URL ?>/<?= getUserRole() ?>/dashboard" class="btn btn-outline-light btn-sm">Back to Dashboard</a>
</nav>

<div class="container mt-4 mb-5" style="max-width: 700px;">
    <h3>Notifications</h3>

    <?php if (empty($notifications)): ?>
        <div class="alert alert-info">No notifications yet.</div>
    <?php else: ?>
        <div class="list-group">
            <?php foreach ($notifications as $n): ?>
                <div class="list-group-item <?= !$n['is_read'] ? 'list-group-item-light border-start border-primary border-3' : '' ?>">
                    <div class="d-flex justify-content-between">
                        <strong><?= htmlspecialchars($n['title']) ?></strong>
                        <span class="text-muted small"><?= htmlspecialchars(date('d M Y, H:i', strtotime($n['created_at']))) ?></span>
                    </div>
                    <?php if (!empty($n['body'])): ?>
                        <p class="mb-0 text-muted small"><?= htmlspecialchars($n['body']) ?></p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
</body>
</html>