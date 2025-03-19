<?php
session_start();
include '../../config/func.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login/login.php");
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.bootstrap5.css">

    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.datatables.net/2.2.2/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.2.2/js/dataTables.bootstrap5.js"></script>
    <script>
        $(document).ready(function() {
            $('#listSendTable').DataTable({
                "order": [
                    [2, "desc"]
                ],
                "columnDefs": [{
                    "type": "date",
                    "targets": 2,
                    "orderable": false,
                    "targets": 4
                }],
                "language": {
                    "search": "ค้นหา:",
                    "lengthMenu": "แสดง _MENU_ รายการ",
                    "info": "แสดง _START_ ถึง _END_ (จาก _TOTAL_ รายการ)",
                    "paginate": {
                        "first": "หน้าแรก",
                        "last": "หน้าสุดท้าย",
                        "next": "ถัดไป",
                        "previous": "ก่อนหน้า"
                    },
                    "emptyTable": "ไม่มีเอกสารที่เสร็จสิ้น"
                }
            });
        });
    </script>

    <title>เอกสาร/แบบฟอร์ม</title>
</head>

<body style="background-color: #F5F5F5;">
    <!-- Header -->
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

    <!-- Sidebar ด้านซ้าย -->
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

    <!-- content -->
    <section id="container">
        <div class="content-header">
            <div>
                <a class='bf' onclick="history.back();"><i class='fas fa-angle-left'></i>&nbsp;&nbsp;ย้อนกลับ</a>
                <h1>เอกสารรอดำเนินการ</h1>
            </div>
        </div>

        <!-- Table -->
        <div class="content shadow-sm p-3 mb-5 rounded">
            <table id="listSendTable" class="table table-hover mb-3">
                <thead>
                    <tr>
                        <th colspan="2" style="width: 45%;">เรื่อง</th>
                        <th style="width: 20%; text-align: left;">วันที่แก้ไขล่าสุด</th>
                        <th style="width: 25%;">ผู้รับ</th>
                        <th style="width: 10%;" class="text-center">สถานะ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $result = fetch_completed($conn, $user_id);
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td><img src='../../assets/img/draft.png' width='17px'></td>";
                            echo "<td style='width: 45%;'>";
                            if (!empty($row['name'])) {
                                echo "<a href='../inbox/viewinbox.php?submission_id=" . $row['submission_id'] . "'>" . $row['name'] . "</a>";
                            } else {
                                echo "<a href='../inbox/viewinbox.php?submission_id=" . $row['submission_id'] . "'>ไม่มีชื่อเรื่อง</a>";
                            }
                            if ($row['urgency'] == 1) {
                                echo "&nbsp;&nbsp;<span class='badge bg-danger'>ด่วน</span> ";
                            }
                            echo "<div class='form-text'>รหัสอ้งอิง: " . $row['document_code'] . "</div></td>";
                            echo "<td style='width: 20%; text-align: left;'>" . date("d/m/Y H:i", strtotime($row['updated_at'])) . "</td>";
                            echo "<td style='width: 25%;'>" . $row['firstname'] . " " . $row['lastname'] . "</td>";
                            echo "<td style='width: 10%;' class='text-center'><h5 class='text-center'><span class='badge rounded-pill text-bg-primary'>เสร็จสิ้น</span></h5></td>";
                            echo "</tr>";
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </section>

    <script src="../../assets/js/script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>