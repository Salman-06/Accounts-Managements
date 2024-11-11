<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection parameters
include 'db_connection.php';

// Get form data and handle empty values
$companyName = isset($_POST['companyName']) ? $_POST['companyName'] : '';
$customerName = isset($_POST['customerName']) ? $_POST['customerName'] : '';
$phoneNumber = isset($_POST['phoneNumber']) ? $_POST['phoneNumber'] : '';
$address1 = isset($_POST['address1']) ? $_POST['address1'] : '';
$address2 = isset($_POST['address2']) ? $_POST['address2'] : '';
$city = isset($_POST['city']) ? $_POST['city'] : '';
$pincode = isset($_POST['pincode']) ? $_POST['pincode'] : '';
$district = isset($_POST['district']) ? $_POST['district'] : '';
$state = isset($_POST['state']) ? $_POST['state'] : '';
$gstin = isset($_POST['gstin']) ? $_POST['gstin'] : '';
$idProof = isset($_POST['idProof']) ? $_POST['idProof'] : '';
$photo = isset($_FILES['photo']['name']) ? $_FILES['photo']['name'] : '';

// Handle file upload
$target_dir = "uploads/";
if (!file_exists($target_dir)) {
    mkdir($target_dir, 0777, true); // Create directory if it doesn't exist
}
$target_file = $target_dir . basename($_FILES["photo"]["name"]);
if (!empty($photo) && move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {
    $photoPath = $target_file;
} else {
    $photoPath = NULL;
}

// Generate the next customer ID
$result = $conn->query("SELECT MAX(id) AS max_id FROM customers");
$row = $result->fetch_assoc();
$nextId = $row['max_id'] + 1;
$customerID = 'CVT' . $nextId;

// Prepare and execute the SQL query
$stmt = $conn->prepare("INSERT INTO customers (customer_id, company_name, customer_name, phone_number, address1, address2, city, pincode, district, state, gstin, id_proof, photo_path) 
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssssssssssss", $customerID, $companyName, $customerName, $phoneNumber, $address1, $address2, $city, $pincode, $district, $state, $gstin, $idProof, $photoPath);

if ($stmt->execute()) {
    echo "<script>
            alert('New customer record created successfully with Customer ID: $customerID');
            window.location.href = 'newCustomer.html'; // Replace with your actual form URL
          </script>";
} else {
    echo "Error: " . $stmt->error;
}

// Close the connection
$stmt->close();
$conn->close();
?>
