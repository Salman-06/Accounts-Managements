<?php
// Database connection details
$servername = "localhost";
//$servername = "191.101.79.103";
$username = "u648102058_covusr";
$password = "C0v@i@123";
$dbname = "u648102058_covai";

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Output a JSON error if the connection fails
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'Database connection failed: ' . $e->getMessage()]);
    exit;
}

// Get pagination and search parameters from request
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$entries = isset($_GET['entries']) ? (int)$_GET['entries'] : 10;
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Set up pagination calculations
$offset = ($page - 1) * $entries;

// Prepare the SQL query
$sql = "SELECT Mem_No, Mem_Id, Mem_Name, Mem_Fname, Mem_GST, Mem_Mobile FROM tblmember";
$params = [];

// Add search functionality
if (!empty($search)) {
    $sql .= " WHERE Mem_Name LIKE :search OR Mem_Fname LIKE :search OR Mem_Id LIKE :search";
    $params[':search'] = "%$search%";
}

// Add pagination limits
$sql .= " LIMIT :offset, :entries";
$params[':offset'] = $offset;
$params[':entries'] = $entries;

try {
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
    $stmt->bindValue(':entries', (int)$entries, PDO::PARAM_INT);
    if (!empty($search)) {
        $stmt->bindValue(':search', "%$search%", PDO::PARAM_STR);
    }
    $stmt->execute();
    $customers = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Count total records for pagination
    $countSql = "SELECT COUNT(*) FROM tblmember";
    if (!empty($search)) {
        $countSql .= " WHERE Mem_Name LIKE :search OR Mem_Fname LIKE :search OR Mem_Id LIKE :search";
    }
    $countStmt = $pdo->prepare($countSql);
    if (!empty($search)) {
        $countStmt->bindValue(':search', "%$search%", PDO::PARAM_STR);
    }
    $countStmt->execute();
    $totalRecords = $countStmt->fetchColumn();
    $totalPages = ceil($totalRecords / $entries);

    // Set header to JSON and output response
    header('Content-Type: application/json');
    echo json_encode([
        'success' => true,
        'customers' => $customers,
        'totalPages' => $totalPages
    ]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => 'Query failed: ' . $e->getMessage()]);
    exit;
}
?>
