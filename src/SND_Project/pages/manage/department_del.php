<?php
session_start();
include '../../config/func.php';

if (!isset($_SESSION['email'])) {
    header("Location: ../login/login.php");
    exit();
}

// ตรวจสอบว่ามีการส่ง u_id มาหรือไม่
if (isset($_GET['id'])) {
    $department_id = intval($_GET['id']); // แปลงเป็นจำนวนเต็มเพื่อความปลอดภัย

    // ตรวจสอบว่าผู้ใช้มีอยู่ในระบบหรือไม่
    $checkDepartment = "SELECT * FROM departments WHERE department_id = ?";
    $stmt = mysqli_prepare($conn, $checkDepartment);
    mysqli_stmt_bind_param($stmt, "i", $department_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        // ลบผู้ใช้จากฐานข้อมูล
        $sql = "DELETE FROM departments WHERE department_id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $department_id);

        if (mysqli_stmt_execute($stmt)) {

            header("Location: department.php?delete_success=1");
        } else {
            echo "เกิดข้อผิดพลาดในการลบข้อมูล: " . mysqli_error($conn);
        }
    } else {
        echo "ไม่พบผู้ใช้ที่ต้องการลบ.";
    }
} else {

    header("Location: department.php");
    exit();
}
?>
