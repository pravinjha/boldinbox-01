<?php
/**
* A Change_package class
*
* This class is to  change the memeber package
*
* @version 1.0
* @author Pravin Jha <pravinjha@gmail.com>
* @project Boldinbox

*/
class Change_package extends CI_Controller{
	private $PUM_MERCHANT_KEY;
	private $PUM_MERCHANT_SALT;
	private $PAYU_BASE_URL;
	private $TEST_MODE;

	function __construct(){
		parent::__construct();
		
		if(!$this->is_authorized->check_user()){
			redirect('user/index');exit;
		}elseif($this->session->userdata('member_id')==''){		
			redirect('user/index');exit;
		}	
		force_ssl(); 
		
		
		//  Collect login info to authorize payment		
		$this->load->library('Billingcim'); # load billing library
		$this->billingcim->loginKey($this->config->item('loginname'), $this->config->item('transactionkey'), $this->config->item('test_mode'));
		
		$this->load->model('UserModel');
		$this->load->model('BillingModel');		
		$this->load->model('userboard/Subscriber_Model');
		$this->load->model('payment/payment_model');
		$this->load->model('Activity_Model');
		$this->load->model('ConfigurationModel');
		
		$this->load->helper('notification'); 
		$this->load->helper('transactional_notification');	
		$this->load->helper('admin_notification');
		$this->confg_arr=$this->ConfigurationModel->get_site_configuration_data_as_array();
		
		$this->TEST_MODE = FALSE;
		$this->PUM_MERCHANT_KEY = ($this->TEST_MODE)?'gtKFFx' : 'F3ESJu';
		$this->PUM_MERCHANT_SALT = ($this->TEST_MODE)?'eCwWELxi' : 'QIacjtPJ';
		$this->PAYU_BASE_URL =  ($this->TEST_MODE)? 'https://test.payu.in' : 'https://secure.payu.in';
	}
	/**
	* Function index is  to display view of upgrade packages using PayUmoney
	* to submit selected package in database	
	*	
	*/
	function index(){		
		$thisMemberId = $this->session->userdata('member_id'); 
		$arrUserProfile = $this->UserModel->get_user_packages(array('member_id' => $thisMemberId));		
		
		//  Recieve any messages to be shown, when campaign is added or updated
		$messages=$this->messages->get();		
		
		// Fetch packages data from database		
		$packages=$this->UserModel->get_packages_data(array('package_deleted'=>0,'package_status'=>1,'is_special'=>0),16);

		
		//Retrieve checked package id		
		$current_package_id = $arrUserProfile[0]['package_id'];
		
		
		// Fetch Country name
		$country_info =$this->UserModel->get_country_data();
		// Get previousoly visited  page url
		$previous_page_url=$this->get_previous_page_url();
		$contactDetail = $this->is_authorized->showBar($this->session->userdata('member_id'));		
		foreach($packages as $r){
			if($contactDetail['totContacts'] > $r['package_min_contacts'] && $contactDetail['totContacts'] <= $r['package_max_contacts']){
				$eligiblePackage = $r['package_id'];
			}			
		}
		
		
		$this->load->view('header',array('title'=>'Change Package','previous_page_url'=>$previous_page_url,'contactDetail'=>$contactDetail));
		$this->load->view('user/change_plan',array('packages'=>$packages, 'eligiblePackage'=>$eligiblePackage, 'user_package'=>$user_packages_array[0],'selected_package'=>$current_package_id,'messages'=>$messages,'posted'=>$posted, 'arrUserProfile'=>$arrUserProfile, 'country_info'=>$country_info, 'mode'=>$mode));
		$this->load->view('footer');	
	}
	function pum_send(){		
		$thisMemberId = $this->session->userdata('member_id'); 
		$arrUserProfile = $this->UserModel->get_user_packages(array('member_id' => $thisMemberId));
		
		$posted = array();
		$posted['key']	 = $this->PUM_MERCHANT_KEY;
		$posted['surl']	 = base_url().'change_package/pum_success/';
		$posted['furl']	 = base_url().'change_package/pum_failure/';		
		$posted['curl']	 = base_url().'change_package/';
		$posted['action'] = $this->PAYU_BASE_URL . '/_payment';
		if($this->input->post('selected_package')!=''){				 
			$this->form_validation->set_rules('selected_package', 'Plan/Package', 'required');		
			$this->form_validation->set_rules('firstname', 'First name', 'required');							
			$this->form_validation->set_rules('lastname', 'Last name', 'required');							
			$this->form_validation->set_rules('address', 'Address', 'required');
			$this->form_validation->set_rules('city', 'City', 'required');
			$this->form_validation->set_rules('state', 'State', 'required');
			$this->form_validation->set_rules('zipcode', 'zipcode', 'required');
			$this->form_validation->set_rules('country', 'Country', 'required');
			$this->form_validation->set_rules('email', 'Email Address', 'required');
			
			
			if($this->form_validation->run()==true){
				$hash = '';
				$posted['member_id'] = $thisMemberId;
				
				$posted['txnid'] = substr(hash('sha256', mt_rand() . microtime()), 0, 20);
				$posted['selected_package'] = $this->input->post('selected_package');				
				$posted['payment_months'] = $this->input->post('payment_months');				
				$posted['promotion_code'] = $this->input->post('promotion_code');				
				$posted['new_package_id'] = $this->input->post('selected_package');				
				$posted['firstname'] = $this->input->post('firstname');				
				$posted['lastname'] = $this->input->post('lastname');				
				$posted['address'] = $this->input->post('address');				
				$posted['city'] = $this->input->post('city');				
				$posted['state'] = $this->input->post('state');				
				$posted['zipcode'] = $this->input->post('zipcode');				
				$posted['country'] = $this->input->post('country');				
				$posted['email'] = $this->input->post('email');				
				$posted['phone'] = $this->input->post('phone');			

				// Update member_package table with billing address	
				$this->db->query("update red_member_packages set first_name='".$posted['firstname']."', last_name='".$posted['lastname']."', address='".$posted['address']."', city='".$posted['city']."', state='".$posted['state']."', zip='".$posted['zipcode']."', country='".$posted['country']."', email_address='".$posted['email']."', phone_number='".$posted['phone']."' where member_id='$thisMemberId' ");	

				$arrSelectedPackage		= $this->UserModel->get_packages_data( array('package_id' => $posted['new_package_id']) );
				$posted['amount']	= $this->getPayableAmount($posted['selected_package'], $posted['payment_months'], $posted['promotion_code']) * 68;
					
				$str_hash = $this->PUM_MERCHANT_KEY .'|' .$posted['txnid'].'|'.$posted['amount'].'|'.$posted['new_package_id'] .'|'.$posted['firstname'] .'|'.$posted['email'] .'|'.$thisMemberId.'|'.$posted['payment_months'].'|'.$posted['promotion_code'].'||||||||'.$this->PUM_MERCHANT_SALT;	
				$posted['hash']  = strtolower(hash('sha512',$str_hash));								
			}else{
				$this->messages->add('All fields are mandatory', 'success');
				redirect('change_package/');exit;
			}
			
		}
		  
		
		$this->load->view('user/change_plan_pum_send',array('posted'=>$posted));
		
	}
	
	function pum_success(){
		
		$status		= $_POST["status"];
		$firstname	= $_POST["firstname"];
		$amount		= $_POST["amount"];
		$txnid		= $_POST["txnid"];
		$posted_hash= $_POST["hash"];
		$key		= $_POST["key"];
		$productinfo= $_POST["productinfo"];
		$email		= $_POST["email"];
		$mid		= $_POST["udf1"];
		$payment_months		= $_POST["udf2"];
		$promotion_code		= $_POST["udf3"];
		
		
		$salt = $this->PUM_MERCHANT_SALT;
		
		if (isset($_POST["additionalCharges"])) {
		   $additionalCharges=$_POST["additionalCharges"];
			$retHashSeq = $additionalCharges.'|'.$salt.'|'.$status.'||||||||'.$promotion_code.'|'.$payment_months.'|'.$mid.'|'.$email.'|'.$firstname.'|'.$productinfo.'|'.$amount.'|'.$txnid.'|'.$key;
			
		}else {	  

			$retHashSeq = $salt.'|'.$status.'||||||||'.$promotion_code.'|'.$payment_months.'|'.$mid.'|'.$email.'|'.$firstname.'|'.$productinfo.'|'.$amount.'|'.$txnid.'|'.$key;

		}
		
		//$hash = hash("sha512", $retHashSeq);
		
		foreach($_POST as $k=>$v)$debugMsg .= ($k). ' = '. $v." \n";
		send_mail(DEVELOPER_EMAIL, SYSTEM_EMAIL_FROM  ,'system' , SYSTEM_DOMAIN_NAME.': Payment Detail',$debugMsg.'---'.$retHashSeq,$debugMsg.'---'.$retHashSeq);		
		
		// START: Update DB member_packages & member_transactions
		$start_payment_date = date("Y-m-d");
		$next_payement_timestamp = strtotime(date("Y-m-d", strtotime($start_payment_date)) . "+".$payment_months." month");            
        $next_payement_date = date('Y-m-d', $next_payement_timestamp);  
		
		$this->UserModel->update_member_package(array('package_id'=>$productinfo, 'amount'=>$amount, 'is_payment'=>1, 'is_admin'=>0, 'payment_type'=>2, 'start_payment_date'=>$start_payment_date, 'next_payement_date'=>$next_payement_date,'member_payment_declined_count'=>0),array('member_id'=>$mid));
		
		$this->db->query("insert into red_member_transactions set user_id='$mid', package_id='$productinfo', amount_paid='$amount', gateway='PAYUMONEY', status='SUCCESS', payment_type=0, gateway_response='$debugMsg'");
		// ENDS:  Update DB member_packages & member_transactions
				
		
		// Get previousoly visited  page url
		$previous_page_url=$this->get_previous_page_url();
		$contactDetail = $this->is_authorized->showBar($this->session->userdata('member_id'));		
		echo $this->load->view('header',array('title'=>'Change Package','previous_page_url'=>$previous_page_url,'contactDetail'=>$contactDetail), true);			
		//if ($hash != $posted_hash) {
		//   echo "Invalid Transaction. Please try again";
		//}else {           	   
			  echo "<div style='width:100%; padding:20px 50px;'><h3>Thank You. Your order status is ". $status .".</h3>
					<h4>Your Transaction ID for this transaction is ".$txnid.".</h4>
					<h4>We have received a payment of INR. " . $amount . ". </h4></div>";          
		//}
		echo $this->load->view('footer',array(),true);	
	}
	
	function pum_failure(){
		$status		= $_POST["status"];
		$firstname	= $_POST["firstname"];
		$amount		= $_POST["amount"];
		$txnid		= $_POST["txnid"];
		$posted_hash= $_POST["hash"];
		$key		= $_POST["key"];
		$productinfo= $_POST["productinfo"];
		$email		= $_POST["email"];
		$mid		= $_POST["udf1"];
		$payment_months		= $_POST["udf2"];
		$promotion_code		= $_POST["udf3"];
		
		foreach($_POST as $k=>$v)$debugMsg .= ($k). ' = '. $v." \n";
		send_mail(DEVELOPER_EMAIL, SYSTEM_EMAIL_FROM  ,'system' , SYSTEM_DOMAIN_NAME.': Payment Detail',$debugMsg,$debugMsg);
		
		
		$salt = $this->PUM_MERCHANT_SALT;		

		If (isset($_POST["additionalCharges"])) {
			   $additionalCharges=$_POST["additionalCharges"];
				$retHashSeq = $additionalCharges.'|'.$salt.'|'.$status.'|||||||||||'.$email.'|'.$firstname.'|'.$productinfo.'|'.$amount.'|'.$txnid.'|'.$key;
				
		}else {	  
			$retHashSeq = $salt.'|'.$status.'|||||||||||'.$email.'|'.$firstname.'|'.$productinfo.'|'.$amount.'|'.$txnid.'|'.$key;
		}
		
		//$hash = hash("sha512", $retHashSeq);
		
		foreach($_POST as $k=>$v)$debugMsg .= ($k). ' = '. $v." \n";
		send_mail(DEVELOPER_EMAIL, SYSTEM_EMAIL_FROM  ,'system' , SYSTEM_DOMAIN_NAME.': Fail Payment Detail',$debugMsg.'---'.$retHashSeq,$debugMsg.'---'.$retHashSeq);	
		
		
		// Get previousoly visited  page url
		$previous_page_url=$this->get_previous_page_url();
		$contactDetail = $this->is_authorized->showBar($this->session->userdata('member_id'));		
		echo $this->load->view('header',array('title'=>'Change Package','previous_page_url'=>$previous_page_url,'contactDetail'=>$contactDetail), true);  
        //if ($hash != $posted_hash) {
		//	echo "Invalid Transaction. Please try again";
		//} else {
			echo "<div style='width:100%; padding:20px 50px;'><h3>Your order status is ". $status .".</h3>
					<h4>Your transaction id for this transaction is ".$txnid.". You may try making the payment by clicking the link below.</h4>
					<a href='".site_url("change_package/")."'>Try again</a></div>";
		//} 
		echo $this->load->view('footer',array(),true);	
	}
	
	function getPromoDetail(){
		$package = $this->input->post('p');
		$months = $this->input->post('pm');
		$promocode = trim($this->input->post('pc'));
		
		
		$packages=$this->UserModel->get_packages_data(array('package_deleted'=>0,'package_status'=>1,'package_id'=>$package ),1);
		
		$rsPcode = $this->db->query("select * from red_promotion_code where `promotion_code`='$promocode' and `payment_months`='$months'");	
		if($rsPcode->num_rows() > 0){
			$discount_percent = $rsPcode->row()->discount_percent;
			$package_amount = $packages[0]['package_price'] ;
			$chargeable_amount = $package_amount  * $months * (1 - ($discount_percent / 100));
			$msg = "<font color='green'>USD $chargeable_amount will be charged for $months month(s) payment.</font>";
		}else{
			$msg = "<font color='red'>Invalid Code</font>";
		}
		$rsPcode->free_result();
		echo $msg;
		exit;
	}
	function getPayableAmount($package, $payment_months, $promocode=''){		
		$packages=$this->UserModel->get_packages_data(array('package_deleted'=>0,'package_status'=>1,'package_id'=>$package ),1);
		$payable_amount = $packages[0]['package_price'] * $payment_months;	
		if($promocode !=''){
			$rsPcode = $this->db->query("select * from red_promotion_code where `promotion_code`='$promocode' and `payment_months`='$payment_months'");	
			if($rsPcode->num_rows() > 0){
				$discount_percent = $rsPcode->row()->discount_percent;
				
				$payable_amount = $payable_amount * (1 - ($discount_percent / 100));
				
			}
			$rsPcode->free_result();
		}
		return $payable_amount;
		exit;
	}
	
	
	
	
	
	
	
	
	function index_cc(){						
        $thisMemberId = $this->session->userdata('member_id'); 
		$arrUserProfile = $this->UserModel->get_user_packages(array('member_id' => $thisMemberId));

		$posted_data = array();
		$posted_data['first_payment'] = $this->payment_model->isFirstPayment($thisMemberId);
        //  If form is submitted
		if($this->input->post('selected_package')!=''){			
			$this->form_validation->set_rules('selected_package', 'Package', 'required');
			$posted_data['payment_gateway'] = 1;// $this->input->post('rdGateway'); // 0 => 'credit_card', 1=>'paypal'
			 
			// If user select free package plan then there will be no validation 
			// for credit card related fields and for billing information			 				
			if ($posted_data['payment_gateway'] == 0) {								
				$this->form_validation->set_rules('cc_number', 'Credit Card Number', 'required');
				$this->form_validation->set_rules('ccexp_month', 'Credit Card Expiry Month', 'required');
				$this->form_validation->set_rules('ccexp_year', 'Credit Card Expiry Year', 'required');
				$this->form_validation->set_rules('cvv', 'Credit Card CVV Number', 'required');
			} 
			$this->form_validation->set_rules('cc_first_name', 'First name', 'required');							
			$this->form_validation->set_rules('cc_last_name', 'Last name', 'required');							
			$this->form_validation->set_rules('cc_address', 'Address', 'required');
			$this->form_validation->set_rules('cc_city', 'City', 'required');
			$this->form_validation->set_rules('cc_state', 'State', 'required');
			$this->form_validation->set_rules('cc_zip', 'zipcode', 'required');
			$this->form_validation->set_rules('cc_country', 'Country', 'required');
			$this->form_validation->set_rules('terms_conditions', 'Terms & Conditions', 'required');				
						
			if($this->form_validation->run()==true){
				$posted_data['member_id'] = $thisMemberId;
				$posted_data['new_package_id'] = $this->input->post('selected_package');
				if ($posted_data['payment_gateway'] == 0) {
					$posted_data['cc_number'] = $this->input->post('cc_number');
					$posted_data['cc_expiry'] = $this->input->post('ccexp_month').$this->input->post('ccexp_year') ;
					$posted_data['cc_cvv'] = $this->input->post('cvv');
				}
				$posted_data['first_name'] = $this->input->post('cc_first_name');
				$posted_data['last_name'] = $this->input->post('cc_last_name');
				$posted_data['address'] = $this->input->post('cc_address');
				$posted_data['city'] = $this->input->post('cc_city');
				$posted_data['state'] = $this->input->post('cc_state');
				$posted_data['zipcode'] = $this->input->post('cc_zip');
				$posted_data['country'] = $this->input->post('cc_country');
				$posted_data['coupon_code'] = ($this->input->post('coupon_code') != '')?$this->input->post('coupon_code') : '';
				
				
				$debugMsg = "\n =======================\n Payment Details: \n";
				foreach($posted_data as $k=>$v)$debugMsg .= strtoupper($k). ' = '. $v." \n";
				
				// send mail with payment details ends 
				send_mail(DEVELOPER_EMAIL, SYSTEM_EMAIL_FROM  ,'system' , SYSTEM_DOMAIN_NAME.': Payment Detail',$debugMsg,$debugMsg);
								
				$arrSelectedPackage		= $this->UserModel->get_packages_data( array('package_id' => $posted_data['new_package_id']) );
				$posted_data['new_package_price']	= $arrSelectedPackage[0]['package_price'];
				
				
				if ($posted_data['payment_gateway'] == 0) { // Payment by credit-card
										
					if($this->payment_by_cc($posted_data)){						
						$this->messages->add('Your Payment is Successful', 'success');					
						redirect('promotions');
						exit;
					}else{
						redirect('change_package');
						exit;
					}					
				} else {
					$this->payment_by_paypal($posted_data);
				}
				
			}
		}
		
		//  Recieve any messages to be shown, when campaign is added or updated
		$messages=$this->messages->get();		
		
		// Fetch packages data from database		
		$packages=$this->UserModel->get_packages_data(array('package_deleted'=>0,'package_status'=>1,'is_special'=>0),16);

		//Retrieve checked package id		
		$current_package_id = $arrUserProfile[0]['package_id'];
		
		$arrUserForPaypal['payment_gateway'] = ($user_packages_array[0]['payment_gateway'] == 1) ? 'paypal' : 'credit_card' ;
		
		
		// Fetch Country name
		$country_info =$this->UserModel->get_country_data();
		// Get previousoly visited  page url
		$previous_page_url=$this->get_previous_page_url();
		$contactDetail = $this->is_authorized->showBar($this->session->userdata('member_id'));		
		$this->load->view('header',array('title'=>'Change Package','previous_page_url'=>$previous_page_url,'contactDetail'=>$contactDetail));
		$this->load->view('user/change_package',array('packages'=>$packages,'user_package'=>$user_packages_array[0],'selected_package'=>$current_package_id,'messages'=>$messages,'posted_data'=>$posted_data, 'arrUserForPaypal'=>$arrUserForPaypal, 'country_info'=>$country_info, 'mode'=>$mode));
		$this->load->view('footer');
	}
	
	
    function changePackage($arrUserPackage = array(),$postedData = array(), $newPlanDetail= array()){		
		// Send Transactional email
		$this->upgraded_package_notification($newPlanDetail['currentPackageMaxContacts'], $newPlanDetail['newPackageMaxContacts'], $arrUserPackage['member_id']);
		// change new package id in database
		$update_array	= array('package_id'=>$postedData['new_package_id'], 'amount'=>$newPlanDetail['newPackageAmount'], 'is_admin'=>0, 'member_payment_declined_count'=>0);
		if($postedData['new_package_id'] > 0)	$update_array['is_payment']	= 1;
		if($postedData['coupon_code'] != '')	$update_array['coupon_code_used']	= $postedData['coupon_code'];
		
		if($newPlanDetail['start_payment_date'] != '')	$update_array['start_payment_date']	= $newPlanDetail['start_payment_date'];
		if($newPlanDetail['next_payment_date'] != '')	$update_array['next_payement_date']	= $newPlanDetail['next_payment_date'];
			
		// update package id in memeber package table
		$this->UserModel->update_member_package($update_array, array('member_id'=>$arrUserPackage['member_id']));
		
		// update campaign_quota for this member
		$upgrade_or_downgrade = ($newPlanDetail['currentPackageAmount'] < $newPlanDetail['newPackageAmount'])?'upgrade': 'downgrade';
		$this->UserModel->updateMemberCampaignQuota($arrUserPackage['member_id'], $upgrade_or_downgrade);
		// Active user account
		$this->UserModel->update_user(array('status'=>'active','login_expiration_notification_date'=>NULL,'cancel_subscription_date'=>NULL),array('member_id'=>$arrUserPackage['member_id']));
		$this->session->set_userdata('member_status','active');
		
		
		$this->messages->add("Your plan is successfully changed to the ".$newPlanDetail['newPackageMaxContacts']." plan", 'success');
		//Move user to Site's paid account subscription list
		$this->paid_member_to_site_account($arrUserPackage['member_id']);
		$user_packages[]	= $postedData['new_package_id'];
		$this->session->set_userdata('user_packages', $user_packages);
		 
		// create activity log	
		$this->Activity_Model->create_activity(array('user_id'=>$arrUserPackage['member_id'],'activity'=>$upgrade_or_downgrade));		
		
		redirect('promotions');
	}
	
		/**
	*	Function payment_by_cc to  do first time payment
	*
	*	@return boolean	return payment is successfull or not
	*/
	function payment_by_cc($postedData){			
		$arrProratedDetail = array();
		
		$user_profile_arr 	= $this->UserModel->get_user_packages(array('member_id'=>$postedData['member_id']));
		$customer_profile_id			= $user_profile_arr[0]['customer_profile_id'];
		$customer_payment_profile_id	= $user_profile_arr[0]['customer_payment_profile_id'];
		// Calculate Proration amount, start-dt, next-pay-dt
		$arrProratedDetail= $this->BillingModel->getProratedAmount($postedData['member_id'],$postedData['new_package_id']);	
			
			 
		if($arrProratedDetail['trial_amount'] > 0){
			// IF any amount is to be charged then check Auth.net customer and payment profile. 
			// IF not created, create it.
			if($customer_profile_id == 0){				
				$customer_profile_id = $this->BillingModel->createCustomerProfileRequest();	// Create Customer_Profile
			}
			
			if($customer_profile_id > 0 and $customer_payment_profile_id == 0){
				$customer_payment_profile_id = $this->BillingModel->createCustomerPaymentProfileRequest($customer_profile_id);
			}
			
			if($customer_profile_id > 0 and $customer_payment_profile_id > 0){
				$user_profile_arr[0]['customer_profile_id'] = $customer_profile_id;
				$user_profile_arr[0]['customer_payment_profile_id'] = $customer_payment_profile_id;
			}else{
				return false;
				exit;
			}
			 
			if($postedData['first_payment']){
				$arrProratedDetail['trial_amount_discounted'] = $this->payment_model->getDiscountedAmountForFirstPayment($arrProratedDetail['trial_amount'],$postedData['coupon_code'] );
			}else{
				$arrProratedDetail['trial_amount_discounted'] = $this->payment_model->getDiscountedAmountForSubsequentPayments($this->session->userdata('member_id'),$arrProratedDetail['trial_amount']);
			}
					
			if($this->BillingModel->createCustomerProfileTransactionRequest($arrProratedDetail['trial_amount_discounted'], $arrProratedDetail['new_package_id'], $user_profile_arr[0], $postedData['first_payment'])){
			
				if($postedData['first_payment']){
										
					$this->UserModel->attachMessage(array('member_id'=>$postedData['member_id'], 'message_id'=>4));		// Attach "Account Approval" message in user dashboard
					// member is updated with pipeline, approval-notes and stop-campaign-notes
					$this->db->query("Update red_members set vmta='mailsvrc.com', stop_campaign_approval=1, campaign_approval_notes='AWAITING ACCOUNT APPROVAL RESPONSE...' where member_id='".$postedData['member_id']."' ");
					// Send user-notice
					$user_data_array=$this->UserModel->get_user_data(array('member_id'=>$postedData['member_id']));
					$mname = $user_data_array[0]['member_username'];	
					$user_name = ($user_data_array[0]['first_name'] != '')? $user_data_array[0]['first_name'] : $mname ;							
					$user_info=array($user_name);
					create_transactional_notification("account_approval", $user_info, $user_data_array[0]['email_address']);
					
					// Admin notification starts					 		
					//$to = $this->confg_arr['admin_notification_email'];		
					$message = "<p>Hello admin,</p><p>Account approval needed for RC Member: $mname [".$postedData['member_id']."]</p><p>Regards,<br />BoldInbox Team</p>";		
					$text_message= "Account approval needed for RC Member: $mname [".$postedData['member_id']."]";
					admin_notification_send_email(DEVELOPER_EMAIL, SYSTEM_EMAIL_FROM,'BoldInbox', "Account approval needed for $mname [".$postedData['member_id']."]",$message,$text_message);
					// Admin notification ends				 				
				} 
				/*
				* IF Auth.net payment is successful
				* Cancel paypal-subscription if any
				**/				
				$this->paypal_subscription_cancel($user_profile_arr[0]['paypal_transaction_id'], 'Cancel');				
			}else{
				if($postedData['first_payment']){
					$this->UserModel->update_member_package(array('customer_profile_id'=>0,'customer_payment_profile_id'=>0) , array('member_id'=>$postedData['member_id']));
				}
				redirect('change_package/index');
			}
		}
		 	
		$this->changePackage($user_profile_arr[0],$postedData, $arrProratedDetail);
		
	}


    /**
     * Function payment_by_paypal to do paypal payment
     * @return boolean return payment is successfull or not 
     * 
     */
    function payment_by_paypal() {
		$thisMid = $this->session->userdata('member_id');
		
		$jsonArray = array('status' => 'error');
		if($this->input->post('selected_package')!=''){			
			$this->form_validation->set_rules('selected_package', 'Package', 'required');
			//$postedData['payment_gateway'] = 1;// $this->input->post('rdGateway'); // 0 => 'credit_card', 1=>'paypal'
			 
		 
			$this->form_validation->set_rules('cc_first_name', 'First name', 'required');							
			$this->form_validation->set_rules('cc_last_name', 'Last name', 'required');							
			$this->form_validation->set_rules('cc_address', 'Address', 'required');
			$this->form_validation->set_rules('cc_city', 'City', 'required');
			$this->form_validation->set_rules('cc_state', 'State', 'required');
			$this->form_validation->set_rules('cc_zip', 'zipcode', 'required');
			$this->form_validation->set_rules('cc_country', 'Country', 'required');
			$this->form_validation->set_rules('terms_conditions', 'Terms & Conditions', 'required');				
						
			if($this->form_validation->run()==true){
				$postedData['member_id'] = $thisMemberId;
				$postedData['new_package_id'] = $this->input->post('selected_package');
				
				$postedData['first_name'] = $this->input->post('cc_first_name');
				$postedData['last_name'] = $this->input->post('cc_last_name');
				$postedData['address'] = $this->input->post('cc_address');
				$postedData['city'] = $this->input->post('cc_city');
				$postedData['state'] = $this->input->post('cc_state');
				$postedData['zipcode'] = $this->input->post('cc_zip');
				$postedData['country'] = $this->input->post('cc_country');
				$postedData['coupon_code'] = ($this->input->post('coupon_code') != '')?$this->input->post('coupon_code') : '';
				
				
				$debugMsg = "\n =======================\n Payment Details: \n";
				foreach($postedData as $k=>$v)$debugMsg .= strtoupper($k). ' = '. $v." \n";
				
				// send mail with payment details ends 
				send_mail(DEVELOPER_EMAIL, SYSTEM_EMAIL_FROM  ,'system' , SYSTEM_DOMAIN_NAME.': Payment Detail',$debugMsg,$debugMsg);
			}else{
				$jsonArray['status'] = 'error';
				$jsonArray['msg'] = validation_errors();
			}
		}	
		$arrMemberPackage = array('payment_type' => 1, 'first_name'=>$postedData['first_name'], 'last_name'=>$postedData['last_name'], 'address'=>$postedData['address'], 'city'=>$postedData['city'], 'state'=>$postedData['state'], 'zip'=>$postedData['zipcode'], 'country'=>$postedData['country'], 'member_id'=>$thisMid, 'is_admin'=>0,'is_payment'=>0, 'payment_paypal_status'=>0);	
        if(trim($postedData['coupon_code']) != ''){
			$arrMemberPackage['coupon_code_used'] = $postedData['coupon_code'];
			$arrMemberPackage['coupon_attached_on'] = date('Y-m-d');			
		}
		// Update Billind-Details in member_package table		
        $this->UserModel->update_member_package($arrMemberPackage, array('member_id'=>$thisMid)); 
		
		
        
		// Calculate Proration amount
		$arrPaymentDetail = $this->BillingModel->getProratedPaymentDetail($postedData['member_id'], $postedData['new_package_id']);
		$trial_period= $arrPaymentDetail['trial_period'];
		$payable_amount= $arrPaymentDetail['trial_amount'];
		$newPackageInterval= ('months' == $arrPaymentDetail['newPackageInterval'])?'M':'Y';
		$next_payment_date =  $arrPaymentDetail['next_payment_date']; 
		$start_payment_date =  $arrPaymentDetail['start_payment_date']; 
        $payable_amount = number_format($payable_amount, 2);

        // regular price for new recurring plan
        $selected_package_array = $this->UserModel->get_packages_data(array('package_id' => $postedData['new_package_id']));
        $selected_package_price = $selected_package_array[0]['package_price'];
		$package_title = $selected_package_array[0]['package_title'];
		// regular price for current recurring plan	
        $user_profile_arr = $this->UserModel->get_user_packages(array('member_id' => $thisMid));
		
        if ($payable_amount > 0) {
            $payable_amount = ($first_payment)? $this->payment_model->getDiscountedAmountForFirstPayment($payable_amount, $postedData['coupon_code']) : $this->payment_model->getDiscountedAmountForSubsequentPaymentsNew($thisMid, $payable_amount, $next_payment_date);                   
            
			$payment_type =($first_payment)? 1:2;      // First payment or subsequent payment       
        
			$thisTransactionId = $this->UserModel->insert_payment_transactions(array ( 'user_id'=>$thisMid, 'package_id'=>$postedData['new_package_id'],'gateway'=>'Paypal','gateway_response' => '', 'amount_paid'=>$payable_amount, 'status'=>'FAILURE', 'payment_type'=>$payment_type, 'is_deleted'=>1));  
			// We will send this custom string to validate authenticity of payment when returned from PayPal
			// member-id, transaction-id, new-package_id, trial_amount, trial_pd, regular_amount, package_interval
			$stringToValidateForPayPal = $thisMid.'|'.$thisTransactionId.'|'.$postedData['new_package_id'].'|'.$payable_amount.'|'.$trial_period.'|'.$selected_package_price.'|'.$newPackageInterval ;
			$enc_custom = $this->is_authorized->encryptor('encrypt',$stringToValidateForPayPal); 
			
			$jsonArray = array('status' => 'success', 'transaction_id'=>$thisTransactionId, 'member_package_id' => $postedData['new_package_id'], 'package_title' => $package_title, 'trial_amount' => sprintf("%01.2f",$payable_amount), 'package_price' => sprintf("%01.2f",$selected_package_price) , 'trial_cycle' => $trial_period, 'payment_year_month' => $newPackageInterval, 'custom'=>$enc_custom);        
			//$jsonArray = array('status' => 'success', 'member_package_id' => $newpackageId, 'package_title' => $package_title, 'package_price' => $payable_amount, 'package_regular_price' => $selected_package_price, 'first_name' => $first_name, 'last_name' => $last_name, 'address1' => $address1, 'city' => $city, 'state' => $state, 'zip' => $zipcode, 'no_of_uses' => $trial_period, 'payment_year_month' => $newPackageInterval); 
        }
		
        
        echo json_encode($jsonArray);
        exit;
        /* End Extra Code */
    }

    function cancelpaypal() {
		redirect('newsletter/campaign');
		exit;
    }

    function notify_paypal_url() {
        $data2 = '==========<><><><><>####<><><><><><><>========================';
        $data2 .= file_get_contents('php://input');
        fwrite(fopen($this->config->item('campaign_files').'paypal', "w"), $data2);
        fclose($myfile);

        $header = '';
        $req = 'cmd=_notify-validate';


        $customArray = explode("|", $_POST['custom']);
        $member_id = $customArray[0];



        foreach ($_POST as $key => $value) $req .= "&$key=".urlencode(stripslashes($value));
        


        //Post info back to paypal for verification
        $header .= "POST /cgi-bin/webscr HTTP/1.0\r\n";
        $header .= "Content-Type: application/x-www-form-urlencoded\r\n";
        $header .= "Content-Length: " . strlen($req) . "\r\n\r\n";
        $fp = fsockopen('www.paypal.com', 80, $errno, $errstr, 30);


        $userPackageArray = $this->UserModel->get_user_packages(array('member_id' => $member_id));


        if (!$fp) {
            //Process HTTP Error
            $message .= "\n HTTP ERROR. \n";
        } else {
            fputs($fp, $header . $req);
            while (!feof($fp)) {
					$res = fgets($fp, 1024);
					send_mail(DEVELOPER_EMAIL, SYSTEM_EMAIL_FROM , 'BoldInbox', "BoldInbox paypal payment notice for $member_id = ".$res, $req);                
            }
            fclose($fp);
        }
    }

	/*
     * Function successpaypal to return from the paypal site	
     * 	
     */
    function successpaypal($tid) {
		if(!(intval($tid) > 0))exit;
		// STARTS
		$separator = '==========<><><><><>####<><><><><><><>========================'."\n\n";
        $data2 = file_get_contents('php://input');    

        foreach ($_POST as $key => $value) $req .= "&$key=".urlencode(stripslashes($value));
		$myfile = fopen($this->config->item('campaign_files').'paypal', "a");
		fwrite($myfile, $separator.$data2.$separator.$req);
        fclose($myfile);
		// ENDS
		
		$thisMid = $this->session->userdata('member_id');		
		$rsTransaction = $this->db->query("select * from red_member_transactions where transaction_id='$tid' and user_id = '$thisMid'");	
		if($rsTransaction->num_rows() > 0 ){			
			$intPackageId = $rsTransaction->row()->package_id;		
		}
		$rsTransaction->free_result();
		
        $paypalPackageArray = $this->UserModel->get_user_packages(array('member_id' => $thisMid));
        $previous_paypal_profileId = $paypalPackageArray[0]['paypal_transaction_id'];
         
        $packagesArray = $this->UserModel->get_packages_data(array('package_id' => $intPackageId));
		$package_recurring_interval = $packagesArray[0]['package_recurring_interval'];		
        $package_amount = $packagesArray[0]['package_price'];
        $quota_multiplier = $packagesArray[0]['quota_multiplier'];
        $package_max_contacts = $packagesArray[0]['package_max_contacts'];
        $max_campaign_quota = $quota_multiplier * $package_max_contacts;


        /* Fetch data from the "red_member_packages" */
       
        $configurationModels = $this->ConfigurationModel->get_site_configuration_data_as_array();
        $to_mails = $configurationModels['admin_notification_email'];
        $from_emails = $configurationModels['admin_email'];
           
        if ($_POST['payment_status'] == 'failed' || $_POST['payment_status'] == 'expired' || $_POST['payment_status'] == 'voided') {// failed            
            $this->db->insert('red_paypal_response', array('member_id' => $thisMid, 'package_id' =>$intPackageId, 'response' => serialize($_POST),  'createddate' => date('Y-m-d H:i:s')));

			//Update transaction with response 
			$this->UserModel->update_payment_transactions(array('gateway_response' => serialize($_POST)),array("transaction_id" => $tid));

            //send mail to admin and user
            $body = 'Hello Admin,<br/><br/>';
			$body .= '<b>UserId:</b> ' . $thisMid. '<br/>';
            $body .= '<b>UserName:</b> ' .$this->session->userdata('member_username') . '<br/>';           
            $body .= '<b>Payment Type:</b> Paypal<br/>';
			$body .= '<b>Paypal Payer Email:</b> '. $_POST['payer_email'].'<br/>';            
            send_mail($to_mails, $from_emails, 'BoldInbox', 'BoldInbox paypal payment Failed', $body);
        }else{
 
            $start_payment_date = date("Y-m-d"); 
			$trial_pd = 1;
			
			if($_POST['period1'] != ''){ 
				$arrPeriod = explode(' ',$_POST['period1']);
				$trial_pd = $arrPeriod[0];
			}	
			 
            $next_payement_timestamp = ($package_recurring_interval == 'months')?strtotime(date("Y-m-d", strtotime($start_payment_date)) . "+".$trial_pd." month"): strtotime(date("Y-m-d", strtotime($start_payment_date)) . "+".$trial_pd." year");            
            $next_payement_date = date('Y-m-d', $next_payement_timestamp);           
            
			$this->UserModel->update_member_package(array('package_id'=>$intPackageId, 'amount'=>$package_amount, 'is_payment'=>1, 'is_admin'=>0, 'payment_type'=>1, 'start_payment_date'=>$start_payment_date, 'next_payement_date'=>$next_payement_date,'member_payment_declined_count'=>0, 'paypal_payer_email' => $_POST['payer_email'],   'payment_paypal_status' => 1, 'paypal_transaction_id' => $_POST['subscr_id']),array('member_id'=>$thisMid));
			
           
            // Cancel any previous paypal-subscription 
            $this->paypal_subscription_cancel($previous_paypal_profileId, 'Cancel');
             
			//Update transaction with response 
			$this->UserModel->update_payment_transactions(array('gateway_response' => serialize($_POST),'status' => 'SUCCESS','is_deleted'=>0 ),array("transaction_id" => $tid));
			$this->db->last_query();
			// Add record in red_paypal_response (useless)
			 $this->db->insert('red_paypal_response', array('member_id' => $thisMid, 'package_id' =>$intPackageId, 'response' => serialize($_POST),  'paypal_profile_id' => $_POST['subscr_id'], 'createddate' => date('Y-m-d H:i:s')));     
            
            //send mail to admin and user
            $body = 'Hello Admin,<br/><br/>';
            $body .= '<b>UserId:</b> ' . $thisMid . '<br/>';
			$body .= '<b>UserName:</b> ' .$this->session->userdata('member_username') . '<br/>';           
			$body .= '<b>Paypal Payer Email:</b> '. $_POST['payer_email'].'<br/>';
            send_mail($to_mails, $from_emails, 'BoldInbox', 'BoldInbox paypal payment Success', $body);
        }
		redirect('newsletter/campaign/index/thanks');
		exit;
    }
 

    /**
     * PayPal Subscription Cancelation
     *
     */
    function paypal_subscription_cancel($profile_id, $apaypal_subscription_cancelction) {

        $api_request = 'USER=' . urlencode( $this->config->item('PAYPAL_USERNAME') )
                . '&PWD=' . urlencode($this->config->item('PAYPAL_PASSWORD'))
                . '&SIGNATURE=' . urlencode($this->config->item('PAYPAL_SIGNATURE'))
                . '&VERSION=76.0'
                . '&METHOD=ManageRecurringPaymentsProfileStatus'
                . '&PROFILEID=' . urlencode($profile_id)
                . '&ACTION=' . urlencode($apaypal_subscription_cancelction)
                . '&NOTE=' . urlencode('Profile cancelled at store');

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->config->item('PAYPAL_URL')); // For live transactions, change to 'https://api-3t.paypal.com/nvp'
        curl_setopt($ch, CURLOPT_VERBOSE, 1);

        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);

        // Set the API parameters for this transaction
        curl_setopt($ch, CURLOPT_POSTFIELDS, $api_request);

        // Request response from PayPal
        $response = curl_exec($ch);

        // If no response was received from PayPal there is no point parsing the response
        if (!$response) {
            //send email to admin.
            //die('Calling PayPal to change_subscription_status failed: ' . curl_error($ch) . '(' . curl_errno($ch) . ')');
        } else {
            
        }
        curl_close($ch);
        // An associative array is more usable than a parameter string
        parse_str($response, $parsed_response);

        return $parsed_response;
    }

    
	
	/**
	*	Function to send  notification email to admin for upgraded package
	*
	*	@param integer $previous_package_max_contacts  previous package maximum contacts
	*	@param integer $current_package_max_contacts  selected package maximum contacts
	*/
	/* function upgraded_package_notification($previous_package_max_contacts=0,$current_package_max_contacts=0){
		# Load the user model which interact with database
		
		# Fetch user data from database
		$user_data_array=$this->UserModel->get_user_data(array('member_id'=>$this->session->userdata('member_id')));

		$user_info=array($user_data_array[0]['member_username'],$previous_package_max_contacts,$current_package_max_contacts);
		$this->load->plugin('notification');
		create_notification("upgraded",$user_info);
	} */
	function upgraded_package_notification($current_package_max_contacts=0,$new_package_max_contacts=0,$member_id=0){		
		$user_data_array	= $this->UserModel->get_user_data(array('member_id'=>$member_id));
		$user_info=array($user_data_array[0]['member_username'], $current_package_max_contacts, $new_package_max_contacts);
		
		create_notification("upgraded",$user_info);
	}
	
	/**
	*	Function paid_member_to_site_account to move user's to BoldInbox paid account subscription list
	*/
	function paid_member_to_site_account($user_id=0){		
		$subscriber_created_by=157;
		
		// Get registered users from database
		$user_count=$this->UserModel->get_user_count(array('is_deleted'=>0,'member_id'=>$user_id));
		$users_array=$this->UserModel->get_user_data(array('is_deleted'=>0,'member_id'=>$user_id),$user_count);
		$signup_data=array();
		foreach($users_array as $user){
			$register_user=false;
			foreach($user as $key=>$value){
				if($key=="email_address"){
					if($value!=''){
						$signup_data['subscriber_email_address']=$value;
						$arrEmailExploded = explode( '@',$signup_data['subscriber_email_address'] );
						$signup_data['subscriber_email_domain'] = $arrEmailExploded[1];
						$register_user=true;
					}
				}
				if($register_user){
					if($key=="first_name") $signup_data['subscriber_first_name']=$value;					
					if($key=="last_name") $signup_data['subscriber_last_name']=$value;
					if($key=="address_line_1") $signup_data['subscriber_address']=$value;
					if($key=="city") $signup_data['subscriber_city']=$value;
					if($key=="state") $signup_data['subscriber_state']=$value;
					if($key=="zipcode") $signup_data['subscriber_zip_code']=$value;
					if($key=="country_name") $signup_data['subscriber_country']=$value;
					if($key=="company") $signup_data['subscriber_company']=$value;
					
					//create subscriber
					$qry = "INSERT INTO red_email_subscribers SET ";
					$flds = '';
					foreach($signup_data as $key=>$val)  $flds .= $key . ' = \'' . mysqli_real_escape_string($this->is_authorized->get_mysqli(),$val) . '\', ';
					$flds .=  'subscriber_created_by = '.$subscriber_created_by ;
					$qry .=  $flds .' ON DUPLICATE KEY UPDATE ' . $flds . ', is_deleted = 0,subscriber_status=1,is_signup=1 , subscriber_id=LAST_INSERT_ID(subscriber_id)';
					$this->db->query($qry);
					$last_inserted_id = $this->db->insert_id();
					if($member_id==157){
						$del_sublistid=122;
						$sublistid=245;
					}else{
						$del_sublistid=78;
						$sublistid=79;
					}
					if ($last_inserted_id > 0 and $sublistid > 0){
						$this->Subscriber_Model->delete_subscription_subscriber(array('subscriber_id'=>$last_inserted_id,'subscription_id'=>$del_sublistid));
						$input_array=array('subscriber_id'=>$last_inserted_id,'subscription_id'=>$sublistid);
						$this->Subscriber_Model->replace_subscription_subscriber($input_array);
					}else{
						$qry="SELECT subscriber_id FROM red_email_subscribers WHERE subscriber_email_address='$value' AND is_deleted = 0 AND subscriber_status=1 AND is_signup=1";
						$subscriber_qry=$this->db->query($qry);
						$subscriber_data_array=$subscriber_qry->result_array();	// Fetch resut
						if($subscriber_data_array[0]['subscriber_id']>0){
							$this->Subscriber_Model->delete_subscription_subscriber(array('subscriber_id'=>$subscriber_data_array[0]['subscriber_id'],'subscription_id'=>$del_sublistid));
							$input_array=array('subscriber_id'=>$subscriber_data_array[0]['subscriber_id'],'subscription_id'=>$sublistid);
							$this->Subscriber_Model->replace_subscription_subscriber($input_array);
						}
					}
				}
			}
		}
	}

	
	/**
	*	Function get_previous_page_url to get previously visited page url
	*
	*	@return string return previously visited page url
	*/
	function get_previous_page_url(){
		if($_SERVER['HTTP_REFERER']!=base_url()."change_package/index"){
			$this->session->set_userdata('HTTP_REFERER', $_SERVER['HTTP_REFERER']);
			$previous_page_url=$_SERVER['HTTP_REFERER'];
		}else{
			$previous_page_url=$this->session->userdata('HTTP_REFERER');
		}
		return $previous_page_url;
	}
	
}
?>