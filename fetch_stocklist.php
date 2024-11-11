<?php
include 'db_connection.php';

$fromDate = $_GET['from'];
$toDate = $_GET['to'];

// SQL query to fetch stock data for the selected range
$sql = "SELECT * FROM stock WHERE date BETWEEN '$fromDate' AND '$toDate' ORDER BY date ASC";
$result = $conn->query($sql);

$data = array();

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        // Format the date to dd-mm-yyyy before sending it to the frontend
        $formatted_date = date("d-m-Y", strtotime($row['date']));
        $data[] = array(
            'date' => $formatted_date, // Use the formatted date
            'closing_12kg_load' => $row['closing_12kg_load'],
            'closing_12kg_empty' => $row['closing_12kg_empty'],
            'closing_17kg_load' => $row['closing_17kg_load'],
            'closing_17kg_empty' => $row['closing_17kg_empty'],
            'closing_srg' => $row['closing_srg'],
            'closing_ind_srg' => $row['closing_ind_srg'],
            'closing_adap' => $row['closing_adap'],
            'closing_ind_adap' => $row['closing_ind_adap'],
            'closing_stove' => $row['closing_stove'],
            'closing_hose' => $row['closing_hose'],
            'closing_lighter' => $row['closing_lighter']
        );
    }
}

echo json_encode($data);

$conn->close();
?>
