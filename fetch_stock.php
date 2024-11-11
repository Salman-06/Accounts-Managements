<?php
include 'db_connection.php';

$date = $_GET['date'];
$previous_date = date('Y-m-d', strtotime($date . ' -1 day'));

$sql = "SELECT closing_12kg_empty, closing_12kg_load, closing_17kg_empty, closing_17kg_load, closing_hose, closing_srg, closing_ind_srg, closing_adap, closing_ind_adap, closing_lighter, closing_stove FROM stock WHERE date='$previous_date'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo json_encode($row);
} else {
    echo json_encode([]);
}

$conn->close();
?>
