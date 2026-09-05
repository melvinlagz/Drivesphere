<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Vehicles - DriveSphere Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-dark bg-dark px-3">
    <span class="navbar-brand mb-0 h1">DriveSphere Admin</span>
    <a href="<?= BASE_URL ?>/admin/dashboard" class="btn btn-outline-light btn-sm">Back to Dashboard</a>
</nav>

<div class="container mt-4 mb-5">
    <h3>Manage Vehicles</h3>

    <div class="mb-3">
        <a href="?status=" class="btn btn-sm btn-outline-secondary">All</a>
        <a href="?status=pending" class="btn btn-sm btn-outline-warning">Pending</a>
        <a href="?status=published" class="btn btn-sm btn-outline-success">Published</a>
        <a href="?status=rejected" class="btn btn-sm btn-outline-danger">Rejected</a>
    </div>

    <?php if (empty($cars)): ?>
        <div class="alert alert-info">No vehicles found for this filter.</div>
    <?php else: ?>
        <table class="table table-bordered bg-white">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Vehicle</th>
                    <th>Seller</th>
                    <th>Listing Type</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cars as $car): ?>
                    <tr>
                        <td><?= $car['id'] ?></td>
                        <td><?= htmlspecialchars($car['brand'] . ' ' . $car['model']) ?> (<?= $car['year'] ?>)</td>
                        <td><?= htmlspecialchars($car['business_name'] ?: $car['seller_name']) ?></td>
                        <td><?= htmlspecialchars($car['listing_type']) ?></td>
                        <td>
                            <?php
                                $badgeClass = match($car['status']) {
                                    'published' => 'bg-success',
                                    'pending'   => 'bg-warning text-dark',
                                    'rejected'  => 'bg-danger',
                                    default     => 'bg-secondary',
                                };
                            ?>
                            <span class="badge <?= $badgeClass ?>"><?= htmlspecialchars($car['status']) ?></span>
                        </td>
                        <td><?= htmlspecialchars(date('d M Y', strtotime($car['created_at']))) ?></td>
                        <td>
                            <a href="<?= BASE_URL ?>/cars/view?id=<?= $car['id'] ?>" target="_blank" class="btn btn-sm btn-outline-primary">View</a>

                            <?php if ($car['status'] !== 'published'): ?>
                                <form method="POST" action="<?= BASE_URL ?>/admin/vehicles/approve" style="display:inline;">
                                    <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
                                    <input type="hidden" name="car_id" value="<?= $car['id'] ?>">
                                    <button type="submit" class="btn btn-sm btn-success">Approve</button>
                                </form>
                            <?php endif; ?>

                            <?php if ($car['status'] !== 'rejected'): ?>
                                <form method="POST" action="<?= BASE_URL ?>/admin/vehicles/reject" style="display:inline;">
                                    <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
                                    <input type="hidden" name="car_id" value="<?= $car['id'] ?>">
                                    <button type="submit" class="btn btn-sm btn-danger">Reject</button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
</body>
</html>