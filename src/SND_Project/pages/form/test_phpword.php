<?php
require_once '../../vendor/autoload.php';

try {
    // สร้าง PHPWord instance
    $phpWord = new \PhpOffice\PhpWord\PhpWord();
    echo "PHPWord สามารถใช้งานได้!\n";
    
    // แสดงเวอร์ชัน
    $reflection = new ReflectionClass('\PhpOffice\PhpWord\PhpWord');
    $version = $reflection->getNamespaceName();
    echo "PHPWord version: $version\n";
    
    // ตรวจสอบ extensions
    $required = ['xml', 'zip', 'dom', 'gd', 'mbstring'];
    echo "PHP Extensions:\n";
    foreach ($required as $ext) {
        echo "$ext: " . (extension_loaded($ext) ? "Loaded" : "Not loaded") . "\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}