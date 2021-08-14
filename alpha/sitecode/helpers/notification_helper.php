<?php
/**
*	Plugin log activity for user activity
*/

if (!defined('BASEPATH')) exit('No direct script access allowed');
if ( ! function_exists('getArrSMTP')){
	function getArrSMTP($vmta){
		$arrSmtp = config_item('SMTP');
		$arrPoolVmta = config_item('pool_and_vmta');
		foreach($arrPoolVmta as $k=>$arrVmta){
			if(in_array($vmta, $arrVmta)){
				return $arrSmtp[$k];
				exit;			
			}	
		}
		return $arrSmtp[0];
		exit;	
	}
}
function get_Admin_notification_email(){
	$CI             =& get_instance();
	$sql            = 'SELECT config_name,config_value FROM `red_site_configurations` where `config_name` = "admin_notification_email"';
    $query          = $CI->db->query($sql);
	$admin_email	= "";
	if ($query->num_rows() == 1){
		$row = $query->row();
		$admin_email        = $row->config_value;
	}
	return $admin_email;
}
function create_notification($activity="",$replace_arr=array(),$totcount =0, $total_complaint_emails=0, $total_delivered_emails=0){
	$admin_email=get_Admin_notification_email();
	if($activity=="upgradation"){
		$html_content = file_get_contents(config_item('locker_path') ."system_emails/admin_upgradtion_package_email.html");
		$arrSearchStr = array('xxx_user_name_xxx','xxx_number_of_contacts_xxx');
		$message=str_replace($arrSearchStr, $replace_arr, $html_content); 
		// Removed by pravinjha@gmail.com	
		// send_mail($admin_email, SYSTEM_EMAIL_FROM, 'BoldInbox', 'User Requires upgradation',$message,$message);
	}elseif($activity=="upgraded"){
		$html_content = file_get_contents(config_item('locker_path') ."system_emails/admin_upgraded_package_email.html");
		$arrSearchStr = array('xxx_user_name_xxx','xxx_package_1_xxx','xxx_package_2_xxx');		
		$message=str_replace($arrSearchStr, $replace_arr, $html_content);
		$subject = ($replace_arr[1] > $replace_arr[2])?'Plan change:'. $replace_arr[0].' downgraded from '.$replace_arr[1] .' to '. $replace_arr[2].' plan': 'Plan change:'.$replace_arr[0].' upgraded '.$replace_arr[1] .' to '. $replace_arr[2].' plan';
		send_mail($admin_email, SYSTEM_EMAIL_FROM, 'BoldInbox', $subject,$message,$message);
	}elseif($activity=="add_contact_limit"){
		$html_content = file_get_contents(config_item('locker_path') ."system_emails/admin_addcontact_notification_email.html");
		$arrSearchStr = array('xxx_user_name_xxx','xxx_contacts_xxx','xxx_max_contacts_xxx','xxx_action_xxx', 'xxx_total_contacts_xxx');
		$message=str_replace($arrSearchStr, $replace_arr, $html_content);
		send_mail($admin_email, SYSTEM_EMAIL_FROM, 'BoldInbox', 'Contact added by user is '.$replace_arr[1],$message,$message);
	}elseif($activity=="delete_contact_limit"){
		$html_content = file_get_contents(config_item('locker_path') ."system_emails/admin_contact_notification_email.html");
		$arrSearchStr = array('xxx_user_name_xxx','xxx_contacts_xxx','xxx_max_contacts_xxx','xxx_action_xxx');
		$message=str_replace($arrSearchStr, $replace_arr, $html_content);		
		$subject = ($totcount ==0)?'Contacts deleted by user is more than '.$replace_arr[2] : 'Contacts deleted by user is '.$totcount ;		
		send_mail($admin_email, SYSTEM_EMAIL_FROM, 'BoldInbox', $subject,$message,$message);
	}elseif($activity=="complaint_percentage_high"){
		$html_content = file_get_contents(config_item('locker_path') ."system_emails/admin_complaint_notification_email.html");
		$arrSearchStr = array('xxx_user_name_xxx','xxx_campaign_name_xxx','[CAMPAIGN_VIEW_LINK]');
		$message=str_replace($arrSearchStr, $replace_arr, $html_content);
		send_mail($admin_email, SYSTEM_EMAIL_FROM, 'BoldInbox', 'High complaint rate of '.$totcount.'% with '.$total_delivered_emails.' emails delivered and '.$total_complaint_emails.' complaint emails',$message,$message);
	}elseif($activity=="bounce_percentage_high"){
		$html_content = file_get_contents(config_item('locker_path') ."system_emails/admin_bounce_notification_email.html");
		$arrSearchStr = array('xxx_user_name_xxx','xxx_campaign_name_xxx','[CAMPAIGN_VIEW_LINK]');
		$message=str_replace($arrSearchStr, $replace_arr, $html_content);	  
		send_mail($admin_email, SYSTEM_EMAIL_FROM, 'BoldInbox', 'High bounce rate of '.$totcount.'% with '.$total_delivered_emails.' emails delivered and '.$total_complaint_emails.' bounced emails',$message,$message);
	}else if($activity=="feedback"){
		$html_content = file_get_contents(config_item('locker_path') ."system_emails/admin_feedback_notification_email.html");		
		$feedback_arr[]=$replace_arr[2];		//create feedback replace array		
		$arrSearchStr = array('xxx_message_xxx');
		$message=str_replace($arrSearchStr, $feedback_arr, $html_content);		
		contact_mail(SYSTEM_EMAIL_FROM, $replace_arr[0], 'BoldInbox feedback', 'Support: '.$replace_arr[1], $message, $message);
		send_mail(DEVELOPER_EMAIL, $replace_arr[0], 'BoldInbox', 'Support: '.$replace_arr[1], $message, $message);
	}else if($activity=="campaign_not_scheduled_notification"){
		$html_content = file_get_contents(config_item('locker_path') ."system_emails/admin_campaign_not_schedule_email.html");	
		$arrSearchStr = array('xxx_user_name_xxx','xxx_campaign_xxx');
		$message_cnt=str_replace($arrSearchStr, $replace_arr, $html_content);		
		send_mail($admin_email, SYSTEM_EMAIL_FROM, 'BoldInbox', 'User can not send scheduled campaign',$message_cnt,$message_cnt);
	}else if($activity=="testmail_sent"){
		$html_content = file_get_contents(config_item('locker_path') ."system_emails/admin_testmail_sent.html");	
		$arrSearchStr = array('xxx_user_name_xxx','xxx_recipients_xxx', 'xxx_subject_xxx', 'xxx_message_text_xxx');		
		$message_cnt=str_replace($arrSearchStr, $replace_arr, $html_content);		
		send_mail($admin_email, SYSTEM_EMAIL_FROM, 'BoldInbox', 'Test mail sent',$message_cnt,$message_cnt);
	}
}
function send_bib_mail($to="", $sender="",$sender_name="", $subject="",$message="",$text_message=""){
	require_once("phpmailer/class.phpmailer.php");
	$mail = new PHPMailer();	
	//SMTP begin
	$mail->IsSMTP();// set mailer to use SMTP
	$mail->SMTPDebug = 0;  // debugging: 1 = errors and messages, 2 = messages only
	$mail->SMTPAuth = true;  // authentication enabled
	$arrSmtpDetail = getArrSMTP('pmta-pool-1');
	$mail->Host = $arrSmtpDetail[0];
	$mail->Hostname = $arrSmtpDetail[0];
	$mail->Username = $arrSmtpDetail[1];
	$mail->Password = $arrSmtpDetail[2];
	$mail->Port = $arrSmtpDetail[3];
	$mail->AddCustomHeader('Sender: '.$arrSmtpDetail[4]);	
	$mail->Sender	= $arrSmtpDetail[4];	
	//mail begin
	$mail->FromName = $sender_name;	
	$mail->From = $sender;	
	$mail->Subject = $subject;		
	$arrEmailz = explode(",",$to);
	

	foreach($arrEmailz as $eachEmailID){		
		$mail->AddAddress($eachEmailID);
	}
	// the HTML to the plain text. Store it into the variable.	
	$mail->AltBody = $text_message;
	$mail->MsgHTML($message);
	@$mail->Send();	
	$mail->SmtpClose();
}


function send_mail($to="", $sender="",$sender_name="", $subject="",$message="",$text_message=""){
	require_once("phpmailer/class.phpmailer.php");
	$mail = new PHPMailer();	
	//SMTP begin
	$mail->IsSMTP();// set mailer to use SMTP
	$mail->SMTPDebug = 0;  // debugging: 1 = errors and messages, 2 = messages only
	$mail->SMTPAuth = true;  // authentication enabled
	$arrSmtpDetail = getArrSMTP('pmta-pool-1');
	$mail->Host = $arrSmtpDetail[0];
	$mail->Hostname = $arrSmtpDetail[0];
	$mail->Username = $arrSmtpDetail[1];
	$mail->Password = $arrSmtpDetail[2];
	$mail->Port = $arrSmtpDetail[3];
	$mail->AddCustomHeader('Sender: '.$arrSmtpDetail[4]);	
	$mail->Sender	= $arrSmtpDetail[4];
	//mail begin
	$mail->FromName = $sender_name;	
	$mail->From = $sender;	
	$mail->Subject = $subject;		
	$arrEmailz = explode(",",$to);
	

	foreach($arrEmailz as $eachEmailID){		
		$mail->AddAddress($eachEmailID);
	}
	// the HTML to the plain text. Store it into the variable.	
	$mail->AltBody = $text_message;
	$mail->MsgHTML($message);
	@$mail->Send();	
	$mail->SmtpClose();
}
function contact_mail($to="", $sender="",$sender_name="", $subject="",$message="",$text_message=""){
	require_once("phpmailer/class.phpmailer.php");
	$mail = new PHPMailer();	
	//SMTP begin
	$mail->IsSMTP();// set mailer to use SMTP
	$mail->SMTPDebug = 0;  // debugging: 1 = errors and messages, 2 = messages only
	$mail->SMTPAuth = true;  // authentication enabled
	$arrSmtpDetail = getArrSMTP('pmta-pool-1');
	$mail->Host = $arrSmtpDetail[0];
	$mail->Hostname = $arrSmtpDetail[0];
	$mail->Username = $arrSmtpDetail[1];
	$mail->Password = $arrSmtpDetail[2];
	$mail->Port = $arrSmtpDetail[3];
	$mail->AddCustomHeader('Sender: '.$arrSmtpDetail[4]);	
	$mail->Sender	= $arrSmtpDetail[4];
	
	If(stripos($sender,'@yahoo.') === FALSE AND stripos($sender,'@aol.') === FALSE){		
		$mail->FromName = $sender_name;	
		$mail->From = $sender;	
	}else{
		$mail->AddReplyTo($sender, $sender_name);
		$mail->FromName = $sender_name;	
		$mail->From = 'support@boldinbox.com';
	}
	$mail->Subject = $subject;		
	$arrEmailz = explode(",",$to);
	

	foreach($arrEmailz as $eachEmailID){		
		$mail->AddAddress($eachEmailID);
	}
	// the HTML to the plain text. Store it into the variable.	
	$mail->AltBody = $text_message;
	$mail->MsgHTML($message);
	@$mail->Send();	
	$mail->SmtpClose();
}
?>