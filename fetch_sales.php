<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database connection
include 'db_connection.php';

header('Content-Type: application/json');

// Run a test query
$sql = "SELECT id, salesDate, customerName, remarks, load12kg, empty12kg, load17kg, empty17kg, srg, adap, stove, lighter, hose, totalAmount, deposit, amountPaid, amountOD FROM sales";
$result = $conn->query($sql);

if (!$result) {
    echo json_encode(["error" => "SQL error: " . $conn->error]);
    exit();
}

$salesData = [];
while ($row = $result->fetch_assoc()) {
    $salesData[] = $row;
}

echo json_encode($salesData);
?>
