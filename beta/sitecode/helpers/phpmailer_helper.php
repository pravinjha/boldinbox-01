<?php
/**
*	Plugin send campaign email for Admin notification mail
*	for test email from campaign and autoresponders
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
// for test email from campaign and autoresponders
// redrotate3
function send_email($recipient, $sender,$sender_name, $subject, $message,$text_message,$bouncemail=0,$campaign_id=0, $campaign_type = 'html',$notification=false,$subscriber_replace_arr=array(), $vmta='rcmailsv.com', $reply_to_email=''){
    $CI =& get_instance();
	$CI->load->model('is_authorized');
    $sess_user = $CI->session->userdata('member_id');
    if (!isset($sess_user)) exit;

	$email_personalize_arr=array();
	$search_email_personalize=get_email_personalize_data($email_personalize_arr);
	$arrPersonalizeReplace=get_fallback_value($message,$text_message,$email_personalize_arr);	# Replace fallback value
	$subscriber_key_arr=array('subscriber_first_name','subscriber_last_name','subscriber_email_address','subscriber_state','subscriber_zip_code','subscriber_country','subscriber_city','subscriber_company','subscriber_dob','subscriber_phone','subscriber_address');
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
	
	
    $mail->FromName = $sender_name;
    $mail->From = $sender;	
	if(trim($reply_to_email) != ''){
		$mail->AddReplyTo($reply_to_email, $sender_name);
	}
    
	// Send email to recivier by array
	if(is_array($recipient)){
		foreach($recipient as $key=>$v){
			unset($recipient_info);
			// explode recipient info
			$recipient_info=explode("||",$v);
			$encodedSubscriber = $CI->is_authorized->encodeSubscriber($key,$recipient_info[0]);
			
			$mail->ClearAddresses();
			$mail->ClearAttachments();
			$mail->ClearCustomHeaders();
			$mail->IsHTML(false);
			$expl_arr=array();
			
			$mail->AddCustomHeader('x-envid:'.$campaign_id);			
			$mail->AddCustomHeader('x-fblid:'.$key.'-'.$campaign_id.'-'.CAMPAIGN_HEADER_SUFFIX);				
			$mail->AddCustomHeader('x-job:'.$key);	
			//$mail->AddCustomHeader('x-virtual-mta: '.$vmta);	 			
			$mail->AddCustomHeader('List-Unsubscribe: <mailto:unsubscribe@boldinbox.com?subject=Unsubscribe '.$encodedSubscriber.'>, <http://www.'.SYSTEM_DOMAIN_NAME.'/cprocess/unsubscribe/'.$campaign_id.'/'.$encodedSubscriber.'>');
			
			$mail->AddAddress($recipient_info[0],$recipient_info[1]);// recipient email address
			$arrSearchStr = array('[subscriber_id]','[CONTACT_EMAIL_ID]');
			
			$arrReplaceBy =array($encodedSubscriber,$recipient_info[0]);
			$expl_arr=explode("||",$subscriber_replace_arr[$key]);
			$expl_arr = array_combine($subscriber_key_arr, $expl_arr);
			$expl_arr = array_filter($expl_arr);
			if(is_array($expl_arr)){
				$replace_email_personalize = array_merge($arrPersonalizeReplace,$expl_arr);
			}else{
				$replace_email_personalize=$arrPersonalizeReplace;
			}
			// Email Personalization on campaign content
			$message_cnt=str_replace($search_email_personalize, $replace_email_personalize, $message);
			$message_cnt=str_replace($arrSearchStr, $arrReplaceBy, $message_cnt);
			// Email Personalization on campaign text content
			$text_message_content=str_replace($search_email_personalize, $replace_email_personalize, $text_message);
			$text_message_content=str_replace($arrSearchStr, $arrReplaceBy, $text_message_content);
		
			// Email Personalization on campaign subject
			$campaign_subject=str_replace($search_email_personalize, $replace_email_personalize, $subject);
			$campaign_subject=str_replace($arrSearchStr, $arrReplaceBy, $campaign_subject);
			
			$body = $message_cnt;
			
			// the HTML to the plain text. Store it into the variable. 
			$text = $text_message_content;
			$mail->Subject = $campaign_subject;
			if($campaign_type == 'html'){
				$mail->AltBody = $text;
				$mail->MsgHTML($body);
			}else{
				$mail->Body = $text;
			}
			$mail->Send();
		}
	}else{
		$message_cnt=$message;
		$body = $message_cnt;
		$emailz = explode(",",$recipient);
		$mailCt = count($emailz);
	
		$mail->AddCustomHeader('x-envid:'.$campaign_id);
		$mail->AddCustomHeader('x-fblid: testmail-'.$campaign_id.'-'.CAMPAIGN_HEADER_SUFFIX);		
		$mail->AddCustomHeader('x-job: testmail');			
		for($i=0; $i<$mailCt; $i++){
			$tu= $emailz[$i];
			$mail->AddAddress($tu);
		}
		// the HTML to the plain text. Store it into the variable. 
		$mail->Subject = $subject;
		//$mail->AddCustomHeader('x-virtual-mta: '.$vmta);	 
		if($campaign_type == 'html'){
			$mail->AltBody = $text_message;
			$mail->MsgHTML($body);
		}else{
			$mail->Body = $text_message;
		}
		
		$mail->Send();
	}  
$mail->SmtpClose();	
	//mail end
}



function forward_friend_send_email($recipient, $sender,$sender_name, $subject, $message,$text_message,$bouncemail=0,$campaign_id=0,$notification=false,$subscriber_replace_arr=array())
{

	$email_personalize_arr=array();
	$search_email_personalize=get_email_personalize_data($email_personalize_arr);
	$arrPersonalizeReplace=get_fallback_value($message,$text_message,$email_personalize_arr);	# Replace fallback value
	$subscriber_key_arr=array('subscriber_first_name','subscriber_last_name','subscriber_email_address','subscriber_state','subscriber_zip_code','subscriber_country','subscriber_city','subscriber_company','subscriber_dob','subscriber_phone','subscriber_address');
    require_once("phpmailer/class.phpmailer.php");

    $mail = new PHPMailer();
	
	//SMTP begin
    $mail->IsSMTP();// set mailer to use SMTP
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
	
    $mail->FromName = $sender_name;
    $mail->From = $sender;	
	
	// Send email to recivier by array
	
	
	
		$message_cnt=$message;
		$body = $message_cnt;
		$emailz = explode(",",$recipient);
		$mailCt = count($emailz);
				
		$mail->AddCustomHeader('x-envid:'.$campaign_id);
		$mail->AddCustomHeader('x-fblid: friend-'.$campaign_id.'-'.CAMPAIGN_HEADER_SUFFIX);			
		$mail->AddCustomHeader('x-job: friend');			
		
		for($i=0; $i<$mailCt; $i++){
			if('sebsauge@gmail.com' != $emailz[$i] and 'pennysubscriptions@gmail.com' != $emailz[$i])			
			$mail->AddAddress($emailz[$i]);
		}
		// the HTML to the plain text. Store it into the variable. 
		$mail->Subject = $subject;
		//$mail->AddCustomHeader('x-virtual-mta: rcmailer8');	 
		$text = $text_message;
		$mail->AltBody = $text;
		$mail->MsgHTML($body);
		@$mail->Send();	
		$mail->SmtpClose();
}




function get_email_personalize_data(&$email_personalize_arr=array())
{
	$CI             =& get_instance();
	$sql            = 'SELECT name,value,default_value FROM `red_email_personalization`';
    $query          = $CI->db->query($sql);
	$email_personalize=array();
	if ($query->num_rows() >0)
    {
		$result_array=$query->result_array();	#Fetch resut
		foreach($result_array as $row){
			$email_personalize[]        = $row['value'];
			$email_personalize_arr[$row['name']]        = $row['default_value'];
		}
	}
	return $email_personalize;
}
/**
  *	Funcion get_fallback_value to replace fallback value
  *	@param (string) (campaign_content)  contains campaign email
  *	@param (string) (text_message)  contains text of campaign email
  *	@param (int) (subscriber_id)  contains subscriber id
**/
function get_fallback_value(&$campaign_content="",&$text_message="",$arrPersonalizeReplace=array()){
	$string		=		$campaign_content;
	$CI         =		& get_instance();
	
	//$pattren="/\{([a-zA-Z0-9_-])*,([a-zA-Z0-9_-])*\}/";
	$pattren="/\{([a-zA-Z0-9_-])*,([^\/])*\}/";
	preg_match_all($pattren,$string,$regs);
	foreach($regs[0] as $value){
		$fallback_value=$value;
		$value=trim($value,'}');
		$expl_value=explode(",",$value,2);
		$sql            = 'SELECT name,value FROM `red_email_personalization` where value like \'%'.$expl_value[0].'%\'';
		$query          = $CI->db->query($sql);
		
		if ($query->num_rows() >0){
			$result_array=$query->result_array();	#Fetch resut
			foreach($result_array as $row){
				#Create an array of the required personalisation token and default value from CAMPAIGN
				$arrPersonalizeReplace[$row['name']] = $expl_value[1];
				$fallback_search_arr[]=$fallback_value;
				$fallback_replace_arr[]=$row['value'];
			}
		}
	}
	
	//$campaign_content=str_replace($fallback_search_arr, $fallback_replace_arr, $string);
	//$text_message=str_replace($fallback_search_arr, $fallback_replace_arr, $text_message);
	return $arrPersonalizeReplace;
}
?>