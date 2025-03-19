<?php
function getNotifications($conn, $user_id)
{
    // ปรับ query ให้ดึง submission_id ด้วย
    $notifications_query = "SELECT n.*, ds.submission_id 
                          FROM notifications n
                          LEFT JOIN document_submission ds ON n.submission_id = ds.submission_id
                          WHERE n.user_id = ? AND n.is_read = 0 
                          ORDER BY n.created_at DESC LIMIT 5";
    $stmt = $conn->prepare($notifications_query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $notifications = $stmt->get_result();
    return $notifications;
}
?>