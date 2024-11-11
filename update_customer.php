<?php
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');  // Return JSON data

// Include database connection
include 'db_connection.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = intval($_POST['id']);
    $companyName = $_POST['companyName'];
    $customerName = $_POST['customerName'];
    $phoneNumber = $_POST['phoneNumber'];
    $address1 = $_POST['address1'];
    $address2 = $_POST['address2'];
    $city = $_POST['city'];
    $pincode = $_POST['pincode'];
    $district = $_POST['district'];
    $state = $_POST['state'];
    $gstin = $_POST['gstin'];

    $query = "UPDATE customers SET 
              company_name = '$companyName',
              customer_name = '$customerName',
              phone_number = '$phoneNumber',
              address1 = '$address1',
              address2 = '$address2',
              city = '$city',
              pincode = '$pincode',
              district = '$district',
              state = '$state',
              gstin = '$gstin'
              WHERE customer_id = $id";

    if (mysqli_query($conn, $query)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => mysqli_error($conn)]);
    }
}
?>