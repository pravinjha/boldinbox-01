<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
* Admin Notification send mail helper
*/


function do_send_email($recipient, $sender,$sender_name, $subject, $message,$text_message,$replyTo){
require_once("phpmailer/class.phpmailer.php");		
		$CI =& get_instance();
		$arrSmtpHostArray = array(
						//array('mail1.bibesp.com', 'smtp1','vR265bibesp@2019+1', '26', 'bounce@bibesp.com','bibesp.com'), 
						//array('mail1.mailsoni.com', 'smtp2','vR265bibesp@2019+2', '26', 'bounce@mailsoni.com','mailsoni.com'),
						//array('mail6.chillmailer.com', 'smtp1','vR3summit@2019+1', '26', 'bounce@chillmailer.com','chillmailer.com'),						
						
						array('mail.chillmailer.com', 'smtp1','vR2summit@2019+1', '26', 'bounce@chillmailer.com','chillmailer.com'),						
						array('mail.mailposh.com', 'smtp2','vR2summit@2019+2', '26', 'bounce@mailposh.com','mailposh.com')
						);
		
		$thisSmtpHostArray  = mt_rand(0, count($arrSmtpHostArray)-1);
			
		$mail = new PHPMailer(); 
		
		//SMTP begin
		$mail->IsSMTP();// set mailer to use SMTP
		$mail->SMTPDebug = 0;  // debugging: 1 = errors and messages, 2 = messages only
		$mail->SMTPAuth = true;  // authentication enabled
		$arrFinalSMTP = $arrSmtpHostArray[$thisSmtpHostArray];	
			
		$mail->Host = $arrFinalSMTP[0];
		$mail->Hostname = $arrFinalSMTP[0];
		$mail->Username = $arrFinalSMTP[1];
		$mail->Password = $arrFinalSMTP[2];
		$mail->Port = $arrFinalSMTP[3];
		$mail->AddCustomHeader('Sender: '.$arrFinalSMTP[4]);	
		$mail->Sender	= $arrFinalSMTP[4];				
		for($i=0; $i< count($recipient); $i++){
			if($i==0){
				$mail->AddAddress($recipient[$i]);
				// $CI->db->insert('video_mail_log', array('email_to'=>$recipient[$i],'email_subject'=>$subject,'email_body'=>$message, 'hostname' => $arrFinalSMTP[0]));					
			}else{
				if($_SERVER['HTTP_HOST'] == '52.88.155.175'  && $recipient[$i] == 'info@standupglobal.com'){
				// DO NOTHING
					// $mail->AddBCC($recipient[$i]);		
					// $CI->db->insert('video_mail_log', array('email_to'=>$recipient[$i],'email_subject'=>$subject,'email_body'=>$message, 'hostname' => $arrFinalSMTP[0]));
				}elseif($subject != 'A new video posted - The Comics Gym'){
					$mail->AddBCC($recipient[$i]);		
					// $CI->db->insert('video_mail_log', array('email_to'=>$recipient[$i],'email_subject'=>$subject,'email_body'=>$message, 'hostname' => $arrFinalSMTP[0]));	
				}				
			}				
		}	

		

		
		$mail->FromName = $sender_name;		
		$mail->From = $sender;			
		$mail->AddReplyTo($replyTo);
		$mail->AddCustomHeader('x-mailer: TheComicsGym');	
		$mail->AddCustomHeader('x-purpose: notification');		
		
		$mail->Subject = $subject;	 				
		$mail->AltBody = $text_message;
		$mail->MsgHTML($message);
		@$mail->Send();
	   $mail->SmtpClose();	   
	
}
?>