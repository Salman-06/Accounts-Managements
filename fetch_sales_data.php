<?php
// Enable error reporting for debugging purposes
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database connection settings
include 'db_connection.php';

if (isset($_GET['date'])) {
    $selected_date = $_GET['date'];

// Prepare the SQL query to fetch sales data for the selected date
$query = "SELECT
            SUM(empty12kg) AS empty12kg,
            SUM(load12kg) AS load12kg,
            SUM(empty17kg) AS empty17kg,
            SUM(load17kg) AS load17kg,
            SUM(hose) AS hose,
            SUM(srg) AS srg,
            SUM(adap) AS adap,
            SUM(lighter) AS lighter,
            SUM(stove) AS stove
        FROM sales
        WHERE salesDate = ?"; // Change to DATE(salesDate) to match date

if ($stmt = $conn->prepare($query)) {
    $stmt->bind_param('s', $selected_date);
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        if ($result) {
            $data = $result->fetch_assoc();
            // Ensure all fields are present, even if null
            $fields = ['empty12kg', 'load12kg', 'empty17kg', 'load17kg', 'srg', 'lighter', 'stove', 'adap', 'hose'];
            foreach ($fields as $field) {
                $data[$field] = $data[$field] ?? 0;
            }
            echo json_encode([$data]); // Wrap in array to ensure consistent format
        } else {
            echo json_encode([[]]);  // Return empty array if no data found
        }
    } else {
        echo json_encode(['error' => 'Query execution failed: ' . $stmt->error]);
    }
} else {
    echo json_encode(['error' => 'Failed to prepare the SQL statement: ' . $conn->error]);
}
} else {
echo json_encode(['error' => 'Date not provided']);
}

$conn->close();
?>
