<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json'); // Ensure response is JSON
include '../conn.php';
// Get the raw POST data
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['action'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
}

$action = $data['action'];

if ($action === 'edit') {
    // Handle Edit Request
    $id = $data['id'];
    $pro_name = $data['pro_name'];
    $model = $data['model'];
    $date = $data['date'];
    $quantity = $data['quantity'];
    $color = $data['color'];

    $sql = "UPDATE place_order SET 
                pro_name = ?, 
                model = ?, 
                date = ?, 
                quantity = ?, 
                color = ? 
            WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssisi", $pro_name, $model, $date, $quantity, $color, $id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Order updated successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update order']);
    }
    $stmt->close();

} elseif ($action === 'delete') {
    // Handle Delete Request
    $id = $data['id'];

    $sql = "DELETE FROM place_order WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Order deleted successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to delete order']);
    }
    $stmt->close();

} else {
    echo json_encode(['success' => false, 'message' => 'Unknown action']);
}

$conn->close();
?>