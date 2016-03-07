<?php

include_once('class_functions.php');

$obj1   = new commonFunctions();
$dbh    = $obj1->dbh;
$server_url = $obj1->getServerUrl();

$status = $_REQUEST['status'];
$valid_status = false;
$upload_path = '';


// updatestatus image upload
if( $status == 'tutor_profile_picture' ){
    $valid_status = true;
    $upload_path = "../uploads/profile_pics/";
}else if($status == 'student_profile_picture'){
    $valid_status = true;
    $upload_path = "../uploads/profile_pics/";
}elseif($status == 'profile_picture_edit'){
    $valid_status = true;
    $upload_path = "../uploads/profile_pics/";
}



if($valid_status){
    $filename   = $_FILES["userfile"]["name"];
    $createdon  = $obj1->getCurrentDate();
    $random     = rand(0,999);
    $filename  = trim($createdon.$random.$filename);

    move_uploaded_file($_FILES["userfile"]["tmp_name"],$upload_path.$filename);
    echo $filename;
}

?>