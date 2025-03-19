<?php
ob_start(); // Start output buffering
session_start();
include '../../config/db.php';
$_SESSION['ok'] = 0;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['upassword'];
    check_login($conn, $email, $password);
}

function check_login($conn, $email, $password) {
    $sql = "SELECT * FROM users WHERE email=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        if ($row['status'] == 0) { // บัญชีถูกระงับ
            $_SESSION['error'] = "บัญชีนี้ถูกระงับการใช้งาน กรุณาติดต่อผู้ดูแลระบบ";
            header('Location: login.php');
            exit();
        }

        if (password_verify($password, $row['upassword'])) {
            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['email'] = $row['email'];
            $_SESSION['role'] = $row['role'];

            if ($row['role'] == 'admin') {
                header("Location: ../../pages/dashboard/admin.php");
            } else if ($row['role'] == 'staff') {
                header("Location: ../../pages/dashboard/staff.php");
            } else {
                header("Location: ../../pages/dashboard/lecturer.php");
            }
            exit();
        } else {
            $_SESSION['error'] = "อีเมลหรือรหัสผ่านไม่ถูกต้อง";
            header('Location: login.php');
            exit();
        }
    } else {
        $_SESSION['error'] = "ไม่พบผู้ใช้งานนี้ในระบบ";
        header('Location: login.php');
        exit();
    }
    $stmt->close();
}

ob_end_flush(); // End and flush the buffer

