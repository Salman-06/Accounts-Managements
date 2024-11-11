<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');

$response = array();

try {
    // Database connection
    $pdo = new PDO('mysql:host=localhost;dbname= u648102058_salman', ' u648102058_dbusr', 'Azad@0606');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Get POST data
    $saleId = $_POST['saleId'];
    $salesDate = $_POST['salesDate'];
    $customerName = $_POST['customerName'];
    $remarks = $_POST['remarks'];
    $load12kg = $_POST['load12kg'];
    $empty12kg = $_POST['empty12kg'];
    $load17kg = $_POST['load17kg'];
    $empty17kg = $_POST['empty17kg'];
    $srg = $_POST['srg'];
    $adap = $_POST['adap'];
    $stove = $_POST['stove'];
    $lighter = $_POST['lighter'];
    $hose = $_POST['hose'];
    $totalAmount = $_POST['totalAmount'];
    $deposit = $_POST['deposit'];
    $amountPaid = $_POST['amountPaid'];
    $amountOD = $_POST['amountOD'];

    // Update sales data in the database
    $stmt = $pdo->prepare("UPDATE sales SET salesDate = ?, customerName = ?, remarks = ?, load12kg = ?, empty12kg = ?, load17kg = ?, empty17kg = ?, srg = ?, adap = ?, stove = ?, lighter = ?, hose = ?, totalAmount = ?, deposit = ?, amountPaid = ?, amountOD = ? WHERE id = ?");
    $stmt->execute([$salesDate, $customerName, $remarks, $load12kg, $empty12kg, $load17kg, $empty17kg, $srg, $adap, $stove, $lighter, $hose, $totalAmount, $deposit, $amountPaid, $amountOD, $saleId]);

    $response['success'] = true;
    $response['message'] = 'Sales data updated successfully';
} catch (Exception $e) {
    $response['success'] = false;
    $response['message'] = 'Error: ' . $e->getMessage();
}

echo json_encode($response);
?>
