<?php
// Enable error reporting for troubleshooting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Check if request method is POST and required data is provided
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_id'])) {
    // Retrieve values from the POST request
    $id = $_POST['update_id'];
    $date = $_POST['date'];
    $odCollected = $_POST['odCollected'];
    $cash = $_POST['cash'];
    $gpayBank = $_POST['gpayBank'];

    // Database connection (replace these values with your actual database credentials)
    include 'db_connection.php';

$conn = new mysqli($servername, $username, $password, $dbname);

    // Check if the connection was successful
    if ($conn->connect_error) {
        echo json_encode(['status' => 'error', 'message' => 'Database connection failed: ' . $conn->connect_error]);
        exit;
    }

    // Prepare the update SQL query
    $updateQuery = "UPDATE od_table SET date = ?, odCollected = ?, cash = ?, gpayBank = ? WHERE id = ?";
    $stmt = $conn->prepare($updateQuery);

    // Check if the statement preparation was successful
    if ($stmt === false) {
        echo json_encode(['status' => 'error', 'message' => 'Failed to prepare statement: ' . $conn->error]);
        exit;
    }

    // Bind the parameters to the SQL query
    $stmt->bind_param("ssddi", $date, $odCollected, $cash, $gpayBank, $id);

    // Execute the query and check if the execution was successful
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Execute failed: ' . $stmt->error]);
    }

    // Close the statement and the connection
    $stmt->close();
    $conn->close();
    exit;
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}
