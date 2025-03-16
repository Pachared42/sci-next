<?php
session_start();

if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
    header("Location: /melgeeks_admin/index.php");
    exit();
}

require __DIR__ . '/../db.php';

$username = $_SESSION['user'];
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แดชบอร์ด Superadmin</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .tabs { display: flex; background: #333; padding: 10px; }
        .tab { padding: 10px 20px; color: white; cursor: pointer; }
        .tab:hover, .active { background: #555; }
        .content { display: none; padding: 20px; }
        .show { display: block; }
    </style>
</head>
<body>
    <h2>ยินดีต้อนรับ, <?php echo htmlspecialchars($username); ?>!</h2>
    
    <div class="tabs">
        <div class="tab active" onclick="showTab('graph')">กราฟ</div>
        <div class="tab" onclick="showTab('keyboards')">Keyboards</div>
        <div class="tab" onclick="showTab('switches')">Switches</div>
        <div class="tab" onclick="showTab('keycaps')">Keycaps</div>
        <div class="tab" onclick="showTab('accessory')">Accessory</div>
        <div class="tab" onclick="showTab('customers')">ข้อมูลลูกค้า</div>
        <a href="/melgeeks_admin/logout.php">ออกจากระบบ</a>
    </div>
    
    <div id="graph" class="content show">
        <h3>กราฟ</h3>
        <p>แสดงข้อมูลสถิติต่าง ๆ</p>
    </div>
    <div id="keyboards" class="content">
        <h3>keyboards</h3>
        <p>แสดงรายการสินค้าทั้งหมด</p>
    </div>
    <div id="switches" class="content">
        <h3>switches</h3>
        <p>แสดงรายการสินค้าทั้งหมด</p>
    </div>
    <div id="keycaps" class="content">
        <h3>keycaps</h3>
        <p>แสดงรายการสินค้าทั้งหมด</p>
    </div>
    <div id="accessory" class="content">
        <h3>accessory</h3>
        <p>แสดงรายการสินค้าทั้งหมด</p>
    </div>
    <div id="customers" class="content">
        <h3>ข้อมูลลูกค้า</h3>
        <p>แสดงรายชื่อลูกค้า</p>
    </div>
    
    <script>
        function showTab(tabId) {
            document.querySelectorAll('.content').forEach(c => c.classList.remove('show'));
            document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
            document.getElementById(tabId).classList.add('show');
            event.target.classList.add('active');
        }
    </script>
</body>
</html>
