<?php
session_start();
include '../../config/func.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: pages/login/login.php");
    exit();
}

// ตรวจสอบว่ามีค่าที่จำเป็นหรือไม่
if (!isset($_GET['action']) || (!isset($_GET['receiver_id']) && !isset($_GET['department_id']))) {
    die("ข้อมูลไม่ถูกต้อง");
}

$action = $_GET['action'];
$status = ($action == 'pull') ? 'removed' : 'pending';

// ตรวจสอบว่าได้รับ submission_id หรือไม่
if (isset($_GET['submission_id']) && is_numeric($_GET['submission_id'])) {
    $submission_id = intval($_GET['submission_id']);
} else {
    die("ข้อมูล submission_id ไม่ถูกต้อง");
}

// ตรวจสอบว่าค่าที่รับมาเป็นตัวเลขหรือไม่ (ป้องกัน SQL Injection)
if (isset($_GET['receiver_id']) && is_numeric($_GET['receiver_id'])) {
    $id = intval($_GET['receiver_id']);
    $sql = "UPDATE document_recipient SET status = ? WHERE receiver_id = ? AND submission_id = ?";
} elseif (isset($_GET['department_id']) && is_numeric($_GET['department_id'])) {
    $id = intval($_GET['department_id']);
    $sql = "UPDATE document_recipient SET status = ? WHERE department_id = ? AND submission_id = ?";
} else {
    die("ข้อมูลไม่ถูกต้อง");
}

$stmt = $conn->prepare($sql);
$stmt->bind_param("sii", $status, $id, $submission_id);

if ($stmt->execute()) {
    echo "<script>alert('อัปเดตสถานะสำเร็จ!'); history.back();</script>";
} else {
    echo "<script>alert('เกิดข้อผิดพลาดในการอัปเดตสถานะ'); history.back();</script>";
}

$stmt->close();
$conn->close();
?>
