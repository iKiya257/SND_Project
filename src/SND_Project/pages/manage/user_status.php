<?php
ob_start(); // Start output buffering
session_start();
include '../../config/func.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: pages/login/login.php");
    exit();
}

if (isset($_GET['u_id']) && isset($_GET['action'])) {
    $user_id = intval($_GET['u_id']);
    $action = $_GET['action'];

    if ($action === 'activate') {
        $sql = "UPDATE users SET status = 1 WHERE user_id = $user_id";
    } elseif ($action === 'deactivate') {
        $sql = "UPDATE users SET status = 0 WHERE user_id = $user_id";
    }

    if (mysqli_query($conn, $sql)) {
        // ส่งผู้ใช้กลับไปยังหน้า user.php
        header("Location: user.php");
        exit();
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
} else {
    echo "Invalid request.";
}

ob_end_flush(); // End and flush the buffer
