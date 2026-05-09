<?php require_once '../../config/connection.php' ?>
<?php if (!$checkBasicSecurity) {
    goto end;
} ?>

<?php
if (!isset($headers['Authorization'])) {
    $response = [
        'response' => 401,
        'success' => false,
        'message' => "No token provided"
    ];
    goto end;
}
$authHeader = $headers['Authorization'];
list($type, $token) = explode(" ", $authHeader);
$token = trim($token);
$user = getAuthenticatedUser($conn);


$userId = $_GET['userId'];
$title = strtoupper(trim($_POST["title"]));
$firstName = strtoupper(trim($_POST["firstName"]));
$middleName = strtoupper(trim($_POST["middleName"]));
$lastName = strtoupper(trim($_POST["lastName"]));
$email = trim($_POST["emailAddress"]);
$phone = trim($_POST["phoneNumber"]);
$address = trim($_POST["homeAddress"]);
$passport = $_FILES['passport']['name']  ?? ''; 

validateEmptyField($userId, 'User Id');
validateEmptyField($firstName, 'First Name');
validateEmptyField($lastName, 'Last Name');
validateEmptyField($email, 'Email Address');
validateEmptyField($phone, 'Phone Number');
validateEmptyField($address, 'address');
validateEmail($email);
validateDigit($phone, 'Phone Number');
phoneLenght($phone);

//////////////check if email address already exist//////////////////////////
$query = mysqli_query($conn, "SELECT email FROM users_tab WHERE email = '$email' AND userId != '$userId'") or die(mysqli_error($conn));
$checkEmailExists = mysqli_num_rows($query);
if ($checkEmailExists > 0) {
    $response = [
        'response' => 110,
        'success' => false,
        'message' => "This email ('$email') is already in use. Please try another Email Address."
    ];
    goto end;
}

// Build base update query (without passport)
$update = "UPDATE users_tab SET 
    userId = '$userId',
    titleId = '$title',
    firstName = '$firstName',
    middleName = '$middleName',
    lastName = '$lastName',
    email = '$email',
    phoneNumber = '$phone',
    homeAddress = '$address',
    genderId = '$genderId',
    statusId = '1',
    updatedTime = NOW()";

// If passport uploaded, add to query
if (!empty($passport)) {
    $update .= ", passport = ''"; 
}

$update .= " WHERE userId = '$userId'";
mysqli_query($conn, $update) or die(mysqli_error($conn));

// Process passport upload only if new file uploaded
if (!empty($passport)) {
    $allowedExts = ["jpg", "jpeg", "png", "gif", "webp"];
    $extension = pathinfo($passport, PATHINFO_EXTENSION);
    if (in_array(strtolower($extension), $allowedExts)) {
        // delete old
        $query = mysqli_query($conn, "SELECT passport FROM users_tab WHERE userId = '$userId'");
        $fetchQuery = mysqli_fetch_assoc($query);
        $dbPassport = $fetchQuery['passport'];
        if (!empty($dbPassport)) {
            unlink($usersProfilePixPath . $dbPassport);
        }
        $newPassportName = $userId . $passport;
        $uploadPath = $usersProfilePixPath . $newPassportName;
        move_uploaded_file($_FILES["passport"]["tmp_name"], $uploadPath);
        // now update passport in DB
        mysqli_query($conn, "UPDATE users_tab SET passport = '$newPassportName' WHERE userId = '$userId'") or die(mysqli_error($conn));
    }
}


$alertSequence = $callclass->_getSequenceCount($conn, 'ALERT');
$alertArray = json_decode($alertSequence, true);
$alertNo = $alertArray[0]['no'];
/// generate log ///////
$alertId = 'ALERT' . $alertNo . date("Ymdhis");
$action = 'USER UPDATE';
$description = 'The administrator successfully updated a user account within the system with USERID: ' . $userId . '.';
$performedBy = $user['firstName'] . ' ' . $user['lastName'];
$userType = 'ADMIN';
$roleId = ($user['roleId']);
$ipAddress   = $_SERVER['REMOTE_ADDR'] ?? '';
$browserName = $_SERVER['HTTP_USER_AGENT'] ?? '';
$systemName  = php_uname('s');
$logActivity = logActivities($conn, $alertId, $action, $description, $performedBy, $userType, $roleId, $ipAddress, $browserName, $systemName);

$response = [
    'response' => 200,
    'success' => true,
    'message' => "Record Updated successfully",
];
end:
echo json_encode($response);
?>