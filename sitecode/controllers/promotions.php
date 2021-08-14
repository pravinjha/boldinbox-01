<?php
/**
  *	Controller class for campaigns
  *	It have controller functions for campaign management.
 */
class Promotions extends CI_Controller
{
	/**
	  *	Contructor for controller.
	  *	It checks user session and redirects user if not logged in
	 */
	private $confg_arr = array(); 
	function __construct(){
        parent::__construct();
		
		$this->load->model('ConfigurationModel');
		$this->confg_arr=$this->ConfigurationModel->get_site_configuration_data_as_array();
		if($this->confg_arr['maintenance_mode'] !='no'){
			redirect ("/site_under_maintenance/");
			exit;
		}
		
		
		// check via common model
		if(!$this->is_authorized->check_user())
			redirect('user/index');
		// Create user's folders
		$this->is_authorized->createUserFiles();
		
		// Load libraries, models and helpers
		$this->load->library('upload');			
		$this->load->helper('notification');
		
		
		$this->load->model('userboard/Campaign_Model');
		$this->load->model('userboard/Page_Model');
		$this->load->model('userboard/contact_model');
		$this->load->model('userboard/subscription_Model');
		$this->load->model('userboard/Subscriber_Model');
		$this->load->model('UserModel');
		$this->load->model('userboard/Campaign_Autoresponder_Model');
		$this->load->model('Activity_Model');
		$this->load->helper('htmltotext');
		
		
		$this->output->enable_profiler(false);

		// Get absolute path for uploading
		$user_dir = $this->session->userdata('member_id') % 1000;
		$this->upload_path= $this->config->item('user_public').$user_dir .'/'.$this->session->userdata('member_id');
		// Force SSL
		force_ssl();
	}

	/**
	 * Function Index
	 *
	 * function for listing of campaigns.
	 */
	function index($start=0){	
		$thisMid = $this->session->userdata('member_id');
		// Delete Campaign which are not save(is_status=1)		
		$condition_array=array('is_status'=>1, 'campaign_created_by'=>$thisMid,  'campaign_date_added > DATE_ADD(concat(CURDATE(), " 00:00:00"), INTERVAL 1 DAY)'=>NULL);
		$this->Campaign_Model->delete($condition_array);
		
		$contactDetail = $this->is_authorized->showBar($this->session->userdata('member_id'));
		// Ends: Google adword tracking
		$this->load->view('header',array('title'=>'List Campaigns','contactDetail'=>$contactDetail));
		$this->display($start,false);
		$this->load->view('footer',array('isFirstTimeUser'=>$isFirstTimeUser));
	}


	function display($start=0,$is_ajax=true){		
		// Get Maximum Contacts according to user package id
		$user_packages_array=$this->UserModel->get_user_packages(array('member_id'=>$this->session->userdata('member_id'),'is_deleted'=>0));
		$package_array=$this->UserModel->get_packages_data(array('package_id'=>$user_packages_array[0]['package_id']));		

		$campaign_data['package_max_contacts']= $package_array[0]['package_max_contacts'];

		// Function to get total contacts and contacts count list wise
		$subscriber_data=$this->display_subscriptions();
		$subscriber_count= $subscriber_data['sum_first_two_subscriber'];

		if($subscriber_count > $campaign_data['package_max_contacts']){
			$campaign_data['upgrade_package']=1;
			// attach Upgrade your package message with member
			$this->UserModel->attachMessage(array('member_id'=>$this->session->userdata('member_id'), 'message_id'=>2));
		}else{
			$this->UserModel->detachMessage(array('member_id'=>$this->session->userdata('member_id'), 'message_id'=>2));
			$campaign_data['upgrade_package']=0;
		}

		$fetch_condiotions_array=array('campaign_created_by'=>$this->session->userdata('member_id'),'rec.is_deleted'=>0,'is_status'=>0);
		// search string
		$campaign_data['campaign_search_by']	= $this->input->post('campaign_search_by');
		$campaign_data['campaign_search']	= trim($this->input->post('campaign_search'));	
		if($campaign_data['campaign_search'] !=''){
			$strLike = $campaign_data['campaign_search'];
			if($campaign_data['campaign_search_by'] =='subject'){				
				$fetch_condiotions_array["email_subject like'%$strLike%'"]=NULL;
			}elseif($campaign_data['campaign_search_by'] =='title'){				
				$fetch_condiotions_array["campaign_title like'%$strLike%'"]=NULL;
			}elseif($campaign_data['campaign_search_by'] =='content'){				
				$fetch_condiotions_array["campaign_content like'%$strLike%'"]=NULL;
			}
		}
		// Define config parameters for paging like base url, total rows and record per page.
		$config['base_url']=base_url().'promotions/display';
		$config['total_rows']=$this->Campaign_Model->get_shchedule_campaign_count($fetch_condiotions_array);
		$config['per_page']=15;
		$config['uri_segment']=3;
		$config['full_tag_open']	= 	'<ul class="pagination">';
		$config['full_tag_close'] 	= 	'</ul>';
		$config['cur_tag_open'] 	= 	'<li><a class="selected">';
		$config['cur_tag_close'] 	= 	'</a></li>';
		$config['first_tag_open'] 	= 	'<li>';
		$config['first_tag_close'] 	= 	'</li>';
		$config['last_tag_open'] 	= 	'<li>';
		$config['last_tag_close'] 	= 	'</li>';
		$config['num_tag_open'] 	= 	'<li>';
		$config['num_tag_close'] 	= 	'</li>';
		$config['next_tag_open'] 	= 	'<li>';
		$config['next_tag_close'] 	= 	'</li>';
		$config['prev_tag_open'] 	=	'<li>';
		$config['prev_tag_close'] 	= 	'</li>';
		// Initialize paging with above parameters
		$this->pagination->initialize($config);
		//Create paging links
		$paging_links=$this->pagination->create_links();

		// Fetches campaign data from database
		$campaign_data['campaigns']=$this->Campaign_Model->get_shchedule_campaign_data($fetch_condiotions_array,$config['per_page'],$start);
		$campaign_data['active_campaign_count']=$this->Campaign_Model->get_campaign_count(array('campaign_created_by'=>$this->session->userdata('member_id'),'rec.is_deleted'=>0,'is_status'=>0,'campaign_status'=>'archived','campaign_sheduled <'=>date('Y-m-d H:i:s',now()),'campaign_sheduled IS NOT NULL'=>NULL));
		
		$campaign_data['ready_campaign_count']=$this->Campaign_Model->get_campaign_count(array('campaign_created_by'=>$this->session->userdata('member_id'),'rec.is_deleted'=>0,'is_status'=>0,'campaign_status'=>'ready','campaign_sheduled <'=>date('Y-m-d H:i:s',now()),'campaign_sheduled IS NOT NULL'=>NULL));
		
		$campaign_data['queueing_campaign_count']=$this->Campaign_Model->get_campaign_count(array('campaign_created_by'=>$this->session->userdata('member_id'),'rec.is_deleted'=>0,'is_status'=>0,'campaign_status'=>'queueing','campaign_sheduled <'=>date('Y-m-d H:i:s',now()),'campaign_sheduled IS NOT NULL'=>NULL));

		// Recieve any messages to be shown, when campaign is added or updated
		$messages=$this->messages->get();
		// Assign messages to array to be send to view.
		$campaign_data['messages'] =$messages;
		// Fetch user data from database
		$user_data_array=$this->UserModel->get_user_data(array('member_id'=>$this->session->userdata('member_id')));
		$campaign_data['extra']=$user_data_array[0];
		

		// Get shoreten url
		$shorten_url=get_shorten_url();
		// Convert to Local Time for display to users
		for($i=0; $i<count($campaign_data['campaigns']); $i++){
			$campaign_data['campaigns'][$i]['draftDate'] = ('0000-00-00 00:00:00' == $campaign_data['campaigns'][$i]['campaign_date_updated'])?$campaign_data['campaigns'][$i]['campaign_date_added']: $campaign_data['campaigns'][$i]['campaign_date_updated'];
			$campaign_data['campaigns'][$i]['draftDate'] = getGMTToLocalTime($campaign_data['campaigns'][$i]['draftDate'], $this->session->userdata('member_time_zone'));
			$campaign_data['campaigns'][$i]['campaign_date_added'] = getGMTToLocalTime($campaign_data['campaigns'][$i]['campaign_date_added'], $this->session->userdata('member_time_zone'));
			$campaign_data['campaigns'][$i]['campaign_date_updated'] = getGMTToLocalTime($campaign_data['campaigns'][$i]['campaign_date_updated'], $this->session->userdata('member_time_zone'));
			$campaign_data['campaigns'][$i]['campaign_sheduled'] = getGMTToLocalTime($campaign_data['campaigns'][$i]['campaign_sheduled'], $this->session->userdata('member_time_zone'));
			$campaign_data['campaigns'][$i]['campaign_delay_minute'] = $campaign_data['campaigns'][$i]['campaign_delay_minute'];
			$campaign_data['campaigns'][$i]['email_send_date'] = getGMTToLocalTime($campaign_data['campaigns'][$i]['email_send_date'], $this->session->userdata('member_time_zone'));
			$campaign_data['campaigns'][$i]['screenshot'] = $this->db->query("select screenshot from red_campaign_screenshot where campaign_id='".$campaign_data['campaigns'][$i]['campaign_id']."'")->row()->screenshot;		
			$campaign_data['campaigns'][$i]['enc_cid'] = $this->is_authorized->encryptor('encrypt',$campaign_data['campaigns'][$i]['campaign_id']);		
			
			//prepare subscription list id
			$subscription_id_arr=@explode(",",$campaign_data['campaigns'][$i]['subscription_list']);

			//Fetch subscription List Titles
			$this->load->model('userboard/Emailreport_Model');
			$subscription_list_title=$this->Emailreport_Model->get_subscription_list_title(array('subscription_created_by'=>$this->session->userdata('member_id')),$subscription_id_arr);
			$campaign_data['campaigns'][$i]['subscription_list_title']= @implode(", ",$subscription_list_title);
		}
		
		// Loads header, campaign and footer view.
		if($is_ajax)
		echo $this->load->view('promotions/campaign_list_ajax',array('paging_links'=>$paging_links,'subscriber_data'=>$subscriber_data,'campaign_data'=>$campaign_data,'shorten_url'=>$shorten_url), true);
		else
		$this->load->view('promotions/campaign_list',array('paging_links'=>$paging_links,'subscriber_data'=>$subscriber_data,'campaign_data'=>$campaign_data,'shorten_url'=>$shorten_url));

	}
	/**
	* Function to select templates
	*/
	function layouts(){			
		$customLayout = array();		
		$thisMid = $this->session->userdata('member_id');
		$rsSavedLayouts = $this->db->query("select campaign_id from red_email_campaigns where campaign_created_by='$thisMid' and campaign_template_option=3 and is_status=0 and is_deleted=0 and is_template=1");		
		if($rsSavedLayouts->num_rows() > 0){
			foreach($rsSavedLayouts->result_array() as $row){
				$enc_cid = $this->is_authorized->encryptor('encrypt',$row['campaign_id']);
				$customLayout[$enc_cid] = $this->db->query("select screenshot from red_campaign_screenshot where campaign_id='".$row['campaign_id']."'")->row()->screenshot;		
			}
		}
		$arrLayout = array(0=>'0.png', 1=>'1.png', 2=>'2.png', 3=>'3.png',4=>'4.png', 5=>'5.png', 6=>'6.png');
		$contactDetail = $this->is_authorized->showBar($thisMid);
		$this->load->view('header',array('title'=>'List Campaigns','contactDetail'=>$contactDetail));		
		$this->load->view('promotions/layouts', array('layout_list'=>$arrLayout, 'customLayout'=>$customLayout));
		$this->load->view('footer');
	}
	
	function create_diy_campaign($layout=0, $mode=''){	
		$today_dt = date("j M, Y",strtotime(getGMTToLocalTime(date('Y-m-d H:i:s'),$this->session->userdata('member_time_zone')) ));
		// Create campaign
		$campaign_id=$this->Campaign_Model->create_campaign(array('campaign_title'=>'Campaign - '.$today_dt, 'campaign_created_by'=>$this->session->userdata('member_id'), 'campaign_theme_id'=>$layout, 'campaign_template_id'=>'-1', 'campaign_template_option'=>'3','campaign_color_theme_id'=>'-1', 'campaign_date_added'=>date('Y-m-d H:i:s', now()), 'campaign_content'=>NULL,'campaign_email_content'=>NULL,'campaign_text_content'=>NULL,	'email_subject'=>null,'sender_email'=>null,'sender_name'=>null,	'subscription_list'=>null,'click_url'=>NULL,'campaign_after_encode_url'=>NULL));
		
		//Fetch max of right and position from pages table table for default
		
		$row=$this->Page_Model->getMax('right',array("type='folder'"));
		$right=$row[0]['getmax']+1;
		$row=$this->Page_Model->getMax('position',array("type='folder'"));
		$position=$row[0]['getmax']+1;
		$page_id=$this->Page_Model->create_page(array(	'title'=>'home','name'=>'home',	'meta_description'=>NULL,'meta_keyword'=>NULL,'site_id'=>$campaign_id,'page_position'=>1,'is_published'=>'yes','parent_id'=>1,'position'=>0,'`left`'=>$right,'`right`'=>$right+1,'level'=>2,'type'=>'default'));
		 
				
		// Collect email template information
		$email_template_info=$this->Campaign_Model->get_campaign_data(array('campaign_id'=>$campaign_id,'campaign_created_by'=>$this->session->userdata('member_id')));
		
		$this->Campaign_Model->add_background_color_content(array('red_background_color_page_id'=>$page_id,'red_background_color_block_name'=>'footer_font_txt','red_background_color_block_content'=>'font-size::#2'));
		// Redirect to DIY with blank editor
		$enc_cid = $this->is_authorized->encryptor('encrypt',$campaign_id); 
		redirect('promotions/campaign_editor/'.$enc_cid);		
	}
	
	function plain_text($enc_cid=0){		
		$thisMid = $this->session->userdata('member_id');
		$campaign_array = array();		
		$campaign_array['campaign_title']=  'Campaign - '. date("j M, Y",strtotime(getGMTToLocalTime(date('Y-m-d  H:i:s'),$this->session->userdata('member_time_zone')) ));
		if(!is_numeric($id)){
			$int_cid =  $this->is_authorized->encryptor('decrypt',$enc_cid); 
			if(intval($int_cid) > 0){ 
				$arr_campaign	= $this->Campaign_Model->get_campaign_data(array('campaign_id'=>$int_cid,'campaign_created_by'=>$thisMid));
				$campaign_array = $arr_campaign[0];
			}	
		}
		$campaign_array['enc_cid']= $enc_cid;
		$campaign_array['is_autoresponder']=  false;			
		$user_data_array=$this->UserModel->get_user_data(array('member_id'=>$thisMid));		
		$campaign_array['user_data']=$user_data_array[0];
		$country_info=$this->UserModel->get_country_data();
		$campaign_array['country_info']=$country_info;
		$campaign_array['country_name']=  '';	
		foreach($country_info as $c){
			if($c['country_id'] == $campaign_array['user_data']['country_id'])
			$campaign_array['country_name']=  $c['country_name'];			
		}
		
		$contactDetail = $this->is_authorized->showBar($this->session->userdata('member_id')); 
		// Loads header, campaign and footer view.
		$this->load->view('header',array('title'=>'Promotions: Plain-text', 'contactDetail'=>$contactDetail));		
		$this->load->view('promotions/campaign_plain_text',array('campaign_data'=>$campaign_array));		
		$this->load->view('footer');
	}
	function plain_text_process(){			
		if(isset($_POST)){
			$thisMid = $this->session->userdata('member_id');
			$array_campaign_data = array( 'campaign_created_by'=>$thisMid, 'campaign_template_option'=>5, 'campaign_date_added'=>date('Y-m-d H:i:s', now()), 'is_status'=>0);		
			$this->form_validation->set_rules('campaign_title', 'Campaign Title', 'required');
			$this->form_validation->set_rules('campaign_text_email', 'Campaign Text Email', 'required');
			if($this->form_validation->run()==true){
				$array_campaign_data['campaign_title']	= $this->input->post('campaign_title');
				$array_campaign_data['campaign_content']= $this->input->post('campaign_text_email');
				$array_campaign_data['campaign_text_content']= $this->input->post('campaign_text_email');
				$array_campaign_data['campaign_email_content']= $this->input->post('campaign_text_email');				
				
				$int_cid = 0;
				$enc_cid = trim($this->input->post('enc_cid'));
				if(!is_numeric($enc_cid) && $enc_cid != ''){
					$int_cid =  $this->is_authorized->encryptor('decrypt',$enc_cid); 			
					if(intval($int_cid) > 0)$campaign_array	= $this->Campaign_Model->get_campaign_data(array('campaign_id'=>$int_cid,'campaign_created_by'=>$thisMid));
					if(count($campaign_array) > 0){
						// Update campaign
						$this->Campaign_Model->update_campaign($array_campaign_data,array('campaign_id'=>$int_cid));
						redirect('preview/index/'.$enc_cid); exit;
					}else{
						redirect('promotions/'); exit;	
					}						
				}
				// Create new campaign
				$new_campaign_id=$this->Campaign_Model->create_campaign($array_campaign_data);
				$enc_cid =  $this->is_authorized->encryptor('encrypt',$new_campaign_id); 
				redirect('preview/index/'.$enc_cid); exit;
			}			
		}		
		redirect('promotions/'); exit;	
	}
	
	function html_code($enc_cid=0){		
		$thisMid = $this->session->userdata('member_id');
		$campaign_array = array();		
		$campaign_array['campaign_title']=  'Campaign - '. date("j M, Y",strtotime(getGMTToLocalTime(date('Y-m-d  H:i:s'),$this->session->userdata('member_time_zone')) ));
		if(!is_numeric($id)){
			$int_cid =  $this->is_authorized->encryptor('decrypt',$enc_cid); 
			if(intval($int_cid) > 0){ 
				$arr_campaign	= $this->Campaign_Model->get_campaign_data(array('campaign_id'=>$int_cid,'campaign_created_by'=>$thisMid));
				$campaign_array = $arr_campaign[0];
			}	
		}
		$campaign_array['enc_cid']= $enc_cid;
		$campaign_array['is_autoresponder']=  false;			
		$user_data_array=$this->UserModel->get_user_data(array('member_id'=>$thisMid));		
		$campaign_array['user_data']=$user_data_array[0];
		$country_info=$this->UserModel->get_country_data();
		$campaign_array['country_info']=$country_info;
		$campaign_array['country_name']=  '';	
		foreach($country_info as $c){
			if($c['country_id'] == $campaign_array['user_data']['country_id'])
			$campaign_array['country_name']=  $c['country_name'];			
		}
		
		$contactDetail = $this->is_authorized->showBar($this->session->userdata('member_id')); 
		// Loads header, campaign and footer view.
		$this->load->view('header',array('title'=>'Promotions: Paste-html-code', 'contactDetail'=>$contactDetail));		
		$this->load->view('promotions/campaign_html_code',array('campaign_data'=>$campaign_array));		
		$this->load->view('footer');
	}
	
	function html_code_process(){
		if(isset($_POST)){
			$thisMid = $this->session->userdata('member_id');
			$array_campaign_data = array( 'campaign_created_by'=>$thisMid, 'campaign_template_option'=>4, 'campaign_date_added'=>date('Y-m-d H:i:s', now()), 'is_status'=>0);		
			$this->form_validation->set_rules('campaign_title', 'Campaign Title', 'required');
			$this->form_validation->set_rules('paste_code', 'HTML Code', 'required');
			if($this->form_validation->run()==true){
				$array_campaign_data['campaign_title']	= $this->input->post('campaign_title');
				// remove css and javascript
				//$html=$this->removeCssScript();
				//$htmlWithCss=htmlentities ($html, ENT_QUOTES, 'utf-8', false)	;				
				
				$html=$this->input->post('paste_code');			 
				$htmlWithCss=$this->automatice_css_inliner();				
				$array_campaign_data['campaign_content']= $htmlWithCss;							
				$array_campaign_data['campaign_text_content']=html2text($html,false,false);												
				
				$int_cid = 0;
				$enc_cid = trim($this->input->post('enc_cid'));
				if(!is_numeric($enc_cid) && $enc_cid != ''){
					$int_cid =  $this->is_authorized->encryptor('decrypt',$enc_cid); 			
					if(intval($int_cid) > 0)$campaign_array	= $this->Campaign_Model->get_campaign_data(array('campaign_id'=>$int_cid,'campaign_created_by'=>$thisMid));
					if(count($campaign_array) > 0){
						// Update campaign
						$this->Campaign_Model->update_campaign($array_campaign_data,array('campaign_id'=>$int_cid));
						redirect('preview/index/'.$enc_cid); exit;
					}else{
						redirect('promotions/'); exit;	
					}						
				}
				// Create new campaign
				$new_campaign_id=$this->Campaign_Model->create_campaign($array_campaign_data);
				$enc_cid =  $this->is_authorized->encryptor('encrypt',$new_campaign_id); 
				redirect('preview/index/'.$enc_cid); exit;
			}			
		}		
		redirect('promotions/'); exit;		
	}
	
	function url_import($enc_cid=0){	
		$thisMid = $this->session->userdata('member_id');
		$campaign_array = array();		
		$campaign_array['campaign_title']=  'Campaign - '. date("j M, Y",strtotime(getGMTToLocalTime(date('Y-m-d  H:i:s'),$this->session->userdata('member_time_zone')) ));
		if(!is_numeric($id)){
			$int_cid =  $this->is_authorized->encryptor('decrypt',$enc_cid); 
			if(intval($int_cid) > 0){ 
				$arr_campaign	= $this->Campaign_Model->get_campaign_data(array('campaign_id'=>$int_cid,'campaign_created_by'=>$thisMid));
				$campaign_array = $arr_campaign[0];
			}	
		}
		$campaign_array['enc_cid']= $enc_cid;
		$campaign_array['is_autoresponder']=  false;			
		$user_data_array=$this->UserModel->get_user_data(array('member_id'=>$thisMid));		
		$campaign_array['user_data']=$user_data_array[0];
		$country_info=$this->UserModel->get_country_data();
		$campaign_array['country_info']=$country_info;
		$campaign_array['country_name']=  '';	
		foreach($country_info as $c){
			if($c['country_id'] == $campaign_array['user_data']['country_id'])
			$campaign_array['country_name']=  $c['country_name'];			
		}
		
		$contactDetail = $this->is_authorized->showBar($this->session->userdata('member_id')); 
		// Loads header, campaign and footer view.
		$this->load->view('header',array('title'=>'Promotions: URL-import', 'contactDetail'=>$contactDetail));		
		$this->load->view('promotions/campaign_import_url',array('campaign_data'=>$campaign_array));		
		$this->load->view('footer');	
	}
	function url_import_process(){
		if(isset($_POST)){
			$thisMid = $this->session->userdata('member_id');
			$array_campaign_data = array( 'campaign_created_by'=>$thisMid, 'campaign_template_option'=>1, 'campaign_date_added'=>date('Y-m-d H:i:s', now()), 'is_status'=>0);		
			$this->form_validation->set_rules('campaign_title', 'Campaign Title', 'required');			
			$this->form_validation->set_rules('campaign_import_url', 'Import URL', 'required|callback_validate_url|trim');		
			if($this->form_validation->run()==true){
				$array_campaign_data['campaign_title']	= $this->input->post('campaign_title');
				$url=$this->input->get_post('campaign_import_url', true);
				$htmlWithCss=$this->automatice_css_inliner();			
								
				$array_campaign_data['campaign_text_content']=html2text($htmlWithCss,false,false);												
				$array_campaign_data['campaign_content']=htmlentities($htmlWithCss, ENT_QUOTES, "UTF-8");
				$array_campaign_data['import_campaign_url']=$url;
				
				$int_cid = 0;
				$enc_cid = trim($this->input->post('enc_cid'));
				if(!is_numeric($enc_cid) && $enc_cid != ''){
					$int_cid =  $this->is_authorized->encryptor('decrypt',$enc_cid); 			
					if(intval($int_cid) > 0)$campaign_array	= $this->Campaign_Model->get_campaign_data(array('campaign_id'=>$int_cid,'campaign_created_by'=>$thisMid));
					if(count($campaign_array) > 0){
						// Update campaign
						$this->Campaign_Model->update_campaign($array_campaign_data,array('campaign_id'=>$int_cid));
						redirect('preview/index/'.$enc_cid); exit;
					}else{
						redirect('promotions/'); exit;	
					}						
				}
				// Create new campaign
				$new_campaign_id=$this->Campaign_Model->create_campaign($array_campaign_data);
				$enc_cid =  $this->is_authorized->encryptor('encrypt',$new_campaign_id); 
				redirect('preview/index/'.$enc_cid); exit;
							
			}		
		}		
		redirect('promotions/'); exit;		
	}
	
	function zip_import($enc_cid=0){	
		$thisMid = $this->session->userdata('member_id');
		$campaign_array = array();		
		$campaign_array['campaign_title']=  'Campaign - '. date("j M, Y",strtotime(getGMTToLocalTime(date('Y-m-d  H:i:s'),$this->session->userdata('member_time_zone')) ));
		if(!is_numeric($id)){
			$int_cid =  $this->is_authorized->encryptor('decrypt',$enc_cid); 
			if(intval($int_cid) > 0){ 
				$arr_campaign	= $this->Campaign_Model->get_campaign_data(array('campaign_id'=>$int_cid,'campaign_created_by'=>$thisMid));
				$campaign_array = $arr_campaign[0];
			}	
		}
		$campaign_array['enc_cid']= $enc_cid;
		$campaign_array['is_autoresponder']=  false;			
		$user_data_array=$this->UserModel->get_user_data(array('member_id'=>$thisMid));		
		$campaign_array['user_data']=$user_data_array[0];
		$country_info=$this->UserModel->get_country_data();
		$campaign_array['country_info']=$country_info;
		$campaign_array['country_name']=  '';	
		foreach($country_info as $c){
			if($c['country_id'] == $campaign_array['user_data']['country_id'])
			$campaign_array['country_name']=  $c['country_name'];			
		}
		
		$contactDetail = $this->is_authorized->showBar($this->session->userdata('member_id')); 
		// Loads header, campaign and footer view.
		$this->load->view('header',array('title'=>'Promotions: URL-import', 'contactDetail'=>$contactDetail));		
		$this->load->view('promotions/campaign_import_zip',array('campaign_data'=>$campaign_array));		
		$this->load->view('footer');
	}	
	function zip_import_process(){
		if(isset($_POST)){
			$thisMid = $this->session->userdata('member_id');
			$array_campaign_data = array( 'campaign_created_by'=>$thisMid, 'campaign_template_option'=>2, 'campaign_date_added'=>date('Y-m-d H:i:s', now()), 'is_status'=>0);		
			$this->form_validation->set_rules('campaign_title', 'Campaign Title', 'required');			
			$this->form_validation->set_rules('campaign_import_zip_file', 'Campaign Import Zip File', 'callback_validate_upload');		
			if($this->form_validation->run()==true){
				$array_campaign_data['campaign_title']	= $this->input->post('campaign_title');
				$html=$this->extracted_zip_html;
				$htmlWithCss=$this->automatice_css_inliner($html);	
				$array_campaign_data['campaign_content']=$htmlWithCss; // commented line 440 ON 12 Jan, 2017. Added this line 434 and 441 
				
				$text_html=html2text($htmlWithCss,false,false);				
				$array_campaign_data['campaign_text_content']= $this->is_authorized->webCompatibleString(@mb_convert_encoding($text_html, 'HTML-ENTITIES', "UTF-8"));
				
				$htmlWithCss=htmlentities($htmlWithCss, ENT_QUOTES | ENT_IGNORE, "UTF-8");
				//$array_campaign_data['campaign_content']=$htmlWithCss; // commented line 440 ON 12 Jan, 2017. Added line 434and following 
				$array_campaign_data['campaign_email_content']=$htmlWithCss;				
				
				$int_cid = 0;
				$enc_cid = trim($this->input->post('enc_cid'));
				if(!is_numeric($enc_cid) && $enc_cid != ''){
					$int_cid =  $this->is_authorized->encryptor('decrypt',$enc_cid); 			
					if(intval($int_cid) > 0)$campaign_array	= $this->Campaign_Model->get_campaign_data(array('campaign_id'=>$int_cid,'campaign_created_by'=>$thisMid));
					if(count($campaign_array) > 0){
						// Update campaign
						$this->Campaign_Model->update_campaign($array_campaign_data,array('campaign_id'=>$int_cid));
						redirect('preview/index/'.$enc_cid); exit;
					}else{
						redirect('promotions/'); exit;	
					}						
				}
				// Create new campaign
				$new_campaign_id=$this->Campaign_Model->create_campaign($array_campaign_data);
				$enc_cid =  $this->is_authorized->encryptor('encrypt',$new_campaign_id); 
				redirect('preview/index/'.$enc_cid); exit;
							
			}		
		}		
		redirect('promotions/'); exit;			
	}
	function validate_upload(){		
		if(!file_exists($this->upload_path)){	mkdir($this->upload_path,0777); chmod($this->upload_path,0777);	}		
		if(!file_exists($this->upload_path.'/imported_zip_files')){	mkdir($this->upload_path.'/imported_zip_files/',0777);chmod($this->upload_path.'/imported_zip_files/',0777); }	
		if(!file_exists($this->upload_path.'/extracted_zip_files')){ mkdir($this->upload_path.'/extracted_zip_files/',0777); chmod($this->upload_path.'/extracted_zip_files/',0777);}
		$upload_config	=array();
		$upload_config['upload_path'] = $this->upload_path.'/imported_zip_files/';
		$upload_config['allowed_types'] = 'zip|rar';
		$upload_config['max_size']	= 1024*15; #5MB		
		$this->upload->initialize($upload_config);		
		$new_file_name=$this->session->userdata('member_id').'_'.date('YmdHis'); // New file name of zippped file		
		if(!$this->upload->do_upload('campaign_import_zip_file')){ 
			// check if file is uploaded successfully $this->upload->display_errors();			
			$this->form_validation->set_message('validate_upload', $this->upload->display_errors());
			return false;
		}else{
			$uploaded_file_array=$this->upload->data();			
			rename($uploaded_file_array['full_path'],$uploaded_file_array['file_path'].$new_file_name.$uploaded_file_array['file_ext']);
			
			$zip = new ZipArchive;
			if ($zip->open($uploaded_file_array['file_path'].$new_file_name.$uploaded_file_array['file_ext']) === TRUE) {
				$zip->extractTo($this->upload_path.'/extracted_zip_files/'.$new_file_name.'/');
				$zip->close();
			}
			$file_names_allowed=array('index.html','index.html');
			$file_ext_allowed=array('html','jpg','png','gif','css');
			$files=array();
			$directories=array();
			// Get Files array in extracted folder AND  delete Unwanted/dangerous files			
			$extracted_files_array = $this->dirtoarray($this->upload_path.'/extracted_zip_files/'.$new_file_name.'/');
			// print_r($extracted_files_array); exit;
			// Cycle through all source files to copy them in Destination
			foreach ($extracted_files_array as $each_file) {
				if(!in_array(strtolower(end(@explode('.',$each_file))),$file_ext_allowed)){
					@unlink($each_file);
				}else{
					$f_content = @file_get_contents($each_file);
					if(stripos($f_content, "<?php") !== false) {
						@unlink($each_file);
						send_mail(SYSTEM_NOTICE_EMAIL_TO, SYSTEM_EMAIL_FROM  ,'system' , SYSTEM_DOMAIN_NAME.': Hacking Attempt',"Campaign zip file(".$each_file.") having PHP into it tried to upload","Campaign zip file(".$each_file.") having PHP into it tried to upload");
				$data1=array('error' => 'Your file could not be imported');
					}
				}				
			}
			// Open renamed directory for reading and put files in files arrray and directories in directories array
			$dir_handle=opendir($this->upload_path.'/extracted_zip_files/'.$new_file_name.'/');
			while (false !== ($file = readdir($dir_handle))) {
				 if($file!='.' && $file!='..'){
					if(is_dir($this->upload_path.'/extracted_zip_files/'.$new_file_name.'/'.$file))
						$directories[]=$file;
					else
						$files[]=$file;
				 }
			}
			// Declare extracted_file variable
			$extracted_file='';
			// If extracted directory have files in it, then iterate in directory to search any of allowed files in it.
			if(count($files)){
				foreach($files as $file){
					if(in_array($file,$file_names_allowed) ){
						$key=array_search($file,$file_names_allowed);
						$extracted_file= $this->upload_path.'/extracted_zip_files/'.$new_file_name.'/'.$file_names_allowed[$key];
						$path_to_images=base_url().'locker/user_files/'.$this->session->userdata('member_id').'/extracted_zip_files/'.$new_file_name;
						break;
					}
				}
			}
			

			// If extracted directory have directories in it, then iterate in first directory to search any of allowed files in it.
			if($extracted_file=='' && count($directories)){
				$directory=$directories[0];
				$dir_handle=opendir($this->upload_path.'/extracted_zip_files/'.$new_file_name.'/'.$directory);
				while (false !== ($file = readdir($dir_handle))) {
					if($file!='.' || $file!='..'){
						if(in_array($file,$file_names_allowed)){
							$key=array_search($file,$file_names_allowed);
							$extracted_file=$this->upload_path.'/extracted_zip_files/'.$new_file_name.'/'.$directory.'/'. $file_names_allowed[$key];
							$path_to_images=base_url().'locker/user_files/'.$this->session->userdata('member_id').'/extracted_zip_files/'.$new_file_name.'/'.$directory;
							break;
						}
					}
				}
			}		

			// To check if extracted file exits in archive
			if($extracted_file!=''){
				$html=file_get_contents($extracted_file);				
				//$filtered_html=preg_replace('#(href|src)=(["\'])([.\/]*)([^:"\']*)(["\'])#',	'$1="'.$path_to_images.'/$4"',$html); // replace path to images and css
				$html=preg_replace('/\s+/',	' ',$html); // remove multiple spaces, \t, \n etc into 1 space
  				$filtered_html=preg_replace('#(href|src)\s?=\s?(["\'])([.\/]*)([^:"\']*)(["\'])#',	'$1="'.$path_to_images.'/$4"',$html);
				
				$this->extracted_zip_html=$filtered_html; // Assign filtered html to class variable and return true
				return true;
			}else{
				// If file is not found , then display error message and return false.
				$this->form_validation->set_message('validate_upload', 'Zip Archive does not contain index.html or is corrupt');
				return false;
			}
		}
	}
	/**
	 *	Function dirtoarray for converting css to inline css
	 *
	 *	@param (string) (html_content)  contains html content
	 *	@return (string) processedHTML: return filtter html content
	 */	
	function dirtoarray($dir, $recursive=true) {
    $array_items = array();
    if ($handle = opendir($dir)) {
        while (false !== ($file = readdir($handle))) {
            if ($file != "." && $file != "..") {
                if (is_dir($dir. "/" . $file)) {
                    if($recursive) {
                        $array_items = array_merge($array_items, $this->dirtoarray($dir. "/" . $file, $recursive));
                    }
                } else {
                    $file = $dir . "/" . $file;
                    $array_items[] = preg_replace("/\/\//si", "/", $file);
                }
            }
        }
        closedir($handle);
    }
    return $array_items;
	}
	/**
	*	Create a campaign from another campaign
	*/
	function create_from_campaign($enc_cid=0,$is_copy=0){
		$cid = $this->is_authorized->encryptor('decrypt',$enc_cid);  // This the template campaign (from which new campaign will be created)
		if(is_numeric($cid) and intval($cid) > 0){
			$loaded_campaign=$this->Campaign_Model->get_campaign_data(array('campaign_id'=>$cid,'campaign_created_by'=>$this->session->userdata('member_id')));
			//Redirects user to listing page if user have not created this campaign or campaign does not exists
			if(!count($loaded_campaign)){
				$this->messages->add('Campaign does not exists or you have not created this campaign', 'error');
				redirect('promotions');
			}

			// Retrieve data posted in form posted by user using input class
			$input_array=array('campaign_title'=>$loaded_campaign[0]['campaign_title'],
				'campaign_created_by'=>$this->session->userdata('member_id'),
				'campaign_theme_id'=>$loaded_campaign[0]['campaign_theme_id'],
				'campaign_template_id'=>$loaded_campaign[0]['campaign_template_id'],
				'campaign_template_option'=>$loaded_campaign[0]['campaign_template_option'],
				'campaign_text_content'=>$loaded_campaign[0]['campaign_text_content'],			
				'campaign_outer_bg'=>$loaded_campaign[0]['campaign_outer_bg'],
				'campaign_content'=>$loaded_campaign[0]['campaign_content'],
				'campaign_email_content'=>$loaded_campaign[0]['campaign_email_content'],
				'campaign_color_theme_id'=>$loaded_campaign[0]['campaign_color_theme_id'],
				'campaign_status'=>'draft',
				'campaign_date_added'=> date('Y-m-d H:i:s', now()),		
				'preheader'=>$loaded_campaign[0]['preheader'],
				'email_subject'=>$loaded_campaign[0]['email_subject'],
				'sender_email'=>$loaded_campaign[0]['sender_email'],
				'sender_name'=>$loaded_campaign[0]['sender_name'],
				'reply_to_email'=>$loaded_campaign[0]['reply_to_email'],
				'subscription_list'=>$loaded_campaign[0]['subscription_list'],
				'is_status'=>0,
				'click_url'=>NULL,
				'campaign_after_encode_url'=>NULL
			);
			if($is_copy < 1){	// From layout page as custom-template			
				$input_array['campaign_title'] = 'Campaign - '. date("j M, Y",strtotime(getGMTToLocalTime(date('Y-m-d H:i:s'),$this->session->userdata('member_time_zone')) ));
				$input_array['email_subject'] = '';
			}	
			// Create new campaign
			$newCid=	$this->Campaign_Model->create_campaign($input_array);
			// Start: copy campaign's files
			$src = $this->upload_path.'/email_templates/'.$cid;
			$dst = $this->upload_path.'/email_templates/'.$newCid;
			$this->copyCampaignFiles($src, $dst, $cid, $newCid);
			// End: copy campaign's files
			$newEncCid = $this->is_authorized->encryptor('encrypt',$newCid);
			if($is_copy > 1){// In case of Resend
				redirect('preview/index/'.$newEncCid);				
			}elseif($loaded_campaign[0]['campaign_template_option']==3){// DIY Campaigns			
				// Redirect to campaign editor
				redirect('promotions/campaign_editor/'.$newEncCid);
			}else{
				redirect('preview/index/'.$newEncCid);				
			}
		}else{
			redirect('promotions/');
		}		
	}
	function copyCampaignFiles($srcDir, $dstDir, $oldCampaignID, $newCampaignID) {
		if (file_exists($dstDir)) rrmdir($dstDir);
		if (is_dir($srcDir)) {
			mkdir($dstDir);
			$files = scandir($srcDir);
			foreach ($files as $file){
				if ($file != "." && $file != ".."){
					$newFile = str_replace($oldCampaignID,$newCampaignID, $file);
					//copy("$srcDir/$file", "$dstDir/$newFile",$oldCampaignID,$newCampaignID); 
					copy("$srcDir/$file", "$dstDir/$newFile"); 
				} 
			}
		}	
	}	
	/**
	 *	Function Theme
	 *
	 *	'theme' controller function to select the theme.
	 */
	function theme(){
		// To check form is submittted for saving theme
		if($this->input->post('action')=='save'){
			// get campaign id
			$campaign_id=$this->input->get_post('campaign_id', TRUE);
			//check if campaign exist then update campaign in database
			if($campaign_id){
				// Retrieve data posted in form posted by user using input class
				$input_array=array(
				'campaign_theme_id'=>$this->input->get_post('red_theme_name', TRUE),
				'campaign_template_id'=>$this->input->get_post('red_template_name', TRUE),
				'campaign_template_option'=>'3'
				);
				// Update campaign by data posted by user
				$this->Campaign_Model->update_campaign($input_array,array('campaign_id'=>$campaign_id,'campaign_created_by'=>$this->session->userdata('member_id')));
				// Redirect to listing of campaigns
				redirect('promotions/campaign_editor/'.$campaign_id);
			}
		}else{
		
		
		}
	}
  	 
	 
	/**
	*	'Dislay' controller function for listing of subscriptions.
	*/

	function display_subscriptions($start=0){
		$subscriber_data = array();
		$fetch_condiotions_array=array('subscription_created_by'=>$this->session->userdata('member_id'),'is_deleted'=>0,'subscription_status'=>1);

			$subscription_data['subscriptions']=$this->subscription_Model->get_subscription_data($fetch_condiotions_array);
			if(count($subscription_data['subscriptions']) > 0){
				foreach($subscription_data['subscriptions'] AS $subscription){
					$contact_data['subscription_title']=$subscription['subscription_title'];
					$contact_data['subscription_id']=$subscription['subscription_id'];

					$contact_data['total_subscriber']=$this->contact_model->get_contacts_count_in_list(array('subscriber_created_by'=>$this->session->userdata('member_id'),'subscriber_status'=>1,'is_deleted'=>0),$subscription['subscription_id']);
					// Collect all the values in an array for use it in view of my-account
					$subscriber_data['subscribers'][]=$contact_data;
					if($subscription['subscription_id'] < 0)
					$subscriber_data['sum_first_two_subscriber']	= $contact_data['total_subscriber']; // total Contacts
				}

			}

			return ($subscriber_data);

	}


	/**
	* 	'Delete' function for campaign deletion.
	*/

	function update_campaign($enc_cid, $mode=''){		
		$cid = $this->is_authorized->encryptor('decrypt',$enc_cid);
		if($mode == 'stop'){
			$this->Campaign_Model->update_campaign(array('campaign_status'=>'draft'), array('campaign_id'=>$cid,'campaign_created_by'=>$this->session->userdata('member_id')));
			$this->Emailreport_Model->delete_emailqueue(array('campaign_id'=>$cid));			
			$this->load->model('Activity_Model'); 		
			$this->Activity_Model->create_activity(array('user_id'=>$this->session->userdata('member_id'), 'activity'=>'campaign_stopped',  'campaign_id'=>$cid));
			echo 'Campaign stopped';
		}elseif($mode == 'delete'){
			$this->Campaign_Model->delete_campaign(array('campaign_id'=>$cid,'campaign_created_by'=>$this->session->userdata('member_id')));			
			$this->load->model('userboard/Emailreport_Model');		
			$this->Emailreport_Model->delete_emailqueue(array('campaign_id'=>$cid));			
			$this->load->model('Activity_Model'); 		
			$this->Activity_Model->create_activity(array('user_id'=>$this->session->userdata('member_id'), 'activity'=>'campaign_delete',  'campaign_id'=>$cid));
			//$this->updateDelayForAutomaticCampaigns($cid); // Update Campaign's DELAY for AUTOMATIC campaigns.
			echo 'Campaign deleted';	
		}elseif($mode == 'create_template'){
			$this->Campaign_Model->update_campaign(array('is_template'=>1), array('campaign_id'=>$cid,'campaign_created_by'=>$this->session->userdata('member_id')));
			echo'Campaign saved as template';
		}elseif($mode == 'delete_template'){
			$this->Campaign_Model->update_campaign(array('is_template'=>0), array('campaign_id'=>$cid,'campaign_created_by'=>$this->session->userdata('member_id')));
			echo'Campaign deleted as template';
		}		
	}
	function updateDelayForAutomaticCampaigns($cid){
		$mid = $this->session->userdata('member_id');
		
		$rsDeletedScheduledTime = $this->db->query("SELECT campaign_status_show, campaign_delay_minute, Date_add(campaign_sheduled, INTERVAL campaign_delay_minute MINUTE)scheduled_time FROM `red_email_campaigns` where campaign_id='$cid' ");		
		$deleted_campaign_status_show = $rsDeletedScheduledTime->row()->campaign_status_show;
		$deleted_campaign_delay = $rsDeletedScheduledTime->row()->campaign_delay_minute;
		$deleted_scheduled_time = $rsDeletedScheduledTime->row()->scheduled_time;
		$rsDeletedScheduledTime->free_result();
		
		if($this->UserModel->isMemberAuthentic($mid)  && $campaign_status_show > 1){
		
			$rsPreviousCampaign = $this->db->query("SELECT Date_add(campaign_sheduled, INTERVAL campaign_delay_minute MINUTE)scheduled_time FROM `red_email_campaigns` where campaign_created_by='$mid' and is_deleted=0 and scheduled_time <  '$deleted_scheduled_time'");			
			$previous_scheduled_time = $rsPreviousCampaign->row()->scheduled_time;
			$rsPreviousCampaign->free_result();
			
			$delay_to_reduce = $deleted_campaign_delay + ((strtotime($deleted_scheduled_time) - strtotime($previous_scheduled_time))/60);
			//$rsNextCampaign = $this->db->query("SELECT Date_add(campaign_sheduled, INTERVAL campaign_delay_minute MINUTE)scheduled_time FROM `red_email_campaigns` where campaign_created_by='$mid' and campaign_status_show > 1 and is_deleted=0 and scheduled_time >  '$deleted_scheduled_time'");			
			//$previous_scheduled_time = $rsNextCampaign->row()->scheduled_time;
			//$rsNextCampaign->free_result();
			
			$this->db->query("UPDATE `red_email_campaigns` SET campaign_delay_minute = (campaign_delay_minute - $delay_to_reduce) where campaign_created_by='$mid' and campaign_status_show > 1 and is_deleted=0 and scheduled_time >  '$deleted_scheduled_time'");
		}
	}
	function selected_subscribers($ajax=true,$subscription_id=0){
		$where_in=array();
		$subscriber_count=0;
		$fetch_condiotions_array=array('subscriber_created_by'=>$this->session->userdata('member_id'),'subscriber_status'=>1,'is_deleted'=>0);

		if($subscription_id){
			$where_in[]=$subscription_id;
			unset($_POST['subscriptions']);
			$_POST['subscriptions'][]=$subscription_id;

			$subscriber_count= $this->contact_model->get_contacts_count_in_selected_lists($fetch_condiotions_array,$_POST['subscriptions']);

		}else if(isset($_POST['subscriptions'])){

			$subscriber_count= $this->contact_model->get_contacts_count_in_selected_lists($fetch_condiotions_array,$_POST['subscriptions']);
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
	*	Function Campaign editor to create email
	*/	
	function campaign_editor($id=0){
		$encrypted_cid = $id;
		$id = $this->is_authorized->encryptor('decrypt',$id);
		$thisMid = $this->session->userdata('member_id');
		//Fetch campaign data from database by campaign ID
		$campaign_array=$this->Campaign_Model->get_campaign_data(array('campaign_id'=>$id,'campaign_created_by'=>$thisMid));

		//Redirects user to listing page if user have not created this campaign or campaign does not exists
		if(!count($campaign_array)){
			redirect('promotions');
		}
		if((($campaign_array[0]['campaign_status']=='archived')&&(date('Y-m-d H:i:s', strtotime( $campaign_array[0]['campaign_sheduled']))<date("Y-m-d H:i:s")))||($campaign_array[0]['campaign_status']=='ready')||($campaign_array[0]['campaign_status']=='active')||($campaign_array[0]['campaign_status']=='unapproved') ){
			redirect('promotions');
		}
		$this->session->set_userdata('email_template_id', $id);		
		$layout = $campaign_array[0]['campaign_theme_id']; // this is the Layout selected by user in first step
		// Create array for use in view
		$email_template_data=array();
		if($campaign_create){
			$email_template_data['campaign_create']="copy";
		}

		$user_data_array=$this->UserModel->get_user_data(array('member_id'=>$thisMid));
		$user_info=true;
		$str_user_detail_for_footer='<div style="padding:15px 15px 15px 15px;height:30px;">';
		if($user_data_array[0]['company']){
			$str_user_detail_for_footer.="<span class='company_name'><b><span class='copyright'>&copy; </span>".$user_data_array[0]['company']."</b></span><br/>";
		}else{
			$str_user_detail_for_footer.="<span class='company_name'><b><span class='copyright'>&copy; </span>Company Name</b></span><br/>";
			$user_info=false;
		}
		$str_user_detail_for_footer.='<div style="float:left;width:100%;margin-left:18px;">';
		if($user_data_array[0]['address_line_1']){
			$str_user_detail_for_footer.='<span class="address">'.$user_data_array[0]['address_line_1'].' '.$user_data_array[0]['address_line_2'].'</span>';
		}else{
			$str_user_detail_for_footer.="<span class='address'>Street Address</span>";
			$user_info=false;
		}
		if($user_data_array[0]['city']){
			$str_user_detail_for_footer.='<span class="city"> | '.$user_data_array[0]['city'].'</span>';
		}else{
			$str_user_detail_for_footer.="<span class='city'> | City</span>";
			$user_info=false;
		}
		if($user_data_array[0]['state']){
			$str_user_detail_for_footer.='<span class="state">, '.$user_data_array[0]['state'].'</span> ';
		}else{
			$str_user_detail_for_footer.="<span class='state'>, State</span> ";
			$user_info=false;
		}if($user_data_array[0]['zipcode']){
			$str_user_detail_for_footer.='<span class="zip">'.$user_data_array[0]['zipcode'].'</span>';
		}else{
			$str_user_detail_for_footer.="<span class='zip'> Zip Code</span>";
			$user_info=false;
		}
		if($user_data_array[0]['country_name']){
			if($user_data_array[0]['country_id']==225){
				$country=$user_data_array[0]['country_code'];
			}elseif($user_data_array[0]['country_id']==245){
				$country=$user_data_array[0]['country_custom'];
			}else{
				$country=$user_data_array[0]['country_name'];
			}
			if($country !='USA' and $country !='United States')
			$str_user_detail_for_footer.='<span class="country"> | '.$country.'</span>';
			else
			$str_user_detail_for_footer.='<span class="country"></span>';
		}else{
			$str_user_detail_for_footer.="<span class='country'></span>";
			$user_info=false;
		}
		$str_user_detail_for_footer.="</div></div>";
		// Add email template ID to email_template_data array

		$email_template_data['encrypted_cid']=$encrypted_cid;
		$email_template_data['email_template_id']=$id;
		
		
		$fetch_conditions_array=array('site_id'=>$id, 'is_deleted'=>'No', 'is_autoresponder'=>'0');
		$total_pages=$this->Page_Model->get_page_count($fetch_conditions_array);

		$email_template_data['pages']=$this->Page_Model->get_page_data($fetch_conditions_array,$total_pages);
		
	// Theme background color information
	$block_all_data_count=$this->Campaign_Model->get_background_color_blocks_names_and_content_count(array('red_background_color_page_id'=>$email_template_data['pages'][0]['id']));

	$email_template_color_info=$this->Campaign_Model->get_background_color_blocks_names_and_content_data(array('red_background_color_page_id'=>$email_template_data['pages'][0]['id']),$block_all_data_count);

		foreach ($email_template_color_info AS $email_template_color){
			$expl_color=explode(":#",$email_template_color['red_background_color_block_content']);
			if($email_template_color['red_background_color_block_name']=="outer-background"){
				$email_template_data['outer_background']=$expl_color[1];
			}else{
				$email_template_data[$email_template_color['red_background_color_block_name']]=$expl_color[1];
			}
		}

		$email_template_info=$this->Campaign_Model->get_campaign_data(array('campaign_id'=>$id));
		$email_template_data['email_template_info']=$email_template_info[0];
		$template_info=$this->Campaign_Model->get_template_data(array('template_id'=>$email_template_data['email_template_info']['campaign_template_id']));

		$email_template_data['template_info']=$template_info[0];
		$email_template_data['member_id']=$thisMid;

		$template_default_data=array();

		$template_base_path= $this->config->item('site_root_path') .$this->config->item('site_assets') .'templates/';
		//$template_base_path= $this->config->item('locker') .'templates/';
		$arrLayout = array(0=>'0.html', 1=>'1.html', 2=>'2.html', 3=>'3.html',4=>'4.html', 5=>'5.html', 6=>'6.html');
		$template_path =$template_base_path. $arrLayout[$layout];
		


		$email_template_data['template_info']['template_html']= @file_get_contents($template_path);
		$filtered_html= $email_template_data['template_info']['template_html'];
		//$filtered_html=preg_replace('#(href|src)=(["\'])([.\/]*)([^:"\']*)(["\'])#', '$1="'.$template_base_path.'/$4"',$email_template_data['template_info']['template_html']);


		$body_empty_text='<p class="empty-text">Drag here</p>';
		$search_arr=array('{locker}','{website_name}','{body_top}','{body_main}','{body_bottom}','{body_left}','{body_right}','{FOOTER}');

		$replace_arr=array($this->config->item('locker'),'<span class="empty-text">click to add website name</span>',$body_empty_text,$body_empty_text,"",$body_empty_text,$body_empty_text,ucwords($str_user_detail_for_footer));
		$filtered_html=str_replace($search_arr,$replace_arr,$filtered_html);

		/******* if templete contents are showing using ajax**************************/

		$email_template_data['template_info']['filtered_html']= $filtered_html;

		$email_template_data['email_template_info']['body_empty_text']=$body_empty_text;
		// Header-images
		$templates_count=$this->Campaign_Model->get_template_count(array('rect.is_active'=>1,'rect.is_delete'=>0));
		#echo $templates_count;
		$template_data=$this->Campaign_Model->get_template_data(array('rect.is_active'=>1,'rect.is_delete'=>0),$templates_count);

		$email_template_data['template_data']=$template_data;



		//Count number of image bank
		$image_bank_count=$this->Campaign_Model->get_image_bank_count(array('img_is_status'=>1));
		//Get information of image bank
		$image_bank_data=$this->Campaign_Model->get_image_bank_data(array('img_is_status'=>1,'img_user_id'=>$thisMid),$templates_count);

		$image_bank_str='';
		$j=1;
		for($i=0;$i<count($image_bank_data);$i++){
			$path_info=pathinfo($thisMid.'/image_bank/'.$this->session->userdata('email_template_id').'/uploaded_images/'.$image_bank_data[$i]['img_name']);
			//thumb image path
			$thumb_image_path=$path_info['filename'].".".$path_info['extension'];
			$library_images[]=$thumb_image_path;
		}
		$email_template_data['library_images']=$library_images;
		$this->session->set_userdata('show_layout_container', 1);
		$email_template_data['show_layout_container']=0;
		if($this->session->userdata('show_layout_container')==1)
		{
			$email_template_data['show_layout_container']=1;
			$this->session->set_userdata('show_layout_container', 0);
		}
		$email_template_data['theme_css']=$this->get_theme_css($email_template_data['email_template_info']['campaign_color_theme_id']);

		$email_template_data['user_info']=$user_info;
		$email_template_data['user_data']=$user_data_array[0];
		//Fetch Country name
		$country_info=$this->UserModel->get_country_data();
		$email_template_data['country_info']=$country_info;
		
		
		// Get shoreten url
		$email_template_data['shorten_url']=get_shorten_url();
		//Loads header, campaign and footer view.		
		$this->load->view('promotions/campaign_editor',$email_template_data);
	}
	// function to display social-media dialog
	function get_socialmedia_ajax(){
		$thisMid = $this->session->userdata('member_id');
		$rsSMedia = $this->db->query("select id, socialmedia_name from red_socialmedia where 1");
		$strTableBody = '';
		if($rsSMedia->num_rows() > 0){
			foreach($rsSMedia->result_array() as $row){
				$thisSMid = $row['id'];				
				$thisSMName = $row['socialmedia_name'];	
				$thisSMurl = ''; $isChecked = '';	$style='display:none;';
				$rsMemberSM = $this->db->query("select socialmedia_url from red_member_socialmedia where member_id='$thisMid' and socialmedia_id='$thisSMid'");
				if($rsMemberSM->num_rows() > 0){
					$thisSMurl = $rsMemberSM->row()->socialmedia_url;
					$isChecked = 'checked';
					$style= 'display:block;';
				}	
				$rsMemberSM->free_result();
				
				$strTableBody .='<tr height="50">
								<td width="50">
									<img src="'. $this->config->item('locker'). 'images/icons/'.$thisSMName.'-share.png?v=1"  style = "box-shadow:2px 3px 5px #888;" title="'.$thisSMName.'" />
								</td>
								<td align="center">
									<input type="checkbox" value="1" name="'.$thisSMName.'_link" id="'.$thisSMName.'_link" '.$isChecked.' />
								</td>
								<td class="'.$thisSMName.'_text">
									<input type="text" name="'.$thisSMName.'_url" id="'.$thisSMName.'_url" style="'.$style.'width:220px" value="'.$thisSMurl.'" placeholder="Enter '.$thisSMName.' URL here..."/>
								</td>
							</tr>';				
			}
		}
		$rsSMedia->free_result();	
		echo '<div id="social_media_dialog">		
				<p style="margin: 10px 15px 15px">Check the icons and specify the link to your social media page.</p>
			<table style="border-collase: collapse;" border="0" cellpadding="0">
			<tr>
			<td>
				<table style="border-collase: collapse; margin: 7px 15px;margin-bottom:30px;" cellpadding="4" border ="0">'
					.$strTableBody.
				'</table>
			</td>
			</tr>
			</table>
			
			<div class="message_button" style = "border-right: solid 5px #1C4587;border-left: solid 5px #1C4587;left:-5px;bottom:-30px;">
				<a onclick="javascript: addSM();">Submit</a>
			</div>		
		</div>';		
	}
	function get_theme_colors(){

		//get themes color from database
		$theme_color=$this->Campaign_Model->get_theme_colors(array('is_delete'=>'0','is_active'=>'1'));
		$colors="";
		foreach ($theme_color as $color){			
			if($color['member_id'] > -1){
				$delIcon='<a class="close-link theme_color_delete" id="theme_color_'.$color['id'].'" style = "position:absolute;bottom:0;right:0;height:15px;ine-height:15px;border-radius:0px;font-size:12px;padding:2px 5px;background:#C00;text-align:center;">X</a>';
			}else{
				$delIcon='';			
			}
			
			$colors.='<div class = "DIY-content-box-theme"  id="'.$color['id'].'" onclick="saveColorTheme('.$color['id'].',this)">
						<div class = "theme-color-base" id="outer_bg_'.$color['id'].'" style="background:'.$color['outer_bg'].';">
							<div class = "theme-color-box" id="body_bg_'.$color['id'].'"  style="background:'.$color['body_bg'].';" ></div>
							<div class = "theme-color-box" id="footer_bg_'.$color['id'].'"  style="background:'.$color['footer_bg'].';"></div>
							<div class = "theme-color-box" id="border_color_'.$color['id'].'"  style="background:'.$color['border_color'].';"></div>
						</div>
						<div class = "theme-name">'.substr($color['theme_name'],0,15).' '.$delIcon.'</div>
					</div>';
		}
		echo $colors;
	}
	//Change color theme and css
	function get_theme_css($template_id,$method=""){

		//get themes color from database
		$theme_color= $this->Campaign_Model->get_theme_colors(array('id'=>$template_id));
		$style="";
		foreach($theme_color[0] as $elem=>$color){
			if($elem!='id' && $elem!='theme_name'){			
				$arr_elem=explode('_',$elem,2);
				if($arr_elem[1]=="bg"){
					if($arr_elem[0]=="header"){	// header style is not in use - Nov 7, 2016
						$style.="<style class='header_style custome_style'>#header{	background-color:$color;}</style>";
					}else if($arr_elem[0]=="body"){
						$style.="<style class='body_main_style custome_style'>
							#header{background-color:$color;} 
							#body_main{background-color:$color;	}
							.body_bg{background-color:$color;}
							.body_bg_color{background-color:$color;}
						</style>
						";
					}else if($arr_elem[0]=="outer"){
						$style.="<style class='main-table_style custome_style'>
							#main-table{background-color:$color;}
							/*html, body{background-color:$color;}*/
							.diy-editor{background-color:$color;}
							#template_container{background-color:$color;}
							.outer_bg{background-color:$color;}
						</style>
						";
					}else{
						if($elem=="footer_bg"){
							$style.="<style class='footer_style custome_style'>
								#footer{background-color:$color;}
								.footer_bg{background-color:$color;}
								.footer_txt_color{background-color:$color;}
							</style>";
						}
					}
				}else if($arr_elem[1]=="color"){
					$style.="<style class='border_style custome_style'>
						#email_template_table{border-color:  $color !important; border: solid $color 1.5pt;}
						.body_border {background-color:  $color;}
					</style>
					";
				}else if($elem=="footer_font_color"){
					if($color !='#'){
						$style.="<style class='footer_font_style custome_style'>
							#footer{color:  $color ;}
						</style>
						";
					}
				}
			}
		}
		if($method=="ajax"){
			echo $style;
		}else{
			return $style;
		}
	}
	// Function to cahnge the selected template
	function change_template($template_id,$email_campaign_id){

		$email_template_id=$email_campaign_id; // emial template id

		//Fetch campaign data from database by campaign ID
		$campaign_array=$this->Campaign_Model->get_campaign_data(array('campaign_id'=>$email_campaign_id,'campaign_created_by'=>$this->session->userdata('member_id')));

		//Redirects user to listing page if user have not created this campaign or campaign does not exists
		if(!count($campaign_array)){
			redirect('promotions');
		}
		//update email template id in campaign table
		$this->Campaign_Model->update_campaign(array('campaign_template_id'=>$template_id,'campaign_template_option'=>3),array('campaign_id'=>$email_template_id));

		$this->session->set_userdata('show_layout_container', 1);
		echo $email_template_id;
	}
	//Function to cahnge the selected theme
	function change_theme($template_id,$email_campaign_id,$theme_id=0){

		$email_template_id=$email_campaign_id; // emial template id
		//Fetch campaign data from database by campaign ID
		$campaign_array=$this->Campaign_Model->get_campaign_data(array('campaign_id'=>$email_campaign_id,'campaign_created_by'=>$this->session->userdata('member_id')));

		//Redirects user to listing page if user have not created this campaign or campaign does not exists
		if(!count($campaign_array))
		{
			redirect('promotions');
		}
		if($theme_id){
		//update email template id in campaign table
		$this->Campaign_Model->update_campaign(array('campaign_template_id'=>$template_id,'campaign_template_option'=>3,'campaign_theme_id'=>$theme_id),array('campaign_id'=>$email_template_id));
		}else{
		$this->Campaign_Model->update_campaign(array('campaign_color_theme_id'=>$template_id),array('campaign_id'=>$email_template_id));
		}
		$this->session->set_userdata('show_layout_container', 1);
		echo $email_template_id;
	}

	// Function to show all the email template screenshots
	function get_template_data_for_ajax($campaign_id=0){
		//Fetch campaign data from database by campaign ID
		$campaign_array=$this->Campaign_Model->get_campaign_data(array('campaign_id'=>$campaign_id,'campaign_created_by'=>$this->session->userdata('member_id')));

		//Redirects user to listing page if user have not created this campaign or campaign does not exists
		if(!count($campaign_array))
		{
			redirect('promotions');
		}
		//Count number of templates
		$templates_count=$this->Campaign_Model->get_campaign_template_count(array('rect.is_active'=>1,'rec.campaign_id'=>$campaign_id));

		//Get information of email template
		$template_data=$this->Campaign_Model->get_campaign_template_data(array('rect.is_active'=>1,'rec.campaign_id'=>$campaign_id),$templates_count);

		$template_str='';
		$j=1;
		for($i=0;$i<count($template_data);$i++){
			//template screenshot path
			$template_img_path=base_url().'locker/email_templates/'.$template_data[$i]['template_name'].'/'.$template_data[$i]['screenshot'];
			$template_name=$template_data[$i]['template_name']; //template name
			$template_id=$template_data[$i]['template_id']; // template id

			$template_img='<a onclick="previewTemplate(\''.$template_name.'\','.$template_id.',this)"><img style="width:36px; height:36px;"  src="'.$template_img_path.' " ></a>';
			$template_str.='<li '.$class.'>'.$template_img.$template_links.'</li>';
			$j++;
		}
		echo $template_str;
	}
	// Function to show all the email template for theme
	function get_template_data_for_theme($theme_id=0){


		//Count number of templates
		$templates_count=$this->Campaign_Model->get_template_count(array('rect.is_active'=>1,'rect.is_delete'=>0,'rect.template_theme_id !='=>-1));
		//Get information of email template
		$template_data=$this->Campaign_Model->get_template_data(array('rect.is_active'=>1,'rect.is_delete'=>0,'rect.template_theme_id !='=>-1),$templates_count);
		//,'rect.show_on_dashboard'=>1
		if($theme_id){
			$themes_arr=array();
			foreach($template_data as $theme){
				$category_arr=explode(',',$theme['template_theme_id']);
				if (in_array($theme_id, $category_arr)) {
					$themes_arr[]=$theme;
				}
			}
			unset($template_data);
			$template_data=$themes_arr;
		}

		$template_str='';
		$j=1;
		for($i=0;$i<count($template_data);$i++)
		{
			if($template_data[$i]['template_id']!=-1){
				if($theme_id>0){
					//template screenshot path
					$template_img_path=$this->config->item('locker').'header-images/header-'.$template_data[$i]['template_id'].'.jpg';
					$template_name=$template_data[$i]['template_name']; //template name
					$template_id=$template_data[$i]['template_id']; // template id
					$template_theme_id=$template_data[$i]['template_theme_id']; // theme id
					$arr_template_theme_id=@explode(',',$template_theme_id); // theme id
					$template_theme_id = $arr_template_theme_id[0];

					$template_img='<div class="preview"><a href="javascript:;" onclick="saveTemplate(\''.$template_name.'\','.$template_id.','.$template_theme_id.')" class="banner-highlight"><img  src="'.$template_img_path.' " width="595"></a></div>';
					$selectBannerButton = "<a href='javascript:;' onclick='saveTemplate(\"$template_name\",\"$template_id\",\"$template_theme_id\")' class='btn select'>Select</a></div></div>";


					$template_str.='<li class="article">'.$template_img.$template_links.$selectBannerButton.'</li>';
				}else if($template_data[$i]['show_on_dashboard']==1){
					//template screenshot path
					$template_img_path=$this->config->item('locker').'header-images/header-'.$template_data[$i]['template_id'].'.jpg';
					$template_name=$template_data[$i]['template_name']; //template name
					$template_id=$template_data[$i]['template_id']; // template id
					$template_theme_id=$template_data[$i]['template_theme_id']; // theme id
					$arr_template_theme_id=@explode(',',$template_theme_id); // theme id
					$template_theme_id = $arr_template_theme_id[0];

					$template_img='<div class="preview"><a href="javascript:;" onclick="saveTemplate(\''.$template_name.'\','.$template_id.','.$template_theme_id.')"><img src="'.$template_img_path.' " width="595"></a></div>';

					$selectBannerButton = "<a href='javascript:;' onclick='saveTemplate(\"$template_name\",\"$template_id\",\"$template_theme_id\")' class='btn select'>Select</a>";



					$template_str.='<li class="article">'.$template_img.$template_links.$selectBannerButton.'</li>';
				}
			}
			$j++;
		}
		echo $template_str;
	}
	//Function to show all the email template screenshots
	function get_theme_data_for_ajax($category_id=0){

		//Count number of templates
		$templates_count=$this->Campaign_Model->get_theme_count(array('rect.red_is_active'=>1,'rect.red_is_delete'=>0,'rect.red_theme_id !='=>-1));
		$template_str='';
		$j=1;
		//Get information of email theme
		$campaign_data['theme_data']=$this->Campaign_Model->get_theme_data(array('rect.red_is_active'=>1,'rect.red_is_delete'=>0,'rect.red_theme_id !='=>-1),$templates_count);
		$template_str.="<div style = 'border-bottom:solid 1px #CCC;padding-bottom:5px;margin-bottom:5px;'> Select Your Theme: ";
		$template_str.="<select onchange='changeHeader(\"change\")' id='category_list' style = 'border:solid 1px #CCC;'>";
		$template_str.="<option>None</option>";
		foreach($campaign_data['theme_data'] as $theme_info){
			if(($category_id>0)&&($category_id==$theme_info['red_theme_id'])){
				$templates_count=$this->Campaign_Model->get_template_count(array('rect.is_active'=>1,'rect.is_delete'=>0,'rect.template_theme_id !='=>-1));
				$template_data=$this->Campaign_Model->get_template_data(array('rect.is_active'=>1,'rect.is_delete'=>0,'rect.template_theme_id !='=>-1),$templates_count);
				if($templates_count>0){
					$themes_arr=array();
					foreach($template_data as $theme){
						$category_arr=explode(',',$theme['template_theme_id']);
						if (in_array($category_id, $category_arr)) {
							$themes_arr[]=$theme;
						}
					}
					unset($template_data);
					$template_data=$themes_arr;
				}
				$campaign_data['template_data'][$theme_info['red_theme_id']]=$template_data;
				unset($template_data);
			}
			if(($category_id>0)&&($category_id==$theme_info['red_theme_id'])){
				$template_str.='<option value="'.$theme_info['red_theme_id'].'" selected="selected">'.$theme_info['red_theme_name'].'</option>';
			}else{
				$template_str.='<option value="'.$theme_info['red_theme_id'].'">'.$theme_info['red_theme_name'].'</option>';
			}
		}

		$template_str.="</select></div>";
		if($category_id<=0){
			$templates_count=$this->Campaign_Model->get_template_count(array('rect.is_active'=>1,'rect.is_delete'=>0,'rect.template_theme_id !='=>-1,'rect.show_on_dashboard'=>1));
			$template_data=$this->Campaign_Model->get_template_data(array('rect.is_active'=>1,'rect.is_delete'=>0,'rect.template_theme_id !='=>-1,'rect.show_on_dashboard'=>1),$templates_count);

			$campaign_data['template_data'][-1]=$template_data;
		}

		foreach($campaign_data['template_data'] as $key=>$theme_data){
			$template_str.='<div  class="div_template"><ul class="themes_ul">';
			foreach($theme_data as $theme){
				//template screenshot path				
				$template_img_path=$this->config->item('locker').'images/template-headers/'.$theme['template_id'].'.jpg';
				$template_id=$theme['template_id']; //template id
				$theme_id=$key; // template category  id
				$template_img='<img onclick="saveHeader(\''.$template_id.'\')" src="'.$template_img_path.'" border="0" />';
				$template_str.='<li '.$class.' >'.$template_img.$template_links.'</li>';
				$j++;
			}
			$template_str.='</ul></div>';
		}
		echo  $template_str;
	}
	// Function to display all the image  bank images
	function get_image_bank_for_ajax(){
		//Count number of image bank
		$image_bank_count=$this->Campaign_Model->get_image_bank_count(array('img_is_status'=>1,'img_is_delete'=>0,'img_user_id'=>$this->session->userdata('member_id')));

		//Get information of image bank
		$image_bank_data=$this->Campaign_Model->get_image_bank_data(array('img_is_status'=>1,'img_is_delete'=>0,'img_user_id'=>$this->session->userdata('member_id')),$image_bank_count);

		$image_bank_str='';
		$j=1;
		if(count($image_bank_data)>0){
			for($i=0;$i<count($image_bank_data);$i++)
			{
				$path_info=pathinfo($this->session->userdata('member_id').'/image_bank/'.$this->session->userdata('email_template_id').'/uploaded_images/'.$image_bank_data[$i]['img_name']);
				//thumb image path
				$thumb_image_path=base_url().'asset/user_files/'.$this->session->userdata('member_id').'/image_bank/'.$path_info['filename'].".".$path_info['extension'];
				$img_path=base_url().'asset/user_files/'.$this->session->userdata('member_id').'/image_bank/'.$path_info['filename'].".".$path_info['extension'];
				// Check if file exists or not
				if(file_exists(str_replace(base_url().'asset/user_files/'.$this->session->userdata('member_id').'/',$this->upload_path.'/', $img_path))){

					list($width, $height, $type, $attr) = getimagesize(str_replace(base_url().'asset/user_files/'.$this->session->userdata('member_id').'/',$this->upload_path.'/', $img_path));
					$image_bank_img=' <div class="image_bank_div"><img class="image_bank draggable1"  src="'.$thumb_image_path.'" name="'.$img_path.','.$width.','.$height.'" style="width:108px"></div>';
					$class="class='li_draggable' ";
					$img_remove='<div  class="del_image_link"><a href="javascript:void(0);"  class="remove-img-link image_bank_unlink" style = "font-size:12px;padding:2px 5px;background:#C00;" id="'.$image_bank_data[$i]['img_id'].'" title="Delete Image">X</a></div>';
					$image_bank_str.='<li '.$class.'  title="Click & Drag" ><div  class="img_slide">'.$image_bank_img.$img_remove.$image_bank_links.'</div></li>';
					$j++;
				}//check for Image file exists or not
			}
		}else{
			$image_bank_str='<li class="load_images">
								<b>Click "Upload Images" button to upload your images</b>
							</li>';
		}
		echo $image_bank_str;
	}

	// Function to copy  files in folder
	function recurse_copy($src,$dst){
		if($dir = opendir($src)){
			@mkdir($dst); // create destination directory

			// copy from from src directory to destination directory
			while(false !== ( $file = readdir($dir)) ) {
				if (( $file != '.' ) && ( $file != '..' )) {
					if ( is_dir($src . '/' . $file) ) {
						$this->recurse_copy($src . '/' . $file,$dst . '/' . $file);
					}
					else {
						copy($src . '/' . $file,$dst . '/' . $file);
						chmod($dst . '/' . $file,0777);
					}
				}
			}
			closedir($dir);// close directory
		}
	}
	// function to share a link on facebook
	function share_link($id=0){
		// Get shoreten url
		$shorten_url=get_shorten_url();
		echo "<div style='width:100%;float:left;'><div style='float:none;margin:20px;text-align:center;color:#000;'><div style='margin-bottom:5px;'><b>Copy & Paste URL below to Facebook</b></div><div><b> page or any other sharing medium.</b></div></div>";
		echo "<div style='float:none;margin:5px;'><input type='text' value='".CAMPAIGN_DOMAIN."c/".$id."' style='width:520px;' /></div><div style='float:none;margin:13px;text-align:center;'><img src='".base_url()."locker/images/facebook-share.png' alt='Share on facebook'></div></div>";
	}
	// Function to send notification email to admin for schdule campaigns	
	function notification_email($campaign_id=0){
		$this->load->helper('admin_notification');		
		
		$email_template_info=$this->Campaign_Model->get_campaign_data(array('campaign_id'=>$campaign_id));		
		$email_msg="<p>Hello admin,<br/><br/> Campaign :".$email_template_info[0]['campaign_title']." is ready to send.<br/><br/> Select a choice to allow or disallow it from admin<br/><br/> Regards,<br/>BoldInbox Team</p>";
		
		$to=$this->get_Admin_notification_email();
		
		admin_notification_send_email($to, SYSTEM_EMAIL_FROM,'BoldInbox', "Campaign Approval",$email_msg,$email_msg);
	}
	function get_Admin_notification_email(){		
		$query          = $this->db->query('SELECT config_name,config_value FROM `red_site_configurations` where `config_name` = "admin_notification_email"');
		$admin_email	= "";
		if ($query->num_rows() == 1){
			$row = $query->row();
			$admin_email        = $row->config_value;
		}
		return $admin_email;
	}

	function editorstyle(){
		$this->load->view('promotions/campaign_editor_style');
	}
	function editorjs(){
		$this->load->view('promotions/campaign_editor_js');
	}
	//	Function update_company_info_on_campaign to update comapny info on created campaign	
	function update_company_info_on_campaign($campaign_id=0){		
		$this->load->helper('simple_html_dom');
		$company		=	$this->input->post('company');
		$address_line_1	=	$this->input->post('address_line_1');
		$city			=	$this->input->post('city');
		$state			=	$this->input->post('state');
		$zipcode		=	$this->input->post('zipcode');
		$country_id		=	$this->input->post('country');
		if(trim($country_id) != '245'){
			$qry="select country_name FROM red_countries WHERE country_id 	='".$country_id."'";
			$country_qry=$this->db->query($qry);	#execute query
			$country_data_array=$country_qry->result_array();	#Fetch resut
			if(count($country_data_array)>0){
				$country=$country_data_array[0]['country_name'];
			}
		}else{
				$country =$this->input->post('country_custom');
		}

		//Fetch campaign data from database by campaign ID
		$campaign_array=$this->Campaign_Model->get_campaign_data(array('campaign_id'=>$campaign_id,'campaign_created_by'=>$this->session->userdata('member_id')));

		$campaign=change_footer($company,$address_line_1,$city,$state,$zipcode,$country,$campaign_array[0]['campaign_content'],$campaign_array[0]['campaign_email_content']);
		$campaign_cnt_arr=explode('xxx_campaign_content_xxx',$campaign);
		// Load Html to text plugin
		$this->load->helper('htmltotext');
		$text_html=html2text($campaign_cnt_arr[0]);

		 
		// Update campaign by data posted by user
		$this->Campaign_Model->update_campaign(array('campaign_content'=>$campaign_cnt_arr[0],'campaign_email_content '=>$campaign_cnt_arr[1],'campaign_text_content '=>$text_html),array('campaign_id'=>$campaign_array[0]['campaign_id'],'campaign_created_by'=>$this->session->userdata('member_id')));
	}

	// Function check_campaign_status to check campaign has been send successfully or not
	function check_campaign_status($campaign_id=0){

		$campaign_info=$this->Campaign_Model->get_campaign_data(array('campaign_id'=>$campaign_id,'campaign_created_by'=>$this->session->userdata('member_id')));
		if(($this->session->flashdata('campaign_status')=="scheduled")&&($campaign_info[0]['campaign_status']!="active")){
			$this->messages->add('Campaign Scheduled Successfully', 'success');
		}else if($campaign_info[0]['campaign_status']=="active"){
			$this->messages->add('Your email campaign was sent successfully.', 'success');
		}
		// Redirect to listing of campaigns
		redirect('promotions');
	}
	//	Function to cancel your scheduled email campaign and revert back to draft mode.	
	function cancel_campaign_delivery($campaign_id=0){		
		$this->load->model('userboard/Emailreport_Model');
		$this->Emailreport_Model->delete_emailqueue(array('campaign_id'=>$campaign_id));
		$this->Campaign_Model->update_campaign(array('campaign_status'=>'draft'),array('campaign_id'=>$campaign_id,'campaign_created_by'=>$this->session->userdata('member_id')));
	}

	function getImageBankSize(){
		$file_directory = $this->upload_path.'/image_bank';
		$filesize = 0;
		if(CAMPAIGN_HEADER_SUFFIX == 'PRVN'){//WINDOWS			
			$filesize = (1024 * 1024 * 200)+0;
		}else{
			$output = exec('du -sk ' . $file_directory);
			$filesize = trim(str_replace($file_directory, '', $output)) * 1024;
		}

		echo (IMAGE_BANK_QUOTA < $filesize)?'exceeded':'ok';
	}	

	/**
	*	Function import_zip_file to import zip file for creating campaign
	*	@param int id: Campaign id
	*/
	
	 
	/**
	* 	Function Campaign_preview to display the campaign's text version
	*	@param (int) (id)  contains campaign id for which campaign preview will be display
	*/
	function campaign_preview($id=0){	
		$campaign_id = $this->is_authorized->encryptor('decrypt',$id); 
		$campaign_data=array();		
		$campaign_array=$this->Campaign_Model->get_campaign_data(array('campaign_id'=>$campaign_id,'campaign_created_by'=>$this->session->userdata('member_id')));
		
		if(count($campaign_array)){			
			$campaign_data=array(
								'campaign_template_option'=>$campaign_array[0]['campaign_template_option'],
								'campaign_theme_id'=>$campaign_array[0]['campaign_theme_id'],
								'html'=>$campaign_array[0]['campaign_content'],
								'campaign_text_content'=>$campaign_array[0]['campaign_text_content'],
								'campaign_title'=>$campaign_array[0]['campaign_title'],
							);
			$campaign_data['campaign_id']=$campaign_id;
		}else{
			redirect('promotions');
		}
		$user_data_array=$this->UserModel->get_user_data(array('member_id'=>$this->session->userdata('member_id')));		
		$campaign_data['user_data']=$user_data_array[0];
		$country_info=$this->UserModel->get_country_data();
		$campaign_data['country_info']=$country_info;
		
		// To check user have submit the form
		if($this->input->post('action')=='submit'){			
			// Validation rules are applied
			$this->form_validation->set_rules('campaign_title', 'Campaign Title', 'required');
			$this->form_validation->set_rules('campaign_text_content', 'Campaign Text Generated ', 'required');	
			// To check form is validated
			if($this->form_validation->run()==true){
				if($this->input->get_post('regenerate_text', TRUE)==1){
					$campaign_content=html_entity_decode($campaign_array[0]['campaign_content'], ENT_QUOTES, "utf-8" ); 
					$htmlWithCss=$this->automatice_css_inliner($campaign_content);
					
					//$text_html=html2text($htmlWithCss,false,false);
					$text_html=html2text($campaign_content,false,false);
					$input_array=array(	'campaign_title'=>$this->input->get_post('campaign_title', TRUE),'is_status'=>'0','campaign_text_content'=>$text_html);					
				}else if($campaign_array[0]['is_status']==1){
					$input_array=array(	'campaign_title'=>$this->input->get_post('campaign_title', TRUE),'is_status'=>'0','campaign_text_content'=>$this->input->get_post('campaign_text_content', TRUE));
				}else{
					$input_array=array(	'campaign_title'=>$this->input->get_post('campaign_title', TRUE),	'is_status'=>'0','campaign_text_content'=>$this->input->get_post('campaign_text_content', TRUE));
				}
				// Update campaign by data posted by user
				$this->Campaign_Model->update_campaign($input_array,array('campaign_id'=>$campaign_id));
				if($campaign_array[0]['is_status']==1){					
					// create array for insert values in activty table
					$values=array('user_id'=>$this->session->userdata('member_id'),'activity'=>'campaign_created',  'campaign_id'=>$campaign_id	);
					$this->Activity_Model->create_activity($values);
				}
				
				$user_data_array=$this->UserModel->get_user_data(array('member_id'=>$this->session->userdata('member_id')));
				
				 
				if(($campaign_array[0]['campaign_template_option']!=3)&&($campaign_array[0]['campaign_template_option']!=5)){
					$page_html=html_entity_decode( $campaign_array[0]['campaign_content'], ENT_QUOTES, "utf-8" ); 
				}else{
					$page_html=$campaign_array[0]['campaign_content'];
				}
				$this->Campaign_Autoresponder_Model->encode_url($campaign_id,$page_html);	
				 
				redirect('preview/index/'.$id);
				
			}
		}		
		
		// Count number of theme
		$theme_count=$this->Campaign_Model->get_theme_count(array('rect.red_is_active'=>1));
		
		// Get information of email theme
		$campaign_data['theme_data']=$this->Campaign_Model->get_theme_data(array('rect.red_is_active'=>1),$templates_count);		
		foreach($campaign_data['theme_data'] as $theme_info){
			$templates_count=$this->Campaign_Model->get_template_count(array('rect.is_active'=>1,'rect.template_theme_id'=>$theme_info['red_theme_id']));
			$template_data=$this->Campaign_Model->get_template_data(array('rect.is_active'=>1,'rect.template_theme_id'=>$theme_info['red_theme_id']),$templates_count);
			$campaign_data['template_data'][$theme_info['red_theme_id']]=$template_data;
		}
		// Get Maximum Contacts according to session package id
		// Get Package id
		$user_packages_array=$this->UserModel->get_user_packages(array('member_id'=>$this->session->userdata('member_id'),'is_deleted'=>0));
		$package_array=$this->UserModel->get_packages_data(array('package_id'=>$user_packages_array[0]['package_id']));		
		
		$package_price=$package_array[0]['package_price'];
		$package_max_contacts=$package_array[0]['package_max_contacts'];
		// Get Total Subscribers created by user
		$fetch_condiotions_array=array(	'res.subscriber_created_by'=>$this->session->userdata('member_id'), 'res.is_deleted'=>0, 'res.subscriber_status'=>0);
		$subscriber_count=$this->Subscriber_Model->get_subscriber_count($fetch_condiotions_array);

		if($subscriber_count>$package_max_contacts){
			$campaign_data['upgrade_package']=1;
		}else{
			$campaign_data['upgrade_package']=0;
		}
		$campaign_data['is_autoresponder']=false;
	 
		$contactDetail = $this->is_authorized->showBar($this->session->userdata('member_id')); 	
		$this->load->view('header',array('title'=>'Campaign Template','previous_page_url'=>$previous_page_url,'contactDetail'=>$contactDetail));
		$this->load->view('promotions/campaign_preview',array('campaign_data'=>$campaign_data,'shorten_url'=>$shorten_url));
		$this->load->view('footer');
	}
	/**
	 *	Function Automatice_css_inliner for converting css to inline css
	 *	@param (string) (html_content)  contains html content
	 *	@return (string) processedHTML: return filtter html content
	 */
	function automatice_css_inliner($html_content=""){
		$this->load->library('CSSToInlineStyles');// Load library for converting css to inline css
		$dom = new DOMDocument();
		if(isset($_POST['paste_code'])){
			$html=$_POST['paste_code'];
		}elseif(isset($_POST['campaign_import_url'])){
			$url=$this->input->get_post('campaign_import_url', true);
			$html=$this->get_html_from_url($url);
		}else{
			$html=$html_content;
		}
		$dom->recover = true;
		$dom->strictErrorChecking = false;	
		libxml_use_internal_errors(true);
		//$html = preg_replace('/&#?+(\w)+;/', ' ', $html); 
		//$html = str_replace('&nbsp;',' ',$html);
		$html = str_replace('&copy;','',$html);
		//$html = utf8_encode(html_entity_decode($html));		

		$html = mb_convert_encoding($html, 'HTML-ENTITIES', "UTF-8");
		 
		

		@$dom->loadHTML('<?xml encoding="UTF-8">'.$html); 		
		libxml_clear_errors();
		// get stylesheet of extrnal files		
		$link_tags = $dom->getElementsByTagName('style');
		$css="";
		for($i = 0; $i < $link_tags->length; $i++){
			$css.=$link_tags->item($i)->nodeValue;
			$link_tags->item($i)->nodeValue="";
		}
		
		// get stylesheet of link tag
		$link_tags = $dom->getElementsByTagName('link');
		for($i = 0; $i < $link_tags->length; $i++){
			$url= $link_tags->item($i)->getAttribute('href'); 
			$result = $this->validateURL($url);
			if($result){
				$css.=file_get_contents($url);
			}
		}
		// convert css to inline style
		$this->csstoinlinestyles->setHTML($html);
		$this->csstoinlinestyles->setCSS($css);
		$this->csstoinlinestyles->setCleanup(false);
		// grab the processed HTML
		$processedHTML =  $this->csstoinlinestyles->convert();
		// remove link tag css 
		$processedHTML= preg_replace ('/<link[^>]+\>/i', "", $processedHTML);
		// remove javascript from page
		$processedHTML= preg_replace ('/(\\s*)(<script\\b[^>]*?>)([\\s\\S]*?)<\\/script>(\\s*)/i'
	, "", $processedHTML);
		// remove javascript from page
		$processedHTML= preg_replace ('/<script[^>]+\>/i', "", $processedHTML); 
		$processedHTML=preg_replace('/<style\b[^>]*>(.*?)<\/style>/is', "", $processedHTML);
		$processedHTML=preg_replace('|\<style.*\>(.*\n*)\</style\>|isU', "", $processedHTML);
		
		return $processedHTML; 
	}
	/**
	*	Function get_html_from_url to fetch HTML source from URL
	*/
	function get_html_from_url($url=""){
		
		//$html= file_get_contents($url);
		//$html= $this->getSslPage($url);
		$html= $this->curl_file_get_contents($url);
		// fetch domain name from URL like http://www.domain.com from http://www.domain.com/page.html
		// http://www.addresshome.com/mailer/reconnect/reconnect.html ..............images are like src="images/some.gif"
		 $url_arr=pathinfo($url);
		if(!isset($url_arr['extension'])){
			$url_arr['dirname']=$url_arr['dirname']."/".$url_arr['filename'];
		}
		// Filter HTML by replacing relative path of images, css, javascript to absolute path by appending domain name to them
		$filtered_html=preg_replace('#(href|src)=(["\'])([.\/]*)([^:"\']*)(["\'])#',		'$1="'.$url_arr['dirname'].'/$4"',$html);
		
		 
		 
		 /*
		$url_arr=parse_url($url);
		$strUrl = $url_arr['scheme'].'://'.$url_arr['host'].'/'.$url_arr['path'];
		// Filter HTML by replacing relative path of images, css, javascript to absolute path by appending domain name to them
		$filtered_html=preg_replace('#(href|src)=(["\'])([.\/]*)([^:"\']*)(["\'])#',		'$1="'.$strUrl.'/$4"',$html);
		 
	    */
		
		return $filtered_html;
	}
	
	function getSslPage($url) {
		/*$ch = curl_init();
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_REFERER, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		$result = curl_exec($ch);
		curl_close($ch);
		return $result;
		*/
		$arrContextOptions=array("ssl"=>array( "verify_peer"=>false,      "verify_peer_name"=>false) );  

$response = file_get_contents($url, false, stream_context_create($arrContextOptions));
		return $response;
	}	
	 
	function curl_file_get_contents($url){
		 $curl = curl_init();
		 $userAgent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)';
		 
		 curl_setopt($curl,CURLOPT_URL,$url); //The URL to fetch. This can also be set when initializing a session with curl_init().
		 curl_setopt($curl,CURLOPT_RETURNTRANSFER,TRUE); //TRUE to return the transfer as a string of the return value of curl_exec() instead of outputting it out directly.
		 curl_setopt($curl,CURLOPT_CONNECTTIMEOUT,5); //The number of seconds to wait while trying to connect.	
		 
		 curl_setopt($curl, CURLOPT_USERAGENT, $userAgent); //The contents of the "User-Agent: " header to be used in a HTTP request.
		 curl_setopt($curl, CURLOPT_FAILONERROR, TRUE); //To fail silently if the HTTP code returned is greater than or equal to 400.
		 curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE); //To follow any "Location: " header that the server sends as part of the HTTP header.
		 curl_setopt($curl, CURLOPT_AUTOREFERER, TRUE); //To automatically set the Referer: field in requests where it follows a Location: redirect.
		 curl_setopt($curl, CURLOPT_TIMEOUT, 10); //The maximum number of seconds to allow cURL functions to execute.	
		 
		 $contents = curl_exec($curl);
		 curl_close($curl);
		 return $contents;
	}
	function removeCssScript(){
		$processedHTML=$this->input->post('paste_code');
		$processedHTML= preg_replace ('/<link[^>]+\>/i', "", $processedHTML); // remove link tag css 
		$processedHTML= preg_replace ('/(\\s*)(<script\\b[^>]*?>)([\\s\\S]*?)<\\/script>(\\s*)/i'
	, "", $processedHTML); // remove javascript from page
		$processedHTML= preg_replace ('/<script[^>]+\>/i', "", $processedHTML); // remove javascript from page
		$processedHTML=preg_replace('/<style\b[^>]*>(.*?)<\/style>/is', "", $processedHTML);
		$processedHTML=preg_replace('|\<style.*\>(.*\n*)\</style\>|isU', "", $processedHTML);
		return $processedHTML; // return filtter html content
	}
	function validateURL($url){
		$pattern = '/^(([\w]+:)?\/\/)?(([\d\w]|%[a-fA-f\d]{2,2})+(:([\d\w]|%[a-fA-f\d]{2,2})+)?@)?([\d\w][-\d\w]{0,253}[\d\w]\.)+[\w]{2,4}(:[\d]+)?(\/([-+_~.\d\w]|%[a-fA-f\d]{2,2})*)*(\?(&amp;?([-+_~.\d\w]|%[a-fA-f\d]{2,2})=?)*)?(#([-+_~.\d\w]|%[a-fA-f\d]{2,2})*)?$/';
		return preg_match($pattern, $url);
	}
	
	function user_address(){
		$user_data_array=$this->UserModel->get_user_data(array('member_id'=>$this->session->userdata('member_id')));		
		$campaign_data['user_data']=$user_data_array[0];
		$country_info=$this->UserModel->get_country_data();
		$campaign_data['country_info']=$country_info;
		$campaign_data['country_name']=  '';	
		foreach($country_info as $c){
			if($c['country_id'] == $campaign_data['user_data']['country_id'])
			$campaign_data['country_name']=  $c['country_name'];			
		}
		echo $this->load->view('promotions/user_address', array('campaign_data'=>$campaign_data),FALSE);
	}	
	// function to add social media url against members
	function add_member_sm(){
		$mid = $this->session->userdata('member_id');
		$smid = $this->input->post('smid');
		$smurl = $this->input->post('smurl');
		$this->db->query("insert into `red_member_socialmedia` set member_id='$mid', socialmedia_id='$smid', socialmedia_url='$smurl' ON DUPLICATE KEY UPDATE socialmedia_url='$smurl'");
	}
}
?>