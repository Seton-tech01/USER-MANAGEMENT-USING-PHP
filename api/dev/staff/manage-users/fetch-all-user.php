<?php require_once '../../config/connection.php' ?>
<?php if (!$checkBasicSecurity) {goto end;}?>
<?php


$user = getAuthenticatedUser($conn);
if (!$user) {
    exit(json_encode([
        'success' => false,
        'message' => 'Unauthenticated'
    ]));
}

$query = fetchUsersData($conn, "");
if (!$query) {
    $response = [
        'success' => false,
        'message' => 'Query failed: ' . mysqli_error($conn)
    ];
    goto end;
}

$queryCounts= mysqli_num_rows($query);
if ($queryCounts == 0) {
    $response = [
        'success' => false,
        'message' => 'No staff found'
    ];
    goto end;
}
$response = [
    'success' => true,
    'message' => "Staff Fetched Successfully",
];

if ($staffId == '') {
    $response['totalStaff'] = $queryCounts;
}
$response['data']=array();
while ($fetchQuery = mysqli_fetch_assoc($query)) {
    $fetchQuery['documentStoragePath'] = $documentStoragePath."/staff-pics";
    $response['data'][]=$fetchQuery;
}

end:
echo json_encode($response);
?>