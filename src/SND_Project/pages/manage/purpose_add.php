<?php
session_start();
include '../../config/func.php';

// Debug: บันทึกข้อมูลที่ส่งมา
error_log('POST data: ' . print_r($_POST, true));

// ตรวจสอบว่ามีการล็อกอินหรือไม่
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../pages/login/login.php");
    exit();
}

// ตรวจสอบว่ามีการส่งข้อมูลมาหรือไม่
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // รับค่าจากฟอร์ม
    $purpose_name = isset($_POST['purpose_name']) ? trim($_POST['purpose_name']) : '';
    
    // Debug: บันทึกค่า purpose_name
    error_log('Purpose name: ' . $purpose_name);
    
    // ตรวจสอบว่าชื่อวัตถุประสงค์ไม่ว่างเปล่า
    if (!empty($purpose_name)) {
        // เตรียมคำสั่ง SQL สำหรับเพิ่มข้อมูล
        $sql = "INSERT INTO purposes (purpose_name) VALUES (?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $purpose_name);
        
        // ดำเนินการคำสั่ง SQL
        if ($stmt->execute()) {
            $_SESSION['success'] = "เพิ่มวัตถุประสงค์สำเร็จ";
            error_log('Insert successful');
        } else {
            $_SESSION['error'] = "เกิดข้อผิดพลาดในการเพิ่มวัตถุประสงค์: " . $conn->error;
            error_log('Insert error: ' . $conn->error);
        }
        
        $stmt->close();
    } else {
        $_SESSION['error'] = "กรุณาระบุชื่อวัตถุประสงค์";
        error_log('Empty purpose name');
    }
    
    header("Location: purpose.php");
    exit();
} else {
    header("Location: purpose.php");
    exit();
}
