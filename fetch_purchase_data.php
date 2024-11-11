<?php
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 0);

include 'db_connection.php';

if (isset($_GET['date'])) {
    $selected_date = $_GET['date'];

    $query = "SELECT SUM(load12kg) as load12kg, 
                     SUM(given12kg) as given12kg, 
                     SUM(load17kg) as load17kg, 
                     SUM(given17kg) as given17kg, 
                     SUM(srg) as srg, 
                     SUM(lighter) as lighter, 
                     SUM(stove) as stove, 
                     SUM(adap) as adap, 
                     SUM(hose) as hose
              FROM purchases 
              WHERE purchaseDate = ?";

    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param('s', $selected_date);
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            if ($result) {
                $data = $result->fetch_assoc();
                // Ensure all fields are present, even if null
                $fields = ['load12kg', 'given12kg', 'load17kg', 'given17kg', 'srg', 'lighter', 'stove', 'adap', 'hose'];
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