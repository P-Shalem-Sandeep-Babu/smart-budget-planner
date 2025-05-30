<?php
require_once 'includes/config.php';
requireLogin();

$userId = getUserId();

// Get filter parameters
$type = $_GET['type'] ?? '';
$category = $_GET['category'] ?? '';
$month = $_GET['month'] ?? date('Y-m');

// Build query
$query = "SELECT * FROM transactions WHERE user_id = ?";
$params = [$userId];

if (!empty($type)) {
    $query .= " AND type = ?";
    $params[] = $type;
}

if (!empty($category)) {
    $query .= " AND category = ?";
    $params[] = $category;
}

if (!empty($month)) {
    $query .= " AND DATE_FORMAT(date, '%Y-%m') = ?";
    $params[] = $month;
}

$query .= " ORDER BY date DESC";

$transactions = fetchAll($query, $params);

// Get distinct categories for filter
$categories = fetchAll("
    SELECT DISTINCT category 
    FROM transactions 
    WHERE user_id = ? 
    ORDER BY category
", [$userId]);

// Get distinct months for filter
$months = fetchAll("
    SELECT DISTINCT DATE_FORMAT(date, '%Y-%m') as month 
    FROM transactions 
    WHERE user_id = ? 
    ORDER BY month DESC
", [$userId]);
?>

<?php include 'includes/header.php'; ?>

<main class="transactions">
    <h2>Transaction History</h2>
    
    <div class="filters">
        <form method="GET" class="filter-form">
            <div class="form-group">
                <label for="type">Type</label>
                <select id="type" name="type">
                    <option value="">All Types</option>
                    <option value="income" <?php echo $type === 'income' ? 'selected' : ''; ?>>Income</option>
                    <option value="expense" <?php echo $type === 'expense' ? 'selected' : ''; ?>>Expense</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="category">Category</label>
                <select id="category" name="category">
                    <option value="">All Categories</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?php echo htmlspecialchars($cat['category']); ?>" 
                            <?php echo $category === $cat['category'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($cat['category']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label for="month">Month</label>
                <select id="month" name="month">
                    <?php foreach ($months as $m): ?>
                        <option value="<?php echo htmlspecialchars($m['month']); ?>" 
                            <?php echo $month === $m['month'] ? 'selected' : ''; ?>>
                            <?php echo date('F Y', strtotime($m['month'] . '-01')); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <button type="submit" class="btn-filter">Apply Filters</button>
            <a href="transactions.php" class="btn-reset">Reset</a>
        </form>
    </div>
    
    <?php if (empty($transactions)): ?>
        <div class="empty-state">
            <p>No transactions found matching your criteria.</p>
            <a href="add-transaction.php" class="btn-primary">Add Transaction</a>
        </div>
    <?php else: ?>
        <div class="transactions-table">
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Description</th>
                        <th>Category</th>
                        <th>Type</th>
                        <th>Amount</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($transactions as $transaction): ?>
                        <tr>
                            <td><?php echo date('M j, Y', strtotime($transaction['date'])); ?></td>
                            <td><?php echo htmlspecialchars($transaction['description']); ?></td>
                            <td><?php echo htmlspecialchars($transaction['category']); ?></td>
                            <td>
                                <span class="badge <?php echo $transaction['type']; ?>">
                                    <?php echo ucfirst($transaction['type']); ?>
                                </span>
                            </td>
                            <td class="<?php echo $transaction['type']; ?>">
                                <?php echo ($transaction['type'] === 'income' ? '+' : '-') . number_format($transaction['amount'], 2); ?>
                            </td>
                            <td>
                                <a href="edit-transaction.php?id=<?php echo $transaction['id']; ?>" class="btn-edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="delete-transaction.php?id=<?php echo $transaction['id']; ?>" class="btn-delete" onclick="return confirm('Are you sure?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</main>

<?php include 'includes/footer.php'; ?>