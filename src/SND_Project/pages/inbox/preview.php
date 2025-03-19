<?php
require_once '../../config/func.php';
require_once '../../vendor/autoload.php'; // สำหรับ PHPWord

if (isset($_GET['file_id'])) {
    $file_id = $_GET['file_id'];
    
    // ดึงข้อมูลไฟล์จากฐานข้อมูล
    $query = "SELECT file_path FROM document_files WHERE file_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $file_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $template = $result->fetch_assoc();

    if ($template) {
        // ทดลองหลายเส้นทาง
        $possiblePaths = [
            '../../uploads/' . $template['file_path'],
            '../uploads/' . $template['file_path'],
            '../../../uploads/' . $template['file_path'],
            './uploads/' . $template['file_path'],
            $template['file_path'] // กรณีที่ file_path เป็นเส้นทางเต็มอยู่แล้ว
        ];
        
        $filePath = null;
        $debugInfo = [];
        
        foreach ($possiblePaths as $path) {
            $debugInfo[$path] = file_exists($path) ? 'ไฟล์มีอยู่' : 'ไม่พบไฟล์';
            if (file_exists($path)) {
                $filePath = $path;
                break;
            }
        }
        
        if (!$filePath) {
            // ไม่พบไฟล์ในทุกเส้นทางที่เป็นไปได้
            echo json_encode([
                'status' => 'error', 
                'message' => 'ไม่พบไฟล์ในระบบ', 
                'debug' => [
                    'file_path_in_db' => $template['file_path'],
                    'path_checks' => $debugInfo,
                    'current_dir' => getcwd(),
                    'script_filename' => $_SERVER['SCRIPT_FILENAME']
                ]
            ]);
            exit;
        }
        
        $ext = pathinfo($filePath, PATHINFO_EXTENSION);
        
        if ($ext != 'docx') {
            echo json_encode(['status' => 'error', 'message' => 'สามารถแสดงตัวอย่างได้เฉพาะไฟล์ .docx เท่านั้น']);
            exit;
        }

        try {
            $phpWord = \PhpOffice\PhpWord\IOFactory::load($filePath);
            $htmlWriter = new \PhpOffice\PhpWord\Writer\HTML($phpWord);
            
            // เก็บ HTML content ในตัวแปร
            ob_start();
            $htmlWriter->save('php://output');
            $htmlContent = ob_get_clean();
            
            echo json_encode(['status' => 'success', 'content' => $htmlContent]);
        } catch (Exception $e) {
            echo json_encode([
                'status' => 'error', 
                'message' => 'ไม่สามารถแสดงตัวอย่างไฟล์ได้: ' . $e->getMessage(),
                'debug' => [
                    'file_path_in_db' => $template['file_path'],
                    'path_used' => $filePath,
                    'file_exists' => file_exists($filePath) ? 'yes' : 'no',
                    'file_readable' => is_readable($filePath) ? 'yes' : 'no',
                    'file_size' => file_exists($filePath) ? filesize($filePath) : 'N/A'
                ]
            ]);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'ไม่พบข้อมูลไฟล์ในฐานข้อมูล']);
    }
}