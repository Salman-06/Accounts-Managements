<?php
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');  // Return JSON data

// Include database connection
include 'db_connection.php';


if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Get customer ID from the query string
    $customerId = isset($_GET['id']) ? $_GET['id'] : null;

    if ($customerId) {
        // Prepare and execute SQL query to fetch all fields for the customer
        $sql = "SELECT * FROM customers WHERE customer_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $customerId);  // Bind as string because ID is alphanumeric

        if ($stmt->execute()) {
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $customer = $result->fetch_assoc();
                echo json_encode(['success' => true, 'customer' => $customer]);
            } else {
                echo json_encode(['success' => false, 'error' => 'Customer not found']);
            }
        } else {
            echo json_encode(['success' => false, 'error' => 'Failed to execute query']);
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
