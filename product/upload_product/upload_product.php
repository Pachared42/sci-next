<?php
require __DIR__ . '/../../db.php'; // เชื่อมต่อฐานข้อมูล

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // รับค่าจากฟอร์ม
    $productName = $_POST['productName'];
    $barcode = $_POST['barcode'];
    $price = $_POST['productPrice'];
    $cost = $_POST['productCost'];
    $stock = $_POST['stock'];
    $reorderLevel = $_POST['reorderLevel'];
    $image = $_POST['productImage']; // รับ URL ของรูปภาพ
    $productCategory = $_POST['productCategory']; // dried_food, fresh_food, soft_drink

    // ตรวจสอบว่าหมวดหมู่ถูกต้อง
    $validCategories = ['dried_food', 'fresh_food', 'soft_drink'];
    if (!in_array($productCategory, $validCategories)) {
        echo "หมวดหมู่สินค้าไม่ถูกต้อง!";
        exit;
    }

    // เตรียมคำสั่ง SQL
    $sql = "INSERT INTO $productCategory (product_name, barcode, price, cost, stock, reorder_level, image_url) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";

    // เตรียมคำสั่ง SQL และผูกค่าข้อมูล
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdddss", $productName, $barcode, $price, $cost, $stock, $reorderLevel, $image);

    // ประมวลผลคำสั่ง
    if ($stmt->execute()) {
        echo "อัปโหลดสินค้าสำเร็จ!";
    } else {
        echo "เกิดข้อผิดพลาด: " . $stmt->error;
    }

    // ปิดการเชื่อมต่อ
    $stmt->close();
    $conn->close();
}
?>