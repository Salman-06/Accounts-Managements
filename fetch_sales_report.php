<?php
// fetch_sales_report.php

// Database credentials
include 'db_connection.php';

$fromDate = $_GET['fromDate'];
$toDate = $_GET['toDate'];

// SQL query to fetch sales data between selected dates
$sql = "SELECT * FROM sales WHERE salesDate >= '$fromDate' AND salesDate <= '$toDate'";
$result = $conn->query($sql);

// Fetch data and encode to JSON
$salesData = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $salesData[] = $row;
    }
}

echo json_encode($salesData);

$conn->close();
?>
