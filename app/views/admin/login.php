<?php
$pageTitle = 'Login - MiniEvent';

include __DIR__ . '/../partials/head.php';
include __DIR__ . '/../partials/header.php';
?>

<div class="container">
    <div class="auth-container">
        <div class="auth-card">
            <h2>Login</h2>
            <p class="auth-subtitle">Sign in to manage your events</p>

            <?php if (!empty($_SESSION['success'])): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($_SESSION['success']); ?></div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>

            <?php if (!empty($_SESSION['error'])): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($_SESSION['error']); ?></div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <form method="POST" action="/admin/login">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required class="form-control">
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required class="form-control">
                </div>

                <button type="submit" class="btn btn-primary btn-block">Login</button>
            </form>

            <p class="auth-footer">
                Don't have an account?
                <a href="/admin/register">Register here</a>
            </p>

            <div class="demo-credentials">
                <p><strong>Demo Credentials:</strong></p>
                <p>Admin: admin@minievent.com / password</p>
                <p>Organizer: organizer1@minievent.com / password</p>
                <p>Participant: user1@minievent.com / password</p>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../partials/footer.php'; ?>
