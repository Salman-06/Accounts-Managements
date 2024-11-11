<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'db_connection.php';

$sql = "SELECT * FROM purchases";
$result = mysqli_query($conn, $sql);

$purchases = [];

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $purchases[] = $row;  // Add each purchase to the array
    }
}

echo json_encode($purchases);  // Return all purchases as JSON

?>