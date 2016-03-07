<?php

include_once('class_functions.php');

class ratingClass{

    // Add rating
    function ratingAddNew($userid,$adminid,$toid,$rating,$review,$auth_token){

        $obj1   = new commonFunctions();
        $dbh    = $obj1->dbh;

        $status = "failed";
        $error  = "";
        $row    = array();
        $date   = $obj1->getCurrentDate();

        $user_details = $obj1->checkAuthToken($userid,$adminid,$auth_token);
        if(sizeof($user_details)>0) {
            $check_rate_query = $dbh->prepare("SELECT r.* FROM tb_rating r WHERE r.userid='$userid' AND r.to_id='$toid'  ");
            $check_rate_query->execute();
            if($check_rate_query->rowCount()==0)
            {
                $query = $dbh->prepare("INSERT INTO `tb_rating`(`date`,`userid`,`to_id`,`rating`,`review`) VALUES('$date','$userid','$toid','$rating','$review') ");
                if($query->execute()){
                    $status = "success";
                    $ratingid = $dbh->lastInsertId();
                }
            }else{
                $update = $dbh->prepare("UPDATE `tb_rating` r SET r.rating='$rating',r.review='$review' WHERE r.userid='$userid' AND r.to_id='$toid'");
                $update->execute();
                $status = "success";
            }

            $query1 = $dbh->prepare("SELECT r.*,u.`userid`,u.`profile_picture`,CONCAT(u.`first_name`,' ',u.`last_name`) AS `fullname` FROM tb_rating r INNER JOIN tb_user u WHERE r.userid='$userid' AND r.to_id='$toid' ");
            if ($query1->execute()) {
                $row = $query1->fetch(PDO::FETCH_ASSOC);
                $row['average_rating'] = $obj1->averageRating($toid);
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


    // Rating List
    function getRatingList($userid,$adminid,$auth_token){

        $obj1   = new commonFunctions();
        $dbh    = $obj1->dbh;

        $status       = "failed";
        $error        = "";
        $details      = array();
        $total_rating = 0;
        $total_users  = 0;
        $average_rating = 0;

        $user_details = $obj1->checkAuthToken($userid,$adminid,$auth_token);
        if(sizeof($user_details)>0){
            $selectUser = $dbh->prepare("SELECT r.*,u.`userid`,u.`profile_picture`,CONCAT(u.`first_name`,' ',u.`last_name`) AS `fullname` FROM tb_rating r INNER JOIN tb_user u WHERE r.to_id='$userid' AND u.userid=r.userid ");
            $selectUser->execute();
            $total_users = $selectUser->rowCount();
            if( $total_users > 0 ){
                $status  = "success";
                while($row = $selectUser->fetch(PDO::FETCH_ASSOC)){
                    $total_rating = $total_rating+$row['rating'];
                    $details[] = $row;
                }
            }
        }else{
            $error   = "Invalid Token";
        }

        if($total_users>0){
            $average_rating = round($total_rating/$total_users);
        }

        return array(
            'status'         => $status,
            'error'          => $error,
            'average_rating' => $average_rating,
            'details'        => $details

        );

    }



}

?>