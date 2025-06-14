<?php
// เชื่อมต่อฐานข้อมูล
require_once('../config/db.php');

// ดึงข้อมูลสินค้าจากหลายตาราง
$sql = <<<SQL
SELECT product_name, barcode, price, image_url FROM dried_food
UNION ALL
SELECT product_name, barcode, price, image_url FROM fresh_food
UNION ALL
SELECT product_name, barcode, price, image_url FROM snack
UNION ALL
SELECT product_name, barcode, price, image_url FROM soft_drink
UNION ALL
SELECT product_name, barcode, price, image_url FROM stationery
SQL;

$result = $conn->query($sql);
?>