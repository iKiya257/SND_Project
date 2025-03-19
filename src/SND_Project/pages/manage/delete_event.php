<?php
include '../../config/func.php';
if (isset($_POST['id'])) {
    $id = $_POST['id'];
    $sql = "DELETE FROM events WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

$conn->close();
?>