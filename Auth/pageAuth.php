<?php
require_once('authSales.php');
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Management Page</title>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Thai:wght@100..900&family=Noto+Sans:ital,wght@0,100..900&display=swap" rel="stylesheet">
    <link rel="icon" type="image/svg+xml" href="/sci-next/img/sci-next.svg">
    <link rel="stylesheet" href="/sci-next/Auth/pageAuth.css?v=1">
</head>
<body>
    <div class="container">
        <h2>คุณต้องการเข้าสู่ระบบจัดการใด?</h2>
        <a href="/sci-next/admin/<?php echo ($role === 'superadmin') ? 'dashboard_superadmin/superadminDashboard.php' : 'dashboard_admin/adminDashboard.php'; ?>" class="btn dashboard-btn">ไปยัง Dashboard</a>
        <a href="/sci-next/sales-page/<?php echo ($role === 'superadmin') ? 'sales-pageSuperadmin.php' : 'sales-pageAdmin.php'; ?>" class="btn sale-btn">ไปยังหน้าขายสินค้า</a>
    </div>
</body>
</html>