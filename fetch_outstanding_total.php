<?php
// Database connection settings
include 'db_connection.php';

// Get the selected date from the request
$date = $_GET['date'];

if (!$date) {
    die(json_encode(['error' => 'No date provided']));
}

// Prepare and execute SQL query with correct salesDate column
$query = $conn->prepare('SELECT SUM(amountOD) as outstandingTotal FROM sales WHERE salesDate = ?');
$query->bind_param('s', $date);
$query->execute();
$result = $query->get_result();

// Fetch the result
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $outstandingTotal = (float)$row['outstandingTotal'];  // Ensure it is a float
    echo json_encode(['outstandingTotal' => $outstandingTotal]);
} else {
    echo json_encode(['outstandingTotal' => 0]);
}

// Close the connection
$conn->close();
?>
