<?php
include 'db_connection.php'; // Include your database connection file

header('Content-Type: application/json'); // Ensure the output is JSON

// Run the query to fetch customers
$sql = "SELECT name FROM customers"; // Adjust the SQL as per your table structure
$result = $conn->query($sql);

$customers = [];

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $customers[] = $row; // Add each row to the customers array
    }
} else {
    echo json_encode(["error" => "Database query failed: " . $conn->error]);
    exit();
}

// Return the customers in JSON format
echo json_encode($customers);
?>
