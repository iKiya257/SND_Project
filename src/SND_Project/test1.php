<?php
include 'config/func.php'; // ต้องแน่ใจว่ามี $conn

require_once 'vendor/autoload.php'; // โหลด PHPWord

// ดึงข้อมูลเอกสารจากฐานข้อมูล
$sql = "SELECT file_id, file_path FROM document_files";
$result = $conn->query($sql);

if (!$result) {
    die("Query failed: " . $conn->error);
}

$files = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DOCX Preview</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="container mt-5">
    <h2>Document List</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>File Name</th>
                <th>Preview</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($files as $file): ?>
                <tr>
                    <td><?= htmlspecialchars($file['file_id']) ?></td>
                    <td><?= htmlspecialchars(basename($file['file_path'])) ?></td>
                    <td>
                        <a href="preview.php?file=<?= urlencode($file['file_path']) ?>" target="_blank" class="btn btn-primary">View</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
