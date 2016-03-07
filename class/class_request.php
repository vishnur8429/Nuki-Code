<?php

include_once('class_functions.php');

class requestClass{

    // New Request
    function requestAdd($userid,$adminid,$toid,$hour,$price,$comment,$payment_mode,$auth_token){
        $obj1   = new commonFunctions();
        $dbh    = $obj1->dbh;

        $status = "failed";
        $error  = "";
        $details= '';
        $date   = $obj1->getCurrentDate();

        $user_details = $obj1->checkAuthToken($userid,$adminid,$auth_token);
        if(sizeof($user_details)>0) {
            $query1 = $dbh->prepare("SELECT r.* FROM tb_request r WHERE ((r.userid='$userid' AND r.to_id='$toid') OR (r.userid='$toid' AND r.to_id='$userid')) AND ((r.status='pending' OR r.status='started'))");
            $query1->execute();
            if($query1->rowCount() == 0 ){
                $row_toDetails = $obj1->getUserDetails($userid,$toid);
                if(sizeof($row_toDetails)>0){
                    $obj1->pushNotificationAndroid('Session Request','You have new session request!',$row_toDetails['device_token'],1);
                    $obj1->pushNotificationIphone('Session Request','You have new session request!',$row_toDetails['device_token'],1);
                }
                $insert = $dbh->prepare("INSERT INTO `tb_request`(`date`,`userid`,`to_id`,`hours`,`comment`,`payment_mode`,`amount`,`payment_status`,`status`) VALUES('$date','$userid','$toid','$hour','$comment','$payment_mode','$price','pending','pending') ");
                if ($insert->execute()){
                    $status = "success";
                    $requestid = $dbh->lastInsertId();
                }
            }else{
                $row = $query1->fetch(PDO::FETCH_ASSOC);
                $details = $row['status'];
            }
        }else{
            $error   = "Invalid Token";
        }

        return array(
            'status'   => $status,
            'details'  => $details,
            'error'    => $error
        );

    }



    // Request Pending List
    function requestPendingList($userid,$adminid,$auth_token){

        $obj1   = new commonFunctions();
        $dbh    = $obj1->dbh;

        $status       = "failed";
        $error        = "";
        $details      = array();

        $user_details = $obj1->checkAuthToken($userid,$adminid,$auth_token);
        if(sizeof($user_details)>0) {
            $query1 = $dbh->prepare("SELECT r.* FROM tb_request r WHERE (r.to_id='$userid') AND r.status='pending' ");
            $query1->execute();
            if($query1->rowCount() > 0 ){
                $status = "success";
                $row = $query1->fetch(PDO::FETCH_ASSOC);
                $row['elapsed_time'] = $obj1->getElapsedTime($row['date']);
                $details[] = $row;
            }
        }else{
            $error   = "Invalid Token";
        }

        return array(
            'status'   => $status,
            'details'  => $details,
            'error'    => $error
        );

    }

    function paypalAjax($userid,$card_number,$card_month,$card_year,$card_date,$card_cvv,$first_name,$last_name,$address_1,$address_2,$city,$postal_code,$amount,$tutorid){

        $obj1        = new commonFunctions();
        $dbh         = $obj1->dbh; 

        $status      = 'failed';
        $error       = '';
        $details     = array();
        
        $cardnumber  = $obj1->encrypt($card_number);
        $expirymonth = $obj1->encrypt($card_month);
        $expiryyear  = $obj1->encrypt($card_year);
        $cvvnumber   = $obj1->encrypt($card_cvv);
        $date        = $obj1->getCurrentDate(); 

        // STUDENT CARD DETAILS
        $select = $dbh->prepare( "SELECT * FROM `tb_usercarddetails` WHERE `userid`='$userid'" );
        $select->execute();
        if($select->rowCount()>0){
            $update = $dbh->prepare( "UPDATE tb_usercarddetails SET `cardnumber`='$cardnumber',
                                                                    `expirymonth`='$expirymonth',
                                                                    `expiryyear`='$expiryyear',
                                                                    `cvvnumber`='$cvvnumber',
                                                                    `date_modified`='$date' 
                                                                    WHERE `userid`='$userid'");
            $update->execute();
        } else {
            $insert = $dbh->prepare( "INSERT INTO `tb_usercarddetails`(`userid`,`cardnumber`,`expirymonth`,`expiryyear`,`cvvnumber`,`date_added`,`date_modified`)
                                                                VALUES('$userid','$cardnumber','$expirymonth','$expiryyear','$cvvnumber','$date','$date')" );
            $insert->execute();
        }


        // TUTOR PAYPAL DETAILS
        $paypalUsername  = '';
        $paypalPassword  = '';
        $paypalSignature = '';
        $query_tutor = $dbh->prepare( "SELECT * FROM `tb_userpaypaldetails` WHERE `userid`=:tutorid" );
        $query_tutor->bindParam(':tutorid',$tutorid,PDO::PARAM_INT);
        $query_tutor->execute();
        if( $query_tutor->rowCount() > 0 ){
            $row_tutor       = $query_tutor->fetch(PDO::FETCH_ASSOC);
            $paypalUsername  = $obj1->decrypt($row_tutor['username']);
            $paypalPassword  = $obj1->decrypt($row_tutor['password']);
            $paypalSignature = $obj1->decrypt($row_tutor['signature']);
        }

    

        /*
           Credit card number   :  4032035820956084
           Credit card type     :  Visa
           Expiration date      :  062019
           CVV                  :   123
        */
        // Store request params in an array
        // $request_params = array(
        //     'METHOD' => 'DoDirectPayment',
        //     'USER' => 'nisha.s.pushpan_api1.gmail.com',        // payment to user
        //     'PWD' => '1403181169',                             // payment to user password
        //     'SIGNATURE' => 'AXpdfmVwbM97Fu4b70dmLYf4mI-OAoUtDVmAPSSd-MnXK0fYmyjvofan',
        //     'VERSION' => '85.0',
        //     'PAYMENTACTION' => 'Sale',
        //     'IPADDRESS' => $_SERVER['REMOTE_ADDR'], 
        //     'CREDITCARDTYPE' => 'Visa',
        //     'ACCT' => $card_number,
        //     'EXPDATE' => $card_date,
        //     'CVV2' => $card_cvv ,
        //     'FIRSTNAME' => $first_name,
        //     'LASTNAME' => $last_name,
        //     'STREET' => $address_1.",".$address_2,
        //     'CITY' => $city,
        //     'STATE' => '',
        //     'COUNTRYCODE' => '',
        //     'ZIP' => $postal_code,
        //     'AMT' => $amount,
        //     'CURRENCYCODE' => 'USD',
        //     'DESC' => 'Payments Pro'
        // );
        $request_params = array(
            'METHOD'         => 'DoDirectPayment',
            'USER'           => $paypalUsername,        
            'PWD'            => $paypalPassword,                            
            'SIGNATURE'      => $paypalSignature,
            'VERSION'        => '85.0',
            'PAYMENTACTION'  => 'Sale',
            'IPADDRESS'      => $_SERVER['REMOTE_ADDR'], 
            'CREDITCARDTYPE' => 'Visa',
            'ACCT'           => $card_number,
            'EXPDATE'        => $card_date,
            'CVV2'           => $card_cvv ,
            'FIRSTNAME'      => $first_name,
            'LASTNAME'       => $last_name,
            'STREET'         => $address_1.",".$address_2,
            'CITY'           => $city,
            'STATE'          => '',
            'COUNTRYCODE'    => '',
            'ZIP'            => $postal_code,
            'AMT'            => $amount,
            'CURRENCYCODE'   => 'USD',
            'DESC'           => 'Payments Pro'
        );

        $nvp_string = '';
        foreach($request_params as $var=>$val){
            $nvp_string .= '&'.$var.'='.urlencode($val);
        }

        $result = array();
        // Send NVP string to PayPal and store response
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_VERBOSE, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
        curl_setopt($curl, CURLOPT_URL, 'https://api-3t.sandbox.paypal.com/nvp');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $nvp_string);
        $result = curl_exec($curl);

        $status1 =0;
        $payment_array = array();
        $payment_array = explode("&",$result);

        $payment_array[9];
        foreach($payment_array as $eachitem){
            if($eachitem=="ACK=Success"){
                $status1=1;
            }
        }

        if($status1==1){
            $status = "success";
        }

        return array(
            'status'   => $status,
            'details'  => $details,
            'error'    => $error
            );

    }

    


    // Request Accept Reject
    function requestAcceptReject($userid,$adminid,$fromid,$accept_status,$auth_token){

        $obj1   = new commonFunctions();
        $dbh    = $obj1->dbh;

        $status       = "failed";
        $error        = "";
        $details      = array();

        $user_details = $obj1->checkAuthToken($userid,$adminid,$auth_token);
        if(sizeof($user_details)>0) {
            $query1 = $dbh->prepare("SELECT r.* FROM tb_request r WHERE (r.userid='$fromid' AND r.to_id='$userid') AND r.status='pending' ");
            $query1->execute();
            if($query1->rowCount() > 0 ){
                if($accept_status=='accepted'){
                    $status      = "success";
                    $update_user = $dbh->prepare("UPDATE tb_request r SET status='accepted' WHERE (r.userid='$fromid' AND r.to_id='$userid') AND r.status='pending' ");
                    $update_user->execute();
                    $row_fromDetails = $obj1->getUserDetails($userid,$fromid);
                    if(sizeof($row_fromDetails)>0){
                        $obj1->pushNotificationAndroid('Session Request Accept','Tutor accepted your session request!',$row_fromDetails['device_token'],1);
                        $obj1->pushNotificationIphone('Session Request Accept','Tutor accepted your session request!',$row_fromDetails['device_token'],1);
                    }
                }else if($accept_status=='rejected'){
                    $status      = "success";
                    $update_user = $dbh->prepare("DELETE FROM tb_request WHERE (userid='$fromid' AND to_id='$userid') AND status='pending' ");
                    $update_user->execute();
                    $row_fromDetails = $obj1->getUserDetails($userid,$fromid);
                    if(sizeof($row_fromDetails)>0){
                        $obj1->pushNotificationAndroid('Session Request Reject','Tutor rejected your session request!',$row_fromDetails['device_token'],1);
                        $obj1->pushNotificationIphone('Session Request Reject','Tutor rejected your session request!',$row_fromDetails['device_token'],1);
                    }
                }
            }
        }else{
            $error   = "Invalid Token";
        }

        return array(
            'status'   => $status,
            'details'  => $details,
            'error'    => $error
        );

    }


    // Request Waiting To Start List
    function requestStartList($userid,$adminid,$auth_token){

        $obj1   = new commonFunctions();
        $dbh    = $obj1->dbh;

        $status       = "failed";
        $error        = "";
        $details      = array();

        $user_details = $obj1->checkAuthToken($userid,$adminid,$auth_token);
        if(sizeof($user_details)>0) {
            $query1 = $dbh->prepare("SELECT r.* FROM tb_request r WHERE (r.to_id='$userid') AND r.status='accepted' ");
            $query1->execute();
            if($query1->rowCount() > 0 ){
                $status = "success";
                $row = $query1->fetch(PDO::FETCH_ASSOC);
                $row['elapsed_time'] = $obj1->getElapsedTime($row['date']);
                $details[] = $row;
            }
        }else{
            $error   = "Invalid Token";
        }

        return array(
            'status'   => $status,
            'details'  => $details,
            'error'    => $error
        );

    }


    // Request Completed List
    function requestCompletedList($userid,$adminid,$auth_token){

        $obj1   = new commonFunctions();
        $dbh    = $obj1->dbh;

        $status       = "failed";
        $error        = "";
        $details      = array();

        $user_details = $obj1->checkAuthToken($userid,$adminid,$auth_token);
        if(sizeof($user_details)>0) {
            $query1 = $dbh->prepare("SELECT r.* FROM tb_request r WHERE (r.to_id='$userid') AND r.status='completed' ");
            $query1->execute();
            if($query1->rowCount() > 0 ){
                $status = "success";
                $row = $query1->fetch(PDO::FETCH_ASSOC);
                $row['elapsed_time'] = $obj1->getElapsedTime($row['date']);
                $details[] = $row;
            }
        }else{
            $error   = "Invalid Token";
        }

        return array(
            'status'   => $status,
            'details'  => $details,
            'error'    => $error
        );

    }


    // Tutor session list
    function tutorSessionList($userid,$adminid,$auth_token){

        $obj1   = new commonFunctions();
        $dbh    = $obj1->dbh;

        $status       = "failed";
        $error        = "";
        $details1     = array();
        $details2     = array();
        $details3     = array();

        $user_details = $obj1->checkAuthToken($userid,$adminid,$auth_token);
        if(sizeof($user_details)>0) {
            $status = "success";

            $query1 = $dbh->prepare("SELECT r.*,u.userid,u.first_name,u.last_name,u.email,u.user_type,u.profile_picture FROM tb_request r INNER JOIN tb_user u ON (r.to_id='$userid') AND r.status='pending' AND u.userid=r.userid ORDER BY r.request_id desc");
            $query1->execute();
            if($query1->rowCount() > 0 ){
                $status = "success";
                while($row1 = $query1->fetch(PDO::FETCH_ASSOC)){
                    $row1['fullname'] = $row1['first_name'].' '.$row1['last_name'];
                    $row1['elapsed_time'] = $obj1->getElapsedTime($row1['date']);
                    $details1[] = $row1;
                }
            }

            $query2 = $dbh->prepare("SELECT r.*,u.userid,u.first_name,u.last_name,u.email,u.user_type,u.profile_picture FROM tb_request r INNER JOIN tb_user u ON (r.to_id='$userid') AND r.status='accepted' AND u.userid=r.userid ORDER BY r.request_id desc");
            $query2->execute();
            if($query2->rowCount() > 0 ){
                $status = "success";
                while($row2 = $query2->fetch(PDO::FETCH_ASSOC)) {
                    $row2['fullname'] = $row2['first_name'] . ' ' . $row2['last_name'];
                    $row2['elapsed_time'] = $obj1->getElapsedTime($row2['date']);
                    $details2[] = $row2;
                }
            }

            $query3 = $dbh->prepare("SELECT r.*,u.userid,u.first_name,u.last_name,u.email,u.user_type,u.profile_picture FROM tb_request r INNER JOIN tb_user u ON (r.to_id='$userid') AND r.status='completed' AND u.userid=r.userid ORDER BY r.request_id desc");
            $query3->execute();
            if($query3->rowCount() > 0 ){
                $status = "success";
                while($row3 = $query3->fetch(PDO::FETCH_ASSOC)) {
                    $row3['fullname'] = $row3['first_name'] . ' ' . $row3['last_name'];
                    $row3['elapsed_time'] = $obj1->getElapsedTime($row3['date']);
                    $details3[] = $row3;
                }
            }
        }else{
            $error   = "Invalid Token";
        }

        return array(
            'status'   => $status,
            'pending_list'    => $details1,
            'accepted_list'   => $details2,
            'completed_list'  => $details3,
            'error'    => $error
        );

    }

    // Student session list
    function studentSessionList($userid,$adminid,$auth_token){

        $obj1   = new commonFunctions();
        $dbh    = $obj1->dbh;

        $status       = "failed";
        $error        = "";
        $details1     = array();
        $details2     = array();
        $details3     = array();

        $user_details = $obj1->checkAuthToken($userid,$adminid,$auth_token);
        if(sizeof($user_details)>0) {
            $status = "success";

            $query1 = $dbh->prepare("SELECT r.*,u.userid,u.first_name,u.last_name,u.email,u.user_type,u.profile_picture FROM tb_request r INNER JOIN tb_user u ON (r.userid='$userid') AND r.status='pending' AND u.userid=r.to_id ORDER BY r.request_id desc");
            $query1->execute();
            if($query1->rowCount() > 0 ){
                $status = "success";
                while($row1 = $query1->fetch(PDO::FETCH_ASSOC)){
                    $row1['fullname'] = $row1['first_name'].' '.$row1['last_name'];
                    $row1['elapsed_time'] = $obj1->getElapsedTime($row1['date']);
                    $details1[] = $row1;
                }
            }

            $query2 = $dbh->prepare("SELECT r.*,u.userid,u.first_name,u.last_name,u.email,u.user_type,u.profile_picture FROM tb_request r INNER JOIN tb_user u ON (r.userid='$userid') AND r.status='accepted' AND u.userid=r.to_id ORDER BY r.request_id desc");
            $query2->execute();
            if($query2->rowCount() > 0 ){
                $status = "success";
                while($row2 = $query2->fetch(PDO::FETCH_ASSOC)) {
                    $row2['fullname'] = $row2['first_name'] . ' ' . $row2['last_name'];
                    $row2['elapsed_time'] = $obj1->getElapsedTime($row2['date']);
                    $details2[] = $row2;
                }
            }

            $query3 = $dbh->prepare("SELECT r.*,u.userid,u.first_name,u.last_name,u.email,u.user_type,u.profile_picture FROM tb_request r INNER JOIN tb_user u ON (r.userid='$userid') AND r.status='completed' AND u.userid=r.to_id ORDER BY r.request_id desc");
            $query3->execute();
            if($query3->rowCount() > 0 ){
                $status = "success";
                while($row3 = $query3->fetch(PDO::FETCH_ASSOC)) {
                    $row3['fullname'] = $row3['first_name'] . ' ' . $row3['last_name'];
                    $row3['elapsed_time'] = $obj1->getElapsedTime($row3['date']);
                    $details3[] = $row3;
                }
            }
        }else{
            $error   = "Invalid Token";
        }

        return array(
            'status'   => $status,
            'pending_list'    => $details1,
            'accepted_list'   => $details2,
            'completed_list'  => $details3,
            'error'    => $error
        );

    }


    // Session Rejected
    function sessionReject($userid,$adminid,$requestid,$auth_token){

        $obj1   = new commonFunctions();
        $dbh    = $obj1->dbh;

        $status       = "failed";
        $error        = "";

        $user_details = $obj1->checkAuthToken($userid,$adminid,$auth_token);
        if(sizeof($user_details)>0) {
            $status      = "success";
            $update_user = $dbh->prepare("UPDATE tb_request r SET r.status='rejected' WHERE (r.request_id='$requestid' AND r.to_id='$userid') AND r.status='accepted' ");
            $update_user->execute();
        }else{
            $error   = "Invalid Token";
        }

        return array(
            'status'   => $status,
            'error'    => $error
        );

    }


    // Session Submit/completed
    function sessionComplete($userid,$adminid,$requestid,$auth_token){

        $obj1   = new commonFunctions();
        $dbh    = $obj1->dbh;

        $status       = "failed";
        $error        = "";

        $user_details = $obj1->checkAuthToken($userid,$adminid,$auth_token);
        if(sizeof($user_details)>0) {
            $status      = "success";
            $update_user = $dbh->prepare("UPDATE tb_request r SET r.status='completed' WHERE (r.request_id='$requestid' AND r.to_id='$userid') AND r.status='accepted' ");
            $update_user->execute();
        }else{
            $error   = "Invalid Token";
        }

        return array(
            'status'   => $status,
            'error'    => $error
        );

    }
}

?>