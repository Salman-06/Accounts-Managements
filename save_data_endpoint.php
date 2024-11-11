<?php
header("Content-Type: application/json");
$data = json_decode(file_get_contents("php://input"), true);

if ($data) {
    $date = $data['date'];
    $totalForSelectedDate = $data['totalForSelectedDate'];
    $odCollectedTotal = $data['odCollectedTotal'];
    $outstandingTotal = $data['outstandingTotal'];
    $finalTotal = $data['finalTotal'];
    $odEntries = $data['odEntries'];  // Assuming this is an array of OD entries with odCollected, cash, gpayBank

    // Database connection (replace with your actual DB credentials)
    $conn = new mysqli("localhost", "root", "", "covai");

    if ($conn->connect_error) {
        echo json_encode(["success" => false, "message" => "Database connection failed"]);
        exit;
    }

    // Save general totals if needed
    $stmtGeneral = $conn->prepare("INSERT INTO totals_table (date, totalForSelectedDate, odCollectedTotal, outstandingTotal, finalTotal) VALUES (?, ?, ?, ?, ?)");
    $stmtGeneral->bind_param("sdddd", $date, $totalForSelectedDate, $odCollectedTotal, $outstandingTotal, $finalTotal);
    $stmtGeneral->execute();
    $stmtGeneral->close();

    // Save each OD entry with cash and gpayBank
    foreach ($odEntries as $entry) {
        $odCollected = $conn->real_escape_string($entry['odCollected']); // Escape for safety
        $cash = floatval($entry['cash']);
        $gpayBank = floatval($entry['gpayBank']);
    
        // Use raw SQL to insert directly
        $sql = "INSERT INTO od_table (date, odCollected, cash, gpayBank) VALUES ('$date', '$odCollected', $cash, $gpayBank)";
        if (!$conn->query($sql)) {
            echo json_encode(["success" => false, "message" => "Error: " . $conn->error]);
            exit;
        }
    }
       

    echo json_encode(["success" => true, "message" => "Data saved successfully"]);
} else {
    echo json_encode(["success" => false, "message" => "Invalid data received"]);
}
?>
