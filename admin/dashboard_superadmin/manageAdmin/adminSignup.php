<?php
session_start();
require_once '../../config/db.php'; 

header('Content-Type: application/json; charset=UTF-8');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // ตรวจสอบว่ามีค่าที่ต้องการหรือไม่
    if (!isset($_POST['username'], $_POST['password'], $_POST['confirmPassword'], $_POST['firstName'], $_POST['lastName'])) {
        echo json_encode(["status" => "error", "message" => "ข้อมูลไม่ครบถ้วน"], JSON_UNESCAPED_UNICODE);
        exit;
    }


    $user = $_POST['username'];
    $pass = $_POST['password'];
    $confirmPass = $_POST['confirmPassword'];
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];

    // ตรวจสอบรหัสผ่าน
    if ($pass !== $confirmPass) {
        echo json_encode(["status" => "error", "message" => "รหัสผ่านและยืนยันรหัสผ่านไม่ตรงกัน"], JSON_UNESCAPED_UNICODE);
        exit;
    }


    $check_sql = "SELECT * FROM employee WHERE USERNAME = ?";
    if ($check_stmt = $conn->prepare($check_sql)) {
        $check_stmt->bind_param("s", $user);
        $check_stmt->execute();
        $result = $check_stmt->get_result();

        if ($result->num_rows > 0) {
            echo json_encode(["status" => "error", "message" => "ชื่อผู้ใช้นี้มีอยู่แล้วในระบบ"], JSON_UNESCAPED_UNICODE);
            $check_stmt->close();
            exit;
        }
    } else {
        echo json_encode(["status" => "error", "message" => "ไม่สามารถเชื่อมต่อฐานข้อมูลเพื่อตรวจสอบชื่อผู้ใช้ได้"], JSON_UNESCAPED_UNICODE);
        exit;
    }


    $hashed_pass = password_hash($pass, PASSWORD_DEFAULT);


    $sql = "INSERT INTO employee (USERNAME, PASSWORD, FIRST_NAME, LAST_NAME) VALUES (?, ?, ?, ?)";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ssss", $user, $hashed_pass, $firstName, $lastName);

        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "สมัครสมาชิกสำเร็จแล้ว!"], JSON_UNESCAPED_UNICODE);
        } else {
            echo json_encode(["status" => "error", "message" => "เกิดข้อผิดพลาดในการสมัครสมาชิก"], JSON_UNESCAPED_UNICODE);
        }
        $stmt->close();
    } else {
        echo json_encode(["status" => "error", "message" => "ไม่สามารถเชื่อมต่อฐานข้อมูลเพื่อสมัครสมาชิกได้"], JSON_UNESCAPED_UNICODE);
    }

    $conn->close();
}
exit;
?>