<?php
require_once 'includes/config.php';
requireLogin();

$userId = getUserId();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $amount = floatval($_POST['amount']);
    $type = $_POST['type'];
    $category = $_POST['category'];
    $description = trim($_POST['description']);
    $date = $_POST['date'];
    
    // Validate inputs
    $errors = [];
    
    if ($amount <= 0) {
        $errors[] = "Amount must be greater than 0";
    }
    
    if (!in_array($type, ['income', 'expense'])) {
        $errors[] = "Invalid transaction type";
    }
    
    if (empty($category)) {
        $errors[] = "Category is required";
    }
    
    if (empty($date)) {
        $errors[] = "Date is required";
    }
    
    if (empty($errors)) {
        // Insert transaction
        $result = executeQuery("
            INSERT INTO transactions (user_id, amount, type, category, description, date)
            VALUES (?, ?, ?, ?, ?, ?)
        ", [$userId, $amount, $type, $category, $description, $date]);
        
        if ($result) {
            $_SESSION['success'] = "Transaction added successfully";
            header('Location: transactions.php');
            exit();
        } else {
            $errors[] = "Failed to add transaction";
        }
    }
}

// Get categories for dropdown
$incomeCategories = fetchAll("SELECT name FROM categories WHERE type = 'income'");
$expenseCategories = fetchAll("SELECT name FROM categories WHERE type = 'expense'");
?>

<?php include 'includes/header.php'; ?>

<main class="add-transaction">
    <h2>Add New Transaction</h2>
    
    <?php if (!empty($errors)): ?>
        <div class="alert error">
            <?php foreach ($errors as $error): ?>
                <p><?php echo $error; ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    
    <form method="POST" class="transaction-form">
        <div class="form-group">
            <label for="type">Transaction Type</label>
            <select id="type" name="type" required>
                <option value="">Select Type</option>
                <option value="income" <?php echo ($_POST['type'] ?? '') === 'income' ? 'selected' : ''; ?>>Income</option>
                <option value="expense" <?php echo ($_POST['type'] ?? '') === 'expense' ? 'selected' : ''; ?>>Expense</option>
            </select>
        </div>
        
        <div class="form-group">
            <label for="amount">Amount</label>
            <input type="number" id="amount" name="amount" step="0.01" min="0.01" required 
                   value="<?php echo htmlspecialchars($_POST['amount'] ?? ''); ?>">
        </div>
        
        <div class="form-group">
            <label for="category">Category</label>
            <select id="category" name="category" required>
                <option value="">Select Category</option>
                <?php if (($_POST['type'] ?? '') === 'income'): ?>
                    <?php foreach ($incomeCategories as $cat): ?>
                        <option value="<?php echo htmlspecialchars($cat['name']); ?>" 
                            <?php echo ($_POST['category'] ?? '') === $cat['name'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($cat['name']); ?>
                        </option>
                    <?php endforeach; ?>
                <?php elseif (($_POST['type'] ?? '') === 'expense'): ?>
                    <?php foreach ($expenseCategories as $cat): ?>
                        <option value="<?php echo htmlspecialchars($cat['name']); ?>" 
                            <?php echo ($_POST['category'] ?? '') === $cat['name'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($cat['name']); ?>
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>
        
        <div class="form-group">
            <label for="description">Description</label>
            <input type="text" id="description" name="description" 
                   value="<?php echo htmlspecialchars($_POST['description'] ?? ''); ?>">
        </div>
        
        <div class="form-group">
            <label for="date">Date</label>
            <input type="date" id="date" name="date" required 
                   value="<?php echo htmlspecialchars($_POST['date'] ?? date('Y-m-d')); ?>">
        </div>
        
        <button type="submit" class="btn-primary">Add Transaction</button>
        <a href="transactions.php" class="btn-cancel">Cancel</a>
    </form>
</main>

<script>
    // Update categories based on selected type
    document.getElementById('type').addEventListener('change', function() {
        const type = this.value;
        const categorySelect = document.getElementById('category');
        categorySelect.innerHTML = '<option value="">Select Category</option>';
        
        if (type === 'income') {
            <?php foreach ($incomeCategories as $cat): ?>
                categorySelect.innerHTML += `
                    <option value="<?php echo htmlspecialchars($cat['name']); ?>">
                        <?php echo htmlspecialchars($cat['name']); ?>
                    </option>
                `;
            <?php endforeach; ?>
        } else if (type === 'expense') {
            <?php foreach ($expenseCategories as $cat): ?>
                categorySelect.innerHTML += `
                    <option value="<?php echo htmlspecialchars($cat['name']); ?>">
                        <?php echo htmlspecialchars($cat['name']); ?>
                    </option>
                `;
            <?php endforeach; ?>
        }
    });
</script>

<?php include 'includes/footer.php'; ?>