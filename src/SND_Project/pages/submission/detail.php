<?php
session_start();
include '../../config/func.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: pages/login/login.php");
    exit();
}
if (isset($_GET['submission_id'])) {
    $id = $_GET['submission_id'];
    $sql = "SELECT * FROM document_submission WHERE submission_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) == 1) {
        $doc = mysqli_fetch_assoc($result);
    } else {
        echo "No data found";
        exit;
    }
} else {
    echo "Invalid request";
    exit;
}
$submission_id = $doc['submission_id'];
$result = fetch_recDetail($conn, $submission_id);
$result = fetch_fileDetail($conn, $submission_id);


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
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // ดูตัวอย่างไฟล์
        function previewFile(templateId) {
            console.log('Preview requested for template:', templateId);
            
            fetch('preview.php?file_id=' + templateId)
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
                        <li><a href="../form/folder-staff.php">
                                <div><i class="fa-solid fa-folder"></i> เอกสาร/แบบฟอร์ม</div>
                            </a></li>
                        <li><a href="../inbox/inbox.php">
                                <div><i class="fa-solid fa-box-archive"></i> เอกสารเข้า</div>
                            </a></li>
                        <li><a href="send.php">
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
            <div>
                <a class='bf' onclick="history.back();"><i class='fas fa-angle-left'></i>&nbsp;&nbsp;ย้อนกลับ</a>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="send.php" class='hv' onclick="history.back();">รายการเอกสารส่ง</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?php echo $doc['name']; ?></li>
                </ol>
            </div>
        </div>

        <!-- Table -->
        <div class="content-detail">
            <?php if ($doc): ?>
                <div class="detail-1 shadow-sm p-3 mb-5 rounded">
                    <h3>รายละเอียดเอกสาร</h3>
                    <p class="badge text-bg-info">รหัสอ้างอิง</p> <?= htmlspecialchars($doc['document_code']) ?>
                    <p><strong>ชื่อเรื่อง:</strong>&nbsp;<?= htmlspecialchars($doc['name']) ?></p>
                    <?php
                    if (!empty($doc['previous_refcode'])) {
                        echo "<p><strong>รหัสอ้างอิงก่อนหน้า:</strong>&nbsp;&nbsp;" . $doc['previous_refcode'] . "</p>";
                    } else {
                        echo "<p><strong>รหัสอ้างอิงก่อนหน้า:</strong>&nbsp;ไม่มี</p>";
                    }
                    ?>
                    <div class="form-floating mb-3">
                        <textarea class="form-control" placeholder="Leave a comment here" id="floatingTextarea2Disabled" style="height: 100px" disabled><?= nl2br(htmlspecialchars($doc['remark'])) ?></textarea>
                        <label for="floatingTextarea2Disabled">ข้อความ</label>
                    </div>
                    <table class="table table-hover">
                        <thead>
                            <th>#</th>
                            <th style="width: 45%;">ผู้รับเอกสาร</th>
                            <th style="width: 30%;">วัตถุประสงค์</th>
                            <th style="width: 25%;"></th>
                        </thead>
                        <tbody>
                            <?php
                            $i = 1;
                            $result = fetch_recDetail($conn, $submission_id);
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td class='text-center'>" . $i++ . "</td>";
                                    echo "<td style='width: 45%;'>";
                                    if (!empty($row['firstname']) && !empty($row['lastname'])) {
                                        echo $row['prefix'] . " " . $row['firstname'] . " " . $row['lastname']; // แสดงชื่อบุคคล
                                    } elseif (!empty($row['department_name'])) {
                                        echo $row['department_name']; // แสดงชื่อหน่วยงาน
                                    } else {
                                        echo "-";
                                    }
                                    echo "</td>";
                                    echo "<td style='width: 30%;'>" . $row['purpose_name'] .  "</td>";
                                    echo "<td class='text-center' style='width: 25%;'>";
                                    if (!empty($row['firstname']) && !empty($row['lastname'])) {
                                        if ($row['status'] == 'read') {
                                            echo "<h5 class='text-center'><span class='badge rounded-pill text-bg-light'><i class='fa-solid fa-eye'></i>&nbsp;&nbsp;อ่านแล้ว</span></h5>";
                                        } elseif ($row['status'] == 'pending') {
                                            echo '<a href="detailpullback.php?receiver_id=' . $row['receiver_id'] . '&action=pull&submission_id=' . $submission_id . '" onclick="return confirm(\'คุณต้องการดึงเอกสารกลับจากผู้ใช้รายนี้หรือไม่?\')"><button class="btn btn-secondary btn-sm"><i class="fa-solid fa-rotate-left"></i>&nbsp;&nbsp;ดึงกลับ</button></a>';
                                        } elseif ($row['status'] == 'removed') {
                                            echo '<a href="detailpullback.php?receiver_id=' . $row['receiver_id'] . '&action=push&submission_id=' . $submission_id . '" onclick="return confirm(\'คุณต้องการส่งเอกสารถึงผู้ใช้รายนี้อีกครั้งหรือไม่?\')"><button class="btn btn-success btn-sm"><i class="fa-regular fa-paper-plane"></i>&nbsp;&nbsp;ส่งอีกครั้ง</button></a>';
                                        } elseif ($row['status'] == 'revision') {
                                            echo "<h5 class='text-center'><span class='badge rounded-pill text-bg-danger'>ต้องแก้ไข</span></h5>";
                                        } elseif ($row['status'] == 'completed') {
                                            echo "<h5 class='text-center'><span class='badge rounded-pill text-bg-primary'><i class='fa-solid fa-check'></i>&nbsp;&nbsp;ทราบแล้ว</span></h5>";
                                        }
                                    } elseif (!empty($row['department_name'])) {
                                        if ($row['status'] == 'read') {
                                            echo "<h5 class='text-center'><span class='badge rounded-pill text-bg-light'><i class='fa-solid fa-eye'></i>&nbsp;&nbsp;อ่านแล้ว</span></h5>";
                                        } elseif ($row['status'] == 'pending') {
                                            echo '<a href="detailpullback.php?department_id=' . $row['department_id'] . '&action=pull&submission_id=' . $submission_id . '" onclick="return confirm(\'คุณต้องการดึงเอกสารกลับจากผู้ใช้รายนี้หรือไม่?\')"><button class="btn btn-secondary btn-sm"><i class="fa-solid fa-rotate-left"></i>&nbsp;&nbsp;ดึงกลับ</button></a>';
                                        } elseif ($row['status'] == 'removed') {
                                            echo '<a href="detailpullback.php?department_id=' . $row['department_id'] . '&action=push&submission_id=' . $submission_id . '" onclick="return confirm(\'คุณต้องการส่งเอกสารถึงผู้ใช้รายนี้อีกครั้งหรือไม่?\')"><button class="btn btn-success btn-sm"><i class="fa-regular fa-paper-plane"></i>&nbsp;&nbsp;ส่งอีกครั้ง</button></a>';
                                        } elseif ($row['status'] == 'revision') {
                                            echo "<h5 class='text-center'><span class='badge rounded-pill text-bg-danger'>ต้องแก้ไข</span></h5>";
                                        } elseif ($row['status'] == 'completed') {
                                            echo "<h5 class='text-center'><span class='badge rounded-pill text-bg-primary'><i class='fa-solid fa-check'></i>&nbsp;&nbsp;ทราบแล้ว</span></h5>";
                                        }
                                    }
                                    echo "</td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='4'>ไม่มีรายชื่อผู้รับ</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <div class="detail-2 shadow-sm p-3 mb-5 rounded">
                    <h3>ไฟล์เอกสาร</h3>
                    <table class="table table-hover">
                        <thead>
                            <th>ลำดับ</th>
                            <th style="width: 80%;">ไฟล์เอกสาร</th>
                            <th style="width: 20%;"></th>
                        </thead>
                        <tbody>
                            <?php
                            $i = 1;
                            $result = fetch_fileDetail($conn, $submission_id);
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    $file_path = "../uploads/" . $row['file_path'];
                                    $file_name = basename($row['file_path']);
                                    $file_extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
                                    

                                    // ประเภทไฟล์ที่เปิดบนเบราว์เซอร์ได้
                                    $viewable_types = ['pdf', 'jpg', 'jpeg', 'png'];
                                    // ประเภทไฟล์ที่เปิดบน Word Online ได้
                                    $word_types = ['doc', 'docx'];

                                    if (in_array($file_extension, $viewable_types)) {
                                        $file_link = "<a href='$file_path' target='_blank'>$file_name</a><td></td>";
                                    } elseif (in_array($file_extension, $word_types)) {
                                        $file_link = "<a href='$file_path' target='_blank'>$file_name</a><td><a id='bta' class='btn btn-primary' href='javascript:void(0)' role='button' onclick='previewFile(" . htmlspecialchars($row['file_id']) . ")'>ดูตัวอย่าง</a></td>";
                                    } else {
                                        $file_link = "<a href='$file_path' download>$file_name</a><td></td>";
                                    }

                                    echo "<tr>";
                                    echo "<td class='text-center'>" . $i++ . "</td>";
                                    echo "<td>" . $file_link . "</td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='6'>ไม่มีรายการไฟล์</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>


            <?php else: ?>
                <p>ไม่พบข้อมูลเอกสาร</p>
            <?php endif; ?>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>