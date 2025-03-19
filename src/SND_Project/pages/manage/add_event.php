<?php
include '../../config/func.php';
$title = $_POST['title'];
$start = $_POST['start'];
$end = $_POST['end'];
$description = $_POST['description'];

$sql = "INSERT INTO events (title, start, end, description) VALUES ('$title', '$start', '$end', '$description')";
$conn->query($sql);
$conn->close();
?>