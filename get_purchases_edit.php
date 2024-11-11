<?php
// Include database connection
include 'db_connection.php';

// Get the purchase ID from the URL
if (isset($_GET['id'])) {
    $purchaseId = $_GET['id'];

    // Prepare the SQL query to fetch the purchase data by ID
    $sql = "SELECT * FROM purchases WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $purchaseId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Fetch the data as an associative array
        $purchaseData = $result->fetch_assoc();
        
        // Return the data as JSON
        echo json_encode($purchaseData);
    } else {
        // If no purchase found, return an error response
        echo json_encode(['success' => false, 'message' => 'Purchase not found']);
    }
    
    $stmt->close();
} else {
    // If ID is not provided, return an error response
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}

$conn->close();
?>
