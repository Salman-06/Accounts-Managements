<?php

include 'db_connection.php';


header('Content-Type: application/json');

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $query = "DELETE FROM sales WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        http_response_code(200); // Success
        echo json_encode(['success' => true, 'message' => 'Record deleted successfully']);
    } else {
        http_response_code(500); // Server error
        echo json_encode(['success' => false, 'message' => 'Failed to delete record']);
    }

    $stmt->close();
} else {
    http_response_code(400); // Bad request
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}

$conn->close();
?>