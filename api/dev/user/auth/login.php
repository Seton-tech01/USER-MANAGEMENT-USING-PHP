<?php
require_once '../../config/connection.php';
?>

<?php if (!$checkBasicSecurity) {goto end;}?>

<?php
$email = strtoupper(trim($_POST['email']));
$password = trim($_POST['password']);

//Security for name
validateEmptyField($email, 'EMAIL');
validateEmptyField($password, 'PASSWORD');

// Check if email exists
$emailCheck = mysqli_query($conn, "SELECT email FROM staff_tab WHERE email = '$email'") or die(mysqli_error($conn));
$CheckEmailExists = mysqli_num_rows($emailCheck);
if ($CheckEmailExists == 0) {
    $response = [
        'response' => 105,
        'success' => false,
        'message' => "Invalid Credentials"
    ];
    goto end;
}

$inputPassword = (md5($password));

// Select with all info
$select = "
    SELECT staffId, role_id, status_id
    FROM staff_tab 
    WHERE email = '$email' AND password = '$inputPassword'
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
$allUserCount = mysqli_num_rows($query);
if (!$allUserCount) {
    $response = [
        'response'=> 200,
        'success'=> false,
        'message'=> 'Incorrect Password, Kindly enter the correct Password!!!'
    ];
    goto end;
}

$userData = mysqli_fetch_assoc($query);

// Check statusId
if ($userData['status_id'] != 1) {
    $response = [
        'response'=> 403,
        'success'=> false,
        'message'=> 'You are suspended and you don\'t have access to this application. Visit the management.'
    ];
    goto end;
}
$roleId = $userData['role_id'];
$userId = $userData['staffId'];


// If not suspended, update login time
$update = mysqli_query($conn, "UPDATE staff_tab SET last_login = NOW() WHERE email = '$email';");

$generateToken = generateStaffToken($conn , $userId , "staff");
if (!$generateToken['success']) {
    $response = [
        'response'=> 200,
        'success'=> false,
        'message'=> $generateToken['message'],
    ];
    goto end;
}
$token = $generateToken['token'];
// Prepare response
$response = [
    'response'=> 200,
    'success'=> true,
    'message'=> 'USER LOGGED IN SUCCESSFUL!!',
    'token'=> $token
];

end:
echo json_encode($response);
?>
