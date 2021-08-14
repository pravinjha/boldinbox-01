<?php
class Is_authorized extends CI_Model
{	
	protected $scookie;
	protected $uri;
	
	//Constructor class with parent constructor
	function __construct(){
		parent::__construct();		
		$this->load->helper('cookie');
		$this->load->library('StatelessCookie');
		$this->scookie = new StatelessCookie();
		$this->redirect_unconfirmed();
		$this->set_default_time_zone();
		$this->set_domain();
		$this->uri = $_SERVER['REQUEST_URI'];
		if(stristr($this->uri,'update_failed_cc') === false  and stripos($_SERVER['REQUEST_URI'],'user_account_inactive_message') === false  and stripos($_SERVER['REQUEST_URI'],'registration_notification') === false){		
			$this->credit_card_expiration();
		}		 
	}
	function redirect_unconfirmed(){  
		if($this->session->userdata('webmaster_id')!=''){
		}else{
			$mid = $this->session->userdata('member_id');
			$rsMemberStatus = $this->db->query("select `status` from red_members where member_id='$mid'");
			$strMemberStatus = $rsMemberStatus->row()->status;
			if(stristr($_SERVER['REQUEST_URI'],'locker/') !== false or stristr($_SERVER['REQUEST_URI'],'asset/') !== false or stristr($_SERVER['REQUEST_URI'],'feedback/') !== false){
				
			}elseif((stristr($_SERVER['REQUEST_URI'],'/registration_notification') !== false   or stristr($_SERVER['REQUEST_URI'],'/user_account_inactive_message') !== false) and $strMemberStatus != 'inactive' and $strMemberStatus != 'unconfirmed'){
				redirect(base_url().'promotions');
				exit;
			}elseif((stristr($_SERVER['REQUEST_URI'],'/registration_notification') === false) and (stristr($_SERVER['REQUEST_URI'],'/confirm_user') === false) and (stristr($_SERVER['REQUEST_URI'],'/user_confirmation_notification') === false)  and (stristr($_SERVER['REQUEST_URI'],'/logout') === false)  and (stristr($_SERVER['REQUEST_URI'],'register_different_user') === false) and ($strMemberStatus=='unconfirmed')){
				redirect(base_url().'user/registration_notification');
				exit;
			}elseif((stripos($_SERVER['REQUEST_URI'],'user_account_inactive_message') === false) and(stripos($_SERVER['REQUEST_URI'],'get_message') === false) and $strMemberStatus == 'inactive'){		
				//$thread_log = config_item('campaign_files').'err_log';			
				//write_file("$thread_log", $_SERVER['REQUEST_URI']."\n");
				redirect(base_url(). 'user/user_account_inactive_message');
				exit;			
			}
		}
	}
	function get_mysqli() { 
		$db = (array)get_instance()->db;
		return mysqli_connect($db['hostname'], $db['username'], $db['password'], $db['database']);
	}
	function escape_str($str,$like=false){
		if (is_array($str)){
            foreach($str as $key => $val){
                $str[$key] = $this->escape_str($val, $like);
            }           
            return $str;
        }
        if (function_exists('mysql_real_escape_string') AND is_resource($this->conn_id)){
            $str = mysql_real_escape_string($str, $this->conn_id);
        }elseif (function_exists('mysql_escape_string')){
            $str = mysql_escape_string($str);
        }else{
            $str = addslashes($str);
        }        
        // escape LIKE condition wildcards
        if ($like === TRUE){
            $str = str_replace(array('%', '_'), array('\\%', '\\_'), $str);
        }
        return $str;
	}

	function set_domain(){	
		if((!isset($_SERVER['SHELL']))  and CAMPAIGN_DOMAIN != 'http://www.'.SYSTEM_DOMAIN_NAME.'/' and $_SERVER['REQUEST_METHOD'] != 'POST'){
			if(stristr($_SERVER['REQUEST_URI'],'locker/') === false or stristr($_SERVER['REQUEST_URI'],'asset/') === false){
			
			if (!isset($_SERVER['REQUEST_URI'])){
				$_SERVER['REQUEST_URI'] = substr($_SERVER['PHP_SELF'],1 );
				if (isset($_SERVER['QUERY_STRING'])) { $_SERVER['REQUEST_URI'].='?'.$_SERVER['QUERY_STRING']; }
			}
		
			if(in_array($_SERVER['SERVER_NAME'], unserialize(OTHER_ALLOWED_DOMAIN_ARRAY))){	 
				$allowed_uri_found = false;
				foreach(unserialize(OTHER_DOMAIN_ALLOWED_PAGE_ARRAY) as $allowed_uri){
					if(stristr($_SERVER['REQUEST_URI'],$allowed_uri) !== false){		
						$allowed_uri_found = true;
					}
				}
				if(!$allowed_uri_found){
					$to = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") ? "http" : "http");
					$to	.= '://www.'.SYSTEM_DOMAIN_NAME.$_SERVER['REQUEST_URI'];	 
					header('location:'.$to,TRUE,301);
					exit;
				}
			}elseif($_SERVER['SERVER_NAME'] == 'www.'.SYSTEM_DOMAIN_NAME){ 
				//$allowed_array = array('/c/', '/a/', '/s/','userboard/signup/subscribe');
				$allowed_array = unserialize(OTHER_DOMAIN_ALLOWED_PAGE_ARRAY);
				
				 
				foreach($allowed_array as $allowed_uri){ 
					if(stristr($_SERVER['REQUEST_URI'],$allowed_uri) !== false){	 			 
						$to	= CAMPAIGN_DOMAIN.substr($_SERVER['REQUEST_URI'],1);	 
						header('location:'.$to,TRUE,301);
						exit;
					}
				}
			}
		}
		}
	}
	
	function set_default_time_zone(){
		if($this->session->userdata('webmaster_id')!='') {
			date_default_timezone_set(WEBMASTER_TIMEZONE);
		}elseif('' != $this->session->userdata('member_time_zone')){		
			date_default_timezone_set($this->session->userdata('member_time_zone'));
		}else{
			$this->session->set_userdata('member_time_zone', 'GMT');
			date_default_timezone_set('GMT');
		}		
	}
	function check_user(){
	//echo 'xx='.$this->session->userdata('member_id')."=yy";
		if($this->session->userdata('member_id')!=''){
			return $this->check_user_status($this->session->userdata('member_id'));			  
		}else{	
			return $this->verifyCookie();	
		}			
	}
	
	function check_user_status($mid){	
		$qry="select * FROM red_members WHERE `member_id`='$mid' and is_deleted=0";
		
		$user_qry=$this->db->query($qry);	#execute query				
		$user_data_array=$user_qry->result_array();	#Fetch resut
		if(count($user_data_array)){
			 
			if($user_data_array[0]['status']=="inactive"){
				redirect('user/user_account_inactive_message');
				exit();
			}
			
			
			//Assign  session to user
			$this->session->set_userdata('member_id', $user_data_array[0]['member_id']);
			$this->session->set_userdata('member_username', $user_data_array[0]['member_username']);
			$this->session->set_userdata('member_email_address', $user_data_array[0]['email_address']);
			$this->session->set_userdata('member_autoresponder_status', $user_data_array[0]['autoresponder_status']);
			
		}		
		
		return true;
	}

	function removeCookieTocken($uid){	
		#$removeTocken ="delete FROM `red_cookie` WHERE `user_id`='$uid'";
		#$this->db->query($removeTocken);	#execute query		
	}
	function verifyCookie(){
		$thisCookie = get_cookie('rc_utcpa');
		  
		$member_id = $this->scookie->getCookieData($thisCookie);
		################################3
		$qry="select * FROM red_members WHERE `member_id`='$member_id' and is_deleted=0";
		
		$user_qry=$this->db->query($qry);	#execute query				
		$user_data_array=$user_qry->result_array();	#Fetch resut
		if(count($user_data_array)){
			if(!$this->scookie->checkCookie($thisCookie, $user_data_array[0]['member_password'])){
				return false;
			}	
			/*
			if(($user_data_array[0]['status']=="inactive")&&($user_data_array[0]['status_inactive_description']=="policy related")){
				echo('user/user_account_inactive_message');
				exit();
			}*/
			
			
			//Assign  session to user
			$this->session->set_userdata('member_id', $user_data_array[0]['member_id']);
			$this->session->set_userdata('member_username', $user_data_array[0]['member_username']);
			$this->session->set_userdata('member_email_address', $user_data_array[0]['email_address']);
			$this->session->set_userdata('member_autoresponder_status', $user_data_array[0]['autoresponder_status']);
			return true;	
		}		
		################################3 
		return false;
		
	}
	function saveCookie($member_id, $pwd, $storedPwd ){
		$cookie = $this->scookie->buildCookie(strtotime("+1 hour"), $member_id, $this->is_login($pwd, $storedPwd));
		$user_cookie = array('name'  => 'utcpa', 'value'  => $cookie, 'expire' => time()+ (7*24*60*60), 'prefix' => 'rc_','path'=>'/' ); 
							 
		set_cookie($user_cookie);

	#setcookie("rc_utcpa", $cookie);	
	}
	function is_login($pwd, $savedPwd){
		return $this->scookie->login($pwd, $savedPwd);
	}
	function hashPassword($pwd){
		return $this->scookie->hashPassword($pwd);
	}
	 
	function getRealIpAddr(){
	 
		if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
		{
		  $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
		}
		elseif (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
		{
		  $ip=$_SERVER['HTTP_CLIENT_IP'];
		}		 
		else
		{
		  $ip=$_SERVER['REMOTE_ADDR'];
		}
		return $ip;
	}

	function createUserFiles(){
		#Check if folder with modulo of User ID exists on server
		$user_dir = $this->session->userdata('member_id') % 1000;
		
		$user_private_path = $this->config->item('user_private').$user_dir ;					
		if(!file_exists($user_private_path)){
					mkdir("$user_private_path",0755);
					chmod("$user_private_path",0777);
		}
		$user_private_path= $user_private_path .'/'.$this->session->userdata('member_id');	
		if(!file_exists($user_private_path))
		{
					mkdir($user_private_path,0777);
					chmod($user_private_path,0777);
		}
		

		$user_public_path = $this->config->item('user_public').$user_dir ;
		
		if(!file_exists($user_public_path))
		{
			mkdir($user_public_path,0777,true);
			chmod($user_public_path,0777);					
		}
		$user_public_path= $user_public_path .'/'.$this->session->userdata('member_id');	
		if(!file_exists($user_public_path))
		{
			mkdir($user_public_path,0777);
			chmod($user_public_path,0777);
		}
		return;
	}	
	function add_fb_og_meta_tags($sites_html, $og_title, $og_type){
		$html = new DOMDocument();
		$previous_value = libxml_use_internal_errors(TRUE);
		@$html->loadHTML($sites_html);
		libxml_clear_errors();
		libxml_use_internal_errors($previous_value);
		
		$html->formatOutput = true;
		$head = $html->getElementsByTagName('head')->item(0);
		$body = $html->getElementsByTagName('body')->item(0)->nodeValue ;
		if( is_object($head)){
			//$og_description = html_entity_decode(substr(strip_tags($body),0,150).'...',ENT_QUOTES);
			$og_description = preg_replace("/\s+/S", " ", html_entity_decode(substr(strip_tags($body),0,150).'...',ENT_QUOTES, 'UTF-8'));
			if(!mb_check_encoding($og_description, 'UTF-8')) $og_description = @utf8_encode($og_description);
			
			$metahttp = $html->createElement('meta');
			$metahttp->setAttribute('property', 'og:type');
			$metahttp->setAttribute('content', "$og_type");	
			$head->appendChild($metahttp);

			$metahttp = $html->createElement('meta');
			$metahttp->setAttribute('property', 'og:title');
			$metahttp->setAttribute('content', "$og_title");	
			$head->appendChild($metahttp);		

			$metahttp = $html->createElement('meta');
			$metahttp->setAttribute('property', 'og:url');
			$metahttp->setAttribute('content', "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");	
			$head->appendChild($metahttp);

			$metahttp = $html->createElement('meta');
			$metahttp->setAttribute('property', 'og:description');
			$metahttp->setAttribute('content', "$og_description");	
			$head->appendChild($metahttp);

			//Get all meta tags and loop through them.
			foreach($html->getElementsByTagName('img') as $siteImage) {
				// fetch image src from HTML body - img tags
				$strImgSrc = $siteImage->getAttribute('src');
				
				$metahttp = $html->createElement('meta');
				$metahttp->setAttribute('property', 'og:image');
				$metahttp->setAttribute('content', "$strImgSrc");	
				$head->appendChild($metahttp);		
			}
		return $html->saveHTML();  
		}
		return $sites_html;

	}	
	function getOGMetaTags($sites_html, $title){
		$strMetaTags = '';
		$html = new DOMDocument();
		$previous_value = libxml_use_internal_errors(TRUE);
		$html->loadHTML($sites_html);
		libxml_clear_errors();
		libxml_use_internal_errors($previous_value);
		 
		$og_description = preg_replace("/\s+/S", " ", html_entity_decode(substr(strip_tags($sites_html),0,150).'...',ENT_QUOTES));
		
		//$strMetaTags .= "<meta property='og:type' content='article' >\n";
		$strMetaTags .= "<meta property='og:title' content='$title' >\n";
		$strMetaTags .= "<meta property='og:url' content='http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]' >\n";
		//$strMetaTags .= "<meta property='og:description' content='$og_description' >\n";
	
		//Get all meta tags and loop through them.
		foreach($html->getElementsByTagName('img') as $siteImage) {
			// fetch image src from HTML body - img tags
			$strImgSrc = $siteImage->getAttribute('src');
			$strMetaTags .= "<meta property='og:image' content='$strImgSrc' >\n";
				
		}
		return $strMetaTags;

	}
	/**
	*	Function to check payment
	*/
	function credit_card_expiration(){	
		$CI =& get_instance();		
		if ($CI->uri->segment(1) != 'change_package' && $CI->uri->segment(1) != 'change_plan' && $CI->uri->segment(1) != 'update_failed_cc' && $CI->uri->segment(1) != 'webmaster' && $CI->uri->segment(2) != 'logout' && $CI->uri->segment(2) != 'get_message' ) {		
			$this->load->model('UserModel');
			$user_packages_array=$this->UserModel->get_user_packages(array('member_id'=>$this->session->userdata('member_id'),'is_deleted'=>0));
			$package_id=$user_packages_array[0]['package_id'];
			$payment_type = $user_packages_array[0]['payment_type'];
			if($package_id>0){
				$next_payment_date = explode('-',$user_packages_array[0]['next_payement_date']);
				// Added in BIB on 2nd February: Starts
				if($this->dateDiff("-",$next_payment_date[1].'-'.$next_payment_date[2].'-'.$next_payment_date[0],date("m-d-Y")) < 0){
					redirect('change_package/index', 'refresh');	
					exit;
				}
				// Added in BIB on 2nd February: Ends
				// Below this line is useless temporarily
				
				$user_data_array=$this->UserModel->get_user_transactions(array('user_id'=>$this->session->userdata('member_id')),0,0,"like");
				if(($user_data_array[0]['gateway_response']!="ADMIN")&&(count($user_data_array)>0)){
					$datetime_arr=explode(' ',$user_data_array[0]['transaction_date']);
					$date_arr=explode('-',$datetime_arr[0]);
					// find out date differnece between current date and subscriber added date
					#$date_diff= $this->dateDiff("-",date("m-d-Y"),$date_arr[1]."-".$date_arr[2]."-".$date_arr[0]);
					#if($date_diff>30){
					$date_diff= $this->dateDiff("-",$next_payment_date[1].'-'.$next_payment_date[2].'-'.$next_payment_date[0],date("m-d-Y"));
					if($date_diff < 0){
						if('FAILURE' == $this->UserModel->lastPaymentStatus()){
							if($payment_type == 1){
								// if payment is paypal then redirect the upgrade package cim 
								$this->UserModel->attachMessage(array('member_id'=>$this->session->userdata('member_id'), 'message_id'=>12)); // Paypal Failed Message
							
								redirect('change_package/index','refresh');
									
							}else{
								$this->UserModel->attachMessage(array('member_id'=>$this->session->userdata('member_id'), 'message_id'=>3));
								redirect('change_package/index', 'refresh');							
							}	
							exit;
						}
						
					}else{
						if($payment_type == 1){
							$this->UserModel->detachMessage(array('member_id'=>$this->session->userdata('member_id'), 'message_id'=>12)); //Paypal Failed Message
						}else{
							$this->UserModel->detachMessage(array('member_id'=>$this->session->userdata('member_id'), 'message_id'=>3));
						}	
					}
				}
			}
		}
	}
	/**
	*	dateDiff function is for calculating number of days between two dates
	*/
	function dateDiff($dformat, $endDate, $beginDate){
		$date_parts1=explode($dformat, $beginDate);
		$date_parts2=explode($dformat, $endDate);
		$start_date=gregoriantojd($date_parts1[0], $date_parts1[1], $date_parts1[2]);
		$end_date=gregoriantojd($date_parts2[0], $date_parts2[1], $date_parts2[2]);
		return   $end_date - $start_date;
	}
	function encodeSubscriber($sid, $semail){		
		return $this->base64UrlSafeEncode($sid.'-'.trim($semail));	
	}
	function decodeSubscriber($encoded_subscriber){
		$arrSubscriber = array();
		$strDecodedSubscriber = $this->base64UrlSafeDecode($encoded_subscriber);
		$arrSubscriber = explode('-',$strDecodedSubscriber);
		
		return $arrSubscriber;
	}
	function base64UrlSafeEncode($data){		
		return (trim($data))? rtrim(strtr(base64_encode(trim($data)), '+/', '-_'), '=') : '';		
	}
	function base64UrlSafeDecode($base64){
	  return base64_decode(strtr($base64, '-_', '+/'));
	}
	
	function webCompatibleString($str){
		$theBad = 	array("“","”","‘","’","…","—","–","Â");
		$theGood = array("\"","\"","'","'","...","-","-","");
		$str = str_replace($theBad,$theGood,$str);
		$str = preg_replace('/[^(\x20-\x7F)\x0A]*/','', $str);
		return $str;
	}
	function ValidateAddress($address) {
		return preg_match('/^(?!(?>(?1)"?(?>\\\[ -~]|[^"])"?(?1)){255,})(?!(?>(?1)"?(?>\\\[ -~]|[^"])"?(?1)){65,}@)((?>(?>(?>((?>(?>(?>\x0D\x0A)?[	 ])+|(?>[	 ]*\x0D\x0A)?[	 ]+)?)(\((?>(?2)(?>[\x01-\x08\x0B\x0C\x0E-\'*-\[\]-\x7F]|\\\[\x00-\x7F]|(?3)))*(?2)\)))+(?2))|(?2))?)([!#-\'*+\/-9=?^-~-]+|"(?>(?2)(?>[\x01-\x08\x0B\x0C\x0E-!#-\[\]-\x7F]|\\\[\x00-\x7F]))*(?2)")(?>(?1)\.(?1)(?4))*(?1)@(?!(?1)[a-z0-9-]{64,})(?1)(?>([a-z0-9](?>[a-z0-9-]*[a-z0-9])?)(?>(?1)\.(?!(?1)[a-z0-9-]{64,})(?1)(?5)){0,126}|\[(?:(?>IPv6:(?>([a-f0-9]{1,4})(?>:(?6)){7}|(?!(?:.*[a-f0-9][:\]]){7,})((?6)(?>:(?6)){0,5})?::(?7)?))|(?>(?>IPv6:(?>(?6)(?>:(?6)){5}:|(?!(?:.*[a-f0-9]:){5,})(?8)?::(?>((?6)(?>:(?6)){0,3}):)?))?(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[1-9]?[0-9])(?>\.(?9)){3}))\])(?1)$/isD', $address);
	}
	/*
	* Function to encrypt string
	**/
	function encryptor($action, $string) {
    	$output = false;

    	$encrypt_method = "AES-256-CBC";
    	//pls set your unique hashing key
    	$secret_key = 'j0TumK0h0P@s@nDw@h!b@@Tk@h3ng3';
    	$secret_iv = 'pr@v!njh@r@nd0m';

   		// hash
    	$key = hash('sha256', $secret_key);

    	// iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
    	$iv = substr(hash('sha256', $secret_iv), 0, 16);

    	//do the encyption given text/string/number
    	if( $action == 'encrypt' ) {
        	$output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
        	$output = $this->base64UrlSafeEncode($output);
    	}elseif( $action == 'decrypt' ){
    		//decrypt the given text/string/number
    		 
        	$output = (trim($string) !='')?openssl_decrypt($this->base64UrlSafeDecode(rawurldecode($string)), $encrypt_method, $key, 0, $iv) : '';
    	}

    return $output;
	}
	/*
	** Function to show Used Contacts for the  opted plan
	**/
	function showBar($mid){
		$arrPlanDetail = array();
		$sqlPlanMaxContact = "select package_max_contacts from red_packages p inner join red_member_packages mp on p.package_id=mp.package_id where mp.member_id='$mid'";
		$arrPlanDetail['PlanMaxContact'] = $this->db->query($sqlPlanMaxContact)->row()->package_max_contacts;
		$arrPlanDetail['totContacts'] = $this->db->query("select count(subscriber_id) tc from red_email_subscribers where subscriber_created_by='$mid' and subscriber_status=1 and is_deleted=0")->row()->tc;		
		return $arrPlanDetail;
	}
	/*
	** Function to return STATS table for specific campaign_id
	**/
	function getStatsTable($cid=0){
		$arrStatsTable = unserialize(STATS_DBTABLE_ARRAY);
		
		$statsTableIndex = 0;
		if($cid > 0){
			$statsTableIndex = $this->db->query("select stats_table_id from red_email_campaigns where campaign_id = '$cid'")->row()->stats_table_id;
		}
		
		return $arrStatsTable[$statsTableIndex];
	}
}
?>