<?php
require_once(__DIR__ . '/../../config/db.php');
header('Content-Type: application/json');

// ตรวจว่ามีการร้องขออะไร (เช่น mode = count หรือ all)
$mode = $_GET['mode'] ?? 'all';

if ($mode === 'count') {
    // ========== โค้ดนับ order เฉพาะวันนี้ ==========
    $today = date('Y-m-d');
    $sql = "SELECT COUNT(*) AS order_count FROM orders WHERE DATE(order_date) = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $today);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    echo json_encode(['success' => true, 'count' => $row['order_count']]);
    exit;
}

// ========== โค้ดดึงรายการ order ทั้งหมด ==========
$sql = "
SELECT 
    o.id AS order_id,
    o.order_date,
    oi.product_name,
    oi.barcode,
    oi.price,
    oi.quantity,
    oi.image_url
FROM orders o
JOIN order_items oi ON o.id = oi.order_id
ORDER BY o.order_date DESC
";

$result = $conn->query($sql);
if (!$result) {
    echo json_encode(['success' => false, 'message' => $conn->error]);
    exit;
}

$orders = [];
while ($row = $result->fetch_assoc()) {
    $orderId = $row['order_id'];
    if (!isset($orders[$orderId])) {
        $orders[$orderId] = [
            'order_date' => $row['order_date'],
            'items' => [],
            'total' => 0
        ];
    }

    $subtotal = $row['price'] * $row['quantity'];
    $orders[$orderId]['items'][] = [
        'name' => $row['product_name'],
        'barcode' => $row['barcode'],
        'price' => $row['price'],
        'quantity' => $row['quantity'],
        'image_url' => $row['image_url'],
        'subtotal' => $subtotal
    ];

    $orders[$orderId]['total'] += $subtotal;
}

echo json_encode(['success' => true, 'orders' => $orders]);
