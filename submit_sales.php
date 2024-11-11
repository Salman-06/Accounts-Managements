<?php
// Enable error reporting for debugging purposes (can be removed in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Set header to return JSON response
header('Content-Type: application/json');

// Database connection details
include 'db_connection.php';


// Check if the form is submitted via POST method
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $salesDate = $_POST['salesDate'];
    $customerName = $_POST['customerName'];
    $remarks = $_POST['remarks'];
    $load12kg = $_POST['load12kg'];
    $empty12kg = $_POST['empty12kg'];
    $load17kg = $_POST['load17kg'];
    $empty17kg = $_POST['empty17kg'];
    $srg = $_POST['srg'];
    $adap = $_POST['adap'];
    $stove = $_POST['stove'];
    $lighter = $_POST['lighter'];
    $hose = $_POST['hose'];
    $totalAmount = $_POST['totalAmount'];
    $deposit = $_POST['deposit'];
    $amountPaid = $_POST['amountPaid'];
    $amountOD = $_POST['amountOD'];

    // Prepare SQL statement for inserting the new sales record
    $sql = "INSERT INTO sales (salesDate, customerName, remarks, load12kg, empty12kg, load17kg, empty17kg, srg, adap, stove, lighter, hose, totalAmount, deposit, amountPaid, amountOD)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    // Create a prepared statement
    $stmt = $conn->prepare($sql);

    // Bind parameters to the prepared statement
    $stmt->bind_param("sssiiiiiiiiiiddd", $salesDate, $customerName, $remarks, $load12kg, $empty12kg, $load17kg, $empty17kg, $srg, $adap, $stove, $lighter, $hose, $totalAmount, $deposit, $amountPaid, $amountOD);

    // Execute the prepared statement and return JSON response based on the result
    if ($stmt->execute()) {
        // Return success response in JSON format
        echo json_encode(['success' => true, 'message' => 'Sales entry added successfully']);
    } else {
        // Return error response in JSON format
        echo json_encode(['success' => false, 'message' => 'Error adding sales entry: ' . $stmt->error]);
    }

    // Close the statement
    $stmt->close();
}

// Close the database connection
$conn->close();
?>
