<?php
include '../../config/func.php';
$sql = "SELECT * FROM events";
$result = $conn->query($sql);

$events = [];
$colors = '#F26B0F';

while ($row = $result->fetch_assoc()) {
    $events[] = [
        'id' => $row['id'],
        'title' => $row['title'],
        'start' => $row['start'],
        'end' => $row['end'],
        'description' => $row['description'],
        'color' => $colors
    ];
}

echo json_encode($events);
$conn->close();
?>
