<?php
session_start();
include '../../config/func.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login/login.php");
    exit();
}
$submission_id = isset($_GET['submission_id']) ? intval($_GET['submission_id']) : 0;
$user_id = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : 0;

if ($submission_id <= 0 || $user_id <= 0) {
    die("ข้อมูลไม่ถูกต้อง");
}

// ค้นหาข้อมูลเอกสารที่เกี่ยวข้อง
// ค้นหาข้อมูลเอกสารที่เกี่ยวข้อง รวมข้อมูล firstname, lastname ของผู้รับ
$sql = "SELECT ds.*, u.firstname, u.lastname, u.prefix, dr.recipient_id, dr.receiver_id, dr.department_id, 
                   dr.status AS recipient_status, dr.revision_reason, u2.firstname AS receiver_firstname, 
                   u2.lastname AS receiver_lastname, u2.prefix AS receiver_prefix, dp.department_name, p.purpose_name
            FROM document_submission ds
            LEFT JOIN document_recipient dr ON ds.submission_id = dr.submission_id
            LEFT JOIN users u ON ds.sender_id = u.user_id
            LEFT JOIN users u2 ON dr.receiver_id = u2.user_id
            LEFT JOIN departments dp ON dr.department_id = dp.department_id
            LEFT JOIN purposes p ON ds.purpose_id = p.purpose_id
            WHERE ds.submission_id = ?
            AND (ds.sender_id = ? OR dr.receiver_id = ? OR dr.department_id IN 
                 (SELECT department_id FROM user_department WHERE user_id = ?))";


$stmt = $conn->prepare($sql);
$stmt->bind_param("iiii", $submission_id, $user_id, $user_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$doc = $result->fetch_assoc();


$submission_id = $doc['submission_id'];
$result = fetch_fileDetail($conn, $submission_id);


$user_id = $_SESSION['user_id'];
$data = fetch_user($conn, $user_id);

if (isset($_GET['submission_id'])) {
    $submission_id = intval($_GET['submission_id']);

    // อัปเดตสถานะเป็น 'read'
    $sql = "UPDATE document_recipient 
            SET status = 'read' 
            WHERE submission_id = ? AND receiver_id = ? AND status = 'pending'";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $submission_id, $user_id);
    $stmt->execute();
    $stmt->close();
}
if (isset($_GET['submission_id'])) {
    $submission_id = $_GET['submission_id'];

    // อัพเดตสถานะการอ่านของ notification ที่เกี่ยวข้อง
    $update_query = "UPDATE notifications SET is_read = 1 WHERE submission_id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("i", $submission_id);
    $stmt->execute();
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
                            <li><a class="dropdown-item" href="../../templates/lecturer/profile.php">ข้อมูลส่วนตัว</a></li>
                            <li><a class="dropdown-item" href="../../templates/lecturer/changePassword.php">แก้ไขรหัสผ่าน</a></li>
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
                        <li><a href="../dashboard/lecturer.php">
                                <div><i class="fa-solid fa-house"></i> หน้าแรก</div>
                            </a></li>
                        <li><a href="../form/folder-lect.php">
                                <div><i class="fa-solid fa-folder"></i> เอกสาร/แบบฟอร์ม</div>
                            </a></li>
                        <li><a href="inbox_lect.php">
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
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="inbox_lect.php" class='hv' onclick="history.back();">รายการเอกสารส่ง</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?php echo $doc['name']; ?></li>
                </ol>
            </div>
        </div>

        <!-- Table -->
        <div class="content-detail">
            <?php if ($doc): ?>
                <div class="detail-1 shadow-sm p-3 mb-5 rounded">
                    <div class="row g-2 mb-3">
                        <div class="d-flex align-items-center">
                            <h3 class="mb-0">รายละเอียดเอกสาร</h3>
                            <?php if (!empty($doc['department_id']) && empty($doc['receiver_id'])): ?>
                                <span class="badge rounded-pill text-bg-secondary ms-auto">ถึงกลุ่มวิชา</span>
                            <?php elseif (!empty($doc['receiver_id'])): ?>
                                <span class="badge rounded-pill text-bg-secondary ms-auto">ถึงบุคคล</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <p class="badge text-bg-info">รหัสอ้างอิง</p> <?= htmlspecialchars($doc['document_code']) ?>
                    <p><strong>ชื่อเรื่อง:</strong> <?= htmlspecialchars($doc['name']) ?></p>
                    <?php
                    if (!empty($doc['previous_refcode'])) {
                        echo "<p><strong>รหัสอ้างอิงก่อนหน้า:</strong>&nbsp;&nbsp;" . $doc['previous_refcode'] . "</p>";
                    } else {
                        echo "<p><strong>รหัสอ้างอิงก่อนหน้า:</strong>&nbsp;ไม่มี</p>";
                    }
                    ?>
                    <p><strong>วัตถุประสงค์:</strong> <?= htmlspecialchars($doc['purpose_name']) ?></p>
                    <?php
                    if ($doc['recipient_status'] != 'revision') {
                        echo '<p><strong>ผู้ส่ง: </strong>' . $doc['prefix'] . ' ' . $doc['firstname'] . ' ' . $doc['lastname'] . '</p>';
                    } else {
                        echo '<p><strong>ผู้ส่งคืน: </strong>' . $doc['receiver_prefix'] . '' . $doc['receiver_firstname'] . ' ' . $doc['receiver_lastname'] . '</p>';
                    }
                    if ($doc['recipient_status'] != 'revision') {
                        echo '<div class="form-floating mb-3">
                                    <textarea class="form-control" placeholder="Leave a comment here" id="floatingTextarea2Disabled" style="height: 100px" disabled>' . $doc['remark'] . '</textarea>
                                    <label for="floatingTextarea2Disabled">ข้อความ</label>
                                </div>';
                    } else {
                        echo '<div class="form-floating mb-3">
                                    <textarea class="form-control" placeholder="Leave a comment here" id="floatingTextarea2Disabled" style="height: 100px" disabled>' . $doc['revision_reason'] . '</textarea>
                                    <label for="floatingTextarea2Disabled">หมายเหตุการส่งกลับเอกสาร</label>
                                </div>';
                    }
                    ?>

                    <!-- Modal -->
                    <div class="modal fade" id="rejection" tabindex="-1" aria-labelledby="rejectionLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="rejectionLabel">หมายเหตุ</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form action="rejection.php" method="POST">
                                    <input type="hidden" name="recipient_id" value="<?= $doc['recipient_id'] ?>">
                                    <input type="hidden" name="submission_id" value="<?= $doc['submission_id'] ?>">
                                    <div class="modal-body">
                                        <div class="form-floating">
                                            <textarea class="form-control" name="revision_reason" placeholder="Leave a comment here" id="floatingTextarea"></textarea>
                                            <label for="floatingTextarea">ระบุเหตุผลการส่งกลับ</label>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                                        <button type="submit" class="btn btn-primary">ยืนยันการส่งกลับ</button>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                    <div class="row g-2 mb-3">
                        <?php
                        if ($doc['recipient_status'] != 'revision') {
                            if ($doc['recipient_status'] != 'completed') {
                                if (!empty($doc['receiver_id'])) {
                                    // ถ้าเป็น receiver_id (ส่งถึงบุคคล)
                                    echo "<button type='button' class='btn btn-danger col-auto' data-bs-toggle='modal' data-bs-target='#rejection'>
                                            <i class='fa-solid fa-rotate-left'></i>&nbsp;&nbsp;ทราบและส่งกลับ
                                        </button>";
                                    echo "<a class='col-auto ms-auto' href='update_status.php?recipient_id=" . $doc['recipient_id'] . "&action=completed'
                                            onclick='return confirm(\"คุณต้องการเปลี่ยนสถานะเอกสารเป็นเสร็จสิ้นหรือไม่?\")'>
                                            <button type='button' class='btn btn-primary'>
                                                <i class='fa-solid fa-check'></i>&nbsp;&nbsp;ทราบ
                                            </button>
                                        </a>";
                                } elseif (!empty($doc['department_id'])) {
                                    // ถ้าเป็น department_id (ส่งถึงหน่วยงาน)
                                    if ($_SESSION['role'] != 'staff') {
                                        echo "<p class='text-muted text-center'>คุณไม่มีสิทธิ์ดำเนินการเอกสารนี้</p>";
                                    } else {
                                        echo "<a class='col-auto ms-auto' href='update_status.php?recipient_id=" . $doc['recipient_id'] . "&action=completed'
                                                    onclick='return confirm(\"คุณต้องการเปลี่ยนสถานะเอกสารเป็นเสร็จสิ้นหรือไม่?\")'>
                                                    <button type='button' class='btn btn-primary'>
                                                        <i class='fa-solid fa-check'></i>&nbsp;&nbsp;ทราบ
                                                    </button>
                                                </a>";
                                    }
                                }
                            } else {
                                echo "<a class='btn btn-success disabled col-auto ms-auto' role='button' aria-disabled='true'>
                                        <i class='fa-solid fa-check'></i>&nbsp;&nbsp;ทราบแล้ว
                                    </a>";
                            }
                        } elseif ($doc['recipient_status'] == 'revision') {
                            if ($doc['sender_id'] == $user_id) {
                                echo "<a class='col-auto ms-auto' href='update_status.php?recipient_id=" . $doc['recipient_id'] . "&action=completed'
                                        onclick='return confirm(\"คุณต้องการเปลี่ยนสถานะเอกสารเป็นเสร็จสิ้นหรือไม่?\")'>
                                        <button type='button' class='btn btn-primary'>
                                            <i class='fa-solid fa-check'></i>&nbsp;&nbsp;ทราบ
                                        </button>
                                    </a>";
                            } elseif ($doc['receiver_id'] == $user_id) {
                                echo "<a class='btn btn-danger disabled col-auto ms-auto' role='button' aria-disabled='true'>
                                        <i class='fa-solid fa-check'></i>&nbsp;&nbsp;ส่งคืนแล้ว
                                    </a>";
                            }
                        }
                        ?>
                    </div>
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