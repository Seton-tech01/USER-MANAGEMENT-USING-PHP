<?php

///////////////////GENERAL FUNCTIONS////////////////////////////////
class allClass
{   ///COUNTER
    function _getSequenceCount($conn, $counterId)
    {
        // lock and get current value
        $count = mysqli_fetch_array(mysqli_query(
            $conn,
            "SELECT counter_value FROM counter_tab WHERE counter_id = '$counterId' FOR UPDATE"
        ));

        // increment
        $num = $count[0] + 1;

        // update DB
        mysqli_query(
            $conn,
            "UPDATE counter_tab SET counter_value = '$num' WHERE counter_id = '$counterId'"
        ) or die(mysqli_error($conn));

        // always pad to 4 digits with leading zeros: 0001, 0010, 0123, 1234
        $no = sprintf('%04d', $num);

        // return JSON string
        return '[{"no":"' . $no . '"}]';
    }

}
$callclass = new allClass();


/// field validation
function validateEmptyField($field, $fieldName)
{
    if (empty($field)) {
        echo json_encode([
            'response' => 400,
            'success' => false,
            'message' => "$fieldName REQUIRED! Check the fields and try again",
        ]);
        exit;
    }
}

/// email validation
function validateEmail($emailAddress)
{
    if (!filter_var($emailAddress, FILTER_VALIDATE_EMAIL)) {
        echo json_encode([
            'response' => 104,
            'success' => false,
            'message' => "$emailAddress is not valid."
        ]);
        exit;
    }
}

//// function for digit Validation
function validateDigit($field, $fieldName)
{
    if (!is_numeric($field)) {
        echo json_encode([
            'response' => 103,
            'success' => false,
            'message' => "INVALID $fieldName! ENTER ONLY DIGITS."
        ]);
        exit;
    }
}

//// function for PHONE LENGTH
function phoneLenght($phoneNumber)
{
    if (strlen($phoneNumber) != 11) {
        echo json_encode([
            'response' => 104,
            'success' => false,
            'message' => "INVALID PHONE NUMBER! NUMBER MUST BE EXACTLY 11 DIGITS."
        ]);
        exit;
    }
}


//////////////////////// STUDENT FUNCTION///////////////////////////////////////////////////
///FETCH STUDENTS
function fetchUsersData($conn, $userId)
{
    if ($userId != '') {
        return mysqli_query($conn, "
            SELECT a.userId, a.firstName, a.middleName, a.lastName, a.email, a.phoneNumber, a.homeAddress, a.genderId, b.genderName,
            a.statusId, c.statusName, a.passport, a.createdTime 
            FROM users_tab a, setup_gender_tab b, setup_status_tab c
            WHERE a.genderId = b.genderId
            AND a.statusId = c.statusId
            AND a.userId = '$userId'
        ");
    }

    return mysqli_query($conn, "
        SELECT DISTINCT 
        a.userId, 
        a.firstName, 
        a.middleName, 
        a.lastName, 
        a.email, 
        a.phoneNumber, 
        a.homeAddress,
        a.genderId, b.genderName, 
        a.statusId, c.statusName, 
        a.passport, 
        a.createdTime 
    FROM users_tab a
    INNER JOIN setup_gender_tab b ON a.genderId = b.genderId
    INNER JOIN setup_status_tab c ON a.statusId = c.statusId;"
);
}

/////////////////////ALL STAFF FUNCTION//////////////////////////////////////////////
function fetchStaffData($conn, $staffId, $roleId)
{ 
if ($staffId != '') {
    $staffId = mysqli_real_escape_string($conn, $staffId);
    $sql = "SELECT 
    a.staffId, a.firstName, a.lastName, a.passport, 
    a.email, a.last_login,
    d.statusId, d.statusName, e.roleId, e.roleName, g.titleId, g.titleName
    FROM staff_tab a
    LEFT JOIN setup_status_tab d ON a.status_id = d.statusId
    LEFT JOIN setup_role_tab e ON a.role_id = e.roleId
    LEFT JOIN setup_title_tab g ON a.titleId = g.titleId
    WHERE a.staffId = '$staffId' ";

    } else {
   $sql = "SELECT
    a.staffId, a.firstName, a.lastName, a.passport, 
    a.email, a.last_login,
    d.statusId, d.statusName, e.roleId, e.roleName, g.titleId, g.titleName
    FROM staff_tab a
    LEFT JOIN setup_status_tab d ON a.status_id = d.statusId
    LEFT JOIN setup_role_tab e ON a.role_id = e.roleId
    LEFT JOIN setup_title_tab g ON a.titleId = g.titleId
    ";

    }
    $query = mysqli_query($conn, $sql);

    if (!$query) {
        die("Query Error: " . mysqli_error($conn));
    }

    return $query;
}

//// function to log activities
function logActivities($conn, $alertId, $action, $description, $performedBy, $userType, $roleId, $ipAddress, $browserName, $systemName){
     mysqli_query($conn, "INSERT INTO activity_log_tab(alertId, action, description,  performedBy, userType, roleId, ipAddress, browserName, systemName, created_time) 
    VALUES ('$alertId', '$action', '$description','$performedBy', '$userType', '$roleId', '$ipAddress', '$browserName', '$systemName', NOW())") or die(mysqli_error($conn));
}

///////////////////GENERATE TOKEN////////////////////////////////
function generateStaffToken(mysqli $conn, string $staffId, string $userType): array
{
   
    $token = bin2hex(random_bytes(32)); 
    $timestamp = date('Y-m-d H:i:s');;
    $deviceId  = php_uname('s');
    
    // Delete existing token for this user and device
    mysqli_query($conn, "DELETE FROM personal_access_tokens WHERE user_id = '$staffId' AND device_id = '$deviceId'");
    
    //Insert new token
    $insert = mysqli_query($conn, "INSERT INTO personal_access_tokens (token, user_id, device_id, user_type, created_time) VALUES ('$token', '$staffId', '$deviceId', '$userType','$timestamp')") or die(mysqli_error($conn));
    
    if (!$insert) {
        return ['success' => false, 'message' => 'Failed to generate token'];
    }
    
    return ['success' => true, 'token' => $token];
}

function getAuthenticatedUser($conn)
{
    $headers = getallheaders();

    if (!isset($headers['Authorization'])) {
        return null;
    }

    list($type, $token) = explode(" ", $headers['Authorization']);

    if ($type !== "Bearer") {
        return null;
    }

    $token = trim($token);

    $sql = "
        SELECT s.userId, s.firstName, s.lastName, s.statusId
        FROM users_tab s
        INNER JOIN personal_access_tokens t ON s.userId = t.user_id
        WHERE t.token = '$token'
        LIMIT 1
    ";

    $query = mysqli_query($conn, $sql);

    if (!$query || mysqli_num_rows($query) == 0) {
        return null;
    }

    return mysqli_fetch_assoc($query);
}

function getAuthenticatedStaff($conn)
{
    $headers = getallheaders();

    if (!isset($headers['Authorization'])) {
        return null;
    }

    list($type, $token) = explode(" ", $headers['Authorization']);

    if ($type !== "Bearer") {
        return null;
    }

    $token = trim($token);

    $sql = "
        SELECT s.staffId, s.firstName, s.lastName, s.role_id as roleId, s.status_id as statusId
        FROM staff_tab s
        INNER JOIN personal_access_tokens t ON s.staffId = t.user_id
        WHERE t.token = '$token'
        LIMIT 1
    ";

    $query = mysqli_query($conn, $sql);

    if (!$query || mysqli_num_rows($query) == 0) {
        return null;
    }

    return mysqli_fetch_assoc($query);
}
?>