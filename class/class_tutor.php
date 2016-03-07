<?php
include_once('class_functions.php');


class tutorClass {

    function tutorList($userid,$adminid,$sub_subjectid,$auth_token){
        $obj1 = new commonFunctions();
        $dbh  = $obj1->dbh;
        $status  = "failed";
        $error   = "";
        $details = [];
        $user_details = array();

        $user_details = $obj1->checkAuthToken($userid,$adminid,$auth_token);
        if(sizeof($user_details)>0){
            $selectUser = $dbh->prepare("SELECT u.userid,u.first_name,u.last_name,u.email,u.user_type,u.profile_picture,u.class_taken_id FROM tb_user u WHERE u.user_type='Tutor' GROUP BY u.userid");
            $selectUser->execute();
            if($selectUser->rowCount() > 0 ){
                $status  = "success";
                while($row = $selectUser->fetch(PDO::FETCH_ASSOC)){
                    $each_userid     = $row['userid'];
                    $row['fullname'] = $row['first_name'].' '.$row['last_name'];
                    $requestid = '';
                    $fromid    = '';
                    $toid      = '';
                    $request_status = 'No Request';
                    $request_details = $dbh->prepare("SELECT r.* FROM tb_request r WHERE  ((r.userid='$userid' AND r.to_id='$each_userid') OR (r.userid='$each_userid' AND r.to_id='$userid')) AND (r.status='pending' OR r.status='accepted') ");
                    $request_details->execute();
                    while($row_request = $request_details->fetch(PDO::FETCH_ASSOC)){
                        $request_status = $row_request['status'];
                        $requestid      = $row_request['request_id'];
                        $fromid         = $row_request['userid'];
                        $toid           = $row_request['to_id'];
                        $request_status = $row_request['status'];
                    }
                    $row['request_id'] = $requestid;
                    $row['from_id']    = $fromid;
                    $row['to_id']      = $toid;
                    $row['status']     = $request_status;

                    $row['average_rating'] = $obj1->averageRating($each_userid);
                    $subject   = '';

                    $sub_subject_found = 0;
                    $subject_list  = array();
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



}
?>