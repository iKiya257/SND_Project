<?php
ob_start();
session_start();
include '../../config/func.php';

if (!isset($_SESSION['email'])) {
    header("Location: ../../pages/login/login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $prefix = $_POST['prefix'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $position = $_POST['position'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];
    $department_id = $_POST['department'];

    // ตรวจสอบอีเมลซ้ำ
    $check_email = "SELECT email FROM users WHERE email = ?";
    $stmt_check = $conn->prepare($check_email);
    $stmt_check->bind_param("s", $email);
    $stmt_check->execute();
    $result = $stmt_check->get_result();

    if ($result->num_rows > 0) {
        // อีเมลซ้ำ
        $_SESSION['error'] = "อีเมลนี้มีอยู่ในระบบแล้ว กรุณาใช้อีเมลอื่น";
        header("Location: adduser.php");
        ob_end_flush();
        exit();
    }

    // เข้ารหัสรหัสผ่าน
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    try {
        // เริ่ม transaction
        $conn->begin_transaction();

        // เพิ่มผู้ใช้
        $sql = "INSERT INTO users (prefix, upassword, firstname, lastname, position, email, role) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssss", $prefix, $hashed_password, $firstname, $lastname, $position, $email, $role);
        
        if ($stmt->execute()) {
            $user_id = $stmt->insert_id;
            
            // เพิ่มความสัมพันธ์กับแผนก
            $sql_dept = "INSERT INTO user_department (user_id, department_id) VALUES (?, ?)";
            $stmt_dept = $conn->prepare($sql_dept);
            $stmt_dept->bind_param("ii", $user_id, $department_id);
            
            if ($stmt_dept->execute()) {
                // ทำการ commit เมื่อทุกอย่างสำเร็จ
                $conn->commit();
                $_SESSION['success'] = "เพิ่มผู้ใช้สำเร็จ";
                header("Location: user.php");
                ob_end_flush();
                exit();
            } else {
                throw new Exception("ไม่สามารถเพิ่มข้อมูลแผนกได้");
            }
        } else {
            throw new Exception("ไม่สามารถเพิ่มข้อมูลผู้ใช้ได้");
        }
    } catch (Exception $e) {
        // ถ้าเกิดข้อผิดพลาด ให้ rollback
        $conn->rollback();
        $_SESSION['error'] = "เกิดข้อผิดพลาด: " . $e->getMessage();
        header("Location: adduser.php");
        ob_end_flush();
        exit();
    }
}
?>