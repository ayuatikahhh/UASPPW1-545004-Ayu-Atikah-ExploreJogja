<?php
session_start();
include '../connection.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!isset($_SESSION['id_user'])) {
        echo json_encode(['success' => false, 'error' => 'Anda belum login.']);
        exit;
    }

    $id_user = $_SESSION['id_user'];
    $destination_code = $_POST['destination_code'] ?? null;
    $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 0;

    if (!$destination_code || $quantity <= 0) {
        echo json_encode(['success' => false, 'error' => 'Data tidak lengkap atau salah.']);
        exit;
    }

    $stmt = $conn->prepare("CALL create_order(?, ?, ?)");
    $stmt->bind_param("isi", $id_user, $destination_code, $quantity);

    try {
        $stmt->execute();

        //invoice terakhir
        $result = $conn->query("SELECT invoice_number FROM orders WHERE id_user = $id_user ORDER BY id_order DESC LIMIT 1");
        $invoice = $result->fetch_assoc()['invoice_number'] ?? null;

        if (!$invoice) {
            echo json_encode(['success' => false, 'error' => 'Gagal mengambil invoice.']);
            exit;
        }

        // tiket
        $ticketsRes = $conn->query("SELECT ticket_code FROM tickets WHERE invoice_number = '$invoice'");
        $tickets = [];
        while ($row = $ticketsRes->fetch_assoc()) {
            $tickets[] = $row['ticket_code'];
        }

        echo json_encode([
            'success' => true,
            'invoice' => $invoice,
            'tickets' => $tickets
        ]);
    } catch (mysqli_sql_exception $e) {
        echo json_encode(['success' => false, 'error' => 'Gagal membuat pesanan: ' . $e->getMessage()]);
    }
}
?>
