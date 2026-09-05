<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Messages - DriveSphere</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-dark bg-dark px-3">
    <span class="navbar-brand mb-0 h1">DriveSphere</span>
    <a href="<?= BASE_URL ?>/<?= getUserRole() ?>/dashboard" class="btn btn-outline-light btn-sm">Back to Dashboard</a>
</nav>

<div class="container mt-4 mb-5">
    <h3>Messages</h3>

    <?php if (empty($conversations)): ?>
        <div class="alert alert-info">No conversations yet.</div>
    <?php else: ?>
        <div class="list-group">
            <?php foreach ($conversations as $conv): ?>
                <a href="<?= BASE_URL ?>/messages/thread?user=<?= $conv['other_user_id'] ?><?= $conv['car_id'] ? '&car=' . $conv['car_id'] : '' ?>"
                   class="list-group-item list-group-item-action d-flex justify-content-between align-items-start">
                    <div>
                        <strong><?= htmlspecialchars($conv['other_user_name']) ?></strong>
                        <?php if ($conv['brand']): ?>
                            <span class="text-muted"> — re: <?= htmlspecialchars($conv['brand'] . ' ' . $conv['model']) ?></span>
                        <?php endif; ?>
                        <div class="text-muted small"><?= htmlspecialchars(mb_strimwidth($conv['last_message'] ?? '', 0, 80, '...')) ?></div>
                    </div>
                    <?php if ($conv['unread_count'] > 0): ?>
                        <span class="badge bg-primary rounded-pill"><?= $conv['unread_count'] ?></span>
                    <?php endif; ?>
                </a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
</body>
</html>