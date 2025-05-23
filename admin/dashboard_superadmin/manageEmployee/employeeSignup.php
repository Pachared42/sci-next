<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once('../../../config/db.php');

ob_start();

if (headers_sent()) {
    error_log("Headers already sent before this point");
}

header('Content-Type: application/json; charset=UTF-8');

// ตรวจสอบ method POST เท่านั้น
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'status' => 'error',
        'message' => 'Method not allowed'
    ]);
    exit;
}

// ตรวจสอบข้อมูลที่ต้องการครบหรือไม่
$requiredFields = ['gmail', 'password', 'confirmPassword', 'firstName', 'lastName', 'profileImage'];
foreach ($requiredFields as $field) {
    if (!isset($_POST[$field])) {
        ob_end_clean();
        echo json_encode(["status" => "error", "message" => "ข้อมูลไม่ครบถ้วน: {$field}"], JSON_UNESCAPED_UNICODE);
        exit;
    }
}

// ดึงข้อมูลจาก POST และ trim
$gmailRaw = trim($_POST['gmail']);
$password = $_POST['password'];
$confirmPassword = $_POST['confirmPassword'];
$firstNameRaw = trim($_POST['firstName']);
$lastNameRaw = trim($_POST['lastName']);
$profileImageBase64 = $_POST['profileImage'];
$roleId = 3; // กำหนด role_id เป็น 2 ตามที่ต้องการ

// Validate email format
if (!filter_var($gmailRaw, FILTER_VALIDATE_EMAIL)) {
    ob_end_clean();
    echo json_encode(["status" => "error", "message" => "อีเมลไม่ถูกต้อง"], JSON_UNESCAPED_UNICODE);
    exit;
}

// sanitize ชื่อ (กรณีจะแสดงผลบน HTML ต้อง escape เพิ่มทีหลังอีกที)
$gmail = $gmailRaw;
$firstName = htmlspecialchars($firstNameRaw, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
$lastName = htmlspecialchars($lastNameRaw, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');

// ตรวจสอบรหัสผ่านและยืนยันรหัสผ่าน
if ($password !== $confirmPassword) {
    ob_end_clean();
    echo json_encode(["status" => "error", "message" => "รหัสผ่านและยืนยันรหัสผ่านไม่ตรงกัน"], JSON_UNESCAPED_UNICODE);
    exit;
}

// ตรวจสอบ gmail ซ้ำในฐานข้อมูล
$check_sql = "SELECT id FROM users WHERE gmail = ?";
$check_stmt = $conn->prepare($check_sql);
if (!$check_stmt) {
    ob_end_clean();
    echo json_encode(["status" => "error", "message" => "Prepare statement ล้มเหลว: " . $conn->error], JSON_UNESCAPED_UNICODE);
    exit;
}
$check_stmt->bind_param("s", $gmail);
$check_stmt->execute();
$check_stmt->store_result();

if ($check_stmt->num_rows > 0) {
    $check_stmt->close();
    ob_end_clean();
    echo json_encode(["status" => "error", "message" => "อีเมลนี้ถูกใช้งานแล้ว"], JSON_UNESCAPED_UNICODE);
    exit;
}
$check_stmt->close();

// กำหนดขนาดไฟล์รูปสูงสุด 5MB
$maxImageSizeBytes = 1 * 1024 * 1024; // 1MB

$imageData = null;
if (!empty($profileImageBase64)) {
    // ตรวจสอบรูปแบบ base64 ว่าถูกต้อง
    if (preg_match('/^data:image\/(jpeg|png|webp);base64,/', $profileImageBase64)) {
        $base64Data = explode(',', $profileImageBase64)[1];
        
        // เช็คขนาด base64 ก่อน decode
        if (strlen($base64Data) > $maxImageSizeBytes) {
            ob_end_clean();
            echo json_encode(["status" => "error", "message" => "ไฟล์รูปภาพใหญ่เกินไป (เกิน 1MB)"], JSON_UNESCAPED_UNICODE);
            exit;
        }

        $imageData = base64_decode($base64Data);

        if ($imageData === false) {
            ob_end_clean();
            echo json_encode(["status" => "error", "message" => "แปลงข้อมูลภาพล้มเหลว"], JSON_UNESCAPED_UNICODE);
            exit;
        }

        // ตรวจสอบ mime type จริงของภาพ
        $finfo = finfo_open();
        $mimeType = finfo_buffer($finfo, $imageData, FILEINFO_MIME_TYPE);
        finfo_close($finfo);

        $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
        if (!in_array($mimeType, $allowedTypes)) {
            ob_end_clean();
            echo json_encode(["status" => "error", "message" => "ประเภทไฟล์รูปภาพไม่ถูกต้อง"], JSON_UNESCAPED_UNICODE);
            exit;
        }
    } else {
        ob_end_clean();
        echo json_encode(["status" => "error", "message" => "รูปภาพไม่ถูกต้องหรือรูปแบบไม่รองรับ"], JSON_UNESCAPED_UNICODE);
        exit;
    }
}

// แฮชรหัสผ่าน
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// เตรียม statement insert โดยแยกกรณีมีรูปภาพและไม่มีรูปภาพ
if ($imageData !== null) {
    // มีรูปภาพ
    $insert_sql = "INSERT INTO users (gmail, password, first_name, last_name, role_id, profile_image) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insert_sql);
    if (!$stmt) {
        ob_end_clean();
        echo json_encode(["status" => "error", "message" => "Prepare statement ล้มเหลว: " . $conn->error], JSON_UNESCAPED_UNICODE);
        exit;
    }
    // bind_param: s=string, i=int, b=blob
    $null = null;
    $stmt->bind_param("ssssib", $gmail, $hashedPassword, $firstName, $lastName, $roleId, $null);
    $stmt->send_long_data(5, $imageData);

} else {
    // ไม่มีรูปภาพ (ส่ง NULL)
    $insert_sql = "INSERT INTO users (gmail, password, first_name, last_name, role_id, profile_image) VALUES (?, ?, ?, ?, ?, NULL)";
    $stmt = $conn->prepare($insert_sql);
    if (!$stmt) {
        ob_end_clean();
        echo json_encode(["status" => "error", "message" => "Prepare statement ล้มเหลว: " . $conn->error], JSON_UNESCAPED_UNICODE);
        exit;
    }
    $stmt->bind_param("ssssi", $gmail, $hashedPassword, $firstName, $lastName, $roleId);
}

// Execute และตอบกลับ
if ($stmt->execute()) {
    ob_end_clean();
    echo json_encode(["status" => "success", "message" => "สมัครสมาชิกสำเร็จ!"], JSON_UNESCAPED_UNICODE);
} else {
    ob_end_clean();
    echo json_encode(["status" => "error", "message" => "เกิดข้อผิดพลาดในการบันทึกข้อมูล: " . $stmt->error], JSON_UNESCAPED_UNICODE);
}

$stmt->close();
$conn->close();
exit;
