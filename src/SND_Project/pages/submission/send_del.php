<?php
session_start();
include '../../config/func.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: pages/login/login.php");
    exit();
}

if (isset($_GET['submission_id'])) {
    $submission_id = $_GET['submission_id'];

    // อัปเดตสถานะเอกสารเป็น 'cancel'
    $sql = "UPDATE document_submission SET status = 'cancel' WHERE submission_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $submission_id);

    if ($stmt->execute()) {
        echo "<script>alert('ยกเลิกการส่งเอกสารเรียบร้อย'); window.location.href='send.php';</script>";
    } else {
        echo "<script>alert('เกิดข้อผิดพลาด กรุณาลองใหม่'); window.history.back();</script>";
    }
}
?>
