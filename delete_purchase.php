<?php
// Assuming you're using a MySQL database connection in $conn
include 'db_connection.php';

// Get the ID of the entry to be deleted
$id = $_GET['id'];

// Delete the entry
$sql = "DELETE FROM purchases WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

$response = [];
if ($stmt->execute()) {
    $response['success'] = true;
} else {
    $response['success'] = false;
}

$stmt->close();
$conn->close();

echo json_encode($response);
?>