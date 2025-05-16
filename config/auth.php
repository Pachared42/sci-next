<?php
session_start();

require 'config/db.php';

if ($conn->connect_error) {
    die("เชื่อมต่อฐานข้อมูลล้มเหลว: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';

    if (empty($username) || empty($password)) {
        $_SESSION['error'] = "กรุณากรอกข้อมูลให้ครบถ้วน";
        header("Location: index.php");
        exit();
    } else {
        $sql = "SELECT * FROM admins WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();

        if ($row && password_verify($password, $row['PASSWORD'])) {
            session_regenerate_id(true);
            $_SESSION['admin_id'] = $row['ID'];
            $_SESSION['user'] = $row['USERNAME'];
            $_SESSION['role'] = $row['ROLE'];

            if ($row['ROLE'] == 'superadmin') {
                header("Location: /sci-next/admin/dashboard_superadmin/superadminDashboard.php");
                exit();
            } else {
                header("Location: /sci-next/admin/dashboard_admin/adminDashboard.php");
                exit();
            }
        } else {
            $_SESSION['error'] = "ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง";
            header("Location: index.php");
            exit();
        }
    }
}

$conn->close();
?>