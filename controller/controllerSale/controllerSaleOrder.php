<?php
require_once(__DIR__ . '/../../config/db.php');
header('Content-Type: application/json');

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
