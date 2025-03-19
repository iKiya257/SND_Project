<?php
session_start();
include '../../config/func.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: pages/login/login.php");
    exit();
}
$user_id = $_SESSION['user_id'];
$data = fetch_user($conn, $user_id);
// ดึงข้อมูลโฟลเดอร์เริ่มต้น
$result = fetch_folder($conn);
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
            $('#listfolderTable').DataTable({
                "columnDefs": [{
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
                    "emptyTable": "ไม่มีข้อมูลโฟลเดอร์"
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
            <a class="navbar-brand" href="../dashboard/staff.php"><img src="../../assets/img/logo-snd.png" alt="" width="200px"></a>
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
                            <li><a class="dropdown-item" href="../../templates/staff/profile.php">ข้อมูลส่วนตัว</a></li>
                            <li><a class="dropdown-item" href="../../templates/staff/changePassword.php">แก้ไขรหัสผ่าน</a></li>
                            <li><a id="exit" class="dropdown-item" href="../../pages/login/logout.php">ออกจากระบบ</a></li>
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
                        <p>Documents</p>
                    </div>
                    <ul>
                        <li><a href="../dashboard/staff.php">
                                <div><i class="fa-solid fa-house"></i> หน้าแรก</div>
                            </a></li>
                        <li><a href="../form/folder-staff.php">
                                <div><i class="fa-solid fa-folder"></i> เอกสาร/แบบฟอร์ม</div>
                            </a></li>
                        <li><a href="../inbox/inbox.php">
                                <div><i class="fa-solid fa-box-archive"></i> เอกสารเข้า</div>
                            </a></li>
                        <li><a href="../submission/send.php">
                                <div><i class="fa-solid fa-paper-plane"></i> เอกสารส่ง</div>
                            </a></li>
                    </ul>
                </div>
                <div class="analyst">
                    <div class="title-sidebar">
                        <p>Analyst</p>
                    </div>
                    <ul>
                        <li><a href="../analyst/analyst-staff.php">
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
            <h1>เอกสาร/แบบฟอร์ม</h1>
            <div>
                <!-- Button trigger modal -->
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                    <i class="fa-solid fa-plus"></i>&nbsp; สร้างโฟลเดอร์
                </button>

                <!-- Modal pop up -->
                <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="staticBackdropLabel">สร้างโฟลเดอร์</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form action="folder_db.php" method="POST">
                                <div class="modal-body">
                                    <div class="addfolder">
                                        <label>ชื่อ</label>
                                        <input type="text" class="form-control" name="folder_name" placeholder="ระบุชื่อโฟลเดอร์">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary col-auto" data-bs-dismiss="modal">ยกเลิก</button>
                                    <button type="submit" class="btn btn-primary col-auto ms-auto">สร้าง</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="content shadow-sm p-3 mb-5 rounded">
            <table id="listfolderTable" class="table table-hover mb-3">
                <thead>
                    <tr>
                        <th colspan="2" style="width: 45%;">ชื่อ</th>
                        <th style="width: 20%; text-align: left;">แก้ไขล่าสุด</th>
                        <th style="width: 30%;">แก้ไขโดย</th>
                        <th style="width: 5%;"></th>
                    </tr>
                </thead>
                <tbody id="folderTable">
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td><img src='../../assets/img/folder.png' width='17px'></td>";
                            echo "<td style='width: 45%;'><a href='file_staff.php?folder_id=" . $row['folder_id'] . "'>" . $row['folder_name'] . "</a></td>";
                            echo "<td style='width: 20%; text-align: left;'>" . date("d/m/Y H:i", strtotime($row['updated_at'])) . "</td>";
                            echo "<td style='width: 30%;'>" . $row['prefix'] . " " . $row['firstname'] . " " . $row['lastname'] . "</td>";
                            echo "<td style='width: 5%;'><a class='link-dark' href='#' data-bs-toggle='dropdown' aria-expanded='false'><i class='fas fa-ellipsis-h '></i></a>
                                    <ul class='dropdown-menu'>
                                        <li><a id='exit' class='dropdown-item' href='folder_del.php?folder_id=" . $row['folder_id'] . "' onclick='return confirm(\"คุณต้องการลบโฟลเดอร์นี้หรือไม่?\")'>ลบ</a></li>
                                    </ul></td>";
                            echo "</tr>";
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="../../assets/js/script.js"></script>
</body>

</html>