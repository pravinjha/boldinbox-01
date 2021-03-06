<?php
/**
* A Campaign_email_test class
** This class is for campaign email test
** @version 1.0* @author Pravin Jha <pravinjha@gmail.com>
* @project BoldInbox
*/
class Campaign_email_test extends CI_Controller{	
	function __construct(){		
		parent::__construct();		
		if($this->session->userdata('member_id')=='')redirect('user/index');
		$this->load->helper('phpmailer');
		$this->load->model('UserModel');
		$this->load->model('userboard/Autoresponder_Model');
		$this->load->model('userboard/Emailreport_Model');
		$this->load->model('userboard/Campaign_Model');
		$this->load->model('userboard/Campaign_Autoresponder_Model');
		$this->load->model('Activity_Model');
		$this->load->helper('notification');
	}	
	/**	
	*	Function index 	
	*	for campaign email test	
	*	@param integer $campaign_id  campaign id	
	*/	
	function index($campaign_id=0){		
		$sent_campaign=$this->Campaign_Model->get_campaign_data(array('campaign_id'=>$campaign_id,'campaign_created_by'=>$this->session->userdata('member_id'))); 		
		if(!count($sent_campaign)){			redirect('promotions');		}				
		if(isset($_POST['email_address'])){			
			$email_address=rtrim( $this->input->post('email_address'),",");	#remove last most comma from email address			
			$email_address_arr=explode(",",$email_address);	#expload email address with comma			
			$test_email=$sent_campaign[0]['test_email']+count($email_address_arr);	#count total number of test email			
			$this->test_email=$sent_campaign[0]['test_email'];			
			$this->form_validation->set_rules('email_address', 'Email Address ', 'required|valid_emails|callback_validate_max_email|trim');			
			// To check form is validated
			if($this->form_validation->run()==true){
			$this->Campaign_Model->update_campaign(array('test_email'=>$test_email,'email_subject'=>$_POST['email_subject']),array('campaign_id'=>$campaign_id)); 				
			$user=$this->UserModel->get_user_data(array('member_id'=>$this->session->userdata('member_id')));				
			$vmta = $user[0]['vmta'];								
			$is_ga=$_POST['is_ga'];				
			$is_ctrack=$_POST['is_ctrack'];				
			$sender_name=$_POST['email_from'];								
			$sender=$_POST['email_id']; 				
			$reply_to_email= (isset($_POST['reply_to_email']) && $_POST['reply_to_email'] != '')?$_POST['reply_to_email']:''; 				
			$subject=$_POST['email_subject'];				
			$preheader=$_POST['preheader'];				
			$is_ctrack = 'no'; // Stop click-tracking in test mail. Added on 18 May, 2015;				
			if($is_ctrack == 'no' && $sent_campaign[0]['campaign_template_option'] ==3)$sent_campaign[0]['campaign_after_encode_url']=$sent_campaign[0]['campaign_content'];				
			$message_cnt=$this->Campaign_Autoresponder_Model->attach_campaign_link($sent_campaign[0],$user, $preheader='');				
//echo 'Pravinjha@gmail.com='.$message_cnt;exit;	
			if($is_ga){					
				$message_cnt = $this->attachGAnalytics($campaign_id, $message_cnt, $subject);				
			}				 				
			$campaign_footer_text_only = $this->Campaign_Autoresponder_Model->campaign_footer_text_only($user, $sent_campaign[0]['campaign_id'], false, true);				
			#send test  email one by one to each email address				
			foreach($email_address_arr as $to){					
				$message=$message_cnt;								#collect text content of email					
				$campaign_footer_text_only = str_replace("[CONTACT_EMAIL_ID]",$to,$campaign_footer_text_only);					
				$text_message=$sent_campaign[0]['campaign_text_content'].$campaign_footer_text_only;					
				if(($sent_campaign[0]['campaign_template_option']!=3)&&($sent_campaign[0]['campaign_template_option']!=5)){						
				#Remove unneccessary characters from campaign contetn						
				$message=utf8_decode($this->is_authorized->webCompatibleString($message));						 					
				}					
				$subscriber_info = array('subscriber_id'=>0,'subscriber_email_address'=>$to,'subscriber_first_name'=>'','subscriber_last_name'=>'','subscriber_state'=>'',
										'subscriber_zip_code'=>'','subscriber_country'=>'','subscriber_city'=>'','subscriber_company'=>'','subscriber_dob'=>'','subscriber_phone'=>'',
										'subscriber_address'=>'','subscriber_extra_fields'=>'');								
				$email_personalization = true;					
				$is_autoresponder = false;					
				$this->Campaign_Autoresponder_Model->getPersonalization($message,$text_message,$subject,$subscriber_info, $is_autoresponder, $campaign_id,$vmta, $email_personalization);					 					
				$receiver[]=$to;					
				$campaign_type = 	($sent_campaign[0]['campaign_template_option'] != 5) ? 'html' : 'text' ;										
				
				// send email					
				send_email($to,$sender,$sender_name, '(TEST) '.$subject,$message,$text_message,$bouncemailreply,$campaign_id, $campaign_type, false, array(),$vmta, $reply_to_email);							
			}
			$email_addresses=implode(',',$receiver);					
			// create array for insert values in activty table					
			$this->Activity_Model->create_activity(array('user_id'=>$this->session->userdata('member_id'),  'activity'=>'campaign_tested', 'campaign_id'=>$campaign_id,	'email_addresses'=>$email_addresses));					
			// Send RC-Alert					
			create_notification("testmail_sent",array($this->session->userdata('member_username'),$email_addresses));											
			//print success message										
			//echo "Success: Test-mail sent for CID-[$campaign_id]:".$test_email;			
			echo "Success: Test-mail sent:".$test_email;			
			}			
			// Display Validation Errors						
			if(validation_errors()){				
				echo 'error:'.validation_errors().":".$test_email;			
			}		
		}	
	}
			
	function attachGAnalytics($cid, $mail_html, $subject){	
		$campaign_array=$this->Campaign_Model->get_campaign_data(array('campaign_id'=>$cid));	
		$mid = $campaign_array[0]['campaign_created_by'];	
		$subject = urlencode($subject);		
		$doc = new DOMDocument();	
		$doc->recover = true;	
		$doc->strictErrorChecking = false;		
		libxml_use_internal_errors(true);	
		$mail_html = mb_convert_encoding($mail_html, 'HTML-ENTITIES', "UTF-8");	
		//$encoding = mb_detect_encoding( $mail_html, "auto" ).'==xxxxxxxxxxxxxxx';	
		//string mb_convert_encoding ( string $str , string $to_encoding [, mixed $from_encoding ] )		
		// or $doc->loadHTML('<?xml encoding="UTF-8">' . $html);	
		// or $dom = new DomDocument('1.0', 'UTF-8');	
		
		@$doc->loadHTML($mail_html);	
		libxml_clear_errors();
		foreach($doc->getElementsByTagName('a') as $link){		
			$url = $link->getAttribute('href');		
			$host = parse_url($url, PHP_URL_HOST);		 		
			if($this->db->query("select * from red_ga_domains where `member_id`='$mid' and `domain_name`='$host'")->num_rows() > 0){
				if (strpos($url, '?') === false) {	
					$url .= '?utm_source=test_campaign&utm_medium=email&utm_campaign='.$subject;			
				}else{				
					$url .= '&utm_source=test_campaign&utm_medium=email&utm_campaign='.$subject;			
				}		
			}				
			$link->setAttribute('href',$url);	 
		}	 
		$mail_html = $doc->saveHTML();	 
		return $mail_html; 
	}	
	/**
	*	Function autoresponder 	
	*	for autoresponder email test	
	*	
	*	@param integer $campaign_id  autoresponder id	
	**/	
	
	function autoresponder($campaign_id=0){ 		
		$sent_campaign=$this->Autoresponder_Model->get_autoresponder_data(array('campaign_id'=>$campaign_id,'campaign_created_by'=>$this->session->userdata('member_id')));		
		// Redirects user to listing page if user have not created this campaign or campaign does not exists		
		if(!count($sent_campaign)){			redirect('promotions');		}		
		#Check email address exist or not		
		if(isset($_POST['email_address'])){			
		$email_address=rtrim( $this->input->post('email_address'),","); // remove last most comma from email address			
		$email_address_arr=explode(",",$email_address);		// expload email address with comma			
		$test_email=$sent_campaign[0]['test_email']+count($email_address_arr);	// count total number of test email			
		$this->test_email=$sent_campaign[0]['test_email'];			
		$this->form_validation->set_rules('email_address', 'Email Address ', 'required|valid_emails|callback_validate_max_email|trim');	// To check form is validated			
		if($this->form_validation->run()==true){				
			$this->Autoresponder_Model->update_autoresponder(array('test_email'=>$test_email,'email_subject'=>$_POST['email_subject']),array('campaign_id'=>$campaign_id));
			$user=$this->UserModel->get_user_data(array('member_id'=>$this->session->userdata('member_id')));				
			$vmta = $user[0]['vmta'];					
			#set sender of email				
			$user_name	= ($user[0]['company']!="")? $user[0]['company'] :  $user[0]['member_username'];				
			$sender_name= $_POST['email_from'];				
			$sender		= $_POST['email_id'];				
			$subject	= $_POST['email_subject'];				
			$is_ga		= $_POST['is_ga'];				
			$is_ctrack	= $_POST['is_ctrack'];				
			$is_ctrack = 'no'; // Stop click-tracking in test mail. Added on 18 May, 2015;				
			if($is_ctrack == 'no' && $sent_campaign[0]['campaign_template_option'] ==3)$sent_campaign[0]['campaign_after_encode_url']=$sent_campaign[0]['campaign_content'];	
			// add links:emailtrack_img,footer,unsubscribe,forward links with campaign				
			$message_cnt=$this->Campaign_Autoresponder_Model->attach_campaign_link($sent_campaign[0],$user, true);				
			if($is_ga){					
				$message_cnt = $this->attachGAnalytics($campaign_id, $message_cnt, $subject);				
			}
			$campaign_footer_text_only = $this->Campaign_Autoresponder_Model->campaign_footer_text_only($user, $sent_campaign[0]['campaign_id'], true, true);
			// send test  email one by one to each email address				
			foreach($email_address_arr as $to){										
				$message		= $message_cnt;					
				$campaign_footer_text_only = str_replace("[CONTACT_EMAIL_ID]",$to,$campaign_footer_text_only);					
				$text_message = $sent_campaign[0]['campaign_text_content'].$campaign_footer_text_only;					 								
				/* $message=utf8_decode($this->is_authorized->webCompatibleString($message)); */					
				// Fetch Email personalize string in array from database 					
				$subscriber_info = array('subscriber_id'=>0,'schedule_id'=>0,'subscriber_email_address'=>$to,'subscriber_first_name'=>'','subscriber_last_name'=>'',
				'subscriber_state'=>'','subscriber_zip_code'=>'','subscriber_country'=>'','subscriber_city'=>'','subscriber_company'=>'','subscriber_dob'=>'',
				'subscriber_phone'=>'','subscriber_address'=>'','subscriber_extra_fields'=>'');								
				$email_personalization = true;					
				$is_autoresponder = true;					
				$this->Campaign_Autoresponder_Model->getPersonalization($message,$text_message,$subject,$subscriber_info, $is_autoresponder, $campaign_id,$vmta, $email_personalization); 					
				$receiver[]=$to;					
				$campaign_type = 	($sent_campaign[0]['campaign_template_option'] != 5) ? 'html' : 'text' ;					
				#send email										
				send_email($to,$sender,$sender_name,'(TEST) '.$subject,$message,$text_message,$bouncemailreply,$campaign_id,$campaign_type, false, array(),$vmta);				
			}				
			// print success message				
			echo "Success: Test-mail sent for AID-[$campaign_id]:".$test_email;			
		}			
		// Display Validation Errors						
		if(validation_errors()){				
			echo 'error:'.validation_errors().":".$test_email;			
		}		
		}		
	}	
	/**		Function validate_max_email to validate email addresses of test email	*/	
	function validate_max_email(){		
		$last_character=substr($this->input->post('email_address'), -1);		
		if($last_character==","){			
			$email_address=$this->input->post('email_address');			
			$email_address=rtrim( $email_address,",");		
		}else{			
			$email_address=$this->input->post('email_address');		
		}		
		$email_arr=explode(",",$email_address);		
		foreach($email_arr as $email){			
			if($email==""){				
				$this->form_validation->set_message('validate_max_email', 'The Email Address field must contain all valid email addresses.');				
				return false;			
			}		
		}		
		if((count($email_arr)+$this->test_email)>25){			
			$this->form_validation->set_message('validate_max_email', 'You have reached the maximum allowed tests');			
			return false;		
		}		
		if(count($email_arr)>5){
			$this->form_validation->set_message('validate_max_email', 'The max limit of %s field can not be more than five');				
			return false;		
		}else{
			return true;		
		}	
	} 
}
?>