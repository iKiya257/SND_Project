<?php
session_start(); // เริ่มต้น session
include '../../config/func.php';
// ตรวจสอบว่าผู้ใช้เข้าสู่ระบบหรือยัง

if (isset($_POST['department_id'])) {
    $dept_id = intval($_POST['department_id']);
    
    $sql = "SELECT u.user_id, u.prefix, u.firstname, u.lastname 
            FROM users u
            JOIN user_department ud ON u.user_id = ud.user_id
            WHERE ud.department_id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $dept_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $users = [];
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }

    echo json_encode($users);
}

?>
