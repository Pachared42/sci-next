<?php
// เปิดการใช้งาน session ถ้ามีการยืนยันสิทธิ์ผู้ใช้
session_start();

// เชื่อมต่อฐานข้อมูล
require_once '../../config/db.php'; // เปลี่ยนชื่อไฟล์หากคุณใช้ชื่ออื่น

// ตรวจสอบว่ามีค่าที่ส่งมาหรือไม่
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // ตรวจสอบว่าได้รับค่า ID หรือไม่
    if (isset($_POST['id'])) {
        $id = intval($_POST['id']); // แปลงให้เป็น int เพื่อความปลอดภัย

        // เตรียมคำสั่ง SQL แบบ prepared statement
        $stmt = $conn->prepare("DELETE FROM soft_drink WHERE id = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            echo json_encode([
                "success" => true,
                "message" => "ลบสินค้าสำเร็จ"
            ]);
        } else {
            echo json_encode([
                "success" => false,
                "message" => "เกิดข้อผิดพลาดในการลบสินค้า: " . $stmt->error
            ]);
        }

        $stmt->close();
        $conn->close();
    } else {
        echo json_encode([
            "success" => false,
            "message" => "ไม่ได้ระบุ ID ของสินค้าที่จะลบ"
        ]);
    }
} else {
    echo json_encode([
        "success" => false,
        "message" => "ต้องใช้ HTTP POST เท่านั้น"
    ]);
}
?>
