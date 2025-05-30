<?php
// Application configuration
define('APP_NAME', 'Smart Budget Planner');
define('APP_URL', 'http://localhost/smart-budget-planner');

// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'budget_planner');

// Start session
session_start();

// Include other required files
require_once 'db.php';
require_once 'auth.php';
?>