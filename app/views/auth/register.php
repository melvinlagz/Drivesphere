<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register - DriveSphere</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container" style="max-width: 500px; margin-top: 60px;">
    <div class="card shadow-sm">
        <div class="card-body p-4">
            <h3 class="text-center mb-4">Create your DriveSphere account</h3>

            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        <?php foreach ($errors as $error): ?>
                            <li><?= htmlspecialchars($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form method="POST" action="<?= BASE_URL ?>/register">
                <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">

                <div class="mb-3">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="full_name" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Phone</label>
                    <input type="text" name="phone" class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">I am registering as</label>
                    <select name="role_id" class="form-select">
                        <option value="1">Customer</option>
                        <option value="2">Seller / Dealer / Rental Company</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required minlength="8">
                </div>

                <div class="mb-3">
                    <label class="form-label">Confirm Password</label>
                    <input type="password" name="confirm_password" class="form-control" required minlength="8">
                </div>

                <button type="submit" class="btn btn-primary w-100">Register</button>
            </form>

            <p class="text-center mt-3 mb-0">
                Already have an account? <a href="<?= BASE_URL ?>/login">Login here</a>
            </p>
        </div>
    </div>
</div>
</body>
</html>