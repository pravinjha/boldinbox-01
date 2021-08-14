<?php
class Account extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		
		$this->load->model('ConfigurationModel');
		$this->confg_arr=$this->ConfigurationModel->get_site_configuration_data_as_array();
		if($this->confg_arr['maintenance_mode'] !='no'){
			redirect ("/site_under_maintenance/");
			exit;
		}
		
		# check via common model
		if(!$this->is_authorized->check_user())		 
			redirect('user/index');	

		force_ssl();			
	}
	function index(){
		$this->account();
	}
	function account(){
		$thisMid = $this->session->userdata('member_id');
		 
		// Load the user model which interact with database
		$this->load->model('UserModel');
		// To check if form is submitted
		if($this->input->post('action')=='Save Details'){
			// Validation rules are applied
			$this->form_validation->set_rules('first_name', 'First name', 'required');
			$this->form_validation->set_rules('last_name', 'Last name', 'required');
			$this->form_validation->set_rules('email_address', 'Email address', 'required|valid_email');
			$this->form_validation->set_rules('company', 'Company', 'required');
			$this->form_validation->set_rules('address_line_1', 'Address', 'required');
			$this->form_validation->set_rules('city', 'City', 'required');
			$this->form_validation->set_rules('state', 'State', 'required');
			$this->form_validation->set_rules('zipcode', 'Zip code', 'required');
			$this->form_validation->set_rules('country', 'Country', 'required');
			
			// To check form is validated
			if($this->form_validation->run()==true){
				$update_info=array('first_name'=>$this->input->post('first_name'),
				'last_name'=>$this->input->post('last_name'),
				'email_address'=>$this->input->post('email_address'),
				'company'=>$this->input->post('company'),
				'address_line_1'=>$this->input->post('address_line_1'),
				'city'=>$this->input->post('city'),
				'state'=>$this->input->post('state'),
				'zipcode'=>$this->input->post('zipcode'),
				'country'=>$this->input->post('country'),
				'country_custom'=>$this->input->post('country_custom'),
				'member_time_zone'=>$this->input->post('member_time_zone')
				);
				// update user info
				$this->UserModel->update_user($update_info,array('member_id'=>$thisMid));
				$this->session->set_userdata('member_time_zone',$this->input->post('member_time_zone'));
				// Assign  success message by message class
				$this->messages->add('User updated successfully', 'success');					
				redirect('account/index');
			}
		}
		// Recieve any messages to be shown, when campaign is added or updated
		$messages=$this->messages->get();
		//Fetch User Data from database
		$user_data=$this->UserModel->get_user_data(array('member_id'=>$thisMid));
		$user_data=$user_data[0];
		
		// Fetch user packages from database
		$user_transactions=array();
		$user_transactions=$this->UserModel->get_user_transactions(array('user_id'=>$thisMid ,'gateway !='=>'ADMIN','t.is_deleted'=>0),5,0,"like");
		
		//Fetch credit card info of user
		$user_credit_card_info=array();
		$user_credit_card_info=$this->UserModel->get_user_credit_card_info(array('m.member_id'=>$thisMid ,'m.is_deleted'=>0));
		if($user_data['show_sent_counter']){
			$rsCountContactsInQueueu = $this->db->query("select count(subscriber_id) c from red_email_queue where user_id='$thisMid'");			 
			$user_credit_card_info[0]['campaign_sent_counter'] = $user_credit_card_info[0]['campaign_sent_counter'] + $rsCountContactsInQueueu->row()->c;		
			$user_credit_card_info[0]['max_campaign_quota'] = $user_credit_card_info[0]['max_campaign_quota'];
			$rsCountContactsInQueueu->free_result();
		}
		 $package_detail = $this->UserModel->get_user_package(array('member_id' => $thisMid));
		//Fetch Country name
		$country_info=$this->UserModel->get_country_data();
		$contactDetail = $this->is_authorized->showBar($this->session->userdata('member_id'));		
		$this->load->view('header',array('title'=>'My Account','contactDetail'=>$contactDetail));
		$this->load->view('user/account',array('package_detail'=>$package_detail[0],'user_info'=>$user_data,'user_transactions'=>$user_transactions,'user_credit_card_info'=>$user_credit_card_info[0],'messages'=>$messages,'country_info'=>$country_info));
		$this->load->view('footer');
	}
	/**
	  *	Function to display list of all transaction in fancybox
	 */ 
	function invoices_view_all(){
		 
		 
		// Load the user model which interact with database
		$this->load->model('UserModel');
		// Fetch user packages from database
		$user_transactions=array();
		$user_transactions=$this->UserModel->get_user_transactions(array('user_id'=>$this->session->userdata('member_id'),'gateway !='=>'ADMIN','t.is_deleted'=>0),0,0,'like');
		if($_SERVER['HTTP_REFERER']!=base_url()."account/invoices_view_all"){
			$this->session->set_userdata('HTTP_REFERER', $_SERVER['HTTP_REFERER']);
			$previous_page_url=$_SERVER['HTTP_REFERER'];
		}else{
			$previous_page_url=$this->session->userdata('HTTP_REFERER');
		}
		//Loads header, Invoices list and footer view.
		$this->load->view('header',array('title'=>'My Invoices','previous_page_url'=>$previous_page_url));
		$this->load->view('user/invoices_list',array('user_transactions'=>$user_transactions));
		$this->load->view('footer-inner-red');
	}
	/**
	  * Function to display transaction detail
	 */
	function billing_detail($id=0){
		 
		// Load the user model which interact with database
		$this->load->model('UserModel');
		// Fetch user packages from database
		$user_packages=array();
		$user_transactions=$this->UserModel->get_user_transactions(array('user_id'=>$this->session->userdata('member_id'),'transaction_id'=>$id),0);
		/* echo "<pre>";
		print_R($user_transactions);
		die; */
		$previous_page_url=base_url()."account/index";
		$this->load->view('user/billing_detail',array('user_transactions'=>$user_transactions[0]));
	}
	function email_billing_receipt($user_id=0,$transaction_id=0){
		// Load the user model which interact with database
		$this->load->model('UserModel');
		// Fetch user data from database
		$user_data_array=$this->UserModel->get_user_transactions(array('user_id'=>$user_id,'transaction_id'=>$transaction_id));
		
		if($user_data_array[0]['first_name']){
			$user_name=$user_data_array[0]['first_name'];
		}else{
			$user_name=$user_data_array[0]['member_username'];
		}
		$order_id=$user_data_array[0]['transaction_id'];
		$datetime = strtotime($user_data_array[0]['transaction_date']);
		$purchase_date = date("F d, Y", $datetime);
		$current_plan=$user_data_array[0]['package_min_contacts']."-".$user_data_array[0]['package_max_contacts'];
		if($user_data_array[0]['gateway_response']=="ADMIN"){
			$amount="$0";
		}else{
			$amount="$".$user_data_array[0]['amount_paid'];
		}
		// Start: By CB
		$payment_type = $user_data_array[0]['gateway'];
		if($payment_type == 'AUTHORIZE'){
			$payment_type = 'Credit Card';
		}
		// End: By CB
		$card_ending_in=$user_data_array[0]['credit_card_last_digit'];
		$billed_to=$user_data_array[0]['first_name']." ".$user_data_array[0]['last_name'];
		$company=$user_data_array[0]['company'];
		$phone_number=$user_data_array[0]['phone_number'];
		$email_address=$user_data_array[0]['email_address'];
		$billing_address=$user_data_array[0]['address'];
		$user_info=array($user_name,$order_id,$purchase_date,$current_plan,$amount,$card_ending_in,$billed_to,$company,$phone_number,$email_address,$billing_address,$payment_type);
		$this->load->helper('transactional_notification');
		//create_transactional_notification("billing_receipt_notification",$user_info,$user_data_array[0]['email_address']);
		create_transactional_notification("billing_receipt_notification",$user_info,'pravinjha@gmail.com');
	}
	
	/**
		Function to submit user info from Diy footer dialog box
	**/
	function user_info(){
		 
		$this->form_validation->set_rules('company', 'Company', 'required');
		$this->form_validation->set_rules('address_line_1', 'Address', 'required');
		$this->form_validation->set_rules('city', 'City', 'required');
		$this->form_validation->set_rules('state', 'State', 'required');
		$this->form_validation->set_rules('zipcode', 'Zip code', 'required');
		$this->form_validation->set_rules('country', 'Country', 'required');
			
		// To check form is validated
		if($this->form_validation->run()==true){
			$update_info=array('company'=>$this->input->post('company'),'address_line_1'=>$this->input->post('address_line_1'),'city'=>$this->input->post('city'),'state'=>$this->input->post('state'),'zipcode'=>$this->input->post('zipcode'),'country'=>$this->input->post('country'),'country_custom'=>$this->input->post('country_custom'));
			// Load the user model which interact with database
			$this->load->model('UserModel');
			// update user info
			$this->UserModel->update_user($update_info,array('member_id'=>$this->session->userdata('member_id')));
			// Assign  success message by message class
			echo 'success:User updated successfully';
		}
		// If validation errors in submitted values by user then print validation errors
		if(validation_errors()){
			echo 'error:'.validation_errors();
		}
	}
	/**
		Function to get user info for Diy footer dialog box
	**/
	function get_user_info(){
		 
		// Load the user model which interact with database
		$this->load->model('UserModel');
		// Fetch user data from database
		$user_data_array=$this->UserModel->get_user_data(array('member_id'=>$this->session->userdata('member_id')));
		echo $user_data_array[0]['company']."|".$user_data_array[0]['address_line_1']."|".$user_data_array[0]['city']."|".$user_data_array[0]['state']."|".$user_data_array[0]['zipcode']."|".$user_data_array[0]['country_id']."|".$user_data_array[0]['country_name'];
	}
}
/* End of file */
?>