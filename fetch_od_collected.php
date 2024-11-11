<?php
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'db_connection.php';

// Get the 'fromDate', 'toDate', and 'page' from the query string
$fromDate = $_GET['fromDate'];
$toDate = $_GET['toDate'];
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

// Fetch OD collected data
$sqlOD = "
    SELECT date, collected, cash, gpay_bank 
    FROM od_collected 
    WHERE date BETWEEN '$fromDate' AND '$toDate'
    LIMIT $limit OFFSET $offset";

$resultOD = $conn->query($sqlOD);

// Prepare the report data
$report = [];
while ($rowOD = $resultOD->fetch_assoc()) {
    $report[] = [
        'date' => $rowOD['date'],
        'collected' => $rowOD['collected'],
        'cash' => $rowOD['cash'],
        'gpay_bank' => $rowOD['gpay_bank'],
    ];
}

// Get the total count of records for pagination
$totalSql = "SELECT COUNT(*) AS total FROM od_collected WHERE date BETWEEN '$fromDate' AND '$toDate'";
$totalResult = $conn->query($totalSql);
$totalRows = $totalResult->fetch_assoc()['total'];
$totalPages = ceil($totalRows / $limit);

if (!empty($report)) {
    echo json_encode([
        'success' => true,
        'report' => $report,
        'totalPages' => $totalPages
    ]);
} else {
    echo json_encode(['success' => false, 'error' => 'No data found']);
}

$conn->close();
?>
