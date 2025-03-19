<?php
ob_start();
session_start();
include '../../config/func.php';
// ตรวจสอบการเข้าสู่ระบบ
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../pages/login/login.php");
    exit();
}
// รับค่า user_id จาก session
$user_id = $_SESSION['user_id'];
$data = fetch_user($conn, $user_id);

if (isset($_GET['folder_id'])) {
    $folder_id = intval($_GET['folder_id']);

    // ดึงข้อมูล department_name จากฐานข้อมูล
    $sql = "SELECT folder_name FROM folders WHERE folder_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $folder_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $folder = $result->fetch_assoc();
    } else {
        $folder = ['folder_name' => 'ไม่พบข้อมูล'];
    }
    $stmt->close();
} else {
    $folder = ['folder_name' => 'ไม่มีข้อมูล'];
}

// ตรวจสอบว่ามีการส่งข้อมูลจากฟอร์มหรือไม่
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $template_name = trim($_POST['template_name']);
    $folder_id = isset($_GET['folder_id']) ? intval($_GET['folder_id']) : 0;

    // ตรวจสอบว่าชื่อเอกสารว่างหรือไม่
    if (empty($template_name)) {
        echo "<script>alert('กรุณาระบุชื่อเอกสาร'); window.history.back();</script>";
        exit();
    }

    // ตรวจสอบการอัปโหลดไฟล์
    if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
        $file_tmp = $_FILES['file']['tmp_name'];
        $file_name = $_FILES['file']['name'];
        $file_size = $_FILES['file']['size'];
        $file_error = $_FILES['file']['error'];

        // ตรวจสอบนามสกุลไฟล์
        $allowed_extensions = ['doc', 'docx'];
        $file_extension = pathinfo($file_name, PATHINFO_EXTENSION);
        if (!in_array(strtolower($file_extension), $allowed_extensions)) {
            echo "<script>alert('รูปแบบไฟล์ไม่รองรับ'); window.history.back();</script>";
            exit();
        }

        // กำหนดชื่อไฟล์ใหม่เพื่อป้องกันชื่อซ้ำ
        $new_file_name = uniqid('file_', true) . '.' . $file_extension;
        $upload_path = '../../uploads/' . $new_file_name;

        // ย้ายไฟล์ไปยังตำแหน่งที่กำหนด
        if (move_uploaded_file($file_tmp, $upload_path)) {
            // บันทึกข้อมูลลงฐานข้อมูล
            $sql = "INSERT INTO templates (template_name, file_path, uploaded_by, folder_id) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssii", $template_name, $new_file_name, $user_id, $folder_id);

            if ($stmt->execute()) {
                header("Location: file_staff.php?folder_id=$folder_id");
                exit();
            } else {
                echo "<script>alert('เกิดข้อผิดพลาดในการบันทึกข้อมูล'); window.history.back();</script>";
                exit();
            }
        } else {
            echo "<script>alert('เกิดข้อผิดพลาดในการอัปโหลดไฟล์'); window.history.back();</script>";
            exit();
        }
    } else {
        echo "<script>alert('กรุณาเลือกไฟล์สำหรับอัปโหลด'); window.history.back();</script>";
        exit();
    }
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.bootstrap5.css">
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.datatables.net/2.2.2/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.2.2/js/dataTables.bootstrap5.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
                    "emptyTable": "โฟลเดอร์ว่าง"
                }
            });
        });

        function confirmDelete(template_id, folder_id) {
            if (confirm('คุณต้องการลบเอกสารนี้ใช่หรือไม่?')) {
                window.location.href = 'file_del.php?template_id=' + template_id + '&folder_id=' + folder_id;
            }
        }
        // ดูตัวอย่างไฟล์
        function previewFile(templateId) {
            console.log('Preview requested for template:', templateId);
            
            fetch('preview.php?template_id=' + templateId)
                .then(response => {
                    console.log('Raw response:', response);
                    return response.json();
                })
                .then(data => {
                    console.log('Parsed data:', data);
                    if (data.status === 'success') {
                        Swal.fire({
                            title: 'ตัวอย่างเอกสาร',
                            html: `<div style="max-height: 70vh; overflow-y: auto;">
                                    ${data.content}
                                </div>`,
                            width: '80%',
                            showCloseButton: true,
                            showConfirmButton: false
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'เกิดข้อผิดพลาด',
                            text: data.message || 'ไม่สามารถแสดงตัวอย่างไฟล์ได้'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'เกิดข้อผิดพลาด',
                        text: 'ไม่สามารถแสดงตัวอย่างไฟล์ได้'
                    });
                });
        }

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
                        <p>Documents
                        <p>
                    </div>
                    <ul>
                        <li><a href="../dashboard/staff.php">
                                <div><i class="fa-solid fa-house"></i> หน้าแรก</div>
                            </a></li>
                        <li><a href="folder-staff.php">
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
                        <p>Analyst
                        <p>
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
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="folder-staff.php" class='hv' onclick="history.back();">เอกสาร/แบบฟอร์ม</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?php echo $folder['folder_name']; ?></li>
            </ol>
            <div>
                <!-- Button trigger modal -->
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                    <i class="fa-solid fa-arrow-up-from-bracket"></i></i>&nbsp;&nbsp; อัพโหลดไฟล์
                </button>

                <!-- Modal pop up -->
                <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="staticBackdropLabel">อัพโหลดเอกสาร/แบบฟอร์ม</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form action="" method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="folder_id" value="<?php echo htmlspecialchars($folder_id); ?>">
                                <div class="modal-body">
                                    <div class="form-group mb-3">
                                        <label>ชื่อเอกสาร/แบบฟอร์ม</label>
                                        <input type="text" class="form-control" name="template_name" placeholder="ระบุชื่อเอกสาร/แบบฟอร์ม">
                                    </div>
                                    <div class="form-group mb-3">
                                        <input type="file" class="form-control" name="file" id="file">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary col-auto" data-bs-dismiss="modal">ยกเลิก</button>
                                    <button type="submit" class="btn btn-primary col-auto ms-auto">ยืนยัน</button>
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
                        <th colspan="2" style="width: 50%;">ชื่อ</th>
                        <th style="width: 20%; text-align: left;">แก้ไขล่าสุด</th>
                        <th style="width: 25%;">แก้ไขโดย</th>
                        <th style="width: 5%;"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $result = fetch_template($conn);
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td><img src='../../assets/img/draft.png' width='17px'></td>";
                            echo "<td style='width: 50%;'>" . $row['template_name'] . "</td>";
                            echo "<td style='width: 20%; text-align: left;'>" . date("d/m/Y H:i", strtotime($row['created_at'])) . "</td>";
                            echo "<td style='width: 25%;'>" . $row['firstname'] . " " . $row['lastname'] . "</td>";
                            echo "<td style='width: 5%;'><a class='link-dark' href='#' data-bs-toggle='dropdown' aria-expanded='false'><i class='fas fa-ellipsis-h '></i></a>
                                    <ul class='dropdown-menu'>
                                        <li><a class='dropdown-item' href='javascript:void(0)' onclick='previewFile(" . htmlspecialchars($row['template_id']) . ")'>ดูตัวอย่าง</a></li>
                                        <li><a id='download' class='dropdown-item' href='download.php?template_id=" . $row['template_id'] . "'>ดาวน์โหลด</a></li>
                                        <li><a class='dropdown-item' href='javascript:void(0)' onclick='confirmDelete(" . htmlspecialchars($row['template_id']) . ", " . $folder_id . ")'>ลบ</a></li>
                                    </ul></td>";
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