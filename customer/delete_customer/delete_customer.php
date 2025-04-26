<?php
require '../../config/db.php'; // ไฟล์เชื่อมต่อฐานข้อมูล

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['customer_id'])) {
    $customer_id = intval($_POST['customer_id']);

    // ตรวจสอบว่ามีลูกค้านี้อยู่จริงหรือไม่
    $sql_check = "SELECT * FROM users WHERE ID_NUMBER = :customer_id";
    $stmt_check = oci_parse($conn, $sql_check);
    oci_bind_by_name($stmt_check, ":customer_id", $customer_id);
    oci_execute($stmt_check);

    if (oci_fetch($stmt_check)) {
        // ลบลูกค้า
        $sql_delete = "DELETE FROM users WHERE ID_NUMBER = :customer_id";
        $stmt_delete = oci_parse($conn, $sql_delete);
        oci_bind_by_name($stmt_delete, ":customer_id", $customer_id);

        if (oci_execute($stmt_delete)) {
            echo "<script>alert('ลบลูกค้าเรียบร้อยแล้ว!'); window.location.href='../../dashboard_superadmin/dashboard_superadmin.php';</script>";
        } else {
            echo "<script>alert('เกิดข้อผิดพลาดในการลบลูกค้า!'); window.history.back();</script>";
        }

        oci_free_statement($stmt_delete);
    } else {
        echo "<script>alert('ไม่พบลูกค้าที่ต้องการลบ!'); window.history.back();</script>";
    }

    oci_free_statement($stmt_check);
    oci_close($conn); // ปิดการเชื่อมต่อ
} else {
    header("Location: ../../dashboard_superadmin/dashboard_superadmin.php");
    exit();
}
?>