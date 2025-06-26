<?php
session_start();
require_once(__DIR__ . "/../../../config/db.php");

// ตรวจสอบสิทธิ์
if (!isset($_SESSION['gmail'])) {
    http_response_code(401);
    exit('ไม่ได้รับอนุญาต');
}

$gmailSession = $_SESSION['gmail'];

// ฟังก์ชันตรวจสอบอีเมล
function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// ฟังก์ชันตรวจสอบรูปภาพ
function isValidImage($file) {
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    $maxSize = 2 * 1024 * 1024; // 2MB

    if ($file['error'] !== UPLOAD_ERR_OK) return false;
    if (!in_array(mime_content_type($file['tmp_name']), $allowedTypes)) return false;
    if ($file['size'] > $maxSize) return false;

    return true;
}

// รับค่าจากฟอร์ม (trim เพื่อลบช่องว่างส่วนเกิน)
$gmail      = trim($_POST['gmail']);
$first_name = trim($_POST['first_name']);
$last_name  = trim($_POST['last_name']);
$password   = $_POST['password'] ?? '';

// ตรวจสอบอีเมล
if (!isValidEmail($gmail)) {
    http_response_code(400);
    exit('รูปแบบอีเมลไม่ถูกต้อง');
}

// ตรวจสอบความยาวรหัสผ่าน ถ้ามีการเปลี่ยน
if (!empty($password) && strlen($password) < 8) {
    http_response_code(400);
    exit('รหัสผ่านต้องมีความยาวอย่างน้อย 8 ตัวอักษร');
}

// ตรวจสอบและอัปโหลดรูปภาพ
$imageData = null;
if (!empty($_FILES['profile_image']['name'])) {
    if (!isValidImage($_FILES['profile_image'])) {
        http_response_code(400);
        exit('ไฟล์รูปภาพไม่ถูกต้อง');
    }
    $imageData = file_get_contents($_FILES['profile_image']['tmp_name']);
} else {
    // ดึงรูปเดิมจาก DB
    $stmt = $conn->prepare("SELECT profile_image FROM users WHERE gmail = ?");
    $stmt->bind_param("s", $gmailSession);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 0) {
        http_response_code(404);
        exit('ไม่พบผู้ใช้ในระบบ');
    }
    $current = $result->fetch_assoc();
    $imageData = $current['profile_image'];
}

// เริ่มการอัปเดตข้อมูล
if (!empty($password)) {
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("
        UPDATE users 
        SET gmail=?, password=?, first_name=?, last_name=?, profile_image=?
        WHERE gmail=?
    ");
    $stmt->bind_param("ssssss", $gmail, $hashedPassword, $first_name, $last_name, $imageData, $gmailSession);
} else {
    $stmt = $conn->prepare("
        UPDATE users 
        SET gmail=?, first_name=?, last_name=?, profile_image=?
        WHERE gmail=?
    ");
    $stmt->bind_param("sssss", $gmail, $first_name, $last_name, $imageData, $gmailSession);
}

if ($stmt->execute()) {
    $_SESSION['gmail'] = $gmail;
    echo 'อัปเดตข้อมูลสำเร็จ';
} else {
    // บันทึก error ลง log แยก และแจ้งข้อความทั่วไปกับผู้ใช้
    error_log('Database update error: ' . $stmt->error);
    http_response_code(500);
    echo 'เกิดข้อผิดพลาดภายในระบบ กรุณาลองใหม่ภายหลัง';
}
?>
