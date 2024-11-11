<?php
// Enable error reporting for debugging (remove in production)
ini_set('display_errors', 0);  // Disable error display in production
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);

// Set the content type to JSON
header('Content-Type: application/json');

try {
    // Database connection details
    $host = "localhost";
    $dbname = "covai";  // Your database name
    $username = "root";       // Your database username
    $password = "";          // Your database password

    // Create connection using PDO for better security
    $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];
    
    $pdo = new PDO($dsn, $username, $password, $options);

    // Validate and get the date parameter
    if (!isset($_GET['date']) || empty($_GET['date'])) {
        throw new Exception("No date provided");
    }

    $date = $_GET['date'];
    
    // Validate date format (assuming YYYY-MM-DD format)
    if (!preg_match("/^\d{4}-\d{2}-\d{2}$/", $date)) {
        throw new Exception("Invalid date format");
    }

    // Prepare and execute the query using prepared statements
    $stmt = $pdo->prepare("SELECT * FROM stock WHERE date = :date LIMIT 1");
    $stmt->execute(['date' => $date]);
    
    $result = $stmt->fetch();

    if ($result) {
        echo json_encode($result);
    } else {
        echo json_encode(["message" => ""]);
    }

} catch (PDOException $e) {
    // Handle database errors
    http_response_code(500);
    echo json_encode(["error" => "Database error occurred"]);
    error_log("Database Error: " . $e->getMessage());
} catch (Exception $e) {
    // Handle other errors
    http_response_code(400);
    echo json_encode(["error" => $e->getMessage()]);
}