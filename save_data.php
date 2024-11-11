<?php
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Connect to the database
include 'db_connection.php';


// Get the incoming JSON data
$data = json_decode(file_get_contents('php://input'), true);

$selectedDate = $data['selectedDate'];  // Date selected by the user

// Insert OD Collected data into the 'od_collected' table
foreach ($data['odCollectedData'] as $collected) {
    $sql = "INSERT INTO od_collected (date, collected, cash, gpay_bank) VALUES (
        '" . $selectedDate . "', 
        '" . $conn->real_escape_string($collected['collected']) . "', 
        " . floatval($collected['cash']) . ", 
        " . floatval($collected['gpayBank']) . "
    )";
    
    if (!$conn->query($sql)) {
        die(json_encode(['success' => false, 'error' => 'OD Collected SQL error: ' . $conn->error]));
    }
}

// Insert Outstanding data into the 'outstanding' table
foreach ($data['outstandingData'] as $outstanding) {
    $sql = "INSERT INTO outstanding (date, outstanding_name, amount) VALUES (
        '" . $selectedDate . "', 
        '" . $conn->real_escape_string($outstanding['name']) . "', 
        " . floatval($outstanding['amount']) . "
    )";
    
    if (!$conn->query($sql)) {
        die(json_encode(['success' => false, 'error' => 'Outstanding SQL error: ' . $conn->error]));
    }
}

// Insert Totals data into the 'totals' table
$totalData = $data['totalData'];
$sql = "INSERT INTO totals (date, total_for_selected_date, od_collected_total, outstanding_total, final_total) VALUES (
    '" . $selectedDate . "', 
    " . floatval($totalData['totalForSelectedDate']) . ", 
    " . floatval($totalData['odCollectedTotal']) . ", 
    " . floatval($totalData['outstandingTotal']) . ", 
    " . floatval($totalData['finalTotal']) . "
)";
if (!$conn->query($sql)) {
    die(json_encode(['success' => false, 'error' => 'Totals SQL error: ' . $conn->error]));
}

// Close the connection
$conn->close();

// Redirect back to the newout.html page
header("Location: newout.html");
exit(); // Ensure no further processing happens
?>