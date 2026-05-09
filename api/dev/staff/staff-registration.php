<?php require_once '../config/connection.php' ?>
<?php if (!$checkBasicSecurity) {goto end;} ?>

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

//Input fields
$titleId = strtoupper(trim($_POST["titleId"]));
$firstName = strtoupper(trim($_POST["firstName"]));
$middleName = strtoupper(trim($_POST["middleName"]));
$lastName = strtoupper(trim($_POST["lastName"]));
$emailAddress = trim($_POST["emailAddress"]);
$phoneNumber = trim($_POST["phoneNumber"]);
$homeAddress = trim(trim($_POST["homeAddress"]));
$qualificationId =trim($_POST["qualificationId"]);
$genderId = trim($_POST["genderId"]);
$roleId = trim($_POST["roleId"]);
$passport = trim($_FILES["passport"]['name']);


validateEmptyField($titleId, 'Title');
validateEmptyField($firstName, 'First Name');
validateEmptyField($lastName, 'Last Name');
validateEmptyField($emailAddress, 'Email Address');
validateEmptyField($phoneNumber, 'Phone Number');
validateEmptyField($roleId, 'Role');
validateEmail($emailAddress);
validateDigit($phoneNumber, 'Phone Number');
phoneLenght($phoneNumber);

$allowedExts = array("jpg", "jpeg", "JPEG", "JPG", "gif", "png", "PNG", "GIF", "webp", "WEBP");
$extension = pathinfo($_FILES['passport']['name'], PATHINFO_EXTENSION);
if (!in_array(($extension), $allowedExts)) {
    $response = [
        'response' => 111,
        'success' => false,
        'message' => 'ERROR! Input passport to continue'
    ];
    goto end;
}

//////////////check if email address already exist//////////////////////////
$query = mysqli_query($conn, "SELECT email FROM staff_tab WHERE email = '$emailAddress'") or die(mysqli_error($conn));
$checkEmailExists = mysqli_num_rows($query);
if ($checkEmailExists > 0) {
    $response = [
        'response' => 110,
        'success' => false,
        'message' => "This email ('$emailAddress') is already in use. Please try another Email Address."
    ];
    goto end;
}

//////////////geting sequence//////////////////////////
$sequence = $callclass->_getSequenceCount($conn, 'STAFF');
$array = json_decode($sequence, true);
$no = $array[0]['no'];

/// generate staffId ///////
$staffId = 'STAFF' . $no . date("Ymdhis");
$passport = $staffId . $passport;
$uploadPath = $adminProfilePixPath . $passport;

if (!move_uploaded_file($_FILES["passport"]["tmp_name"], $uploadPath)) {
    $response = [
        'response' => 112,
        'success' => false,
        'message' => 'PICTURE UPLOAD ERROR! Contact your Engineer For Help'
    ];
    goto end;
}

$inputPassword = (md5($lastName));

//// insert into staff_tab ////
mysqli_query($conn, "INSERT INTO staff_tab(staffId, titleId, firstName,  middleName, lastName, email, phone_number, home_address, gender_id, qualification_id,  status_id, role_id, password, passport, created_time) 
VALUES ('$staffId', '$title', '$firstName','$middleName', '$lastName', '$emailAddress', '$phoneNumber', '$homeAddress', '$genderId','$qualificationId', '1', '$roleId', '$inputPassword','$passport', NOW())") or die(mysqli_error($conn));



$alertSequence = $callclass->_getSequenceCount($conn, 'ALERT');
$alertArray = json_decode($alertSequence, true);
$alertNo = $alertArray[0]['no'];
/// generate log ///////
$alertId = 'ALERT' . $alertNo . date("Ymdhis");
$action = 'STAFF REGISTRATION';
$description = 'The administrator successfully completed the registration process, 
resulting in the creation of a new account within the system with STAFFID: ' . $staffId . '.';
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
    'message' => "Staff registered successfully",
];

end:
echo json_encode($response);
