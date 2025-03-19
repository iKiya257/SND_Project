<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: SND_Project/pages/login/login.php");
    exit();
}
?>