<?php require_once '../../config/connection.php';?>
<?php if (!$checkBasicSecurity) {goto end;}?>
<?php

$user = getAuthenticatedStaff($conn);
if (!$user) {
    exit(json_encode([
        'success' => false,
        'message' => 'Unauthenticated'
    ]));
}
$staffId = $user['staffId'];
$select = "
    SELECT a.staffId, a.firstName, a.lastName, a.passport, a.email, 
    a.last_login, b.statusId, b.statusName, c.roleName, c.roleId, d.titleName
    FROM staff_tab a
    LEFT JOIN setup_status_tab b ON a.status_id = b.statusId
    LEFT JOIN setup_role_tab c ON a.role_id = c.roleId
    LEFT JOIN setup_title_tab d ON a.titleId = d.titleId
    WHERE a.staffId = '$staffId'
";
$query = mysqli_query($conn, $select);
if (!$query) {
    $response = [
        'response'=> 500,
        'success'=> false,
        'message'=> 'Database error: ' . mysqli_error($conn)
    ];
    goto end;
}

$data = mysqli_fetch_assoc($query);
$response = [
    'response'=> 200,
    'success'=> true,
    'message'=> 'STAFF PROFILE FETCHED SUCCESSFUL!!',
    'data'=> $data,
    'documentStoragePath'=> $documentStoragePath.'/staff-pics',
];

end:
echo json_encode($response);
?>
