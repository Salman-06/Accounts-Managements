<?php
// Include database connection
include 'db_connection.php';

// Start debugging - Check if ID is being passed
if (isset($_GET['id'])) {
    $purchaseId = $_GET['id'];

    // Get the form data from POST request
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

    // Debugging - Log values to see if they're coming through correctly
    error_log("Updating purchase ID: $purchaseId with data: purchaseDate = $purchaseDate, customerName = $customerName");

    // Prepare the SQL query to update the purchase data
    $sql = "UPDATE purchases SET 
        purchaseDate = ?, 
        customerName = ?, 
        load12kg = ?, 
        given12kg = ?, 
        load17kg = ?, 
        given17kg = ?, 
        srg = ?, 
        adap = ?, 
        stove = ?, 
        lighter = ?, 
        hose = ?, 
        amount12kg = ?, 
        amount17kg = ?, 
        totalPurchaseValue = ? 
        WHERE id = ?";

    // Debugging - Check if the SQL statement prepares correctly
    if (!$stmt = $conn->prepare($sql)) {
        echo json_encode(['success' => false, 'message' => 'SQL error: ' . $conn->error]);
        exit;
    }

    // Bind parameters
    $stmt->bind_param("ssiiiiiiiiiiiii", $purchaseDate, $customerName, $load12kg, $given12kg, $load17kg, $given17kg, $srg, $adap, $stove, $lighter, $hose, $amount12kg, $amount17kg, $totalPurchaseValue, $purchaseId);

    // Execute and check if the update was successful
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        // If the query fails, log the error
        error_log("SQL Error: " . $stmt->error);
        echo json_encode(['success' => false, 'message' => 'Failed to update purchase: ' . $stmt->error]);
    }

    $stmt->close();
} else {
    // If ID is not provided, return an error response
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}

$conn->close();
?>
