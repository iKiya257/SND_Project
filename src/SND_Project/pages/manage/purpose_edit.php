<?php
session_start();
include '../../config/func.php';

// ตรวจสอบว่ามีการล็อกอินหรือไม่
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../pages/login/login.php");
    exit();
}

// ตรวจสอบว่ามีการส่งข้อมูลมาหรือไม่
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // รับค่าจากฟอร์ม
    $purpose_id = $_POST['purpose_id'];
    $purpose_name = $_POST['purpose_name'];
    
    // ตรวจสอบว่าชื่อวัตถุประสงค์และรหัสไม่ว่างเปล่า
    if (!empty($purpose_id) && !empty($purpose_name)) {
        // เตรียมคำสั่ง SQL สำหรับอัปเดตข้อมูล
        $sql = "UPDATE purposes SET purpose_name = ? WHERE purpose_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $purpose_name, $purpose_id);
        
        // ดำเนินการคำสั่ง SQL
        if ($stmt->execute()) {
            // อัปเดตข้อมูลสำเร็จ
            $_SESSION['success'] = "แก้ไขวัตถุประสงค์สำเร็จ";
        } else {
            // อัปเดตข้อมูลไม่สำเร็จ
            $_SESSION['error'] = "เกิดข้อผิดพลาดในการแก้ไขวัตถุประสงค์: " . $conn->error;
        }
        
        $stmt->close();
    } else {
        // ข้อมูลไม่ครบถ้วน
        $_SESSION['error'] = "กรุณาระบุข้อมูลให้ครบถ้วน";
    }
    
    // กลับไปยังหน้าจัดการวัตถุประสงค์
    header("Location: purpose.php");
    exit();
} else {
    // ถ้าไม่ได้ส่งข้อมูลมาด้วยวิธี POST ให้กลับไปยังหน้าจัดการวัตถุประสงค์
    header("Location: purpose.php");
    exit();
}
