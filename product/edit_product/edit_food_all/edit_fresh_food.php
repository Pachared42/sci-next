<?php
require __DIR__ . '/../../../config/db.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method ไม่ถูกต้อง']);
    exit;
}

// รับค่า id จาก POST แทน GET
$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

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

// ตรวจสอบข้อมูล
if (!$product_name || !$barcode || $price === false || $cost === false || $stock === false || $reorder_level === false) {
    echo json_encode(['success' => false, 'message' => 'ข้อมูลไม่ครบหรือข้อมูลไม่ถูกต้อง']);
    exit;
}

try {
    $query = "UPDATE fresh_food SET 
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

$conn->close();
?>