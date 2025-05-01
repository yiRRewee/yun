<?php 
include '../public/header.php';
include '../models/reportModels.php';
require "../config/adminAuth.php";

$page = $_GET['page'] ?? 'orders';
$filter = $_GET['filter'] ?? '1month'; 

$today = date('Y-m-d');
switch ($filter) {
    case '1month':
        $startDate = date('Y-m-d', strtotime('-1 month'));
        break;
    case '3months':
        $startDate = date('Y-m-d', strtotime('-3 months'));
        break;
    case '6months':
        $startDate = date('Y-m-d', strtotime('-6 months'));
        break;
    case '1year':
        $startDate = date('Y-m-d', strtotime('-1 year'));
        break;
    case 'all':
    default:
        $startDate = '2025-01-01'; 
        break;
}
$endDate = $today;

$topProducts = getTopProducts(5);
$orderCounts = getOrderCountByMonth($startDate, $endDate);
$monthlyComparison = getMonthlyComparison();
?>

<link rel="stylesheet" href="../assets/css/report.css">

<div class="report-header">
    <h2>📊 Admin Report</h2>
    <div class="report-navigation">
        <a href="?page=orders" class="<?= ($page == 'orders') ? 'active' : '' ?>">Orders Done</a> |
        <a href="?page=products" class="<?= ($page == 'products') ? 'active' : '' ?>">Top Selling Products</a> |
        <a href="?page=sales" class="<?= ($page == 'sales') ? 'active' : '' ?>">Sales Comparison</a>
    </div>
</div>

<div class="report-container">

    <?php if ($page == 'orders'): ?>
        <!-- Filter Selection -->
        <form method="get" class="order-filter-form">
            <input type="hidden" name="page" value="orders">
            <label for="filter">Filter by Time:</label>
            <select name="filter" id="filter" onchange="this.form.submit()">
                <option value="1month" <?= ($filter == '1month') ? 'selected' : '' ?>>Last 1 Month</option>
                <option value="3months" <?= ($filter == '3months') ? 'selected' : '' ?>>Last 3 Months</option>
                <option value="6months" <?= ($filter == '6months') ? 'selected' : '' ?>>Last 6 Months</option>
                <option value="1year" <?= ($filter == '1year') ? 'selected' : '' ?>>Last 1 Year</option>
                <option value="all" <?= ($filter == 'all') ? 'selected' : '' ?>>All Time</option>
            </select>
        </form>

        <!-- Orders Table -->
        <table class="report-table">
            <thead>
                <tr>
                    <th>Month</th>
                    <th>Total Orders</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orderCounts as $order): ?>
                <tr>
                    <td><?= htmlspecialchars($order->month) ?></td>
                    <td><?= htmlspecialchars($order->total_orders) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    <?php elseif ($page == 'products'): ?>

        <table class="report-table">
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Total Sold</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($topProducts as $product): ?>
                <tr>
                    <td><?= htmlspecialchars($product->name) ?></td>
                    <td><?= htmlspecialchars($product->total_sold) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    <?php elseif ($page == 'sales'): ?>

        <div class="sales-comparison">
            <p><strong>This Month Sales:</strong> RM <?= number_format($monthlyComparison->current_month_sales, 2) ?></p>
            <p><strong>Last Month Sales:</strong> RM <?= number_format($monthlyComparison->previous_month_sales, 2) ?></p>

            <canvas id="salesChart" width="400" height="200"></canvas>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script>
$(document).ready(function() {
    const ctx = $('#salesChart')[0].getContext('2d');
    const salesChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Last Month', 'This Month'],
            datasets: [{
                label: 'Sales RM',
                data: [<?= $monthlyComparison->previous_month_sales ?>, <?= $monthlyComparison->current_month_sales ?>],
                backgroundColor: ['#ff6384', '#36a2eb'],
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
});
</script>


    <?php endif; ?>

</div>

<?php include "../public/footer.php"; ?>
