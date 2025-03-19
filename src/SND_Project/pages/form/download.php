<?php
ob_start();
session_start();
include '../../config/func.php';

if (!isset($_SESSION['user_id']) || !isset($_GET['template_id'])) {
    header("Location: ../../pages/login/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$template_id = $_GET['template_id'];

// ดึงข้อมูลไฟล์และชื่อ template จากฐานข้อมูล
$sql = "SELECT file_path, template_name FROM templates WHERE template_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $template_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $template = $result->fetch_assoc();
    $file_path = '../../uploads/' . $template['file_path'];
    
    // ดึงนามสกุลไฟล์จาก file_path
    $file_extension = pathinfo($file_path, PATHINFO_EXTENSION);
    
    // สร้างชื่อไฟล์ใหม่จาก template_name + นามสกุลไฟล์เดิม
    $download_filename = $template['template_name'] . '.' . $file_extension;

    if (file_exists($file_path)) {
        // บันทึกการดาวน์โหลดลงในตาราง download_logs
        $sql = "INSERT INTO download_logs (user_id, template_id, download_time) VALUES (?, ?, CURRENT_TIMESTAMP())";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $user_id, $template_id);
        $stmt->execute();

        // ดาวน์โหลดไฟล์
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $download_filename . '"');
        header('Content-Length: ' . filesize($file_path));
        readfile($file_path);
        exit();
    }
}

// หากไม่พบไฟล์หรือมีข้อผิดพลาด
echo "<script>alert('ไม่พบไฟล์ที่ต้องการดาวน์โหลด'); window.history.back();</script>";
exit();
?>