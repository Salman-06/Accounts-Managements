<?php
// Database connection
$host = 'localhost';
$dbname = 'u648102058_salman';
$username = 'u648102058_dbusr';
$password = 'Azad@0606';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Database connection failed: ' . $e->getMessage()]);
    exit();
}

// Retrieve selected date from the URL parameter
if (isset($_GET['date'])) {
    $selectedDate = $_GET['date'];

    // Fetch sales data for the selected date
    $salesQuery = $pdo->prepare("SELECT * FROM sales WHERE salesDate = :salesDate");
    $salesQuery->execute(['salesDate' => $selectedDate]);
    $salesData = $salesQuery->fetchAll(PDO::FETCH_ASSOC);

    // Fetch totals for the selected date
    $totalsQuery = $pdo->prepare("SELECT totalForSelectedDate, odCollectedTotal, outstandingTotal, finalTotal FROM totals_table WHERE date = :date");
    $totalsQuery->execute(['date' => $selectedDate]);
    $totalsData = $totalsQuery->fetch(PDO::FETCH_ASSOC);

    // Return both sales data and totals in JSON format
    echo json_encode([
        'sales' => $salesData,
        'totals' => $totalsData
    ]);
} else {
    echo json_encode(['error' => 'Date parameter is missing']);
}
?>
