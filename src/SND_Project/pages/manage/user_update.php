<?php
ob_start();
session_start();
include '../../config/func.php';

if (!isset($_SESSION['email'])) {
    header("Location: ../login/login.php");
    exit();
}

// ตรวจสอบการส่งข้อมูลฟอร์ม
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // รับค่าจากฟอร์ม
    $user_id = intval($_POST['user_id']); // แปลงเป็นจำนวนเต็มเพื่อความปลอดภัย
    $prefix = mysqli_real_escape_string($conn, $_POST['prefix']);
    $firstname = mysqli_real_escape_string($conn, $_POST['firstname']);
    $lastname = mysqli_real_escape_string($conn, $_POST['lastname']);
    $position = mysqli_real_escape_string($conn, $_POST['position']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $role = mysqli_real_escape_string($conn, $_POST['role']);

    // คำสั่ง SQL สำหรับอัปเดตข้อมูลผู้ใช้
    $sql = "UPDATE users SET prefix = ?, firstname = ?, lastname = ?, position = ?, email = ?, role = ? WHERE user_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ssssssi", $prefix, $firstname, $lastname, $position, $email, $role, $user_id);

    if (mysqli_stmt_execute($stmt)) {
        // ถ้าสำเร็จให้กลับไปยังหน้าผู้ใช้
        header("Location: user.php");
    } else {
        echo "เกิดข้อผิดพลาดในการอัปเดตข้อมูล: " . mysqli_error($conn);
    }
} else {
    // ถ้าไม่ใช่ POST ให้เปลี่ยนกลับไปยังหน้าผู้ใช้
    header("Location: user.php");
    exit();
}

$department_id = intval($_POST['department']); // รับค่า department_id

// ตรวจสอบว่าผู้ใช้มีภาควิชาอยู่หรือไม่
$sql_check = "SELECT * FROM user_department WHERE user_id = ?";
$stmt_check = mysqli_prepare($conn, $sql_check);
mysqli_stmt_bind_param($stmt_check, "i", $user_id);
mysqli_stmt_execute($stmt_check);
$result_check = mysqli_stmt_get_result($stmt_check);

if (mysqli_num_rows($result_check) > 0) {
    // อัปเดตภาควิชาเดิม
    $sql_update = "UPDATE user_department SET department_id = ? WHERE user_id = ?";
    $stmt_update = mysqli_prepare($conn, $sql_update);
    mysqli_stmt_bind_param($stmt_update, "ii", $department_id, $user_id);
    mysqli_stmt_execute($stmt_update);
} else {
    // เพิ่มภาควิชาใหม่
    $sql_insert = "INSERT INTO user_department (user_id, department_id) VALUES (?, ?)";
    $stmt_insert = mysqli_prepare($conn, $sql_insert);
    mysqli_stmt_bind_param($stmt_insert, "ii", $user_id, $department_id);
    mysqli_stmt_execute($stmt_insert);
}

?>
