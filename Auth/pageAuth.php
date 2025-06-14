<?php
require_once('authSales.php');
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เลือกหน้าจัดการ</title>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Thai&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="pageAuth.scss">
</head>
<body>
    <div class="container">
        <h2>คุณต้องการเข้าสู่ระบบจัดการใด?</h2>
        <a href="/sci-next/admin/<?php echo ($role === 'superadmin') ? 'dashboard_superadmin/superadminDashboard.php' : 'dashboard_admin/adminDashboard.php'; ?>" class="btn dashboard-btn">ไปยัง Dashboard</a>
        <a href="/sci-next/sales-page/<?php echo ($role === 'superadmin') ? 'sales-pageSuperadmin.php' : 'sales-pageAdmin.php'; ?>" class="btn sale-btn">ไปยังหน้าขายสินค้า</a>
    </div>
</body>
</html>