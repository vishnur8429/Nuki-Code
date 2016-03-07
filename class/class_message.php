<?php

include_once('class_functions.php');

class messageClass{

    // GetMessageUserList
    function GetMessageUserList($userid,$adminid,$auth_token){

        $obj1   = new commonFunctions();
        $dbh    = $obj1->dbh;

        $user_details = array();
        $row          = array();
        $status       = "failed";
        $error        = "";
        $details      = array();

        $user_details = $obj1->checkAuthToken($userid,$adminid,$auth_token);
        if(sizeof($user_details)>0){
            $user_type = $user_details['user_type'];
            if($user_type=="Student"){
                $selectUser = $dbh->prepare("SELECT u.`userid`,u.`profile_picture`,CONCAT(u.`first_name`,' ',u.`last_name`) AS `fullname` FROM tb_user u WHERE user_type='Tutor' AND u.userid!='$userid' ");
            }else{
                $selectUser = $dbh->prepare("SELECT u.`userid`,u.`profile_picture`,CONCAT(u.`first_name`,' ',u.`last_name`) AS `fullname` FROM tb_user u WHERE user_type='Student' AND u.userid!='$userid' ");
            }
            $selectUser->execute();
            if($selectUser->rowCount() > 0 ){
                $status  = "success";
                while($row = $selectUser->fetch(PDO::FETCH_ASSOC)){

                    $friendid = $row['userid'];
                    $message  = array(
                        'message' => '',
                        'elaspsed_time' => ''
                        );
                    $friend_details = $obj1->getUserDetails($userid,$friendid);
                    if(sizeof($friend_details)>0) {
                        $query_message = $dbh->prepare( "SELECT m.* FROM `tb_message` m INNER JOIN `tb_user` u ON ((m.`from_id`='$userid' AND m.`to_id`='$friendid') OR (m.`from_id`='$friendid' AND m.`to_id`='$userid')) AND m.`from_id`=u.`userid` ORDER BY m.`message_id` DESC LIMIT 0,1 ");
                        $query_message->execute();
                        if($query_message->rowCount() > 0 ){
                            $row_message   = $query_message->fetch(PDO::FETCH_ASSOC);
                            $row_message['elaspsed_time'] = $obj1->getElapsedTime($row_message['date']);
                            
                            $message['message']  = $row_message['message'];
                            $message['elaspsed_time']  = $row_message['elaspsed_time'];
                        }
                    }

                    $row['messagedetails'] = $message;
                    $details[] = $row;
                }
            }
        }else{
            $error   = "Invalid Token";
        }


        return array(
            'status'   => $status,
            'error'   => $error,
            'details'  => $details
        );

    }



    // Add Message New
    function messageAddNew($userid,$adminid,$to,$message,$image,$auth_token){

        $obj1   = new commonFunctions();
        $dbh    = $obj1->dbh;

        $status = "failed";
        $error        = "";
        $row    = array();
        $date   = $obj1->getCurrentDate();

        $from_firstname = '';
        $to_firstname   = '';
        $device_token   = '';

        $type     = "Text";
        $message  = preg_replace("/&#?[a-z0-9]+;/i","",$message);

        $user_details = $obj1->checkAuthToken($userid,$adminid,$auth_token);
        if(sizeof($user_details)>0) {
            $from_details = $obj1->getUserDetails($userid,$userid);
            $to_details   = $obj1->getUserDetails($userid,$to);
            if(sizeof($from_details)>0 && sizeof($to_details)>0){
                $from_firstname = $from_details['first_name'];
                $to_firstname   = $to_details['first_name'];
                $device_token   = $to_details['device_token'];

                $query = $dbh->prepare("INSERT INTO `tb_message`(`date`,`from_id`,`to_id`,`message`,`image`,`active_status`) VALUES('$date','$userid','$to','$message','$image',1)");
                if ($query->execute()){
                    $status = "success";
                    $messageid = $dbh->lastInsertId();

                    $query1 = $dbh->prepare("SELECT m.*,u.`userid`,u.`profile_picture`,CONCAT(u.`first_name`,' ',u.`last_name`) AS `fullname` FROM `tb_message` m INNER JOIN `tb_user` u
                    WHERE  m.`message_id`='$messageid' AND m.`from_id`=u.`userid` ");

                    if ($query1->execute()) {
                        $row = $query1->fetch(PDO::FETCH_ASSOC);
                        if ($device_token != '') {
                            $fullname = $row['fullname'];
                            $chat_message = $fullname . " sent a message";
                            $row_toDetails = $obj1->getUserDetails($userid,$to);
                            if(sizeof($row_toDetails)>0){
                                $obj1->pushNotificationAndroid('Message',$chat_message,$row_toDetails['device_token'],1);
                                $obj1->pushNotificationIphone('Message',$chat_message,$row_toDetails['device_token'],1);
                            }
                        }

                        $row['elaspsed_time'] = $obj1->getElapsedTime($row['date']);
                    }
                }

            }
        }else{
            $error   = "Invalid Token";
        }

        return array(
            'status'   => $status,
            'error'   => $error,
            'details'  => $row,
        );

    }




    // GetUserFullChat
    function getUserMessage($userid,$adminid,$friendid,$auth_token){
        $obj1   = new commonFunctions();
        $dbh    = $obj1->dbh;

        $details   = array();
        $friend_details = array();
        $error        = "";
        $status    = "failed";

        $user_details   = $obj1->checkAuthToken($userid,$adminid,$auth_token);
        $friend_details = $obj1->getUserDetails($userid,$friendid);
        if(sizeof($user_details)>0) {
            $status = "success";
            $query = $dbh->prepare("SELECT m.*,u.`userid`,u.`profile_picture`,CONCAT(u.`first_name`,' ',u.`last_name`) AS `fullname` FROM `tb_message` m INNER JOIN `tb_user` u
                  ON ((m.`from_id`='$userid' AND m.`to_id`='$friendid') OR (m.`from_id`='$friendid' AND m.`to_id`='$userid')) AND m.`from_id`=u.`userid` ORDER BY m.`message_id` asc ");
            $query->execute();
            if ($query->rowCount() > 0) {
                while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                    $row['elaspsed_time'] = $obj1->getElapsedTime($row['date']);
                    $details[] = $row;
                }
            }
        }else{
            $error   = "Invalid Token";
        }

        return array(
            'status'   => $status,
            'error'    => $error,
            'details'  => $details,
            'friend_details' => $friend_details
        );

    }
 

    // CheckLastUserChatId
    function latestMessageList($userid,$adminid,$friendid,$messageid,$auth_token){
        $obj1   = new commonFunctions();
        $dbh    = $obj1->dbh;

        $details   = array();
        $error     = "";
        $status    = "failed";

        $user_details = $obj1->checkAuthToken($userid,$adminid,$auth_token);
        if(sizeof($user_details)>0) {
            $query = $dbh->prepare("SELECT m.*,u.`userid`,u.`profile_picture`,CONCAT(u.`first_name`,' ',u.`last_name`) AS `fullname` FROM `tb_message` m INNER JOIN `tb_user` u
                                WHERE (m.`from_id`='$friendid' AND m.`to_id`='$userid') AND m.`message_id`>'$messageid' AND m.`from_id`=u.`userid` ORDER BY m.`message_id` DESC");
            if ($query->execute()) {
                while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                    $status = "success";
                    $row['elaspsed_time'] = $obj1->getElapsedTime($row['date']);
                    $details[] = $row;
                }
            }
        }else{
            $error   = "Invalid Token";
        }

        return array(
            'status'  => $status,
            'error'   => $error,
            'result'  => $details
        );

    }

}

?>