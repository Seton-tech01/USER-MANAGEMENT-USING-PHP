<?php
require_once '../../config/connection.php';

$response = [];

if (!$checkBasicSecurity) {
    goto end;
}

$email = strtolower(trim($_POST['email']));
$password = trim($_POST['password']);

validateEmptyField($email, 'EMAIL');
validateEmptyField($password, 'PASSWORD');

$sql = "SELECT staffId, role_id, status_id, password 
        FROM staff_tab 
        WHERE email = ?";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) == 0) {
    $response = [
        'response' => 105,
        'success' => false,
        'message' => 'Invalid Credentials'
    ];
    goto end;
}

$userData = mysqli_fetch_assoc($result);

if (md5($password) !== $userData['password']) {
    $response = [
        'response' => 105,
        'success' => false,
        'message' => 'Invalid Credentials'
    ];
    goto end;
}

if ($userData['status_id'] != 1) {
    $response = [
        'response'=> 403,
        'success'=> false,
        'message'=> 'Account suspended'
    ];
    goto end;
}

$userId = $userData['staffId'];

mysqli_query($conn, "UPDATE staff_tab SET last_login = NOW() WHERE staffId = '$userId'");

$generateToken = generateStaffToken($conn, $userId, "staff");

$response = [
    'response'=> 200,
    'success'=> true,
    'message'=> 'USER LOGGED IN SUCCESSFUL!!',
    'token'=> $generateToken['token']
];

end:
echo json_encode($response);
?>