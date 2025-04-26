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
        header("Location: login.php");
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

            ob_start();

            if ($row['ROLE'] == 'superadmin') {
                header("Location: video_logo_sci_next.php?t=" . time());
                exit();
            } else {
                header("Location: admin/dashboard_admin/dashboard_admin.php");
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

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LOGIN ADMIN</title>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Thai:wght@100..900&family=Noto+Sans:ital,wght@0,100..900&display=swap" rel="stylesheet">

    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    
    <link rel="stylesheet" href="login/login_admin.css">
</head>

<body>
    <div class="container">
        <h2>ยินดีต้อนรับสู่ระบบ ADMIN</h2>

        <?php if (isset($_SESSION['error'])) { ?>
            <p class="error"><?php echo $_SESSION['error']; ?></p>
            <?php unset($_SESSION['error']); ?>
        <?php } ?>

        <form method="POST">
            <div class="input-group">
                <span class="material-icons input-icon">person</span>
                <input type="text" name="username" id="username" placeholder=" " required>
                <label for="username">ชื่อผู้ใช้</label>
            </div>

            <div class="input-group">
                <span class="material-icons input-icon">lock</span>
                <input type="password" name="password" id="password" placeholder=" " required>
                <label for="password">รหัสผ่าน</label>
            </div>

            <button type="submit">
                <span class="material-icons">login</span>
                เข้าสู่ระบบ
            </button>
        </form>
    </div>
</body>

</html>