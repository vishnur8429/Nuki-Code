<?php

include_once("class_functions.php");
include_once("class_admin.php");
include_once("class_user.php");
include_once("class_request.php");
include_once("class_student.php");
include_once("class_tutor.php");
include_once("class_wallet.php");
include_once("class_message.php");
include_once("class_rating.php");


$obj1            = new commonFunctions();
$admin_obj       = new adminClass();
$user_obj        = new userClass();
$request_obj     = new requestClass();
$student_obj     = new studentClass();
$tutor_obj       = new tutorClass();
$wallet_obj      = new walletClass();
$message_obj     = new messageClass();
$rating_obj      = new ratingClass();


extract($_REQUEST);

switch($request){
    case 'admin_login':
        $output = $admin_obj->LoginAdmin($username,md5($password));
        break;
    case 'change_password':
        $output = $admin_obj->change_password($userid,$adminid,md5($password),$auth_token);
        break;
	case 'add_subject':
		$output=$admin_obj->add_subject($subject);
        break;
	case 'update_subject':
		$output=$admin_obj->update_subject($subid,$subject);
        break;
    case 'subject_list':
        $output = $admin_obj->viewSubject();
        break;
    case 'sub_subject_list':
        $output = $admin_obj->viewSubSubject();
        break;
	case 'correspondingsub':
		$output=$admin_obj->correspondingsub($subject);
        break;	
	case 'add_sub_subject':
		$output=$admin_obj->add_sub_subject($subject,$sub_subject);
        break;
	case 'update_sub_subject':
		$output=$admin_obj->update_sub_subject($subid,$subject,$Subsubject);
        break;	
	// case 'register_user':
 //        $output = $user_obj->RegisterUser($firstname,$lastname,$email,$university_email,md5($password),$user_type,$profile_picture,$school,$graduation_year,$study_category,$study_subjectid,$study_subsubjectid,$category,$subject,$sub_subject,$rate,$user_introduction,$class_takenid,$device_token);
 //        break;
    case 'register_user':
        $output = $user_obj->RegisterUser($firstname,$lastname,$email,$university_email,md5($password),$user_type,$profile_picture,$school,$graduation_year,$study_details,$teach_details,$rate,$user_introduction,$class_takenid,$device_token,$cardnumber,$expirymonth,$expiryyear,$cvvnumber,$paypalUsername,$paypalPassword,$paypalSignature);
        break;
    case 'account_verify':
        $output = $user_obj->account_verify($email,$code);
        break;
    case 'login_user':
        $output = $user_obj->loginUser($email,md5($password),$device_token);
        break;
    case 'fb_login':
        $output= $user_obj->facebookLogin($facebook_token,$name,$email,$profile_picture,$device_token);
        break;
    // case 'fb_profile_update':
    //     $output = $user_obj->facebookProfileUpdate($userid,$university_email,$firstname,$lastname,md5($password),$user_type,$school,$category,$subject,$sub_subject,$rate,$user_introduction,$class_takenid,$study_category,$study_subjectid,$study_subsubjectid);
    //     break;
    case 'fb_profile_update':
        $output = $user_obj->facebookProfileUpdate($userid,$firstname,$lastname,$university_email,md5($password),$user_type,$school,$graduation_year,$study_details,$teach_details,$rate,$user_introduction,$class_takenid,$cardnumber,$expirymonth,$expiryyear,$cvvnumber,$paypalUsername,$paypalPassword,$paypalSignature);
        break;
    case 'google_plus_login':
        $output= $user_obj->googlePlusLogin($google_token,$device_token);
        break;
    // case 'profile_update':
    //     $output = $user_obj->profileUpdate($userid,$firstname,$lastname,$school,$study_category,$study_subject,$study_subsubject);
    //     break;
    case 'profile_update':
        $output = $user_obj->profileUpdate($userid,$firstname,$lastname,$school);
        break;    
    case 'profile_picture_update':
        $output = $user_obj->profilePictureUpdate($userid,$profile_picture,$auth_token);
        break;
    case 'logout':
        $output = $user_obj->Logout($userid);
        break;
    case 'email_check':
        $output = $user_obj->emailCheck($email);
        break;
    case 'forgot_password':
        $output = $user_obj->forgot_password($email);
        break;
    case 'forgot_password_update':
        $output = $user_obj->forgot_password_update($email,$code,md5($password));
        break;
    case 'user_details':
        $output = $user_obj->userDetails($userid,$adminid,$profileid,$auth_token);
        break;   
    case 'getUserCardDetails':
        $output = $user_obj->getUserCardDetails($userid);
        break; 
    case 'getPaypalDetails':
        $output = $user_obj->getPaypalDetails($userid);
        break; 
    case 'updatePaypalSettings':
        $output = $user_obj->updatePaypalSettings($userid,$paypalUsername,$paypalPassword,$paypalSignature);
        break;        




    case 'getUserStudyDetails':
        $output = $user_obj->getUserStudyDetails($userid);
        break;
    case 'addNewStudyDetails':
        $output = $user_obj->addNewStudyDetails($userid,$category,$subject,$subsubject);
        break;
    case 'deleteStudyDetails':
        $output = $user_obj->deleteStudyDetails($studyid);
        break; 

    case 'getUserTeachDetails':
        $output = $user_obj->getUserTeachDetails($userid);
        break;
    case 'addNewTeachDetails':
        $output = $user_obj->addNewTeachDetails($userid,$category,$subject,$subsubject);
        break;
    case 'deleteTeachDetails':
        $output = $user_obj->deleteTeachDetails($teachid);
        break;   
    case 'getEncrypt':
        $output = $user_obj->getEncrypt($description);
        break;            



    case 'request_add':
        $output= $request_obj->requestAdd($userid,$adminid,$toid,$hour,$price,$comment,$payment_mode,$auth_token);
        break;
    case 'paypal_credit':
        $output= $request_obj->paypalAjax($userid,$card_number,$card_month,$card_year,$card_date,$card_cvv,$first_name,$last_name,$address_1,$address_2,$city,$postal_code,$amount,$tutorid);
        break;
    case 'request_pending_list':
        $output= $request_obj->requestPendingList($userid,$adminid,$auth_token);
        break;
    case 'request_accept_reject':
        $output= $request_obj->requestAcceptReject($userid,$adminid,$fromid,$accept_status,$auth_token);
        break;
    case 'request_start_list':
        $output= $request_obj->requestStartList($userid,$adminid,$auth_token);
        break;
    case 'request_completed_list':
        $output= $request_obj->requestCompletedList($userid,$adminid,$auth_token);
        break;
    case 'tutor_sessionlist':
        $output= $request_obj->tutorSessionList($userid,$adminid,$auth_token);
        break;
    case 'student_sessionlist':
        $output= $request_obj->studentSessionList($userid,$adminid,$auth_token);
        break;
    case 'session_reject':
        $output= $request_obj->sessionReject($userid,$adminid,$requestid,$auth_token);
        break;
    case 'session_complete':
        $output= $request_obj->sessionComplete($userid,$adminid,$requestid,$auth_token);
        break;


    case 'my_student_list':
        $output= $student_obj->myStudentList($userid,$adminid,$auth_token);
        break;
    case 'student_list':
        $output= $student_obj->studentList($userid,$adminid,$sub_subjectid,$auth_token);
        break;
    case 'tutor_list':
        $output= $tutor_obj->tutorList($userid,$adminid,$sub_subjectid,$auth_token);
        break;


    case 'student_wallet_list':
        $output= $wallet_obj->studentWalletList($userid,$adminid,$auth_token);
        break;
    case 'tutor_wallet_list':
        $output= $wallet_obj->tutorWalletList($userid,$adminid,$auth_token);
        break;
    case 'payment_finish':
        $output= $wallet_obj->paymentFinish($userid,$adminid,$requestid,$auth_token);
        break;


    case 'message_userlist':
        $output= $message_obj->GetMessageUserList($userid,$adminid,$auth_token);
        break;
    case 'message_new':
        $output= $message_obj->messageAddNew($userid,$adminid,$to,$message,$image,$auth_token) ;
        break;
    case 'message_details':
        $output= $message_obj->getUserMessage($userid,$adminid,$friendid,$auth_token);
        break;
    case 'message_latest':
        $output= $message_obj->latestMessageList($userid,$adminid,$friendid,$messageid,$auth_token);
        break;


    case 'rating_new':
        $output= $rating_obj->ratingAddNew($userid,$adminid,$toid,$rating,$review,$auth_token);
        break;
    case 'rating_list':
        $output= $rating_obj->getRatingList($userid,$adminid,$auth_token);
        break;
        

    case 'major_minor_new':
        $output = $user_obj->majorMinorNew($userid,$category,$subject,$sub_subject,$rate);
        break;
    case 'major_minor_delete':
        $output = $user_obj->majorMinorDelete($userid,$adminid,$majorid,$auth_token);
        break;


    default:
        $output = 'No Request Type Found!!';
        break;
}

echo json_encode( $output );