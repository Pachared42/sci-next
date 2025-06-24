<?php
session_start();
require_once(__DIR__ . "/../../../config/db.php");

// ตรวจสอบสิทธิ์
if (!isset($_SESSION['gmail'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// ตรวจสอบว่ามีพารามิเตอร์ gmail มั้ย
if (!isset($_GET['gmail']) || empty($_GET['gmail'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing gmail parameter']);
    exit;
}

$gmail = $_GET['gmail'];

// เตรียม statement
$stmt = $conn->prepare("SELECT id, gmail, first_name, last_name, profile_image FROM users WHERE gmail = ?");
$stmt->bind_param("s", $gmail);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    http_response_code(404);
    echo json_encode(['error' => 'User not found']);
    exit;
}

$user = $result->fetch_assoc();

// แปลงรูปภาพ BLOB เป็น base64 พร้อม mime type (สมมติ jpeg)
$profileImage = null;
if (!empty($user['profile_image'])) {
    $base64Image = base64_encode($user['profile_image']);
    $profileImage = "data:image/jpeg;base64," . $base64Image;
}

$response = [
    'id' => $user['id'],
    'gmail' => $user['gmail'],
    'first_name' => $user['first_name'],
    'last_name' => $user['last_name'],
    'profile_image' => $profileImage
];

header('Content-Type: application/json');
echo json_encode($response);
