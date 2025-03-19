<?php
session_start();
include '../../config/func.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: pages/login/login.php");
    exit();
}

$ref = generateDocCode($conn);

$departments = [];
$sql = "SELECT department_id, department_name FROM departments";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $departments[$row['department_id']] = $row['department_name'];
    }
}

$purposes = $conn->query("SELECT purpose_id, purpose_name FROM purposes");

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
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">
    <title>เอกสาร/แบบฟอร์ม</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body style="background-color: #F5F5F5;">
    <!-- Header -->
    <nav id="header" class="navbar navbar-expand-lg bg-body fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="#"><img src="../../assets/img/logo-snd.png" alt="" width="200px"></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
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
            <h1>รายละเอียดการส่งเอกสาร</h1>
        </div>
        <div class="content shadow-sm p-3 mb-5 rounded">

            <form action="send_db.php" method="POST" enctype="multipart/form-data">

                <h3 class="mb-3">ตั้งค่าการส่งเอกสาร</h3>
                <p class="badge text-bg-info">รหัสอ้างอิง</p>&nbsp; <?php echo $ref; ?><br>
                <div class="mb-2">
                    <label>เรื่อง</label>
                    <input type="text" class="form-control" name="name" id="name" require>
                </div>
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" id="urgent" name="urgent" value="1">
                    <label class="form-check-label" for="urgent">กำหนดเป็นเอกสารด่วน</label>
                </div>
                <div class="mb-3">
                    <label>รหัสอ้างอิงก่อนหน้า (ถ้ามี)</label>
                    <input type="text" class="form-control" name="ref_code" id="ref_code" require>
                    <div class="form-text">
                        รหัสอ้างอิงจากเอกสารก่อนหน้า เช่น 20250101-xxx
                    </div>
                </div>
                <hr>
                <div class="row g-3 mb-3">
                    <div class="col-auto">
                        <label for="recipient_type" class="form-label">ผู้รับเอกสาร</label>
                    </div>
                    <div class="col-auto">
                        <select id="recipient_type" class="form-select">
                            <option selected>เลือกประเภทผู้รับ</option>
                            <option value="individual">ผู้รับรายบุคคล</option>
                            <option value="department">ภาควิชาที่กำหนด</option>
                        </select>
                    </div>

                    <div class="col-auto">
                        <!-- สำหรับกรอกผู้รับรายบุคคล -->
                        <div id="individual_section" class="row g-3 mb-3" style="display:none;">
                            <div class="col-auto">
                                <select id="department_select" class="form-select">
                                    <option selected>เลือกภาควิชา</option>
                                    <?php foreach ($departments as $dept_id => $dept_name) { ?>
                                        <option value="<?php echo $dept_id; ?>"><?php echo $dept_name; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-auto">
                                <select id="individual_select" class="form-select">
                                    <option selected>กรุณาเลือกภาควิชาก่อน</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-auto">
                        <!-- สำหรับเลือกภาควิชา -->
                        <div id="department_section" class="mb-3" style="display:none;">
                            <select id="department_group_select" class="form-select">
                                <option selected>เลือกภาควิชา</option>
                                <?php foreach ($departments as $dept_id => $dept_name) { ?>
                                    <option value="<?php echo $dept_id; ?>"><?php echo $dept_name; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- เลือกวัตถุประสงค์ -->
                <div class="row g-3 mb-3">
                    <div class="col-auto">
                        <label for="inputPassword6" class="col-form-label">วัตถุประสงค์</label>
                    </div>
                    <div class="col-auto">
                        <select class="form-select" aria-label="Default select example" name="purpose_id" id="purpose_id" required>
                            <option value=""> ระบุวัตถุประสงค์ </option>
                            <?php while ($row = $purposes->fetch_assoc()): ?>
                                <option value="<?php echo $row['purpose_id']; ?>">
                                    <?php echo $row['purpose_name']; ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>
                <div class="mb-3">
                    <button type="button" id="add_recipient" class="btn btn-primary">เพิ่มผู้รับ</button>
                </div>
                <div style="width: 50%;">
                    <table class="table table-striped table-hover border" id="recipient_table">
                        <thead>
                            <tr>
                                <th style="width: 60%;">ชื่อผู้รับ / ภาควิชา</th>
                                <th style="width: 30%;">วัตถุประสงค์</th>
                                <th style="width: 10%;"></th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
                <hr>
                <!-- อัปโหลดไฟล์ -->
                <div class="fileinput" onclick="document.getElementById('fileInput').click();">
                    <input type="file" id="fileInput" name="files[]" accept=".pdf,.doc,.docx,.png,.jpg,.jpeg" multiple hidden>
                    <i class="fas fa-cloud-upload-alt"></i>
                    <p>DOC/PDF/JPG/PNG</p>
                    <p>อัปโหลดจากอุปกรณ์นี้</p>
                </div>
                <div class="file-list" id="fileList"></div>

                <div class="form-floating mb-3">
                    <textarea class="form-control" placeholder="Leave a comment here" name="remark" id="remark" style="height: 100px"></textarea>
                    <label for="floatingTextarea2">หมายเหตุ</label>
                </div>


                <div class="d-grid gap-2 d-md-flex justify-content-md-end mb-3">
                    <button type="submit" class="btn btn-success">ส่งเอกสาร</button>
                </div>
            </form>


        </div>
    </section>

    <script>
        $(document).ready(function() {
            $('#recipient_type').change(function() {
                let type = $(this).val();
                if (type === 'individual') {
                    $('#individual_section').show();
                    $('#department_section').hide();
                } else if (type === 'department') {
                    $('#individual_section').hide();
                    $('#department_section').show();
                } else {
                    $('#individual_section, #department_section').hide();
                }
            });

            $('#department_select').change(function() {
                let dept_id = $(this).val();
                console.log("Selected Department ID:", dept_id);
                $('#individual_select').empty().append('<option value="">-- กรุณาเลือก --</option>');
                if (dept_id) {
                    $.ajax({
                        url: 'get_users.php',
                        type: 'POST',
                        data: {
                            department_id: dept_id
                        },
                        success: function(response) {
                            console.log(response); // เพิ่มส่วนนี้เพื่อตรวจสอบค่าที่ส่งกลับใน Console
                            try {
                                let users = JSON.parse(response);
                                if (users.error) {
                                    alert("Error: " + users.error);
                                } else {
                                    $.each(users, function(index, user) {
                                        $('#individual_select').append('<option value="' + user.user_id + '">' + user.prefix + ' ' + user.firstname + ' ' + user.lastname + '</option>');
                                    });
                                }
                            } catch (e) {
                                alert("เกิดข้อผิดพลาดในการโหลดข้อมูล");
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error("AJAX Error:", status, error);
                            alert("ไม่สามารถโหลดข้อมูลผู้ใช้ได้");
                        }
                    });
                }
            });

            $('#add_recipient').click(function(event) {
                event.preventDefault(); // ป้องกันการรีเฟรชหน้าเว็บ

                let type = $('#recipient_type').val(); // ประเภทของผู้รับ
                let recipient = ''; // ชื่อผู้รับ
                let user_id = ''; // เก็บ user_id
                let dept_id = ''; // เก็บ dept_id

                if (type === 'individual') {
                    recipient = $('#individual_select option:selected').text();
                    user_id = $('#individual_select').val();
                } else if (type === 'department') {
                    recipient = $('#department_group_select option:selected').text();
                    dept_id = $('#department_group_select').val();
                }

                let purpose = $('#purpose_id option:selected').text() || 'ไม่ระบุ';
                let purpose_id = $('#purpose_id').val();

                if ((user_id || dept_id) && purpose_id) {
                    $('#recipient_table tbody').append(
                        `<tr>
                            <td>${recipient}</td>
                            <td>${purpose}</td>
                            <td>
                                <input type="hidden" name="user_ids[]" value="${user_id}">
                                <input type="hidden" name="dept_ids[]" value="${dept_id}">
                                <input type="hidden" name="purposes[]" value="${purpose_id}">
                                <button class="btn-close" aria-label="Close" onclick="removeRow(this)"></button>
                            </td>
                        </tr>`
                    );
                } else {
                    alert('กรุณาเลือกผู้รับและวัตถุประสงค์ก่อน');
                }
            });
        });

        document.getElementById("fileInput").addEventListener("change", function(event) {
            const fileList = document.getElementById("fileList");
            fileList.innerHTML = "";
            for (const file of event.target.files) {
                const fileItem = document.createElement("div");
                fileItem.classList.add("file-item");
                fileItem.innerHTML = `${file.name} <button class='remove-file btn-close' aria-label='Close'></button>`;
                fileList.appendChild(fileItem);

                fileItem.querySelector(".remove-file").addEventListener("click", function() {
                    fileItem.remove();
                });
            }
        });

        $(document).ready(function() {
            $('#uploadForm').on('submit', function(e) {
                e.preventDefault();

                // แสดง loading
                Swal.fire({
                    title: 'กำลังส่งเอกสาร...',
                    text: 'กรุณารอสักครู่',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                let formData = new FormData(this);

                $.ajax({
                    url: 'send_db.php',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        try {
                            const result = JSON.parse(response);

                            if (result.status === 'success') {
                                // แสดงข้อความสำเร็จ
                                Swal.fire({
                                    icon: 'success',
                                    title: 'สำเร็จ!',
                                    text: result.message,
                                    showConfirmButton: false,
                                    timer: 1500
                                }).then(() => {
                                    // redirect ไปยังหน้า send.php
                                    window.location.href = result.redirect;
                                });
                            } else {
                                // แสดงข้อความผิดพลาด
                                Swal.fire({
                                    icon: 'error',
                                    title: 'เกิดข้อผิดพลาด!',
                                    text: result.message
                                });
                            }
                        } catch (e) {
                            // กรณี parse JSON ไม่ได้
                            Swal.fire({
                                icon: 'error',
                                title: 'เกิดข้อผิดพลาด!',
                                text: 'เกิดข้อผิดพลาดในการประมวลผล'
                            });
                        }
                    },
                    error: function() {
                        // กรณีเกิด error จาก AJAX
                        Swal.fire({
                            icon: 'error',
                            title: 'เกิดข้อผิดพลาด!',
                            text: 'ไม่สามารถเชื่อมต่อกับเซิร์ฟเวอร์ได้'
                        });
                    }
                });
            });
        });
    </script>
    <script src="../../assets/js/script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>