<?php
require_once 'includes/config.php';
requireLogin();

// Get user's financial summary
$userId = getUserId();
$currentMonth = date('Y-m');

// Get income and expenses for current month
$transactions = fetchAll("
    SELECT type, SUM(amount) as total 
    FROM transactions 
    WHERE user_id = ? AND DATE_FORMAT(date, '%Y-%m') = ?
    GROUP BY type
", [$userId, $currentMonth]);

$income = 0;
$expenses = 0;

foreach ($transactions as $transaction) {
    if ($transaction['type'] === 'income') {
        $income = $transaction['total'];
    } else {
        $expenses = $transaction['total'];
    }
}

$balance = $income - $expenses;

// Get recent transactions
$recentTransactions = fetchAll("
    SELECT * FROM transactions 
    WHERE user_id = ? 
    ORDER BY date DESC 
    LIMIT 5
", [$userId]);

// Get expense breakdown by category
$expenseCategories = fetchAll("
    SELECT category, SUM(amount) as total 
    FROM transactions 
    WHERE user_id = ? AND type = 'expense' AND DATE_FORMAT(date, '%Y-%m') = ?
    GROUP BY category
    ORDER BY total DESC
    LIMIT 5
", [$userId, $currentMonth]);

// Prepare data for charts
$categories = [];
$amounts = [];

foreach ($expenseCategories as $category) {
    $categories[] = $category['category'];
    $amounts[] = $category['total'];
}
?>

<?php include 'includes/header.php'; ?>

<main class="dashboard">
    <div class="summary-cards">
        <div class="card income-card">
            <h3>Income</h3>
            <p class="amount">$<?php echo number_format($income, 2); ?></p>
            <i class="fas fa-arrow-up"></i>
        </div>
        
        <div class="card expense-card">
            <h3>Expenses</h3>
            <p class="amount">$<?php echo number_format($expenses, 2); ?></p>
            <i class="fas fa-arrow-down"></i>
        </div>
        
        <div class="card balance-card">
            <h3>Balance</h3>
            <p class="amount">$<?php echo number_format($balance, 2); ?></p>
            <i class="fas fa-wallet"></i>
        </div>
    </div>
    
    <div class="charts-container">
        <div class="chart-card">
            <h3>Expense Breakdown</h3>
            <canvas id="expenseChart"></canvas>
        </div>
        
        <div class="chart-card">
            <h3>Monthly Trend</h3>
            <canvas id="monthlyTrendChart"></canvas>
        </div>
    </div>
    
    <div class="recent-transactions">
        <h3>Recent Transactions</h3>
        
        <?php if (empty($recentTransactions)): ?>
            <p>No transactions found. <a href="add-transaction.php">Add your first transaction</a></p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Description</th>
                        <th>Category</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recentTransactions as $transaction): ?>
                        <tr>
                            <td><?php echo date('M j', strtotime($transaction['date'])); ?></td>
                            <td><?php echo htmlspecialchars($transaction['description']); ?></td>
                            <td><?php echo htmlspecialchars($transaction['category']); ?></td>
                            <td class="<?php echo $transaction['type']; ?>">
                                <?php echo ($transaction['type'] === 'income' ? '+' : '-') . number_format($transaction['amount'], 2); ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
    
    <div class="invest-tips">
        <h3><i class="fas fa-lightbulb"></i> Investment Tips</h3>
        <div class="tips-container" id="investTips">
            <p>Ask our AI assistant for personalized investment recommendations.</p>
        </div>
    </div>
</main>

<script>
    // Expense breakdown chart
    const expenseCtx = document.getElementById('expenseChart').getContext('2d');
    const expenseChart = new Chart(expenseCtx, {
        type: 'pie',
        data: {
            labels: <?php echo json_encode($categories); ?>,
            datasets: [{
                data: <?php echo json_encode($amounts); ?>,
                backgroundColor: [
                    '#18BC9C', '#2C3E50', '#3498DB', '#F39C12', '#E74C3C',
                    '#9B59B6', '#1ABC9C', '#D35400', '#34495E', '#7F8C8D'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'right'
                }
            }
        }
    });
    
    // Monthly trend chart (simplified example)
    const trendCtx = document.getElementById('monthlyTrendChart').getContext('2d');
    const trendChart = new Chart(trendCtx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            datasets: [
                {
                    label: 'Income',
                    data: [1200, 1900, 1500, 2000, 1800, 2200],
                    borderColor: '#18BC9C',
                    backgroundColor: 'rgba(24, 188, 156, 0.1)',
                    fill: true
                },
                {
                    label: 'Expenses',
                    data: [800, 1200, 1000, 1500, 1300, 1600],
                    borderColor: '#E74C3C',
                    backgroundColor: 'rgba(231, 76, 60, 0.1)',
                    fill: true
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top'
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
    
    // Load investment tips
    document.addEventListener('DOMContentLoaded', function() {
        fetchInvestTips();
    });
    
    function fetchInvestTips() {
        fetch('api/gemini.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                message: "Provide 3 short investment tips for a beginner investor. Keep each tip under 140 characters. Format as a bulleted list."
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.response) {
                document.getElementById('investTips').innerHTML = data.response;
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }
</script>

<?php include 'includes/footer.php'; ?>