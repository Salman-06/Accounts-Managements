<?php
include 'db_connection.php';

// Fetch data from POST
$date = $_POST['date'];

// Opening Stock
$opening_12kg_empty = $_POST['opening_12kg_empty'];
$opening_12kg_load = $_POST['opening_12kg_load'];
$opening_17kg_empty = $_POST['opening_17kg_empty'];
$opening_17kg_load = $_POST['opening_17kg_load'];
$opening_hose = $_POST['opening_hose'];
$opening_srg = $_POST['opening_srg'];
$opening_ind_srg = $_POST['opening_ind_srg'];
$opening_adap = $_POST['opening_adap'];
$opening_ind_adap = $_POST['opening_ind_adap'];
$opening_lighter = $_POST['opening_lighter'];
$opening_stove = $_POST['opening_stove'];

// Purchases
$purchase_12kg_empty = $_POST['purchase_12kg_empty'];
$purchase_12kg_load = $_POST['purchase_12kg_load'];
$purchase_17kg_empty = $_POST['purchase_17kg_empty'];
$purchase_17kg_load = $_POST['purchase_17kg_load'];
$purchase_hose = $_POST['purchase_hose'];
$purchase_srg = $_POST['purchase_srg'];
$purchase_ind_srg = $_POST['purchase_ind_srg'];
$purchase_adap = $_POST['purchase_adap'];
$purchase_ind_adap = $_POST['purchase_ind_adap'];
$purchase_lighter = $_POST['purchase_lighter'];
$purchase_stove = $_POST['purchase_stove'];

// Sales
$sales_12kg_empty = $_POST['sales_12kg_empty'];
$sales_12kg_load = $_POST['sales_12kg_load'];
$sales_17kg_empty = $_POST['sales_17kg_empty'];
$sales_17kg_load = $_POST['sales_17kg_load'];
$sales_hose = $_POST['sales_hose'];
$sales_srg = $_POST['sales_srg'];
$sales_ind_srg = $_POST['sales_ind_srg'];
$sales_adap = $_POST['sales_adap'];
$sales_ind_adap = $_POST['sales_ind_adap'];
$sales_lighter = $_POST['sales_lighter'];
$sales_stove = $_POST['sales_stove'];

// Closing Stock
$closing_12kg_empty = $_POST['closing_12kg_empty'];
$closing_12kg_load = $_POST['closing_12kg_load'];
$closing_17kg_empty = $_POST['closing_17kg_empty'];
$closing_17kg_load = $_POST['closing_17kg_load'];
$closing_hose = $_POST['closing_hose'];
$closing_srg = $_POST['closing_srg'];
$closing_ind_srg = $_POST['closing_ind_srg'];
$closing_adap = $_POST['closing_adap'];
$closing_ind_adap = $_POST['closing_ind_adap'];
$closing_lighter = $_POST['closing_lighter'];
$closing_stove = $_POST['closing_stove'];

// Insert data into the table
$sql = "INSERT INTO stock (date, opening_12kg_empty, opening_12kg_load, opening_17kg_empty, opening_17kg_load, opening_hose, 
        opening_srg, opening_ind_srg, opening_adap, opening_ind_adap, opening_lighter, opening_stove, purchase_12kg_empty, 
        purchase_12kg_load, purchase_17kg_empty, purchase_17kg_load, purchase_hose, purchase_srg, purchase_ind_srg, purchase_adap, 
        purchase_ind_adap, purchase_lighter, purchase_stove, sales_12kg_empty, sales_12kg_load, sales_17kg_empty, sales_17kg_load, 
        sales_hose, sales_srg, sales_ind_srg, sales_adap, sales_ind_adap, sales_lighter, sales_stove, closing_12kg_empty, 
        closing_12kg_load, closing_17kg_empty, closing_17kg_load, closing_hose, closing_srg, closing_ind_srg, closing_adap, 
        closing_ind_adap, closing_lighter, closing_stove) 
        VALUES ('$date', '$opening_12kg_empty', '$opening_12kg_load', '$opening_17kg_empty', '$opening_17kg_load', '$opening_hose', 
        '$opening_srg', '$opening_ind_srg', '$opening_adap', '$opening_ind_adap', '$opening_lighter', '$opening_stove', 
        '$purchase_12kg_empty', '$purchase_12kg_load', '$purchase_17kg_empty', '$purchase_17kg_load', '$purchase_hose', 
        '$purchase_srg', '$purchase_ind_srg', '$purchase_adap', '$purchase_ind_adap', '$purchase_lighter', '$purchase_stove', 
        '$sales_12kg_empty', '$sales_12kg_load', '$sales_17kg_empty', '$sales_17kg_load', '$sales_hose', '$sales_srg', '$sales_ind_srg', 
        '$sales_adap', '$sales_ind_adap', '$sales_lighter', '$sales_stove', '$closing_12kg_empty', '$closing_12kg_load', 
        '$closing_17kg_empty', '$closing_17kg_load', '$closing_hose', '$closing_srg', '$closing_ind_srg', '$closing_adap', 
        '$closing_ind_adap', '$closing_lighter', '$closing_stove')";

if ($conn->query($sql) === TRUE) {
    echo "Record saved successfully!";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
