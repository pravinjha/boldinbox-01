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
function send_onboarding_mail($activity="",$replace_arr=array(),$to_email=""){
$vmta = '';
$testmode =0;
switch ($activity) {
    
	case 'member_unconfirmed_yet':		
        $html_content = file_get_contents(config_item('locker_path') ."system_emails/onboarding/member_unconfirmed_yet.html");
        $txt_content = file_get_contents(config_item('locker_path') ."system_emails/onboarding/member_unconfirmed_yet.txt");		
		$subject = 'Thank you for Registration!';		
		$arrSearchStr = array('[xxx_LINK_xxx]','[FIRSTNAME_OR_USERNAME]','xxxcopyright_yearxxx');
		$new_replace_arr	= array(site_url("user/confirm_user/".$replace_arr[1]),$replace_arr[0],date('Y'));
        break;
    case 'active_free_with_campaign':
        $html_content = file_get_contents(config_item('locker_path') ."system_emails/onboarding/active_free_with_campaign.html");
        $txt_content = file_get_contents(config_item('locker_path') ."system_emails/onboarding/active_free_with_campaign.txt");		
		$subject = "Just Checking In...";		
		$arrSearchStr = array('[FIRSTNAME_OR_USERNAME]','xxxcopyright_yearxxx');
		$new_replace_arr	= array($replace_arr[0],date('Y'));
        break;
    case 'active_free_no_campaign_no_contact':
        $html_content = file_get_contents(config_item('locker_path') ."system_emails/onboarding/active_free_no_campaign_sent.html");
        $txt_content = file_get_contents(config_item('locker_path') ."system_emails/onboarding/active_free_no_campaign_sent.txt");		
		$subject = "You're almost there...";		
		$arrSearchStr = array('[FIRSTNAME_OR_USERNAME]','xxxcopyright_yearxxx');
		$new_replace_arr	= array($replace_arr[0],date('Y'));
        break;
    case 'active_free_no_campaign_with_contact':
        $html_content = file_get_contents(config_item('locker_path') ."system_emails/onboarding/active_free_no_campaign_sent_36hrs.html");
        $txt_content = file_get_contents(config_item('locker_path') ."system_emails/onboarding/active_free_no_campaign_sent_36hrs.txt");		
		$subject = " Let's roll out your first email campaign!";		
		$arrSearchStr = array('[FIRSTNAME_OR_USERNAME]','xxxcopyright_yearxxx');
		$new_replace_arr	= array($replace_arr[0],date('Y'));
        break;
    case 'active_free_yet_after_7days':
    case 'active_free_yet_after_14days':
    case 'active_free_yet_after_21days':
    case 'active_free_yet_after_28days':
        $html_content = file_get_contents(config_item('locker_path') ."system_emails/onboarding/active_free_yet_after_120hrs.html");
        $txt_content = file_get_contents(config_item('locker_path') ."system_emails/onboarding/active_free_yet_after_120hrs.txt");		
		$subject = "Great Offer Inside!";		
		$arrSearchStr = array('[FIRSTNAME_OR_USERNAME]','xxxcopyright_yearxxx');
		$new_replace_arr	= array($replace_arr[0],date('Y'));
        break;
    case 'paid_more_than_1month':
        $html_content = file_get_contents(config_item('locker_path') ."system_emails/onboarding/paid_more_than_1month.html");
        $txt_content = file_get_contents(config_item('locker_path') ."system_emails/onboarding/paid_more_than_1month.txt");		
		$subject = "Tell us what you think?";		
		$arrSearchStr = array('[FIRSTNAME_OR_USERNAME]','xxxcopyright_yearxxx');
		$new_replace_arr	= array($replace_arr[0],date('Y'));
        break;
    case 'failed_cc':
        $html_content = file_get_contents(config_item('locker_path') ."system_emails/onboarding/failed_cc.html");
        $txt_content = file_get_contents(config_item('locker_path') ."system_emails/onboarding/failed_cc.txt");		
		$subject = "Payment failure!";		
		$arrSearchStr = array('[FIRSTNAME_OR_USERNAME]','xxxcopyright_yearxxx');
		$new_replace_arr	= array($replace_arr[0],date('Y'));
        break;
	case 'downgraded_more_than_1month':
        $html_content = file_get_contents(config_item('locker_path') ."system_emails/onboarding/downgraded_more_than_1month.html");
        $txt_content = file_get_contents(config_item('locker_path') ."system_emails/onboarding/downgraded_more_than_1month.txt");		
		$subject = "BoldInbox really misses you!";		
		$arrSearchStr = array('[FIRSTNAME_OR_USERNAME]','xxxcopyright_yearxxx');
		$new_replace_arr	= array($replace_arr[0],date('Y'));
        break;
    case 'downgraded_more_than_2month':
        $html_content = file_get_contents(config_item('locker_path') ."system_emails/onboarding/downgraded_more_than_2month.html");
        $txt_content = file_get_contents(config_item('locker_path') ."system_emails/onboarding/downgraded_more_than_2month.txt");		
		$subject = "BoldInbox really misses you!";		
		$arrSearchStr = array('[FIRSTNAME_OR_USERNAME]','xxxcopyright_yearxxx');
		$new_replace_arr	= array($replace_arr[0],date('Y'));
        break;		
}
	 
		$html_content	= getHeader().$html_content.getFooter();
		$to		= ($to_email !='')?$to_email:$replace_arr[1];

		$html_body	= str_replace($arrSearchStr, $new_replace_arr, $html_content);		
		$txt_body	= str_replace($arrSearchStr, $new_replace_arr, $txt_content);		
		$sender	= (trim($sender)!='')?$sender:SYSTEM_EMAIL_FROM;
		$sender_name	= (trim($sender_name)!='')?$sender_name:'BoldInbox';	
		
		if( $to !=''){			 
		echo "<br/>".$to;
		echo "<br/>".$subject;
		echo "<br/>".$html_body;
		echo "<br/>=============================<br/>";
			$to = ($testmode)?'pravinjha@gmail.com': $to;
			send_tmail($to, $sender, $sender_name, $subject, $html_body, $txt_body, $vmta);			
		} 

}
function send_tmail($to, $sender="",$sender_name="", $subject="",$message="",$text_message="", $vmta='boldinbox.com'){
	$vmta='boldinbox.com';
	require_once("phpmailer/class.phpmailer.php");
	$mail = new PHPMailer();	
	$mail->IsSMTP(); // set mailer to use SMTP
	$mail->SMTPDebug = 0;  // debugging: 1 = errors and messages, 2 = messages only
	$mail->SMTPAuth = false;  // authentication enabled
	
	$arrSmtpDetail = getArrSMTP('pmta2-pool-1');
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
	
	//$mail->AddCustomHeader('x-virtual-mta: '.$vmta);	 	
	//$mail->IsHTML(false);	
	//$mail->Body = $text_message;	
	$mail->AltBody = $text_message;
	$mail->MsgHTML($message);	
	$mail->AddAddress($to);	
	@$mail->Send();	
	$mail->SmtpClose();	  
}


function getHeader(){
return '
<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">	  
<head><title>BoldInbox Transactional Mail</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="SKYPE_TOOLBAR" content="SKYPE_TOOLBAR_PARSER_COMPATIBLE" /> 
<link href="http://www.boldinbox.com/locker/css/email_preview.css?v=6-20-13" rel="stylesheet"></link> 		      
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
</head>
	  
	<body class="body_style" style="background-color: #ffffff !important;">		   	 
	<table width="597" align = "center" cellspacing="0" cellpadding="0" class="container-div preheader" style="  border:none;clear:both; ">	 
	<tr>
		<td align="center" height = "40">&nbsp;</td>									  								
	</tr>	
	</table> 	
	<table cellspacing="0" cellpadding="0" width="605" style="border-width: thick; border-style: solid; border-collapse: separate; border-radius: 10px; overflow: hidden; font-family: Arial,Helvetica,sans-serif; border-color: #E10028 !important;" align="center">
		<tr>
			<td align="left">
				<div style = "margin: 15px 0px 0px 15px;"><img src="http://www.boldinbox.com/asset/user_files/4194/image_bank/20160103230627.png" alt="BoldInbox_Logo_Icon_Red" border="0"  height="45" width="200" /></div>
				<div style="text-align: left;margin-top:-5px;">
					<span style="margin-left:72px;font-size:15px;font-style:italic;">easy email marketing...</span>
				</div> 																			
				<hr style="width:547px;margin-top:10px;margin-bottom:5px;padding-left:3px;padding-right: 3px;padding-top:0px;padding-bottom:0px;color:#F5F5F5;" />
			</td>									  								
		</tr>
		<tr> 																		
			<td>
				<div style = "margin:5px 25px 5px 25px;line-height:1.5;font-size:13px;">
			  <!-- header ends -->
			  
			  ';


}

function getFooter(){
return '<!-- footer -->		  
					
				</div>
				<hr style="width:547px;margin-top:10px;margin-bottom:5px;padding-left:3px;padding-right: 3px;padding-top:0px;padding-bottom:0px;color:#F5F5F5;" />					
		</tr>
		<tr>
		<td align="left">
				<div align = "center" style = "border-top:solid 2px #3D9AFF;margin-bottom:10px;"></div>
				<div align = "center" style="text-align:center;border:solid 0px;width:150px;margin:0px auto;box-shadow:2px 3px 5px #888;font-family:calibri;"><a href="http://www.boldinbox.com/" style = "border:solid 0px;text-decoration:none;color:#111;font-size:12px;">Powered By<img src="http://www.boldinbox.com/locker/images/powered-by-logo-blue.png" alt="Powered By BoldInbox.Com" title="Powered By BoldInbox.Com" border="0" width = "150" /></a></div>
			</td>	
		</td>	
	</table>

	<div style="text-align: center;margin: 20px auto;">		
		<div style="line-height: 1.5; font-family:Arial,Helvetica,sans-serif;color:#333333;font-size:12px;padding-bottom:10px;">
		&copy; '.date('Y').' BoldInbox, All Rights Reserved</div>
		<a href="https://www.boldinbox.com/"> <img src="http://www.boldinbox.com/locker/images/powered-by-logo-blue.png" alt="logo" title="logo" border="0" /></a>
	</div>

</body>
</html>
		
';
}
?>