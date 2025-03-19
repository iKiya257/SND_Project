<?php
// ไม่ต้องประกาศฟังก์ชัน getNotifications() อีก
// เพราะถูกเรียกใช้จาก notification_functions.php แล้ว
?>

<li class="nav-item dropdown">
    <a class="nav-link position-relative" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
        <img src="../../assets/img/notification.png" width="20px">
        <?php
        $notifications = getNotifications($conn, $_SESSION['user_id']);
        $unread_count = $notifications->num_rows;
        if ($unread_count > 0):
        ?>
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger notification-badge">
                <?php echo $unread_count; ?>
            </span>
        <?php endif; ?>
    </a>
    <ul class="dropdown-menu dropdown-menu-end notification-dropdown">
        <li class="notification-header">การแจ้งเตือน</li>
        <?php if ($notifications->num_rows > 0): ?>
            <?php while ($notification = $notifications->fetch_assoc()): ?>
                <li>
                    <a class="dropdown-item notification-item" 
                       href="javascript:void(0);" 
                       onclick="window.location.href='http://localhost:8080/pages/inbox/viewinbox.php?submission_id=<?php echo $notification['submission_id']; ?>'"
                       data-notification-id="<?php echo $notification['id']; ?>">
                        <div class="notification-content">
                            <small><?php echo date('d/m/Y H:i', strtotime($notification['created_at'])); ?></small>
                            <p class="mb-0"><?php echo $notification['message']; ?></p>
                        </div>
                    </a>
                </li>
            <?php endwhile; ?>
        <?php else: ?>
            <li class="no-notifications">ไม่มีการแจ้งเตือนใหม่</li>
        <?php endif; ?>
    </ul>
</li>