<?php
require_once '../../config/func.php';

if (isset($_GET['file_id'])) {
    $file_id = $_GET['file_id'];
    
    // ดึงข้อมูลไฟล์จากฐานข้อมูล
    $query = "SELECT * FROM document_files WHERE file_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $file_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $file = $result->fetch_assoc();
    
    if ($file) {
        echo "<h3>ข้อมูลไฟล์:</h3>";
        echo "<pre>";
        print_r($file);
        echo "</pre>";
        
        // ตรวจสอบ path ต่างๆ
        $paths = [
            '../../uploads/' . $file['file_path'],
            '../uploads/' . $file['file_path'],
            '/var/www/html/uploads/' . $file['file_path'],
            $_SERVER['DOCUMENT_ROOT'] . '/uploads/' . $file['file_path'],
            realpath('../../uploads') . '/' . $file['file_path']
        ];
        
        echo "<h3>ตรวจสอบ path:</h3>";
        echo "<ul>";
        foreach ($paths as $path) {
            echo "<li>$path: " . (file_exists($path) ? "มีไฟล์" : "ไม่มีไฟล์") . "</li>";
        }
        echo "</ul>";
    } else {
        echo "ไม่พบข้อมูลไฟล์";
    }
} else {
    echo "ไม่ได้ระบุ file_id";
}