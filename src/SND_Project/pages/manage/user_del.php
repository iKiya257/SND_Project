<?php
ob_start();
session_start();
include '../../config/func.php';

if (!isset($_SESSION['email'])) {
    header("Location: ../login/login.php");
    exit();
}

// ตรวจสอบว่ามีการส่ง u_id มาหรือไม่
if (isset($_GET['u_id'])) {
    $user_id = intval($_GET['u_id']); // แปลงเป็นจำนวนเต็มเพื่อความปลอดภัย

    // ตรวจสอบว่าผู้ใช้มีอยู่ในระบบหรือไม่
    $checkUser = "SELECT * FROM users WHERE user_id = ?";
    $stmt = mysqli_prepare($conn, $checkUser);
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        // เริ่ม transaction
        mysqli_begin_transaction($conn);

        try {
            // ลบผู้ใช้จากฐานข้อมูล
            $sql = "DELETE FROM users WHERE user_id = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "i", $user_id);

            if (mysqli_stmt_execute($stmt)) {
                // เมื่อสำเร็จ
                mysqli_commit($conn);
                $_SESSION['success'] = "ลบข้อมูลผู้ใช้เรียบร้อยแล้ว";
                header("Location: user.php");
                ob_end_flush();
                exit();
            } else {
                throw new Exception("เกิดข้อผิดพลาดในการลบข้อมูล: " . mysqli_error($conn));
            }
        } catch (Exception $e) {
            mysqli_rollback($conn);
            $_SESSION['error'] = $e->getMessage();
            header("Location: user.php");
            ob_end_flush();
            exit();
        }
    } else {
        echo "ไม่พบผู้ใช้ที่ต้องการลบ.";
    }
} else {
    // หากไม่มี u_id ส่งมา กลับไปหน้า user.php
    header("Location: user.php");
    ob_end_flush();
    exit();
}
?>
