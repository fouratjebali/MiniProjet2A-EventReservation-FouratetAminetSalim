<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<header>
    <nav class="navbar">
        <div class="nav-container">
            <a href="/" class="logo">MiniEvent</a>
            <ul class="nav-menu">
                <li><a href="/">Home</a></li>
                <li><a href="/event/list">Events</a></li>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <?php if ($_SESSION['role'] === 'admin'): ?>
                        <li><a href="/admin/dashboard">Admin</a></li>
                    <?php elseif ($_SESSION['role'] === 'organizer'): ?>
                        <li><a href="/event/myEvents">My Events</a></li>
                    <?php else: ?>
                        <li><a href="/event/myRegistrations">My Registrations</a></li>
                    <?php endif; ?>
                    <li><a href="/admin/logout">Logout (<?php echo htmlspecialchars($_SESSION['username']); ?>)</a></li>
                <?php else: ?>
                    <li><a href="/admin/login">Login</a></li>
                    <li><a href="/admin/register">Register</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>
</header>