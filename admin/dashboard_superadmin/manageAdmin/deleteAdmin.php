<?php
session_start();
require_once(__DIR__ . '/../../../config/db.php');
header('Content-Type: application/json');

// ตรวจสอบ session (เฉพาะผู้ดูแลระบบเท่านั้นถึงลบได้)
if (!isset($_SESSION['gmail'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'คุณไม่ได้รับอนุญาตให้ดำเนินการ']);
    exit;
}

$currentGmail = $_SESSION['gmail']; // คนที่ล็อกอินอยู่
$targetGmail = $_POST['gmail'] ?? '';

// ตรวจสอบว่า parameter ถูกส่งมา
if (empty($targetGmail)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'ไม่พบ Gmail ที่จะลบ']);
    exit;
}

// ป้องกันไม่ให้ลบตัวเอง
if ($currentGmail === $targetGmail) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'ไม่สามารถลบบัญชีของตัวเองได้']);
    exit;
}

// ตรวจสอบว่า Gmail นี้มีอยู่จริงในระบบ
$checkStmt = $conn->prepare("SELECT gmail FROM users WHERE gmail = ?");
$checkStmt->bind_param("s", $targetGmail);
$checkStmt->execute();
$checkResult = $checkStmt->get_result();

if ($checkResult->num_rows === 0) {
    http_response_code(404);
    echo json_encode(['success' => false, 'message' => 'ไม่พบผู้ดูแลระบบที่ต้องการลบ']);
    $checkStmt->close();
    exit;
}
$checkStmt->close();

// ลบผู้ดูแลระบบ
$deleteStmt = $conn->prepare("DELETE FROM users WHERE gmail = ?");
$deleteStmt->bind_param("s", $targetGmail);

if ($deleteStmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'ลบผู้ดูแลระบบสำเร็จ']);
} else {
    http_response_code(500);
    error_log("ลบผู้ดูแลระบบล้มเหลว: " . $deleteStmt->error);
    echo json_encode(['success' => false, 'message' => 'เกิดข้อผิดพลาดในการลบ']);
}

$deleteStmt->close();
$conn->close();
// จบการทำงาน
?>
