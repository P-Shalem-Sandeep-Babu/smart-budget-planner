<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo APP_NAME; ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/chart.js@3.7.1/dist/chart.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container">
        <?php if (isLoggedIn()): ?>
        <header class="header">
            <div class="logo">
                <h1><i class="fas fa-wallet"></i> Smart Budget</h1>
            </div>
            <nav class="nav">
                <ul>
                    <li><a href="dashboard.php"><i class="fas fa-home"></i> Dashboard</a></li>
                    <li><a href="transactions.php"><i class="fas fa-exchange-alt"></i> Transactions</a></li>
                    <li><a href="add-transaction.php"><i class="fas fa-plus-circle"></i> Add Transaction</a></li>
                    <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                </ul>
            </nav>
            <div class="user-info">
                <span><i class="fas fa-user"></i> <?php echo htmlspecialchars(getUser()['username']); ?></span>
            </div>
        </header>
        <?php endif; ?>