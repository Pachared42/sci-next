<?php
require_once __DIR__ . '/config/auth.php';
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LOGIN ADMIN</title>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Thai:wght@100..900&family=Noto+Sans:ital,wght@0,100..900&display=swap" rel="stylesheet">

    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <link rel="stylesheet" href="Login-Register/Login.css">

    <link rel="manifest" href="/sci-next/manifest.json">
    <meta name="theme-color" content="#31393C">
    <link rel="apple-touch-icon" href="/sci-next/img/next.256.png">
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
    <script>
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/sci-next/serviceWorker.js')
                .then(reg => console.log('✅ Service Worker registered:', reg))
                .catch(err => console.error('❌ Service Worker registration failed:', err));
        }
    </script>
</body>

</html>