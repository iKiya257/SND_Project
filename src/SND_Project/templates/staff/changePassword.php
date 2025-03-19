<?php
session_start();
include '../../config/func.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../pages/login/login.php");
    exit();
}
$user_id = $_SESSION['user_id'];
$data = fetch_user($conn, $user_id);
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // ตรวจสอบความยาวรหัสผ่านใหม่
    if (strlen($new_password) < 8 || strlen($new_password) > 12) {
        $error = "รหัสผ่านต้องมีความยาว 8-12 ตัว";
    } elseif ($new_password !== $confirm_password) {
        $error = "รหัสผ่านใหม่และการยืนยันไม่ตรงกัน";
    } else {
        // ดึงข้อมูลรหัสผ่านเดิมจากฐานข้อมูล
        $stmt = $conn->prepare("SELECT upassword FROM users WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 0) {
            $error = "ไม่พบผู้ใช้";
        } else {
            $stmt->bind_result($hashed_password);
            $stmt->fetch();
            $stmt->close();

            // ตรวจสอบรหัสผ่านเดิม
            if (!password_verify($current_password, $hashed_password)) {
                $error = "รหัสผ่านปัจจุบันไม่ถูกต้อง";
            } else {
                // เข้ารหัสรหัสผ่านใหม่
                $new_hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

                // อัปเดตรหัสผ่านใหม่ลงฐานข้อมูล
                $update_stmt = $conn->prepare("UPDATE users SET upassword = ? WHERE user_id = ?");
                $update_stmt->bind_param("si", $new_hashed_password, $user_id);

                if ($update_stmt->execute()) {
                    $success = "เปลี่ยนรหัสผ่านสำเร็จ";
                } else {
                    $error = "เกิดข้อผิดพลาดในการเปลี่ยนรหัสผ่าน";
                }

                $update_stmt->close();
            }
        }
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
    <title>หน้าแรก</title>

</head>

<body style="background-color: #F5F5F5;">
    <nav id="header" class="navbar navbar-expand-lg bg-body fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="staff.php"><img src="../../assets/img/logo-snd.png" alt="" width="200px"></a>
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
                            <li><a class="dropdown-item" href="profile.php">ข้อมูลส่วนตัว</a></li>
                            <li><a class="dropdown-item" href="changePassword.php">แก้ไขรหัสผ่าน</a></li>
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
                        <p>Documents
                        <p>
                    </div>
                    <ul>
                        <li><a href="../../pages/dashboard/staff.php">
                                <div><i class="fa-solid fa-house"></i> หน้าแรก</div>
                            </a></li>
                        <li><a href="../../pages/form/folder-staff.php">
                                <div><i class="fa-solid fa-folder"></i> เอกสาร/แบบฟอร์ม</div>
                            </a></li>
                        <li><a href="../../pages/inbox/inbox.php">
                                <div><i class="fa-solid fa-box-archive"></i> เอกสารเข้า</div>
                            </a></li>
                        <li><a href="../../pages/submission/send.php">
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
                        <li><a href="profile.php">
                                <div><i class="fa-solid fa-arrow-trend-up"></i> วิเคราะห์รายงาน</div>
                            </a></li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <section id="container">
        <div class="content-header">
            <h1>แก้ไขรหัสผ่าน</h1>
        </div>
        <div class="content-password shadow-sm p-3 mb-5 rounded">
            <form method="POST" action="" class="needs-validation" novalidate>
                <div class="mb-3">
                    <label class="form-label">รหัสผ่านปัจจุบัน:</label>
                    <input type="password" name="current_password" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">รหัสผ่านใหม่:</label>
                    <input type="password" name="new_password" class="form-control" required>
                    <?php if ($error === "รหัสผ่านต้องมีความยาว 8-12 ตัว") { ?>
                        <div class="invalid-feedback d-block">
                            รหัสผ่านต้องมีความยาว 8-12 ตัว
                        </div>
                    <?php } ?>
                </div>

                <div class="mb-3">
                    <label class="form-label">ยืนยันรหัสผ่านใหม่:</label>
                    <input type="password" name="confirm_password" class="form-control" required>
                </div>

                <?php if (!empty($error)) { ?>
                    <div class="alert alert-danger">
                        <?php echo $error; ?>
                    </div>
                <?php } ?>

                <?php if (!empty($success)) { ?>
                    <div class="alert alert-success">
                        <?php echo $success; ?>
                    </div>
                <?php } ?>

                <button type="submit" class="btn btn-primary">เปลี่ยนรหัสผ่าน</button>
            </form>
        </div>
    </section>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>