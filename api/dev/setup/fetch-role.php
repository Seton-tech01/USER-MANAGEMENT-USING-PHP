<?php require_once '../config/connection.php' ?>
<?php if (!$checkBasicSecurity) {goto end;}?>

<?php

    $query= "SELECT roleId, roleName FROM setup_role_tab";
        $queryCounts=mysqli_query($conn,$query) or die(mysqli_error($conn));
        $allUserCount=mysqli_num_rows($queryCounts);
        
        if($allUserCount==0){
            $response = [
                'response'=> 200,
                'success'=>false,
                'message'=> 'No Record found!!!'
            ];  
            goto end;
        }
            $response = [
                'response'=> 200,
                'success'=> true,
                'message'=> 'Role(s) FETCHED SUCCESSFUL!!',
               
            ]; 
            while ($fetchQuery = mysqli_fetch_assoc($queryCounts)) {
                    $response['data'][] = $fetchQuery;
                }

end:
echo json_encode($response);
?>