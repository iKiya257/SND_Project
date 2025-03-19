<?php
$uploadDir = "../../uploads/";
$response = ["success" => false, "message" => ""];

if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_FILES["files"])) {
    $files = $_FILES["files"];
    $uploadedFiles = [];

    for ($i = 0; $i < count($files["name"]); $i++) {
        $fileName = basename($files["name"][$i]);
        $filePath = $uploadDir . $fileName;

        if (move_uploaded_file($files["tmp_name"][$i], $filePath)) {
            $uploadedFiles[] = $fileName;
        }
    }

    if (!empty($uploadedFiles)) {
        $response["success"] = true;
        $response["message"] = "Uploaded successfully: " . implode(", ", $uploadedFiles);
    } else {
        $response["message"] = "No files were uploaded.";
    }
}

echo json_encode($response);
?>
