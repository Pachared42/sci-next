<?php
session_start();
require_once __DIR__ . '/../config/db.php';

if ($conn->connect_error) {
    die("เชื่อมต่อฐานข้อมูลล้มเหลว: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $gmail = isset($_POST['gmail']) ? trim($_POST['gmail']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';

    if (empty($gmail) || empty($password)) {
        $_SESSION['error'] = "กรุณากรอกข้อมูลให้ครบถ้วน";
        header("Location: index.php");
        exit();
    } else {

        $sql = "
            SELECT users.*, roles.name AS role_name 
            FROM users 
            LEFT JOIN roles ON users.role_id = roles.id 
            WHERE users.gmail = ?
        ";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $gmail);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();

        if ($user && password_verify($password, $user['password'])) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['gmail'] = $user['gmail'];
            $_SESSION['role'] = $user['role_name'];
            $_SESSION['full_name'] = $user['first_name'] . ' ' . $user['last_name'];

            switch ($user['role_name']) {
                case 'superadmin':
                    header("Location: /sci-next/Auth/pageAuth.php");
                    break;
                case 'admin':
                    header("Location: /sci-next/Auth/pageAuth.php");
                    break;
                case 'employee':
                    header("Location: /sci-next/Auth/pageAuth.php");
                    break;
                default:
                    header("Location: /sci-next/index.php");
                    break;
            }
            exit();
        } else {
            $_SESSION['error'] = "อีเมลหรือรหัสผ่านไม่ถูกต้อง";
            header("Location: index.php");
            exit();
        }
    }
}

$conn->close();
?>
