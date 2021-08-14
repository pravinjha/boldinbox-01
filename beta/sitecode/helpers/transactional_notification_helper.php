<?php
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
function create_transactional_notification($activity="",$replace_arr=array(),$to_email=""){
$vmta = '';
switch ($activity) {
    case 'welcome':
        $html_content = file_get_contents(config_item('locker_path') ."system_emails/html/welcome.html");
        $txt_content = file_get_contents(config_item('locker_path') ."system_emails/html/welcome.txt");
		$subject = 'Thank You for Signing-up! Welcome to BoldInbox';
		$arrSearchStr = array('[xxx_LINK_xxx]','[USERNAME]','[FIRSTNAME_OR_USERNAME]','[PASSWORD]','xxxcopyright_yearxxx');	
		$new_replace_arr	= array('',$replace_arr[0],$replace_arr[0],$replace_arr[1],date('Y'));
        break;
    case 'confirm_user_registration':
        $html_content = file_get_contents(config_item('locker_path') ."system_emails/html/welcome.html");
        $txt_content = file_get_contents(config_item('locker_path') ."system_emails/html/welcome.txt");
		$subject = 'Thank You for Signing-up! Welcome to BoldInbox';
		$arrSearchStr = array('[xxx_LINK_xxx]','[USERNAME]','[FIRSTNAME_OR_USERNAME]','[PASSWORD]','xxxcopyright_yearxxx');
		$new_replace_arr	= array(site_url("user/confirm_user/".$replace_arr[0]),$replace_arr[2],$replace_arr[2],$replace_arr[3],date('Y'));
        break;
    case 'verify_other_email':
        $html_content = file_get_contents(config_item('locker_path') ."system_emails/html/verify_other_email.html");
        $txt_content = file_get_contents(config_item('locker_path') ."system_emails/html/verify_other_email.txt");
		$subject = 'Verify your email';
		$arrSearchStr = array('[xxx_LINK_xxx]','[USERNAME]','[FIRSTNAME_OR_USERNAME]','xxxcopyright_yearxxx');
		$new_replace_arr	= array(site_url("user/verify/".$replace_arr[0]),$replace_arr[2],$replace_arr[2],date('Y'));
        break;
    case 'campaign_send_notification':
        $html_content = file_get_contents(config_item('locker_path') ."system_emails/html/campaign_sent.html");
        $txt_content = file_get_contents(config_item('locker_path') ."system_emails/html/campaign_sent.txt");
		$subject = 'Your Email Promotion has been completely sent';
		$arrSearchStr = array('[FIRSTNAME_OR_USERNAME]','[CAMPAIGN_NAME]','[TOTAL_CONTACTS_SELECTED]','[CAMPAIGN_VIEW_LINK]','[STAT_LINK]','xxxcopyright_yearxxx');
		$new_replace_arr	= array($replace_arr[0],$replace_arr[1],$replace_arr[2],$replace_arr[3],$replace_arr[4],date('Y'));
        break;
	case 'campaign_approved_notification':
        $html_content = file_get_contents(config_item('locker_path') ."system_emails/html/campaign_approved.html");
        $txt_content = file_get_contents(config_item('locker_path') ."system_emails/html/campaign_approved.txt");
		$subject = 'Your Email Promotion has been approved for release';
		$arrSearchStr = array('[FIRSTNAME_OR_USERNAME]','[CAMPAIGN_NAME]','[CAMPAIGN_VIEW_LINK]','xxxcopyright_yearxxx');
		$new_replace_arr	= array($replace_arr[0],$replace_arr[1],$replace_arr[2],date('Y'));
        break;
	case 'campaign_suspended_notification':
        $html_content = file_get_contents(config_item('locker_path') ."system_emails/html/campaign_suspended.html");
        $txt_content = file_get_contents(config_item('locker_path') ."system_emails/html/campaign_suspended.txt");
		$subject = 'Your campaign was disallowed'; 
		$arrSearchStr = array('[FIRSTNAME_OR_USERNAME]','[CAMPAIGN_NAME]','[CAMPAIGN_VIEW_LINK]','[DISALLOW_REASON]', 'xxxcopyright_yearxxx');
		$new_replace_arr	= array($replace_arr[0],$replace_arr[1],$replace_arr[2],$replace_arr[3],date('Y'));
        break;
	case 'campaign_not_scheduled_notification':
        $html_content = file_get_contents(config_item('locker_path') ."system_emails/html/campaign_not_scheduled.html");
        $txt_content = file_get_contents(config_item('locker_path') ."system_emails/html/campaign_not_scheduled.txt");
		$subject = 'Your scheduled campaign could not be sent';
		$arrSearchStr = array('[FIRSTNAME_OR_USERNAME]','[CAMPAIGN_NAME]','[xxx_number_of_contacts_xxx]','[xxx_max_contacts_xxx]','CAMPAIGN_VIEW_LINK','xxxcopyright_yearxxx');
		$new_replace_arr	= array($replace_arr[0],$replace_arr[1],$replace_arr[2],$replace_arr[3],$replace_arr[4],date('Y'));
        break;
	case 'billing_receipt_notification':
        $html_content = file_get_contents(config_item('locker_path') ."system_emails/html/invoice.html");
        $txt_content = file_get_contents(config_item('locker_path') ."system_emails/html/invoice.txt");
		$subject = 'BoldInbox Payment-Invoice';
		$arrSearchStr = array('[FIRSTNAME_OR_USERNAME]','[ORDER_ID]','[PURCHASE_DATE]','[CURRENT_PLAN]','[AMOUNT_PAID]','[CARD_ENDING_IN]','[BILLED_TO]','[COMPANY]','[PHONE]','[EMAIL_ADDRESS]','[BILLING_ADDRESS]','xxxcopyright_yearxxx');
		$new_replace_arr	= array($replace_arr[0],$replace_arr[1],$replace_arr[2],$replace_arr[3],$replace_arr[4],$replace_arr[5],$replace_arr[6],$replace_arr[7],$replace_arr[8],$replace_arr[9],$replace_arr[10],date('Y'));
        break;
	case 'confirm_subscription':
		
		
		if(trim($replace_arr[5])==""){
			$html_content = "To activate your subscription, please follow the link below.\r\n
If you can't click it, please copy the entire link and paste it into your browser.\r\n\r\n
xxx_LINK_xxx
							\r\n\r\n
Thank You!
							\r\n";
		}else{
			$html_content = trim($replace_arr[5])."\r\n\r\n
xxx_LINK_xxx
						\r\n\r\n	";
		}
		$txt_content = $html_content;
		$html_content = nl2br($html_content);
		$subject = ('' != trim($replace_arr[6]))?trim($replace_arr[6]):'Please Confirm Your Subscription';
		$arrSearchStr = array('xxx_LINK_xxx');		
		$encodedURLData = base64url_encode($replace_arr[2]."-".$replace_arr[3]."-".$replace_arr[4]);		
		$link=site_url("newsletter/signup/verify_subscription/".$encodedURLData);
		
		$to_email=$replace_arr[3];		
		$to=$replace_arr[3];		
		$sender=$replace_arr[1];
		$sender_name=$replace_arr[0];
		$vmta = $replace_arr[7];
		
		$new_replace_arr	= array($link);
		
        break;		
	case 'account_approval':
        $html_content = file_get_contents(config_item('locker_path') ."system_emails/html/account_approval.html");
        $txt_content = file_get_contents(config_item('locker_path') ."system_emails/html/account_approval.txt");
		$subject = 'Thanks for Upgrading - Account Approval';
		$arrSearchStr = array('[FIRSTNAME_OR_USERNAME]','xxxcopyright_yearxxx');
		
		$new_replace_arr	= array($replace_arr[0], date('Y'));
        break;	
	case 'list_growing':
        $html_content = file_get_contents(config_item('locker_path') ."system_emails/html/list_growing.html");
        $txt_content = file_get_contents(config_item('locker_path') ."system_emails/html/list_growing.txt");
		$subject = 'Your Email List is Growing';
		$arrSearchStr = array('[FIRSTNAME_OR_USERNAME]','xxxcopyright_yearxxx');
		
		$new_replace_arr	= array($replace_arr[0], date('Y'));
        break;	
	case 'contact_imported_notification':
        $html_content = file_get_contents(config_item('locker_path') ."system_emails/html/contacts_imported.html");
        $txt_content = file_get_contents(config_item('locker_path') ."system_emails/html/contacts_imported.txt");
		$subject = 'Your Contacts have been Imported';
		$arrSearchStr = array('[FIRSTNAME_OR_USERNAME]','xxxcopyright_yearxxx');
		
		$new_replace_arr	= array($replace_arr[0], date('Y'));
        break;	
	case 'contact_imported_upgrade_notification':
        $html_content = file_get_contents(config_item('locker_path') ."system_emails/html/contacts_imported_upgrade_plan.html");
        $txt_content = file_get_contents(config_item('locker_path') ."system_emails/html/contacts_imported_upgrade_plan.txt");
		$subject = 'Your Contacts have been Imported';
		$arrSearchStr = array('[FIRSTNAME_OR_USERNAME]','xxxcopyright_yearxxx');
		
		$new_replace_arr	= array($replace_arr[0], date('Y'));
        break;	
	case 'refer_freind':
        $html_content = file_get_contents(config_item('locker_path') ."system_emails/html/refer_friend.html");
        $txt_content = file_get_contents(config_item('locker_path') ."system_emails/html/refer_friend.txt");
		$sender_name	= $replace_arr[0];
		$subject = "$sender_name invites you to join BoldInbox";
		$message		= $replace_arr[1];
		$arrSearchStr = array('[xxx_message_xxx]','xxxcopyright_yearxxx');		
		$new_replace_arr	= array(nl2br($message),date('Y'));
        break;	
	case 'user_account_expire':
        $html_content = file_get_contents(config_item('locker_path') ."system_emails/html/termination_notice_for_inactivity.html");
        $txt_content = file_get_contents(config_item('locker_path') ."system_emails/html/termination_notice_for_inactivity.txt");		
		$subject = 'Notice of Account Termination';		
		$arrSearchStr = array('{f_name,username}','xxxcopyright_yearxxx','[xxdaysxx]','[xxnotlogindaysxx]');
		$new_replace_arr	= array($replace_arr[0], date('Y'), $replace_arr[2], $replace_arr[3]);
        break;	
	case 'confirmation_of_account_termination':
        $html_content = file_get_contents(config_item('locker_path') ."system_emails/html/subscription_cancelled.html");
        $txt_content = file_get_contents(config_item('locker_path') ."system_emails/html/subscription_cancelled.txt");		
		$subject = 'Confirmation of Account Termination';		
		$arrSearchStr = array('{f_name,username}','xxxcopyright_yearxxx','[xxdaysxx]');
		$new_replace_arr	= array($replace_arr[0], date('Y'), $replace_arr[2]);
        break;	
	case 'bib_payment_failure':
        $html_content = file_get_contents(config_item('locker_path') ."system_emails/html/failed_cc.html");
        $txt_content = file_get_contents(config_item('locker_path') ."system_emails/html/failed_cc.txt");		
		$subject = 'Your BoldInbox subscription payment has failed.';		
		$arrSearchStr = array('[FIRSTNAME_OR_USERNAME]','xxxcopyright_yearxxx');
		$new_replace_arr	= array($replace_arr[0], date('Y'));
        break;	
	case 'member_unconfirmed_yet':
        $html_content = file_get_contents(config_item('locker_path') ."system_emails/onboarding/member_unconfirmed_yet.html");
        $txt_content = file_get_contents(config_item('locker_path') ."system_emails/onboarding/member_unconfirmed_yet.txt");		
		$subject = 'Thank you for Registration!';		
		$arrSearchStr = array('[xxx_LINK_xxx]','[FIRSTNAME_OR_USERNAME]','xxxcopyright_yearxxx');
		$new_replace_arr	= array(site_url("user/confirm_user/".$replace_arr[0]),$replace_arr[2],date('Y'));
        break;
    case 'active_free_no_campaign_no_contact':
        $html_content = file_get_contents(config_item('locker_path') ."system_emails/onboarding/active_free_no_campaign_sent.html");
        $txt_content = file_get_contents(config_item('locker_path') ."system_emails/onboarding/active_free_no_campaign_sent.txt");		
		$subject = "You're almost there...";		
		$arrSearchStr = array('[FIRSTNAME_OR_USERNAME]','xxxcopyright_yearxxx');
		$new_replace_arr	= array($replace_arr[2],date('Y'));
        break;
    case 'active_free_no_campaign_with_contact':
        $html_content = file_get_contents(config_item('locker_path') ."system_emails/onboarding/active_free_no_campaign_sent_36hrs.html");
        $txt_content = file_get_contents(config_item('locker_path') ."system_emails/onboarding/active_free_no_campaign_sent_36hrs.txt");		
		$subject = "You're almost there...";		
		$arrSearchStr = array('[FIRSTNAME_OR_USERNAME]','xxxcopyright_yearxxx');
		$new_replace_arr	= array($replace_arr[2],date('Y'));
        break;
    case 'active_free_yet_after_7days':
    case 'active_free_yet_after_14days':
    case 'active_free_yet_after_21days':
    case 'active_free_yet_after_28days':
        $html_content = file_get_contents(config_item('locker_path') ."system_emails/onboarding/active_free_yet_after_120hrs.html");
        $txt_content = file_get_contents(config_item('locker_path') ."system_emails/onboarding/active_free_yet_after_120hrs.txt");		
		$subject = "Great Offer Inside!";		
		$arrSearchStr = array('[FIRSTNAME_OR_USERNAME]','xxxcopyright_yearxxx');
		$new_replace_arr	= array($replace_arr[2],date('Y'));
        break;
		
}
	if($activity != 'confirm_subscription'){
		$html_content	= getHeader().$html_content.getFooter();
		$to		= ($to_email !='')?$to_email:$replace_arr[1];

		$html_body	= str_replace($arrSearchStr, $new_replace_arr, $html_content);		
		$txt_body	= str_replace($arrSearchStr, $new_replace_arr, $txt_content);		
		//$subject	= str_replace($arrSearchStr, $new_replace_arr, $subject);		
		$sender	= (trim($sender)!='')?$sender:SYSTEM_EMAIL_FROM;
		$sender_name	= (trim($sender_name)!='')?$sender_name:'BoldInbox';	
		$arrTo = explode(',', $to);
		if(is_array($arrTo)){
			foreach($arrTo as $send_to){
				if($send_to != '')
				send_tmail($send_to, $sender, $sender_name, $subject, $html_body, $txt_body, $vmta);
			}
		}else{
			if( $to !=''){		
				send_tmail($to, $sender, $sender_name, $subject, $html_body, $txt_body, $vmta);
			}
		}
		
	}else{						
		if($replace_arr[2] >0 and  $replace_arr[4] > 0 and $replace_arr[3] !='')
		$to		= $replace_arr[3];
		else
		$to		= '';	
		
		$html_body	= str_replace($arrSearchStr, $new_replace_arr, $html_content);		
		$txt_body	= str_replace($arrSearchStr, $new_replace_arr, $txt_content);		
		$sender	= (trim($sender)!='')?$sender:SYSTEM_EMAIL_FROM;
		$sender_name	= (trim($sender_name)!='')?$sender_name:'BoldInbox';			
		if( $to !=''){		
			send_tmail_plain_text($to, $sender, $sender_name, $subject,  $txt_body, $vmta);			
		}
			
	}

}

function send_member_message_email($to, $sender="",$sender_name="", $subject="",$html_content="",$txt_body="", $vmta='boldinbox.com'){
	$html_content	= getHeader().$html_content.getFooter();
	send_tmail($to, $sender, $sender_name, $subject, $html_content, $txt_body, $vmta);
}
function send_tmail($to, $sender="",$sender_name="", $subject="",$message="",$text_message="", $vmta='boldinbox.com'){
//echo $to."<br/>==================<br/>".$sender."<br/>==================<br/>".$sender_name."<br/>==================<br/>".$subject."<br/>==================<br/>".$message."<br/>==================<br/>".$text_message;

	$vmta='boldinbox.com';
	require_once("phpmailer/class.phpmailer.php");
	$mail = new PHPMailer();	
	$mail->IsSMTP(); // set mailer to use SMTP
	$mail->SMTPDebug = 0;  // debugging: 1 = errors and messages, 2 = messages only
	$mail->SMTPAuth = true;  // authentication enabled
	$arrSmtpDetail = getArrSMTP('pmta-pool-2');
	$mail->Host = $arrSmtpDetail[0];
	$mail->Hostname = $arrSmtpDetail[0];
	$mail->Username = $arrSmtpDetail[1];
	$mail->Password = $arrSmtpDetail[2];
	$mail->Port = $arrSmtpDetail[3];
	$mail->AddCustomHeader('Sender: '.$arrSmtpDetail[4]);	
	$mail->Sender	= $arrSmtpDetail[4];
	 	
	//$mail->AddReplyTo($sender, $sender_name);
	$mail->FromName = $sender_name;
	$mail->From = $sender;  
	$mail->AddReplyTo('support@boldinbox.com', 'BoldInbox Support');	
	$mail->Subject = $subject;
	
	//$mail->AddCustomHeader('x-virtual-mta: '.$mail->Host);	 	
	//$mail->IsHTML(false);	
	//$mail->Body = $text_message;	
	$mail->AltBody = $text_message;
	$mail->MsgHTML($message);	
	$mail->AddAddress($to);	
	@$mail->Send();	
	$mail->SmtpClose();	  
}
function bib_transactional($to, $sender="",$sender_name="", $subject="",$message="",$text_message="", $vmta='boldinbox.com'){
//echo $to."<br/>==================<br/>".$sender."<br/>==================<br/>".$sender_name."<br/>==================<br/>".$subject."<br/>==================<br/>".$message."<br/>==================<br/>".$text_message;

	$vmta='boldinbox.com';
	require_once("phpmailer/class.phpmailer.php");
	$mail = new PHPMailer();	
	$mail->IsSMTP(); // set mailer to use SMTP
	$mail->SMTPDebug = 0;  // debugging: 1 = errors and messages, 2 = messages only
	$mail->SMTPAuth = true;  // authentication enabled
	$arrSmtpDetail = getArrSMTP('pmta-pool-2');
		$mail->Host = $arrSmtpDetail[0];
		$mail->Hostname = $arrSmtpDetail[0];
		$mail->Username = $arrSmtpDetail[1]; 
		$mail->Password = $arrSmtpDetail[2];
		$mail->Port = $arrSmtpDetail[3];
		$mail->AddCustomHeader('Sender: '.$arrSmtpDetail[4]);	
		$mail->Sender	= $arrSmtpDetail[4];
  	
	$mail->AddReplyTo('sumit@boldinbox.com', 'BoldInbox Support');	
	$mail->FromName = 'BoldInbox';
	$mail->From = 'support@boldinbox.com';  
	$mail->Subject = $subject;	
	 	
	$mail->AltBody = $text_message;
	$mail->MsgHTML($message);	
	$mail->AddAddress($to);	
	@$mail->Send();	
	$mail->SmtpClose();	  
}
//function send_tmail_plain_text($to, $sender="",$sender_name="", $subject="",$text_message="", $vmta='rcorp73'){
function send_tmail_plain_text($to, $sender="",$sender_name="", $subject="",$text_message="", $vmta='mail3.bibesp.com'){
	require_once("phpmailer/class.phpmailer.php");
	$mail = new PHPMailer();	
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
	
	$mail->FromName = $sender_name;	
	$mail->From = $sender;	
	$mail->Subject = $subject;
	
	//$mail->AddCustomHeader('x-virtual-mta: '.$mail->Host);	 
	$mail->IsHTML(false);	
	$mail->Body = $text_message;		
	$mail->AddAddress($to);	
	@$mail->Send();	
	$mail->SmtpClose();	
}

function base64url_encode($data) { 
	  return rtrim(strtr(base64_encode($data), '+/', '-_'), '='); 
} 

function getHeader(){
return '
<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">	  
<head><title>BoldInbox.Com | Transactional Email</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="SKYPE_TOOLBAR" content="SKYPE_TOOLBAR_PARSER_COMPATIBLE" /> 	      
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
</head>
	  
	<body>		   	 
	
	<table cellspacing="0" cellpadding="0" width="600" border = "0" align="center">
		<tr>
			<td align="left">
				<div align = "center"><a href="http://www.boldinbox.com/"> <img src="http://www.boldinbox.com/locker/images/logo-blue.png" alt="BoldInbox.Com" border="0"  /></a></div>
				<div align = "center" style = "border-top:solid 2px #3D9AFF;"></div>
			</td>									  								
		</tr>
		<tr> 																		
			<td>
				<div style = "margin:10px;line-height:1.5;font-size:13px;">
			  <!-- header ends -->

			  
			  ';


}

function getFooter(){
return ' <!-- footer -->		  
					
				</div>				
			</td>		
		</tr>
		<tr>
			<td align="left">
				<div align = "center" style = "border-top:solid 2px #3D9AFF;margin-bottom:10px;"></div>
				<div align = "center" style="text-align:center;border:solid 0px;width:150px;margin:0px auto;box-shadow:2px 3px 5px #888;font-family:calibri;"><a href="http://www.boldinbox.com/" style = "border:solid 0px;text-decoration:none;color:#111;font-size:12px;">Powered By<img src="http://www.boldinbox.com/locker/images/powered-by-logo-blue.png" alt="Powered By BoldInbox.Com" title="Powered By BoldInbox.Com" border="0" width = "150" /></a></div>
			</td>									  								
		</tr>
	</table>

	<div style="text-align: center;margin: 20px auto;">		
		<div style="line-height: 1.5; font-family:Arial,Helvetica,sans-serif;color:#333333;font-size:12px;padding-bottom:10px;">
		&copy; '.date('Y').' BoldInbox, All Rights Reserved</div>
	</div>

</body>
</html>
		
';
}
?>