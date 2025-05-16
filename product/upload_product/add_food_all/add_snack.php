<?php
require __DIR__ . '/../../../config/db.php';

header("Content-Type: application/json");
error_reporting(E_ALL);
ini_set('display_errors', 1);

$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
    http_response_code(400);
    ob_clean();
    echo json_encode(["success" => false, "message" => "ไม่ได้รับข้อมูล JSON หรือข้อมูลผิดรูปแบบ"]);
    exit;
}

if (!isset($data['products']) || empty($data['products'])) {
    http_response_code(400);
    ob_clean();
    echo json_encode(["success" => false, "message" => "ไม่มีสินค้าให้เพิ่ม"]);
    exit;
}

$sql = "INSERT INTO snack (product_name, image_url, barcode, price, cost, stock, reorder_level) 
        VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    http_response_code(500);
    ob_clean();
    echo json_encode(["success" => false, "message" => "SQL Error: " . $conn->error]);
    exit;
}

foreach ($data['products'] as $product) {
    if (!isset($product['productName'], $product['productImage'], $product['barcode'], $product['productPrice'], $product['productCost'], $product['productStock'], $product['productReorderLevel'])) {
        http_response_code(400);
        ob_clean();
        echo json_encode(["success" => false, "message" => "ข้อมูลสินค้าบางรายการขาด"]);
        exit;
    }

    $stmt->bind_param("sssddii", 
        $product['productName'], 
        $product['productImage'], 
        $product['barcode'], 
        $product['productPrice'], 
        $product['productCost'], 
        $product['productStock'], 
        $product['productReorderLevel']
    );

    if (!$stmt->execute()) {
        http_response_code(500);
        ob_clean();
        echo json_encode(["success" => false, "message" => "Insert Error: " . $stmt->error]);
        exit;
    }
}

ob_clean();
echo json_encode(["success" => true, "message" => "เพิ่มสินค้าเรียบร้อย"]);
exit;
?>
