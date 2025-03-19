<?php
session_start();
include '../../config/func.php';

// ตรวจสอบว่ามีการล็อกอินหรือไม่
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../pages/login/login.php");
    exit();
}

// ตรวจสอบว่ามีการส่ง ID มาหรือไม่
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $purpose_id = $_GET['id'];
    
    // ตรวจสอบก่อนว่ามีการใช้งานวัตถุประสงค์นี้ในตาราง document_submission หรือไม่
    $check_sql = "SELECT COUNT(*) as count FROM document_submission WHERE purpose_id = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("i", $purpose_id);
    $check_stmt->execute();
    $result = $check_stmt->get_result();
    $row = $result->fetch_assoc();
    $check_stmt->close();
    
    if ($row['count'] > 0) {
        // มีการใช้งานวัตถุประสงค์นี้อยู่ ไม่สามารถลบได้
        $_SESSION['error'] = "ไม่สามารถลบวัตถุประสงค์นี้ได้ เนื่องจากมีการใช้งานในเอกสารการส่งงาน";
    } else {
        // ไม่มีการใช้งาน สามารถลบได้
        // เตรียมคำสั่ง SQL สำหรับลบข้อมูล
        $sql = "DELETE FROM purposes WHERE purpose_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $purpose_id);
        
        // ดำเนินการคำสั่ง SQL
        if ($stmt->execute()) {
            // ลบข้อมูลสำเร็จ
            $_SESSION['success'] = "ลบวัตถุประสงค์สำเร็จ";
        } else {
            // ลบข้อมูลไม่สำเร็จ
            $_SESSION['error'] = "เกิดข้อผิดพลาดในการลบวัตถุประสงค์: " . $conn->error;
        }
        
        $stmt->close();
    }
} else {
    // ไม่มี ID ที่ส่งมา
    $_SESSION['error'] = "ไม่พบรหัสวัตถุประสงค์ที่ต้องการลบ";
}

// กลับไปยังหน้าจัดการวัตถุประสงค์
header("Location: purpose.php");
exit();
