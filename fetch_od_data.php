<?php
// fetch_od_data.php

header('Content-Type: application/json');

// Database credentials
include 'db_connection.php';


$date = $_GET['date'];

// Fetch the main record
$sql = "SELECT * FROM od_main WHERE date = '$date'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $mainRecord = $result->fetch_assoc();

    $mainId = $mainRecord['id'];
    $initialTotal = $mainRecord['initial_total'];
    $odCollectedTotal = $mainRecord['od_collected_total'];
    $outstandingTotal = $mainRecord['outstanding_total'];
    $finalCalculatedTotal = $mainRecord['final_calculated_total'];

    // Fetch the OD Collected records
    $odCollectedList = [];
    $sql = "SELECT name, cash, gpay FROM od_collected WHERE main_id = '$mainId'";
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        $odCollectedList[] = $row;
    }

    // Fetch the Outstanding records
    $outstandingList = [];
    $sql = "SELECT name, amount FROM outstanding WHERE main_id = '$mainId'";
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        $outstandingList[] = $row;
    }

    echo json_encode([
        'success' => true,
        'initialTotal' => $initialTotal,
        'odCollectedList' => $odCollectedList,
        'outstandingList' => $outstandingList,
        'odCollectedTotal' => $odCollectedTotal,
        'outstandingTotal' => $outstandingTotal,
        'finalCalculatedTotal' => $finalCalculatedTotal
    ]);

} else {
    echo json_encode(['success' => false, 'message' => 'No records found']);
}

$conn->close();
?>
