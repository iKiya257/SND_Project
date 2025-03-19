<?php
ob_start();
session_start();
include '../../config/func.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../pages/login/login.php");
    exit();
}

if (isset($_GET['folder_id'])) {
    $folder_id = intval($_GET['folder_id']); // ป้องกัน SQL Injection

    $sql = "DELETE FROM templates WHERE folder_id = $folder_id";
    $sql = "DELETE FROM folder_mapping WHERE folder_id = $folder_id";
    $sql = "DELETE FROM folders WHERE folder_id = $folder_id";
    
    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('ลบโฟลเดอร์สำเร็จ!'); window.location.href='folder-staff.php';</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
} else {
    header("Location: folder-staff.php");
    exit();
}
?>
