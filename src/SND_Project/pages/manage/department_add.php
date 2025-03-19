<?php
session_start();
include '../../config/func.php';

// ตรวจสอบว่ามีการเข้าสู่ระบบหรือไม่
if (!isset($_SESSION['email'])) {
    header("Location: ../../pages/login/login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // รับค่าจากฟอร์ม
    $department_name = trim($_POST['folder_name']);

    // ตรวจสอบว่าชื่อกลุ่มวิชาไม่ว่างเปล่า
    if (!empty($department_name)) {
        // เชื่อมต่อฐานข้อมูล
        include '../../config/db.php';

        // เตรียมคำสั่ง SQL
        $stmt = $conn->prepare("INSERT INTO departments (department_name) VALUES (?)");
        $stmt->bind_param("s", $department_name);
        
        // ดำเนินการเพิ่มข้อมูล
        if ($stmt->execute()) {
            $_SESSION['success'] = "เพิ่มกลุ่มวิชาเรียบร้อยแล้ว";
        } else {
            $_SESSION['error'] = "เกิดข้อผิดพลาดในการเพิ่มกลุ่มวิชา";
        }
        
        // ปิดการเชื่อมต่อ
        $stmt->close();
        $conn->close();
    } else {
        $_SESSION['error'] = "กรุณาระบุชื่อกลุ่มวิชา";
    }
}

// กลับไปยังหน้าหลัก
header("Location: department.php");
exit();
?>