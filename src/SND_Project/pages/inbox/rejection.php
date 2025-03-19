<?php
ob_start();
session_start();
include '../../config/func.php';
require '../../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login/login.php");
    exit();
}

// ฟังก์ชันส่งอีเมล
function sendEmail($toEmail, $subject, $body)
{
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'ziochar338@gmail.com';
        $mail->Password = 'lvrx dpnu egfy sqiv';
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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $conn->begin_transaction();

    try {
        $recipient_id = $_POST['recipient_id'];
        $submission_id = $_POST['submission_id'];
        $revision_reason = $_POST['revision_reason'];
        $current_user_id = $_SESSION['user_id'];

        // 1. อัพเดตสถานะเอกสาร
        $update_sql = "UPDATE document_recipient 
                      SET status = 'revision', revision_reason = ? 
                      WHERE recipient_id = ?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("si", $revision_reason, $recipient_id);
        $stmt->execute();

        // 2. บันทึกลง document_revisions
        $revision_sql = "INSERT INTO document_revisions (submission_id, recipient_id) VALUES (?, ?)";
        $stmt = $conn->prepare($revision_sql);
        $stmt->bind_param("ii", $submission_id, $recipient_id);
        $stmt->execute();

        // 3. ดึงข้อมูลเอกสารและผู้ส่ง
        $doc_sql = "SELECT ds.*, u_sender.firstname as sender_firstname, 
                           u_sender.lastname as sender_lastname,
                           u_sender.email as sender_email,
                           u_current.firstname as current_firstname,
                           u_current.lastname as current_lastname
                    FROM document_submission ds
                    JOIN users u_sender ON ds.sender_id = u_sender.user_id
                    JOIN users u_current ON u_current.user_id = ?
                    WHERE ds.submission_id = ?";

        $stmt = $conn->prepare($doc_sql);
        $stmt->bind_param("ii", $current_user_id, $submission_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $doc = $result->fetch_assoc();

        if ($doc) {
            // 4. สร้างการแจ้งเตือนในระบบ
            $notification_message = "เอกสาร {$doc['name']} ถูกส่งกลับจาก {$doc['current_firstname']} {$doc['current_lastname']} เนื่องจาก: {$revision_reason}";

            $notify_sql = "INSERT INTO notifications (user_id, submission_id, message, created_at) 
               VALUES (?, ?, ?, NOW())";
            $stmt = $conn->prepare($notify_sql);
            $stmt->bind_param("iis", $doc['sender_id'], $submission_id, $notification_message);
            $stmt->execute();


            // 5. ส่งอีเมลแจ้งเตือน
            $email_subject = "แจ้งเตือน: เอกสารถูกส่งกลับ";
            $email_body = "เรียน คุณ{$doc['sender_firstname']} {$doc['sender_lastname']}\n\n";
            $email_body .= "เอกสารของคุณถูกส่งกลับ:\n";
            $email_body .= "ชื่อเอกสาร: {$doc['name']}\n";
            $email_body .= "รหัสเอกสาร: {$doc['document_code']}\n";
            $email_body .= "ผู้ส่งกลับ: {$doc['current_firstname']} {$doc['current_lastname']}\n";
            $email_body .= "เหตุผล: {$revision_reason}\n\n";
            $email_body .= "กรุณาเข้าสู่ระบบเพื่อตรวจสอบเอกสาร\n";

            sendEmail($doc['sender_email'], $email_subject, $email_body);

            // Commit transaction
            $conn->commit();

            if (isset($_SESSION['role']) && $_SESSION['role'] == 'staff') {
                header("Location: inbox.php?id=$submission_id&status=revision_success");
                exit();
            } else {
                header("Location: inbox_lect.php?id=$submission_id&status=revision_success");
                exit();
            }
        } else {
            throw new Exception("ไม่พบข้อมูลเอกสาร");
        }
    } catch (Exception $e) {
        $conn->rollback();
        $response = [
            'status' => 'error',
            'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()
        ];
    }

    // ส่ง JSON response
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}
