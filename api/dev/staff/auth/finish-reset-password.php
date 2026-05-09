<?php require_once '../../config/connection.php';?>

<?php if (!$checkBasicSecurity) {goto end;}?>

<?php

// Input validation
$staffId = trim($_POST['staffId']);
$otp = trim($_POST['otp']);
$newPassword = trim($_POST['newPassword']);
$confirmPassword = trim($_POST['confirmPassword']);


// Security for email
validateEmptyField($otp, 'OTP');
validateEmptyField($newPassword, 'NEW PASSWORD');
validateEmptyField($confirmPassword, 'CONFIRM PASSWORD');

// ENSURE NEW PASSWORD AND CONFIRM PASSWORD MATCH
if ($newPassword !== $confirmPassword) {
    $response = [
        'response' => 400,
        'success' => false,
        'message' => "New Password and Confirm Password do not match."
    ];
    goto end;
}

   // Check if OTP exists
    $query = mysqli_query($conn, "SELECT * FROM otp_tab WHERE userId = '$staffId' AND otp_code = '$otp' AND expires_at > NOW()");
    $CheckEmailExists= mysqli_num_rows($query);
    if ($CheckEmailExists == 0) {
        $response = [
            'response' => 105,
            'success' => false,
            'message' => "This OTP is not correct. Please try another OTP"
        ];
        goto end;
    }
    $hash = md5($newPassword);

    // Update the password in the database
    $updateQuery = mysqli_query($conn, "UPDATE staff_tab SET password = '$hash' WHERE staffId = '$staffId'") or die(mysqli_error($conn));
    if (!$updateQuery) {
        $response = [
            'response' => 500,
            'success' => false,
            'message' => 'Database error: ' . mysqli_error($conn)
        ];
        goto end;
    }else {
        $response = [
            'response' => 200,
            'success' => true,
            'message' => 'Password Updated successfully'
        ];
    }


end:
echo json_encode($response);
?> 