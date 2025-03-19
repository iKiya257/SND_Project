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
function sendEmail($toEmail, $subject, $body) {
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

// เพิ่มฟังก์ชัน createNotification ไว้ด้านบนของไฟล์ หลังจาก include
function createNotification($conn, $user_id, $message, $submission_id) {
    $insert_query = "INSERT INTO notifications (user_id, message, submission_id, created_at, is_read) 
                     VALUES (?, ?, ?, NOW(), 0)";
    $stmt = $conn->prepare($insert_query);
    $stmt->bind_param("isi", $user_id, $message, $submission_id);
    return $stmt->execute();
}

$user_id = $_SESSION['user_id']; 
$ref = generateDocCode($conn);
$uploadDir = "../../uploads/";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // เริ่ม transaction
    $conn->begin_transaction();

    try {
        $name = $conn->real_escape_string($_POST['name']);
        $remark = $conn->real_escape_string($_POST['remark']);
        $purpose_id = intval($_POST['purpose_id']);
        $urgent = isset($_POST['urgent']) ? 1 : 0;
        $refcode = $conn->real_escape_string($_POST['ref_code']);

        // 1. บันทึกเอกสาร
        $stmt = $conn->prepare("INSERT INTO document_submission (document_code, status, name, remark, purpose_id, sender_id, urgency, previous_refcode) VALUES (?, 'submitted', ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssiiis", $ref, $name, $remark, $purpose_id, $user_id, $urgent, $refcode);

        if ($stmt->execute()) {
            $doc_id = $stmt->insert_id;
            $submission_id = $stmt->insert_id;
            $stmt->close();

            // 2. บันทึกผู้รับเอกสารและส่งการแจ้งเตือน
            if (!empty($_POST['user_ids']) || !empty($_POST['dept_ids'])) {
                // ดึงข้อมูลผู้ส่ง
                $stmt_sender = $conn->prepare("SELECT firstname, lastname FROM users WHERE user_id = ?");
                $stmt_sender->bind_param("i", $user_id);
                $stmt_sender->execute();
                $sender = $stmt_sender->get_result()->fetch_assoc();

                // สำหรับผู้รับที่เป็นบุคคล
                if (!empty($_POST['user_ids'])) {
                    foreach ($_POST['user_ids'] as $recipient_user_id) {
                        if (!empty($recipient_user_id)) {
                            // บันทึกผู้รับ
                            $stmt_recipient = $conn->prepare("INSERT INTO document_recipient (submission_id, receiver_id, department_id) VALUES (?, ?, NULL)");
                            $stmt_recipient->bind_param("ii", $doc_id, $recipient_user_id);
                            $stmt_recipient->execute();

                            // ดึงข้อมูลผู้รับ
                            $stmt_receiver = $conn->prepare("SELECT email, firstname, lastname FROM users WHERE user_id = ?");
                            $stmt_receiver->bind_param("i", $recipient_user_id);
                            $stmt_receiver->execute();
                            $receiver = $stmt_receiver->get_result()->fetch_assoc();

                            // สร้างข้อความแจ้งเตือน
                            $notification_message = "คุณได้รับเอกสารใหม่: {$name} จาก {$sender['firstname']} {$sender['lastname']}";
                            
                            // ใช้ฟังก์ชัน createNotification
                            if (createNotification($conn, $recipient_user_id, $notification_message, $submission_id)) {
                                // บันทึกสำเร็จ
                                error_log("Notification created successfully for user: $recipient_user_id, submission: $submission_id");
                            } else {
                                // บันทึกไม่สำเร็จ
                                error_log("Failed to create notification for user: $recipient_user_id, submission: $submission_id");
                            }

                            // ส่งอีเมลแจ้งเตือน
                            $email_subject = "แจ้งเตือน: มีเอกสารใหม่";
                            $email_body = "เรียน คุณ{$receiver['firstname']} {$receiver['lastname']}\n\n";
                            $email_body .= "คุณได้รับเอกสารใหม่จากระบบ:\n";
                            $email_body .= "รหัสเอกสาร: {$ref}\n";
                            $email_body .= "ชื่อเอกสาร: {$name}\n";
                            $email_body .= "ผู้ส่ง: {$sender['firstname']} {$sender['lastname']}\n";
                            $email_body .= "กรุณาเข้าสู่ระบบเพื่อตรวจสอบเอกสาร\n";

                            sendEmail($receiver['email'], $email_subject, $email_body);
                        }
                    }
                }

                // สำหรับผู้รับที่เป็นแผนก
                if (!empty($_POST['dept_ids'])) {
                    foreach ($_POST['dept_ids'] as $dept_id) {
                        if (!empty($dept_id)) {
                            $stmt_recipient = $conn->prepare("INSERT INTO document_recipient (submission_id, receiver_id, department_id) VALUES (?, NULL, ?)");
                            $stmt_recipient->bind_param("ii", $doc_id, $dept_id);
                            $stmt_recipient->execute();

                            // ดึงชื่อแผนก
                            $stmt_dept_name = $conn->prepare("SELECT department_name FROM departments WHERE department_id = ?");
                            $stmt_dept_name->bind_param("i", $dept_id);
                            $stmt_dept_name->execute();
                            $dept_result = $stmt_dept_name->get_result();
                            $dept_name = "";
                            if ($dept_row = $dept_result->fetch_assoc()) {
                                $dept_name = $dept_row['department_name'];
                            }

                            // ดึงข้อมูลผู้ใช้ทั้งหมดในแผนก
                            $stmt_dept_users = $conn->prepare("SELECT u.user_id, u.email, u.firstname, u.lastname 
                                                              FROM users u 
                                                              JOIN user_department ud ON u.user_id = ud.user_id
                                                              WHERE ud.department_id = ?");
                            $stmt_dept_users->bind_param("i", $dept_id);
                            $stmt_dept_users->execute();
                            $result_dept_users = $stmt_dept_users->get_result();
                            
                            while ($dept_user = $result_dept_users->fetch_assoc()) {
                                // สร้างข้อความแจ้งเตือน
                                $notification_message = "คุณได้รับเอกสารใหม่: {$name} จาก {$sender['firstname']} {$sender['lastname']}";
                                if (!empty($dept_name)) {
                                    $notification_message .= " (ส่งถึงแผนก {$dept_name})";
                                }
                                
                                // ใช้ฟังก์ชัน createNotification
                                if (createNotification($conn, $dept_user['user_id'], $notification_message, $submission_id)) {
                                    // บันทึกสำเร็จ
                                    error_log("Notification created successfully for user: {$dept_user['user_id']} in department: $dept_id, submission: $submission_id");
                                } else {
                                    // บันทึกไม่สำเร็จ
                                    error_log("Failed to create notification for user: {$dept_user['user_id']} in department: $dept_id, submission: $submission_id");
                                }

                                // ส่งอีเมลแจ้งเตือน
                                $email_subject = "แจ้งเตือน: มีเอกสารใหม่";
                                if (!empty($dept_name)) {
                                    $email_subject .= " สำหรับแผนก {$dept_name}";
                                }
                                
                                $email_body = "เรียน คุณ{$dept_user['firstname']} {$dept_user['lastname']}\n\n";
                                $email_body .= "คุณได้รับเอกสารใหม่จากระบบ";
                                if (!empty($dept_name)) {
                                    $email_body .= " สำหรับแผนก {$dept_name}";
                                }
                                $email_body .= ":\n";
                                $email_body .= "รหัสเอกสาร: {$ref}\n";
                                $email_body .= "ชื่อเอกสาร: {$name}\n";
                                $email_body .= "ผู้ส่ง: {$sender['firstname']} {$sender['lastname']}\n";
                                $email_body .= "กรุณาเข้าสู่ระบบเพื่อตรวจสอบเอกสาร\n";

                                sendEmail($dept_user['email'], $email_subject, $email_body);
                            }
                        }
                    }
                }
            }

            // 3. อัปโหลดไฟล์เอกสาร
            if (isset($_FILES["files"])) {
                $files = $_FILES["files"];
                for ($i = 0; $i < count($files["name"]); $i++) {
                    if ($files["error"][$i] === UPLOAD_ERR_OK) {
                        $originalFileName = pathinfo($files["name"][$i], PATHINFO_FILENAME);
                        $extension = pathinfo($files["name"][$i], PATHINFO_EXTENSION);
                        $fileName = $originalFileName;
                        $counter = 1;
            
                        // ตรวจสอบว่ามีไฟล์ชื่อซ้ำหรือไม่
                        $filePath = $uploadDir . $fileName . ($extension ? "." . $extension : "");
                        while (file_exists($filePath)) {
                            $fileName = $originalFileName . " ($counter)";
                            $filePath = $uploadDir . $fileName . ($extension ? "." . $extension : "");
                            $counter++;
                        }
            
                        if (move_uploaded_file($files["tmp_name"][$i], $filePath)) {
                            $stmt = $conn->prepare("INSERT INTO document_files (submission_id, file_path) VALUES (?, ?)");
                            $stmt->bind_param("is", $submission_id, $filePath);
                            $stmt->execute();
                        }
                    }
                }
            }
            

            // Commit transaction
            $conn->commit();
            $_SESSION['success'] = "ส่งเอกสารสำเร็จ";
        }
    } catch (Exception $e) {
        // Rollback ในกรณีที่เกิดข้อผิดพลาด
        $conn->rollback();
        $_SESSION['error'] = "เกิดข้อผิดพลาด: " . $e->getMessage();
    }
}

if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] == 'staff') {
        header("Location: send.php");
    } else {
        header("Location: send_lect.php");
    }
    ob_end_flush();
    exit();
}
?>

