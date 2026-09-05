<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Sellers - DriveSphere Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-dark bg-dark px-3">
    <span class="navbar-brand mb-0 h1">DriveSphere Admin</span>
    <a href="<?= BASE_URL ?>/admin/dashboard" class="btn btn-outline-light btn-sm">Back to Dashboard</a>
</nav>

<div class="container mt-4 mb-5">
    <h3>Manage Sellers</h3>

    <div class="mb-3">
        <a href="?filter=" class="btn btn-sm btn-outline-secondary">All</a>
        <a href="?filter=verified" class="btn btn-sm btn-outline-success">Verified</a>
        <a href="?filter=unverified" class="btn btn-sm btn-outline-warning">Unverified</a>
    </div>

    <?php if (empty($sellers)): ?>
        <div class="alert alert-info">No sellers found.</div>
    <?php else: ?>
        <table class="table table-bordered bg-white">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Business Name</th>
                    <th>Type</th>
                    <th>Email</th>
                    <th>Listings</th>
                    <th>Verified</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($sellers as $seller): ?>
                    <tr>
                        <td><?= htmlspecialchars($seller['full_name']) ?></td>
                        <td><?= htmlspecialchars($seller['business_name'] ?: '-') ?></td>
                        <td><?= htmlspecialchars(ucfirst(str_replace('_', ' ', $seller['seller_type']))) ?></td>
                        <td><?= htmlspecialchars($seller['email']) ?></td>
                        <td><?= $seller['total_listings'] ?></td>
                        <td>
                            <span class="badge <?= $seller['is_verified'] ? 'bg-success' : 'bg-secondary' ?>">
                                <?= $seller['is_verified'] ? 'Verified' : 'Not Verified' ?>
                            </span>
                        </td>
                        <td>
                            <form method="POST" action="<?= BASE_URL ?>/admin/sellers/toggle-verification" style="display:inline;">
                                <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
                                <input type="hidden" name="seller_id" value="<?= $seller['id'] ?>">
                                <button type="submit" class="btn btn-sm <?= $seller['is_verified'] ? 'btn-outline-secondary' : 'btn-success' ?>">
                                    <?= $seller['is_verified'] ? 'Unverify' : 'Verify' ?>
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
</body>
</html>