<?php
require __DIR__ . '/../../db.php';  // เชื่อมต่อฐานข้อมูล

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // รับค่าจากฟอร์ม
    $productID = $_POST['productID']; // รับค่า ID ของสินค้าที่จะแก้ไข
    $productName = $_POST['productName'];
    $productPrice = $_POST['productPrice'];
    $productCategory = $_POST['productCategory']; // keycaps, keyboards, switches, accessories

    // เตรียมคำสั่ง SQL สำหรับการอัปเดตข้อมูลตามประเภทของสินค้า
    switch ($productCategory) {
        case 'keyboards':
            $sql = "UPDATE keyboard SET NAME = :name, PRICE = :price, KEYBOARD_SIZE = :keyboard_size WHERE ID = :id";
            $keyboardSize = $_POST['keyboardSize']; // รับค่า keyboard size จากฟอร์ม
            break;
        case 'switches':
            $sql = "UPDATE switches SET NAME = :name, PRICE = :price, SWITCH_TYPE = :switch_type WHERE ID = :id";
            $switchType = $_POST['switchType']; // รับค่า switch type จากฟอร์ม
            break;
        case 'keycaps':
            $sql = "UPDATE keycaps SET NAME = :name, PRICE = :price, KEYCAP_PROFILE = :keycap_profile WHERE ID = :id";
            $keycapProfile = $_POST['keycapProfile']; // รับค่า keycap profile จากฟอร์ม
            break;
        case 'accessories':
            $sql = "UPDATE accessory SET NAME = :name, PRICE = :price, PRODUCT_TYPE = :product_type WHERE ID = :id";
            $productType = $_POST['productType']; // รับค่า product type จากฟอร์ม
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
    oci_bind_by_name($stmt, ':id', $productID);

    // ผูกค่าหมวดหมู่สินค้าตามประเภท
    if ($productCategory == 'keyboards') {
        oci_bind_by_name($stmt, ':keyboard_size', $keyboardSize);
    } elseif ($productCategory == 'switches') {
        oci_bind_by_name($stmt, ':switch_type', $switchType);
    } elseif ($productCategory == 'keycaps') {
        oci_bind_by_name($stmt, ':keycap_profile', $keycapProfile);
    } elseif ($productCategory == 'accessories') {
        oci_bind_by_name($stmt, ':product_type', $productType);
    }

    // สั่งให้ฐานข้อมูลประมวลผลคำสั่ง
    if (oci_execute($stmt)) {
        echo "อัปเดตสินค้าสำเร็จ!";
    } else {
        $error = oci_error($stmt);
        echo "เกิดข้อผิดพลาด: " . $error['message'];
    }

    // ปิดการเชื่อมต่อ
    oci_free_statement($stmt);
    oci_close($conn);
}
?>
