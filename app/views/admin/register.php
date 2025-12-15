<?php 
$pageTitle = 'Register - MiniEvent';
include 'app/views/partials/header.php'; 
?>

<div class="container">
    <div class="auth-container">
        <div class="auth-card">
            <h2>Register</h2>
            <p class="auth-subtitle">Create your account</p>
            
            <form method="POST" action="/admin/register">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required class="form-control">
                </div>
                
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required class="form-control">
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required minlength="6" class="form-control">
                </div>
                
                <div class="form-group">
                    <label for="role">Register as</label>
                    <select id="role" name="role" class="form-control">
                        <option value="participant">Participant</option>
                        <option value="organizer">Event Organizer</option>
                    </select>
                </div>
                
                <button type="submit" class="btn btn-primary btn-block">Register</button>
            </form>
            
            <p class="auth-footer">
                Already have an account? <a href="/admin/login">Login here</a>
            </p>
        </div>
    </div>
</div>

<?php include 'app/views/partials/footer.php'; ?>