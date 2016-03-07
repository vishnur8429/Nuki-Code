<?php
include_once('class_connection.php');

class commonFunctions extends DB_Connection {

    //get ServerPath
    function getServerUrl(){
        return "www.sicsglobal.com/HybridApp/Nuki/";
    }

    //get App serverPath
    function getAppServerUrl(){
        return "www.sicsglobal.com/HybridApp/Nuki/mobile_app/";
    }

    // get Current DateTime
    function getCurrentDate(){
        date_default_timezone_set("Asia/Kolkata");
        $date   =   date('Y-m-d H:i:s');
        return $date;
    }

    // Sort array by ascending
    function sort_2d_asc($array, $key) {
        usort($array, function($a, $b) use ($key) {
            return strnatcasecmp($a[$key], $b[$key]);
        });
        return $array;
    }

    // Sort array by descending
    function sort_2d_desc($array, $key) {
        usort($array, function($a, $b) use ($key) {
            return strnatcasecmp($b[$key], $a[$key]);
        });
        return $array;
    }

    function getElapsedTime($eventTime){
        $current_date =   $this->getCurrentDate();
        $totaldelay = strtotime($current_date) - strtotime($eventTime);

        $days = floor($totaldelay / 86400);
        $elapsed_time = $days." days ago";
        if($days<1) {
            $hours = floor($totaldelay / 3600);
            $elapsed_time = $hours . " hours ago";
            if ($hours < 1) {
                $minutes = floor($totaldelay / 60);
                $elapsed_time = $minutes . " minutes ago";
                if ($minutes < 1) {
                    $elapsed_time = $totaldelay . " seconds ago";
                    if ($elapsed_time < 30) {
                        $elapsed_time = "just now";
                    }
                }
            }
        }

        return $elapsed_time;
    }

    function mailFunction($from,$to,$subject,$content){
        $i = 0;
        $random_hash = md5(date('r', time()));
        $headers  = "From:$from\nReply-To:$from ";
        $headers .= "\nContent-Type: text/html; boundary=\"PHP-alt-".$random_hash."\"";
        $mail = mail($to,$subject,$content,$headers,"-f $from");
        if($mail){
            $i=1;
        }
        return $i;
    }

    function generateAuthToken($email){
        $authToken = sha1(str_shuffle(mt_rand(10000,99999).str_shuffle($this->getCurrentDate()).str_shuffle($email)));
        return $authToken;
    }

    function getUserDetails($userid,$profileid){
        $dbh            = $this->dbh;
        $user_details   = array();

        $selectUser = $dbh->prepare("select * from tb_user where userid='$profileid' ");
        $selectUser->execute();
        if($selectUser->rowCount() > 0 ){
            $user_details = $selectUser->fetch(PDO::FETCH_ASSOC);
        }
        return $user_details;
    }

    function checkAuthToken($userid,$adminid,$authToken){
        $dbh            = $this->dbh;
        $user_details   = array();
        if($authToken!=''){
            if($userid=='' || $userid==0){
                $selectUser = $dbh->prepare("select * from tb_admin where admin_id='$adminid' AND auth_token='$authToken' ");
            }else{
                $selectUser = $dbh->prepare("select * from tb_user where userid='$userid' AND auth_token='$authToken' ");
            }
            $selectUser->execute();
            if($selectUser->rowCount() > 0 ){
                $user_details = $selectUser->fetch(PDO::FETCH_ASSOC);
            }
        }
        return array_filter($user_details);
    }

    function pushNotificationIphone($type,$message,$deviceToken,$count){

        /*$passphrase = "sics";
        $ctx = stream_context_create();
        stream_context_set_option($ctx, 'ssl', 'local_cert', 'pushcert.pem');
        stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);

        $fp = stream_socket_client(
            'ssl://gateway.sandbox.push.apple.com:2195', $err,
            $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);

        if (!$fp)
            exit("Failed to connect: $err $errstr".PHP_EOL);

        $body['aps'] = array(
            'type' => $type,
            'title' => 'Freshires',
            'alert' => $message,
            'sound' => 'default',
            'badge' => $count
        );

        $payload = json_encode($body);
        $msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;
        $result = fwrite($fp, $msg, strlen($msg));*/

       /* if (!$result)
        echo 'Message not delivered'.PHP_EOL;
        else
        echo 'Message successfully delivered' . PHP_EOL;*/
        //fclose($fp);

    }

    function pushNotificationAndroid($type,$message,$deviceToken,$count)
    {
        // for more details visit - http://www.androidhive.info/2012/10/android-push-notifications-using-google-cloud-messaging-gcm-php-and-mysql/
        $url          = 'https://android.googleapis.com/gcm/send';
        $serverApiKey = "AIzaSyDH8GPQrYNd2UrDbBtl_HaNwb0Jd1a0Sm4";   // google API Key

        $headers = array(
            'Content-Type:application/json',
            'Authorization:key=' . $serverApiKey
        );

        $data = array(
            'registration_ids' => array($deviceToken),
            'data' => array(
                'type'      => $type,
                'title'     => 'Nuki',
                'message'   => $message,
                'sound'     => 'default',
                'badge'     => $count,
                'url'       => 'http://androidmyway.wordpress.com'
            )
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);

        if ($headers)
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        $response = curl_exec($ch);
        curl_close($ch);
        //print ($response);
    }

    function majorMinorSubjectList($userid){
        $dbh    = $this->dbh;
        $row    = array();
        $select = $dbh->prepare("SELECT m.major_id,m.userid,m.category,m.subject_id,m.sub_subject_id,s.subject,s1.sub_subject,m.hourly_rate FROM tb_major_minor m INNER JOIN tb_subject s ON m.`userid`='$userid' AND s.subject_id=m.subject_id INNER JOIN tb_sub_subject s1 ON s1.sub_subject_id=m.sub_subject_id ORDER BY m.major_id DESC");
        $select->execute();
        if($select->rowCount()>0){
            while($row1    = $select->fetch(PDO::FETCH_ASSOC)){
                $row[] = $row1;
            }
        }
        return $row;
    }

    function averageRating($userid){
        $dbh            = $this->dbh;
        $average_rating = 0;

        $select = $dbh->prepare("SELECT AVG(rating) AS rating_average  FROM `tb_rating` WHERE to_id = '$userid'");
        $select->execute();
        if($select->rowCount()>0){
            $row = $select->fetch(PDO::FETCH_ASSOC);
            $average_rating =  (int)$row['rating_average'];
            $average_rating = round($average_rating);
        }

        return $average_rating;
    }

    function getMyRating($userid,$profileid){
        $dbh        = $this->dbh;
        $row        = array();

        $select = $dbh->prepare("SELECT rating,review  FROM `tb_rating` WHERE userid='$userid' AND to_id = '$profileid'");
        $select->execute();
        if($select->rowCount()>0){
            $row = $select->fetch(PDO::FETCH_ASSOC);
        }
        return $row;
    }

    function sessionHistory($userid){
        $dbh        = $this->dbh;
        $details        = array();

        $request_details = $dbh->prepare("SELECT r.*,u.first_name,u.last_name,u.profile_picture,u.fb_profile_picture FROM tb_request r INNER JOIN tb_user u ON  r.userid=u.userid AND r.to_id='$userid' AND (r.status='started' OR r.status='completed') ");
        $request_details->execute();
        while($row_request = $request_details->fetch(PDO::FETCH_ASSOC)){
            $row_request['fullname'] = $row_request['first_name']." ".$row_request['last_name'];
            $details[] = $row_request;
        }
        return $details;
    }

    function encrypt($text) {
        $encrypt = rand(100,999).base64_encode($text);   // abc -> 419YWJj // 212312312313 -> 907MjEyMzEyMzEyMzEz 
        return $encrypt;
    }
 
    function decrypt($text) {
        $decrypt = substr($text,3);
        $decrypt = base64_decode($decrypt);
        return $decrypt;
    }

}

?>