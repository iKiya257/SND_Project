<?php
ob_start();
session_start();
include '../../config/func.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../pages/login/login.php");
    exit();
}
// รับค่า session ของผู้ใช้
$user_email = $_SESSION['email'];

// ตรวจสอบว่ามีการส่งข้อมูลจากฟอร์มหรือไม่
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['folder_name'])) {
    // รับค่าจากฟอร์ม
    $folder_name = trim($_POST['folder_name']);
    
    // ตรวจสอบว่าชื่อโฟลเดอร์ว่างหรือไม่
    if (empty($folder_name)) {
        echo "<script>
                alert('ชื่อโฟลเดอร์ห้ามว่างเปล่า');
                window.history.back();
              </script>";
        exit();
    }
    
    // ดึงข้อมูล user_id ของผู้ใช้ปัจจุบันจากตาราง users
    $sql_user = "SELECT user_id FROM users WHERE email = ?";
    $stmt_user = $conn->prepare($sql_user);
    $stmt_user->bind_param("s", $user_email);
    $stmt_user->execute();
    $result_user = $stmt_user->get_result();
    
    if ($result_user->num_rows > 0) {
        $user = $result_user->fetch_assoc();
        $user_id = $user['user_id'];
        
        // เพิ่มข้อมูลโฟลเดอร์ใหม่ในตาราง folders
        $sql_insert = "INSERT INTO folders (folder_name, created_by) VALUES (?, ?)";
        $stmt_insert = $conn->prepare($sql_insert);
        $stmt_insert->bind_param("si", $folder_name, $user_id);
        
        if ($stmt_insert->execute()) {
            // สำเร็จ: redirect กลับไปยังหน้าฟอร์ม
            header("Location: folder-staff.php?success=1");
            exit();
        } else {
            echo "เกิดข้อผิดพลาดในการบันทึกข้อมูล: " . $stmt_insert->error;
        }
    } else {
        echo "ไม่พบข้อมูลผู้ใช้ในระบบ";
    }
} else {
    echo "การส่งข้อมูลไม่ถูกต้อง";
}
?>
