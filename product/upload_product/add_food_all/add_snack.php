<?php
require __DIR__ . '/../../../config/db.php'; // ไฟล์เชื่อมต่อฐานข้อมูล

header("Content-Type: application/json");
error_reporting(E_ALL);
ini_set('display_errors', 1);

// รับ JSON ที่ส่งมา
$data = json_decode(file_get_contents("php://input"), true);

// ตรวจสอบว่า JSON ถูกต้องหรือไม่
if (!$data) {
    echo json_encode(["success" => false, "message" => "ไม่ได้รับข้อมูล JSON หรือข้อมูลผิดรูปแบบ"]);
    exit;
}

// ตรวจสอบว่ามี key 'products' หรือไม่
if (!isset($data['products']) || empty($data['products'])) {
    echo json_encode(["success" => false, "message" => "ไม่มีสินค้าให้เพิ่ม"]);
    exit;
}

$sql = "INSERT INTO snack (product_name, image_url, barcode, price, cost, stock, reorder_level) 
        VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);

// ตรวจสอบว่าการเตรียม SQL สำเร็จหรือไม่
if (!$stmt) {
    echo json_encode(["success" => false, "message" => "SQL Error: " . $conn->error]);
    exit;
}

foreach ($data['products'] as $product) {
    // ตรวจสอบว่ามีข้อมูลที่สำคัญทุกอย่างหรือไม่
    if (!isset($product['productName'], $product['productImage'], $product['barcode'], $product['productPrice'], $product['productCost'], $product['productStock'], $product['productReorderLevel'])) {
        echo json_encode(["success" => false, "message" => "ข้อมูลสินค้าบางรายการขาด"]);
        exit;
    }

    // Bind ค่า
    $stmt->bind_param("ssssddd", 
        $product['productName'], 
        $product['productImage'], 
        $product['barcode'], 
        $product['productPrice'], 
        $product['productCost'], 
        $product['productStock'], 
        $product['productReorderLevel']
    );

    // Execute การเพิ่มสินค้า
    if (!$stmt->execute()) {
        echo json_encode(["success" => false, "message" => "Insert Error: " . $stmt->error]);
        exit;
    }
}

// ส่งผลลัพธ์หลังจากเพิ่มสินค้าเสร็จ
echo json_encode(["success" => true, "message" => "เพิ่มสินค้าเรียบร้อย"]);
exit;
?>