<?php
// Database connection parameters
$host = 'db'; // ชื่อโฮสต์ของ MySQL (ใน Docker Compose ชื่อ service คือ 'db')
$user = 'root'; // ชื่อผู้ใช้ MySQL (ต้องตรงกับ MYSQL_USER ใน docker-compose.yml)
$password = 'MYSQL_ROOT_PASSWORD'; // รหัสผ่าน MySQL (ต้องตรงกับ MYSQL_PASSWORD ใน docker-compose.yml)
$database = 'snddb'; // ชื่อฐานข้อมูล (ต้องตรงกับ MYSQL_DATABASE ใน docker-compose.yml)

try {
    // Create database connection
    $conn = new PDO("mysql:host=$host;dbname=$database", $user, $password);
    // Set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Connected to database successfully<br>";
    
    // Check if admin user already exists
    $stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $email = 'admin3@gmail.com';
    $stmt->execute();
    
    if ($stmt->fetchColumn() > 0) {
        echo "Admin user already exists!";
    } else {
        // Create admin user
        $stmt = $conn->prepare("INSERT INTO users (email, password, role) VALUES (:email, :password, :role)");
        
        // Set parameters and execute
        $email = 'admin3@gmail.com';
        $password = password_hash('123', PASSWORD_DEFAULT); // Hash the password for security
        $role = 'admin';
        
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':role', $role);
        
        $stmt->execute();
        
        echo "Admin user created successfully!";
    }
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
