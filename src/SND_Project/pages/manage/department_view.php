<?php
session_start();
include '../../config/func.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../pages/login/login.php");
    exit();
}

if (isset($_GET['id'])) {
    $department_id = intval($_GET['id']);

    // ดึงข้อมูล department_name จากฐานข้อมูล
    $sql = "SELECT department_name FROM departments WHERE department_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $department_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $department = $result->fetch_assoc();
    } else {
        $department = ['department_name' => 'ไม่พบข้อมูล'];
    }
    $stmt->close();
} else {
    $department = ['department_name' => 'ไม่มีข้อมูล'];
}

$user_id = intval($_GET['id']);
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
            $('#listTable').DataTable({
                "order": 1,
                "columnDefs": [{
                        "orderable": false,
                        "targets": 0
                    }
                ],
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
                    "emptyTable": "ไม่มีรายการบุคลากร"
                }
            });
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
                    <?php include '../../components/notification_component.php'; ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="<?php echo $profile_image; ?>" class="rounded-circle" width="30" height="30">
                            <?php echo isset($data) ? $data['firstname'] . " " . $data['lastname'] : "ไม่พบข้อมูล"; ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-lg-end">
                            <li><a class="dropdown-item" href="../../templates/admin/changePassword.php">แก้ไขรหัสผ่าน</a></li>
                            <li><a id="exit" class="dropdown-item" href="../../pages/login/logout.php">ออกจากระบบ</a></li>
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
        <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="department.php" class='hv' onclick="history.back();">ข้อมูลสังกัดกลุ่มวิชา</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?php echo $department['department_name']; ?></li>
            </ol>
        </div>
       
        <!-- Table -->
        <div class="content shadow-sm p-3 mb-5 rounded">
            <table id="listTable" class="table table-hover mb-3">
                <thead>
                    <tr>
                        <th>#</th>
                        <th style="width: 60%;">ชื่อ</th>
                        <th style="width: 40%;">ตำแหน่ง</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    $result = fetch_user_department($conn);
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $i++ . "</td>";
                            echo "<td>" . $row['firstname'] . " " . $row['lastname'] . "</td>";
                            echo "<td>";
                            if (!empty($row['position'])) {
                                echo  $row['position'];
                            } else {
                                echo "-";
                            }
                            echo "</td>";
                            echo "</tr>";
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>