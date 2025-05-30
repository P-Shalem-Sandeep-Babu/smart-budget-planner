<?php
// Check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Redirect if not logged in
function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit();
    }
}

// Redirect if logged in
function requireGuest() {
    if (isLoggedIn()) {
        header('Location: dashboard.php');
        exit();
    }
}

// Get current user ID
function getUserId() {
    return $_SESSION['user_id'] ?? null;
}

// Get current user data
function getUser() {
    if (!isLoggedIn()) return null;
    
    global $pdo; // Add this line to access the PDO connection
    $user = fetchOne("SELECT * FROM users WHERE id = ?", [getUserId()]);
    return $user;
}

// Login user
function login($email, $password) {
    global $pdo; // Add this line to access the PDO connection
    
    $user = fetchOne("SELECT * FROM users WHERE email = ?", [$email]);
    
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        return true;
    }
    
    return false;
}

// Register user
function register($username, $email, $password) {
    global $pdo; // Add this line to access the PDO connection
    
    $existingUser = fetchOne("SELECT id FROM users WHERE email = ?", [$email]);
    
    if ($existingUser) {
        return false; // Email already exists
    }
    
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $result = executeQuery(
        "INSERT INTO users (username, email, password) VALUES (?, ?, ?)",
        [$username, $email, $hashedPassword]
    );
    
    if ($result) {
        $userId = $pdo->lastInsertId(); // Now $pdo is accessible
        $_SESSION['user_id'] = $userId;
        return true;
    }
    
    return false;
}
?>