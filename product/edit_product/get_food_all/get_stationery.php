<?php
require __DIR__ . '/../../../config/db.php';

header('Content-Type: application/json');

// ตรวจสอบว่า id ถูกส่งมาหรือไม่และเป็นตัวเลข
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);
} else {
    http_response_code(400);
    echo json_encode(["message" => "ไม่ได้ระบุรหัสสินค้า"]);
    exit;
}

$query = "SELECT id, product_name, barcode, price, cost, stock, reorder_level, image_url FROM stationery WHERE id = ?";
$stmt = $conn->prepare($query);

if (!$stmt) {
    http_response_code(500);
    echo json_encode(["message" => "เกิดข้อผิดพลาดในการเตรียมคำสั่ง SQL: " . $conn->error]);
    exit;
}

$stmt->bind_param("i", $id);

if (!$stmt->execute()) {
    http_response_code(500);
    echo json_encode(["message" => "เกิดข้อผิดพลาดในการรันคำสั่ง SQL: " . $stmt->error]);
    exit;
}

$result = $stmt->get_result();
$product = $result->fetch_assoc();

if ($product) {
    echo json_encode($product);
} else {
    http_response_code(404);
    echo json_encode(["message" => "ไม่พบสินค้า"]);
}

$stmt->close();
$conn->close();
?>

