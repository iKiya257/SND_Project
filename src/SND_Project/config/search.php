<?php
require_once 'db.php'; // เชื่อมต่อฐานข้อมูล

header('Content-Type: application/json'); // กำหนดให้ส่งกลับเป็น JSON

$search_query = isset($_GET['q']) ? trim($_GET['q']) : '';

// ปรับปรุง SQL query โดยใช้ REPLACE เพื่อลบช่องว่างระหว่างตัวเลขและข้อความ
$sql = "
    SELECT 
        CASE 
            WHEN f.folder_id IS NOT NULL THEN f.folder_name
            ELSE t.template_name 
        END as name,
        CASE 
            WHEN f.folder_id IS NOT NULL THEN 'folder'
            ELSE 'template' 
        END as type,
        COALESCE(f.folder_id, t.folder_id) as folder_id,
        COALESCE(f.updated_at, t.created_at) as updated_at,
        u.firstname,
        u.lastname,
        CASE 
            WHEN f.folder_id IS NOT NULL THEN f.created_by
            ELSE t.uploaded_by 
        END as created_by
    FROM (
        SELECT * FROM folders WHERE 
            REPLACE(REPLACE(LOWER(folder_name), ' ', ''), '\t', '') LIKE ?
        UNION
        SELECT * FROM folders WHERE folder_id IN (
            SELECT DISTINCT folder_id FROM templates WHERE 
                REPLACE(REPLACE(LOWER(template_name), ' ', ''), '\t', '') LIKE ?
        )
    ) f
    LEFT JOIN users u ON f.created_by = u.user_id
    LEFT JOIN templates t ON t.folder_id = f.folder_id AND 
        REPLACE(REPLACE(LOWER(template_name), ' ', ''), '\t', '') LIKE ?
    WHERE 
        REPLACE(REPLACE(LOWER(f.folder_name), ' ', ''), '\t', '') LIKE ? OR 
        REPLACE(REPLACE(LOWER(t.template_name), ' ', ''), '\t', '') LIKE ?
    ORDER BY type DESC, name ASC";

// เตรียมคำสั่ง SQL
$stmt = $conn->prepare($sql);

// ตรวจสอบการเตรียมคำสั่ง SQL
if (!$stmt) {
    die(json_encode(['error' => 'Error in preparing SQL: ' . $conn->error]));
}

// ปรับปรุงค่า search_param โดยลบช่องว่างและแปลงเป็นตัวพิมพ์เล็ก
$search_param = "%" . str_replace([' ', '\t'], '', strtolower($search_query)) . "%";
$stmt->bind_param("sssss", $search_param, $search_param, $search_param, $search_param, $search_param);

// รันคำสั่ง SQL
$stmt->execute();

// รับผลลัพธ์
$result = $stmt->get_result();

$data = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = [
            'template_name' => $row['name'],
            'type' => $row['type'],
            'folder_id' => $row['folder_id'],
            'updated_at' => $row['updated_at'],
            'firstname' => $row['firstname'] ?? '-',
            'lastname' => $row['lastname'] ?? '-'
        ];
    }
}

echo json_encode($data); // ส่งกลับผลลัพธ์เป็น JSON
?>