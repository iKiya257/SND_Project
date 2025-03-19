<?php
session_start();
include '../../config/func.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../pages/login/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$data = fetch_user($conn, $user_id);

// การดึงข้อมูลการดาวน์โหลด
$sql = "SELECT d.template_id, t.template_name, COUNT(d.id) as download_count 
        FROM download_logs d 
        LEFT JOIN templates t ON d.template_id = t.template_id
        GROUP BY d.template_id, t.template_name";
$result = mysqli_query($conn, $sql);
$download_data = array();
while ($row = mysqli_fetch_assoc($result)) {
    $download_data[] = $row;
}

// ดาวน์โหลดรายวัน
$sql2 = "SELECT DATE(download_time) as date, COUNT(*) as count 
         FROM download_logs 
         GROUP BY DATE(download_time)
         ORDER BY date DESC LIMIT 7";
$result2 = mysqli_query($conn, $sql2);
$daily_downloads = array();
while ($row = mysqli_fetch_assoc($result2)) {
    $daily_downloads[] = $row;
}

// ดึงข้อมูลการส่งเอกสารรายวัน
$sql3 = "SELECT DATE(created_at) as date, COUNT(submission_id) as count
         FROM document_submission 
         GROUP BY DATE(created_at)
         ORDER BY date DESC LIMIT 7";
$result3 = mysqli_query($conn, $sql3);
$daily_submissions = array();
while ($row = mysqli_fetch_assoc($result3)) {
    $daily_submissions[] = $row;
}

//  ดึงข้อมูลสถานะเอกสาร
$sql4 = "SELECT 
            CASE status
                WHEN 'pending' THEN 'รอดำเนินการ'
                WHEN 'read' THEN 'อ่านแล้ว'
                WHEN 'removed' THEN 'ยกเลิกแล้ว'
                WHEN 'revision' THEN 'ส่งกลับแก้ไข'
                WHEN 'completed' THEN 'เสร็จสิ้น'
                ELSE status
            END as status_name,
            COUNT(*) as count 
         FROM document_recipient 
         GROUP BY status";
$result4 = mysqli_query($conn, $sql4);
$status_data = array();
while ($row = mysqli_fetch_assoc($result4)) {
    $status_data[] = $row;
}
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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .chart-container {
            width: 80%;
            margin: 0 auto;
        }
    </style>
    <title>วิเคราะห์รายงาน</title>
</head>

<body style="background-color: #F5F5F5;">
    <nav id="header" class="navbar navbar-expand-lg bg-body fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="#"><img src="../../assets/img/logo-snd.png" alt="" width="200px"></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto">
                <?php include '../../components/notification_component.php'; ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
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
                        <li><a href="../dashboard/lecturer.php">
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
                        <li><a href="analyst-lect.php">
                                <div><i class="fa-solid fa-arrow-trend-up"></i> วิเคราะห์รายงาน</div>
                            </a></li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <section id="container">
        <div class="content-header">
            <h2>รายงานสถิติ</h2>
        </div>
        <div class="content shadow-sm p-3 mb-5 rounded">
            <div class="text-center">
                <div class="btn-group mb-3" role="group">
                    <button type="button" class="btn btn-primary active" onclick="showChart('downloadByFile')">สถิติแยกตามไฟล์</button>
                    <button type="button" class="btn btn-primary" onclick="showChart('downloadByDay')">สถิติดาวน์โหลดรายวัน</button>
                    <button type="button" class="btn btn-primary" onclick="showChart('submissionByDay')">สถิติการส่งเอกสารรายวัน</button>
                    <button type="button" class="btn btn-primary" onclick="showChart('documentStatus')">สถานะเอกสารในระบบ</button>
                </div>
            </div>
            <div>
                <div class="chart-container">
                    <canvas id="downloadByFileChart" style="display: block;"></canvas>
                    <canvas id="downloadByDayChart" style="display: none;"></canvas>
                    <canvas id="submissionByDayChart" style="display: none;"></canvas>
                    <canvas id="documentStatusChart" style="display: none;"></canvas>
                </div>
            </div>
        </div>
    </section>

    <script src="../../assets/js/script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // ข้อมูลสำหรับกราฟแรก (ดาวน์โหลดตามไฟล์)
        const downloadData = <?php echo json_encode($download_data); ?>;
        const dailyData = <?php echo json_encode($daily_downloads); ?>;

        // เรียงลำดับข้อมูลและเลือกเฉพาะ 5 อันดับแรกที่มีการดาวน์โหลดมากที่สุด
        const topDownloads = [...downloadData].sort((a, b) => b.download_count - a.download_count).slice(0, 5);

        // สร้างกราฟแรก (ดาวน์โหลดตามไฟล์)
        const ctx1 = document.getElementById('downloadByFileChart').getContext('2d');
        const downloadByFileChart = new Chart(ctx1, {
            type: 'bar',
            data: {
                labels: topDownloads.map(item => item.template_name),
                datasets: [{
                    label: 'จำนวนการดาวน์โหลด',
                    data: topDownloads.map(item => item.download_count),
                    backgroundColor: 'rgba(242, 107, 15)',
                    borderColor: 'rgba(242, 107, 15)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                plugins: {
                    title: {
                        display: true,
                        text: '5 อันดับไฟล์ที่มีการดาวน์โหลดมากที่สุด',
                        font: {
                            size: 16
                        }
                    }
                }
            }
        });

        // สร้างกราฟที่สอง (ดาวน์โหลดรายวัน)
        const ctx2 = document.getElementById('downloadByDayChart').getContext('2d');
        const downloadByDayChart = new Chart(ctx2, {
            type: 'line',
            data: {
                labels: dailyData.map(item => item.date),
                datasets: [{
                    label: 'จำนวนการดาวน์โหลดต่อวัน',
                    data: dailyData.map(item => item.count),
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1,
                    tension: 0.1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // เพิ่มข้อมูลสำหรับกราฟการส่งเอกสาร
        const submissionData = <?php echo json_encode($daily_submissions); ?>;

        // สร้างกราฟการส่งเอกสารรายวัน
        const ctx3 = document.getElementById('submissionByDayChart').getContext('2d');
        const submissionByDayChart = new Chart(ctx3, {
            type: 'line',
            data: {
                labels: submissionData.map(item => item.date),
                datasets: [{
                    label: 'จำนวนการส่งเอกสารต่อวัน',
                    data: submissionData.map(item => item.count),
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1,
                    tension: 0.1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                },
                plugins: {
                    title: {
                        display: true,
                        text: 'สถิติการส่งเอกสารประจำวัน'
                    }
                }
            }
        });

        // เพิ่มข้อมูลสำหรับกราฟสถานะเอกสาร
        const statusData = <?php echo json_encode($status_data); ?>;

        // สร้างกราฟวงกลมแสดงสถานะเอกสาร
        const ctx4 = document.getElementById('documentStatusChart').getContext('2d');
        const documentStatusChart = new Chart(ctx4, {
            type: 'doughnut',
            data: {
                labels: statusData.map(item => item.status_name),
                datasets: [{
                    data: statusData.map(item => item.count),
                    backgroundColor: [
                        'rgba(242, 107, 15, 0.8)', // สีส้ม - รอดำเนินการ
                        'rgba(54, 162, 235, 0.8)', // สีฟ้า - อ่านแล้ว
                        'rgba(255, 99, 132, 0.8)', // สีแดง - ลบแล้ว
                        'rgba(255, 206, 86, 0.8)', // สีเหลือง - ส่งกลับแก้ไข
                    ],
                    borderColor: [
                        'rgba(242, 107, 15, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 99, 132, 1)',
                        'rgba(255, 206, 86, 1)',
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'สถานะเอกสารทั้งหมดในระบบ',
                        font: {
                            size: 16
                        }
                    }
                }
            }
        });

        // ฟังก์ชันสำหรับสลับการแสดงกราฟ
        function showChart(chartId) {
            // ซ่อนทุกกราฟ
            document.getElementById('downloadByFileChart').style.display = 'none';
            document.getElementById('downloadByDayChart').style.display = 'none';
            document.getElementById('submissionByDayChart').style.display = 'none';
            document.getElementById('documentStatusChart').style.display = 'none';

            // แสดงกราฟที่เลือก
            if (chartId === 'downloadByFile') {
                document.getElementById('downloadByFileChart').style.display = 'block';
            } else if (chartId === 'downloadByDay') {
                document.getElementById('downloadByDayChart').style.display = 'block';
            } else if (chartId === 'submissionByDay') {
                document.getElementById('submissionByDayChart').style.display = 'block';
            } else if (chartId === 'documentStatus') {
                document.getElementById('documentStatusChart').style.display = 'block';
            }

            // อัพเดทสถานะปุ่ม
            document.querySelectorAll('.btn-group .btn').forEach(btn => {
                btn.classList.remove('active');
            });
            event.target.classList.add('active');
        }

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
    </script>
</body>

</html>