<?php
// เชื่อมต่อฐานข้อมูล
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // รับค่าจากฟอร์ม
    $folder_id = intval($_POST['folder_id']);
    $template_name = trim($_POST['template_name']);

    // ตรวจสอบว่าได้อัปโหลดไฟล์มาหรือไม่
    if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['file'];

        // ตรวจสอบชนิดไฟล์
        $allowed_types = array("docx", "doc");
        if (!in_array($file_type, $allowed_types)) {
            echo "อัปโหลดเฉพาะไฟล์ .docx เท่านั้น";
        }

        // กำหนดโฟลเดอร์สำหรับจัดเก็บไฟล์
        $upload_dir = 'uploads/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        // สร้างชื่อไฟล์ใหม่เพื่อหลีกเลี่ยงการชนกัน
        $file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $new_file_name = uniqid('doc_', true) . '.' . $file_extension;
        $file_path = $upload_dir . $new_file_name;

        // ย้ายไฟล์ไปยังโฟลเดอร์ที่กำหนด
        if (move_uploaded_file($file['tmp_name'], $file_path)) {
            // บันทึกข้อมูลลงในฐานข้อมูล
            $sql = "INSERT INTO templates (folder_id, template_name, file_path, uploaded_by, created_at) 
                    VALUES (?, ?, ?, ?, NOW())";

            if ($stmt = $conn->prepare($sql)) {
                // เปลี่ยน user_id ตามระบบที่คุณมี (สมมุติเป็น 1 สำหรับตัวอย่างนี้)
                $user_id = 1;

                $stmt->bind_param('issi', $folder_id, $template_name, $file_path, $user_id);

                if ($stmt->execute()) {
                    echo 'อัปโหลดไฟล์สำเร็จ';
                } else {
                    echo 'เกิดข้อผิดพลาดในการบันทึกข้อมูล: ' . $stmt->error;
                }

                $stmt->close();
            } else {
                echo 'เกิดข้อผิดพลาดในการเตรียมคำสั่ง SQL: ' . $conn->error;
            }
        } else {
            echo 'เกิดข้อผิดพลาดในการอัปโหลดไฟล์';
        }
    } else {
        echo 'โปรดเลือกไฟล์ที่ต้องการอัปโหลด';
    }
}

$conn->close();
?>
