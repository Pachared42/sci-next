<?php
require 'vendor/autoload.php'; // ต้องติดตั้ง PHPSpreadsheet

use PhpOffice\PhpSpreadsheet\IOFactory;

$host = "localhost";
$user = "root";
$pass = "";
$db = "your_database_name";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("เชื่อมต่อฐานข้อมูลล้มเหลว: " . $conn->connect_error);
}

if (isset($_FILES['excel_file']['name'])) {
    $fileName = $_FILES['excel_file']['tmp_name'];

    $spreadsheet = IOFactory::load($fileName);
    $data = $spreadsheet->getActiveSheet()->toArray();

    // ลบหัวตาราง
    array_shift($data);

    foreach ($data as $row) {
        $product_name = $conn->real_escape_string($row[0]);
        $barcode = $conn->real_escape_string($row[1]);
        $price = floatval($row[2]);
        $cost = floatval($row[3]);
        $stock = intval($row[4]);
        $reorder = intval($row[5]);
        $image_url = $conn->real_escape_string($row[6]);

        $sql = "INSERT INTO products_dried_food (product_name, barcode, price, cost, stock, reorder_level, image_url)
                VALUES ('$product_name', '$barcode', $price, $cost, $stock, $reorder, '$image_url')";

        $conn->query($sql);
    }

    echo "อัปโหลดข้อมูลสำเร็จ!";
} else {
    echo "โปรดเลือกไฟล์ Excel";
}
?>