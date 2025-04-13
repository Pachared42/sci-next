<?php
// เชื่อมต่อฐานข้อมูล
require __DIR__ . '/../../../db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // รับค่า id จาก URL
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;

    // รับข้อมูลจากฟอร์ม
    $product_name = $_POST['product_name'];
    $barcode = $_POST['barcode'];
    $price = $_POST['price'];
    $cost = $_POST['cost'];
    $stock = $_POST['stock'];
    $reorder_level = $_POST['reorder_level'];
    $image_url = $_POST['image_url'];

    // ตรวจสอบข้อมูลที่รับมา
    if (!empty($product_name) && !empty($barcode) && $id > 0) {

        // ตรวจสอบว่า image_url เป็น URL ถูกต้องหรือไม่
        if (!filter_var($image_url, FILTER_VALIDATE_URL)) {
            echo "กรุณากรอก URL";
            exit;
        }

        $query = "UPDATE dried_food SET 
                    product_name = ?, 
                    barcode = ?, 
                    price = ?, 
                    cost = ?, 
                    stock = ?, 
                    reorder_level = ?, 
                    image_url = ? 
                  WHERE id = ?";

        if ($stmt = $conn->prepare($query)) {

            // ประเภทข้อมูล: s=string, d=double, i=integer
            $stmt->bind_param("ssddii si", $product_name, $barcode, $price, $cost, $stock, $reorder_level, $image_url, $id);

            if ($stmt->execute()) {
                echo "อัปเดตข้อมูลสำเร็จ";
            } else {
                echo "เกิดข้อผิดพลาดในการอัปเดตข้อมูล: " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo "เกิดข้อผิดพลาดในการเตรียมคำสั่ง SQL: " . $conn->error;
        }
    } else {
        echo "กรุณากรอกข้อมูลให้ครบถ้วน";
    }

    $conn->close();
}
?>

