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
    // ใช้ชื่อคีย์ตามที่ส่งจากฟอร์ม
    $user_id = isset($_POST['user_id']) ? (int) $_POST['user_id'] : 0;
    $new_password = isset($_POST['new_password']) ? trim($_POST['new_password']) : '';

    // ตรวจสอบข้อมูลที่กรอก
    if ($user_id === 0 || empty($new_password)) {
        die("กรุณากรอกข้อมูลให้ครบถ้วน");
    }

    // ตรวจสอบว่า ID มีอยู่จริงในฐานข้อมูล
    $check_sql = "SELECT * FROM admins WHERE id = :user_id";
    $check_stmt = oci_parse($conn, $check_sql);
    oci_bind_by_name($check_stmt, ":user_id", $user_id);
    oci_execute($check_stmt);

    // ถ้าไม่พบ ID ที่กรอกให้แสดงข้อผิดพลาด
    if (!oci_fetch($check_stmt)) {
        die("ไม่พบข้อมูลผู้ใช้ที่มี ID: " . htmlspecialchars($user_id));
    }
    oci_free_statement($check_stmt);

    // แฮชรหัสผ่านใหม่เพื่อความปลอดภัย
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    // SQL สำหรับการอัปเดตข้อมูล
    $sql = "UPDATE admins SET password = :password WHERE id = :user_id";
    $stmt = oci_parse($conn, $sql);

    if (!$stmt) {
        $e = oci_error($conn);
        die("เกิดข้อผิดพลาด SQL: " . $e['message']);
    }

    // ผูกตัวแปรกับ SQL
    oci_bind_by_name($stmt, ":password", $hashed_password);
    oci_bind_by_name($stmt, ":user_id", $user_id);

    // ทำการอัปเดตข้อมูล
    if (oci_execute($stmt)) {
        // เก็บข้อความแจ้งเตือนใน session
        $_SESSION['message'] = "อัปเดตข้อมูลสำเร็จ!";

        // รีเฟรชหน้าโดยไม่ให้เกิดลูป
        header("Location: ../../dashboard_superadmin/dashboard_superadmin.php");
        exit();
    } else {
        $e = oci_error($stmt);
        echo "เกิดข้อผิดพลาด: " . $e['message'];
    }

    oci_free_statement($stmt);
}

oci_close($conn); // ปิดการเชื่อมต่อฐานข้อมูล
?>

<!-- ในไฟล์ dashboard_superadmin.php -->
<?php
session_start();
if (isset($_SESSION['message'])) {
    echo "<script>alert('" . $_SESSION['message'] . "');</script>";
    unset($_SESSION['message']); // เคลียร์ข้อความหลังจากแสดง
}
?>

<!-- เนื้อหาของ dashboard_superadmin.php ที่เหลือ -->
