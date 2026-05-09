<?php require_once '../../config/connection.php';?>
<?php if (!$checkBasicSecurity) {goto end;}?>
<?php

$user = getAuthenticatedUser($conn);
if (!$user) {
    exit(json_encode([
        'success' => false,
        'message' => 'Unauthenticated'
    ]));
}
$userId = $user['userId'];
$select = "
    SELECT a.userId, a.firstName, a.lastName, a.passport, a.email, 
     b.statusId, b.statusName, d.titleName
    FROM users_tab a
    LEFT JOIN setup_status_tab b ON a.statusId = b.statusId
    LEFT JOIN setup_title_tab d ON a.titleId = d.titleId
    WHERE a.userId = '$userId'
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
    'message'=> 'USER PROFILE FETCHED SUCCESSFUL!!',
    'data'=> $data,
    'documentStoragePath'=> $documentStoragePath.'/user-pics',
];

end:
echo json_encode($response);
?>
