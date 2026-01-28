<?php
session_start();
require_once __DIR__ . '/../config/db.php';

if (isset($_SESSION['user_id'])) {
    return;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    return;
}

$gmail = trim($_POST['gmail'] ?? '');
$password = trim($_POST['password'] ?? '');

if ($gmail === '' || $password === '') {
    $_SESSION['error'] = "กรุณากรอกข้อมูลให้ครบถ้วน";
    header("Location: /sci-next/index.php");
    exit;
}

$sql = "
    SELECT users.*, roles.name AS role_name
    FROM users
    LEFT JOIN roles ON users.role_id = roles.id
    WHERE users.gmail = ?
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $gmail);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$user || !password_verify($password, $user['password'])) {
    $_SESSION['error'] = "อีเมลหรือรหัสผ่านไม่ถูกต้อง";
    header("Location: /sci-next/index.php");
    exit;
}

session_regenerate_id(true);

$_SESSION['user_id']   = $user['id'];
$_SESSION['gmail']     = $user['gmail'];
$_SESSION['role']      = $user['role_name'];
$_SESSION['full_name'] = $user['first_name'] . ' ' . $user['last_name'];

header("Location: /sci-next/Auth/pageAuth.php");
exit;