<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Conversation - DriveSphere</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-dark bg-dark px-3">
    <span class="navbar-brand mb-0 h1">DriveSphere</span>
    <a href="<?= BASE_URL ?>/messages" class="btn btn-outline-light btn-sm">Back to Inbox</a>
</nav>

<div class="container mt-4 mb-5" style="max-width: 700px;">
    <h4>
        Conversation with <?= htmlspecialchars($otherUser['full_name']) ?>
        <?php if ($car): ?>
            <span class="text-muted small">— re: <?= htmlspecialchars($car['brand'] . ' ' . $car['model']) ?></span>
        <?php endif; ?>
    </h4>

    <div class="card p-3 mb-3" style="height: 400px; overflow-y: auto;">
        <?php if (empty($messages)): ?>
            <p class="text-muted">No messages yet. Say hello!</p>
        <?php else: ?>
            <?php foreach ($messages as $msg): ?>
                <?php $isMine = $msg['sender_id'] == $_SESSION['user_id']; ?>
                <div class="mb-2 d-flex <?= $isMine ? 'justify-content-end' : 'justify-content-start' ?>">
                    <div class="p-2 rounded <?= $isMine ? 'bg-primary text-white' : 'bg-light border' ?>" style="max-width: 70%;">
                        <div><?= nl2br(htmlspecialchars($msg['message'])) ?></div>
                        <div class="small <?= $isMine ? 'text-white-50' : 'text-muted' ?>"><?= htmlspecialchars(date('d M, H:i', strtotime($msg['created_at']))) ?></div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <form method="POST" action="<?= BASE_URL ?>/messages/send">
        <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
        <input type="hidden" name="receiver_id" value="<?= $otherUser ? $_GET['user'] : '' ?>">
        <?php if ($car): ?>
            <input type="hidden" name="car_id" value="<?= $car['id'] ?>">
        <?php endif; ?>
        <div class="input-group">
            <textarea name="message" class="form-control" rows="2" placeholder="Type your message..." required></textarea>
            <button type="submit" class="btn btn-primary">Send</button>
        </div>
    </form>
</div>
</body>
</html>