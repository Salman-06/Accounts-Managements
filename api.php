<?php
// api.php
header('Content-Type: application/json');
require_once 'config.php';

$action = $_GET['action'] ?? '';

switch ($action) {
    case 'get_total_for_date':
        echo json_encode(getTotalForDate($_GET['date'] ?? ''));
        break;
    case 'get_data_for_date':
        echo json_encode(getDataForDate($_GET['date'] ?? ''));
        break;
    case 'save_data':
        echo json_encode(saveData());
        break;
    default:
        echo json_encode(['error' => 'Invalid action']);
}

function getTotalForDate($date) {
    global $conn;
    
    $sql = "SELECT SUM(totalAmount) as total FROM (
                SELECT SUM(od_collected + cash + gpay_bank) as totalAmount 
                FROM od_collected WHERE date = ? 
                UNION ALL 
                SELECT SUM(amount) as totalAmount 
                FROM outstanding WHERE date = ?
            ) as combined";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $date, $date);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    return ['total' => $row['total'] ?? 0];
}

function getDataForDate($date) {
    global $conn;
    
    $od_collected = [];
    $outstanding = [];
    
    // Fetch OD Collected data
    $sql = "SELECT * FROM od_collected WHERE date = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $date);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $od_collected[] = $row;
    }
    
    // Fetch Outstanding data
    $sql = "SELECT * FROM outstanding WHERE date = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $date);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $outstanding[] = $row;
    }
    
    return ['odCollected' => $od_collected, 'outstanding' => $outstanding];
}

function saveData() {
    global $conn;
    
    $data = json_decode(file_get_contents('php://input'), true);
    $date = $data['date'] ?? '';
    
    if (empty($date)) {
        return ['success' => false, 'error' => 'Date is required'];
    }
    
    // Begin transaction
    $conn->begin_transaction();
    
    try {
        // Save OD Collected data
        $sql = "INSERT INTO od_collected (date, od_collected, cash, gpay_bank) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        
        foreach ($data['odCollected'] as $item) {
            $stmt->bind_param("sddd", $date, $item['od_collected'], $item['cash'], $item['gpay_bank']);
            $stmt->execute();
        }
        
        // Save Outstanding data
        $sql = "INSERT INTO outstanding (date, name, amount) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        
        foreach ($data['outstanding'] as $item) {
            $stmt->bind_param("ssd", $date, $item['name'], $item['amount']);
            $stmt->execute();
        }
        
        // Commit transaction
        $conn->commit();
        
        return ['success' => true];
    } catch (Exception $e) {
        // Rollback transaction on error
        $conn->rollback();
        return ['success' => false, 'error' => $e->getMessage()];
    }
}

$conn->close();
?>