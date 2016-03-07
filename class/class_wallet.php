<?php

include_once('class_functions.php');

class walletClass{

    // Student Wallet List pending/completed
    function studentWalletList($userid,$adminid,$auth_token){
        $obj1   = new commonFunctions();
        $dbh    = $obj1->dbh;

        $status  = "success";
        $error   = "";
        $pending_list   = array();
        $completed_list = array();

        $date    = $obj1->getCurrentDate();
        $user_details = $obj1->checkAuthToken($userid,$adminid,$auth_token);
        if(sizeof($user_details)>0) {
            $select_pendingRequest = $dbh->prepare(" SELECT r.*,u.first_name,u.last_name,u.profile_picture,u.fb_profile_picture FROM tb_request r INNER JOIN tb_user u ON r.to_id=u.userid AND r.userid='$userid' AND r.payment_status='pending' AND r.status='completed' ");
            $select_pendingRequest->execute();
            while($row_pendingRequest = $select_pendingRequest->fetch(PDO::FETCH_ASSOC)){
                $row_pendingRequest['fullname'] = $row_pendingRequest['first_name'].' '.$row_pendingRequest['last_name'];
                $pending_list[] = $row_pendingRequest;
            }

            $select_completedRequest = $dbh->prepare(" SELECT r.*,u.first_name,u.last_name,u.profile_picture,u.fb_profile_picture FROM tb_request r INNER JOIN tb_user u ON r.to_id=u.userid AND r.userid='$userid' AND r.payment_status='completed' AND r.status='completed' ");
            $select_completedRequest->execute();
            while($row_completedRequest   = $select_completedRequest->fetch(PDO::FETCH_ASSOC)){
                $row_completedRequest['fullname'] = $row_completedRequest['first_name'].' '.$row_completedRequest['last_name'];
                $completed_list[]   = $row_completedRequest;
            }
        }else{
            $error   = "Invalid Token";
        }

        return array(
            'status'        => $status,
            'pending_list'  => $pending_list,
            'completed_list'=> $completed_list,
            'error'         => $error
        );
    }




    // Tutor List pending/completed
    function tutorWalletList($userid,$adminid,$auth_token){
        $obj1   = new commonFunctions();
        $dbh    = $obj1->dbh;

        $status  = "success";
        $error   = "";
        $pending_list   = array();
        $completed_list = array();

        $date    = $obj1->getCurrentDate();
        $user_details = $obj1->checkAuthToken($userid,$adminid,$auth_token);
        if(sizeof($user_details)>0) {
            $select_pendingRequest = $dbh->prepare(" SELECT r.*,u.first_name,u.last_name,u.profile_picture,u.fb_profile_picture FROM tb_request r INNER JOIN tb_user u ON r.userid=u.userid AND r.to_id='$userid' AND r.payment_status='pending' AND r.status='completed' ");
            $select_pendingRequest->execute();
            while($row_pendingRequest = $select_pendingRequest->fetch(PDO::FETCH_ASSOC)){
                $row_pendingRequest['fullname'] = $row_pendingRequest['first_name'].' '.$row_pendingRequest['last_name'];
                $pending_list[] = $row_pendingRequest;
            }
            $select_completedRequest = $dbh->prepare(" SELECT r.*,u.first_name,u.last_name,u.profile_picture,u.fb_profile_picture FROM tb_request r INNER JOIN tb_user u ON r.userid=u.userid AND r.to_id='$userid' AND r.payment_status='completed' AND r.status='completed' ");
            $select_completedRequest->execute();
            while($row_completedRequest   = $select_completedRequest->fetch(PDO::FETCH_ASSOC)){
                $row_completedRequest['fullname'] = $row_completedRequest['first_name'].' '.$row_completedRequest['last_name'];
                $completed_list[]   = $row_completedRequest;
            }

        }else{
            $error   = "Invalid Token";
        }

        return array(
            'status'        => $status,
            'pending_list'  => $pending_list,
            'completed_list'=> $completed_list,
            'error'         => $error
        );
    }

    // Payment Finish
    function paymentFinish($userid,$adminid,$requestid,$auth_token){
        $obj1   = new commonFunctions();
        $dbh    = $obj1->dbh;

        $status  = "success";
        $error   = "";
        $details  = array();

        $date    = $obj1->getCurrentDate();
        $user_details = $obj1->checkAuthToken($userid,$adminid,$auth_token);
        if(sizeof($user_details)>0) {
            $select_pendingRequest = $dbh->prepare(" SELECT * FROM tb_request WHERE userid='$userid' AND request_id='$requestid' AND payment_status='pending' ");
            $select_pendingRequest->execute();
            if($select_pendingRequest->rowCount() > 0){
                $update_user = $dbh->prepare("UPDATE tb_request SET payment_status='completed' WHERE request_id='$requestid'");
                $update_user->execute();
            }
        }else{
            $error   = "Invalid Token";
        }

        return array(
            'status'    => $status,
            'details'   => $details,
            'error'     => $error
        );
    }



}

?>