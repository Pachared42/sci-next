<?php
require __DIR__ . '/../../db.php';  // เชื่อมต่อฐานข้อมูล

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // รับค่าจากฟอร์ม
    $productName = $_POST['productName'];
    $productPrice = $_POST['productPrice'];
    $productCategory = $_POST['productCategory']; // keycaps, keyboards, switches, accessories
    $image = $_POST['productImage']; // รับค่าจาก URL ของรูปภาพ
    $reviewVideoUrl = isset($_POST['reviewVideoUrl']) ? $_POST['reviewVideoUrl'] : null; // ถ้าไม่มีค่าให้เป็น NULL

    // ตรวจสอบว่า URL ของรูปภาพไม่ว่าง
    if (empty($image)) {
        echo "กรุณากรอก URL ของรูปภาพ!";
        exit;
    }

    // เตรียมคำสั่ง SQL สำหรับการแทรกข้อมูลตามประเภทของสินค้า
    switch ($productCategory) {
        case 'keyboards':
            $table = 'keyboard';
            $size = $_POST['keyboardSize']; // ต้องรับค่า keyboard size จากฟอร์ม
            $sql = "INSERT INTO $table (NAME, PRICE, IMAGE_URL, KEYBOARD_SIZE, REVIEW_VIDEO_URL) VALUES (:name, :price, :image_url, :keyboard_size, :review_video_url)";
            break;
        case 'switches':
            $table = 'switches';
            $switchType = $_POST['switchType']; // ต้องรับค่า switch type จากฟอร์ม
            $sql = "INSERT INTO $table (NAME, PRICE, IMAGE_URL, SWITCH_TYPE, REVIEW_VIDEO_URL) VALUES (:name, :price, :image_url, :switch_type, :review_video_url)";
            break;
        case 'keycaps':
            $table = 'keycaps';
            $keycapProfile = $_POST['keycapProfile']; // ต้องรับค่า keycap profile จากฟอร์ม
            $sql = "INSERT INTO $table (NAME, PRICE, IMAGE_URL, KEYCAP_PROFILE, REVIEW_VIDEO_URL) VALUES (:name, :price, :image_url, :keycap_profile, :review_video_url)";
            break;
        case 'accessories':
            $table = 'accessory';
            $productType = $_POST['productType']; // ต้องรับค่า product type จากฟอร์ม
            $sql = "INSERT INTO $table (NAME, PRICE, IMAGE_URL, PRODUCT_TYPE, REVIEW_VIDEO_URL) VALUES (:name, :price, :image_url, :product_type, :review_video_url)";
            break;
        default:
            echo "หมวดหมู่ไม่ถูกต้อง!";
            exit;
    }

    // เตรียมคำสั่ง SQL
    $stmt = oci_parse($conn, $sql);

    // ผูกค่าตัวแปรกับคำสั่ง SQL
    oci_bind_by_name($stmt, ':name', $productName);
    oci_bind_by_name($stmt, ':price', $productPrice);
    oci_bind_by_name($stmt, ':image_url', $image); // ใช้ URL ของรูปภาพ
    oci_bind_by_name($stmt, ':review_video_url', $reviewVideoUrl, -1); // ผูกค่าของ Review Video URL

    // ผูกค่าหมวดหมู่สินค้าตามประเภท
    if ($productCategory == 'keyboards') {
        oci_bind_by_name($stmt, ':keyboard_size', $size);
    } elseif ($productCategory == 'switches') {
        oci_bind_by_name($stmt, ':switch_type', $switchType);
    } elseif ($productCategory == 'keycaps') {
        oci_bind_by_name($stmt, ':keycap_profile', $keycapProfile);
    } elseif ($productCategory == 'accessories') {
        oci_bind_by_name($stmt, ':product_type', $productType);
    }

    // สั่งให้ฐานข้อมูลประมวลผลคำสั่ง
    if (oci_execute($stmt)) {
        echo "อัปโหลดสินค้าสำเร็จ!";
    } else {
        $error = oci_error($stmt);
        echo "เกิดข้อผิดพลาด: " . $error['message'];
    }

    // ปิดการเชื่อมต่อ
    oci_free_statement($stmt);
    oci_close($conn);
}
?>
