<?php
require_once '../../config/func.php';
require_once '../../vendor/autoload.php'; // สำหรับ PHPWord

if (isset($_GET['template_id'])) {
    $template_id = $_GET['template_id'];
    
    // ดึงข้อมูลไฟล์จากฐานข้อมูล
    $query = "SELECT * FROM templates WHERE template_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $template_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $template = $result->fetch_assoc();

    if ($template) {
        // ต้องใช้ path เต็มเพื่อเข้าถึงไฟล์
        $filePath = '../../uploads/' . $template['file_path'];
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
            echo json_encode(['status' => 'error', 'message' => 'ไม่สามารถแสดงตัวอย่างไฟล์ได้: ' . $e->getMessage()]);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'ไม่พบไฟล์']);
    }
}