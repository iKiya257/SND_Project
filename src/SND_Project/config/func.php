<?php
include 'db.php';
function fetch_user($conn, $user_id) {
    $query = "SELECT prefix, firstname, lastname FROM users WHERE user_id = ? LIMIT 1";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        return $result->fetch_assoc(); // คืนค่าข้อมูลเป็นอาร์เรย์
    } else {
        return null;
    }
}
function fetch_folder($conn) {
    $sql = "
        SELECT f.folder_id, f.folder_name, f.updated_at, u.firstname, u.lastname, u.prefix
        FROM folders f
        INNER JOIN users u ON f.created_by = u.user_id
        ORDER BY f.updated_at DESC
    ";

    $stmt = $conn->prepare($sql);
    $stmt->execute();
    return $stmt->get_result();
}

function fetch_template($conn) {
    if (!isset($_GET['folder_id']) || empty($_GET['folder_id'])) {
        return null;
    }
    
    $folder_id = intval($_GET['folder_id']);
    $sql = "SELECT t.template_id, t.template_name, t.file_path, t.created_at, u.firstname, u.lastname, u.prefix
            FROM templates t
            LEFT JOIN users u ON t.uploaded_by = u.user_id
            WHERE t.folder_id = ?";
    
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $folder_id);
        $stmt->execute();
        return $stmt->get_result();
    }
    return false;
}
function fetch_submission($conn, $user_id) {
    $sql = "SELECT 
                ds.submission_id, 
                ds.name, 
                ds.urgency, 
                ds.document_code, 
                ds.updated_at, 
                ds.status, 
                u.firstname, 
                u.lastname, 
                u.prefix,
                IF(dr.revision_count > 0, 'revision', ds.status) AS final_status
            FROM document_submission ds
            JOIN users u ON ds.sender_id = u.user_id
            LEFT JOIN (
                SELECT submission_id, COUNT(*) AS revision_count
                FROM document_recipient
                WHERE status = 'revision'
                GROUP BY submission_id
            ) dr ON ds.submission_id = dr.submission_id
            WHERE ds.sender_id = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    return $result;
}
function fetch_inbox($conn, $user_id) {
    $sql = "
        (SELECT ds.submission_id, ds.name, ds.sender_id, ds.document_code, ds.urgency, ds.updated_at, 
                ds.status AS ds_status, dr.status AS dr_status, 
                u.firstname, u.lastname, u.prefix, dr.revision_reason, dr.receiver_id
         FROM document_submission ds
         JOIN document_recipient dr ON ds.submission_id = dr.submission_id
         LEFT JOIN users u ON ds.sender_id = u.user_id
         WHERE (dr.receiver_id = ? OR dr.department_id IN 
               (SELECT department_id FROM user_department WHERE user_id = ?))
           AND ds.status NOT IN ('cancel')
           AND dr.status NOT IN ('removed')
           AND EXISTS (
               SELECT 1 
               FROM document_recipient dr2 
               WHERE dr2.submission_id = ds.submission_id 
               AND dr2.status NOT IN ('removed')
           )
         ORDER BY ds.updated_at DESC
        )
        UNION
        (SELECT ds.submission_id, ds.name, ds.sender_id, ds.document_code, ds.urgency, ds.updated_at, 
                ds.status AS ds_status, dr.status AS dr_status, 
                u.firstname, u.lastname, u.prefix, dr.revision_reason, dr.receiver_id
         FROM document_submission ds
         JOIN document_recipient dr ON ds.submission_id = dr.submission_id
         LEFT JOIN users u ON ds.sender_id = u.user_id
         WHERE ds.sender_id = ? 
         AND dr.status = 'revision'
         AND ds.status NOT IN ('cancel')
         AND EXISTS (
               SELECT 1 
               FROM document_recipient dr2 
               WHERE dr2.submission_id = ds.submission_id 
               AND dr2.status NOT IN ('removed')
           )
         ORDER BY ds.updated_at DESC
        )";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iii", $user_id, $user_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result;
}

function fetch_users($conn) {
    $sql = "SELECT * FROM users";
    $result = mysqli_query($conn, $sql);
    if (!$result) {
        return false;
    }
    return $result;
}

function fetch_department($conn) {
    $sql = "SELECT * FROM departments"; 
    $result = mysqli_query($conn, $sql);
    if (!$result) {
        return false;
    }
    return $result;
}

function fetch_userdepartment($conn, $user_id) {
    $sql = "SELECT department_id FROM user_department WHERE user_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    return $row ? $row['department_id'] : null;
}

function fetch_user_department($conn) {
    if (!isset($_GET['id']) || empty($_GET['id'])) {
        return null;
    }
    
    $department_id = intval($_GET['id']);
    $sql = "SELECT u.user_id, u.firstname, u.lastname, u.prefix, u.position
            FROM users u
            LEFT JOIN user_department ud ON u.user_id = ud.user_id
            WHERE ud.department_id = ?";
    
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $department_id);
        $stmt->execute();
        return $stmt->get_result();
    }
    return false;
}

function fetch_user_by_id($conn, $user_id) {
    $sql = "SELECT * FROM users WHERE user_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_assoc($result); // คืนค่าข้อมูลผู้ใช้ที่ตรงกัน
}

function fetch_department_count($conn) {
    $sql = "SELECT d.department_id, d.department_name, COUNT(ud.user_id) AS total_user
            FROM departments d
            LEFT JOIN user_department ud ON d.department_id = ud.department_id
            GROUP BY d.department_id";
    return $conn->query($sql);
}

function fetch_purpose($conn) {

    // คำสั่ง SQL เพื่อดึงข้อมูลจากตาราง purposes
    $sql = "SELECT * FROM purposes";
    $result = $conn->query($sql);

    return $result;
}
function generateDocCode($conn) {
    $date = date('Ymd');
    $query = "SELECT COUNT(*) AS count FROM document_submission WHERE document_code LIKE '$date-%'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    $count = $row['count'] + 1;
    return $date . '-' . str_pad($count, 3, '0', STR_PAD_LEFT);
}
function fetch_recDetail($conn, $submission_id) {
    $sql = "SELECT u.firstname, u.lastname, u.prefix, d.department_name, ds.purpose_id, p.purpose_name, dr.status, dr.receiver_id, dr.department_id
            FROM document_recipient dr
            LEFT JOIN users u ON dr.receiver_id = u.user_id
            LEFT JOIN departments d ON dr.department_id = d.department_id
            LEFT JOIN document_submission ds ON dr.submission_id = ds.submission_id
            LEFT JOIN purposes p ON ds.purpose_id = p.purpose_id
            WHERE dr.submission_id = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $submission_id);
    $stmt->execute();
    return $stmt->get_result();
}

function fetch_fileDetail($conn, $submission_id) {
    $sql = "SELECT file_id, file_path FROM document_files WHERE submission_id = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $submission_id);
    $stmt->execute();
    return $stmt->get_result();
}
function fetch_pending($conn, $user_id) {
    $sql = "SELECT ds.submission_id, ds.name, ds.sender_id, ds.document_code, ds.urgency, ds.updated_at, 
                    ds.status AS ds_status, dr.status AS dr_status, 
                    u.firstname, u.lastname, u.prefix, dr.revision_reason, dr.receiver_id
            FROM document_submission ds
            JOIN document_recipient dr ON ds.submission_id = dr.submission_id
            LEFT JOIN users u ON ds.sender_id = u.user_id
            WHERE (dr.receiver_id = ? OR dr.department_id IN 
                (SELECT department_id FROM user_department WHERE user_id = ?))
            AND dr.status != 'removed'
            AND dr.status != 'revision'
            AND dr.status != 'completed'
            ORDER BY ds.updated_at DESC";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $user_id, $user_id);
    $stmt->execute();
    return $stmt->get_result();
}

function fetch_revision($conn, $user_id) {
    $sql = "SELECT 
                ds.submission_id, 
                ds.name, 
                ds.document_code, 
                ds.urgency, 
                ds.updated_at, 
                u.firstname, 
                u.lastname, 
                u.prefix
            FROM document_submission ds
            JOIN document_recipient dr ON ds.submission_id = dr.submission_id
            JOIN users u ON dr.receiver_id = u.user_id
            WHERE ds.sender_id = ? 
            AND dr.status = 'revision'";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    return $result;
}

function fetch_completed($conn, $user_id) {
    $sql = "SELECT ds.submission_id, ds.name, ds.sender_id, ds.document_code, ds.urgency, ds.updated_at, 
                    ds.status AS ds_status, dr.status AS dr_status, 
                    u.firstname, u.lastname, u.prefix, dr.revision_reason, dr.receiver_id
            FROM document_submission ds
            JOIN document_recipient dr ON ds.submission_id = dr.submission_id
            JOIN users u ON dr.receiver_id = u.user_id
            WHERE ds.sender_id = ? 
            AND dr.status = 'completed'";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    return $stmt->get_result();
}
// เพิ่มฟังก์ชันสำหรับดึงการแจ้งเตือน
function fetch_notifications($conn, $user_id) {
    $notifications_query = "SELECT n.*, ds.submission_id, ds.name as document_name
                          FROM notifications n
                          LEFT JOIN document_submission ds ON n.submission_id = ds.submission_id
                          WHERE n.user_id = ? AND n.is_read = 0 
                          ORDER BY n.created_at DESC LIMIT 5";
    $stmt = $conn->prepare($notifications_query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    return $stmt->get_result();
}


