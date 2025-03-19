<?php
session_start();
include '../../config/func.php';

if (!isset($_SESSION['email'])) {
    header("Location: ../../pages/login/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$data = fetch_user($conn, $user_id);
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
                events: 'fetch_events.php' // โหลดเหตุการณ์จาก PHP
            });
            calendar.render();
        });
    </script>
    <title>หน้าแรก</title>

</head>

<body style="background-color: #F5F5F5;">
    <nav id="header" class="navbar navbar-expand-lg bg-body fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="admin.php"><img src="../../assets/img/logo-snd.png" alt="" width="200px"></a>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <?php echo isset($data) ? $data['firstname'] . " " . $data['lastname'] : "ไม่พบข้อมูล"; ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-lg-end">
                            <li><a class="dropdown-item" href="../../templates/admin/changePassword.php">แก้ไขรหัสผ่าน</a></li>
                            <li><a class="dropdown-item" href="../../pages/login/logout.php">ออกจากระบบ</a></li>

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
                        <p>หน้าแรก</p>
                    </div>
                    <ul>
                        <li><a href="../dashboard/admin.php">
                                <div><i class="fa-solid fa-house"></i>&nbsp;&nbsp; หน้าแรก</div>
                            </a></li>
                    </ul>
                    <div class="title-sidebar">
                        <p>เกี่ยวกับผู้ใช้งาน</p>
                    </div>
                    <ul>
                        <li><a href="user.php">
                                <div><i class="fa-solid fa-user-gear"></i>&nbsp;&nbsp; การจัดการผู้ใช้</div>
                            </a></li>
                        <li><a href="department.php">
                                <div><i class="fa-solid fa-users-line"></i>&nbsp;&nbsp; สังกัดกลุ่มวิชา</div>
                            </a></li>
                    </ul>
                    <div class="title-sidebar">
                        <p>การจัดการเอกสาร</p>
                    </div>
                    <ul>
                        <li><a href="purpose.php">
                                <div><i class="fa-solid fa-gear"></i>&nbsp;&nbsp; จัดการวัตถุประสงค์</div>
                            </a></li>
                    </ul>
                    <div class="title-sidebar">
                        <p>การจัดการปฏิทิน</p>
                    </div>
                    <ul>
                        <li><a href="calendar.php">
                                <div><i class="fa-solid fa-calendar-days"></i>&nbsp;&nbsp; จัดการปฏิทิน</div>
                            </a></li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <section id="container">
        <div class="content-header">
            <h2>จัดการปฏิทิน</h2>
            <div>
                <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addEventModal">เพิ่ม Event</button>
            </div>
        </div>
        <div class="content shadow-sm p-3 mb-5 rounded">
            <div id="calendar"></div>
        </div>
        <!-- Modal เพิ่ม Event -->
        <div class="modal fade" id="addEventModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">เพิ่ม Event ใหม่</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form id="addEventForm">
                            <div class="mb-3">
                                <label class="form-label">เรื่อง</label>
                                <input type="text" class="form-control" name="title" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">วันที่เริ่ม</label>
                                <input type="datetime-local" class="form-control" name="start" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">วันที่สิ้นสุด</label>
                                <input type="datetime-local" class="form-control" name="end" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">หมายเหตุ</label>
                                <textarea class="form-control" name="description"></textarea>
                            </div>
                            <button type="submit" class="btn btn-success">บันทึก</button>
                        </form>
                    </div>
                </div>
            </div>
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
                    <div class="modal-footer">
                        <button class="btn btn-danger" id="deleteEventBtn">ลบ Event</button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');

            var calendar = new FullCalendar.Calendar(calendarEl, {
                locale: 'th',
                initialView: 'dayGridMonth',
                events: 'fetch_events.php',
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

                    $('#deleteEventBtn').off('click').on('click', function() {
                        if (confirm('คุณต้องการลบ Event นี้หรือไม่?')) {
                            $.ajax({
                                url: 'delete_event.php',
                                type: 'POST',
                                data: {
                                    id: info.event.id
                                },
                                success: function() {
                                    calendar.refetchEvents();
                                    $('#eventDetailsModal').modal('hide');
                                }
                            });
                        }
                    });
                }
            });

            calendar.render();

            $('#addEventForm').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    url: 'add_event.php',
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>