<?php
// เชื่อมต่อฐานข้อมูล
require __DIR__ . '/../../../config/db.php';

// เช็กว่าได้ id มาจาก URL ไหม
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $query = "SELECT * FROM dried_food WHERE id = ?";
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("i", $id); // i = integer
        $stmt->execute();
        $result = $stmt->get_result();
        $product = $result->fetch_assoc();

        if ($product) {
            // ส่งข้อมูลกลับแบบ JSON
            header('Content-Type: application/json');
            echo json_encode($product);
        } else {
            http_response_code(404);
            echo json_encode(["message" => "ไม่พบสินค้า"]);
        }

        $stmt->close();
    } else {
        http_response_code(500);
        echo json_encode(["message" => "เกิดข้อผิดพลาดในการเตรียมคำสั่ง SQL"]);
    }
} else {
    http_response_code(400);
    echo json_encode(["message" => "ไม่ได้ระบุรหัสสินค้า"]);
}

$conn->close();
?>
