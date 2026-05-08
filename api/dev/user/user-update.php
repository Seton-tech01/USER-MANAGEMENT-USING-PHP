<?php require_once '../config/connection.php' ?>
<?php if (!$checkBasicSecurity) {
    goto end;
} ?>

<?php
$staffId = $_GET['staffId'];
$title = strtoupper(trim($_POST["title"]));
$firstName = strtoupper(trim($_POST["firstName"]));
$middleName = strtoupper(trim($_POST["middleName"]));
$lastName = strtoupper(trim($_POST["lastName"]));
$email = trim($_POST["emailAddress"]);
$phone = trim($_POST["phoneNumber"]);
$address = trim($_POST["homeAddress"]);
$role = strtoupper(trim($_POST["roleId"]));
$statusId = trim($_POST["statusId"]);
$passport = $_FILES['passport']['name']  ?? ''; 
$userName = strtoupper(trim($_POST["userName"]));
$userId = trim($_POST["userId"]);

validateEmptyField($staffId, 'Staff Id');
validateEmptyField($firstName, 'First Name');
validateEmptyField($lastName, 'Last Name');
validateEmptyField($email, 'Email Address');
validateEmptyField($phone, 'Phone Number');
validateEmptyField($address, 'address');
validateEmptyField($role, 'Role');
validateEmptyField($statusId, 'Status');
validateEmail($email);
validateDigit($phone, 'Phone Number');
phoneLenght($phone);

//////////////check if email address already exist//////////////////////////
$query = mysqli_query($conn, "SELECT email FROM staff_tab WHERE email = '$email' AND staffId != '$staffId'") or die(mysqli_error($conn));
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
$update = "UPDATE staff_tab SET 
    titleId = '$title',
    firstName = '$firstName',
    middleName = '$middleName',
    lastName = '$lastName',
    email = '$email',
    phone_number = '$phone',
    home_address = '$address',
    role_id = '$role',
    status_id = '$statusId',
    updated_time = NOW()";

// If passport uploaded, add to query
if (!empty($passport)) {
    $update .= ", passport = ''"; 
}

$update .= " WHERE staffId = '$staffId'";
mysqli_query($conn, $update) or die(mysqli_error($conn));

// Process passport upload only if new file uploaded
if (!empty($passport)) {
    $allowedExts = ["jpg", "jpeg", "png", "gif", "webp"];
    $extension = pathinfo($passport, PATHINFO_EXTENSION);
    if (in_array(strtolower($extension), $allowedExts)) {
        // delete old
        $query = mysqli_query($conn, "SELECT passport FROM staff_tab WHERE staffId = '$staffId'");
        $fetchQuery = mysqli_fetch_assoc($query);
        $dbPassport = $fetchQuery['passport'];
        if (!empty($dbPassport)) {
            unlink($adminProfilePixPath . $dbPassport);
        }
        $newPassportName = $staffId . $passport;
        $uploadPath = $adminProfilePixPath . $newPassportName;
        move_uploaded_file($_FILES["passport"]["tmp_name"], $uploadPath);
        // now update passport in DB
        mysqli_query($conn, "UPDATE staff_tab SET passport = '$newPassportName' WHERE staffId = '$staffId'") or die(mysqli_error($conn));
    }
}

$response = [
    'response' => 200,
    'success' => true,
    'message' => "Record Updated successfully",
];

end:
echo json_encode($response);
?>