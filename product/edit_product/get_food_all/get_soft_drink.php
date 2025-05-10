<?php
require __DIR__ . '/../../../config/db.php';

// ตรวจสอบว่า id ถูกส่งมาหรือไม่และเป็นตัวเลข
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);
} else {
    http_response_code(400);
    echo json_encode(["message" => "ไม่ได้ระบุรหัสสินค้า"]);
    exit;
}

$query = "SELECT id, product_name, barcode, price, cost, stock, reorder_level, image_url FROM soft_drink WHERE id = ?";
if ($stmt = $conn->prepare($query)) {
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();

    if ($product) {
        header('Content-Type: application/json');
        echo json_encode($product);
    } else {
        http_response_code(404);
        echo json_encode(["message" => "ไม่พบสินค้า"]);
    }

    $stmt->close();
} else {
    http_response_code(500);
    echo json_encode(["message" => "เกิดข้อผิดพลาดในการเตรียมคำสั่ง SQL"]);
}

$conn->close();
?>
