<?php
require_once 'includes/config.php';
requireGuest();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    
    if (login($email, $password)) {
        header('Location: dashboard.php');
        exit();
    } else {
        $error = "Invalid email or password";
    }
}
?>

<?php include 'includes/header.php'; ?>

<main class="auth-container">
    <div class="auth-card">
        <h2>Welcome to Smart Budget Planner</h2>
        <p>Take control of your finances with our powerful budgeting tools and AI assistant.</p>
        
        <form action="index.php" method="POST">
            <div class="form-group">
                <label for="email"><i class="fas fa-envelope"></i> Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            
            <div class="form-group">
                <label for="password"><i class="fas fa-lock"></i> Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <?php if (isset($error)): ?>
                <div class="alert error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <button type="submit" class="btn-primary">Login</button>
        </form>
        
        <div class="auth-footer">
            <p>Don't have an account? <a href="register.php">Register here</a></p>
        </div>
    </div>
    
    <div class="features-container">
        <div class="feature-card">
            <i class="fas fa-chart-pie"></i>
            <h3>Track Spending</h3>
            <p>Visualize your expenses with beautiful charts and graphs.</p>
        </div>
        
        <div class="feature-card">
            <i class="fas fa-robot"></i>
            <h3>AI Assistant</h3>
            <p>Get personalized financial advice from our AI assistant.</p>
        </div>
        
        <div class="feature-card">
            <i class="fas fa-bullseye"></i>
            <h3>Set Goals</h3>
            <p>Create and track financial goals to improve your savings.</p>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>