<?php
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database connection
include 'db_connection.php';


// Get the 'fromDate', 'toDate', and 'page' from the query string
$fromDate = $_GET['fromDate'];
$toDate = $_GET['toDate'];
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

// Fetch Outstanding data
$sql = "
    SELECT date, outstanding_name, amount 
    FROM outstanding 
    WHERE date BETWEEN '$fromDate' AND '$toDate'
    LIMIT $limit OFFSET $offset";

$result = $conn->query($sql);

// Get the total count of records for pagination
$totalSql = "SELECT COUNT(*) AS total FROM outstanding WHERE date BETWEEN '$fromDate' AND '$toDate'";
$totalResult = $conn->query($totalSql);
$totalRows = $totalResult->fetch_assoc()['total'];
$totalPages = ceil($totalRows / $limit);

if ($result->num_rows > 0) {
    $report = [];
    while ($row = $result->fetch_assoc()) {
        $report[] = [
            'date' => $row['date'],
            'outstanding_name' => $row['outstanding_name'],
            'amount' => $row['amount'],
        ];
    }
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
