<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Connect to the database
include 'db_connection.php';

// Fetch customer data
$sql = "SELECT customer_id, customer_name FROM customers";
$result = $conn->query($sql);

if (!$result) {
    echo json_encode(['success' => false, 'message' => 'SQL query failed: ' . $conn->error]);
    exit;
}

$customers = array();

if ($result->num_rows > 0) {
    // Output data of each row
    while($row = $result->fetch_assoc()) {
        $customers[] = array(
            "customer_id" => $row["customer_id"],
            "customer_name" => $row["customer_name"]
        );
    }
} else {
    echo json_encode(['success' => false, 'message' => 'No customers found']);
    exit;
}

// Return the customer data as JSON
echo json_encode(array("success" => true, "customers" => $customers));

$conn->close();
?>
