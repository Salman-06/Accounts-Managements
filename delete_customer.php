<?php

// Enable error reporting for debugging (optional, keep if needed)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');  // Ensure the output is JSON

include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Access the customer ID via the URL query string (GET)
    $customerId = isset($_GET['id']) ? $_GET['id'] : null;  // No intval()

    if ($customerId) {
        // SQL query to delete the customer
        $sql = "DELETE FROM customers WHERE customer_id = ?";
        $stmt = $conn->prepare($sql);
        
        if (!$stmt) {
            echo json_encode(['success' => false, 'error' => 'Failed to prepare SQL statement']);
            exit;
        }

        // Bind the customer ID as a string
        $stmt->bind_param('s', $customerId);

        if ($stmt->execute()) {
            echo json_encode(['success' => true]);  // Return only JSON response
        } else {
            echo json_encode(['success' => false, 'error' => 'Failed to execute SQL query']);
        }

        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'error' => 'Invalid customer ID']);
    }

    $conn->close();
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
}
?>