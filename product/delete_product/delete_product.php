<?php
require __DIR__ . '/../../db.php';

header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];  // รับ id ของสินค้า
    $category = $_POST['category'];  // รับ category ที่ส่งมาจาก JavaScript

    // ตรวจสอบหมวดหมู่เพื่อเลือกตาราง
    switch ($category) {
        case 'keyboards':
            $table = 'keyboard';
            break;
        case 'switches':
            $table = 'switches';
            break;
        case 'keycaps':
            $table = 'keycaps';
            break;
        case 'accessories':
            $table = 'accessory';
            break;
        default:
            echo json_encode(["success" => false, "message" => "หมวดหมู่ไม่ถูกต้อง"]);
            exit();
    }

    // ลบสินค้าออกจากฐานข้อมูล
    $sql = "DELETE FROM $table WHERE ID = :id";
    $stmt = oci_parse($conn, $sql);
    oci_bind_by_name($stmt, ":id", $id);

    if (oci_execute($stmt)) {
        echo json_encode(["success" => true, "message" => "ลบสินค้าสำเร็จ!"]);  // ส่งผลลัพธ์กลับเมื่อลบสำเร็จ
    } else {
        $error = oci_error($stmt);
        echo json_encode(["success" => false, "message" => $error['message']]);  // ส่งข้อความข้อผิดพลาด
    }
}
?>