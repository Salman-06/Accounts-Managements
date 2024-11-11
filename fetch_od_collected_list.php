<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database connection
include 'db_connection.php';

if ($conn->connect_error) {
    echo json_encode(["error" => "Database connection failed: " . $conn->connect_error]);
    exit();
}

// Retrieve and sanitize input
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$pageSize = 20;
$offset = ($page - 1) * $pageSize;
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';

// Fetch paginated data with corrected table and column names
$sql = "SELECT * FROM od_table WHERE odCollected LIKE '%$search%' ORDER BY date DESC LIMIT $offset, $pageSize";
$result = $conn->query($sql);
if (!$result) {
    echo json_encode(["error" => "SQL error in fetching data: " . $conn->error]);
    exit();
}

$items = [];
while ($row = $result->fetch_assoc()) {
    $row['cash'] = (float) $row['cash'];
    $row['gpayBank'] = (float) $row['gpayBank'];
    $items[] = $row;
}


// Fetch totals
$totalSql = "SELECT COUNT(*) as totalItems, SUM(cash) as totalCash, SUM(gpayBank) as totalGpayBank FROM od_table WHERE odCollected LIKE '%$search%'";
$totalResult = $conn->query($totalSql);
if (!$totalResult) {
    echo json_encode(["error" => "SQL error in fetching totals: " . $conn->error]);
    exit();
}

$totalData = $totalResult->fetch_assoc();
$totalPages = ceil($totalData['totalItems'] / $pageSize);

// Output the response as JSON
echo json_encode([
    'items' => $items,
    'totals' => [
        'cash' => (float)($totalData['totalCash'] ?? 0),
        'gpayBank' => (float)($totalData['totalGpayBank'] ?? 0)
    ],
    'totalPages' => $totalPages
]);

$conn->close();
?>
