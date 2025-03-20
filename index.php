<?php
session_start();

// เชื่อมต่อฐานข้อมูล MySQL
require 'db.php';

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("เชื่อมต่อฐานข้อมูลล้มเหลว: " . $conn->connect_error);
}

// ตรวจสอบว่าเป็นการร้องขอ POST หรือไม่
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';

    // ตรวจสอบว่ามีการกรอกข้อมูลหรือไม่
    if (empty($username) || empty($password)) {
        $_SESSION['error'] = "กรุณากรอกข้อมูลให้ครบถ้วน";
        header("Location: login.php");
        exit();
    } else {
        // ใช้ Prepared Statement เพื่อป้องกัน SQL Injection
        $sql = "SELECT * FROM admins WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();

        // ตรวจสอบว่ามีข้อมูลผู้ใช้ในฐานข้อมูลหรือไม่
        if ($row && password_verify($password, $row['PASSWORD'])) {
            $_SESSION['admin_id'] = $row['ID'];
            $_SESSION['user'] = $row['USERNAME'];
            $_SESSION['role'] = $row['ROLE'];

            ob_start();

            // เช็คบทบาทของผู้ใช้
            if ($row['ROLE'] == 'superadmin') {
                header("Location: admin/dashboard_superadmin/dashboard_superadmin.php");
            } else {
                header("Location: admin/dashboard_admin/dashboard_admin.php");
            }
            exit();
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
    <title>เข้าสู่ระบบ ADMIN</title>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Thai:wght@100..900&family=Noto+Sans:ital,wght@0,100..900&display=swap" rel="stylesheet">
    <style>
        body {
            background-image: url(img.content/sci_desktop.jpg);
            background-size: cover;
            display: flex;
            color: #333;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        * {
            font-family: 'Noto Sans Thai', 'Noto Sans', sans-serif;
            box-sizing: border-box;
        }

        .container {
            background-color: #ffffff;
            border-radius: 10px;
            padding: 30px;
            max-width: 400px;
            width: 100%;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: black;
            margin: 0 0 20px 0;
        }

        .error {
            color: red;
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px;
        }

        .input-group {
            position: relative;
            margin: 20px 0;
        }

        input {
            width: 100%;
            padding: 12px;
            border: 1px solid rgb(30, 30, 30);
            border-radius: 50px;
            font-size: 20px;
            padding-left: 20px;
            transition: all 0.3s ease-in-out;
        }

        input:focus {
            outline: 2px solid rgb(30, 30, 30);
        }

        label {
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 18px;
            color: #888;
            transition: 0.3s;
            pointer-events: none;
            transition: all 0.3s ease-in-out;
        }

        input:focus + label,
        input:not(:placeholder-shown) + label {
            top: -1px;
            left: 20px;
            font-size: 14px;
            color: black;
            background: white;
            padding: 0 5px;
        }

        button {
            background: black;
            color: white;
            border: none;
            padding: 15px;
            border-radius: 50px;
            cursor: pointer;
            font-size: 20px;
            width: 100%;
            transition: background 0.3s;
            margin-top: 10px;
            font-weight: bold;
        }

        button:hover {
            background: #333;
        }
    </style>
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
                <input type="text" name="username" id="username" placeholder=" " required>
                <label for="username">ชื่อผู้ใช้</label>
            </div>

            <div class="input-group">
                <input type="password" name="password" id="password" placeholder=" " required>
                <label for="password">รหัสผ่าน</label>
            </div>

            <button type="submit">เข้าสู่ระบบ</button>
        </form>
    </div>
</body>
</html>