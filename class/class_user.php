<?php
include_once('class_functions.php');


class userClass {

    // //user registration
    // function RegisterUser($firstname,$lastname,$email,$university_email,$password,$user_type,$profile_picture,$school,$graduation_year,$study_category,$study_subjectid,$study_subsubjectid,$category,$subject,$sub_subject,$rate,$user_introduction,$class_takenid,$device_token){

    //     $obj1   = new commonFunctions();
    //     $dbh    = $obj1->dbh;
    //     $server_url = $obj1->getServerUrl();

    //     $status = 'failed';
    //     $userid = 0;
    //     $category_array     = explode(',',$category);
    //     $subject_array      = explode(',',$subject);
    //     $sub_subject_array  = explode(',',$sub_subject);

    //     $selectUser = $dbh->prepare("select * from tb_user where email=:email");
    //     $selectUser->bindParam(':email',$email,PDO::PARAM_STR);
    //     $selectUser->execute();
    //     if( $selectUser->rowCount() == 0 ){
    //         $created_on = $obj1->getCurrentDate();
    //         $authToken  = $obj1->generateAuthToken($email);
    //         $insertUser = $dbh->prepare( "INSERT INTO tb_user(date,first_name,last_name,email,university_email,password,user_type,profile_picture,school,graduation_year,study_category,study_subjectid,study_subsubjectid,user_introduction,device_token,auth_token,status)
    //                                                    VALUES('$created_on',:first_name,:last_name,:email,:university_email,:password,:user_type,:profile_picture,:school,:graduation_year,:study_category,:study_subjectid,:study_subsubjectid,:user_introduction,:device_token,:auth_token,'0')");
    //         $insertUser->bindParam(":first_name",$firstname,PDO::PARAM_STR);
    //         $insertUser->bindParam(":last_name",$lastname,PDO::PARAM_STR);
    //         $insertUser->bindParam(":email",$email,PDO::PARAM_STR);
    //         $insertUser->bindParam(":university_email",$university_email,PDO::PARAM_STR);
    //         $insertUser->bindParam(":password",$password,PDO::PARAM_STR);

    //         $insertUser->bindParam(":user_type",$user_type,PDO::PARAM_STR);
    //         $insertUser->bindParam(":profile_picture",$profile_picture,PDO::PARAM_STR);

    //         $insertUser->bindParam(":school",$school,PDO::PARAM_STR);
    //         $insertUser->bindParam(":graduation_year",$graduation_year,PDO::PARAM_INT);

    //         $insertUser->bindParam(":study_category",$study_category,PDO::PARAM_STR);
    //         $insertUser->bindParam(":study_subjectid",$study_subjectid,PDO::PARAM_INT);
    //         $insertUser->bindParam(":study_subsubjectid",$study_subsubjectid,PDO::PARAM_INT);

    //         $insertUser->bindParam(":user_introduction",$user_introduction,PDO::PARAM_STR);

    //         $insertUser->bindParam(":device_token",$device_token,PDO::PARAM_STR);
    //         $insertUser->bindParam(":auth_token",$authToken,PDO::PARAM_STR);

    //         $statue  = $insertUser->execute();
    //         $userid  = $dbh->lastInsertId();

    //         if((sizeof($category_array) == sizeof($subject_array))){
    //             $i =0;
    //             foreach($category_array as $each_category){
    //                 $each_subject = $subject_array[$i];
    //                 $each_sub_subject = $sub_subject_array[$i];
    //                 $insert = $dbh->prepare( "INSERT INTO tb_major_minor(date,userid,category,subject_id,sub_subject_id,hourly_rate,active_status) VALUES('$created_on',:userid,:category,:subject_id,:sub_subject_id,:hourly_rate,'1')");
    //                 $insert->bindParam(":userid",$userid,PDO::PARAM_INT);
    //                 $insert->bindParam(":category",$each_category,PDO::PARAM_STR);
    //                 $insert->bindParam(":subject_id",$each_subject,PDO::PARAM_INT);
    //                 $insert->bindParam(":sub_subject_id",$each_sub_subject,PDO::PARAM_INT);
    //                 $insert->bindParam(":hourly_rate",$rate,PDO::PARAM_INT);
    //                 $insert->execute();
    //                 ++$i;
    //             }
    //         }

    //         if($userid>0){
    //             $secret_code = $obj1->generateAuthToken($email);
    //             $secret_code = substr($secret_code,0,6);
    //             $update_user = $dbh->prepare("UPDATE tb_user SET verification_code='$secret_code' WHERE userid='$userid'");
    //             $update_user->execute();

    //             $subject = "Verify Nuki Account";
    //             $message = 'Thanking you to register on Nuki App!Please enter the following verification code to activate your account!'.'<p>'.$secret_code.'</p>';
    //             $mail = $obj1->mailFunction('support@srishtis.com',$university_email,'Nuki',$message);
    //             $status = 'success';
    //         }
    //     }
    //     else{
    //         $status = 'email exist';
    //     }

    //     return array(
    //         "status"  => $status,
    //         "user_id" => $userid
    //     );
    // }
    //user registration
    function RegisterUser($firstname,$lastname,$email,$university_email,$password,$user_type,$profile_picture,$school,$graduation_year,$study_details,$teach_details,$rate,$user_introduction,$class_takenid,$device_token,$cardnumber,$expirymonth,$expiryyear,$cvvnumber,$paypalUsername,$paypalPassword,$paypalSignature){

        $obj1   = new commonFunctions();
        $dbh    = $obj1->dbh;
        $server_url = $obj1->getServerUrl();

        $status = 'failed';
        $userid = 0;

        $selectUser = $dbh->prepare("select * from tb_user where email=:email");
        $selectUser->bindParam(':email',$email,PDO::PARAM_STR);
        $selectUser->execute();
        if( $selectUser->rowCount() == 0 ){
            $created_on = $obj1->getCurrentDate();
            $authToken  = $obj1->generateAuthToken($email);
            $insertUser = $dbh->prepare( "INSERT INTO tb_user(date,first_name,last_name,email,university_email,password,user_type,profile_picture,school,graduation_year,user_introduction,device_token,auth_token,status)
                                                       VALUES('$created_on',:first_name,:last_name,:email,:university_email,:password,:user_type,:profile_picture,:school,:graduation_year,:user_introduction,:device_token,:auth_token,'0')");
            $insertUser->bindParam(":first_name",$firstname,PDO::PARAM_STR);
            $insertUser->bindParam(":last_name",$lastname,PDO::PARAM_STR);
            $insertUser->bindParam(":email",$email,PDO::PARAM_STR);
            $insertUser->bindParam(":university_email",$university_email,PDO::PARAM_STR);
            $insertUser->bindParam(":password",$password,PDO::PARAM_STR);
            $insertUser->bindParam(":user_type",$user_type,PDO::PARAM_STR);
            $insertUser->bindParam(":profile_picture",$profile_picture,PDO::PARAM_STR);
            $insertUser->bindParam(":school",$school,PDO::PARAM_STR);
            $insertUser->bindParam(":graduation_year",$graduation_year,PDO::PARAM_INT);
            $insertUser->bindParam(":user_introduction",$user_introduction,PDO::PARAM_STR);
            $insertUser->bindParam(":device_token",$device_token,PDO::PARAM_STR);
            $insertUser->bindParam(":auth_token",$authToken,PDO::PARAM_STR);
            $statue  = $insertUser->execute();
            $userid  = $dbh->lastInsertId();

            // STUDY
            $study_details = json_decode($study_details);
            foreach($study_details as $value){
                $study_category   = $value->category;
                $study_subject    = $value->subjectid;
                $study_subsubject = $value->subsubjectid;
                $query_study = $dbh->prepare( "INSERT INTO tb_study(`userid`,`category`,`subjectid`,`subsubjectid`,`date_added`,`date_modified`) 
                                                             VALUES('$userid','$study_category','$study_subject','$study_subsubject','$created_on','$created_on')" );
                $query_study->execute();
            }

            // TEACH
            if($user_type=='Tutor'){
                $teach_details = json_decode($teach_details);
                foreach($teach_details as $value){
                    $insert = $dbh->prepare( "INSERT INTO tb_major_minor(date,userid,category,subject_id,sub_subject_id,active_status) 
                                                                  VALUES('$created_on',:userid,:category,:subject_id,:sub_subject_id,'1')");
                    $insert->bindParam(":userid",$userid,PDO::PARAM_INT);
                    $insert->bindParam(":category",$value->category,PDO::PARAM_STR);
                    $insert->bindParam(":subject_id",$value->subjectid,PDO::PARAM_INT);
                    $insert->bindParam(":sub_subject_id",$value->subsubjectid,PDO::PARAM_INT);
                    $insert->execute();
                }

                // TUTOR PAYPAL DETAILS
                $paypalUsername  = $obj1->encrypt($paypalUsername);
                $paypalPassword  = $obj1->encrypt($paypalPassword);
                $paypalSignature = $obj1->encrypt($paypalSignature);
                $qry_tutor_paypal_details = $dbh->prepare( "INSERT INTO tb_userpaypaldetails(userid,username,password,signature,date_added,date_modified) 
                                                                                      VALUES(:userid,:paypalUsername,:paypalPassword,:paypalSignature,'$created_on','$created_on')");
                $qry_tutor_paypal_details->bindParam(":userid",$userid,PDO::PARAM_INT);
                $qry_tutor_paypal_details->bindParam(":paypalUsername",$paypalUsername,PDO::PARAM_STR);
                $qry_tutor_paypal_details->bindParam(":paypalPassword",$paypalPassword,PDO::PARAM_STR);
                $qry_tutor_paypal_details->bindParam(":paypalSignature",$paypalSignature,PDO::PARAM_STR);
                $qry_tutor_paypal_details->execute();
            }

            // STUDENT - CREDIT CARD DETAILS
            if($user_type=='Student'){
                $cardnumber  = $obj1->encrypt($cardnumber);
                $expirymonth = $obj1->encrypt($expirymonth);
                $expiryyear  = $obj1->encrypt($expiryyear);
                $cvvnumber   = $obj1->encrypt($cvvnumber);
                $qry_insert_student_card_details = $dbh->prepare( "INSERT INTO tb_usercarddetails(userid,cardnumber,expirymonth,expiryyear,cvvnumber,date_added,date_modified) 
                                                                                           VALUES(:userid,:cardnumber,:expirymonth,:expiryyear,:cvvnumber,'$created_on','$created_on')");
                $qry_insert_student_card_details->bindParam(":userid",$userid,PDO::PARAM_INT);
                $qry_insert_student_card_details->bindParam(":cardnumber",$cardnumber,PDO::PARAM_STR);
                $qry_insert_student_card_details->bindParam(":expirymonth",$expirymonth,PDO::PARAM_STR);
                $qry_insert_student_card_details->bindParam(":expiryyear",$expiryyear,PDO::PARAM_STR);
                $qry_insert_student_card_details->bindParam(":cvvnumber",$cvvnumber,PDO::PARAM_STR);
                $qry_insert_student_card_details->execute();
            }

            if($userid>0){
                $secret_code = $obj1->generateAuthToken($email);
                $secret_code = substr($secret_code,0,6);
                $update_user = $dbh->prepare("UPDATE tb_user SET verification_code='$secret_code' WHERE userid='$userid'");
                $update_user->execute();
                $subject = "Verify Nuki Account";
                $message = 'Thanking you to register on Nuki App!Please enter the following verification code to activate your account!'.'<p>'.$secret_code.'</p>';
                $mail    = $obj1->mailFunction('support@srishtis.com',$university_email,'Nuki',$message);
                $status  = 'success';
            }
        }
        else {
            $status = 'email exist';
        }

        return array(
            "status"  => $status,
            "user_id" => $userid
        );
    }



    // Account Verify
    function account_verify($email,$code){
        $obj1 = new commonFunctions();
        $dbh = $obj1->dbh;

        $status = 'failed';
        $row    = array();
        $error  = '';

        $selectUser = $dbh->prepare("SELECT * FROM tb_user WHERE email=:email AND verification_code=:verification_code AND status=0");
        $selectUser->bindParam(":email",$email);
        $selectUser->bindParam(":verification_code",$code);
        $selectUser->execute();

        if($selectUser->rowCount()>0){
            $row = $selectUser->fetch(PDO::FETCH_ASSOC);
            $userid = $row['userid'];
            $row['fullname']            = $row['first_name'].' '.$row['last_name'];
            $row['average_rating']      = $obj1->averageRating($userid);
            $row['major_minor_subject'] = $obj1->majorMinorSubjectList($userid);
            $row['session_history']     = $obj1->sessionHistory($userid);


            $row['studyItems'] = $this->getUserStudyDetails($userid);
            $row['teachItems'] = $this->getUserTeachDetails($userid);


            $update_user = $dbh->prepare("UPDATE tb_user SET status=1 WHERE userid='$userid'");
            $update_user->execute();
            $status = 'success';
        }else{
            $selectUser1 = $dbh->prepare("SELECT * FROM tb_user WHERE email=:email AND verification_code=:verification_code AND status=1");
            $selectUser1->bindParam(":email",$email);
            $selectUser1->bindParam(":verification_code",$code);
            $selectUser1->execute();
            if($selectUser1->rowCount() > 0 ){
                $row = $selectUser1->fetch(PDO::FETCH_ASSOC);
                $userid = $row['userid'];
                $row['fullname']            = $row['first_name'].' '.$row['last_name'];
                $row['average_rating']      = $obj1->averageRating($userid);
                $row['major_minor_subject'] = $obj1->majorMinorSubjectList($userid);
                $row['session_history']     = $obj1->sessionHistory($userid);


                $row['studyItems'] = $this->getUserStudyDetails($userid);
                $row['teachItems'] = $this->getUserTeachDetails($userid);


                $status = 'success';
                $error  = 'already verified';
            }else{
                $error = 'code invalid';
            }
        }

        return array(
            "status"    => $status,
            "error"     => $error,
            "user_data" => $row
        );
    }



    // user login
    function loginUser($email,$password,$device_token){
        $obj1 = new commonFunctions();
        $dbh = $obj1->dbh;

        $status = 'failed';
        $error  = '';
        $row = array();

        $selectUser = $dbh->prepare("SELECT * FROM tb_user WHERE email=:email AND password=:password");
        $selectUser->bindParam(":email",$email);
        $selectUser->bindParam(":password",$password);
        $selectUser->execute();

        if($selectUser->rowCount() > 0 ){
            $row = $selectUser->fetch(PDO::FETCH_ASSOC);
            if($row['status']==0){
                $error = "verification needed";
            }else{
                $row['fullname'] = $row['first_name'].' '.$row['last_name'];
                $userid = $row['userid'];
                $email  = $row['email'];
                $authToken = $obj1->generateAuthToken($email);

                $update_user = $dbh->prepare("UPDATE tb_user SET auth_token='$authToken',device_token='$device_token' WHERE userid='$userid'");
                $update_user->execute();

                $row['device_token'] = $device_token;
                $row['auth_token'] = $authToken;
                $row['average_rating'] = $obj1->averageRating($userid);
                $row['major_minor_subject'] = $obj1->majorMinorSubjectList($userid);
                $row['session_history']     = $obj1->sessionHistory($userid);


                $sub_subject_id = $row['study_subsubjectid'];
                $query1 = $dbh->prepare("SELECT a.sub_subject,b.subject FROM tb_sub_subject a INNER JOIN tb_subject b ON a.sub_subject_id='$sub_subject_id' AND a.subject_id=b.subject_id");
                $query1->execute();
                $row1   = $query1->fetch(PDO::FETCH_ASSOC);
                $row['study_subject']    = $row1['subject'];
                $row['study_subsubject'] = $row1['sub_subject'];


                $row['studyItems'] = $this->getUserStudyDetails($userid);
                $row['teachItems'] = $this->getUserTeachDetails($userid);


                $status = 'success';
            }
        }

        return array(
            "status"    => $status,
            "error"     => $error,
            "user_data" => $row
        );
    }



    function facebookLogin($facebook_token,$name,$email,$profile_picture,$device_token){
        $obj1 = new commonFunctions();
        $dbh  = $obj1->dbh;

        $status  = "failed";
        $details = array();
        $error   = "";
        $userid  = 0;

        $selectUser = $dbh->prepare("select * from tb_user where email=:email");
        $selectUser->bindParam(':email',$email,PDO::PARAM_STR);
        $selectUser->execute();
        if($selectUser->rowCount() == 0){
            $created_on = $obj1->getCurrentDate();
            $authToken  = $obj1->generateAuthToken($email);
            $insertUser = $dbh->prepare( "INSERT INTO tb_user(date,first_name,email,fb_profile_picture,facebook_token,device_token,auth_token,status) VALUES('$created_on',:first_name,:email,:profile_picture,:facebook_token,:device_token,:auth_token,'0')");
            $insertUser->bindParam(":first_name",$name,PDO::PARAM_STR);
            $insertUser->bindParam(":email",$email,PDO::PARAM_STR);
            $insertUser->bindParam(":profile_picture",$profile_picture,PDO::PARAM_STR);
            $insertUser->bindParam(":facebook_token",$facebook_token,PDO::PARAM_STR);
            $insertUser->bindParam(":device_token",$device_token,PDO::PARAM_STR);
            $insertUser->bindParam(":auth_token",$authToken,PDO::PARAM_STR);
            $insertUser->execute();
            $userid = $dbh->lastInsertId();
        }else{
            $row       = $selectUser->fetch(PDO::FETCH_ASSOC);
            $userid    = $row['userid'];
            $email     = $row['email'];
            $authToken = $obj1->generateAuthToken($email);

            $update = $dbh->prepare( "UPDATE `tb_user` SET fb_profile_picture='$profile_picture',facebook_token='$facebook_token',auth_token='$authToken',device_token='$device_token' WHERE email='$email'");
            $update->execute();
        }

        $select1 = $dbh->prepare("SELECT * FROM tb_user WHERE userid=:userid");
        $select1->bindParam(":userid",$userid);
        $select1->execute();
        if($select1->rowCount()>0) {
            $details = $select1->fetch(PDO::FETCH_ASSOC);
            $details['fullname']        = $details['first_name'].' '.$details['last_name'];
            $details['device_token']    = $device_token;
            $details['auth_token']      = $authToken;
            $details['average_rating']  = $obj1->averageRating($userid);
            $details['major_minor_subject'] = $obj1->majorMinorSubjectList($userid);
            $details['session_history']     = $obj1->sessionHistory($userid);


            $sub_subject_id = $details['study_subsubjectid'];
            $query1 = $dbh->prepare("SELECT a.sub_subject,b.subject FROM tb_sub_subject a INNER JOIN tb_subject b ON a.sub_subject_id='$sub_subject_id' AND a.subject_id=b.subject_id");
            $query1->execute();
            $row1   = $query1->fetch(PDO::FETCH_ASSOC);
            $details['study_subject']    = $row1['subject'];
            $details['study_subsubject'] = $row1['sub_subject'];

            
            $details['studyItems'] = $this->getUserStudyDetails($userid);
            $details['teachItems'] = $this->getUserTeachDetails($userid);


            $status = 'success';
        }

        return array(
            "status"  => $status,
            "error"   => $error,
            "details" => $details
        );
    }




    //Facebook Profile details update
    // function facebookProfileUpdate($userid,$university_email,$firstname,$lastname,$password,$user_type,$school,$category,$subject,$sub_subject,$rate,$user_introduction,$class_takenid,$study_category,$study_subjectid,$study_subsubjectid){

    //     $obj1   = new commonFunctions();
    //     $dbh    = $obj1->dbh;
    //     $details= array();
    //     $status = 'failed';

    //     $category_array     = explode(',',$category);
    //     $subject_array      = explode(',',$subject);
    //     $sub_subject_array  = explode(',',$sub_subject);

    //     $selectUser = $dbh->prepare("select * from tb_user where userid=:userid");
    //     $selectUser->bindParam(':userid',$userid,PDO::PARAM_INT);
    //     $selectUser->execute();
    //     if( $selectUser->rowCount() > 0 ){
    //         $rowUser = $selectUser->fetch(PDO::FETCH_ASSOC);
    //         $created_on = $obj1->getCurrentDate();
    //         $secret_code = $obj1->generateAuthToken($rowUser['email']);
    //         $secret_code = substr($secret_code,0,6);
    //         $update = $dbh->prepare("UPDATE tb_user SET university_email='$university_email',first_name='$firstname',last_name='$lastname',password='$password',user_type='$user_type',school='$school',verification_code='$secret_code',study_category='$study_category',study_subjectid='$study_subjectid',study_subsubjectid='$study_subsubjectid' WHERE userid='$userid'");
    //         $update->execute();

    //         $subject = "Verify Nuki Account";
    //         $message = 'Thanking you to register on Nuki App!Please enter the following verification code to activate your account!'.'<p>'.$secret_code.'</p>';
    //         $mail    = $obj1->mailFunction('support@srishtis.com',$rowUser['university_email'],'Nuki',$message);


    //         if((sizeof($category_array) == sizeof($subject_array))){
    //             $i =0;
    //             foreach($category_array as $each_category){
    //                 $each_subject = $subject_array[$i];
    //                 $each_sub_subject = $sub_subject_array[$i];
    //                 $insert = $dbh->prepare( "INSERT INTO tb_major_minor(date,userid,category,subject_id,sub_subject_id,hourly_rate,active_status) VALUES('$created_on',:userid,:category,:subject_id,:sub_subject_id,:hourly_rate,'0')");
    //                 $insert->bindParam(":userid",$userid,PDO::PARAM_INT);
    //                 $insert->bindParam(":category",$each_category,PDO::PARAM_STR);
    //                 $insert->bindParam(":subject_id",$each_subject,PDO::PARAM_INT);
    //                 $insert->bindParam(":sub_subject_id",$each_sub_subject,PDO::PARAM_INT);
    //                 $insert->bindParam(":hourly_rate",$rate,PDO::PARAM_INT);
    //                 $insert->execute();
    //                 ++$i;
    //             }
    //         }

    //         $select1 = $dbh->prepare("SELECT * FROM tb_user WHERE userid=:userid");
    //         $select1->bindParam(":userid",$userid);
    //         $select1->execute();
    //         if($select1->rowCount()>0) {
    //             $details = $select1->fetch(PDO::FETCH_ASSOC);
    //             $email   = $details['email'];
    //             $details['fullname']            = $details['first_name'].' '.$details['last_name'];
    //             $details['average_rating']      = $obj1->averageRating($userid);
    //             $details['major_minor_subject'] = $obj1->majorMinorSubjectList($userid);
    //             $details['session_history']     = $obj1->sessionHistory($userid);


    //             $sub_subject_id = $details['study_subsubjectid'];
    //             $query1 = $dbh->prepare("SELECT a.sub_subject,b.subject FROM tb_sub_subject a INNER JOIN tb_subject b ON a.sub_subject_id='$sub_subject_id' AND a.subject_id=b.subject_id");
    //             $query1->execute();
    //             $row1   = $query1->fetch(PDO::FETCH_ASSOC);
    //             $details['study_subject']    = $row1['subject'];
    //             $details['study_subsubject'] = $row1['sub_subject'];

                
    //             $status = 'success';
    //         }

    //     }

    //     return array(
    //         "status"  => $status,
    //         'details' => $details
    //     );
    // }
    function facebookProfileUpdate($userid,$firstname,$lastname,$university_email,$password,$user_type,$school,$graduation_year,$study_details,$teach_details,$rate,$user_introduction,$class_takenid,$cardnumber,$expirymonth,$expiryyear,$cvvnumber,$paypalUsername,$paypalPassword,$paypalSignature){

        $obj1       = new commonFunctions();
        $dbh        = $obj1->dbh;

        $details    = array();
        $status     = 'failed';

        // CHECK USER COUNT
        $selectUser = $dbh->prepare("select * from tb_user where userid=:userid");
        $selectUser->bindParam(':userid',$userid,PDO::PARAM_INT);
        $selectUser->execute();
        if( $selectUser->rowCount() > 0 ){
            $rowUser      = $selectUser->fetch(PDO::FETCH_ASSOC);
            $created_on   = $obj1->getCurrentDate();
            $secret_code  = $obj1->generateAuthToken($rowUser['email']);
            $secret_code  = substr($secret_code,0,6);
            $update = $dbh->prepare("UPDATE tb_user SET university_email='$university_email',first_name='$firstname',last_name='$lastname',password='$password',user_type='$user_type',school='$school',verification_code='$secret_code' WHERE userid='$userid'");
            $update->execute();

            // MAIL TO USER EMAIL
            $subject = "Verify Nuki Account";
            $message = 'Thanking you to register on Nuki App!Please enter the following verification code to activate your account!'.'<p>'.$secret_code.'</p>';
            $mail    = $obj1->mailFunction('support@srishtis.com',$rowUser['university_email'],'Nuki',$message);

            // USER STUDY DETAILS
            $study_details = json_decode($study_details);
            foreach($study_details as $value){
                $study_category   = $value->category;
                $study_subject    = $value->subjectid;
                $study_subsubject = $value->subsubjectid;
                $query_study = $dbh->prepare( "INSERT INTO tb_study(`userid`,`category`,`subjectid`,`subsubjectid`,`date_added`,`date_modified`) 
                                                             VALUES('$userid','$study_category','$study_subject','$study_subsubject','$created_on','$created_on')" );
                $query_study->execute();
            }

            // USER TEACH DETAILS
            if($user_type=='Tutor'){
                $teach_details = json_decode($teach_details);
                foreach($teach_details as $value){
                    $insert = $dbh->prepare( "INSERT INTO tb_major_minor(date,userid,category,subject_id,sub_subject_id,active_status) 
                                                                  VALUES('$created_on',:userid,:category,:subject_id,:sub_subject_id,'1')");
                    $insert->bindParam(":userid",$userid,PDO::PARAM_INT);
                    $insert->bindParam(":category",$value->category,PDO::PARAM_STR);
                    $insert->bindParam(":subject_id",$value->subjectid,PDO::PARAM_INT);
                    $insert->bindParam(":sub_subject_id",$value->subsubjectid,PDO::PARAM_INT);
                    $insert->execute();
                }

                // TUTOR PAYPAL DETAILS
                $paypalUsername  = $obj1->encrypt($paypalUsername);
                $paypalPassword  = $obj1->encrypt($paypalPassword);
                $paypalSignature = $obj1->encrypt($paypalSignature);
                $qry_tutor_paypal_details = $dbh->prepare( "INSERT INTO tb_userpaypaldetails(userid,username,password,signature,date_added,date_modified) 
                                                                                      VALUES(:userid,:paypalUsername,:paypalPassword,:paypalSignature,'$created_on','$created_on')");
                $qry_tutor_paypal_details->bindParam(":userid",$userid,PDO::PARAM_INT);
                $qry_tutor_paypal_details->bindParam(":paypalUsername",$paypalUsername,PDO::PARAM_STR);
                $qry_tutor_paypal_details->bindParam(":paypalPassword",$paypalPassword,PDO::PARAM_STR);
                $qry_tutor_paypal_details->bindParam(":paypalSignature",$paypalSignature,PDO::PARAM_STR);
                $qry_tutor_paypal_details->execute();
            }

            // STUDENT - CREDIT CARD DETAILS
            if($user_type=='Student'){
                $cardnumber  = $obj1->encrypt($cardnumber);
                $expirymonth = $obj1->encrypt($expirymonth);
                $expiryyear  = $obj1->encrypt($expiryyear);
                $cvvnumber   = $obj1->encrypt($cvvnumber);
                $qry_insert_student_card_details = $dbh->prepare( "INSERT INTO tb_usercarddetails(userid,cardnumber,expirymonth,expiryyear,cvvnumber,date_added,date_modified) 
                                                                                           VALUES(:userid,:cardnumber,:expirymonth,:expiryyear,:cvvnumber,'$created_on','$created_on')");
                $qry_insert_student_card_details->bindParam(":userid",$userid,PDO::PARAM_INT);
                $qry_insert_student_card_details->bindParam(":cardnumber",$cardnumber,PDO::PARAM_STR);
                $qry_insert_student_card_details->bindParam(":expirymonth",$expirymonth,PDO::PARAM_STR);
                $qry_insert_student_card_details->bindParam(":expiryyear",$expiryyear,PDO::PARAM_STR);
                $qry_insert_student_card_details->bindParam(":cvvnumber",$cvvnumber,PDO::PARAM_STR);
                $qry_insert_student_card_details->execute();
            }

            // GET USER DETAILS
            $select1 = $dbh->prepare("SELECT * FROM tb_user WHERE userid=:userid");
            $select1->bindParam(":userid",$userid);
            $select1->execute();
            if($select1->rowCount()>0) {
                $details = $select1->fetch(PDO::FETCH_ASSOC);
                $email   = $details['email'];
                $details['fullname']            = $details['first_name'].' '.$details['last_name'];
                $details['average_rating']      = $obj1->averageRating($userid);
                $details['major_minor_subject'] = $obj1->majorMinorSubjectList($userid);
                $details['session_history']     = $obj1->sessionHistory($userid);

                $sub_subject_id = $details['study_subsubjectid'];
                $query1 = $dbh->prepare("SELECT a.sub_subject,b.subject FROM tb_sub_subject a INNER JOIN tb_subject b ON a.sub_subject_id='$sub_subject_id' AND a.subject_id=b.subject_id");
                $query1->execute();
                $row1   = $query1->fetch(PDO::FETCH_ASSOC);
                $details['study_subject']    = $row1['subject'];
                $details['study_subsubject'] = $row1['sub_subject'];


                $details['studyItems'] = $this->getUserStudyDetails($userid);
                $details['teachItems'] = $this->getUserTeachDetails($userid);

                $status = 'success';
            }
        }

        return array(
            "status"  => $status,
            'details' => $details
        );
    }




    // google plus login
    function googlePlusLogin($google_token,$device_token){
        $obj1 = new commonFunctions();
        $dbh  = $obj1->dbh;

        $status  = "failed";
        $details = array();
        $error   = "";

        $selectUser = $dbh->prepare("select * from tb_user where google_token=:google_token");
        $selectUser->bindParam(':google_token',$google_token,PDO::PARAM_STR);
        $selectUser->execute();
        if( $selectUser->rowCount() == 0 ){
            $created_on = $obj1->getCurrentDate();
            $authToken  = $obj1->generateAuthToken($google_token);
            $insertUser = $dbh->prepare( "INSERT INTO tb_user(date,google_token,device_token,auth_token,status) VALUES('$created_on',:device_token,:google_token,:auth_token,'1')");
            $insertUser->bindParam(":google_token",$google_token,PDO::PARAM_STR);
            $insertUser->bindParam(":device_token",$google_token,PDO::PARAM_STR);
            $insertUser->bindParam(":auth_token",$authToken,PDO::PARAM_STR);
            $insertUser->execute();
            $userid = $dbh->lastInsertId();

            $select1 = $dbh->prepare("SELECT * FROM tb_user WHERE userid=:userid");
            $select1->bindParam(":userid",$userid);
            $select1->execute();
            if($select1->rowCount()>0){
                $details = $select1->fetch(PDO::FETCH_ASSOC);
                $details['fullname'] = $details['first_name'].' '.$details['last_name'];
                $details['auth_token']      = $authToken;
                $details['average_rating']  = $obj1->averageRating($userid);
                $details['major_minor_subject'] = $obj1->majorMinorSubjectList($userid);
                $details['session_history']     = $obj1->sessionHistory($userid);
                $status = 'success';
            }
        }else{
            $details = $selectUser->fetch(PDO::FETCH_ASSOC);

            $details['fullname'] = $details['first_name'].' '.$details['last_name'];
            $userid = $details['userid'];
            $email  = $details['email'];
            $google_token = $details['google_token'];
            $authToken = $obj1->generateAuthToken($google_token);

            $update = $dbh->prepare( "UPDATE `tb_user` SET auth_token='$authToken',device_token='$device_token' WHERE google_token='$google_token'");
            $update->execute();

            $details['device_token']    = $device_token;
            $details['auth_token']      = $authToken;
            $details['average_rating']  = $obj1->averageRating($userid);
            $details['major_minor_subject'] = $obj1->majorMinorSubjectList($userid);
            $details['session_history']     = $obj1->sessionHistory($userid);

            $sub_subject_id = $details['study_subsubjectid'];
            $query1 = $dbh->prepare("SELECT a.sub_subject,b.subject FROM tb_sub_subject a INNER JOIN tb_subject b ON a.sub_subject_id='$sub_subject_id' AND a.subject_id=b.subject_id");
            $query1->execute();
            $row1   = $query1->fetch(PDO::FETCH_ASSOC);
            $details['study_subject']    = $row1['subject'];
            $details['study_subsubject'] = $row1['sub_subject'];


            $details['studyItems'] = $this->getUserStudyDetails($userid);
            $details['teachItems'] = $this->getUserTeachDetails($userid);


            $status = 'success';
        }


        return array(
            "status"  => $status,
            "error"   => $error,
            "user_data" => $details
        );
    }




    //Profile details update
    // function profileUpdate($userid,$firstname,$lastname,$school,$study_category,$study_subject,$study_subsubject){

    //     $obj1   = new commonFunctions();
    //     $dbh    = $obj1->dbh;
    //     $user_details   = array();
    //     $status = 'failed';

    //     $update = $dbh->prepare("UPDATE tb_user SET first_name='$firstname',last_name='$lastname',school='$school',study_category='$study_category',study_subjectid='$study_subject',study_subsubjectid='$study_subsubject' WHERE userid='$userid'");
    //     $update->execute();

    //     $select1 = $dbh->prepare("SELECT * FROM tb_user WHERE userid=:userid");
    //     $select1->bindParam(":userid",$userid);
    //     $select1->execute();
    //     if($select1->rowCount() > 0 ) {
    //         $details = $select1->fetch(PDO::FETCH_ASSOC);
    //         $details['fullname'] = $details['first_name'].' '.$details['last_name'];
    //         $details['average_rating']      = $obj1->averageRating($userid);
    //         $details['major_minor_subject'] = $obj1->majorMinorSubjectList($userid);
    //         $details['session_history']     = $obj1->sessionHistory($userid);

    //         $sub_subject_id = $details['study_subsubjectid'];
    //         $query1 = $dbh->prepare("SELECT a.sub_subject,b.subject FROM tb_sub_subject a INNER JOIN tb_subject b ON a.sub_subject_id='$sub_subject_id' AND a.subject_id=b.subject_id");
    //         $query1->execute();
    //         $row1   = $query1->fetch(PDO::FETCH_ASSOC);
    //         $details['study_subject']    = $row1['subject'];
    //         $details['study_subsubject'] = $row1['sub_subject'];


    //         $details['studyItems'] = $this->getUserStudyDetails($userid);
    //         $details['teachItems'] = $this->getUserTeachDetails($userid);


    //         $status = 'success';
    //     }

    //     return array(
    //         "status"  => $status,
    //         'details' => $details
    //     );
    // }
    function profileUpdate($userid,$firstname,$lastname,$school){

        $obj1   = new commonFunctions();
        $dbh    = $obj1->dbh;
        $user_details   = array();
        $status = 'failed';

        $update = $dbh->prepare("UPDATE tb_user SET first_name='$firstname',last_name='$lastname',school='$school' WHERE userid='$userid'");
        $update->execute();

        $select1 = $dbh->prepare("SELECT * FROM tb_user WHERE userid=:userid");
        $select1->bindParam(":userid",$userid);
        $select1->execute();
        if($select1->rowCount() > 0 ) {
            $details = $select1->fetch(PDO::FETCH_ASSOC);
            $details['fullname'] = $details['first_name'].' '.$details['last_name'];
            $details['average_rating']      = $obj1->averageRating($userid);
            $details['major_minor_subject'] = $obj1->majorMinorSubjectList($userid);
            $details['session_history']     = $obj1->sessionHistory($userid);

            $sub_subject_id = $details['study_subsubjectid'];
            $query1 = $dbh->prepare("SELECT a.sub_subject,b.subject FROM tb_sub_subject a INNER JOIN tb_subject b ON a.sub_subject_id='$sub_subject_id' AND a.subject_id=b.subject_id");
            $query1->execute();
            $row1   = $query1->fetch(PDO::FETCH_ASSOC);
            $details['study_subject']    = $row1['subject'];
            $details['study_subsubject'] = $row1['sub_subject'];

            $details['studyItems'] = $this->getUserStudyDetails($userid);
            $details['teachItems'] = $this->getUserTeachDetails($userid);

            $status = 'success';
        }

        return array(
            "status"  => $status,
            'details' => $details
        );
    }




    //Profile details update
    function profilePictureUpdate($userid,$profile_picture,$auth_token){

        $obj1   = new commonFunctions();
        $dbh    = $obj1->dbh;
        $status = 'failed';

        $update = $dbh->prepare("UPDATE tb_user SET profile_picture='$profile_picture' WHERE userid='$userid'");
        $update->execute();
        $status = 'success';

        return array(
            "status"  => $status,
            'details' => ''
        );
    }



    function Logout($userid){
        $obj1   = new commonFunctions();
        $dbh    = $obj1->dbh;
        $status = "failed";

        $selectUser = $dbh->prepare("SELECT * FROM tb_user WHERE userid=:userid");
        $selectUser->bindParam(":userid",$userid);
        $selectUser->execute();
        if($selectUser->rowCount() > 0 ){
            $update_user = $dbh->prepare("UPDATE tb_user SET auth_token='' AND device_token='' WHERE userid='$userid'");
            $update_user->execute();
            $status = "success";
        }

        return array(
            "status" => $status
        );
    }



    // Email check
    function emailCheck($email){
        $obj1   = new commonFunctions();
        $dbh    = $obj1->dbh;
        $details = '';
        $error   = '';
        $status = 'failed';


        $selectUser = $dbh->prepare("SELECT * FROM tb_user WHERE email=:email");
        $selectUser->bindParam(":email",$email);
        $selectUser->execute();
        if($selectUser->rowCount() == 0){
            $status = 'success';
        }else{
            $error   = "email exist";
        }

        return array(
            'status'   => $status,
            'error'    => $error,
            'details'  => $details,
        );
    }



    // forgot password
    function forgot_password($email){

        $obj1   = new commonFunctions();
        $dbh    = $obj1->dbh;
        $status = 'failed';
        $error  = '';
        $user_details = array();

        $selectUser = $dbh->prepare("SELECT * FROM tb_user WHERE email=:email");
        $selectUser->bindParam(":email",$email);
        $selectUser->execute();
        if($selectUser->rowCount() > 0){
            $user_details = $selectUser->fetch(PDO::FETCH_ASSOC);
            $secret_code = $obj1->generateAuthToken($email);
            $secret_code = substr($secret_code,0,6);

            $update_code = $dbh->prepare("UPDATE tb_user SET secret_code='$secret_code' WHERE email='$email'");
            $update_code->execute();

            $link    = $obj1->getServerUrl().'forgot_password_reset.php?email='.$email.'&code='.$secret_code;
            $subject = "Reset your password on Nuki";
            $message = 'Please click on the following link to reset your password!'.'<p>'.$link.'</p>';
            $from = "support@srishtis.com";
            $to   = $email;
            $mail = $obj1->mailFunction($from,$to,$subject,$message);
            if($mail){
                $status = "success";
            }
        }else{
            $error   = "email invalid";
        }

        return array(
            'status' => $status,
            'error'  => $error,
        );

    }



    // forgot password update
    function forgot_password_update($email,$secret_code,$password){

        $obj1   = new commonFunctions();
        $dbh    = $obj1->dbh;
        $status = 'failed';
        $details  = '';

        $selectUser = $dbh->prepare("SELECT * FROM tb_user WHERE email=:email");
        $selectUser->bindParam(":email",$email);
        $selectUser->execute();
        if($selectUser->rowCount() > 0){
            $user_details = $selectUser->fetch(PDO::FETCH_ASSOC);
            if($secret_code == $user_details['secret_code']){
                $update_code = $dbh->prepare("UPDATE tb_user SET password='$password',secret_code='' WHERE email='$email'");
                $update_code->execute();
                $status = "success";
            }else{
                $error   = "code invalid";
            }
        }else{
            $error   = "email invalid";
        }

        return array(
            'status' => $status,
            'details'  => $details,
        );

    }



    function change_password($userid,$adminid,$current_password,$new_password,$auth_token){
        $obj1   = new commonFunctions();
        $dbh    = $obj1->dbh;

        $status    = false;
        $details   = '';
        $error     = '';
        $user_details = array();

        $user_details = $obj1->checkAuthToken($userid,$adminid,$auth_token);
        if(sizeof($user_details)>0){
            $current_password = md5($current_password);
            $new_password     = md5($new_password);
            $query  = $dbh->prepare( "SELECT * FROM `tb_user` WHERE userid='$userid' AND `password`='$current_password' " );
            $query->execute();
            if( $query->rowCount() > 0 ){
                $update = $dbh->prepare( "UPDATE `tb_user` SET password='$new_password' WHERE userid='$userid'");
                if( $update->execute() ){
                    $status = true;
                }
            }
            else{
                $details = "current password mismatch";
            }
        }else{
            $error   = "Invalid Token";
        }

        return array(
            'status'   => $status,
            'error'    => $error,
            'details'  => $details
        );
    }



    // Userlist
    function userlist($userid,$adminid,$auth_token){
        $obj1 = new commonFunctions();
        $dbh  = $obj1->dbh;

        $status  = "failed";
        $error   = "";
        $details      = array();
        $user_details = array();

        $user_details = $obj1->checkAuthToken($userid,$adminid,$auth_token);
        if(sizeof($user_details)>0){
            $selectUser = $dbh->prepare("SELECT * FROM tb_user");
            $selectUser->execute();
            if($selectUser->rowCount() > 0 ){
                $status  = "success";
                while($row = $selectUser->fetch(PDO::FETCH_ASSOC)){
                    $each_userid = $row['userid'];
                    $row['average_rating'] = $obj1->averageRating($each_userid);
                    $details[] = $row;
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



    // Userlist
    function userDetails($userid,$adminid,$profileid,$auth_token){
        $obj1 = new commonFunctions();
        $dbh  = $obj1->dbh;

        $status  = "failed";
        $error   = "";
        $myrating = '';
        $myreview = '';
        $row     = array();
        $rating_array = array();

        $user_details = $obj1->checkAuthToken($userid,$adminid,$auth_token);
        if(sizeof($user_details)>0){
            $selectUser = $dbh->prepare("SELECT * FROM tb_user where `userid`='$profileid'");
            $selectUser->execute();
            if($selectUser->rowCount() > 0 ){
                $status  = "success";
                $row = $selectUser->fetch(PDO::FETCH_ASSOC);
                $each_userid = $row['userid'];
                $row['average_rating'] = $obj1->averageRating($each_userid);
                $rating_array          = $obj1->getMyRating($userid,$profileid);
                if(sizeof($rating_array)>0){
                    $myrating = $rating_array['rating'];
                    $myreview = $rating_array['review'];
                }
                $row['profile_picture']= trim($row['profile_picture']);
                $row['fb_profile_picture']= trim($row['fb_profile_picture']);
                $row['my_rating'] = $myrating;
                $row['my_review'] = $myreview;
                $row['fullname']  = $row['first_name'].' '.$row['last_name'];
                $row['major_minor_subject'] = $obj1->majorMinorSubjectList($each_userid);
                $row['session_history']     = $obj1->sessionHistory($profileid);


                $row['studyItems'] = $this->getUserStudyDetails($userid);
                $row['teachItems'] = $this->getUserTeachDetails($userid);


            }
        }else{
            $error   = "Invalid Token";
        }

        return array(
            "status"  => $status,
            "error"   => $error,
            "details" => $row
        );
    }



    function majorMinorNew($userid,$category,$subject,$sub_subject,$rate){
        $obj1   = new commonFunctions();
        $dbh    = $obj1->dbh;
        $created_on = $obj1->getCurrentDate();
        $row        = array();
        $status     = 'failed';

        $insert = $dbh->prepare( "INSERT INTO tb_major_minor(date,userid,category,subject_id,sub_subject_id,hourly_rate,active_status) VALUES('$created_on',:userid,:category,:subject_id,:sub_subject_id,:hourly_rate,'1')");
        $insert->bindParam(":userid",$userid,PDO::PARAM_INT);
        $insert->bindParam(":category",$category,PDO::PARAM_STR);
        $insert->bindParam(":subject_id",$subject,PDO::PARAM_INT);
        $insert->bindParam(":sub_subject_id",$sub_subject,PDO::PARAM_INT);
        $insert->bindParam(":hourly_rate",$rate,PDO::PARAM_INT);
        $insert->execute();
        $majorid  = $dbh->lastInsertId();
        $status   = 'success';

        $select = $dbh->prepare("SELECT m.major_id,m.userid,m.category,s.subject,s1.sub_subject,m.hourly_rate FROM tb_major_minor m INNER JOIN tb_subject s ON m.`userid`='$userid' AND s.subject_id=m.subject_id AND m.major_id=:majorid INNER JOIN tb_sub_subject s1 ON s1.sub_subject_id=m.sub_subject_id ");
        $select->bindParam(":majorid",$majorid);
        $select->execute();
        if($select->rowCount() > 0){
            $row = $select->fetch(PDO::FETCH_ASSOC);
        }

        return array(
            "status"  => $status,
            "details" => $row
        );
    }



    function majorMinorDelete($userid,$adminid,$majorid,$auth_token){
        $obj1   = new commonFunctions();
        $dbh    = $obj1->dbh;
        $status = 'failed';

        $delete = $dbh->prepare("DELETE FROM tb_major_minor WHERE major_id='$majorid'");
        $delete = $delete->execute();
        $status   = 'success';

        return array(
            "status"  => $status,
            "details" => ''
        );
    }




    // GET USER STUDY DETAILS
    function getUserStudyDetails($userid){
        
        $obj1 = new commonFunctions();
        $dbh  = $obj1->dbh;        

        $data  = array();
        $query = $dbh->prepare( "SELECT a.`id`,
                                        a.`category`,
                                        a.`subjectid`,
                                        b.`subject`,
                                        a.`subsubjectid`,
                                        c.`sub_subject` AS `subsubject`,
                                        a.`date_added` 
                                        FROM `tb_study` a LEFT JOIN `tb_subject` b ON a.`subjectid`=b.`subject_id`
                                        LEFT JOIN `tb_sub_subject` c ON a.`subsubjectid`=c.`sub_subject_id`
                                        WHERE a.`userid`='$userid 'ORDER BY a.`id` DESC" );
        $query->execute();
        while($row  = $query->fetch(PDO::FETCH_ASSOC)){
            $data[] = $row;
        }
          
        return $data;

    }



    // GET USER TEACH DETAILS
    function getUserTeachDetails($userid){
        
        $obj1  = new commonFunctions();
        $dbh   = $obj1->dbh;        

        $data  = array();
        $query = $dbh->prepare( "SELECT a.`major_id` AS `id`,
                                        a.`category`,
                                        a.`subject_id`,
                                        b.`subject`,
                                        a.`sub_subject_id`,
                                        c.`sub_subject` AS `subsubject`,
                                        a.`hourly_rate`,
                                        a.`active_status`,
                                        a.`date_modified` 
                                        FROM `tb_major_minor` a LEFT JOIN `tb_subject` b ON a.`subject_id`=b.`subject_id`
                                        LEFT JOIN `tb_sub_subject` c ON a.`sub_subject_id`=c.`sub_subject_id`
                                        WHERE a.`userid`='$userid' ORDER BY a.`major_id` DESC" );
        $query->execute();
        while($row  = $query->fetch(PDO::FETCH_ASSOC)){
            $data[] = $row;
        }

        return $data;

    }



    // ADD NEW USER STUDY DETAILS
    function addNewStudyDetails($userid,$category,$subject,$subsubject){
        
        $obj1   = new commonFunctions();
        $dbh    = $obj1->dbh; 
        $date   = $obj1->getCurrentDate(); 

        $status = false;      
        $data   = 0;
        $query  = $dbh->prepare( "INSERT INTO tb_study(`userid`,`category`,`subjectid`,`subsubjectid`,`date_added`,`date_modified`) 
                                                VALUES('$userid','$category','$subject','$subsubject','$date','$date')" );
        if( $query->execute() ){
            $status = true;
            $data   = $dbh->lastInsertId();
        }
        
        return array(
            'status' => $status,
            'result' => $data
            );

    }



    // ADD NEW USER TEACH DETAILS
    function addNewTeachDetails($userid,$category,$subject,$subsubject){
        
        $obj1   = new commonFunctions();
        $dbh    = $obj1->dbh; 
        $date   = $obj1->getCurrentDate(); 

        $status = false;      
        $data   = 0;
        $query  = $dbh->prepare( "INSERT INTO tb_major_minor(`date`,`userid`,`category`,`subject_id`,`sub_subject_id`,`active_status`) 
                                                      VALUES('$date','$userid','$category','$subject','$subsubject','1')");
        if( $query->execute() ){
            $status = true;
            $data   = $dbh->lastInsertId();
        }
        
        return array(
            'status' => $status,
            'result' => $data
            );

    }



    // DELETE USER STUDY DETAILS
    function deleteStudyDetails($studyid){
        
        $obj1   = new commonFunctions();
        $dbh    = $obj1->dbh; 
        
        $status = false;      
        $query  = $dbh->prepare( "DELETE FROM `tb_study` WHERE `id`='$studyid'" );
        if( $query->execute() ){
            $status = true;
        }
        
        return array(
            'status' => $status
            );

    }


    // DELETE USER STUDY DETAILS
    function deleteTeachDetails($teachid){
        
        $obj1   = new commonFunctions();
        $dbh    = $obj1->dbh; 
        
        $status = false;      
        $query  = $dbh->prepare( "DELETE FROM `tb_major_minor` WHERE `major_id`='$teachid'" );
        if( $query->execute() ){
            $status = true;
        }
        
        return array(
            'status' => $status
            );

    }




    function getUserCardDetails($userid){

        $obj1   = new commonFunctions();
        $dbh    = $obj1->dbh;

        $status = false;
        $data   = array();

        $query  = $dbh->prepare( "SELECT * FROM `tb_usercarddetails` WHERE `userid`=:userid" );
        $query->bindParam(':userid',$userid,PDO::PARAM_INT);
        $query->execute();
        if( $query->rowCount() > 0 ){
            $status = true;
            $row    = $query->fetch(PDO::FETCH_ASSOC);
            $row['cardnumber']  = $obj1->decrypt($row['cardnumber']);
            $row['expirymonth'] = $obj1->decrypt($row['expirymonth']);
            $row['expiryyear']  = $obj1->decrypt($row['expiryyear']);
            $row['cvvnumber']   = $obj1->decrypt($row['cvvnumber']);
            $data   = $row;
        }

        return array(
            'status'  => $status,
            'details' => $data
            );
        
    }




    function getPaypalDetails($userid){

        $obj1   = new commonFunctions();
        $dbh    = $obj1->dbh;

        $status = false;
        $data   = array();

        $query  = $dbh->prepare( "SELECT * FROM `tb_userpaypaldetails` WHERE `userid`=:userid" );
        $query->bindParam(':userid',$userid,PDO::PARAM_INT);
        $query->execute();
        if( $query->rowCount() > 0 ){
            $status = true;
            $row    = $query->fetch(PDO::FETCH_ASSOC);
            $row['username']   = $obj1->decrypt($row['username']);
            $row['password']   = $obj1->decrypt($row['password']);
            $row['signature']  = $obj1->decrypt($row['signature']);
            $data   = $row;
        }

        return array(
            'status'  => $status,
            'details' => $data
            );
        
    }




    function updatePaypalSettings($userid,$paypalUsername,$paypalPassword,$paypalSignature){

        $obj1       = new commonFunctions();
        $dbh        = $obj1->dbh;

        $status     = false;
        $data       = array();

        $username   = $obj1->encrypt($paypalUsername);
        $password   = $obj1->encrypt($paypalPassword);
        $signature  = $obj1->encrypt($paypalSignature);
        $date       = $obj1->getCurrentDate(); 

        $select     = $dbh->prepare( "SELECT * FROM `tb_userpaypaldetails` WHERE `userid`='$userid'" );
        $select->execute();
        if($select->rowCount()>0){
            $update = $dbh->prepare( "UPDATE tb_userpaypaldetails SET `username`='$username',
                                                                      `password`='$password',
                                                                      `signature`='$signature',
                                                                      `date_modified`='$date' 
                                                                      WHERE `userid`='$userid'");
            $update->execute();
            $status = true;
        } else {
            $insert = $dbh->prepare( "INSERT INTO `tb_userpaypaldetails`(`userid`,`username`,`password`,`signature`,`date_added`,`date_modified`)
                                                                  VALUES('$userid','$username','$password','$signature','$date','$date')" );
            $insert->execute();
            $status = true;
        }


        return array(
            'status' => $status
            );

    }



    
    function getEncrypt($description){
        // $encrypt = $this->encrypt($description);
        // echo $encrypt;
        // die();

        $decrypt = $this->decrypt($description);
        echo $decrypt; 
        die();

    }

}
?>