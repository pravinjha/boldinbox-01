<?php
class Campaign extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		if($this->session->userdata('webmaster_username')!='sitemaster')redirect(base_url());

		// Load the campaign model which interact with database
		$this->load->model('UserModel');
		$this->load->model('userboard/Campaign_Model');
		$this->load->model('bandook/Campaigns_Model');
		$this->load->model('userboard/Emailreport_Model');		
		$this->load->model('userboard/Page_Model');
		$this->load->model('userboard/Contact_model');
		$this->load->model('bandook/MessagesModel');
		$this->load->model('ConfigurationModel');
		$this->load->helper('transactional_notification');
		
		# HTTPS/SSL enabled
		force_ssl();
		$this->output->enable_profiler(false);

	}
	/**
	*	Function to list campaign whose status is ready
	*/
	function ongoing($start=0){
		// $this->output->enable_profiler(true);
		$fetch_conditions_array=array('rec.is_segmentation'=>1,'rec.is_deleted'=>0);		
		//$fetch_conditions_array=array('rec.campaign_status'=>'active','rec.is_segmentation'=>1,'rec.is_deleted'=>0);		
		$campaign_data_array=$this->Campaigns_Model->get_data_campaign_management_ONGOING($fetch_conditions_array,$config['per_page'],$start,'asc','campaign_sheduled',true);
		//echo $this->db->last_query();				
		$i=0;
		foreach($campaign_data_array as $campaign){	
			
			$contacts_in_queue = $this->get_not_email_send_contacts($campaign['campaign_id'],$campaign['member_id']);
			//echo "<br/> ==".$contacts_in_queue."==".$this->db->last_query();
			if($contacts_in_queue <= 0 ){			
				unset($campaign_data_array[$i]);						
			}else{
				
				$rsQueueu = $this->db->query("select count(subscriber_id) tot from `red_email_queue` WHERE `campaign_id` =  '".$campaign['campaign_id']."'");
				$queue_contacts= $rsQueueu->row()->tot;
				$rsQueueu->free_result();
				$statsTable = $this->is_authorized->getStatsTable($campaign['campaign_id']); // get stats table
				$rsStats = $this->db->query("select count(queue_id) tot from `$statsTable` WHERE `campaign_id` =  '".$campaign['campaign_id']."'");			
				$contacts[$campaign['campaign_id']]	= $rsStats->row()->tot +$queue_contacts;	
				//echo "<br/>1=".$campaign['campaign_contacts'].'---'.$contacts[$campaign['campaign_id']]	;
				$rsStats->free_result();
								
				//$contacts[$campaign['campaign_id']] = $campaign['campaign_contacts'];
				$email_not_send_contacts[$campaign['campaign_id']]= $contacts_in_queue;	
			}
		$i++;	
		}		
		// Recieve any messages to be shown, when campaign is added or updated
		$messages=$this->messages->get();
		$logo_link="bandook/dashboard_stat";
		if($_POST['mode']=='search' AND !isset($_POST['btn_cancel'])){
			$contacts_array['username']=$_POST['username'];
		}
		
		//Loads header, users listing  and footer view.
		$this->load->view('bandook/header',array('title'=>'Manage Campaign','logo_link'=>$logo_link));
		$this->load->view('bandook/campaign_list',array('campaigns'=>$campaign_data_array,'messages' =>$messages,'contacts'=>$contacts,'email_not_send_contacts'=>$email_not_send_contacts,'mode'=>'sent','contacts_array'=>$contacts_array));
		$this->load->view('bandook/footer');
	}
	function sent($start=0){
		 
		$fetch_conditions_array=array('rec.campaign_status'=>'active','rec.is_deleted'=>0);
		
		$config['base_url']=base_url().'bandook/campaign/sent';
		$config['total_rows']=$this->Campaigns_Model->get_campaign_count($fetch_conditions_array,true);

		$config['per_page']=20;
		$config['uri_segment']=4;		
		$this->pagination->initialize($config);				
		$paging_links=$this->pagination->create_links();		
		
		$campaign_data_array=$this->Campaigns_Model->get_campaign_data_categorized($fetch_conditions_array, $config['per_page'], $start, 'desc', 'campaign_sheduled', true);
		
		foreach($campaign_data_array as $campaign){			
		
			$rsQueueu = $this->db->query("select count(subscriber_id) tot from `red_email_queue` WHERE `campaign_id` =  '".$campaign['campaign_id']."'");
			$queue_contacts= $rsQueueu->row()->tot;
			$rsQueueu->free_result();
			$statsTable = $this->is_authorized->getStatsTable($campaign['campaign_id']); // get stats table
			$rsStats = $this->db->query("select count(queue_id) tot from `$statsTable` WHERE `campaign_id` =  '".$campaign['campaign_id']."'");			
			$contacts[$campaign['campaign_id']]	= $rsStats->row()->tot +$queue_contacts;	
			$rsStats->free_result();	
			
			$email_not_send_contacts[$campaign['campaign_id']]=$this->get_not_email_send_contacts($campaign['campaign_id'],$campaign['member_id']); 			
		}
		
		// Recieve any messages to be shown, when campaign is added or updated
		$messages=$this->messages->get();
		$logo_link="bandook/dashboard_stat";
		if($_POST['mode']=='search' AND !isset($_POST['btn_cancel'])){
			$contacts_array['username']=$_POST['username'];
		}
		
		//Loads header, users listing  and footer view.
		$this->load->view('bandook/header',array('title'=>'Manage Campaign','logo_link'=>$logo_link));
		$this->load->view('bandook/campaign_list',array('campaigns'=>$campaign_data_array,'paging_links'=>$paging_links,'messages' =>$messages,'contacts'=>$contacts,'email_not_send_contacts'=>$email_not_send_contacts,'mode'=>'sent','contacts_array'=>$contacts_array));
		$this->load->view('bandook/footer');
	}
	/**
	*	Function to list campaign whose want approval
	*/
	
	function approval($start=0){		
		#$fetch_conditions_array=array('rec.campaign_status !='=>'draft','rec.campaign_status !='=>'active','rec.is_deleted'=>0);
		$fetch_conditions_array= "(rec.campaign_status = 'ready' or rec.campaign_status ='active_ready') and rec.is_segmentation = 0 and rec.is_deleted=0";		 
		 
		$config['base_url']=base_url().'bandook/campaign/approval';
		$config['total_rows']=$this->Campaigns_Model->get_campaign_count($fetch_conditions_array,true);
		$config['per_page']=$config['total_rows'];
		$config['uri_segment']=4;
		$this->pagination->initialize($config);		
		$paging_links=$this->pagination->create_links();		
		
		$campaign_data_array=$this->Campaigns_Model->get_campaign_data_categorized($fetch_conditions_array,$config['per_page'],$start,'asc','campaign_sheduled',true);	
		
		foreach($campaign_data_array as $campaign){			
			$rsQueueu = $this->db->query("select count(distinct subscriber_id) tot from `red_email_queue` WHERE `campaign_id` =  '".$campaign['campaign_id']."'");
			$contacts[$campaign['campaign_id']]['total'] = $rsQueueu->row()->tot;
			$rsQueueu->free_result();	
			$cid = $campaign['campaign_id'];
			$mid = $campaign['campaign_created_by'];
			$subscription_list = $campaign['subscription_list'];
			$arr_subscription_list =  array();
			if($subscription_list != NULL and $subscription_list != ''){
				$arr_subscription_list =  explode(',',$subscription_list);
			} 
			$condition_array =  array('subscriber_created_by'=>$mid, 'subscriber_status'=>1, 'is_deleted'=>0, 'sent'=>0);
			$contacts[$cid]['unsent'] = $this->Contact_model->contacts_count_in_lists($mid, $condition_array, $arr_subscription_list);
			//echo $this->db->last_query();
			$contacts[$cid]['sent'] = $contacts[$cid]['total'] - $contacts[$cid]['unsent'];
			$contacts[$cid]['sent'] = ($contacts[$cid]['sent'] < 0) ? 0: $contacts[$cid]['sent'] ;
			$contacts[$cid]['arr_message'] = $this->MessagesModel->assignable_messages($mid);
		}
		if($_POST['mode']=='search' AND !isset($_POST['btn_cancel'])){
			$contacts_array['username']=$_POST['username'];
		}
			
		// Recieve any messages to be shown, when campaign is added or updated
		$messages=$this->messages->get();
		$logo_link="bandook/dashboard_stat";
		 
		//Loads header, users listing  and footer view.
		$this->load->view('bandook/header',array('title'=>'Manage Campaign','logo_link'=>$logo_link));
		$this->load->view('bandook/campaign_list_for_approval',array('campaigns'=>$campaign_data_array, 'messages' =>$messages,'contacts'=>$contacts,'email_not_send_contacts'=>$email_not_send_contacts,'mode'=>'approval','contacts_array'=>$contacts_array));
		$this->load->view('bandook/footer');
	}
	function scheduled($start=0){
		
		//$fetch_conditions_array=array('rec.campaign_status'=>'archived','rec.is_deleted'=>0);
		$fetch_conditions_array=array('rec.campaign_status'=>'archived','rec.is_deleted'=>0,'date_add(rec.campaign_sheduled, INTERVAL (campaign_delay_minute) MINUTE) <= NOW()'=>NULL, 'rec.campaign_sheduled IS NOT NULL '=>NULL);
		$config['base_url']=base_url().'bandook/campaign/scheduled';
		$config['total_rows']=$this->Campaigns_Model->get_campaign_count($fetch_conditions_array,true);
		
		$config['per_page']=20;
		$config['uri_segment']=4;
		$this->pagination->initialize($config);				
		$paging_links=$this->pagination->create_links();		
		
		$campaign_data_array=$this->Campaigns_Model->get_campaign_data_categorized($fetch_conditions_array,$config['per_page'],$start,'asc','campaign_sheduled',true);		
		foreach($campaign_data_array as $campaign){			
			/* $queue_contacts=$this->get_all_queue_contacts($campaign['campaign_id'],$campaign['member_id'],'function');
			$contacts[$campaign['campaign_id']]=$this->get_all_contacts($campaign['campaign_id'],$campaign['member_id'],'function')+$queue_contacts;			
			$email_not_send_contacts[$campaign['campaign_id']]=$this->get_not_email_send_contacts($campaign['campaign_id'],$campaign['member_id']);  */
			$rsQueueu = $this->db->query("select count(subscriber_id) tot from `red_email_queue` WHERE `campaign_id` =  '".$campaign['campaign_id']."'");
			$queue_contacts= $rsQueueu->row()->tot;
			$rsQueueu->free_result();
			$statsTable = $this->is_authorized->getStatsTable($campaign['campaign_id']); // get stats table
			$rsStats = $this->db->query("select count(queue_id) tot from `$statsTable` WHERE `campaign_id` =  '".$campaign['campaign_id']."'");			
			$contacts[$campaign['campaign_id']]	= $rsStats->row()->tot +$queue_contacts;	
			$rsStats->free_result();	
			
			$email_not_send_contacts[$campaign['campaign_id']]=$this->get_not_email_send_contacts($campaign['campaign_id'],$campaign['member_id']); 
		}
		if($_POST['mode']=='search' AND !isset($_POST['btn_cancel'])){
			$contacts_array['username']=$_POST['username'];
		}
		$confg_arr = $this->ConfigurationModel->get_site_configuration_data_as_array();
		$campaign_in_progress = $confg_arr['campaign_under_progress'];
		// Recieve any messages to be shown, when campaign is added or updated
		$messages=$this->messages->get();
		$logo_link="bandook/dashboard_stat";
		#Get shoreten url 
		$shorten_url=get_shorten_url();
		//Loads header, users listing  and footer view.
		$this->load->view('bandook/header',array('title'=>'Manage Campaign','logo_link'=>$logo_link));
		$this->load->view('bandook/campaign_list',array('campaigns'=>$campaign_data_array, 'campaign_in_progress'=>$campaign_in_progress, 'paging_links'=>$paging_links,'messages' =>$messages,'contacts'=>$contacts,'email_not_send_contacts'=>$email_not_send_contacts,'mode'=>'scheduled','contacts_array'=>$contacts_array,'shorten_url'=>$shorten_url));
		$this->load->view('bandook/footer');
	}
	/**
		Function draft is to display list of Draft campaigns
	*/
	function draft($start=0){
		
		$fetch_conditions_array=array('rec.campaign_status'=>'draft','rec.is_deleted'=>0);
		// Define config parameters for paging like base url, total rows and record per page.
		$config['base_url']=base_url().'bandook/campaign/draft';
		$config['total_rows']=$this->Campaigns_Model->get_campaign_count($fetch_conditions_array,true);
		$config['per_page']=20;
		$config['uri_segment']=4;
		
		// Initialize paging with above parameters
		$this->pagination->initialize($config);		
		//Create paging links
		$paging_links=$this->pagination->create_links();		
		$campaign_data_array=$this->Campaigns_Model->get_campaign_data_categorized($fetch_conditions_array,$config['per_page'],$start,'desc','campaign_date_added',true);
		
		foreach($campaign_data_array as $campaign){			
			$contacts[$campaign['campaign_id']]= 0;			
			$email_not_send_contacts[$campaign['campaign_id']]= 0;
		}
		if($_POST['mode']=='search' AND !isset($_POST['btn_cancel'])){
			$contacts_array['username']=$_POST['username'];
		}
		// Recieve any messages to be shown, when campaign is added or updated
		$messages=$this->messages->get();
		$logo_link="bandook/dashboard_stat";
		#Get shoreten url 
		$shorten_url=get_shorten_url();
		//Loads header, users listing  and footer view.
		$this->load->view('bandook/header',array('title'=>'Manage Campaign','logo_link'=>$logo_link));
		$this->load->view('bandook/campaign_list',array('campaigns'=>$campaign_data_array,'paging_links'=>$paging_links,'messages' =>$messages,'contacts'=>$contacts,'email_not_send_contacts'=>$email_not_send_contacts,'mode'=>'draft','contacts_array'=>$contacts_array,'shorten_url'=>$shorten_url));
		$this->load->view('bandook/footer');
	}
	
	/**
	*	Function to update campaign to draft
	*/
	function draftIt($cid,$mode){		
		$this->Emailreport_Model->delete_emailqueue(array('campaign_id'=>$cid));			
		$this->Campaign_Model->update_campaign(array('campaign_status'=>'draft','campaign_status_show'=>'1','is_segmentation'=>0,'number_of_contacts'=>0), array('campaign_id'=>$cid));
		
		$this->messages->add('Campaign drafted successfully', 'success');
		redirect("bandook/campaign/$mode");		
	}
	
	/**
	*	Function to update campaign to archived
	*/
	function archiveIt($cid,$mode){		
		$this->Emailreport_Model->delete_emailqueue(array('campaign_id'=>$cid));			
		$this->Campaign_Model->update_campaign(array('campaign_status'=>'active','campaign_status_show'=>'5','is_segmentation'=>0,'number_of_contacts'=>0), array('campaign_id'=>$cid));
		
		$this->messages->add('Campaign archived successfully', 'success');
		redirect("bandook/campaign/$mode");		
	}
	/**
	*	Function to update schedule-date-time of a campaign
	*/
	function update_schedule($cid,$newDt){
		$newDt = urldecode($newDt);
		$this->db->query("update red_email_campaigns set campaign_sheduled= '$newDt', email_send_date='$newDt'  WHERE `campaign_id` ='$cid'");
		//echo $this->db->last_query();
		//$this->Campaign_Model->update_campaign(array('campaign_sheduled'=>"$newDt",'campaign_sheduled'=>"$newDt"), array('campaign_id'=>$cid));
		
		echo 'ok';	
	}
	/**
	*	Function to update campaign status
	*/
	function edit($mode="",$campaign_id=0,$status=0){
		if($status==1){
			$this->Campaign_Model->update_campaign(array('campaign_status'=>'archived'), array('campaign_id'=>$campaign_id));						
			$this->messages->add('Campaign approved successfully', 'success');
		}else{
			$this->Campaign_Model->update_campaign(array('campaign_status'=>'disallow','campaign_status_show'=>'3'), array('campaign_id'=>$campaign_id));			
			$this->Emailreport_Model->delete_emailqueue(array('campaign_id'=>$campaign_id));
			$comment = $this->input->post('disallow_comment');	
			$this->campaign_notification($campaign_id, $comment);
			$this->messages->add('Campaign disallowed successfully', 'success');
		}
		redirect("bandook/campaign/approval");
	}
	function disallow_confirm($cid=0){
		$rsDisallowedMsg = $this->db->query("select * from red_disallowed_msg");		
		foreach($rsDisallowedMsg->result() as $row){
			$disallowedmsg[$row->id] = $row->msg_code;
		}
		$this->load->view('bandook/disallow_confirm',array('cid'=>$cid, 'disallowedmsg'=>$disallowedmsg)); 
	}
	function ajaxMsg($mid=0){
		if($mid > 0){
			echo $this->db->query("select msg_detail from red_disallowed_msg where id='$mid'")->row()->msg_detail;	
		}else{
			echo'';
		}
	}
	/**
		Function delete to remove the campaign
	**/
	function delete($mode="",$id=0){
	$today = date('Y-m-d');
		$this->Campaign_Model->update_campaign(array('is_deleted'=>1), array('campaign_id'=>$id));

		//$this->Campaigns_Model->delete_campaign($id);
		//$this->Campaigns_Model->delete_campaign_stat($id);
		$this->messages->add('Campaign deleted successfully', 'success');
		
		if($mode=="stat"){
			redirect('bandook/dashboard_stat/sent_campaign');			
		}else{
			redirect('bandook/campaign/'.$mode);
		}
	}
	function view($id=0){
		// Recieve any messages to be shown, when campaign is added or updated

		$messages=$this->messages->get();		
		
		//Fetch campaign data from database by campaign ID
		$fetch_condiotions_array=array(	'campaign_id'=>$id);
		// Fetches campaign data from database
		$campaign_array=$this->Campaign_Model->get_campaign_data($fetch_condiotions_array);
		
		//Redirects user to listing page if user have not created this campaign or campaign does not exists
		if(!count($campaign_array)){
			redirect('promotions');
		}
		// Prepare array to send to view
		$campaign_data=array('campaign_title'=>$campaign_array[0]['campaign_title'],
		'campaign_template_option'=>$campaign_array[0]['campaign_template_option'],
		'campaign_content'=>$campaign_array[0]['campaign_content'],		 
		'campaign_status'=>$campaign_array[0]['campaign_status']
		);
		$campaign_data['campaign_content']=str_replace('tabindex="-1"','',$campaign_data['campaign_content']);
		// get page id
		$loaded_page=$this->Page_Model->get_page_data(array('site_id'=>$id,'is_autoresponder'=>0));
		$block_outer_color=$this->Campaign_Model->get_background_color_content_data(array('red_background_color_page_id'=>$loaded_page[0]['id'],'red_background_color_block_name'=>'outer-background'));

		if(count($block_outer_color)<=0){
			//get themes color from database
			$theme_color= $this->Campaign_Model->get_theme_colors(array('id'=>$campaign_array[0]['campaign_color_theme_id']));			
			$campaign_data['outer_background']=$theme_color[0]['outer_bg'];
		}else{
			$color_arr=explode("background::",$block_outer_color[0]['red_background_color_block_content']);
			$campaign_data['outer_background']=$color_arr[1];
		}
		//Assign messages to array to be send to view.

		$campaign_data['messages'] =$messages;

		//Loads header, campaign and footer view.
		$this->load->view('userboard/campaign_link',$campaign_data);
	}	

	/**
		function to get all contacts
	**/
	function get_all_contacts($id=0,$user_id=0,$type=""){
				
		// Get Contacts
		$email_subscribers=$this->Emailreport_Model->get_emailreport_data(array('campaign_id'=>$id));
		if($type=="function"){
			return count($email_subscribers);
		}else{
			$logo_link="bandook/dashboard_stat";
			$this->load->view('bandook/header',array('title'=>'Manage Campaign','logo_link'=>$logo_link));
			$this->load->view('bandook/subscriber_list',array('email_subscribers'=>$email_subscribers));
			$this->load->view('bandook/footer');
		}
	}
	/**
		function to get all queue contacts
	**/
	function get_all_queue_contacts($id=0,$user_id=0,$type=""){
				
		$fetch_condiotions_array=array('campaign_id'=>$id);
		
		$email_subscribers=$this->Emailreport_Model->get_emailqueue_data($fetch_condiotions_array);
		if($type=="function"){
			return count($email_subscribers);
		}else{
			$logo_link="bandook/dashboard_stat";
			$this->load->view('bandook/header',array('title'=>'Manage Campaign','logo_link'=>$logo_link));
			$this->load->view('bandook/subscriber_list',array('email_subscribers'=>$email_subscribers));
			$this->load->view('bandook/footer');
		}
	}
	/**
		Function get_not_email_receive_contacts to get contacts who have not receive email
	**/
	function get_not_email_send_contacts($id=0,$user_id=0){				 
		$rsCountQueue = $this->db->query("Select count(subscriber_id) queue from `red_email_queue` where `campaign_id`='$id' and `email_sent`=0");
		$in_queue_subscribers = $rsCountQueue->row()->queue;
		$rsCountQueue->free_result();
		return $in_queue_subscribers;
	}
	/**
		function to get  contacts using paging
	**/
	function get_contacts($id=0,$user_id=0,$start=0){
		#####################################################
		# Check Campaign is in queue or not					#
		#####################################################
		$fetch_conditions_array=array('rec.campaign_id'=>$id);
		$campaign_info=$this->Campaign_Model->get_campaign_data($fetch_conditions_array);
		// Load emailreport model class which handles database interaction
		$this->load->model('bandook/Emailreport_Model');
			$fetch_condiotions_array=array('campaign_id'=>$id);
			// Get Contacts
			if($campaign_info[0]['campaign_status']=="active"){
				$email_subscribers=$this->Emailreport_Model->get_emailreport_data($fetch_condiotions_array,true);	
			}else{
				$email_subscribers=$this->Emailreport_Model->get_emailqueue_data($fetch_condiotions_array,true);
			}		
		// Define config parameters for paging like base url, total rows and record per page.
		$config['base_url']=base_url().'bandook/campaign/get_contacts/'.$id."/".$user_id;
		$config['total_rows']=count($email_subscribers);
		$config['per_page']=20;
		$config['uri_segment']=6;

		// Initialize paging with above parameters
		$this->pagination->initialize($config);
		
		//Create paging inks
		$paging_links=$this->pagination->create_links();	
		// Get Contacts
		if($campaign_info[0]['campaign_status']=="active"){
			$email_subscribers=$this->Emailreport_Model->get_emailreport_data($fetch_condiotions_array,true,$config['per_page'],$start,true);
		}else{
			$email_subscribers=$this->Emailreport_Model->get_emailqueue_data($fetch_condiotions_array,true,$config['per_page'],$start,true);
		}
		$i=0;

		$contacts_array=array();
		if($_POST['mode']=='search' AND !isset($_POST['btn_cancel'])){
			$contacts_array['keyword']=$_POST['keyword'];
			$contacts_array['subscriber_email_address']=$_POST['subscriber_email_address'];
			$contacts_array['subscriber_name']=$_POST['subscriber_name'];
		}
		$logo_link="bandook/dashboard_stat";
		$this->load->view('bandook/header',array('title'=>'Contacts','logo_link'=>$logo_link));
		$this->load->view('bandook/subscriber_list',array('email_subscribers'=>$email_subscribers,'paging_links'=>$paging_links,'id'=>$id,'user_id'=>$user_id,'contacts'=>$contacts_array));
		$this->load->view('bandook/footer');
	}
	
	/**
		Function to send campaign  notification email to member
	**/
	function campaign_notification($campaign_id=0, $msg =''){		
		$fetch_conditions_array=array('campaign_id'=>$campaign_id,'rec.is_deleted'=>0);
		$campaign=$this->Campaign_Model->get_campaign_data($fetch_conditions_array,$config['per_page']);
		// Fetch user data from database
		$user_data_array=$this->UserModel->get_user_data(array('member_id'=>$campaign[0]['campaign_created_by']));	
		$user_name=($user_data_array[0]['first_name'])? $user_data_array[0]['first_name'] : $user_data_array[0]['member_username'];
		$campaign_view_link= CAMPAIGN_DOMAIN.'c/'.$this->is_authorized->encryptor('encrypt',$campaign_id);
		$user_info=array($user_name,$campaign[0]['campaign_title'],$campaign_view_link, $msg);				
		create_transactional_notification("campaign_suspended_notification",$user_info,$user_data_array[0]['email_address']);
	}
	/**
		Function campaign_segmentation to send campaign in segments
	**/
	
	function curl_last_url( $url = '', $maxredirect = 5) {  
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (X11; Linux x86_64; rv:21.0) Gecko/20100101 Firefox/21.0"); // Necessary. The server checks for a valid User-Agent.
	$mr = $maxredirect === null ? 5 : intval($maxredirect); 
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false); 
		if ($mr > 0) { 
			$mr;
			$newurl = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL); 

			$rch = curl_copy_handle($ch); 
			curl_setopt($rch, CURLOPT_HEADER, true); 
			curl_setopt($rch, CURLOPT_USERAGENT, "Mozilla/5.0 (X11; Linux x86_64; rv:21.0) Gecko/20100101 Firefox/21.0"); // Necessary. The server checks for a valid User-Agent.
			curl_setopt($rch, CURLOPT_NOBODY, true); 
			curl_setopt($rch, CURLOPT_FORBID_REUSE, false); 
			curl_setopt($rch, CURLOPT_RETURNTRANSFER, true); 
			do { 
				curl_setopt($rch, CURLOPT_URL, $newurl); 
				$header = curl_exec($rch); 
				if (curl_errno($rch)) { 
					$code = 0; 
				} else { 
					$code = curl_getinfo($rch, CURLINFO_HTTP_CODE); 					 
					if ($code == 301 || $code == 302) { 
						preg_match('/Location:(.*?)\n/', $header, $matches); 
						$newurl = trim(array_pop($matches)); 
					} else { 
						$code = 0; 
					} 
				} 
			} while ($code && --$mr); 
			curl_close($rch); 
			if (!$mr) { 
				if ($maxredirect === null) { 
					trigger_error('Too many redirects. When following redirects, libcurl hit the maximum amount.', E_USER_WARNING); 
				} else { 
					$maxredirect = 0; 
				} 
				return false; 
			} 
			curl_setopt($ch, CURLOPT_URL, $newurl); 
		} 
		return $newurl; 
	}
	
	function campaign_segmentation($campaign_id=0,$member_id=0,$mode=""){			
		$is_segmented = false;
		$segment_size	= '';
		$stats_table_id	= STATS_TABLE_IN_USE; // stats table selection for campaign
		$subscriber_count=$this->get_not_email_send_contacts($campaign_id,$member_id);	
			
		$campaign_array=$this->Campaign_Model->get_campaign_data(array(	'campaign_id'=>$campaign_id));
	
		
		$campaign_data=array('email_subject'=>$campaign_array[0]['email_subject'], 'sender_email'=>$campaign_array[0]['sender_email'], 'sender_name'=>$campaign_array[0]['sender_name'], 'campaign_delay_minute'=>$campaign_array[0]['campaign_delay_minute'], 
'pmta_priority'=>$campaign_array[0]['pmta_priority'], 
'is_dmarc'=>$campaign_array[0]['is_dmarc'], 
'dmarc_from_email'=>$campaign_array[0]['dmarc_from_email'], 
'pipeline'=>$campaign_array[0]['pipeline'], 
'campaign_template_option'=>$campaign_array[0]['campaign_template_option'], 
'spamscore'=>$campaign_array[0]['spamscore'], 'spamreport'=>$campaign_array[0]['spamreport']);
		
		$contacts_total = $campaign_array[0]['campaign_contacts'];
		$subscription_list = $campaign_array[0]['subscription_list'];
		$actual_campaign_sheduled = $campaign_array[0]['campaign_sheduled'];
		$update_campaign_status_show = ($campaign_array[0]['campaign_status_show'] < 4)? 4 : 5;
		$arr_subscription_list =  array();
		if($subscription_list != NULL and $subscription_list != ''){
			$arr_subscription_list =  explode(',',$subscription_list);
		}
		
		$condition_array =  array('subscriber_created_by'=>$member_id, 'subscriber_status'=>1, 'is_deleted'=>0, 'sent'=>0);
		$campaign_data['unsent'] = $this->Contact_model->contacts_count_in_lists($member_id, $condition_array, $arr_subscription_list);

		$campaign_data['sent'] = $contacts_total - $campaign_data['unsent'];
		$campaign_data['sent'] = ($campaign_data['sent'] < 0 )? 0 : $campaign_data['sent'] ;	
			
		// UPVote: Starts
		$rsCampaignUpvote = $this->db->query("Select add_open from red_email_campaigns_detail where campaign_id='$campaign_id'");
		if($rsCampaignUpvote->num_rows() > 0){
			$campaign_data['add_open']		= $rsCampaignUpvote->row()->add_open;		
		}else{
			$rsMember = $this->db->query("select member_username, dmarc_from_email, campaign_approval_notes, max_upvote from red_members where member_id='$member_id'");
			$approval_notes = $rsMember->row()->campaign_approval_notes;
			$campaign_data['add_open']			= $rsMember->row()->max_upvote;	
			$campaign_data['member_username']	= $rsMember->row()->member_username;	
			$campaign_data['dmarc_from_email']	= (trim($campaign_data['dmarc_from_email'])!='')?$campaign_data['dmarc_from_email']:$rsMember->row()->dmarc_from_email;	
			$rsMember->free_result();
		}
		$rsCampaignUpvote->free_result();
		// UPVote: Ends
		$rsGetSegment = $this->db->query("select * from `red_ongoing_segmentation` where `campaign_id`='$campaign_id'");
		if($rsGetSegment->num_rows() > 0){
			$is_segmented = true;
			$segment_size = $rsGetSegment->row()->segment_size;		
			$segment_interval = $rsGetSegment->row()->segment_interval;		
		}
		$rsGetSegment->free_result();

		// To check form is submittted for saving template
		if($this->input->post('action')=='submit'){	
			$add_open = $this->input->post('add_open');
			if($add_open > 0){
				$this->db->query("insert into red_email_campaigns_detail set campaign_id='$campaign_id',add_open='$add_open' ON DUPLICATE KEY UPDATE add_open='$add_open'");		
			}
			$approval_notes = $this->input->post('approval_notes');
			$this->db->query("update red_members set campaign_approval_notes = '$approval_notes'  where `member_id`='$member_id'");				
			
			$delay = (intval($this->input->post('add_delay')) > 0)?	intval($this->input->post('add_delay')) : 0;
			$delay_till_now = (strtotime(gmdate("Y-m-d H:i:s", time()))  -  strtotime($actual_campaign_sheduled));
			$delay_till_now_minutes = ($delay_till_now > 0 )? $delay_till_now/60 : 0;		
			
			$delay = $delay + intval($delay_till_now_minutes) ;
			//echo "<br/>Delay=".$delay;
			//echo "<br/>now=".gmdate("Y-m-d H:i:s", time());
			//echo "<br/>actual_campaign_sheduled=".$actual_campaign_sheduled;
			//echo "<br/>delay_till_now=".$delay_till_now;
			//echo "<br/>delay_till_now_minutes=".$delay_till_now_minutes;
			//echo "<br/>Delay=".$delay;
			//exit;
			$updateCampaignArr = array();
			$updateCampaignArr['stats_table_id'] = $stats_table_id;
			$updateCampaignArr['campaign_delay_minute'] = $delay;
			$updateCampaignArr['campaign_status_show'] = $update_campaign_status_show;
			$updateCampaignArr['pmta_priority'] = $this->input->post('pmta_priority');
			$updateCampaignArr['is_dmarc'] = $this->input->post('is_dmarc');
			$updateCampaignArr['dmarc_from_email'] = $this->input->post('dmarc_from_email');	
			
			if($this->input->post('all')=='All'){
				if($update_campaign_status_show == 4){
					$updateCampaignArr['email_send_date']=date("Y-m-d H:i:s");					
					$updateCampaignArr['is_segmentation'] = 0;
					$updateCampaignArr['number_of_contacts'] = 0;
				}else{					
					$updateCampaignArr['is_segmentation'] = 0;
					$updateCampaignArr['number_of_contacts'] = 0;								
				}
				$this->Campaign_Model->update_campaign($updateCampaignArr, array('campaign_id'=>$campaign_id));				
				redirect("bandook/campaign/edit/$mode/$campaign_id/1");
				exit;
			}elseif($this->input->post('add_number_of_contacts')==1){
				// Validation rules are applied
				$this->form_validation->set_rules('number_of_contacts', 'Number of contacts', "required|integer|callback_number_of_contacts_check|trim");
				// To check form is validated
				if($this->form_validation->run()==true){
					$segment_size = $this->input->post('number_of_contacts');
					$segment_interval = $this->input->post('segment_interval');
					if($this->input->post('automate')=='automate'){						
						$updateCampaignArr['is_segmentation'] = 1;
						$updateCampaignArr['number_of_contacts'] = $segment_size;
						$updateCampaignArr['segment_interval'] = $segment_interval;
						$this->Campaign_Model->update_campaign($updateCampaignArr,array('campaign_id'=>$campaign_id));
						$this->db->query("insert into `red_ongoing_segmentation` set campaign_id='$campaign_id', segment_size='$segment_size', segment_interval='$segment_interval' $last_released_on ON DUPLICATE KEY UPDATE `segment_size`='$segment_size', `segment_interval`='$segment_interval' $last_released_on");
						$this->messages->add("Campaign [$campaign_id] segmented with segment-size $segment_size & interval $segment_interval minutes successfully", 'success');
						redirect("bandook/campaign/approval");
						exit;
					}else{
						$updateCampaignArr['is_segmentation'] = 1;
						$updateCampaignArr['number_of_contacts'] = $segment_size;
						$this->Campaign_Model->update_campaign($updateCampaignArr,array('campaign_id'=>$campaign_id));						
						$this->db->query("delete from `red_ongoing_segmentation` where `campaign_id` = '$campaign_id'");
						redirect("bandook/campaign/edit/$mode/$campaign_id/1");
						exit;
					}
				}
			}
		}
		
		$rs_member_message=$this->db->query("select t2.message_name, t2.message_status from red_member_message t1 inner join red_messages t2 on t1.message_id=t2.message_id where t1.member_id='$member_id'");
		$member_message_array = $rs_member_message->result_array();
		$rs_member_message->free_result();
		
		$message_data_array=$this->MessagesModel->assignable_messages($member_id);	
		$logo_link="bandook/dashboard_stat";
		$this->load->view('bandook/header',array('title'=>'Campaign Segmentation','logo_link'=>$logo_link));
		$this->load->view('bandook/campaign_segmentation',array('campaign_id'=>$campaign_id, 'message_list'=>$message_data_array, 'member_message_list'=>$member_message_array, 'member_id'=>$member_id, 'subscriber_count'=>$subscriber_count, 'mode'=>$mode,'is_segmented'=>$is_segmented, 'approval_notes'=>$approval_notes, 'segment_size'=>$segment_size, 'segment_interval'=>$segment_interval, 'campaign_data'=>$campaign_data));
		$this->load->view('bandook/footer');
	}
	
	
	
	function saveNoteOnly(){
		$approval_notes = $this->input->post('approval_notes');	
		$member_id		= $this->input->post('member_id');	
//		$sql = "update red_members set campaign_approval_notes = IFNULL(concat(replace(campaign_approval_notes, '$approval_notes','') , '$approval_notes' ), '$approval_notes')  where `member_id`='$member_id'";
		$this->db->query("update red_members set campaign_approval_notes = '$approval_notes' where `member_id`='$member_id'");
		echo 'Note Saved';
	}
	
	function attachMessage($cid=0, $memid=0, $msgid=0){		
		$this->UserModel->attachMessage(array('member_id'=>$memid, 'message_id'=>$msgid));
		// START- Send member-message notification-email
		$user_data_array=$this->UserModel->get_user_data(array('member_id'=>$memid));				
		$user_name = ($user_data_array[0]['first_name'] != '')? $user_data_array[0]['first_name'] : $user_data_array[0]['member_username'] ;
		 
		$message_data = $this->MessagesModel->read_messages(array( 'message_id'=>$msgid));
		$message_mail_subject 	= $message_data[0]['email_subject'];
		$message_mail_body		= $message_data[0]['email_body'];
		$message_mail_body = str_replace('[FIRSTNAME_OR_USERNAME]',$user_name , $message_mail_body);
		send_member_message_email($user_data_array[0]['email_address'], 'support@boldinbox.com', 'BoldInbox Support', $message_mail_subject, nl2br($message_mail_body), $message_mail_body);				
		// END- Send member-message notification-email
		if($cid > 0){
			$this->draftIt($cid,'approval');
			exit;
		}else{
			$this->messages->add('Message attached and mail sent successfully', 'success');
			redirect("bandook/users_manage/users_list/");
			exit;
		}	
	}
	/**
		Function number_of_contacts_check 
	**/
	function number_of_contacts_check($value=0){
		$campaign_id=$this->input->post('campaign_id');
		$member_id=$this->input->post('member_id');		
		$subscriber_count=$this->get_not_email_send_contacts($campaign_id,$member_id);
		if($value>$subscriber_count){
			$this->form_validation->set_message('number_of_contacts_check', "%s can not be more than $subscriber_count contacts.");
			return false;
		}else{
			return true;
		}
	}
	function getUserId($uname){
		$sqlUid = "select member_id from red_members where member_username='$uname'";
		$rsUid = $this->db->query($sqlUid);
		if($rsUid->num_rows()>0)
		return $rsUid->row()->member_id;
		else
		return 0;
	}	
	function ipr($cid=0, $mode=''){
		
		$whereClause = " and `campaign_id`='$cid'";
		$rsPipeline = $this->db->query("select ifnull(`pipeline`,'') as pipeline from red_email_campaigns where campaign_id='$cid'");	
		$pipeline = $rsPipeline->row()->pipeline;
		$rsPipeline->free_result();
		$statsTable = $this->is_authorized->getStatsTable($cid); // get stats table
		$sqlTotalForCampaign = "select sum(email_sent) as sent, sum(IF((email_sent= 1 and not_sent_reason=0),1,0)) as released, sum(email_delivered) as delivered,sum(`email_track_read`) as opened,sum(`email_track_click`) as clicks, sum(`email_track_bounce`) as bounced,sum(`email_track_complaint`) as complaints,sum(`email_track_unsubscribes`) as unsubscribes from $statsTable where 1 $whereClause  order by sent";
		$rsTotalForCampaign = $this->db->query($sqlTotalForCampaign);
		if($rsTotalForCampaign->num_rows()>0){
			$intTotalSent_0 = $rsTotalForCampaign->row()->sent;			
			$intTotalSent = $rsTotalForCampaign->row()->delivered;
			// check for freezed campaigns starts
			if(is_null($intTotalSent_0)){
				$strIPRBody = $this->db->query("select ipr from red_email_track_freezed where campaign_id='$cid'")->row()->ipr;
				die('<div style="padding:20px;">'.$strIPRBody.'</div>');			
			}			
			// check for freezed campaigns ends
			if($intTotalSent_0 <= 0 )die('<div style="padding:20px;width:200px;">No records found!</div>');
		$strTblFooter = '<tr><td>TOTAL:</td><td>'.$rsTotalForCampaign->row()->sent.'</td><td>'.$rsTotalForCampaign->row()->released.'</td><td>'.$rsTotalForCampaign->row()->delivered.'('.number_format(($rsTotalForCampaign->row()->delivered)*100/$rsTotalForCampaign->row()->released,2).'%)</td><td>'.$rsTotalForCampaign->row()->opened.'</td><td>'.$rsTotalForCampaign->row()->clicks.'</td><td>'.$rsTotalForCampaign->row()->bounced.'</td><td>'.$rsTotalForCampaign->row()->complaints.'</td><td>'.$rsTotalForCampaign->row()->unsubscribes.'</td></tr>';
		
		}
		 // domain wise counter
		$sqlDomainwiseForCampaign ="select sum(email_sent) as sent, sum(IF((email_sent= 1 and not_sent_reason=0),1,0)) as released, sum(email_delivered) as delivered,sum(`email_track_read`) as opened,sum(`email_track_click`) as clicks,sum(`email_track_bounce`) as bounced,sum(`email_track_complaint`) as complaints,sum(`email_track_unsubscribes`) as unsubscribes, SUBSTRING(subscriber_email_address, instr(subscriber_email_address, '@'), LENGTH(subscriber_email_address)) AS domainname from $statsTable where 1 $whereClause group by domainname having count(queue_id)>50 order by sent desc";
		$rsDomainwiseForCampaign = $this->db->query($sqlDomainwiseForCampaign);
		if($rsDomainwiseForCampaign->num_rows()>0){
			foreach($rsDomainwiseForCampaign->result() as $row){				
				$strTblBody .= '<tr><td>'.$row->domainname.'</td>';
				$strTblBody .= '<td>'.$row->sent.'('.number_format(($row->sent)*100/$intTotalSent_0,2).'%)</td>';
				$strTblBody .= '<td>'.$row->released.'('.number_format(($row->released)*100/$intTotalSent_0,2).'%)</td>';
				if($row->released > 0){
				$strTblBody .= '<td>'.$row->delivered.'('.number_format(($row->delivered)*100/$row->released,2).'%)</td>';
				}else{
				$strTblBody .= '<td>'.$row->delivered.'</td>';
				}
				$row->delivered = ($row->delivered == 0)?1:$row->delivered;
				$strTblBody .= '<td>'.$row->opened.'('.number_format(($row->opened)*100/($row->delivered),2).'%)</td>';
				$strTblBody .= '<td>'.$row->clicks.'('.number_format(($row->clicks)*100/($row->delivered),2).'%)</td>';
				$strTblBody .= '<td>'.$row->bounced.'('.number_format(($row->bounced)*100/($row->delivered),2).'%)</td>';
				$strTblBody .= '<td>'.$row->complaints.'('.number_format(($row->complaints)*100/($row->delivered),2).'%)</td>';
				$strTblBody .= '<td>'.$row->unsubscribes.'('.number_format(($row->unsubscribes)*100/($row->delivered),2).'%)</td></tr>';		
			}
		}
		if($pipeline !='')$pipeline = "<span style='float:right;margin:10px 0;'><b>Pipeline:</b> $pipeline</span>";	
		$strIPR =  '<div style="padding:20px;">'.$pipeline.'		
		<table cellspacing="0" cellpadding="4" border="1"><tr><th>Domain</th><th>Sent</th><th>Released</th><th>Delivered</th><th>Opened</th><th>Clicks</th><th>Bounced</th><th>Complaint</th><th>Unsubscribed</th>' .$strTblBody . $strTblFooter.'</table></div>';
		if($mode=='save')return $strIPR; else echo $strIPR;
	} 	
	
	 
	function global_fbl($interval=0){	
		$arrCampaignFBL = array();	
		$arrOtherFBL = array();	
			
		$rsFBL = $this->db->query("select * from red_global_fbl  where date_format(added_on, '%Y-%m-%d')= DATE_ADD(CURDATE(), INTERVAL (0-$interval) DAY) ");
		$totalFBLs = $rsFBL->num_rows();
		if($totalFBLs > 0){
			foreach($rsFBL->result() as $rowFBL){
				if($rowFBL->campaign_id > 0){
					$arrCampaignFBL[] 	= $rowFBL->email_address;							
				}else{
					$arrOtherFBL[] 		= $rowFBL->email_address;							
				}
			}
		}	
		$rsFBL->free_result();
		$logo_link="bandook/dashboard_stat";
		echo $this->load->view('bandook/header',array('title'=>'Campaign Segmentation','logo_link'=>$logo_link), true);
		echo '<div style="padding:20px;"><div style="padding:20px 0px;">';
		echo "<b>Global FBL on UTC Date:</b> ".date( 'Y-m-d', strtotime( date("Y-m-d", gmmktime())  . " " .(0-$interval) ."  day" ) )." for total $totalFBLs ";
		echo "<span style='float:right;font-size:12px;'><a href='/bandook/campaign/global_fbl/".(1+$interval)."'>Show data for ".date( 'Y-m-d', strtotime( date("Y-m-d", gmmktime()) . " " .(-1-$interval) ."  day" ) )."</a></span>";
		echo '</div>';
		echo '<table cellspacing="1" cellpadding="4" border="0" width="100%" bgcolor="#ebebeb"><tr bgcolor="#ffffff"><th width="70%">Campaign FBLs</th><th>Other FBLs</th></tr>' .'<tr  bgcolor="#ffffff"><td>'.implode(', ',$arrCampaignFBL).'</td><td>'.implode(', ',$arrOtherFBL).'</td></tr></table></div>';	
		echo $this->load->view('bandook/footer', true);
	}
		
	function showStats($cid=0){			
		$campaign_data=$this->Campaigns_Model->get_campaign_data_for_sentmails_new(array('c.campaign_id'=>$cid),'email_send_date',10,0);
			 
		foreach($campaign_data as $campaign){
			$total_sent_emails=$this->Emailreport_Model->get_emailreport_count(array('campaign_id'=>$campaign['campaign_id'],'email_sent'=>1	));								
			$email_track_released = $campaign['email_track_released']; // total released email
			$email_track_delivered = $campaign['email_track_delivered']; // total delivered email
			$email_track_read =$campaign['email_track_read']; // total read emails	
			$email_track_read_percent = ($email_track_delivered > 0) ? round(100 * ($email_track_read/$email_track_delivered), 2) : 0;
			
			$email_track_bounce =$campaign['email_track_bounce']; // total bounce emails			
			$email_track_bounce_percent = ($email_track_delivered > 0) ? round(100 * ($email_track_bounce/$email_track_delivered), 2) : 0;
			
			$total_unread_emails = $email_track_delivered  - $email_track_read - $email_track_bounce; // total unread emails
			$total_unread_emails_percent = ($email_track_delivered > 0) ? round(100 * ($total_unread_emails/$email_track_delivered), 2) : 0;	
			
			$email_track_click =$campaign['email_track_click']; // total click emails			
			$email_track_click_percent = ($email_track_delivered > 0) ? round(100 * ($email_track_click/$email_track_delivered), 2) : 0;
			
			$email_track_forward =$campaign['email_track_forward']; // total forward emails
			$email_track_forward_percent = ($email_track_delivered > 0) ? round(100 * ($email_track_forward/$email_track_delivered), 2) : 0;	
			
			$email_track_unsubscribes =$campaign['email_track_unsubscribes']; // total unsubscribes email
			$email_track_unsubscribes_percent = ($email_track_delivered > 0) ? round(100 * ($email_track_unsubscribes/$email_track_delivered), 2) : 0;
			
			$email_track_spam =$campaign['email_track_spam']; // total bounce emails				 
			$email_track_spam_percent = ($email_track_delivered > 0) ? round(100 * ($email_track_spam/$email_track_delivered), 2) : 0;			
		}		
		echo '<div style="padding:20px;">'.
					'<table cellspacing="0" cellpadding="4" border="1">
					<tr><th>Sent</th><th>Released</th><th>Delivered</th><th>Opened</th><th>Un-opened</th><th>Clicks</th><th>Forwards</th><th>Unsubscribed</th><th>Bounced</th><th>Complaint</th>' .
					"<tr><td>$total_sent_emails</td><td>$email_track_released</td><td>$email_track_delivered</td><td>$email_track_read ($email_track_read_percent)%</td>
					<td>$total_unread_emails ($total_unread_emails_percent)%</td><td>$email_track_click ($email_track_click_percent)%</td>
					<td>$email_track_forward ($email_track_forward_percent)%</td><td>$email_track_unsubscribes ($email_track_unsubscribes_percent)%</td>
					<td>$email_track_bounce ($email_track_bounce_percent)%</td><td>$email_track_spam ($email_track_spam_percent)%</td>" .					 
					'</table></div>';
	}
	function update_vmta($campaign_id=0,$vmta='redrotate'){		
		$this->db->query("update red_email_campaigns set pipeline='$vmta' where campaign_id='$campaign_id'");		
		echo 'Updated';
	}		
}
?>
