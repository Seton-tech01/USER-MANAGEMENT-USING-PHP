<?php
require_once '../config/connection.php'; ?>

<?php if (!$checkBasicSecurity) {
  goto end;
} ?>

<?php

// Input validation
$email = trim($_POST['email']);
$subject = "Mighty Model College - Password Reset OTP";

// Security for email
validateEmptyField($email, 'EMAIL');

validateEmail($email);

// Check if email exists in the database
$query = mysqli_query($conn, "SELECT staffId, adminLastName, adminEmailAddress  FROM staff_tab WHERE adminEmailAddress = '$email'") or die(mysqli_error($conn));
$fetchQuery = mysqli_fetch_array($query);
$staffId = $fetchQuery['staffId'];
$fullName = $fetchQuery['adminLastName'];
$dbEmail = $fetchQuery['adminEmailAddress'];

if ($email !== $dbEmail) {
  $response = [
    'response' => 105,
    'success' => false,
    'message' => "This email ('$email') is not registered. Please try another Email Address"
  ];
  goto end;
}

// Generate a new OTP
$otp = rand(100000, 999999);

$body = "
  <html>
  <head>
    <title>Mighty Model College- Password Reset OTP</title>
    <style>
      .container {
      width: calc(100% - 40px);
      margin: 20px auto;
      padding: 20px;
      background-color: #f9f9f9;
      border: 1px solid #ddd;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      }
      h1 {
        color: #333;
        font-weight: bold;
      }
      .highlight {
        color: #00698f;
      }
      p {
        font-size: 12px;
        color: #666;
      }
      .otp {
        font-size: 24px;
        font-weight: bold;
        color: #00698f;
      }
    </style>
  </head>

  <body>
    <div class='container'>
    <h1>Dear, <span class='highlight'>$fullName</span></h1>
    <p>We received a request to reset your password. To proceed, please use the following One-Time Password (OTP):</p>
    <p class='otp'>$otp</p>
    <p>Please enter this OTP to reset your password. If you didn't request this, please ignore this email.</p>
    <p>Best regards,<br>Your Application Team</p>
    </div>
  </body>
  </html>
  ";

$sender = "From: FromSeton\r\n";
$sender .= "Reply-To: sendmailemma@gmail.com\r\n";
$sender .= "MIME-Version: 1.0\r\n";
$sender .= "Content-Type: text/html; charset=UTF-8\r\n";
$query = mysqli_query($conn, "UPDATE staff_tab SET otp = '$otp' WHERE staffId = '$staffId'") or die(mysqli_error($conn));

if (mail($email, $subject, $body, $sender)) {
  $response = [
    'code' => 200,
    'success' => true,
    'staffId' => $staffId,
    'email' => $email,
    'fullName' => $fullName,
    'message' => 'OTP sent successfully'
  ];
} else {
  $response['message'] = 'Failed to send email';
}

end:
echo json_encode($response);
?> 