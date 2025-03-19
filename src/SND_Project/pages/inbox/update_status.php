<?php
session_start();
include '../../config/func.php';
require '../../vendor/autoload.php';  // เพิ่ม require autoload
use PHPMailer\PHPMailer\PHPMailer;   // เพิ่ม use statement
use PHPMailer\PHPMailer\Exception;    // เพิ่ม use statement
if (!isset($_SESSION['email'])) {
    header("Location: ../login/login.php");
    exit();
}

// เพิ่มฟังก์ชัน sendEmail จาก rejection.php
function sendEmail($toEmail, $subject, $body) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'ziochar338@gmail.com';  // อีเมลที่ใช้ส่ง
        $mail->Password = 'lvrx dpnu egfy sqiv';  // รหัสแอพ
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;
        $mail->CharSet = 'UTF-8';

        $mail->setFrom('ziochar338@gmail.com', 'Notification System');
        $mail->addAddress($toEmail);
        $mail->Subject = $subject;
        $mail->Body = $body;

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Email could not be sent. Mailer Error: {$mail->ErrorInfo}");
        return false;
    }
}

if (isset($_GET['recipient_id']) && isset($_GET['action'])) {
    $recipient_id = intval($_GET['recipient_id']);
    $action = $_GET['action'];

    if ($action === 'completed') {
        $conn->begin_transaction();  // เริ่ม transaction

        try {
            // 1. อัพเดตสถานะ
            $sql = "UPDATE document_recipient SET status = 'completed' WHERE recipient_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $recipient_id);
            $stmt->execute();

            // 2. ดึงข้อมูลเอกสารและผู้ส่ง
            $doc_sql = "SELECT ds.*, u_sender.firstname as sender_firstname, 
                              u_sender.lastname as sender_lastname,
                              u_sender.email as sender_email,
                              u_current.firstname as current_firstname,
                              u_current.lastname as current_lastname
                       FROM document_submission ds
                       JOIN document_recipient dr ON ds.submission_id = dr.submission_id
                       JOIN users u_sender ON ds.sender_id = u_sender.user_id
                       JOIN users u_current ON u_current.user_id = ?
                       WHERE dr.recipient_id = ?";
            
            $stmt = $conn->prepare($doc_sql);
            $stmt->bind_param("ii", $_SESSION['user_id'], $recipient_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $doc = $result->fetch_assoc();

            if ($doc) {
                // 3. สร้างการแจ้งเตือนในระบบ
                $notification_message = "เอกสาร {$doc['name']} ได้รับการอนุมัติจาก {$doc['current_firstname']} {$doc['current_lastname']}";
                
                $notify_sql = "INSERT INTO notifications (user_id, message, submission_id, created_at) 
                              VALUES (?, ?, ?, NOW())";
                $stmt = $conn->prepare($notify_sql);
                $stmt->bind_param("isi", $doc['sender_id'], $notification_message, $doc['submission_id']);
                $stmt->execute();

                // 4. ส่งอีเมลแจ้งเตือน
                $email_subject = "แจ้งเตือน: เอกสารได้รับการอนุมัติ";
                $email_body = "เรียน คุณ{$doc['sender_firstname']} {$doc['sender_lastname']}\n\n";
                $email_body .= "เอกสารของคุณได้รับการอนุมัติ:\n";
                $email_body .= "ชื่อเอกสาร: {$doc['name']}\n";
                $email_body .= "รหัสเอกสาร: {$doc['document_code']}\n";
                $email_body .= "ผู้อนุมัติ: {$doc['current_firstname']} {$doc['current_lastname']}\n\n";
                $email_body .= "กรุณาเข้าสู่ระบบเพื่อตรวจสอบเอกสาร\n";

                sendEmail($doc['sender_email'], $email_subject, $email_body);

                $conn->commit();
                echo "<script>alert('อัปเดตสถานะสำเร็จ!'); history.back();</script>";
            }
        } catch (Exception $e) {
            $conn->rollback();
            echo "<script>alert('เกิดข้อผิดพลาดในการอัปเดตสถานะ'); history.back();</script>";
        }
    }
}

$conn->close();
?>
