<?php
$appName="Remo Anglican College ";
$appDescription ="This is a School management application";
$apiKey = isset($_SERVER['HTTP_APIKEY']) ? $_SERVER['HTTP_APIKEY'] : null;
$expectedApiKey = '33333333335-5432-xr3x4t-3tzyh654-y454';
$websiteUrl = 'http://localhost/user-management';
$studentProfilePixPath = '../../../uploaded-files/dev/student-pics/';
$adminProfilePixPath = '../../uploaded-files/dev/staff-pics/';
$documentStoragePath=$websiteUrl."/api/uploaded-files/dev";

///// check for API security
 $checkBasicSecurity=true;
if ($apiKey != $expectedApiKey) {
    $response = [
        'response' => 401,
        'success' => false,
        'message' => 'SECURITY ACCESS DENIED! You are not allowed to execute this command due to a security breach.'
    ];
    $checkBasicSecurity=false;
}
?>

