<?php
// ปิดการแสดง error หน้าเว็บ และบันทึกลง log
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/../../../php-error.log');
error_reporting(E_ALL);

session_start();
require_once(__DIR__ . '/../../../config/db.php');

header('Content-Type: application/json');

// ตรวจสอบ session
if (!isset($_SESSION['gmail'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

// รับค่าจาก form
$id         = $_POST['id'] ?? '';
$gmail      = $_POST['gmail'] ?? '';
$password   = $_POST['password'] ?? '';
$first_name = $_POST['first_name'] ?? '';
$last_name  = $_POST['last_name'] ?? '';

// ตรวจสอบความถูกต้องของข้อมูล
if (empty($id) || empty($gmail) || empty($first_name) || empty($last_name)) {
    echo json_encode(['success' => false, 'message' => 'กรุณากรอกข้อมูลให้ครบถ้วน']);
    exit;
}

// 🔍 ตรวจสอบว่า Gmail ซ้ำ (แต่ไม่ใช่ของตัวเอง)
$checkStmt = $conn->prepare("SELECT id FROM users WHERE gmail = ? AND id != ?");
$checkStmt->bind_param("si", $gmail, $id);
$checkStmt->execute();
$checkResult = $checkStmt->get_result();

if ($checkResult->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'มี Gmail นี้อยู่ในระบบแล้ว']);
    $checkStmt->close();
    $conn->close();
    exit;
}
$checkStmt->close();

// เตรียมคำสั่ง SQL และ parameter
$updateFields = "gmail = ?, first_name = ?, last_name = ?";
$params = [$gmail, $first_name, $last_name];

if (!empty($password)) {
    $updateFields .= ", password = ?";
    $params[] = password_hash($password, PASSWORD_DEFAULT);
}

$params[] = $id;

$sql = "UPDATE users SET $updateFields WHERE id = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Prepare failed: ' . $conn->error]);
    exit;
}

// ประเมิน type string ของ bind_param (s = string, i = integer)
$types = str_repeat('s', count($params) - 1) . 'i';
$stmt->bind_param($types, ...$params);

if (!$stmt->execute()) {
    echo json_encode(['success' => false, 'message' => 'Execute failed: ' . $stmt->error]);
    exit;
}

// สำเร็จ
echo json_encode(['success' => true]);

$stmt->close();
$conn->close();
?>