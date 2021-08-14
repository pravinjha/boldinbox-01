<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
* Admin Notification send mail helper
*/


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

function admin_notification_send_email($recipient, $sender,$sender_name, $subject, $message,$text_message){
require_once("phpmailer/class.phpmailer.php");		
		$CI =& get_instance();
		$sess_user = $CI->session->userdata('member_id');
		if (!isset($sess_user)) exit;	
		
		$mail = new PHPMailer(); 
		
		//SMTP begin
		$mail->IsSMTP();// set mailer to use SMTP
		$mail->SMTPDebug = 0;  // debugging: 1 = errors and messages, 2 = messages only
		$mail->SMTPAuth = true;  // authentication enabled
		//$arrSmtpDetail = getArrSMTP('pmta2-pool-2');		
		$arrSmtpDetail = getArrSMTP('pmta-pool-1');		
		$mail->Host = $arrSmtpDetail[0];
		$mail->Hostname = $arrSmtpDetail[0];
		$mail->Username = $arrSmtpDetail[1];
		$mail->Password = $arrSmtpDetail[2];
		$mail->Port = $arrSmtpDetail[3];
		$mail->AddCustomHeader('Sender: '.$arrSmtpDetail[4]);	
		$mail->Sender	= $arrSmtpDetail[4];

		
		$emailz = explode(",",$recipient);
			$mailCt = count($emailz);
				
			for($i=0; $i<$mailCt; $i++){			
				$mail->AddAddress($emailz[$i]);
			}
		$mail->FromName = $sender_name;
		$mail->From = $sender;		
		
		$mail->AddCustomHeader('x-envid: notification');
		$mail->AddCustomHeader('x-fblid: admin-notification');			
		$mail->AddCustomHeader('x-job: notification');			
		
		
			// the HTML to the plain text. Store it into the variable. 
		$mail->Subject = $subject;
		//$mail->AddCustomHeader('x-virtual-mta: rcmailer8');	 				
		$mail->AltBody = $text_message;
		$mail->MsgHTML($message);
		@$mail->Send();
	   $mail->SmtpClose();
	//mail end
}
?>