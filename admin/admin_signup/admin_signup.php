<?php
session_start();
require_once '../../db.php';  // ใช้เส้นทางที่ถูกต้องตามที่ไฟล์จริงอยู่

// ตรวจสอบว่าเป็นการร้องขอ POST หรือไม่
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // รับค่าจากฟอร์ม
    $user = $_POST['username'];
    $pass = $_POST['password'];
    $confirmPass = $_POST['confirmPassword'];  // เพิ่มการรับค่าของ confirmPassword

    // ตรวจสอบว่ารหัสผ่านตรงกันหรือไม่
    if ($pass !== $confirmPass) {
        echo "รหัสผ่านและยืนยันรหัสผ่านไม่ตรงกัน";
        exit;
    } else {
        // ตรวจสอบว่าผู้ใช้ที่กำลังสมัครมีอยู่ในระบบหรือไม่
        $check_sql = "SELECT * FROM admins WHERE username = :username";
        $check_stmt = oci_parse($conn, $check_sql);
        oci_bind_by_name($check_stmt, ":username", $user);
        oci_execute($check_stmt);
        if (oci_fetch_assoc($check_stmt)) {
            // ถ้ามีผู้ใช้ในระบบแล้ว
            echo "ชื่อผู้ใช้นี้มีอยู่แล้วในระบบ";
        } else {
            // แฮชรหัสผ่าน
            $hashed_pass = password_hash($pass, PASSWORD_DEFAULT);

            // คำสั่ง SQL สำหรับเพิ่มผู้ใช้ใหม่
            $sql = "INSERT INTO admins (id, username, password, role) 
                    VALUES (admins_id_seq.NEXTVAL, :username, :password, 'admin')";
            $stmt = oci_parse($conn, $sql);
            oci_bind_by_name($stmt, ":username", $user);
            oci_bind_by_name($stmt, ":password", $hashed_pass);

            // Execute statement
            if (oci_execute($stmt)) {
                echo "สมัครสมาชิกสำเร็จแล้ว!";
            } else {
                echo "เกิดข้อผิดพลาดในการสมัครสมาชิก";
            }

            oci_free_statement($stmt);
        }
        oci_free_statement($check_stmt);
    }
}

oci_close($conn); // ปิดการเชื่อมต่อฐานข้อมูล
?>