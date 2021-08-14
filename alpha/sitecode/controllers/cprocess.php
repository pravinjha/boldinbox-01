<?php
/**
* A cprocess class
*
* This class is for unsubscriber mail
*
* @version 1.0
* @author Pravin Jha <pravinjha@gmail.com>
* @project BoldInbox
*/
class Cprocess extends CI_Controller
{
	/**
	*	Contructor for controller.
	*	It checks user session and redirects user if not logged in
	*/
	function __construct(){
        parent::__construct();

		$this->load->model('userboard/Campaign_Model');
		$this->load->model('UserModel');					
		$this->load->model('userboard/Subscriber_Model');		
		$this->load->model('userboard/Emailreport_Model');
		$this->load->library('encrypt');
		$this->load->helper('admin_notification');	
    }
	/**
	*	Function Abuse(Report-Abuse) to Mark subscriber as unsubscribe with feedback=Abuse
	*	@param int id contain campaign id
	*	@param int subscriber_id contain subscriber id
	*/	
	function abuse($id=''){		 	
			$id = str_replace('.html','',$id);
			$utc_str = gmdate("Y-m-d H:i:s", time());	
			$arrSubscriber =  explode(':-:',$this->is_authorized->encryptor('decrypt', $id));
			list($cid, $subscriber_id,$subscriber_email) = $arrSubscriber;	
			$rc_logo = 1;
			$mIP = $this->is_authorized->getRealIpAddr();
			$this->db->query("replace into `red_global_fbl` set `email_address`='$subscriber_email', `campaign_id`='$cid'"); 
		// Check subscriber id should not be empty
		if(intval($subscriber_id) > 0 && intval($cid) > 0){		 
			$rsCampaign = $this->db->query("select DATE_FORMAT(email_send_date, '%Y-%m-%d')email_send_date, pipeline from red_email_campaigns where campaign_id='$cid'" );
			$vmta = $rsCampaign->row()->pipeline;
			$email_send_date = $rsCampaign->row()->email_send_date;
			$rsCampaign->free_result();
			
			$email_report_unsubscribe=$this->Emailreport_Model->get_emailreport_data(array('campaign_id'=>$cid, 'subscriber_id'=>$subscriber_id));		

			if(count($email_report_unsubscribe) > 0){
				$subscriber_created_by=$email_report_unsubscribe[0]['user_id'];					
				// Unsubscribe contact in subscriber table
				$this->Subscriber_Model->update_subscriber(array('subscriber_status'=>2, 'last_unsubscribed_ip'=>$mIP, 'status_change_date'=>$utc_str, 'complaint'=>1,'last_complaint_date'=>$utc_str),array('subscriber_id'=>$subscriber_id));
				
				// Mark email as read				
				if($email_report_unsubscribe[0]['email_track_read'] < 1){
					$input_array=array('email_track_complaint'=>1,'email_track_read'=>1,'email_track_read_date'=>$utc_str,'complaint_date'=>$utc_str);
					$this->db->query("update `red_email_campaigns_scheduled` set `email_track_read` = `email_track_read`+1 where campaign_id='$cid'");
					$this->db->query("update red_email_subscribers set `read` = `read`+1, `last_read_ip`='$mIP', `last_read_date`=current_timestamp() where subscriber_id='$subscriber_id'");
					// Increment OPENED global_ipr_daily for major webmails
					$arrEml = @explode('@',$subscriber_email);
					$emlDomain = $arrEml[1];
					$IPR_Domain = (in_array($emlDomain,config_item('major_domains')))? $emlDomain : 'all' ;	
					$this->db->query("insert into red_global_ipr_daily set `mail_domain` = '$IPR_Domain' ,  `log_date`='$email_send_date' ,  `pipeline`='$vmta', `user_id`='$subscriber_created_by', total_opened=(total_opened + 1) ON DUPLICATE  KEY UPDATE  total_opened=(total_opened + 1) ");					
				}else{
					$input_array=array('email_track_complaint'=>1,'complaint_date'=>$utc_str);
				}
				
				// Mark unsubscribe email in email report tables
				if($email_report_unsubscribe[0]['email_track_complaint']<=0){
					// update unsubscribe mail for subscription list in email report table
					$this->Emailreport_Model->update_emailreport($input_array,array('campaign_id'=>$cid,'subscriber_id'=>$subscriber_id));
					
					// Update unsubscribe counter for campaign
					$this->db->query("update `red_email_campaigns_scheduled` set `email_track_spam` = `email_track_spam`+1 where campaign_id='$cid'");		 		
					// update subscriber as unsubscribe in subscriber table
				$this->Subscriber_Model->update_subscriber(array('subscriber_status'=>2, 'last_unsubscribed_ip'=>$mIP, 'status_change_date'=>$utc_str, 'complaint'=>1,'last_complaint_date'=>$utc_str),array('subscriber_id'=>$subscriber_id));
					// Increment UNSUBSCRIBE global_ipr_daily for major webmails
					$arrEml = @explode('@',$subscriber_email);
					$emlDomain = $arrEml[1];
					$IPR_Domain = (in_array($emlDomain,config_item('major_domains')))? $emlDomain : 'all' ;						
					$this->db->query("insert into red_global_ipr_daily set `mail_domain` = '$IPR_Domain' ,  `log_date`='$email_send_date' ,  `pipeline`='$vmta', `user_id`='$subscriber_created_by', total_complaint=(total_complaint + 1) ON DUPLICATE  KEY UPDATE  total_complaint=(total_complaint + 1) ");			
				}
				
				// update subscriber as unsubscribe in autoresponder_signup table
				$this->Emailreport_Model->update_autoresponder_emailreport(array('email_track_complaint'=>1),array('email_track_subscriber_id'=>$subscriber_id));
				
				$user=$this->UserModel->get_user_data(array('member_id'=>$subscriber_created_by));
				$rc_logo = $user[0]['rc_logo'];
			}
		}
		
		$this->load->view('promotions/abuse_msg',array('msg'=>'<h3>Your complaint registered successfully.</h3>','rc_logo'=>$rc_logo));	
	}	
		
	/**
	*	Function unsubscribe to Mark subscriber as unsubscribe
	*	@param int id contain campaign id
	*	@param int subscriber_id contain subscriber id
	*/
		
	
	function unsubscribe($enc_cid=0,$subscriber_id=0){
		$id =  $this->is_authorized->encryptor('decrypt', $enc_cid);
		$subscriber_id = str_replace('.html','',$subscriber_id);
		$arrSubscriber = $this->is_authorized->decodeSubscriber($subscriber_id);
		list($subscriber_id,$subscriber_email,$cid) = $arrSubscriber;	
		$subscriber_email = $this->is_authorized->webCompatibleString($subscriber_email);
		$campaign_array=$this->Campaign_Model->get_campaign_data(array('campaign_id'=>$id));
		$user_id=$campaign_array[0]['campaign_created_by'];	
		$mIP = $this->is_authorized->getRealIpAddr();
		
		
		// Check subscriber id should not be empty
		if($subscriber_id==0){					
			$user=$this->UserModel->get_user_data(array('member_id'=>$user_id));			
			redirect('cprocess/unsubscirbe_msg/'.$user[0]['rc_logo']);
		}else{		
			$this->Subscriber_Model->update_subscriber(array('subscriber_status'=>0, 'last_unsubscribed_ip'=>$mIP, 'status_change_date'=>date('Y-m-d H:i:s',now()), 'unsubscribed'=>1,'last_unsubscribed_date'=>date('Y-m-d H:i:s',now())),array('subscriber_id'=>$subscriber_id, 'subscriber_email_address'=>$subscriber_email));
			
			// Update email report						
			$email_report_unsubscribe=$this->Emailreport_Model->get_emailreport_data(array('campaign_id'=>$id, 'subscriber_id'=>$subscriber_id, 'subscriber_email_address'=>$subscriber_email));			
			if(intval($id) > 0){
				$rsCampaign = $this->db->query("select DATE_FORMAT(email_send_date, '%Y-%m-%d')email_send_date, pipeline from red_email_campaigns where campaign_id='$id'" );
				$vmta = $rsCampaign->row()->pipeline;
				$email_send_date = $rsCampaign->row()->email_send_date;
				$rsCampaign->free_result();
			}			
			if(count($email_report_unsubscribe) > 0){
			
				$email_id=$email_report_unsubscribe[0]['subscriber_email_address'];	 
				$subscriber_created_by=$email_report_unsubscribe[0]['user_id'];				 
				// Unsubscribe contact in subscriber table
				$this->Subscriber_Model->update_subscriber(array('subscriber_status'=>0, 'last_unsubscribed_ip'=>$mIP, 'status_change_date'=>date('Y-m-d H:i:s',now()), 'unsubscribed'=>1,'last_unsubscribed_date'=>date('Y-m-d H:i:s',now())),array('subscriber_id'=>$subscriber_id));
				// Delete unsubscriber subscriber from email queue table
				$this->Emailreport_Model->delete_emailqueue(array('subscriber_id'=>$subscriber_id));
			
			
				// Mark email as read				
				if($email_report_unsubscribe[0]['email_track_read']<=0){
					$input_array=array('email_track_unsubscribes'=>1,'email_track_read'=>1,'email_track_read_date'=>date('Y-m-d H:i:s',now()),'date_unsubscribe'=>date('Y-m-d H:i:s',now()));
					$this->db->query("update `red_email_campaigns_scheduled` set `email_track_read` = `email_track_read`+1 where campaign_id='$id'");
					$this->db->query("update red_email_subscribers set `read` = `read`+1,`last_read_ip`='$mIP', `last_read_date`=current_timestamp() where subscriber_id='$subscriber_id'");
					// Increment OPENED global_ipr_daily for major webmails
					$arrEml = @explode('@',$email_id);
					$emlDomain = $arrEml[1];
					$IPR_Domain = (in_array($emlDomain,config_item('major_domains')))? $emlDomain : 'all' ;					
					$this->db->query("insert into red_global_ipr_daily set `mail_domain` = '$IPR_Domain' ,  `log_date`='$email_send_date' ,  `pipeline`='$vmta', `user_id`='$subscriber_created_by', total_opened=(total_opened + 1) ON DUPLICATE  KEY UPDATE  total_opened=(total_opened + 1) ");			
				}else{
					$input_array=array('email_track_unsubscribes'=>1,'date_unsubscribe'=>date('Y-m-d H:i:s',now()));
				}
				
				// Mark unsubscribe email in email report tables
				if($email_report_unsubscribe[0]['email_track_unsubscribes']<=0){
					// update unsubscribe mail for subscription list in email report table
					$this->Emailreport_Model->update_emailreport($input_array,array('campaign_id'=>$id,'subscriber_id'=>$subscriber_id));
					
					// Update unsubscribe counter for campaign
					$this->db->query("update `red_email_campaigns_scheduled` set `email_track_unsubscribes` = `email_track_unsubscribes`+1 where campaign_id='$id'");		 		
					// update subscriber as unsubscribe in subscriber table
				$this->Subscriber_Model->update_subscriber(array('subscriber_status'=>0, 'last_unsubscribed_ip'=>$mIP,'status_change_date'=>date('Y-m-d H:i:s',now()), 'unsubscribed'=>1,'last_unsubscribed_date'=>date('Y-m-d H:i:s',now())),array('subscriber_id'=>$subscriber_id));
					// Increment UNSUBSCRIBE global_ipr_daily for major webmails
					$arrEml = @explode('@',$email_id);
					$emlDomain = $arrEml[1];
					$IPR_Domain = (in_array($emlDomain,config_item('major_domains')))? $emlDomain : 'all' ;							
					$this->db->query("insert into red_global_ipr_daily set `mail_domain` = '$IPR_Domain' ,  `log_date`='$email_send_date' ,  `pipeline`='$vmta', `user_id`='$subscriber_created_by', total_unsubscribed=(total_unsubscribed + 1) ON DUPLICATE  KEY UPDATE  total_unsubscribed=(total_unsubscribed + 1) ");			
				}
				// update subscriber as unsubscribe in autoresponder_signup table
				$this->Emailreport_Model->update_autoresponder_emailreport(array('email_track_unsubscribes'=>1),array('email_track_subscriber_id'=>$subscriber_id));
				//Fetch user info
				$user=$this->UserModel->get_user_data(array('member_id'=>$subscriber_created_by));			
				//redirect to thanks msg
				$this->unsubscribed_msg($user[0]['rc_logo'],$this->is_authorized->encryptor('encrypt', $subscriber_id .'-'.$id));
			}else{
				$this->unsubscribed_msg($user[0]['rc_logo'],$this->is_authorized->encryptor('encrypt', $subscriber_id .'-'.$id));			
			}	
		}
	}
	
	/**
	*	Function unsubscirbe_msg to display message if subscriber not exist
	*/
	function unsubscribed_msg($rc_logo=1,$cid_sid=''){
		$msg= '<h3>You have successfully unsubscribed from this mailing list.</h3>';
		$strcid_sid = trim($this->is_authorized->encryptor('decrypt', $cid_sid)); 		
		$arrCidSid = explode('-',$strcid_sid);
		$sid = $arrCidSid[0];
		$cid = $arrCidSid[1];
		$isFeedback = $this->db->query("select count(subscriber_id) as ct from red_unsubscribe_feedback where subscriber_id='$sid' and campaign_id='$cid'")->row()->ct;
		$this->load->view('header_blue');
		$this->load->view('promotions/unsubscribed_msg',array('msg'=>$msg,'rc_logo'=>$rc_logo,'isFeedback'=>$isFeedback,'cid_sid'=>$cid_sid));	
	}
	/**
	*	Function unsubscirbe_msg to display message if subscriber not exist
	*/
	function unsubscirbe_msg(){
		$msg= '<h3>You are not subscribed to this list.</h3>';
		// Load Thanks Message view
		$this->load->view('promotions/thanks_msg',array('msg'=>$msg));
	}
	/**
		Function read to Mark mail as read
		@param int id contain campign id
		@param int subscriber_id contain subscriber id
	*/
	// STOPPED on 17th May, due to OPEN counter issue
	function opened($enc_cid=0,$subscriber_id=0){
		$id =  $this->is_authorized->encryptor('decrypt', $enc_cid);
		$subscriber_id = str_replace('.html','',$subscriber_id);
		$arrSubscriber = $this->is_authorized->decodeSubscriber($subscriber_id);
		list($subscriber_id,$subscriber_email) = $arrSubscriber;	
		if(!(intval($subscriber_id) > 0 ))exit;
		$subscriber_email = $this->is_authorized->webCompatibleString($subscriber_email);
		$mIP = $this->is_authorized->getRealIpAddr();
		
		//Following IPs are from google and should be discarded
		if(trim($mIP) !='')$arrMyIP = @explode(',',$mIP);
		for($i=0;$i < count($arrMyIP);$i++){
			$ip = $arrMyIP[$i];
			$ip_needle = ip2long($ip);			
			if(ip2long('64.233.160.0') <= $ip_needle  &&  $ip_needle <= ip2long('64.233.191.255')) exit; // "in range";
			if(ip2long('66.249.64.0') <= $ip_needle  && $ip_needle <= ip2long('66.249.95.255')) exit; // "in rang";
			
			//if(ip2long('66.102.0.0') <= $ip_needle  &&  $ip_needle <= ip2long('66.102.15.255') ) exit; // "in range";
			
			//if(ip2long('72.14.192.0')  <= $ip_needle  &&  $ip_needle <= ip2long('72.14.255.255')) exit; // "in rang";
			//if(ip2long('74.125.0.0')   <= $ip_needle  &&  $ip_needle <= ip2long('74.125.255.255')) exit; // "in rang";
			//if(ip2long('209.85.128.0') <= $ip_needle  &&  $ip_needle <= ip2long('209.85.255.255')) exit; // "in rang";
			//if(ip2long('216.239.32.0') <= $ip_needle  &&  $ip_needle <= ip2long('216.239.63.255')) exit; // "in rang";			
		}

		
		
		
		
		$fetch_condiotions_array=array('campaign_id'=>$id,'subscriber_id'=>$subscriber_id,'subscriber_email_address'=>$subscriber_email,'email_sent'=>1);
		$email_report=$this->Emailreport_Model->get_emailreport_data($fetch_condiotions_array);
		if($email_report[0]['email_track_read']<=0){					
			// update for subscription			
			$this->Emailreport_Model->update_emailreport(array('email_track_read'=>1,'email_track_read_date'=>date('Y-m-d H:i:s',now())), array('campaign_id'=>$id,'subscriber_id'=>$subscriber_id));
			$this->db->query("update `red_email_campaigns_scheduled` set `email_track_read` = `email_track_read`+1 where campaign_id='$id'");			 
			$this->db->query("update red_email_subscribers set `read` = `read`+1, `last_read_ip`='$mIP', `last_read_date`=current_timestamp() where subscriber_id='$subscriber_id'");	
			// Increment OPENED global_ipr_daily for major webmails
			$arrEml = @explode('@',$subscriber_email);
			$emlDomain = $arrEml[1];
			$IPR_Domain = (in_array($emlDomain,config_item('major_domains')))? $emlDomain : 'all' ;	
			
			$rsCampaign = $this->db->query("select DATE_FORMAT(email_send_date, '%Y-%m-%d')email_send_date, pipeline,campaign_created_by user_id from red_email_campaigns where campaign_id='$id'" );
			$vmta = $rsCampaign->row()->pipeline;
			$email_send_date = $rsCampaign->row()->email_send_date;
			$user_id = $rsCampaign->row()->user_id;
			$rsCampaign->free_result();			
			$this->db->query("insert into red_global_ipr_daily set `mail_domain` = '$IPR_Domain' ,  `log_date`='$email_send_date' ,  `pipeline`='$vmta', `user_id`='$user_id', total_opened=(total_opened + 1) ON DUPLICATE  KEY UPDATE  total_opened=(total_opened + 1) ");			
		}
		
		/*
		// our pixle-tracking img code
		$img_path=substr(FCPATH,0,strrpos(FCPATH,'/'));		
		$img_path= $img_path.'/locker/images/pix.gif';
		header('Content-Type: image/gif');

		$img=@imagecreatefromgif($img_path);
		imagegif($img);
		imagedestroy($img);
		*/
		
		
		/*
		// Open tracker code from:
		// https://www.phpclasses.org/browse/file/62336.html
		
		$img_path=substr(FCPATH,0,strrpos(FCPATH,'/'));
		$img_path= $img_path.'/locker/images/pix.gif';
		$filesize = filesize($img_path);		
		header('Content-Type: image/gif');		 
        header('Pragma: public');
        header('Expires: 0');
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header('Cache-Control: post-check=0, pre-check=0');
        header('Cache-Control: private', false);
        header('Content-Disposition: attachment; filename="blank.gif"');
		header('Content-Transfer-Encoding: binary');
        header('Content-Length: '.$filesize);
		readfile($img_path);
		
		*/
		
		// from interspire-mailer open.php
		// open the file in a binary mode
		$img_path=substr(FCPATH,0,strrpos(FCPATH,'/'));
		$name= $img_path.'/locker/images/pix.gif';
		$fp = fopen($name, 'rb');

		// send the right headers
		header("Content-Type: image/gif");
		header("Content-Length: " . filesize($name));

		// dump the picture and stop the script
		fpassthru($fp);
		exit(0);
		
		
	}
		
		
		
	/**
		Function thanks_msg to display thanks message to user
		@param int rc_logo contain redcapi logo status
	*/
	function thanks_msg($rc_logo=1,$sid=''){
		$msg= '<h3>You have successfully unsubscribed from this mailing list.</h3>';
		$this->load->view('header_blue');
		$this->load->view('promotions/thanks_msg',array('msg'=>$msg,'rc_logo'=>$rc_logo,'sid'=>$sid));		
	}
	function resubscribe($rc_logo,$cid_sid){
		$strcid_sid = trim($this->is_authorized->encryptor('decrypt', $cid_sid)); 		
		$arrCidSid = explode('-',$strcid_sid);
		$sid = $arrCidSid[0];
		$cid = $arrCidSid[1];
		
		$sqlReSubscribe ="update `red_email_subscribers` set `subscriber_status`=1 where `subscriber_id` = '$sid'";
		$this->db->query($sqlReSubscribe);
		
		$msg= '<h3>You have been added back to this mailing list.</h3>';
		$this->load->view('header_blue');
		$this->load->view('promotions/thanks_msg',array('msg'=>$msg,'rc_logo'=>$rc_logo));	
	}
		
	function unsubscribe_feedback(){
		$cid_sid = $this->input->post('cid_sid');
		$strcid_sid = trim($this->is_authorized->encryptor('decrypt', $cid_sid)); 		
		$arrCidSid = explode('-',$strcid_sid);
		$sid = $arrCidSid[0];
		$cid = $arrCidSid[1];
		
		$rsCdetail = $this->db->query("select campaign_created_by,pipeline from red_email_campaigns where campaign_id='$cid'");
		if($rsCdetail->num_rows() > 0){
			$mid = $rsCdetail->row()->campaign_created_by;
			$vmta = $rsCdetail->row()->pipeline;
		}else{
			$mid = 0;
			$vmta = 'redrotate';
		}
		$rsCdetail->free_result();		
		$fid = $this->input->post('opt');
		$thisFid = $fid - 1;
		$arrFeedBackTxt = config_item('unsubscribe_feedback');
		$f_txt = ($fid == 6)?$this->input->post('opt_txt'): $arrFeedBackTxt[$thisFid];
		$this->db->query("insert into `red_unsubscribe_feedback` set subscriber_id='$sid', campaign_id='$cid', member_id='$mid', vmta='$vmta', feedback_id='$fid', feedback_text ='$f_txt' ON DUPLICATE KEY UPDATE  feedback_id='$fid', feedback_text ='$f_txt'");
		echo "Thanks for your feedback";
	
	}

	function powered_by_bib($enc_cid=0,$subscriber_id=0){
		$id =  $this->is_authorized->encryptor('decrypt', $enc_cid);
		if($subscriber_id !=''){
			$arrSubscriber = $this->is_authorized->decodeSubscriber($subscriber_id);
			list($subscriber_id,$subscriber_email) = $arrSubscriber;		
		}
		$mIP = $this->is_authorized->getRealIpAddr();
		$fetch_condiotions_array=array('campaign_id'=>$id,'subscriber_id'=>$subscriber_id,'subscriber_email_address'=> $subscriber_email,'email_sent'=>1);
		$email_report=$this->Emailreport_Model->get_emailreport_data($fetch_condiotions_array);
		if($email_report[0]['email_track_read']<=0){			
			$this->Emailreport_Model->update_emailreport(array('email_track_read'=>1,'email_track_read_date'=>date('Y-m-d H:i:s',now())), array('campaign_id'=>$id,'subscriber_id'=>$subscriber_id));						
			$this->db->query("update `red_email_campaigns_scheduled` set `email_track_read` = `email_track_read`+1 where campaign_id='$id'");			 
			$this->db->query("update red_email_subscribers set `read` = `read`+1, `last_read_ip`='$mIP',`last_read_date`=current_timestamp() where subscriber_id='$subscriber_id'");
			// Increment OPENED global_ipr_daily for major webmails
			$arrEml = @explode('@',$subscriber_email);
			$emlDomain = $arrEml[1];
			$IPR_Domain = (in_array($emlDomain,config_item('major_domains')))? $emlDomain : 'all' ;							
			$rsCampaign = $this->db->query("select DATE_FORMAT(email_send_date, '%Y-%m-%d')email_send_date, pipeline,campaign_created_by user_id  from red_email_campaigns where campaign_id='$id'" );
			$vmta = $rsCampaign->row()->pipeline;
			$email_send_date = $rsCampaign->row()->email_send_date;
			$user_id = $rsCampaign->row()->user_id;
			$rsCampaign->free_result();				
			$this->db->query("insert into red_global_ipr_daily set `mail_domain` = '$IPR_Domain' ,  `log_date`='$email_send_date' ,  `pipeline`='$vmta', `user_id`='$user_id', total_opened=(total_opened + 1) ON DUPLICATE  KEY UPDATE  total_opened=(total_opened + 1) ");			
		}
		
		// update for subscription
		
		redirect('/?source=pbl');

	}	
	
	
}
?>