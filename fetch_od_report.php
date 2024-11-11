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

// Fetch OD collected data
$sqlOD = "
    SELECT date, collected, cash, gpay_bank 
    FROM od_collected 
    WHERE date BETWEEN '$fromDate' AND '$toDate'
    LIMIT $limit OFFSET $offset";

$resultOD = $conn->query($sqlOD);

// Fetch Outstanding data separately
$sqlOutstanding = "
    SELECT date, outstanding_name, amount 
    FROM outstanding 
    WHERE date BETWEEN '$fromDate' AND '$toDate'";

$resultOutstanding = $conn->query($sqlOutstanding);

// Prepare outstanding data by date
$outstandingData = [];
while ($rowOutstanding = $resultOutstanding->fetch_assoc()) {
    $date = $rowOutstanding['date'];
    if (!isset($outstandingData[$date])) {
        $outstandingData[$date] = [
            'names' => [],
            'total' => 0
        ];
    }
    // Collect names and sum amounts for outstanding entries
    $outstandingData[$date]['names'][] = $rowOutstanding['outstanding_name'];
    $outstandingData[$date]['total'] += $rowOutstanding['amount'];
}

// Combine the data into a single structure
$report = [];
while ($rowOD = $resultOD->fetch_assoc()) {
    $date = $rowOD['date'];
    $reportEntry = [
        'date' => $date,
        'collected' => $rowOD['collected'],
        'cash' => $rowOD['cash'],
        'gpay_bank' => $rowOD['gpay_bank'],
        'outstanding_name' => '',
        'outstanding_amount' => 0.00,
    ];

    // If there are outstanding entries for this date, combine them
    if (isset($outstandingData[$date])) {
        $reportEntry['outstanding_name'] = implode(', ', $outstandingData[$date]['names']); // Concatenate names
        $reportEntry['outstanding_amount'] = $outstandingData[$date]['total']; // Total outstanding amount
    }
    
    $report[] = $reportEntry;
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
