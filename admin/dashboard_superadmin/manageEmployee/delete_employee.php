<?php
session_start(); // เริ่มต้น session

require_once '../../config/db.php'; // เชื่อมต่อฐานข้อมูล

if (!$conn) {
    $e = oci_error();
    die("การเชื่อมต่อฐานข้อมูลล้มเหลว: " . $e['message']);
}

// ตรวจสอบว่า admin ได้เข้าสู่ระบบหรือไม่
if (!isset($_SESSION['admin_id'])) {
    die("กรุณาเข้าสู่ระบบก่อน");
}

// ตรวจสอบว่า user ที่เข้าสู่ระบบเป็น superadmin หรือไม่
if ($_SESSION['role'] !== 'superadmin') {
    die("คุณไม่มีสิทธิ์ในการทำการนี้");
}

// ตรวจสอบ method ที่ใช้
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // รับค่าจากฟอร์ม (method POST)
    $user_id = isset($_POST['id']) ? (int) $_POST['id'] : 0;

    // ตรวจสอบว่าผู้ใช้ที่ต้องการลบมีอยู่จริงในฐานข้อมูล
    if ($user_id === 0) {
        die("ID ไม่ถูกต้อง");
    }

    $check_sql = "SELECT * FROM admins WHERE id = :user_id";
    $check_stmt = oci_parse($conn, $check_sql);
    oci_bind_by_name($check_stmt, ":user_id", $user_id);
    oci_execute($check_stmt);

    if (!oci_fetch($check_stmt)) {
        die("ไม่พบผู้ใช้ที่มี ID: " . htmlspecialchars($user_id));
    }
    oci_free_statement($check_stmt);

    // SQL สำหรับการลบข้อมูล
    $sql = "DELETE FROM admins WHERE id = :user_id";
    $stmt = oci_parse($conn, $sql);

    if (!$stmt) {
        $e = oci_error($conn);
        die("เกิดข้อผิดพลาด SQL: " . $e['message']);
    }

    // ผูกตัวแปรกับ SQL
    oci_bind_by_name($stmt, ":user_id", $user_id);

    // ทำการลบข้อมูล
    if (oci_execute($stmt)) {
        // ถ้าลบสำเร็จ
        echo "ลบข้อมูลสำเร็จ!";
        header("Location: ../../dashboard_superadmin/dashboard_superadmin.php"); // รีไดเร็กต์ไปที่หน้า dashboard
        exit();
    } else {
        $e = oci_error($stmt);
        echo "เกิดข้อผิดพลาด: " . $e['message'];
    }

    oci_free_statement($stmt);
}

oci_close($conn); // ปิดการเชื่อมต่อฐานข้อมูล
?>