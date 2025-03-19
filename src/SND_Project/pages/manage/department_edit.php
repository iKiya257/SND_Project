<?php
session_start();
include '../../config/func.php';

if (!isset($_SESSION['email'])) {
    header("Location: ../../pages/login/login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $department_id = $_POST['department_id'];
    $department_name = trim($_POST['department_name']);

    // ตรวจสอบว่าชื่อสังกัดไม่ว่าง
    if (empty($department_name)) {
        echo "<script>alert('กรุณากรอกชื่อสังกัด'); window.history.back();</script>";
        exit();
    }

    // อัปเดตข้อมูลในฐานข้อมูล
    $sql = "UPDATE departments SET department_name = ? WHERE department_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $department_name, $department_id);

    if ($stmt->execute()) {
        header("Location: department.php");
        exit();
    } else {
        echo "<script>alert('เกิดข้อผิดพลาด กรุณาลองใหม่'); window.history.back();</script>";
    }

    $stmt->close();
    $conn->close();
}
?>
