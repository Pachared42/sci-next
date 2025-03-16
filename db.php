<?php
// กำหนดค่าการเชื่อมต่อ MySQL (phpMyAdmin)
$host = 'localhost';
$username = 'root'; // เปลี่ยนเป็นชื่อผู้ใช้จริงหากไม่ใช่ root
$password = ''; // ถ้ามีรหัสผ่านให้ใส่ที่นี่
$database = 'sci_shop';

// เชื่อมต่อกับฐานข้อมูล MySQL
try {
    $conn = new mysqli($host, $username, $password, $database);

    // ตรวจสอบการเชื่อมต่อ
    if ($conn->connect_error) {
        throw new Exception("เชื่อมต่อฐานข้อมูลล้มเหลว: " . $conn->connect_error);
    }
} catch (Exception $e) {
    echo $e->getMessage();
    exit;
}
?>