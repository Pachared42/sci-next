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
    // สร้าง order ใหม่ (เว้นคอลัมน์ไว้ให้ฐานข้อมูลเซ็ต default)
    $insertOrderSQL = "INSERT INTO orders () VALUES ()";
    if (!$conn->query($insertOrderSQL)) {
        throw new Exception("สร้าง order ไม่สำเร็จ: " . $conn->error);
    }
    $order_id = $conn->insert_id;

    $tables = ['dried_food', 'fresh_food', 'snack', 'soft_drink', 'stationery'];

    // เตรียมคำสั่ง SQL สำหรับอัปเดต stock และ insert order_items
    $updateStockStmt = null;
    $insertItemStmt = $conn->prepare("
        INSERT INTO order_items 
            (order_id, product_name, barcode, price, quantity, image_url) 
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    if (!$insertItemStmt) {
        throw new Exception("เตรียมคำสั่ง INSERT order_items ไม่สำเร็จ: " . $conn->error);
    }

    foreach ($data as $barcode => $item) {
        $qty = (int) $item['quantity'];
        $price = (float) $item['price'];
        $name = $item['name'];
        $image = $item['image'];

        $found = false;

        // หัก stock ในแต่ละตาราง
        foreach ($tables as $table) {
            // เตรียมคำสั่ง UPDATE ในตารางนี้ (สร้างใหม่ทุกรอบเพื่อป้องกัน SQL Injection)
            $updateSQL = "UPDATE `$table` SET stock = stock - ? WHERE barcode = ? AND stock >= ?";
            $updateStockStmt = $conn->prepare($updateSQL);
            if (!$updateStockStmt) {
                throw new Exception("เตรียมคำสั่ง UPDATE stock ใน $table ไม่สำเร็จ: " . $conn->error);
            }
            $updateStockStmt->bind_param("isi", $qty, $barcode, $qty);
            $updateStockStmt->execute();

            if ($updateStockStmt->affected_rows > 0) {
                $found = true;
                $updateStockStmt->close();
                break;
            }
            $updateStockStmt->close();
        }

        if (!$found) {
            throw new Exception("ไม่สามารถหักสต็อกสินค้า barcode: $barcode ได้ (สินค้าหมดหรือไม่มีในฐานข้อมูล)");
        }

        // บันทึก order_items
        $insertItemStmt->bind_param(
            "issdis",
            $order_id,
            $name,
            $barcode,
            $price,
            $qty,
            $image
        );
        if (!$insertItemStmt->execute()) {
            throw new Exception("บันทึก order_items ล้มเหลว: " . $insertItemStmt->error);
        }
    }

    $conn->commit();
    echo json_encode(['success' => true, 'order_id' => $order_id]);

} catch (Exception $e) {
    $conn->rollback();
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
