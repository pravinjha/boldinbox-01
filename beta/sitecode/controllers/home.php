<?php
class Home extends CI_Controller
{	
	
	function home(){
		parent::__construct();
		
		parse_str($_SERVER['QUERY_STRING'],$_GET);		
		$this->load->helper('notification');
		$this->load->helper('transactional_notification');
		$this->load->helper('send_mail');
		$this->load->helper('cookie');
		$this->load->library('encrypt');
		force_ssl();	
	}
	function index(){
		// Start: Following code is to set cookie for users came via google adword
		$current_url_like= $_SERVER['HTTP_HOST']  . $_SERVER['REQUEST_URI'];		
		parse_str(array_pop(explode('?',$_SERVER['REQUEST_URI'],2)),$_GET);
		//print_r($_GET);
		$thisIP = $this->is_authorized->getRealIpAddr(); 
		$utm_source = $_GET['utm_source'];
		$utm_medium = $_GET['utm_medium'];
		$utm_campaign = $_GET['utm_campaign'];
		// Set time user entered in $time_entered variable in GMT, 24 format. (Format: yyyymmdd_hhmm)
		$time_entered = gmdate('Y-m-d H:i:s', gmmktime());	
		$visitorDetail = $time_entered . ':-:' . ip2long($thisIP). ':-:' .$utm_source. ':-:' .$utm_medium. ':-:' .$utm_campaign ;
		$cookie = array('name'=>'rctrack','value'=>$this->encrypt->encode($visitorDetail),'expire' => 60*60*24*365*2,'prefix' => 'rc_','secure' => false);		
		//echo $this->encrypt->decode($this->encrypt->encode($visitorDetail));
		//set_cookie($cookie);
		// End
		
		// Load the seo model which interact with database
		$this->load->model('SeoModel');
		$seo_array=$this->SeoModel->get_seo_data(array('is_delete'=>0));
		//$wufoo_url = 'qm29y9s0dgz7iq';
		//Load the header, home page and footer view of index page		
		$this->load->view('header_outer',array('seo_array'=>$seo_array,'title'=>'Home Page', 'logoclass' => 'class="home"', 'wufoo_url'=>$wufoo_url));
		$this->load->view('home/index');
		$this->load->view('footer_outer');
	}
	// google-adword: gadw, capterra: capterra, facebook:fb
	function st($siteID='', $url_id=0){
		if($siteID != ''){
			$rsReferral = $this->db->query("select id, referrer_name from red_member_referrer where referrer_string='$siteID'");
			//$arrSite = array('gadw'=>'google_adword','capterra'=>'capterra','fb'=>'facebook','getapp'=>'GetApp');
			//$arrSiteId = array('gadw'=>1,'capterra'=>2,'fb'=>3,'getapp'=>4);
			//$thisSite = $arrSite[$siteID];
			//$thisSiteId = $arrSiteId[$siteID];
			$thisSite = $rsReferral->row()->referrer_name;
			$thisSiteId = $rsReferral->row()->id;
			
			$thisIP = ip2long($this->is_authorized->getRealIpAddr());
			$thisReferer = $_SERVER['HTTP_REFERER'];
			// Set time user entered in $time_entered variable in GMT, 24 format. (Format: yyyymmdd_hhmm)
			$time_entered = gmdate('Y-m-d\TH:i:s\Z', gmmktime());	
			$visitorDetail = $time_entered . ':-:' . $thisIP. ':-:' .$thisSite ;		
			$this->db->query("insert into red_member_referral set ip_logged = '$thisIP', referer_logged='$thisSiteId', referer_url='$thisReferer', vistor_detail='$visitorDetail' ON DUPLICATE KEY UPDATE referer_url='$thisReferer', vistor_detail='$visitorDetail'");	
			 	
			set_cookie(array('name'=>'rctrack','value'=>$this->encrypt->encode($visitorDetail),'expire' => '63072000', 'path'   => '/', 'prefix' => 'prc_','secure' => true));
		}
		header ("Location: " . urldecode($this->getURL($url_id)));
		
		exit;
	}
	function about(){	
		//send_tmail( 'web-2kn5f@mail-tester.com', 'sumit@multiplat.in',"BoldInbox", 'Goa Special','Email content','Email content');
		//send_tmail( 'sumitthakkar82@gmail.com','sumit@multiplat.in',"BoldInbox", 'Goa Special','Email content','Email content');
		//send_tmail( 'pravinjha@gmail.com', 'sumit@multiplat.in',"BoldInbox", 'Visit Goa, November 2016','Email content','Email content');
		//send_bib_mail('pravinjha@gmail.com', 'sumitthakkar82@gmail.com',"BoldInbox", 'Test email','Email content','Email content');
		
		$this->load->view('header_outer',array('title'=>'About Us'));
		$this->load->view('home/about');
		$this->load->view('footer_outer');	
	}
	function contact(){
			if(isset($_POST)){
			if(empty($_POST['g-recaptcha-response'])){
				$this->messages->add('Confirm you are not a Robot.', 'error');
			}else{
				$secret = '6LcN4wYUAAAAANLx58IuKlAm278fKmzS96o-gzpm';
				//get verify response data
				$verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$_POST['g-recaptcha-response']);
				$responseData = json_decode($verifyResponse);
					
				if(!$responseData->success){				
					$this->messages->add('Robot verification failed, please try again.', 'error');
				}else{
					
					$this->form_validation->set_rules('name', 'Name', 'trim|required|xss_clean');
					$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');				
					
					$data['name']     			= $this->input->post('name');
					$data['phone']     			= $this->input->post('phone');
					$data['email']     			= $this->input->post('email');
					$data['desc']     			= $this->input->post('desc');
					if ( $this->form_validation->run() !== FALSE ){
						$data['msg1']		=	'Your message submitted!';
						
						$message ='<table border="0" width="50%" cellpadding="0" cellspacing="1">';
						$message .='<tr><td width="50" align"left"><strong>Name</strong></td><td width="50" align"left">'.$data['name'].'</td></tr>';
						$message .='<tr><td width="50" align"left"><strong>Phone</strong></td><td width="50" align"left">'.$data['phone'].'</td></tr>';
						$message .='<tr><td width="50" align"left"><strong>Email</strong></td><td width="50" align"left">'.$data['email'].'</td></tr>';
						$message .='<tr><td width="50" align"left"><strong>Message</strong></td><td width="50" align"left">'.$data['desc'].'</td></tr>';
						$message .='</table>';

						echo $text_message = strip_tags($message);

						contact_mail(SYSTEM_EMAIL_FROM, $data['email']  ,$data['name']  , 'Contact Us | BoldInbox',$message,$text_message); 
						 
						
						$data['name']     			= '';
						$data['phone']     			= '';
						$data['email']     			= '';
						$data['desc']     			= '';
					}
				}	
			}
		
		}
		$this->load->view('header_outer',array('title'=>'Contact Us | BoldInbox','show_bottom_bar'=>true));
		$this->load->view('home/contactus',$data);
		$this->load->view('footer_outer');	
	}	
	function features($str_for_seo='Manage-and-Grow-Your-Email-Lists',$category_id=8){
		$this->load->model('supportModel');
		$support_data=$this->supportModel->get_category_data(array('is_delete'=>0,'is_support'=>0));
		$product_data=$this->supportModel->get_category_productdata(array('rsp.is_delete'=>0,'rsp.is_active'=>0,'rsp.category_id'=>$category_id));		
		$this->load->view('header_outer',array('seo_array'=>$seo_array,'title'=>'Features-'.$product_data[0]['category']));		
		$this->load->view('home/features',array('support_data'=>$support_data,'product_data'=>$product_data, 'selected_category_id'=>$category_id));
		$this->load->view('footer_outer');		
	}
	function terms(){
		$this->load->view('header_outer',array('title'=>'Terms Condition'));
		$this->load->view('home/terms-condition');
		$this->load->view('footer_outer');	
	}
	function pricing(){
		$this->load->model('UserModel');
		// Fetch user data from database
		
		$packages=$this->UserModel->get_packages_data(array('package_deleted'=>0,'package_status'=>1,'is_special'=>0,'package_deleted'=>0),28);
		
		// Recieve any messages to be shown, when package is added or updated
		$messages=$this->messages->get();
		
		//Load the header, register and footer view of index page
		$this->load->view('header_outer',array('title'=>'Pricing: Email Marketing'));
		$this->load->view('home/pricing',array('packages'=>$packages,'packages_yearly'=>$packages_yearly));
		$this->load->view('footer_outer');
	}
	function support($str_for_seo='General',$category_id=1,$product_id=0){
	
		$this->load->model('supportModel');
		$support_data=$this->supportModel->get_category_data(array('is_delete'=>0,'is_support'=>1));
		$product_data=$this->supportModel->get_category_productdata(array('rsp.is_delete'=>0,'rsp.is_active'=>1,'rsp.category_id'=>$category_id));		
		$this->load->view('header_outer',array('seo_array'=>$seo_array,'title'=>'Support-'.$product_data[0]['category']));		
		$this->load->view('home/support',array('support_data'=>$support_data,'product_data'=>$product_data, 'selected_category_id'=>$category_id, 'selected_product_id'=>$product_id));
		$this->load->view('footer_outer');		 
	}
	
	function search_result($start=0){  
	  $this->load->model('supportModel');
	  $config['base_url']   = base_url().'home/search_result';
	  $config['total_rows']  = $this->supportModel->count_search_product_result(array('rsp.is_delete'=>0,'rsp.is_active'=>1));	  
	  $config['per_page']   = 10; // Max number of items you want shown per page
	  $config['uri_segment']  = 3;
	  $config['num_links']  = 4; // Number of "digit" links to show before/after the currently viewed page
	  $config['full_tag_open'] =  '<ul class="pagination">';
	  $config['full_tag_close']  =  '</ul>';
	  $config['cur_tag_open']  =  '<li><a class="selected">';
	  $config['cur_tag_close']  =  '</a></li>';
	  $config['first_tag_open']  =  '<li>';
	  $config['first_tag_close']  =  '</li>';
	  $config['last_tag_open']  =  '<li>';
	  $config['last_tag_close']  =  '</li>';
	  $config['num_tag_open']  =  '<li>';
	  $config['num_tag_close']  =  '</li>';
	  $config['next_tag_open']  =  '<li>';
	  $config['next_tag_close']  =  '</li>';
	  $config['prev_tag_open']  = '<li>';
	  $config['prev_tag_close']  =  '</li>'; 
	  $this->pagination->initialize($config);
		$this->form_validation->set_rules('search_text', 'search string', 'required|min_length[4]');
		if(!$this->form_validation->run()){	
			$this->messages->add('Please enter text (at least 4 characters) to search...', 'error');
		}else{			
			$product_data=$this->supportModel->search_product_result(array('rsp.is_delete'=>0,'rsp.is_active'=>1),$config['per_page'],$start);
			$paging_links=$this->pagination->create_links();
		}		
		$messages=$this->messages->get();
		$this->load->view('header_outer',array('seo_array'=>$seo_array,'title'=>'Support'));  
		$this->load->view('home/support_search_result',array('product_data'=>$product_data,'paging_links'=>$paging_links,'search_text'=>$this->input->post('search_text')));
		$this->load->view('footer_outer'); 
	}	
	
	function getURL($uid){
		switch ($uid) {
			case 1:
				return urlencode(base_url().'email-marketing-features');
				break;
			case 2:
				return urlencode(base_url().'pricing');
				break;
			case 3:
				return urlencode(base_url().'signup');
				break;
			case 4:
				return urlencode(base_url().'contact');
				break;
			default:
			   return urlencode(base_url());
		}
	}	
	function mailtest(){
		$msg = 'Hello pravinjha, 

Your request to add a new from-email "sumit@boldinbox.com" for your campaigns, is approved now.
Now you can use this address in your BoldInbox account and send emails from this address.

Thanks,
The BoldInbox Team';
		//send_tmail('sumit@multiplat.in', 'support@boldinbox.com', 'BoldInbox Support', 'Confirm your new "From Email"',nl2br($msg),$msg);
		//bib_transactional('sumitthakkar1982@aol.com', 'support@boldinbox.com', 'BoldInbox Support', 'Confirm your new "From Email"',nl2br($msg),$msg);
		//bib_transactional('web-otnep@mail-tester.com', 'sumit@boldinbox.com', 'BoldInbox Support', 'Confirm your new "From Email"',nl2br($msg),$msg);
		//bib_transactional('web-tsrq8@mail-tester.com', 'sumit@boldinbox.com', 'BoldInbox Support', 'Confirm your new "From Email"',nl2br($msg),$msg);
		bib_transactional('pravinjha@gmail.com', 'sumit@boldinbox.com', 'BoldInbox Support', 'Confirm your new "From Email"',nl2br($msg),$msg);
	}
}
?>