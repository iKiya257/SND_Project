<?php
session_start();
include '../../config/func.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../pages/login/login.php");
    exit();
}
// ตรวจสอบว่าได้รับ user_id มาหรือไม่
if (!isset($_GET['u_id'])) {
    header("Location: user.php"); // ถ้าไม่มี user_id ให้กลับไปยังหน้าผู้ใช้
    exit();
}
$user_id = intval($_GET['u_id']);
$user = fetch_user_by_id($conn, $user_id);

$departments = fetch_department($conn);
$user_department_id = fetch_userdepartment($conn, $user_id);

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
            <h1>ข้อมูลผู้ใช้</h1>
        </div>

        <!-- Table -->
        <div class="content shadow-sm p-3 mb-5 rounded">
            <h2>แก้ไขข้อมูลผู้ใช้</h2>
            <form action="user_update.php" method="POST">
                <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user['user_id']); ?>">
                <div class="mb-3">
                    <label class="form-label">คำนำหน้า</label>
                    <input type="text" class="form-control" name="prefix" value="<?php echo htmlspecialchars($user['prefix']); ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label">ชื่อ</label>
                    <input type="text" class="form-control" name="firstname" value="<?php echo htmlspecialchars($user['firstname']); ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label">นามสกุล</label>
                    <input type="text" class="form-control" name="lastname" value="<?php echo htmlspecialchars($user['lastname']); ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label">ตำแหน่ง</label>
                    <input type="text" class="form-control" name="position" value="<?php echo htmlspecialchars($user['position']); ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label">อีเมล</label>
                    <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($user['email']); ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label">บทบาท</label>
                    <select class="form-select" aria-label="Default select example" name="role">
                        <option value="admin" <?php if ($user['role'] == 'admin') echo 'selected'; ?>>Admin</option>
                        <option value="lecturer" <?php if ($user['role'] == 'lecturer') echo 'selected'; ?>>Lecturer</option>
                        <option value="staff" <?php if ($user['role'] == 'staff') echo 'selected'; ?>>Staff</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">ภาควิชา</label>
                    <select class="form-select" aria-label="Default select example" name="department">
                        <option value="">เลือกกลุ่มวิชา</option>
                        <?php foreach ($departments as $dept): ?>
                            <option value="<?php echo $dept['department_id']; ?>"
                                <?php echo ($dept['department_id'] == $user_department_id) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($dept['department_name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>


                <div class="d-grid gap-2 d-md-flex justify-content-md-end">   
                    <input type="button" value="ยกเลิก" class="btn btn-danger" onclick="history.back();">
                    <input type="submit" name="submit" class="btn btn-primary" value="บันทึก">
                </div>
            </form>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>