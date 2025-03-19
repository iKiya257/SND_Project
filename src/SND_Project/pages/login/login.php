<?php
session_start();
$error = isset($_SESSION['error']) ? $_SESSION['error'] : "";
unset($_SESSION['error']); // ล้างค่าหลังแสดงผล
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../../assets/img/logo-icon.png" rel="icon">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../assets/css/style.css">
    <title>Login</title>
</head>
<body style="
    background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('../../assets/img/img-bg.jpg') no-repeat center center;
    background-size: cover;
    font-family: 'Kanit', sans-serif;
">

    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="row border rounded-5 p-3 bg-white shadow box-area">
            <div class="col-md-6 rounded-4 d-flex justify-content-center align-items-center flex-column left-box" style="background: #F26B0F;">
                <div class="featured-image mb-3">
                    <img src="../../assets/img/loudspeaker.png" class="img-fluid" style="width: 200px;">
                </div>
                <p class="text-white fs-2">Be Verified</p>
                <small class="text-white text-center" style="width: 17rem;">Join experienced Designers on this platform.</small>
            </div>
            <div class="col-md-6 right-box">
                <div class="row align-items-center">
                    <div class="header-text mb-4">
                        <h2>ยินดีต้อนรับ</h2>
                        <p>ระบบประสานงานและจัดการเอกสารออนไลน์</p>
                    </div>

                    <!-- Alert แสดงข้อความแจ้งเตือน -->
                    <?php if (!empty($error)) : ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?= htmlspecialchars($error) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <form action="login-script.php" method="POST">
                        <div class="input-group mb-3">
                            <input type="text" id="email" name="email" class="form-control form-control-lg bg-light fs-6" placeholder="Email address" required>
                        </div>
                        <div class="input-group mb-5">
                            <input type="password" id="upassword" name="upassword" class="form-control form-control-lg bg-light fs-6" placeholder="Password" required>
                        </div>
                        <div class="input-group mb-3">
                            <button class="btn btn-lg btn-primary w-100 fs-6">Login</button>
                        </div>
                        <div class="row">
                            <small>Sign In with MS Entra ID? <a href="#">Go</a></small>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
