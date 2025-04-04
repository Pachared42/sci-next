<?php
// เชื่อมต่อฐานข้อมูล
require __DIR__ . '/../../../db.php';

// ตรวจสอบว่ามีการส่งข้อมูลมาไหม
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // รับข้อมูลจากฟอร์ม
    $id = $_GET['id']; // รับ id จาก URL (เช่น ?id=123)
    $product_name = $_POST['product_name'];
    $barcode = $_POST['barcode'];
    $price = $_POST['price'];
    $cost = $_POST['cost'];
    $stock = $_POST['stock'];
    $reorder_level = $_POST['reorder_level'];
    $image_url = $_POST['image_url'];

    // ตรวจสอบข้อมูลที่ได้รับจากฟอร์ม (สามารถเพิ่มการตรวจสอบข้อมูลเพิ่มเติมได้)
    if (!empty($product_name) && !empty($barcode)) {
        // สร้างคำสั่ง SQL เพื่ออัปเดตข้อมูลในฐานข้อมูล
        $query = "UPDATE dried_food SET 
                    product_name = ?, 
                    barcode = ?, 
                    price = ?, 
                    cost = ?, 
                    stock = ?, 
                    reorder_level = ?, 
                    image_url = ? 
                  WHERE id = ?";

        // เตรียมคำสั่ง SQL
        if ($stmt = $conn->prepare($query)) {
            // ผูกค่ากับคำสั่ง SQL
            $stmt->bind_param("ssddiiis", $product_name, $barcode, $price, $cost, $stock, $reorder_level, $image_url, $id);

            // เรียกใช้คำสั่ง SQL
            if ($stmt->execute()) {
                // ถ้าการอัปเดตสำเร็จ
                echo "อัปเดตข้อมูลสำเร็จ";
            } else {
                // ถ้าเกิดข้อผิดพลาด
                echo "เกิดข้อผิดพลาดในการอัปเดตข้อมูล";
            }

            // ปิดคำสั่ง SQL
            $stmt->close();
        } else {
            echo "เกิดข้อผิดพลาดในการเตรียมคำสั่ง SQL";
        }
    } else {
        echo "กรุณากรอกข้อมูลให้ครบถ้วน";
    }

    // ปิดการเชื่อมต่อฐานข้อมูล
    $conn->close();
}
