<?php
// Prevent direct access
if (basename($_SERVER['PHP_SELF']) === basename(__FILE__)) {
    header("Location: /public/home.php");
    exit();
}

include '../config/db.php';

// Top selling products
function getTopProducts($limit = 10) {
    global $_db;

    $stm = $_db->prepare("SELECT p.id, p.name, SUM(oi.quantity) as total_sold
                          FROM order_items oi
                          JOIN products p ON oi.product_id = p.id
                          GROUP BY p.id, p.name
                          ORDER BY total_sold DESC
                          LIMIT ?");
    $stm->bindValue(1, (int)$limit, PDO::PARAM_INT);
    $stm->execute();
    return $stm->fetchAll(PDO::FETCH_OBJ);
}

// Orders count by month/year
function getOrderCountByMonth($startDate, $endDate) {
    global $_db;

    $stm = $_db->prepare("SELECT DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as total_orders
                          FROM orders
                          WHERE status = 'completed'
                          AND created_at BETWEEN ? AND ?
                          GROUP BY month
                          ORDER BY month ASC");
    $stm->execute([$startDate, $endDate]);
    return $stm->fetchAll(PDO::FETCH_OBJ);
}

// Total sales amount by month/year
function getSalesAmountByMonth($startDate, $endDate) {
    global $_db;

    $stm = $_db->prepare("SELECT DATE_FORMAT(created_at, '%Y-%m') as month, SUM(total_price) as total_sales
                          FROM orders
                          WHERE status = 'Completed'
                          AND created_at BETWEEN ? AND ?
                          GROUP BY month
                          ORDER BY month ASC");
    $stm->execute([$startDate, $endDate]);
    return $stm->fetchAll(PDO::FETCH_OBJ);
}

function getMonthlyComparison() {
    global $_db;

    $currentMonth = date('Y-m');
    $previousMonth = date('Y-m', strtotime('-1 month'));

    $stm = $_db->prepare("SELECT 
                             SUM(CASE WHEN DATE_FORMAT(created_at, '%Y-%m') = ? THEN total_price ELSE 0 END) as current_month_sales,
                             SUM(CASE WHEN DATE_FORMAT(created_at, '%Y-%m') = ? THEN total_price ELSE 0 END) as previous_month_sales
                          FROM orders
                          WHERE status = 'completed'");
    $stm->execute([$currentMonth, $previousMonth]);
    return $stm->fetch(PDO::FETCH_OBJ);
}
?>
