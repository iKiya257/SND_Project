<?php
$host = 'db'; // ชื่อโฮสต์ของ MySQL (ใน Docker Compose ชื่อ service คือ 'db')
$user = 'root'; // ชื่อผู้ใช้ MySQL (ต้องตรงกับ MYSQL_USER ใน docker-compose.yml)
$password = 'MYSQL_ROOT_PASSWORD'; // รหัสผ่าน MySQL (ต้องตรงกับ MYSQL_PASSWORD ใน docker-compose.yml)
$database = 'snddb'; // ชื่อฐานข้อมูล (ต้องตรงกับ MYSQL_DATABASE ใน docker-compose.yml)

$conn = mysqli_connect($host, $user, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// สร้าง user admin
$email = "admin@gmail.com";
$raw_password = "123";
$hashed_password = password_hash($raw_password, PASSWORD_DEFAULT); // เข้ารหัสรหัสผ่านด้วย password_hash
$role = "admin";
$prefix = "Admin";
$firstname = "Admin";
$lastname = "User";
$position = "Administrator";

// เช็คว่ามี user นี้อยู่แล้วหรือไม่
$check_query = "SELECT * FROM users WHERE email = '$email'";
$result = mysqli_query($conn, $check_query);

if (mysqli_num_rows($result) == 0) {
    // ถ้ายังไม่มี user นี้ ให้ทำการเพิ่ม
    $query = "INSERT INTO users (prefix, firstname, lastname, position, email, upassword, role) 
              VALUES ('$prefix', '$firstname', '$lastname', '$position', '$email', '$hashed_password', '$role')";
    
    if (mysqli_query($conn, $query)) {
        echo "Admin user created successfully";
    } else {
        echo "Error creating admin user: " . mysqli_error($conn);
    }
} else {
    echo "Admin user already exists";
}

?>

?>