<?php
session_start();
include '../../config/func.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../pages/login/login.php");
    exit();
}
$user_id = $_SESSION['user_id'];
$data = fetch_user($conn, $user_id);

$result = fetch_pending($conn, $user_id);
$countpending = $result->num_rows; // นับจำนวนแถว

$result = fetch_completed($conn, $user_id);
$countcompleted = $result->num_rows; // นับจำนวนแถว

$result = fetch_revision($conn, $user_id);
$countrevision = $result->num_rows; // นับจำนวนแถว
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../../assets/img/logo-icon.png" rel="icon">
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                events: '../manage/fetch_events.php' // โหลดเหตุการณ์จาก PHP
            });
            calendar.render();
        });
    </script>
    <title>หน้าแรก</title>

</head>

<body style="background-color: #F5F5F5;">
    <nav id="header" class="navbar navbar-expand-lg bg-body fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="lecturer.php"><img src="../../assets/img/logo-snd.png" alt="" width="200px"></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto">
                    <?php include '../../components/notification_component.php'; ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <?php echo isset($data) ? $data['prefix'] . " " . $data['firstname'] . " " . $data['lastname'] : "ไม่พบข้อมูล"; ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-lg-end">
                            <li><a class="dropdown-item" href="../../templates/lecturer/profile.php">ข้อมูลส่วนตัว</a></li>
                            <li><a class="dropdown-item" href="../../templates/lecturer/changePassword.php">แก้ไขรหัสผ่าน</a></li>
                            <li><a id="exit" class="dropdown-item" href="../login/logout.php">ออกจากระบบ</a></li>
                        </ul>
                    </li>

                </ul>
            </div>
        </div>
    </nav>

    <section id="sidebar">
        <div class="sidebar-dash">
            <div class="list-dash">
                <div class="dash">
                    <div class="title-sidebar">
                        <p>Documents
                        <p>
                    </div>
                    <ul>
                        <li><a href="lecturer.php">
                                <div><i class="fa-solid fa-house"></i> หน้าแรก</div>
                            </a></li>
                        <li><a href="../form/folder-lect.php">
                                <div><i class="fa-solid fa-folder"></i> เอกสาร/แบบฟอร์ม</div>
                            </a></li>
                        <li><a href="../inbox/inbox_lect.php">
                                <div><i class="fa-solid fa-box-archive"></i> เอกสารเข้า</div>
                            </a></li>
                        <li><a href="../submission/send_lect.php">
                                <div><i class="fa-solid fa-paper-plane"></i> เอกสารส่ง</div>
                            </a></li>
                    </ul>
                </div>
                <div class="analyst">
                    <div class="title-sidebar">
                        <p>Analyst
                        <p>
                    </div>
                    <ul>
                        <li><a href="../analyst/analyst-lect.php">
                                <div><i class="fa-solid fa-arrow-trend-up"></i> วิเคราะห์รายงาน</div>
                            </a></li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <section id="container">
        <div class="content-header">
            <h1>Quick Access</h1>
        </div>
        <div class="content-access shadow-sm p-3 mb-5 rounded">
        <div class="row">
                <div class="col-auto">
                    <div class="card sales-card pink" id="card">
                        <a href="pending_lect.php">
                            <div class="metric-card" id="card-body">
                                <div class="icon-circle pink-icon">
                                    <i class="fas fa-chart-bar"></i>
                                </div>
                                <div class="metric-label">เอกสารรอดำเนินการ</div>
                                <div class="metric-value"><?php echo $countpending; ?></div>
                                <div class="metric-change">รายการ</div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-auto">
                    <div class="card sales-card yellow" id="card">
                        <a href="revision_lect.php">
                            <div class="metric-card" id="card-body">
                                <div class="icon-circle yellow-icon">
                                    <i class="fa-solid fa-pen"></i>
                                </div>
                                <div class="metric-label">เอกสารที่ต้องแก้ไข</div>
                                <div class="metric-value"><?php echo $countrevision; ?></div>
                                <div class="metric-change">รายการ</div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-auto">
                    <div class="card sales-card green" id="card">
                        <a href="completed_lect.php">
                            <div class="metric-card" id="card-body">
                                <div class="icon-circle green-icon">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div class="metric-label">เอกสารที่ดำเนินการเสร็จสิ้น</div>
                                <div class="metric-value"><?php echo $countcompleted; ?></div>
                                <div class="metric-change">รายการ</div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-header">
            <h1>ปฏิทินกิจกรรม</h1>
        </div>
        <div class="content-calenda shadow-sm p-3 mb-5 rounded">
            <div id="calendar"></div>
        </div>
        <!-- Modal แสดงรายละเอียด Event -->
        <div class="modal fade" id="eventDetailsModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">รายละเอียด Event</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p><strong>เรื่อง:</strong> <span id="eventTitle"></span></p>
                        <p><strong>เริ่ม:</strong> <span id="eventStart"></span></p>
                        <p><strong>สิ้นสุด:</strong> <span id="eventEnd"></span></p>
                        <p><strong>หมายเหตุ:</strong> <span id="eventDescription"></span></p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="../../assets/js/script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function() {
            $('.notification-item').click(function(e) {
                const notificationId = $(this).data('notification-id');

                // ส่ง request ไปอัพเดทสถานะการอ่าน
                $.ajax({
                    url: '../inbox/mark_notification_read.php',
                    type: 'POST',
                    data: {
                        notification_id: notificationId
                    },
                    success: function(response) {
                        try {
                            const result = JSON.parse(response);
                            if (result.success) {
                                // อัพเดทจำนวนการแจ้งเตือน
                                const $badge = $('.badge.rounded-pill.bg-danger');
                                const currentCount = parseInt($badge.text());
                                if (currentCount > 1) {
                                    $badge.text(currentCount - 1);
                                } else {
                                    $badge.remove();
                                }
                            }
                        } catch (e) {
                            console.error('Error parsing response:', e);
                        }
                    }
                });
            });
        });
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');

            var calendar = new FullCalendar.Calendar(calendarEl, {
                locale: 'th',
                initialView: 'dayGridMonth',
                events: '../manage/fetch_events.php',
                eventContent: function(info) {
                    return {
                        html: `<div style="background-color: ${info.event.backgroundColor}; padding: 4px; border-radius: 5px;">${info.event.title}</div>`
                    };
                },
                eventClick: function(info) {
                    $('#eventTitle').text(info.event.title);
                    $('#eventStart').text(info.event.start.toLocaleString());
                    $('#eventEnd').text(info.event.end ? info.event.end.toLocaleString() : '');
                    $('#eventDescription').text(info.event.extendedProps.description || 'ไม่มีข้อมูล');
                    $('#eventDetailsModal').modal('show');
                }
            });

            calendar.render();

            $('#addEventForm').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    url: '../manage/add_event.php',
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function() {
                        calendar.refetchEvents();
                        $('#addEventModal').modal('hide');
                        $('#addEventForm')[0].reset();
                    }
                });
            });
        });
    </script>
</body>

</html>