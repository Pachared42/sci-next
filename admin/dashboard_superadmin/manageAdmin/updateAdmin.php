<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once(__DIR__ . '/../../../config/db.php');

header('Content-Type: application/json');

if (!isset($_SESSION['gmail'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$id         = $_POST['id'] ?? '';
$gmail      = $_POST['gmail'] ?? '';
$password   = $_POST['password'] ?? '';
$first_name = $_POST['first_name'] ?? '';
$last_name  = $_POST['last_name'] ?? '';

if (empty($id) || empty($gmail) || empty($first_name) || empty($last_name)) {
    echo json_encode(['success' => false, 'message' => 'กรุณากรอกข้อมูลให้ครบถ้วน']);
    exit;
}

$updateFields = "gmail = ?, first_name = ?, last_name = ?";
$params = [$gmail, $first_name, $last_name];

if (!empty($password)) {
    $updateFields .= ", password = ?";
    $params[] = password_hash($password, PASSWORD_DEFAULT);
}

$params[] = $id;

$sql = "UPDATE users SET $updateFields WHERE id_number = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Prepare failed: ' . $conn->error]);
    exit;
}

if (!$stmt->execute($params)) {
    echo json_encode(['success' => false, 'message' => 'Execute failed: ' . $stmt->error]);
    exit;
}

echo json_encode(['success' => true]);

$stmt->close();
$conn->close();
// 