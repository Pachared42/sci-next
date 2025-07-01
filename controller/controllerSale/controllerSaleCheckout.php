<?php
require_once(__DIR__ . '/../../config/db.php');
header('Content-Type: application/json');

// รับข้อมูล JSON ที่ส่งมาทาง POST
$data = json_decode(file_get_contents("php://input"), true);

// ตรวจสอบข้อมูล
if (empty($data) || !is_array($data)) {
    echo json_encode(['success' => false, 'message' => 'ไม่มีข้อมูล']);
    exit;
}

$conn->begin_transaction();

try {
    // ✅ สร้าง order ใหม่
    if (!$conn->query("INSERT INTO orders () VALUES ()")) {
        throw new Exception("สร้าง order ไม่สำเร็จ: " . $conn->error);
    }
    $order_id = $conn->insert_id;

    $tables = ['dried_food', 'fresh_food', 'snack', 'soft_drink', 'stationery'];

    // ✅ เตรียมคำสั่ง insert
    $insertItemStmt = $conn->prepare("
        INSERT INTO order_items 
            (order_id, product_name, barcode, price, quantity, image_url) 
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    if (!$insertItemStmt) {
        throw new Exception("เตรียมคำสั่ง INSERT order_items ไม่สำเร็จ: " . $conn->error);
    }

    // ✅ รวมรายการสินค้าซ้ำ
    $mergedData = [];
    foreach ($data as $barcode => $item) {
        if (!isset($mergedData[$barcode])) {
            $mergedData[$barcode] = $item;
        } else {
            $mergedData[$barcode]['quantity'] += $item['quantity'];
        }
    }

    foreach ($mergedData as $barcode => $item) {
        $qty = (int) $item['quantity'];
        $price = (float) $item['price'];
        $name = trim($item['name']);
        $image = trim($item['image']);

        $found = false;

        // ✅ หัก stock จากตารางที่มี
        foreach ($tables as $table) {
            $stmt = $conn->prepare("UPDATE `$table` SET stock = stock - ? WHERE barcode = ? AND stock >= ?");
            if (!$stmt) {
                throw new Exception("เตรียมคำสั่ง UPDATE stock ล้มเหลว: " . $conn->error);
            }
            $stmt->bind_param("isi", $qty, $barcode, $qty);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                $found = true;
                $stmt->close();
                break;
            }
            $stmt->close();
        }

        if (!$found) {
            throw new Exception("ไม่สามารถหักสต็อกสินค้า barcode: $barcode ได้ (สินค้าหมดหรือไม่มีในฐานข้อมูล)");
        }

        // ✅ บันทึก order_items
        $insertItemStmt->bind_param("issdis", $order_id, $name, $barcode, $price, $qty, $image);
        if (!$insertItemStmt->execute()) {
            throw new Exception("บันทึก order_items ล้มเหลว: " . $insertItemStmt->error);
        }
    }

    $insertItemStmt->close();
    $conn->commit();
    echo json_encode(['success' => true, 'order_id' => $order_id]);

} catch (Exception $e) {
    $conn->rollback();
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
