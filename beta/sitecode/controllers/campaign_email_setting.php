<?php
/**
* A Campaign_email_setting class
*
* This class is for campaign email setting
*
* @version 1.0
* @author Pravin Jha <pravinjha@gmail.com>
* @project BIB
*/
class Campaign_email_setting extends CI_Controller
{
	function __construct(){
		parent::__construct();		
		// if memeber is not login then redirect to login page
		if($this->session->userdata('member_id')=='')
			redirect('user/index');
		$this->load->helper('transactional_notification');
		$this->load->helper('notification');
		$this->load->helper('admin_notification');
		// Load upload library for file uploading		
		$this->load->model('UserModel');
		$this->load->model('userboard/Campaign_Model');
		$this->load->model('userboard/contact_model');
		$this->load->model('ConfigurationModel');
		$this->load->model('userboard/Subscription_Model');	
		$this->load->model('userboard/Campaign_Autoresponder_Model');
		$this->load->model('userboard/Autoresponder_Model');		
		$this->load->model('Activity_Model');
		
		#$this->load->model('userboard/Emailreport_Model');
		#$this->load->model('userboard/Subscriber_Model');
		$this->output->enable_profiler(false);
		
		force_ssl();	
	}
	/**
	*	Function index 
	*	for campaign email setting
	*
	*	@param integer $campaign_id  campaign id
	**/
	function index($campaign_id=0){		 
		set_time_limit(0);			  
		$encrypted_cid = $campaign_id;
		$campaign_id = $this->is_authorized->encryptor('decrypt',$campaign_id); 		
		$memberid = $this->session->userdata('member_id');
		$campaign_info=$this->Campaign_Model->get_campaign_data(array('campaign_id'=>$campaign_id,'campaign_created_by'=>$memberid));		 
		
		//	Check campaign already send or new to send,	If already send then display message: 			
		//	This campaign is already sent		 																
		if($campaign_info[0]['campaign_status']=="active"){	
			$this->messages->add('This campaign is already sent', 'success');			
			redirect('promotions');
		}
		
		// Check  Maximum Contacts according to user selected package id			
		if($this->check_user_selected_package()){
			if($this->input->post('action')=='send_campaign'){				
				$campaign_data['subscription_ids_str']=$this->input->post('subscription_ids_str');
				// Set schedule date time of campaign 
				$scheduled_datetime=$this->input->post('date_to_send');				
				$scheduled_date_arr=explode('/',$scheduled_datetime);
			 	$scheduled_date=$scheduled_date_arr[2].'-'.$scheduled_date_arr[0].'-'.$scheduled_date_arr[1];
				$hr = $this->input->post('hour_to_send');
				$min = $this->input->post('min_to_send');
				 
				if($this->input->post('ampm_to_send') == 'pm')
				$hr = ($this->input->post('hour_to_send') < 12)? $this->input->post('hour_to_send') + 12 :  12;
				elseif($this->input->post('ampm_to_send') == 'am')
				$hr =  ($this->input->post('hour_to_send') < 12)? $this->input->post('hour_to_send') :  0;
				
				if($hr == 24)$hr= 0;
				if($hr < 10)$hr= '0'.$hr;
				if($min < 10)$min= '0'.$min; 
				
				if($this->input->post('send_now')==1){
					$scheduled_datetime = date('Y-m-d H:i:s',now()); 
					$is_send_now = 'SEND NOW:'.$scheduled_datetime;
				}else{
					$scheduled_datetime = date('Y-m-d H:i:s',local_to_gmt(mktime($hr,$min,0, $scheduled_date_arr[0], $scheduled_date_arr[1], $scheduled_date_arr[2])));
					$is_send_now = 'SCHEDULE IT:'.$scheduled_datetime;
				}
				$queued_datetime= date('Y-m-d H:i:s',local_to_gmt(time()));  
				
				// if any campaign is scheduled before time now, it should be sent/scheduled as now.
				IF(strtotime($scheduled_datetime) < strtotime($queued_datetime) ) $scheduled_datetime = $queued_datetime;
				// if user want save the email setting
				if($this->input->post('save_email')==1){ 
					$this->save_campagin_email_setting($campaign_id,$schedule_datetime_arr['scheduled_datetime']);
				}else if($this->input->post('save_email')!=1){ // If user want to schedule email					
					$this->form_validation->set_rules('email_subject', 'Email Subject', 'required');			
					$this->form_validation->set_rules('email_id', 'From Email', 'required|valid_email');
					$this->form_validation->set_rules('email_from', 'From Name', 'required');
					$this->form_validation->set_rules('subscriptions[]', 'Select List', 'required');
					if($this->input->post('send_now')!=1){
						$this->form_validation->set_rules('date_to_send', 'Scheduled Date', 'required|callback_validate_scheduled_date');
					}
					// To check form is validated
					if($this->form_validation->run()==true){
						// $email_subject = mb_convert_encoding($this->input->post('email_subject'), 'HTML-ENTITIES', 'UTF-8');
						$email_subject = $this->input->post('email_subject');
						$preheader = $this->input->post('preheader');
						//	Fetch Login user authentication for sheduleing email   
						$user	= $this->UserModel->get_user_data(array('member_id'=>$memberid));
						$user_is_authentic=$user[0]['is_authentic'];
						$clicktracking_status=$user[0]['clicktracking_status'];
						$is_automatic_segmentation=$user[0]['is_automatic_segmentation'];
						$segment_size=$user[0]['segment_size'];
						$campaign_priority=$user[0]['campaign_priority'];
						//$user_is_ga_enabled=$user[0]['google_analytics_status'];
						// Fetch default limit for schedule a email of every user 
						// Load the configuration model which interact with database
						
						$site_configuration_array=$this->ConfigurationModel->get_site_configuration_data(array('config_name'=>'default_allowed_limit_for_send_email'));
						$default_allowed_limit_for_send_email=$site_configuration_array[0]['config_value'];
						// checked subscription lsit have contacts or not
						$number_of_contacts=$this->selected_subscribers(false);
						
						if($number_of_contacts==0){
							$this->messages->add('Please add contacts in checked Contact List', 'error');
						}else{
							// Recieve subscription and campaign posted by user					
							$subscriptions=$this->input->post('subscriptions');
							$subscription_ids_str=implode(',',$subscriptions);
							$input_array=array(	'campaign_id'=>$campaign_id,'campaign_scheduled_date'=>$scheduled_datetime);

							// Store scheduled campaign in database							
							if($campaign_info[0]['campaign_status']=="archived"){
								$this->Campaign_Model->update_scheduled_campaign($input_array,array('campaign_id'=>$campaign_info[0]['campaign_id']));
							}else{
								$this->Campaign_Model->create_scheduled_campaign($input_array);
							}
							
							 
							$subscriber_count=$this->contact_model->get_contacts_count_in_selected_lists(array('subscriber_status'=>1,'res.is_deleted'=>0,'res.subscriber_created_by'=>$memberid),$subscriptions);
							
							$list_ids_str=implode('_',$subscriptions);
							#$subscriber_count=count($subscriber_array);	#number of subsribers
							if(($subscriber_count <= $default_allowed_limit_for_send_email)||($user_is_authentic==1)){
								$campaign_status="archived";
							}else{
								$campaign_status=($this->input->post('send_now')==1)? "ready" : "active_ready";
							}
							//if($this->input->post('is_clicktracking') !== false)
							if($clicktracking_status == 1){
								$is_clicktracking = (isset($_POST['is_clicktracking'])) ?  1 : 0 ;
							}else{
								$is_clicktracking = 1;
							}
							
							// Update campaign status to archived
							$campaign_data_update = array('campaign_sheduled'=>$scheduled_datetime,'campaign_queued'=>$queued_datetime,'email_subject'=>$email_subject,'sender_email'=>$this->input->post('email_id'),'sender_name'=>$this->input->post('email_from'),'subscription_list'=>$subscription_ids_str,'email_send_date'=>$scheduled_datetime,'is_status'=>'0','campaign_priority'=>$campaign_priority, 'is_ga_enabled'=>$this->input->post('is_ga_enabled'),'is_clicktracking'=>$is_clicktracking, 'preheader'=>$preheader);
							if( '' != $this->input->post('reply_to_email') )$campaign_data_update['reply_to_email'] = trim($this->input->post('reply_to_email'));
							$this->Campaign_Model->update_campaign($campaign_data_update,array('campaign_id'=>$campaign_id));
							//$Temp_update_query = 'Debug - Temp_update_query :'.$is_send_now . '---'.$campaign_status. '-----'.$this->db->last_query();
							//admin_notification_send_email('peejha@yahoo.com', SYSTEM_EMAIL_FROM,"BoldInbox", 'Debug - Temp_update_query',$Temp_update_query,$Temp_update_query);
							// Add segmentation
							if($is_automatic_segmentation > 0 && $segment_size > 0 && $campaign_status != "archived"){
								$this->db->query("insert into `red_ongoing_segmentation` set campaign_id='$campaign_id', segment_size='$segment_size', segment_interval='30' ON DUPLICATE KEY UPDATE `segment_size`='$segment_size', `segment_interval`='30' ");
								$this->Campaign_Model->update_campaign(array('is_segmentation'=>'1','number_of_contacts'=>$segment_size, 'segment_interval'=>'30'),array('campaign_id'=>$campaign_id));
							}
							
							$this->create_activity_log($campaign_id);
							$queue_log = config_item('campaign_files').'queue_log_'.date('Ymdhis');
							
							// NEW section to move queueing via cronjob: Starts
							$this->Campaign_Model->update_campaign(array('campaign_status'=>'queueing','campaign_status_show'=>'2', 'sent_counter'=>0, 'campaign_contacts'=>$subscriber_count, 'tobe_campaign_status'=>$campaign_status),array('campaign_id'=>$campaign_id));
							// NEW section to move queueing via cronjob: ENDS
								
							// Since queueing is done using cronjob, following are not in use
							$command = config_item('php_path')." ".FCFOLDER."/index.php  bibsend addToQueue $campaign_id $memberid $list_ids_str $campaign_status"	;
						 										
							 
							if(($campaign_info[0]['campaign_template_option']!=3)&&($campaign_info[0]['campaign_template_option']!=5)){
								$page_html=html_entity_decode($campaign_info[0]['campaign_content'], ENT_QUOTES, "utf-8" ); 
							}else{
								$page_html=$campaign_info[0]['campaign_content'];
							}
							$this->Campaign_Autoresponder_Model->encode_url($campaign_id,$page_html);	
							$affected_member_package_id=$this->UserModel->update_member_package(array('is_first_campaign_send'=>'1'),array('member_id'=>$memberid));				
											
							if($subscriber_count<=$default_allowed_limit_for_send_email){							 
								$user_name=$user[0]['member_username'];
								// send notification email to admin
								$this->notification_subscribers_count($campaign_id,$subscriber_count,$user_name);
								 
							}else{
								//$affected_member_package_id=$this->UserModel->update_member_package(array('is_first_campaign_send'=>'1'),array('member_id'=>$memberid));
								// Check user is authentic or not :						
								//If authentic then send  notfication to admin about		
								// Toatal number of subscribers count						
								// If not authentic then send  notfication to admin for	
								// allow or disallow user campaign									
								if($user_is_authentic==1){								
									$this->notification_subscribers_count($campaign_id,$subscriber_count,$user[0]['member_username']);
								}else{
									$this->notification_email($campaign_id,$subscriber_count,$user[0]['member_username']);
								}
							}
							 
							if($affected_member_package_id > 0){
								// Redirect to first time send campaign notification
								redirect('campaign_email_setting/first_time_user_notification');
								exit;
							}else{
								// Redirect to listing of campaigns
								redirect('promotions/check_campaign_status/'.$campaign_id);
								exit;
							}
						}
						
					}
				}
			}
		}else{
			redirect('change_package/index');
			exit;
		}
		

		// Load subscriptions created by user
		$fetch_conditions_array=array('subscription_created_by'=>$memberid,	'is_deleted'=>0,'subscription_status'=>1);	
		// Fetch Subscription list created by user
		$subscriptions_count=$this->Subscription_Model->get_subscription_count($fetch_conditions_array);
		$subscriptions=$this->Subscription_Model->get_subscription_data($fetch_conditions_array,$subscriptions_count);
		$i=0;
		foreach($subscriptions as $subscription){
			$subscription_id= $subscription['subscription_id'];
			$number_of_contacts=$this->selected_subscribers(false,$subscription_id);
			$subscriptions[$i]['number_of_contacts']=$number_of_contacts;
			$i++;
		}
		$subscription_data=array('subscriptions'=>$subscriptions);
		$campaign_data['email_id']	= $this->getFromEmlArray();
		$campaign_data['last_campaign_from_email']	= $this->getLastCampaignFromEmail();
		
		// Fetch Login user info for displaying on campaign footer
		$user_data_array=$this->UserModel->get_user_data(array('member_id'=>$memberid));
		$user_info=true;
		$user_info=(!$user_data_array[0]['company'])?false :  true;
		$user_info=(!$user_data_array[0]['address_line_1'])?false :  true;
		$user_info=(!$user_data_array[0]['city'])?false :  true;
		$user_info=(!$user_data_array[0]['state'])?false :  true;
		$user_info=(!$user_data_array[0]['zipcode'])?false :  true;		
		
		$campaign_data['user_info']=$user_info;
		$campaign_data['user_data']=$user_data_array[0];
		$campaign_data['email_from']=$user_data_array[0]['company'];
		
		//Fetch Country name
		$country_info=$this->UserModel->get_country_data();
		$campaign_data['country_info']=$country_info;

		$campaign_data['is_ga_enabled']=$user_data_array[0]['google_analytics_status'];
		$campaign_data['is_clicktracking']=$user_data_array[0]['clicktracking_status'];
		$campaign_data['reply_to_enabled']=$user_data_array[0]['reply_to_enabled'];
		// Collect email template information
		$email_template_info=$this->Campaign_Model->get_campaign_data(array('campaign_id'=>$campaign_id,'campaign_created_by'=>$memberid));
		if(($email_template_info[0]['campaign_status']=='active')||(!count($email_template_info))){
			redirect('promotions');
			exit;
		}
		$campaign_data['campaign_id']=$campaign_id;
		$campaign_data['encrypted_cid']=$encrypted_cid;
		$campaign_data['campaign_template_option']=$campaign_info[0]['campaign_template_option'];
		$campaign_data['camapign']=$email_template_info[0];
		$subscription_array=array();
		$subscription_array=explode(",",$email_template_info[0]['subscription_list']);
		$campaign_data['camapign']['subscription_list']=$subscription_array;
		if($email_template_info[0]['email_send_date'] !== null){
			//$email_send_date= date("m/d/Y g:i:a",strtotime($email_template_info[0]['email_send_date']));
			$email_send_date= date("m/d/Y g:i:a",strtotime(getGMTToLocalTime($email_template_info[0]['email_send_date'],$this->session->userdata('member_time_zone')) ));
	 
		
			$date_arr=explode(" ",$email_send_date);
			$campaign_data['camapign']['send_time']=explode(":",$date_arr[1]);
			$campaign_data['camapign']['delivery_date']=$date_arr[0];
		}else{
			$email_send_date=date("m/d/Y g:i:a");
			$date_arr=explode(" ",$email_send_date);
			$campaign_data['camapign']['send_time']=explode(":",$date_arr[1]);
			$campaign_data['camapign']['delivery_date']=$date_arr[0];
		}
		$campaign_data['camapign']['test_email_count']=$campaign_info[0]['test_email'];
		
		$quota_remaining = $this->UserModel->getRemainingCampaignSendingQuota($memberid);
		// Recieve any messages to be shown, when campaign is added or updated
		$messages=$this->messages->get();
		// Get shoreten url 
		$shorten_url=get_shorten_url();
		$contactDetail = $this->is_authorized->showBar($memberid);
		$this->load->view('header',array('title'=>'Send Campaigns','contactDetail'=>$contactDetail));
		$this->load->view('promotions/campaign_email_setting',array('campaign_data'=>$campaign_data,'subscription_data'=>$subscription_data,'messages'=>$messages,'shorten_url'=>$shorten_url,'quota_remaining'=>$quota_remaining));
		$this->load->view('footer');
	}
	
	/**
	*	Function called via AJAX to get List of From Emls
	*/
	function getLastCampaignFromEmail(){
	
		return $this->db->query("select sender_email from red_email_campaigns where campaign_created_by='".$this->session->userdata('member_id')."' and campaign_status='active' order by email_send_date desc limit 1")->row()->sender_email;
	
	}
	/**
	*	Function called via AJAX to get List of From Emls
	*/
	function ajaxFromEmlArray(){
		$arrEmails = $this->getFromEmlArray();
		
		echo implode(',',$arrEmails);
	}
	function getFromEmlArray(){
		$arrFromEmls = array($this->session->userdata('member_email_address'));
		$rsOtherEmailAddresses = $this->db->query("select `email_address` from `red_member_from_email` where `member_id` = '".$this->session->userdata('member_id')."' and `is_verified`=1");
		if($rsOtherEmailAddresses->num_rows() > 0){
			foreach($rsOtherEmailAddresses->result_array() as $otherEml){	
				$arrFromEmls[]	= trim($otherEml['email_address']);			
			}
		}
		$rsOtherEmailAddresses->free_result();	
		return $arrFromEmls;
	}
	/**
	*	Function called via AJAX to add from email address
	*/
	function add_another_emailid(){
		$strNewEml  = trim($this->input->post('newEml'));
		$strNewEmlDomain = substr(strrchr($strNewEml, "@"), 1);
		if(in_array($strNewEmlDomain, config_item('major_domains'))){	
			die('InvalidDomain');
		}
		if(!$this->is_authorized->ValidateAddress($strNewEml)){
			die('err');
		}else{
			$mid = $this->session->userdata('member_id');
			$strUniqueString = sha1(time());
			$rsNewEmail = $this->db->query("select * from `red_member_from_email` where member_id='$mid' and email_address='$strNewEml'");
			//echo $this->db->last_query();
			if($rsNewEmail->num_rows() > 0){
				echo 'dup';
			}else{
				$this->db->query("insert into `red_member_from_email` set member_id='$mid',email_address='$strNewEml',unique_string='$strUniqueString' ON DUPLICATE KEY UPDATE unique_string='$strUniqueString'");
				create_transactional_notification('verify_other_email',array($strUniqueString,$strNewEml, $this->session->userdata('member_username')));
				echo 'ok';
			}
			$rsNewEmail->free_result();
		}
		exit;
	}
	
	/**
	*	Function autoresponder to set email setting for autoresponder
	*	@param int autoresponder_id contain autoresponder id
	*/
	function autoresponder($autoresponder_id=0){		 
		$fetch_condiotions_array=array('campaign_created_by'=>$this->session->userdata('member_id'), 'campaign_id'=>$autoresponder_id, 'is_deleted'=>0);
		# Fetches campaign data from database
		$autoresponder_info=$this->Autoresponder_Model->get_autoresponder_data($fetch_condiotions_array);
		# To check form is submittted and action is send
		if($this->input->post('action')=='send_autoresponder'){
			$autoresponder_data['subscription_ids_str']=$this->input->post('subscription_ids_str');
			if($this->input->post('save_email')==1){
				$this->save_autoresponder_email_setting($autoresponder_id);				
			}else{
				# Validation rules are applied
				$this->form_validation->set_rules('email_subject', 'Email Subject', 'required');
				$this->form_validation->set_rules('email_id', 'From Email', 'required');
				$this->form_validation->set_rules('email_from', 'From Name', 'required');		
				$this->form_validation->set_rules('autoresponder_schedule_interval', 'Number Of Days', 'required|is_natural|trim');
			}
			# To check form is validated
			if($this->form_validation->run()==true){
				// schedule email
				// Recieve subscription and campaign posted by user
				$subscription_ids_str=$this->input->post('subscription_ids_str');
				//$email_subject = mb_convert_encoding($this->input->post('email_subject'), 'HTML-ENTITIES', 'UTF-8');
				$email_subject = $this->input->post('email_subject');
				// Create input array to send to database
				$input_array=array('autoresponder_id'=>$autoresponder_id, 'subscription_ids'=>$subscription_ids_str, 'autoresponder_scheduled_status'=>'1',
					'autoresponder_scheduled_interval'=>$this->input->post('autoresponder_schedule_interval')
				);	
				$user_packages_array=$this->UserModel->get_user_packages(array('member_id'=>$this->session->userdata('member_id'),'is_deleted'=>0));
				$input_array['is_verified']	=  ($user_packages_array[0]['package_id'] > 0 )? 1: 0; //paid user	
				
				if($autoresponder_info[0]['autoresponder_scheduled_id']>0){
					$input_array['autoresponder_scheduled_id']=$autoresponder_info[0]['autoresponder_scheduled_id'];
				}
				// Store scheduled autoresponder in database
				$autoresponder_scheduled_id=$this->Autoresponder_Model->create_scheduled_autoresponder($input_array);
				// admin alert ends
				$email_msg ="<p>Hello admin,</p>";
				$email_msg.="<p>Verify Autoresponder : created by <b>".$this->session->userdata('member_username')."</b></p>";				
				$email_msg.='<p>Regards,</p>';
				$email_msg.='<p>BoldInbox Team</p>';
				
				$to=$this->get_Admin_notification_email();
				$subject="Verify Autoresponder by ".$this->session->userdata('member_username');
				admin_notification_send_email($to, SYSTEM_EMAIL_FROM,"BoldInbox", $subject,$email_msg,$email_msg);
				
				
				$schedule_date=date("Y-m-d H:i:s");				
				$this->Autoresponder_Model->update_autoresponder(array('campaign_status'=>'1','autoresponder_scheduled_interval'=>$this->input->post('autoresponder_schedule_interval'),'autoresponder_scheduled_id'=>$autoresponder_scheduled_id,'campaign_sheduled'=>date('Y-m-d H:i:s',now()),'email_subject'=>$email_subject,'sender_email'=>$this->input->post('email_id'),'sender_name'=>$this->input->post('email_from'),'is_ga_enabled'=>$this->input->post('is_ga_enabled'),'is_clicktracking'=>$this->input->post('is_clicktracking')),array('campaign_id'=>$autoresponder_id));
				// Fetch Login user info for displaying on campaign footer
				$user=$this->UserModel->get_user_data(array('member_id'=>$this->session->userdata('member_id')));
								 
				if(($autoresponder_info[0]['campaign_template_option']!=3)&&($autoresponder_info[0]['campaign_template_option']!=5)){
					$page_html=html_entity_decode($autoresponder_info[0]['campaign_content'], ENT_QUOTES, "utf-8" ); 
				}else{
					$page_html=$autoresponder_info[0]['campaign_content'];
				}
				$this->Campaign_Autoresponder_Model->encode_url($autoresponder_id,$page_html,true);	
				//#############################
				//# create activity log		#
				//#############################
				
				// create array to insert values in activty table
				$values=array('user_id'=>$this->session->userdata('member_id'), 'activity'=>'autoresponder_schedule',  'campaign_id'=>$autoresponder_id);
				$this->Activity_Model->create_activity($values);
				// Assign success message by message class
				$this->messages->add('Autoresponder Scheduled Successfully', 'success');
					
				// Redirect to listing of autoresponders
				redirect('userboard/autoresponder');
			}
		}
		$autoresponder_data['email_id']	= $this->getFromEmlArray();
		
		$user_data_array=$this->UserModel->get_user_data(array('member_id'=>$this->session->userdata('member_id')));
		$user_info=true;
		$str_user_detail_for_footer="";
		$user_info=(!$user_data_array[0]['company'])? false :  true;
		$user_info=(!$user_data_array[0]['address_line_1'])? false :  true;
		$user_info=(!$user_data_array[0]['city'])? false :  true;
		$user_info=(!$user_data_array[0]['state'])? false :  true;
		$user_info=(!$user_data_array[0]['zipcode'])? false :  true;
		$user_info=(!$user_data_array[0]['country_name'])? false :  true;
		
		$autoresponder_data['user_info']=$user_info;
		$autoresponder_data['user_data']=$user_data_array[0];
		$campaign_data['user_data']=$user_data_array[0];
		$autoresponder_data['email_from']=$user_data_array[0]['company'];
		//Fetch Country name
		$country_info=$this->UserModel->get_country_data();
		$autoresponder_data['country_info']=$country_info;
		$campaign_data['country_info']=$country_info;
		
		//$autoresponder_data['email_id']=$this->session->userdata('member_email_address');
		$autoresponder_data['campaign_id']=$autoresponder_id;
		$autoresponder_data['autoresponder']=$autoresponder_info[0];
		$autoresponder_data['camapign']['test_email_count']=$autoresponder_info[0]['test_email'];
		
		$autoresponder_data['is_ga_enabled']=$user_data_array[0]['google_analytics_status'];
		$autoresponder_data['is_clicktracking']=$user_data_array[0]['clicktracking_status'];
		//get autoresponder groups
		$autoresponder_group=$this->Autoresponder_Model->get_autoresponder_group(array('is_deleted'=>0,'id'=>$autoresponder_info[0]['autoresponder_group_id']));
		
		//collect in array
		$autoresponder_data['subscription_ids_str']=$autoresponder_group[0]['autoresponder_subscription_id'];
		# Load the configuration model which interact with database
		
		#Get shoreten url 
		$shorten_url=get_shorten_url();
		$this->load->view('header',array('title'=>'Send Autoresponder'));		
		$this->load->view('userboard/autoresponder_email_setting',array('autoresponder_data'=>$autoresponder_data,'campaign_data'=>$campaign_data,'shorten_url'=>$shorten_url));
		$this->load->view('footer');
	}
	/**
		Function package_info to fetch selected user package info
		@return boolean return true or false
	**/
	function check_user_selected_package(){		
		$package_max_contacts = $this->UserModel->get_current_packages_maxcontact($this->session->userdata('member_id'));		 
		// Get member's actual contact count
		$subscriber_count = $this->contact_model->getContactsCount(array('subscriber_created_by'=>$this->session->userdata('member_id'),'subscriber_status'=>1,'is_deleted'=>0));
		
		// if actual contact count is more than member's package max contact, then send notification to admin
		if($subscriber_count > $package_max_contacts){			
			$user_data_array=$this->UserModel->get_user_data(array('member_id'=>$this->session->userdata('member_id')));
			$user_info=array($user_data_array[0]['member_username'],$subscriber_count);
			create_notification("upgradation",$user_info);			 
			return false;
		}else{
			return true;
		}
	}	
	 
	 
	/**
		Function save_campagin_email_setting to save email setting in database
	**/
	function save_campagin_email_setting($campaign_id=0,$scheduled_datetime=""){
		#Recieve subscription and campaign posted by user						
		$subscriptions=$this->input->post('subscriptions');
		if($subscriptions){
			$subscription_ids_str=implode(',',$subscriptions);
		}
		#Check email subject empty or fill
		if(!$this->input->post('email_subject')){
			$campaign_title="Unnamed";	#Set email subject unnamed if email_subject is empty
		}else{
			$campaign_title=$this->input->post('email_subject');	#Set email subject
			//$email_subject = mb_convert_encoding($this->input->post('email_subject'), 'HTML-ENTITIES', 'UTF-8');
			//$campaign_title = $email_subject;
		}
		#Create input array to send to database						
		$input_array=array('email_subject'=>$email_subject,'sender_email'=>$this->input->post('email_id'),'sender_name'=>$this->input->post('email_from'),'subscription_list'=>$subscription_ids_str,'email_send_date'=>$scheduled_datetime);
		if( '' != $this->input->post('reply_to_email') )$input_array['reply_to_email'] = trim($this->input->post('reply_to_email'));		
		$this->Campaign_Model->update_campaign($input_array,array('campaign_id'=>$campaign_id));
		# Assign success message by message class
		$this->messages->add('Email Saved Successfully', 'success');			
		# Redirect to listing of campaigns
		redirect('promotions');
	}
	/**
		Function selected_subscribers to check number of subscribers in selected subscriptions list
		@param $ajax bolean check function is call for ajax or not
		@param $subscription_id int contain subscription id
	**/
	function selected_subscribers($ajax=true,$subscription_id=0){
		$where_in=array();
		if($subscription_id){			
			$where_in[]=$subscription_id;
			unset($_POST['subscriptions']);
				$_POST['subscriptions'][]=$subscription_id;
				$subscriber_count=0;
				$fetch_condiotions_array=array(	'res.subscriber_created_by'=>$this->session->userdata('member_id'),	'res.subscriber_status'=>1,	'res.is_deleted'=>0);	
				#$subscribers=$this->Subscriber_Model->get_distinct_email($fetch_condiotions_array,$_POST['subscriptions']);
				#$subscriber_count=count($subscribers);			
				$subscriber_count=$this->contact_model->get_contacts_count_in_selected_lists($fetch_condiotions_array,$_POST['subscriptions']);
				
		}else if(isset($_POST['subscriptions'])){
			$subscriber_count=0;
			$fetch_condiotions_array=array('res.subscriber_created_by'=>$this->session->userdata('member_id'), 'res.subscriber_status'=>1, 'res.is_deleted'=>0);	
			#$subscribers=$this->Subscriber_Model->get_distinct_email($fetch_condiotions_array,$_POST['subscriptions']);
			#$subscriber_count=count($subscribers);
			$subscriber_count=$this->contact_model->get_contacts_count_in_selected_lists($fetch_condiotions_array,$_POST['subscriptions']);
		}else{
			$subscriber_count=0;
		}
		if($ajax){
			echo $subscriber_count;
		}else{
			return $subscriber_count;
		}		
	}
	/**
		Function validate_scheduled_date to check that scheduled datetime should be greater than current date time
	**/
	function validate_scheduled_date(){
		if($this->scheduled_datetime !='' and $this->scheduled_datetime < time()){			 
				$this->form_validation->set_message('validate_scheduled_date', 'The %s field can not be older than current date');
				return false;
				exit;
		}		
		return true;
		exit;		
	}
	function create_activity_log($campaign_id=0){
		$this->load->model('Activity_Model');
		$site_configuration_array=$this->ConfigurationModel->get_site_configuration_data(array('config_name'=>'default_allowed_limit_for_send_email'));
		$default_allowed_limit_for_send_email=$site_configuration_array[0]['config_value'];
		# Assign success message by message class
		if(trim($this->input->post('send_now'))=='1'){
			if(($subscriber_count<=$default_allowed_limit_for_send_email)||($user_is_authentic==1)){				 
				# create array for insert values in activty table
				$values=array('user_id'=>$this->session->userdata('member_id'),  'activity'=>'campaign_sent',  'campaign_id'=>$campaign_id);
				$this->Activity_Model->create_activity($values);				
			}else{				 
				# create array for insert values in activty table
				$values=array('user_id'=>$this->session->userdata('member_id'), 'activity'=>'campaign_schedule', 'campaign_id'=>$campaign_id);
				$this->Activity_Model->create_activity($values);
				$this->messages->add('Your email campaign is in queue and will be sent shortly.', 'success');
			}
		}else{
			# create array for insert values in activty table
			$values=array('user_id'=>$this->session->userdata('member_id'),  'activity'=>'campaign_schedule', 'campaign_id'=>$campaign_id);							
			$this->Activity_Model->create_activity($values);			
			$this->session->set_flashdata('campaign_status', 'scheduled');
		}
	}
	
	/**
		Function to send notification email to admin for schdule campaigns
	**/
	function notification_email($campaign_id=0,$subscriber_count=0,$user_name=""){
		//Get email template content
		$email_template_info=$this->Campaign_Model->get_campaign_data(array('campaign_id'=>$campaign_id));
		$scheduledTime = date('Y-m-d g:i a', strtotime( getGMTToLocalTime($email_template_info[0]['campaign_sheduled'], WEBMASTER_TIMEZONE )));
		
		$email_msg="";
		$email_msg.="<p>Hello admin,</p>";
		$email_msg.="<p>Campaign :<b>".$email_template_info[0]['campaign_title']."</b> created by <b>$user_name</b>, is ready to send for <b>$subscriber_count</b> subscribers.
						<br/> Campaign is sent/scheduled for <b>".$scheduledTime."</b>	</p>";
		$email_msg.="<p>Select a choice to allow or disallow it from admin panel.</p>";
		$email_msg.='<p>Regards,</p>';
		$email_msg.='<p>BoldInbox Team</p>';
		
		$to=$this->get_Admin_notification_email();								 
		$message=$email_msg;						
		$text_message=$email_msg;
		// Added by pravinjha@gmail.com
		admin_notification_send_email($to, SYSTEM_EMAIL_FROM,'BoldInbox', "Approval required for campaign sent/scheduled for ".$scheduledTime,$message,$text_message,0,0,true);
	}
	/**
		Function to send notification email to admin for user is sending campaign to xx number of subscribers
	**/
	function notification_subscribers_count($campaign_id=0,$subscriber_count=0,$user_name=""){
		//Get email template content
		$email_template_info=$this->Campaign_Model->get_campaign_data(array('campaign_id'=>$campaign_id));
		$scheduledTime = date('Y-m-d g:i a', strtotime( getGMTToLocalTime($email_template_info[0]['campaign_sheduled'], WEBMASTER_TIMEZONE )));
		
		$email_msg="";
		$email_msg.="<p>Hello admin,</p>";
		$email_msg.="<p>Campaign :<b>".$email_template_info[0]['campaign_title']."</b> created by <b>$user_name</b> is sent/scheduled for ".$subscriber_count." <br/> subscribers. <br/>Campaign is sent/scheduled for <b>".$scheduledTime."</b></p>";
		$email_msg.='<p>Regards,</p>';
		$email_msg.='<p>BoldInbox Team</p>';
		
		$to=$this->get_Admin_notification_email();
		$message=$email_msg;						
		$text_message=$email_msg;		
		// Added by pravinjha@gmail.com
		admin_notification_send_email($to, SYSTEM_EMAIL_FROM,'BoldInbox', 'Campaign sent/scheduled for '.$scheduledTime,$message,$text_message,0,0,true);
	}
	/**
		Function get_Admin_notification_email to fetch admin emails from config table
		@return string $admin_email return admin email list
	*/
	function get_Admin_notification_email(){
		$sql            = 'SELECT config_name,config_value FROM `red_site_configurations` where `config_name` = "admin_notification_email"';
		$query          = $this->db->query($sql);
		$admin_email	= "";
		if ($query->num_rows() == 1)
		{
			$row = $query->row();
			$admin_email        = $row->config_value;
		}
		return $admin_email;
	}
	/**
		Function first_time_user_notification will display notification to first time senders after "upgrade" is done
	*/
	function first_time_user_notification(){
		#Loads header, first_time_user_notification view.
		$this->load->view('header_blue',array('title'=>'Notification'));
		$this->load->view('promotions/first_time_user_notification',array('title'=>'Notification'));	
		$this->load->view('footer_blue');
	}
	/**
		Function save_autoresponder_email_setting to save autoresponder email setting in database
	**/
	function save_autoresponder_email_setting($autoresponder_id=0){
		//$email_subject = mb_convert_encoding($this->input->post('email_subject'), 'HTML-ENTITIES', 'UTF-8');
		$email_subject = $this->input->post('email_subject');
		$input_array=array('email_subject'=>$email_subject,					
			'sender_email'=>$this->input->post('email_id'),
			'sender_name'=>$this->input->post('email_from'),
			'autoresponder_scheduled_interval'=>$this->input->post('autoresponder_schedule_interval')
		);
		#Store scheduled autoresponder in database
		$this->Autoresponder_Model->update_autoresponder($input_array,array('campaign_id'=>$autoresponder_id));
		# Assign success message by message class
		$this->messages->add('Email Saved Successfully', 'success');
		# Redirect to listing of campaigns
		redirect('userboard/autoresponder');
	}
	 	
}
?>
