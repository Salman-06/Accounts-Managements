<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
include 'db_connection.php';

// Get the date range from the GET parameters
$fromDate = $_GET['fromDate'] ?? '';
$toDate = $_GET['toDate'] ?? '';

// Validate the date range
if (empty($fromDate) || empty($toDate)) {
    echo json_encode(['success' => false, 'error' => 'Invalid date range']);
    exit();
}

// Fetch purchase entries within the date range
$sql = "SELECT purchaseDate, customerName, load12kg, given12kg, load17kg, given17kg, srg, adap, stove, lighter, hose, amount12kg, amount17kg, (amount12kg + amount17kg) AS totalPurchaseValue FROM purchases WHERE purchaseDate BETWEEN ? AND ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $fromDate, $toDate);

if ($stmt->execute()) {
    $result = $stmt->get_result();
    $entries = [];

    while ($row = $result->fetch_assoc()) {
        $entries[] = $row;
    }

    echo json_encode($entries);
} else {
    echo json_encode(['success' => false, 'error' => 'Query execution failed: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
