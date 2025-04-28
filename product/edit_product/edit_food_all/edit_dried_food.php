<?php
// เชื่อมต่อฐานข้อมูล
require __DIR__ . '/../../../config/db.php';

// ตั้งค่า header เป็น JSON
header('Content-Type: application/json');

// ตรวจสอบ method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method ไม่ถูกต้อง']);
    exit;
}

// รับค่า id จาก URL และทำความสะอาดข้อมูล
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id || $id <= 0) {
    echo json_encode(['success' => false, 'message' => 'ID ไม่ถูกต้อง']);
    exit;
}

// รับข้อมูลจากฟอร์ม
$product_name = isset($_POST['product_name']) ? htmlspecialchars(trim($_POST['product_name'])) : '';
$barcode = isset($_POST['barcode']) ? htmlspecialchars(trim($_POST['barcode'])) : '';
$price = filter_input(INPUT_POST, 'price', FILTER_VALIDATE_FLOAT);
$cost = filter_input(INPUT_POST, 'cost', FILTER_VALIDATE_FLOAT);
$stock = filter_input(INPUT_POST, 'stock', FILTER_VALIDATE_INT);
$reorder_level = filter_input(INPUT_POST, 'reorder_level', FILTER_VALIDATE_INT);

// ตรวจสอบว่าข้อมูลครบถ้วน
if (!$product_name || !$barcode || $price === false || $cost === false || $stock === false || $reorder_level === false) {
    echo json_encode(['success' => false, 'message' => 'ข้อมูลไม่ครบหรือข้อมูลไม่ถูกต้อง']);
    exit;
}

// ทำงาน SQL ใน try-catch เพื่อจับ error
try {
    $query = "UPDATE dried_food SET 
                product_name = ?, 
                barcode = ?, 
                price = ?, 
                cost = ?, 
                stock = ?, 
                reorder_level = ?
              WHERE id = ?";

    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("ssddiii", $product_name, $barcode, $price, $cost, $stock, $reorder_level, $id);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'อัปเดตข้อมูลสำเร็จ']);
        } else {
            throw new Exception("Execute Error: " . $stmt->error);
        }

        $stmt->close();
    } else {
        throw new Exception("Prepare Error: " . $conn->error);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

// ปิดการเชื่อมต่อ
$conn->close();
?>
