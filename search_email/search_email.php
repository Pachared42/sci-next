<?php
// เชื่อมต่อกับฐานข้อมูล Oracle
require __DIR__ . '/../config/db.php';  // path นี้ถูกต้องเมื่อไฟล์ db.php อยู่ในโฟลเดอร์หลัก

if (!$conn) {
    $e = oci_error();
    echo "เกิดข้อผิดพลาดในการเชื่อมต่อฐานข้อมูล: " . $e['message'];
    exit;
}

// ตรวจสอบหากมีการค้นหาผ่าน GET
if (isset($_GET['email_search'])) {
    $email_search = "%" . $_GET['email_search'] . "%"; // เพิ่ม '%' ให้กับการค้นหา
    
    // เขียน SQL ที่จะใช้ค้นหา
    $sql = "SELECT ID_NUMBER, EMAIL, PASSWORD, FIRST_NAME, LAST_NAME, ADDRESS, PHONE, MEMBERSHIP_LEVEL, TOTAL_SPENT 
            FROM users WHERE EMAIL LIKE :email_search";
    
    $stmt = oci_parse($conn, $sql);
    oci_bind_by_name($stmt, ":email_search", $email_search); // ค้นหาจากอีเมลที่กรอก
    oci_execute($stmt);
    
    // ดึงข้อมูลลูกค้า
    $customers = [];
    while ($row = oci_fetch_assoc($stmt)) {
        $customers[] = $row;
    }

    // แสดงข้อมูลที่ดึงมาเพื่อดีบัก
    echo '<pre>';
    print_r($customers);
    echo '</pre>';
}
?>