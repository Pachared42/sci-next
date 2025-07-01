<?php
require_once(__DIR__ . '/../../config/db.php');

// เปิดการแสดง error ใน dev เท่านั้น (ควรปิดเมื่ออยู่บน production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// ตั้งค่า header สำหรับ JSON และ UTF-8
header('Content-Type: application/json; charset=UTF-8');

// สร้าง SQL รวมสินค้าจากหลายตาราง
$sql = <<<SQL
SELECT product_name, barcode, price, image_url, stock, reorder_level FROM dried_food
UNION ALL
SELECT product_name, barcode, price, image_url, stock, reorder_level FROM fresh_food
UNION ALL
SELECT product_name, barcode, price, image_url, stock, reorder_level FROM snack
UNION ALL
SELECT product_name, barcode, price, image_url, stock, reorder_level FROM soft_drink
UNION ALL
SELECT product_name, barcode, price, image_url, stock, reorder_level FROM stationery
SQL;

// ตรวจสอบการเชื่อมต่อ
if (!$conn) {
    http_response_code(500);
    echo json_encode(['error' => 'ไม่สามารถเชื่อมต่อฐานข้อมูล']);
    exit;
}

// query
$result = $conn->query($sql);

// ตรวจสอบผลลัพธ์
if (!$result) {
    http_response_code(500);
    echo json_encode(['error' => 'Query ผิดพลาด', 'message' => $conn->error]);
    exit;
}

// แปลงผลลัพธ์เป็น JSON
$products = [];
while ($row = $result->fetch_assoc()) {
    // แปลงค่าทางตัวเลขให้เหมาะสม
    $row['price'] = (float) $row['price'];
    $row['stock'] = (int) $row['stock'];
    $row['reorder_level'] = (int) $row['reorder_level'];
    $products[] = $row;
}

// ปิดการเชื่อมต่อ
$conn->close();

// ส่ง JSON กลับ
echo json_encode($products, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
?>
