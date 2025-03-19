<?php
$host = 'db'; // ชื่อโฮสต์ของ MySQL (ใน Docker Compose ชื่อ service คือ 'db')
$user = 'root'; // ชื่อผู้ใช้ MySQL (ต้องตรงกับ MYSQL_USER ใน docker-compose.yml)
$password = 'MYSQL_ROOT_PASSWORD'; // รหัสผ่าน MySQL (ต้องตรงกับ MYSQL_PASSWORD ใน docker-compose.yml)
$database = 'snddb'; // ชื่อฐานข้อมูล (ต้องตรงกับ MYSQL_DATABASE ใน docker-compose.yml)

$conn = mysqli_connect($host, $user, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>