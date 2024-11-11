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
    $purchaseDate = $_POST['purchaseDate'];
    $customerName = $_POST['customerName'];
    $load12kg = $_POST['load12kg'];
    $given12kg = $_POST['given12kg'];
    $load17kg = $_POST['load17kg'];
    $given17kg = $_POST['given17kg'];
    $srg = $_POST['srg'];
    $adap = $_POST['adap'];
    $stove = $_POST['stove'];
    $lighter = $_POST['lighter'];
    $hose = $_POST['hose'];
    $amount12kg = $_POST['amount12kg'];
    $amount17kg = $_POST['amount17kg'];
    $totalPurchaseValue = $_POST['totalPurchaseValue'];

    // Prepare SQL statement for inserting the new purchase record
    $sql = "INSERT INTO purchases (purchaseDate, customerName, load12kg, given12kg, load17kg, given17kg, srg, adap, stove, lighter, hose, amount12kg, amount17kg, totalPurchaseValue)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    // Create a prepared statement
    $stmt = $conn->prepare($sql);

    // Bind parameters to the prepared statement
    $stmt->bind_param("ssiiiiiiiiiddd", $purchaseDate, $customerName, $load12kg, $given12kg, $load17kg, $given17kg, $srg, $adap, $stove, $lighter, $hose, $amount12kg, $amount17kg, $totalPurchaseValue);

    // Execute the prepared statement and return JSON response based on the result
    if ($stmt->execute()) {
        // Return success response in JSON format
        echo json_encode(['success' => true, 'message' => 'Purchase entry added successfully']);
    } else {
        // Return error response in JSON format
        echo json_encode(['success' => false, 'message' => 'Error adding purchase entry: ' . $stmt->error]);
    }

    // Close the statement
    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}

// Close the database connection
$conn->close();
?>
