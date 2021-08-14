<?php
/**
* A Preview class
*
* This class is to preview campaign
*
* @version 1.0
* @author Pravin Jha <pravinjha@gmail.com>
* @project BoldInbox
*/

class Preview extends CI_Controller{
	/**
		Contructor for controller.
	*/
	function __construct(){
		parent::__construct();		
		$this->load->model('userboard/Emailreport_Model');		 
		$this->load->model('userboard/Campaign_Model');			 
		$this->load->model('UserModel');				 
		$this->load->model('userboard/Subscriber_Model');		
		$this->load->model('userboard/Campaign_Autoresponder_Model');	
	}
	function index($id=0, $isScreenshot=''){
		$encrypted_cid = $id;
		$id = $this->is_authorized->encryptor('decrypt',$id);
		//  if member is not logged-in and screenshot-mode is not ON, redirect to login page
		if($this->session->userdata('member_id')=='' && $isScreenshot == '')redirect('user/index');		
		// Fetches campaign data from database
		//$campaign_array=$this->Campaign_Model->get_campaign_data(array('campaign_id'=>$id, 'campaign_created_by'=>$this->session->userdata('member_id') ));
		$campaign_array=$this->Campaign_Model->get_campaign_data(array('campaign_id'=>$id ));
		
		// Redirects user to listing page if user have not created this campaign or campaign does not exists
		if(!count($campaign_array)){
			redirect('promotions');
		}
		// Prepare array to send to view
		$campaign_data=array(
			'campaign_id'=>$id,
			'encrypted_cid'=>$encrypted_cid,
			'member_id'=>$campaign_array[0]['campaign_created_by'],
			'campaign_title'=>$campaign_array[0]['campaign_title'],
			'campaign_template_option'=>$campaign_array[0]['campaign_template_option'],
			'campaign_content'=>$campaign_array[0]['campaign_content'],
			'campaign_status'=>$campaign_array[0]['campaign_status'],
			'email_subject'=>$campaign_array[0]['email_subject'],
			'campaign_outer_bg'=>$campaign_array[0]['campaign_outer_bg']
		);
		
		// remove tabindex="-1" from campign content
		$campaign_data['campaign_content']=str_replace('tabindex="-1"','',$campaign_data['campaign_content']);
		// If campign created by DIY	then remove extra characters
		if($campaign_data['campaign_template_option']!=3){
			$campaign_data['campaign_content']=html_entity_decode($campaign_data['campaign_content'], ENT_QUOTES, "utf-8" );
		}
		
		if($campaign_data['campaign_template_option'] == 5){
				//$campaign_data['campaign_content'] = wordwrap ( $campaign_data['campaign_text_content'] ,75 ,"\n",true );	
				$user=$this->UserModel->get_user_data(array('member_id'=>$campaign_data['member_id']));
				$campaign_footer_text_only = $this->Campaign_Autoresponder_Model->campaign_footer_text_only($user, $id, false, true);
				$campaign_data['campaign_content'] .= $campaign_footer_text_only;
				
				//$campaign_data['rc_logo']= 0;				
			}
		// Email Personalize campaign 
		$email_personalize_arr=array();
		$search_email_personalize=$this->get_email_personalize_data($email_personalize_arr);

		$arrPersonalizeReplace=$this->get_fallback_value($campaign_data['campaign_content'],$email_personalize_arr);

		$campaign_data['campaign_content']=str_replace($search_email_personalize, $arrPersonalizeReplace, $campaign_data['campaign_content']);		
		// Collect BIB logo check
		$campaign_data['rc_logo']=0; // Hide powered by logo for all users in preview mode.
		$campaign_data['isScreenshot']=$isScreenshot;
		$this->getScreenShot($encrypted_cid);
		// Load Campign view Link
		$this->load->view('promotions/preview',$campaign_data);		
	}
	/**
	*	Function to get screenshot of the campaign using googleapi
	*/
	function getScreenShot($cid){		
		$decrypted_cid = $this->is_authorized->encryptor('decrypt',$cid);
		$apiKey = 'AIzaSyB5ebLju4sES305LXwJqbUHr_8ijs3ydfQ';	
		$campaign_url = CAMPAIGN_DOMAIN.'c/'.$cid.'/1' ;
		//$campaign_url = 'http://www.beonlist.com/c/2096';
		// Setup cURL		
		$ch = curl_init('https://www.googleapis.com/pagespeedonline/v1/runPagespeed?url='.$campaign_url.'&key='.$apiKey.'&screenshot=true');		
		//curl_setopt($ch, CURLOPT_POST, true);		
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(	'Content-Type: application/json'));
		curl_setopt($ch, CURLOPT_CAINFO, $this->config->item('rcdata').'cacert.pem');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec($ch);
		// Check for errors
		if($response !== FALSE){
			$arrStr = json_decode($response, TRUE);				
			$data =  str_replace(array('_','-'), array('/','+'), $arrStr['screenshot']['data']);
			$mtype =  $arrStr['screenshot']['mime_type'];
			$w =  $arrStr['screenshot']['width'];
			$h =   $arrStr['screenshot']['height'];
			$screenshot = "data:image/jpeg;base64,$data";
			$this->db->query("insert into red_campaign_screenshot set campaign_id='$decrypted_cid', screenshot='$screenshot' ON DUPLICATE KEY UPDATE screenshot='$screenshot', updated_on=now()");
			//echo $this->db->last_query();
		}else{
			//die(curl_error($ch));
		}
	} 
	function testScreenShot($cid){		
		//$decrypted_cid = $this->is_authorized->encryptor('decrypt',$cid);
		$apiKey = 'AIzaSyB5ebLju4sES305LXwJqbUHr_8ijs3ydfQ';	
		$campaign_url = CAMPAIGN_DOMAIN.'campaign_preview/campaign_view/'.$cid.'/1' ;
		//$campaign_url = 'http://www.beonlist.com/c/2096';
		// Setup cURL		
		$ch = curl_init('https://www.googleapis.com/pagespeedonline/v1/runPagespeed?url='.$campaign_url.'&key='.$apiKey.'&screenshot=true');		
		//curl_setopt($ch, CURLOPT_POST, true);		
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(	'Content-Type: application/json'));
		curl_setopt($ch, CURLOPT_CAINFO, $this->config->item('rcdata').'cacert.pem');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec($ch);
		// Check for errors
		if($response !== FALSE){
			$arrStr = json_decode($response, TRUE);				
			$data =  str_replace(array('_','-'), array('/','+'), $arrStr['screenshot']['data']);
			$mtype =  $arrStr['screenshot']['mime_type'];
			$w =  $arrStr['screenshot']['width'];
			$h =   $arrStr['screenshot']['height'];
			$screenshot = "data:image/jpeg;base64,$data";
			echo "<img src='$screenshot' >";
			
		}else{
			//die(curl_error($ch));
		}
	} 
	
	/**
		Function get_email_personalize_data to fetch email personalize name and value from database
	*/
	function get_email_personalize_data(&$email_personalize_arr=array()){
		$sql            = 'SELECT name,value,default_value FROM `red_email_personalization`';
		$query          = $this->db->query($sql);
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
	*	Function get_fallback_value to fetch fallback value from campign content
	*/
	function get_fallback_value(&$campaign_content="",$arrPersonalizeReplace=array()){
		$string		=		$campaign_content;
		
		//$pattren="/\{([a-zA-Z0-9_-])*,([a-zA-Z0-9_-])*\}/";
		$pattren="/\{([a-zA-Z0-9_-])*,([^\/])*\}/";
		preg_match_all($pattren,$string,$regs);
		foreach($regs[0] as $value){
			$fallback_value=$value;
			$value=trim($value,'}');
			$expl_value=explode(",",$value,2);
			$sql            = 'SELECT name,value FROM `red_email_personalization` where value like \'%'.$expl_value[0].'%\'';
			$query          = $this->db->query($sql);	
			
			if ($query->num_rows() >0){
				$result_array=$query->result_array();	#Fetch resut
				foreach($result_array as $row){
					// Create an array of the required personalisation token and default value from CAMPAIGN
					$arrPersonalizeReplace[$row['name']] = $expl_value[1];
					$fallback_search_arr[]=$fallback_value;
					$fallback_replace_arr[]=$row['value'];
				}
			}
		}
		
		$campaign_content=str_replace($fallback_search_arr, $fallback_replace_arr, $string);
		return $arrPersonalizeReplace;
	}
}
?>