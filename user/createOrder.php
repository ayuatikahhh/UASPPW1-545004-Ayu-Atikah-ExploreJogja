<?php
session_start();
header('Content-Type: application/json');

require_once '../connection.php'; 

try {
    $data = json_decode(file_get_contents("php://input"), true);

    if (!isset($data['id_user'], $data['destination_code'], $data['quantity'])) {
        throw new Exception("Data tidak lengkap.");
    }

    $id_user = intval($data['id_user']);
    $destination_code = $data['destination_code'];
    $quantity = intval($data['quantity']);
    $stmt = $conn->prepare("CALL create_order(?, ?, ?)");
    $stmt->bind_param("isi", $id_user, $destination_code, $quantity);
    $stmt->execute();

    echo json_encode(["success" => true]);
} catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "message" => $e->getMessage()
    ]);
}
?>
