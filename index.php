<?php
session_start();

require 'db.php';

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
    <style>
        body {
            background-image: url(img/Login_SCi_NEXT.webp);
            background-size: cover;
            display: flex;
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
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.5);
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
        }

        .input-group {
            position: relative;
            margin: 20px 0;
        }

        .input-group .input-icon {
            position: absolute;
            top: 50%;
            left: 15px;
            transform: translateY(-50%);
            color: #000000;
            font-size: 24px;
            pointer-events: none;
        }

        .input-group input {
            padding-left: 45px;
        }


        input {
            width: 100%;
            padding: 12px;
            border: 1px solid rgb(30, 30, 30);
            border-radius: 10px;
            font-size: 20px;
            padding-left: 20px;
            transition: all 0.3s ease-in-out;
        }

        input:focus {
            border: 1px solid #ffffff;
        }

        label {
            position: absolute;
            left: 45px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 18px;
            color: #888888;
            transition: 0.2s;
            pointer-events: none;
            transition: all 0.2s ease-in-out;
        }

        input:focus+label,
        input:not(:placeholder-shown)+label {
            top: 0px;
            left: 45px;
            font-size: 14px;
            color: #000000;
            background: #ffffff;
            padding: 0 5px;
        }

        button {
            background: #000000;
            color: white;
            border: none;
            padding: 15px;
            border-radius: 10px;
            cursor: pointer;
            font-size: 20px;
            width: 100%;
            transition: background 0.3s;
            margin-top: 10px;
            font-weight: bold;
        }

        button:hover {
            background: #333333;
        }

        button span.material-icons {
            vertical-align: middle;
            margin-right: 5px;
            font-size: 24px;
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