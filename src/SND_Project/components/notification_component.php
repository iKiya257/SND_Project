<?php
require_once '../../config/func.php';
require_once __DIR__ . '/notification_functions.php';

// ตรวจสอบประเภทของผู้ใช้และเรียกใช้ไฟล์ที่เหมาะสม
if (isset($_SESSION['role'])) {
    $user_role = strtolower($_SESSION['role']);
    
    if ($user_role == 'lecturer') {
        include __DIR__ . '/notification_component_lecturer.php';
    } else if ($user_role == 'staff') {
        include __DIR__ . '/notification_component_staff.php';
    } else {
        // กรณีไม่ทราบประเภทผู้ใช้ ให้ใช้ของ staff เป็นค่าเริ่มต้น
        include __DIR__ . '/notification_component_staff.php';
    }
} else {
    // กรณีไม่มีข้อมูล role ให้ใช้ของ staff เป็นค่าเริ่มต้น
    include __DIR__ . '/notification_component_staff.php';
}
?>