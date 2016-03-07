<?php
include_once('class_functions.php');


class studentClass {

    function studentList($userid,$adminid,$sub_subjectid,$auth_token){
        $obj1 = new commonFunctions();
        $dbh  = $obj1->dbh;

        $status  = "failed";
        $error   = "";
        $details = [];
        $user_details = array();

        $user_details = $obj1->checkAuthToken($userid,$adminid,$auth_token);
        if(sizeof($user_details)>0){
            $selectUser = $dbh->prepare("SELECT u.userid,u.first_name,u.last_name,u.email,u.user_type,u.profile_picture,u.fb_profile_picture,u.class_taken_id,r.request_id,r.userid AS from_id,r.to_id,r.status FROM tb_user u LEFT JOIN tb_request r ON u.user_type='Student' AND ((r.userid='$userid' AND r.to_id=u.userid) OR (r.userid=u.userid AND r.to_id='$userid')) WHERE u.user_type='Student' GROUP BY u.userid");
            $selectUser->execute();
            if($selectUser->rowCount() > 0 ){
                $status  = "success";
                while($row = $selectUser->fetch(PDO::FETCH_ASSOC)){
                    $each_userid = $row['userid'];
                    $subject = '';
                    $row['fullname'] = $row['first_name'].' '.$row['last_name'];
                    if($row['status']=='NULL' || $row['status']=='null' || $row['status']==null){
                        $row['status'] = 'No Request';
                    }
                    $subjectid = $row['class_taken_id'];
                    $sub_subject_found = 0;
                    $subject_list = array();
                    $subject_array = $obj1->majorMinorSubjectList($each_userid);
                    foreach($subject_array as $each_subject){
                        if($each_subject['sub_subject_id']==$sub_subjectid){
                            $sub_subject_found = 1;
                        }
                        $subject_list[] = $each_subject['subject'];
                    }
                    $row['subject'] = implode(',',$subject_list);
                    if($sub_subject_found == 1){
                        $details[] = $row;
                    }
                }
            }
        }else{
            $error   = "Invalid Token";
        }

        return array(
            "status"  => $status,
            "error"   => $error,
            "details" => $details
        );
    }


    function myStudentList($userid,$adminid,$auth_token){
        $obj1 = new commonFunctions();
        $dbh  = $obj1->dbh;

        $status  = "failed";
        $error   = "";
        $details = [];

        $user_details = $obj1->checkAuthToken($userid,$adminid,$auth_token);
        if(sizeof($user_details)>0){
            $selectUser = $dbh->prepare("SELECT u.*,(select count(*) from tb_request r WHERE r.to_id=$userid AND u.userid=r.userid AND r.status='completed') AS teach_count FROM tb_user u");
            $selectUser->execute();
            if($selectUser->rowCount() > 0 ){
                $status  = "success";
                while($row = $selectUser->fetch(PDO::FETCH_ASSOC)){
                    $each_userid = $row['userid'];
                    $row['fullname'] = $row['first_name'].' '.$row['last_name'];
                    if($row['status']=='NULL' || $row['status']=='null' || $row['status']==null){
                        $row['status'] = 'No Request';
                    }
                    if($row['teach_count'] == 1){
                        $details[] = $row;
                    }
                }
            }
        }else{
            $error   = "Invalid Token";
        }

        return array(
            "status"  => $status,
            "error"   => $error,
            "details" => $details
        );
    }



}
?>