<?php

if(!isset($_SESSION)){
    session_start();
}

include_once('class_functions.php');

class adminClass {

    // ADMIN LOGIN
    function LoginAdmin($username,$password){
        $obj1 = new commonFunctions();
        $dbh = $obj1->dbh;

        $status = 'failed';
        $row = array();

        $selectUser = $dbh->prepare("SELECT admin_id,roleid,username FROM tb_admin WHERE username=:username AND password=:password");
        $selectUser->bindParam(":username",$username);
        $selectUser->bindParam(":password",$password);
        $selectUser->execute();

        if($selectUser->rowCount()>0){
            $row = $selectUser->fetch(PDO::FETCH_ASSOC);
            $email  = $row['email'];
            $authToken = $obj1->generateAuthToken($email);

            $adminid = $row['admin_id'];
            $_SESSION['admin_id']   = $adminid;
            $_SESSION['auth_token'] = $authToken;

            $update_admin = $dbh->prepare("UPDATE tb_admin SET auth_token='$authToken' WHERE admin_id='$adminid'");
            $update_admin->execute();
            $status = 'success';
        }

        return array(
            "status"  => $status,
            'details' => $row
        );

    }




    // FORGOT PASSWORD
    function forgot_password($adminid,$auth_token){

        $obj1   = new commonFunctions();
        $dbh    = $obj1->dbh;
        $status = 'failed';
        $error     = '';
        $user_details = array();

        $user_details = $obj1->checkAuthToken(0,$adminid,$auth_token);
        if(sizeof($user_details)>0){
            $email = $user_details['email'];
            $time_expires = $obj1->getCurrentDate();
            $link    = $obj1->getServerUrl().'users/reset_password?at='.base64_encode( serialize(array ( 'email' => $email, 't' => $time_expires )) );
            $subject = "Reset your password on Ecommerce";
            $message = 'Thanks for registering on Ecommerce.Click on the following link to proceed  '.$link;

            $from = "support@srishtis.com";
            $to   = $email;
            $mail = $obj1->mailFunction($from,$to,$subject,$message);
            if($mail){
                $status = "success";
            }
        } else{
            $error   = "Invalid Token";
        }

        return array(
            'status' => $status,
            'error'  => $error,
        );

    }





    function change_password($userid,$adminid,$password,$auth_token){
        $obj1   = new commonFunctions();
        $dbh    = $obj1->dbh;

        $status    = "failed";
        $error     = '';
        $details   = '';
        $user_details = array();

        $user_details = $obj1->checkAuthToken($userid,$adminid,$auth_token);
        if(sizeof($user_details)>0){
            if($userid==0){
                $query  = $dbh->prepare("SELECT * FROM `tb_admin` WHERE admin_id='$adminid' ");
                $query->execute();
                if( $query->rowCount() > 0 ){
                    $update = $dbh->prepare( "UPDATE `tb_admin` SET password='$password' WHERE admin_id='$adminid'");
                    if( $update->execute() ){
                        $status    = "success";
                    }
                }
            }
            else{
                $query  = $dbh->prepare("SELECT * FROM `tb_user` WHERE userid='$userid' ");
                $query->execute();
                if( $query->rowCount() > 0 ){
                    $update = $dbh->prepare( "UPDATE `tb_user` SET password='$password' WHERE userid='$userid' ");
                    if( $update->execute() ){
                        $status    = "success";
                    }
                }
            }
        }else{
            $error   = "Invalid Token";
        }

        return array(
            'status'   => $status,
            'details'  => $details
        );

    }




	function all_members(){
        $obj1   = new commonFunctions();
        $dbh    = $obj1->dbh;

        $status    = "failed";
        $error     = '';
        $details   = '';

        $userid    = 0;
        $user_details = array();	
		
		$selectUser  = $dbh->prepare("SELECT * FROM tb_user");
		 $selectUser->execute();
		
			if($selectUser->rowCount()>0){
		 while($row = $selectUser->fetch(PDO::FETCH_ASSOC))
		 {
		$status = "success";
		 $details[] = $row;
		  }
		 }
		 else
		 {
			 $status    = "failed";
		 }
		 
		 return array(
            'status'   => $status,
            'details'  => $details
        );
    }




    function member_details($id) {
	    $obj1   = new commonFunctions();
        $dbh    = $obj1->dbh;

        $status    = "failed";
        $error     = '';
        $details   = '';

        $userid    = $id;
        $user_details = array();	
		$selectUser  = $dbh->prepare("SELECT * FROM tb_user where `userid`='$userid'");
		 $selectUser->execute();
		
			if($selectUser->rowCount()>0){
				$row = $selectUser->fetch(PDO::FETCH_ASSOC);
				$status = "success";
				$details = $row;
				
			}
			else
		 {
			 $status    = "failed";
		 }
		 
		 return array(
            'status'   => $status,
            'details'  => $details
        );
    }




    function add_subject($subject) {
		$date      =  date('Y-m-d H:i:s');
	    $obj1      = new commonFunctions();
        $dbh       = $obj1->dbh;
		$status    = "failed";
        $error     = '';
        $details   = '';
		$insertSubject  = $dbh->prepare("insert into  `tb_subject` (`date`,`userid`,`subject`)values('$date','','$subject')");
		
		if ($insertSubject->execute()){
		 	$status = "success";
		}
		
	    return array(
			'status'   => $status
		);
    }

    

    function viewSubject() {
	    $obj1      = new commonFunctions();
        $dbh       = $obj1->dbh;
		$status    = "failed";
        $error     = '';
        $details   = '';
		$selectSubject  = $dbh->prepare("SELECT * FROM tb_subject");
		$selectSubject->execute();
		
		if($selectSubject->rowCount()>0){
		  while($row = $selectSubject->fetch(PDO::FETCH_ASSOC))
		  {
             $sub_subject = array();
             $subjectid = $row['subject_id'];
             $status = "success";

              $selectSubSubject  = $dbh->prepare("SELECT * FROM tb_sub_subject WHERE subject_id='$subjectid' ");
              $selectSubSubject->execute();
              while($row1 = $selectSubSubject->fetch(PDO::FETCH_ASSOC))
              {
                  $sub_subject[] = $row1;
              }
              $row['sub_subject'] = $sub_subject;
              $details[] = $row;
		  }
		 }
		 else
		 {
			 $status    = "failed";
		 }
		 
		 return array(
            'status'   => $status,
            'details'  => $details
        );
    }




    function update_subject($subid,$subject) {
	    $obj1      = new commonFunctions();
        $dbh       = $obj1->dbh;
		$status    = "failed";
        $error     = '';
        $details   = '';
		
		$updateSubject  = $dbh->prepare("UPDATE tb_subject set subject='$subject' where subject_id='$subid'");
		if ($updateSubject->execute()){
		 	$status = "success";
		}
		
	    return array(
			'status'   => $status
		);
    }




    function add_sub_subject($subject,$sub_subject) {
	   
		
	    $obj1      = new commonFunctions();
        $dbh       = $obj1->dbh;
		$status    = "failed";
        $error     = '';
        $details   = '';
		$insertSubject  = $dbh->prepare("insert into  `tb_sub_subject` (`subject_id`,`sub_subject`)values('$subject','$sub_subject')");
		
		if ($insertSubject->execute()){
		 	$status = "success";
		}
		
	    return array(
			'status'   => $status
		);
    }




    function viewSubSubject(){
    	    $obj1      = new commonFunctions();
            $dbh       = $obj1->dbh;
    		$status    = "failed";
            $error     = '';
            $details   = '';
    		$selectSubject  = $dbh->prepare("SELECT * FROM tb_sub_subject");
    		$selectSubject->execute();
    		
    		if($selectSubject->rowCount()>0){
    		 while($row = $selectSubject->fetch(PDO::FETCH_ASSOC))
    		 {
    		 $status = "success";
    		 $details[] = $row;
    		  }
    		 }
    		 else
    		 {
    			 $status    = "failed";
    		 }
    		 
    		 return array(
                'status'   => $status,
                'details'  => $details
            );
    }




    function correspondingsub($subject) {
	    $obj1      = new commonFunctions();
        $dbh       = $obj1->dbh;
		$status    = "failed";
        $error     = '';
        $details   = '';
		$selectSubject  = $dbh->prepare("SELECT * FROM tb_sub_subject where `subject_id`='$subject'");
		
		$selectSubject->execute();
		
		if($selectSubject->rowCount()>0){
		 while($row = $selectSubject->fetch(PDO::FETCH_ASSOC))
		 {
		    $status = "success";
		 
            $cate['id'] = $row['sub_subject_id'];
            $cate['name'] = $row['sub_subject'];
            $data[] = $cate;
		}
		 }
		 else
		 {
			 $status    = "failed";
			 $data="failed";
		 }

        return array(
        'status'   => $status,
        'details'  => $data
        );
           
    }




    function update_sub_subject($subid,$subject,$Subsubject) {
	    $obj1      = new commonFunctions();
        $dbh       = $obj1->dbh;
		$status    = "failed";
        $error     = '';
        $details   = '';
		
		$updateSubject  = $dbh->prepare("UPDATE tb_sub_subject set subject_id='$subject',sub_subject='$Subsubject' where sub_subject_id='$subid'");
		if ($updateSubject->execute()){
		 	$status = "success";
		}
		
	    return array(
			'status'   => $status
		);
    }

}

?>