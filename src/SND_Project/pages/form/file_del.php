<?php
ob_start();
session_start();
include '../../config/func.php';

// ตรวจสอบการเข้าสู่ระบบ
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../pages/login/login.php");
    exit();
}

// ตรวจสอบว่ามีการส่ง template_id และ folder_id มาหรือไม่
if (isset($_GET['template_id']) && isset($_GET['folder_id'])) {
    $template_id = intval($_GET['template_id']);
    $folder_id = intval($_GET['folder_id']);

    // ดึงข้อมูลไฟล์เพื่อหาชื่อไฟล์ที่จะลบ
    $sql = "SELECT file_path FROM templates WHERE template_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $template_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $file = $result->fetch_assoc();
        $file_path = "../../uploads/" . $file['file_path'];

        // เริ่ม transaction
        $conn->begin_transaction();

        try {
            // 1. ลบข้อมูลการดาวน์โหลดที่เกี่ยวข้องก่อน
            $sql_delete_logs = "DELETE FROM download_logs WHERE template_id = ?";
            $stmt_delete_logs = $conn->prepare($sql_delete_logs);
            $stmt_delete_logs->bind_param("i", $template_id);
            $stmt_delete_logs->execute();

            // 2. ลบข้อมูลในตาราง templates
            $sql_delete = "DELETE FROM templates WHERE template_id = ?";
            $stmt_delete = $conn->prepare($sql_delete);
            $stmt_delete->bind_param("i", $template_id);
            $stmt_delete->execute();

            // 3. ลบไฟล์จริงในโฟลเดอร์ uploads
            if (file_exists($file_path)) {
                unlink($file_path);
            }

            // Commit transaction
            $conn->commit();

            // ลบสำเร็จ กลับไปหน้าเดิม
            header("Location: file_staff.php?folder_id=" . $folder_id);
            exit();

        } catch (Exception $e) {
            // หากเกิดข้อผิดพลาด ให้ rollback การทำงานทั้งหมด
            $conn->rollback();
            echo "<script>alert('เกิดข้อผิดพลาดในการลบข้อมูล: " . $e->getMessage() . "'); window.history.back();</script>";
            exit();
        }
    } else {
        echo "<script>alert('ไม่พบข้อมูลเอกสาร'); window.history.back();</script>";
        exit();
    }
} else {
    echo "<script>alert('ข้อมูลไม่ครบถ้วน'); window.history.back();</script>";
    exit();
}
?>