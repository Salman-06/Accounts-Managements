<?php
// Database connection
include 'db_connection.php';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Database connection failed: ' . $e->getMessage()]);
    exit();
}

// Retrieve parameters
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$query = isset($_GET['query']) ? trim($_GET['query']) : '';
$itemsPerPage = 20;
$offset = ($page - 1) * $itemsPerPage;

// Base SQL query with closing stock columns
$sql = "SELECT id, date, closing_12kg_empty, closing_12kg_load, closing_17kg_empty, closing_17kg_load, closing_hose, 
        closing_srg, closing_ind_srg, closing_adap, closing_ind_adap, closing_lighter, closing_stove 
        FROM stock";

// Append search criteria if provided
if ($query !== '') {
    $sql .= " WHERE date LIKE :query OR odCollected LIKE :query";
}

// Add pagination and prepare statement
$sql .= " LIMIT :limit OFFSET :offset";
$stmt = $pdo->prepare($sql);

// Bind parameters
if ($query !== '') {
    $stmt->bindValue(':query', "%$query%", PDO::PARAM_STR);
}
$stmt->bindValue(':limit', $itemsPerPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

// Execute the query
try {
    $stmt->execute();
    $stockData = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Respond with stock data
    echo json_encode(['stock' => $stockData]);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Query failed: ' . $e->getMessage()]);
}
?>
