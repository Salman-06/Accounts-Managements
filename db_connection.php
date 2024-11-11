<?php
$servername = "localhost"; // Or your database server
$username = "root";
$password = "";
$dbname = "covai";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
