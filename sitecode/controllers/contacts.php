<?php
/**
  *	Controller class for subscriptions
  *	It have controller functions for subscription management.
 */
class Contacts extends CI_Controller
{
	/**
	  *	Contructor for controller.
	  *	It checks user session and redirects user if not logged in
	 */
	private $confg_arr = array(); 
	function __construct() {
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

		$this->load->model('userboard/subscription_Model');
		$this->load->model('userboard/Subscriber_Model');
		$this->load->model('userboard/contact_model');
		$this->load->model('userboard/Signup_Model');
		$this->load->model('UserModel');
		$this->load->model('userboard/Emailreport_Model');

		$this->output->enable_profiler(false);
		// Force SSL
		force_ssl();
		
    }


	/**
	 * Function index
	 *
	 * 'Index' controller function for listing of subscriptions.
	 *
	 * @param (int) (subscription_id)  for displaying subscription selectable(blue color)  in view of subscription list
	 */

	function index($subscription_id=0,$scroll=0,$action=""){		
		
		$subscription_id = (is_numeric($subscription_id))?$subscription_id:0;
		
		// Recieve any messages to be shown, when subscription is added or updated
		$messages=$this->messages->get();
		if($this->session->userdata('member_id') == 197){		
			$this->populateRCMembers();
		}

		// Get Maximum Contacts according to session package id		
		$user_packages_array=$this->UserModel->get_user_packages(array('member_id'=>$this->session->userdata('member_id'),'is_deleted'=>0));
		$package_array=$this->UserModel->get_packages_data(array('package_id'=>$user_packages_array[0]['package_id']));				 
		$package_price=$package_array[0]['package_price'];
		$subscription_data['package_max_contacts']=$package_array[0]['package_max_contacts'];
		

		// Get Total Subscribers created by login user
		$fetch_condiotions_array=array('subscriber_created_by'=>$this->session->userdata('member_id'),'subscriber_status'=>1,'is_deleted'=>0);
		$subscription_data['subscriber_count']=$this->contact_model->getContactsCount($fetch_condiotions_array);

		$fetch_condiotions_array=array('subscription_created_by'=>$this->session->userdata('member_id'),'is_deleted'=>0	);

		// Fetches subscription data from database
		//$subscription_data['subscriptions']=$this->subscription_Model->get_subscription_data($fetch_condiotions_array);
	 
	 	// Updated on 26 june 2018
	 	$arr1=$this->subscription_Model->get_subscription_data(array('subscription_created_by'=>$this->session->userdata('member_id'),'is_deleted'=>0, 'subscription_id < 0'=>NULL));
		$arr2=$this->subscription_Model->get_subscription_data(array('subscription_created_by'=>$this->session->userdata('member_id'),'is_deleted'=>0, 'subscription_id > 0'=>NULL));
		$subscription_data['subscriptions']=array_merge($arr1,$arr2);
	 
	 
	 
	 
		if(count($subscription_data['subscriptions']) == 0){
			$input_array=array('subscription_title'=>'All My Contacts','subscription_id'=>'-'.$this->session->userdata('member_id'),'subscription_is_name'=>'1','subscription_created_by'=>$this->session->userdata('member_id'));
			$subscription_id=$this->subscription_Model->create_subscription($input_array);	
			$subscription_data['subscriptions']=$this->subscription_Model->get_subscription_data($fetch_condiotions_array);				
		}
		$all_my_contacts=$subscription_data['subscriptions'][0];
		$subscription_order_by_name=$subscription_data['subscriptions'];
		unset($subscription_order_by_name[0]);
		if(count($subscription_order_by_name)>0){
			$subscription_order_by_name=$this->subval_sort($subscription_order_by_name,'subscription_title');
		}
		$subscription_order_by_name[0]=$all_my_contacts;
		// Assign all the subscription for displaying in selectbox list
		$subscription_data['select_subscriptions']=$subscription_order_by_name;

		// Collect subscription id for displaying subscription selectable(blue color) in view of subscription list

		$subscription_id_array=array();
		$autoresponder_subscriptions_array=array();
		foreach($subscription_data['subscriptions'] as $subscription){
			$subscription_id_array[]=$subscription['subscription_id'];
		}
		if($subscription_id){
			$subscription_data['subscription_first_id']=$subscription_id;
		}else{
			$subscription_data['subscription_first_id']=$subscription_id_array[0];
		}
		// Collect Total number of subscriber(contacts) for each subscription

		foreach($subscription_data['subscriptions'] AS $subscription){			
			if($subscription['subscription_id'] > 0){
			$fetch_condiotions_array=array('res.subscriber_created_by'=>$this->session->userdata('member_id'),'res.subscriber_status'=>1,'res.is_deleted'=>0);			
			$subscribers=$this->contact_model->get_contacts_count_in_list($fetch_condiotions_array,$subscription['subscription_id']);
			$subscription_data['total']["'".$subscription['subscription_id']."'"]= $subscribers;
			}else{
			$subscription_data['total']["'".$subscription['subscription_id']."'"]= $subscription_data['subscriber_count'];			
			}
		}

		/**
		 * Fetch user data
		 */
		$user_data_array=$this->UserModel->get_user_data(array('member_id'=>$this->session->userdata('member_id')));
		$subscription_data['extra']=$user_data_array[0];
		$contactDetail = $this->is_authorized->showBar($this->session->userdata('member_id'));
		//Loads header, subscription and footer view.
		$this->load->view('header',array('title'=>'List subscriptions','contactDetail'=>$contactDetail));
		$this->load->view('contacts/contacts',$subscription_data);
		$this->load->view('footer');
	}
	// function to populate MoveTo dpd
	function showMoveToCopyToLists($list=0){
		$thisMid = $this->session->userdata('member_id');
		$str = '';
		if($list == 0)$list = 0 - $thisMid;
		$arrSubscription = $this->subscription_Model->get_subscription_data(array('subscription_created_by'=>$thisMid, 'is_deleted'=>0, 'subscription_id !=' => $list, 'subscription_id >' => 0));
		foreach($arrSubscription as $arrList){
		$str .= "<li  onclick='submit_frm(".$arrList['subscription_id'].",\"move\")' name='".$arrList['subscription_id']."' class='move_".$arrList['subscription_id']." list' >
							  <a href='javascript:void(0);' style = 'font-size:15px;font-weight:700;background:none;box-shadow:none;'>".ucfirst(substr($arrList['subscription_title'],0,25))."</a>
							  </li>";
		}
		//$str .="<li onclick='unsubscribe_list(".(0 - $thisMid).",\"unsubscribe\")' name='".(0 - $thisMid)."' class='do-not-mail-option' ><a href='javascript:void(0);' style = 'font-size:15px;font-weight:700;background:none;box-shadow:none;color:#CC0000;'>Do Not Mail List</a></li>";	
		echo $str;exit;					
	}
	// function to show lists and its contacts count in dpd on contacts page.
	function showLists(){
		$thisMid = $this->session->userdata('member_id');	 
		$complaint_count = $this->db->query("select count(subscriber_id) c from red_email_subscribers where subscriber_created_by='$thisMid' and is_deleted=0 and subscriber_status=2")->row()->c;
		$removed_count = $this->db->query("select count(subscriber_id) c from red_email_subscribers where subscriber_created_by='$thisMid' and is_deleted=0 and subscriber_status=5")->row()->c;
		$unsubscriber_count = $this->db->query("select count(subscriber_id) c from red_email_subscribers where subscriber_created_by='$thisMid' and is_deleted=0 and subscriber_status=0")->row()->c;
		$bounce_count = $this->db->query("select count(subscriber_id) c from red_email_subscribers where subscriber_created_by='$thisMid' and is_deleted=0 and subscriber_status=3")->row()->c;
		//$subscription_data['subscriptions']=$this->subscription_Model->get_subscription_data(array('subscription_created_by'=>$thisMid, 'is_deleted'=>0));
		
		$arr1=$this->subscription_Model->get_subscription_data(array('subscription_created_by'=>$thisMid,'is_deleted'=>0, 'subscription_id < 0'=>NULL));
		$arr2=$this->subscription_Model->get_subscription_data(array('subscription_created_by'=>$thisMid,'is_deleted'=>0, 'subscription_id > 0'=>NULL));
		$subscription_data['subscriptions']=array_merge($arr1,$arr2);
		
		
		
		
		
		
		$all_my_contacts=$subscription_data['subscriptions'][0];
		$subscription_order_by_name=$subscription_data['subscriptions'];
		//echo"<pre>";print_r($subscription_order_by_name);echo"</pre>";
		unset($subscription_order_by_name[0]);
		//echo"<pre>";print_r($subscription_order_by_name);echo"</pre>";
		if(count($subscription_order_by_name)>0){
			$subscription_order_by_name=$this->subval_sort($subscription_order_by_name,'subscription_title');
		}
		$subscription_order_by_name[0]=$all_my_contacts;
		//echo"<pre>";print_r($subscription_order_by_name);echo"</pre>";
		// Assign all the subscription for displaying in selectbox list
		$subscription_data['select_subscriptions']=$subscription_order_by_name;
		
		/*
			Collect subscription id for displaying subscription selectable(blue color) in view of subscription list
		*/
		$subscription_id_array=array();
		$select_subscriptions="";	//Collect subscription title for displaying in select box
		foreach($subscription_data['subscriptions'] as $subscription){
			$subscription_id_array[]=$subscription['subscription_id'];
			$select_subscriptions.=$subscription['subscription_id']."=".ucfirst(substr($subscription['subscription_title'],0,25))."|";
		}
		//remove last character '|' from select_subscriptions string
		$select_subscriptions=substr_replace($select_subscriptions,"",-1);
		if($subscription_id){
			$subscription_data['subscription_first_id']=$subscription_id;
		}else{
			$subscription_data['subscription_first_id']=$subscription_id_array[0];
		}
		foreach($subscription_data['subscriptions'] AS $subscription){			
			if($subscription['subscription_id'] > 0){			
			$subscription_data['total']["'".$subscription['subscription_id']."'"]= $this->contact_model->get_contacts_count_in_list(array('res.subscriber_created_by'=>$thisMid,'res.subscriber_status'=>1,'res.is_deleted'=>0), $subscription['subscription_id']);
			}else{			
			$subscription_data['total']["'".$subscription['subscription_id']."'"]= $this->contact_model->getContactsCount(array('subscriber_created_by'=>$thisMid, 'subscriber_status'=>1,'is_deleted'=>0));
			}
		}
		 
		/*
			Collect subscription list for displaying in subscription view using ajax
		*/
		if(count($subscription_data['subscriptions'])) {
			$i=1;	// variable i is used for change the class of tr altrnatively

			foreach($subscription_data['subscriptions'] as $subscription){				
				if($subscription['subscription_id'] < 0){
				//$subscription_list.= '<div id = "contacts_selected" class = "contacts_selected_c">All My Contacts ('. $subscription_data['total']["'".$subscription['subscription_id']."'"].')</div>';	
				$subscription_list.= '<div class = "contacts_active" style = "overflow:auto;overflow-x:hidden;height:198px;">						
							<div class = "contacts_select_show_head">&raquo; Active Subscribers</div>
							<ul>';
				$subscription_list.= '<li><div class = "contacts_select_show_list_name"><a href = "javascript:void(0);" id = "'.$subscription['subscription_id'].'" class = "contacts_select_show_list_name_a">'.ucfirst(substr ($subscription['subscription_title'],0,35)).' ('.$subscription_data['total']["'".$subscription['subscription_id']."'"].')</a></div></li>';
				}else{				
				$subscription_list.= '<li><div class = "contacts_select_show_list_name"><a href = "javascript:void(0);" id = "'.$subscription['subscription_id'].'" class = "contacts_select_show_list_name_a"><span>'.ucfirst(substr ($subscription['subscription_title'],0,35)).'</span> ('.$subscription_data['total']["'".$subscription['subscription_id']."'"].')</a></div><div class = "contacts_select_show_list_action"><a href = "#" class = "list_edit">Edit</a> | <a href = "javascript:void(0);" id="list_'.$subscription['subscription_id'].'" class="listdelete">Delete</a></div></li>';									
				}												
			}
			$subscription_list.='				
							</ul>
						</div>
						<div class = "contacts_dnm">
							<div class = "contacts_select_show_head">&raquo; Do Not Mail List</div>
							<ul>
								<li><div class = "contacts_select_show_list_name"><a href = "javascript:void(0);" id="bounce_count" class="contacts_select_show_list_name_a">Bounces ('. $bounce_count.')</a></div></li>
								<li><div class = "contacts_select_show_list_name"><a href = "javascript:void(0);" id="unsubscribe_count" class="contacts_select_show_list_name_a">Unsubscribes ('. $unsubscriber_count.')</a></div></li>
								<li><div class = "contacts_select_show_list_name"><a href = "javascript:void(0);" id="complaint_count" class="contacts_select_show_list_name_a">Complaints ('. $complaint_count.')</a></div></li>
								<li><div class = "contacts_select_show_list_name"><a href = "javascript:void(0);" id="removed_count" class="contacts_select_show_list_name_a">Removed ('. $removed_count.')</a></div></li>
							</ul>
					</div>';
			echo $subscription_list;	//print subscription list
		} else {
			// if subscription list is empty
			echo "No record found";
		}	
	}
	// To show lists dropdown on Add-Contacts page
	function showListsDpd(){	
	$select_subscriptions = $this->subscription_Model->get_subscription_data(array('subscription_created_by'=>$this->session->userdata('member_id'),'is_deleted'=>0	));		 
	foreach($select_subscriptions as $subscription){
	  if($subscription_first_id == $subscription['subscription_id'])
		echo "<option value='".$subscription['subscription_id']."' selected>".ucfirst($subscription['subscription_title'])."</option>";
	  else
		echo "<option value='".$subscription['subscription_id']."'>".ucfirst($subscription['subscription_title'])."</option>";
	}
	}
	/**
	 * Function Dislay_ajax
	 *
	 * 'Dislay_ajax' controller function for listing of subscriptions using ajax.
	 *
	 * @param (int) (subscription_id)  for displaying subscription selectable(blue color)  in view of subscription list
	 */

	function display_ajax($subscription_id=0){
		$subscription_id = (is_numeric($subscription_id))?$subscription_id:0;
		
		// Recieve any messages to be shown, when subscription is added or updated
		$messages=$this->messages->get();

		// Creating array of conditions to checked with database with conditions.

		$fetch_condiotions_array=array('subscription_created_by'=>$this->session->userdata('member_id'), 'is_deleted'=>0);
		$subscription_data['subscriptions']=$this->subscription_Model->get_subscription_data($fetch_condiotions_array);
		
		$all_my_contacts=$subscription_data['subscriptions'][0];
		$subscription_order_by_name=$subscription_data['subscriptions'];
		unset($subscription_order_by_name[0]);
		if(count($subscription_order_by_name)>0){
			$subscription_order_by_name=$this->subval_sort($subscription_order_by_name,'subscription_title');
		}
		$subscription_order_by_name[0]=$all_my_contacts;
		// Assign all the subscription for displaying in selectbox list
		$subscription_data['select_subscriptions']=$subscription_order_by_name;

		/*
			Collect subscription id for displaying subscription selectable(blue color) in view of subscription list
		*/
		$subscription_id_array=array();
		$select_subscriptions="";	//Collect subscription title for displaying in select box
		foreach($subscription_data['subscriptions'] as $subscription){
			$subscription_id_array[]=$subscription['subscription_id'];
			$select_subscriptions.=$subscription['subscription_id']."=".ucfirst(substr($subscription['subscription_title'],0,25))."|";
		}
		//remove last character '|' from select_subscriptions string
		$select_subscriptions=substr_replace($select_subscriptions,"",-1);
		if($subscription_id){
			$subscription_data['subscription_first_id']=$subscription_id;
		}else{
			$subscription_data['subscription_first_id']=$subscription_id_array[0];
		}
		foreach($subscription_data['subscriptions'] AS $subscription){			
			if($subscription['subscription_id'] > 0){
			$fetch_condiotions_array=array('res.subscriber_created_by'=>$this->session->userdata('member_id'),'res.subscriber_status'=>1,'res.is_deleted'=>0);			
			$subscribers=$this->contact_model->get_contacts_count_in_list($fetch_condiotions_array,$subscription['subscription_id']);
			$subscription_data['total']["'".$subscription['subscription_id']."'"]= $subscribers;
			}else{
			$fetch_condiotions_array=array('subscriber_created_by'=>$this->session->userdata('member_id'),'subscriber_status'=>1,'is_deleted'=>0);
			$subscription_data['total']["'".$subscription['subscription_id']."'"]= $this->contact_model->getContactsCount($fetch_condiotions_array);
			}
		}
		 
		/*
			Collect subscription list for displaying in subscription view using ajax
		*/
		if(count($subscription_data['subscriptions'])) {
			$i=1;	// variable i is used for change the class of tr altrnatively

			foreach($subscription_data['subscriptions'] as $subscription){
				// if variable i is odd then class will be "nomargin-right" else empty
				if($subscription['subscription_id']=='-'.$this->session->userdata('member_id')){
					if($subscription['subscription_id']>0){
						$delete_link='<a  href="javascript:void(0);" ><i class="icon-trash"></i></a>';
						$delete_link='<li>'.$delete_link.'</li>';
					}
				}else{
					if($subscription['subscription_id']>0){
						$delete_link='<li><a class="delete-list fancybox btn cancel" href="'.base_url().'contacts/delete/'.$subscription['subscription_id'].'" name="'.$subscription['subscription_id'].'"><i class="icon-trash"></i></a></li>';
					}
				}
				if($subscription['subscription_id']>0){
					$edit_link='<li><a id="subscriber_edit" name="'.$subscription['subscription_id'].'"  class="subscriber_edit btn cancel" href="javascript:void(0);"><i class="icon-pencil"></i></a></li>';
				}
				$subscription_list.= '<div class="editing-theme-box '. $class.'" id="'.$subscription['subscription_id'].'">
                    <div class="listname-no" onclick="display_contacts('.$subscription['subscription_id'].')" style="cursor:pointer;"><span class="right-no">'.$subscription_data['total']["'".$subscription['subscription_id']."'"].'</span> <strong class="subscription_strong" name="'.$subscription['subscription_id'].'" id="subscription_id_'.$subscription['subscription_id'].'">'.ucfirst(substr ($subscription['subscription_title'],0,15)).'<input type="hidden" name="subscription_title_'. $subscription['subscription_id'].'" id="subscription_title_'. $subscription['subscription_id'].'" value="'.$subscription['subscription_title'].'" /></strong><input type="text" name="subscription_text_'.$subscription['subscription_id'].'" id="subscription_text_'.$subscription['subscription_id'].'"  class="subscription_text" value="'.$subscription['subscription_title'].'" style="display:none;padding:0px; margin:0px;border:none;"  maxlength="25"/></div>
                    <div class="icon-listing">
                      <ul class="list-icons contacts">
                        '.$edit_link.'
                        '.$delete_link.'
                      </ul>
					   <ul class="list-icons edit_subscription" style="display:none;">
                        <li><a class="btn confirm" onclick="saveSubscriptionTitle(\''.$subscription['subscription_id'].'\');" href="javascript:void(0);">Save</a></li>
                        <li><a class="btn cancel" onclick="javascript:jQuery(this).parents(\'.editing-theme-box\').find(\'.list-icons\').show();jQuery(this).parent().parent().hide();jQuery(\'#subscription_text_'.$subscription['subscription_id'].'\').hide();jQuery(\'#subscription_id_'.$subscription['subscription_id'].'\').show();jQuery(\'#subscription_text_'.$subscription['subscription_id'].'\');$(\'.right-no\').show();" href="javascript:void(0);">Cancel</a></li>
                      </ul>
                    </div>
                  </div>';
					$i++;	//increment i
			}
			echo $select_subscriptions.'/\\'.$subscription_list.'<div class="backdrop"></div>';	//print subscription list
		} else {
			// if subscription list is empty
			echo "No record found";
		}
	}


	/**
	 * Function Create
	 *
	 * 'Create' controller function to create new subscription list.
	 */
	function create(){		
		if($this->input->post('action')=='submit'){
			
			$this->form_validation->set_rules('subscription_title', 'List Name', 'required|min_length[2]|max_length[45]|callback_title_check|trim');

			
			if($this->form_validation->run()==true){
				// Retrieve data posted in form posted by user using input class
				$input_array=array('subscription_title'=>$this->input->get_post('subscription_title', TRUE),
				'subscription_is_name'=>'1',
				'subscription_created_by'=>$this->session->userdata('member_id')
				);


				// Sends form input data to database via model object
				$subscription_id=$this->subscription_Model->create_subscription($input_array);

				//Success message
				echo 'success:'.$subscription_id;
			}else if(validation_errors()){	//To display form  validation error
				if($this->input->get_post('subscription_title', TRUE)){
					//echo 'error:'.validation_errors();
					echo 'error: The list already exists';
				}else{
					echo 'error:List Name is required';
				}
			}
		}
	}

	/**
	 * Function title_check
	 *
	 * Function to check if title already exists in database before updating database by input from user.
	 *
	 * @return (bool)	if title already exists in database then return false else return true
	 */
	function title_check(){
		$conditions_array['subscription_title']=$this->input->get_post('subscription_title', TRUE);	//Subscription title
		$conditions_array['subscription_created_by']=$this->session->userdata('member_id');	//member id
		$conditions_array['is_deleted !=']=1;	//member id

		//check subscription id exist or not
		if($this->input->get_post('subscription_id', TRUE)!='')
			$conditions_array['subscription_id !=']=$this->input->get_post('subscription_id', TRUE);
		//Get subscription data from database using subscription_Model
		$subscription_array=$this->subscription_Model->get_subscription_data($conditions_array);

		// returns true if title exits and false if not exits.
		if(count($subscription_array))
		{
			$this->form_validation->set_message('title_check', 'The %s already exists');
			return FALSE;
		}
		else
			return true;
	}

	/**
	 * Function Edit
	 *
	 * 'Edit' controller function to edit existing subscription.
	 *
	 * @param (int) (subscription_id)  contains subscription_id which is used for edit the subscription data
	 */

	function edit($subscription_id=0){
		$id = (is_numeric($subscription_id)) ? 	$subscription_id : 0;		
		$subscription_data=array();
		
		if($this->input->post('action')=='submit'){		
			$this->form_validation->set_rules('subscription_title', 'Subscription Title', 'required|min_length[2]|max_length[100]|callback_title_check|trim');		
			$input_array=array('subscription_title'=>$this->input->get_post('subscription_title', TRUE),'subscription_is_Email'=>'1','subscription_is_name'=>'1');
			
			if($this->form_validation->run()==true){				
				$this->subscription_Model->update_subscription($input_array,array('subscription_id'=>$id));				
				echo "success:subscription updated successfully";
				exit;
			}else{
				$conditions_array['subscription_id']=$this->input->get_post('subscription_id', TRUE);
				$subscription_array=$this->subscription_Model->get_subscription_data($conditions_array);
				echo "error:".strip_tags(validation_errors()).":".$subscription_array[0]['subscription_title'];
				exit;
			}
			$subscription_data=$input_array;
		}

		/* subscriptions will have count as zero or null when form is not posted.
		   In this case, retreive subscription data from database according to subscription ID.
		*/
		if(!count($subscription_data)){			
			$subscription_array=$this->subscription_Model->get_subscription_data(array('subscription_id'=>$id,'subscription_created_by'=>$this->session->userdata('member_id')));	
			if(!count($subscription_array))	{				
				$this->messages->add('Subscription does not exists or you have not created this Subscription', 'error');
			}
			$subscription_data=array('subscription_title'=>$subscription_array[0]['subscription_title']);
		}

		// Recieve any messages to be shown, when subscription is added or updated
		$messages=$this->messages->get();
		// Add subscription ID to subscription array
		$subscription_data['subscription_id']=$id;

		//Loads  subscription view.
		$this->load->view('contacts/subscription_edit',array('subscription_data'=>$subscription_data,'messages'=>$messages));
	}


	/**
	 * Function Delete
	 *
	 * 'Delete' controller function to Delete existing subscription.
	 *
	 * @param (int) (subscription_id)  contains subscription_id which is used for delete the subscription from database
	 */

	function delete($subscription_id=0)	{
		$id = (is_numeric($subscription_id))? $subscription_id : 0;
		$thisMid = $this->session->userdata('member_id');
		$this->load->model('userboard/Autoresponder_Model');
		$autoresponder_subscription=0;		
		$autoresponder_group=$this->Autoresponder_Model->get_autoresponder_group(array('is_deleted'=>0,'autoresponder_created_by'=>$thisMid,'autoresponder_subscription_id'=>$subscription_id));
		if(count($autoresponder_group)>0){
			$autoresponder_subscription=1;
		}elseif($this->input->post('submit_action')=='submit'){			
			$subscription_cnt=$this->subscription_Model->get_subscription_count(array('subscription_id'=>$id,'subscription_created_by'=>$thisMid));
			
			if($subscription_cnt>0){			
				$this->subscription_Model->delete_subscription(array('subscription_id'=>$id));

				// Deletes subscriber from "subscription List"
				// Deletion of contacts for list in table "subscription_subscriber" is stopped on 22 Feb,2019
				//$this->Subscriber_Model->delete_subscriber_from_list(array('subscription_id'=>$id));
			}else{
				echo "List-id does not exist";
			}
		}
		
		$rsList = $this->db->query("select  subscription_title from red_email_subscriptions where subscription_id='$id' and subscription_created_by='$thisMid'  ");
		$lname = $rsList->row()->subscription_title	;
		$rsList->free_result();
		$this->load->view('contacts/subscription_delete',array('subscription_id'=>$subscription_id,'lname'=>$lname, 'autoresponder_subscription'=>$autoresponder_subscription));
	}
	
	/**
		Function subval_sort for sorting multi dimensional array
	*/
	function subval_sort($a,$subkey) {
		$c[0]="";
		foreach($a as $k=>$v) {
			$b[$k] = strtolower($v[$subkey]);
		}
		@asort($b);
		foreach($b as $key=>$val){
			$c[] = $a[$key];
		}
		return $c;
	}
	/**
	*	Function add_emailreport_to_contact_list to add subscribers  in Contact list
	*/
	function add_emailreport_to_contact_list($action="",$campaign_id=0, $tinyUrl = ''){	
		if($this->input->post('action')=='submit'){
			$campaign_id = $this->is_authorized->encryptor('decrypt',$campaign_id);		
			if($this->input->post('subscription_title')!=''){
				$this->form_validation->set_rules('subscription_title', 'List Name', 'required|min_length[5]|max_length[100]|callback_title_check|trim');
				if($this->form_validation->run()==true){
					$input_array=array('subscription_title'=>$this->input->get_post('subscription_title', TRUE), 'subscription_is_name'=>'1', 'subscription_created_by'=>$this->session->userdata('member_id') );
					$subscription_id=$this->subscription_Model->create_subscription($input_array);
				}	
			}else{
				$this->form_validation->set_rules('subscriptions', ' Contact List', 'required');
				if($this->form_validation->run()==true){
					$subscription_id=$this->input->post('subscriptions');
				}
			}
			if(intval($subscription_id) <= 0){
				echo 'error: '.validation_errors();
				exit;
			}
			if($action=="clickurl"){					
				$fetch_condiotions_array=array('ret.campaign_id'=>$campaign_id, 'counter >'=>0, 'tiny_url'=>$tinyUrl, 'is_autoresponder'=>0);	
				$emailreport_data=$this->Emailreport_Model->get_emailreport_subscriber_click($fetch_condiotions_array);
			}else{
				if($action=="sent"){
					$fetch_condiotions_array=array('campaign_id'=>$campaign_id, 'user_id'=>$this->session->userdata('member_id'));
				}elseif($action=="read"){
					$fetch_condiotions_array=array('campaign_id'=>$campaign_id,'user_id'=>$this->session->userdata('member_id'), 'email_track_read'=>1);
				}elseif($action=="unread"){
					$fetch_condiotions_array=array('campaign_id'=>$campaign_id,'user_id'=>$this->session->userdata('member_id'), 'email_track_read'=>0);
				}elseif($action=="forwardemail"){
					$fetch_condiotions_array=array('campaign_id'=>$campaign_id,'user_id'=>$this->session->userdata('member_id'), 'email_track_forward >'=>0);				
				}elseif($action=="click"){
					$fetch_condiotions_array=array('campaign_id'=>$campaign_id,'user_id'=>$this->session->userdata('member_id'), 'email_track_click >'=>0);
				}elseif($action=="unsubscribes"){
					$fetch_condiotions_array=array('campaign_id'=>$campaign_id,'user_id'=>$this->session->userdata('member_id'), 'email_track_unsubscribes >'=>0);
				}elseif($action=="complaints"){
					$fetch_condiotions_array=array('campaign_id'=>$campaign_id,'user_id'=>$this->session->userdata('member_id'), 'email_track_complaint'=>1);
				}elseif($action=="bounced"){
					$fetch_condiotions_array=array('campaign_id'=>$campaign_id, 'user_id'=>$this->session->userdata('member_id'),'email_track_bounce >'=>0);
				} 
				$emailreport_data=$this->Emailreport_Model->get_emailreport_subscriber($fetch_condiotions_array);
			}
			if(count($emailreport_data)>0){
				foreach ($emailreport_data as $emailreport){
					$arrEmailExploded = explode( '@',$emailreport['subscriber_email_address'] );
					$emailreport['subscriber_email_domain'] = $arrEmailExploded[1];
				
				
					$qry = "INSERT INTO red_email_subscribers SET ";
					$flds = '';
					$flds .=  'subscriber_email_address = \'' . mysqli_real_escape_string($this->is_authorized->get_mysqli(), $emailreport['subscriber_email_address']) . '\', ';
					$flds .=  'subscriber_email_domain = \'' . mysqli_real_escape_string($this->is_authorized->get_mysqli(), $emailreport['subscriber_email_domain']) . '\', ';

					$flds .=  'subscriber_created_by = ' . $this->session->userdata('member_id') ;
					$qry .=  $flds .' ON DUPLICATE KEY UPDATE ' . $flds . ', is_deleted = 0 , subscriber_id=LAST_INSERT_ID(subscriber_id)';
					$this->db->query($qry);
					$last_inserted_id = $this->db->insert_id();
					if (($last_inserted_id > 0) &&($subscription_id>0)){
						$input_array=array('subscriber_id'=>$last_inserted_id,'subscription_id'=>$subscription_id);
						$this->Subscriber_Model->replace_subscription_subscriber($input_array);
					}
				}
			}
			echo "success:Contacts added to the existing/new list successfully!";
			//echo "success:".$this->db->last_query();
			exit;
		}
		
		

		$fetch_condiotions_array=array('subscription_created_by'=>$this->session->userdata('member_id'), 'is_deleted'=>0, 'subscription_id >'=>0);		
		$subscription_list=$this->subscription_Model->get_subscription_data($fetch_condiotions_array);
		
		// Recieve any messages to be shown, when category is added or updated
		$messages=$this->messages->get();
		
		$this->load->view('stats/add_emailreport_view',array('action'=>$action,'campaign_id'=>$campaign_id,'tinyUrl'=>$tinyUrl,'messages' =>$messages,'subscription_list'=>$subscription_list));
		
	}
	/**
	*	Function add_signupform_contact_to_list to add subscribers  in Contact list
	*/
	function add_signupform_contact_to_list($action="",$fid=0){	
		
		if($this->input->post('action')=='submit'){
			if($this->input->post('subscription_title')!=''){
				$this->form_validation->set_rules('subscription_title', 'List Name', 'required|min_length[5]|max_length[100]|callback_title_check|trim');
				if($this->form_validation->run()==true){
					$input_array=array('subscription_title'=>$this->input->get_post('subscription_title', TRUE), 'subscription_is_name'=>'1', 'subscription_created_by'=>$this->session->userdata('member_id') );
					$subscription_id=$this->subscription_Model->create_subscription($input_array);
				}	
			}else{
				$this->form_validation->set_rules('subscriptions', ' Contact List', 'required');
				if($this->form_validation->run()==true){
					$subscription_id=$this->input->post('subscriptions');
				}
			}
			if(intval($subscription_id) <= 0){
				echo 'error: '.validation_errors();
				exit;
			}
			if($action=="view"){				
				$signupform_data=$this->Signup_Model->get_signupform_stats(array('form_id'=>$fid,'activity'=>'1'),$config['per_page'],$start);
			}elseif($action=="confirmation"){	
				$signupform_data=$this->Signup_Model->get_signupform_stats(array('form_id'=>$fid,'activity'=>'3'),$config['per_page'],$start);
			} 
			 
			if(count($signupform_data)>0){
				foreach ($signupform_data as $contact){					
					if (($contact['subscriber_id'] > 0) &&($subscription_id>0)){						
						$this->Subscriber_Model->replace_subscription_subscriber(array('subscriber_id'=>$contact['subscriber_id'],'subscription_id'=>$subscription_id));
					}
				}
			}
			echo "success:Contacts added to the created list successfully!";
			//echo "success:".$this->db->last_query();
			exit;
		}
		
		

		$fetch_condiotions_array=array('subscription_created_by'=>$this->session->userdata('member_id'), 'is_deleted'=>0, 'subscription_id >'=>0);		
		$subscription_list=$this->subscription_Model->get_subscription_data($fetch_condiotions_array);
		
		// Recieve any messages to be shown, when category is added or updated
		$messages=$this->messages->get();
		
		$this->load->view('signup/add_signupform_contacts',array('action'=>$action,'form_id'=>$fid,'messages' =>$messages,'subscription_list'=>$subscription_list));
		
	}
	/**
		Function add_autoresponder_emailreport_to_contact_list to add subscribers  in Contact list
	*/
	function add_autoresponder_emailreport_to_contact_list($action="",$campaign_id=0,$scheduled_id=0){
		if($this->input->post('action')=='submit'){
			if($this->input->post('subscription_title')!=''){			
				$this->form_validation->set_rules('subscription_title', 'List Name', 'required|min_length[5]|max_length[100]|callback_title_check|trim');
				if($this->form_validation->run()==true){
					$input_array=array('subscription_title'=>$this->input->get_post('subscription_title', TRUE), 'subscription_is_name'=>'1', 'subscription_created_by'=>$this->session->userdata('member_id') );
					$subscription_id=$this->subscription_Model->create_subscription($input_array);
				}
			}else{
				$this->form_validation->set_rules('subscriptions', ' Contact List', 'required');
				if($this->form_validation->run()==true){
					$subscription_id=$this->input->post('subscriptions');
				}
			}
			if(intval($subscription_id) <= 0){
				echo 'error: '.validation_errors();
				exit;
			}
				
			if($action=="clickemail"){
				$fetch_condiotions_array=array('campaign_id'=>$id,'counter >'=>0,'is_autoresponder'=>1);
				$emailreport_data=$this->Emailreport_Model->get_emailreport_click($fetch_condiotions_array);
				
			}else{	
				if($action=="sent")	$fetch_condiotions_array=array(	'autoresponder_scheduled_id'=>$scheduled_id, 'res.subscriber_created_by'=>$this->session->userdata('member_id'));
				elseif($action=="read")$fetch_condiotions_array=array('autoresponder_scheduled_id'=>$scheduled_id, 'email_track_read'=>1,'res.subscriber_created_by'=>$this->session->userdata('member_id'));
				elseif($action=="unread")$fetch_condiotions_array=array('autoresponder_scheduled_id'=>$scheduled_id, 'email_track_read'=>0,'res.subscriber_created_by'=>$this->session->userdata('member_id'));
				elseif($action=="forwardemail")$fetch_condiotions_array=array('autoresponder_scheduled_id'=>$scheduled_id, 'email_track_forward >'=>0,'res.subscriber_created_by'=>$this->session->userdata('member_id'));
				elseif($action=="click")$fetch_condiotions_array=array('autoresponder_scheduled_id'=>$scheduled_id, 'email_track_click >'=>0,'res.subscriber_created_by'=>$this->session->userdata('member_id'));
				elseif($action=="unsubscribes")$fetch_condiotions_array=array('autoresponder_scheduled_id'=>$scheduled_id, 'email_track_unsubscribes >'=>0,'res.subscriber_created_by'=>$this->session->userdata('member_id'));
				elseif($action=="complaints")$fetch_condiotions_array=array('autoresponder_scheduled_id'=>$scheduled_id, 'email_track_complaint >'=>0,'res.subscriber_created_by'=>$this->session->userdata('member_id'));
				elseif($action=="bounced")$fetch_condiotions_array=array('autoresponder_scheduled_id'=>$scheduled_id, 'email_track_bounce >'=>0,'res.subscriber_created_by'=>$this->session->userdata('member_id'));				
				
				$emailreport_data=$this->Emailreport_Model->get_autoresponder_emailreport_subscriber($fetch_condiotions_array);
			}		
				
				 
			if(count($emailreport_data)>0){
				foreach ($emailreport_data as $emailreport){				 
					$last_inserted_id = $emailreport['email_track_subscriber_id'];
					if (($last_inserted_id > 0) &&($subscription_id>0)){
						$input_array=array('subscriber_id'=>$last_inserted_id,'subscription_id'=>$subscription_id);
						$this->Subscriber_Model->replace_subscription_subscriber($input_array);
					}
				}
			}
			echo "success:Contacts added to the list successfully!";
			exit;
			
		}
		 

		/**
			Creating array of conditions to checked with database with conditions.

		*/

		$fetch_condiotions_array=array('subscription_created_by'=>$this->session->userdata('member_id'),'is_deleted'=>0,'subscription_id >'=>0);
		
		// Fetches subscription data from database
		$subscription_list=$this->subscription_Model->get_subscription_data($fetch_condiotions_array);
		 
		$messages=$this->messages->get();
		if(count($subscription_list)>0){
			//Loads  view.
			$this->load->view('stats/add_emailreport_view',array('action'=>$action,'campaign_id'=>$campaign_id,'messages' =>$messages,'subscription_list'=>$subscription_list,'autoresponder'=>1,'scheduled_id'=>$scheduled_id));
		}else{
			echo "<div style=\"margin:20px;width:240px;\">Subscription List not exist</div>";
		}
	}
		/**
	* Populate BoldInbox account for Free & Paid users
	*/
	function populateRCMembers(){
		// Delete records for BoldInbox Free & Paid users
		$this->db->query("delete from red_email_subscription_subscriber where subscription_id='4' or subscription_id='5' or subscription_id='7'");
		// Populate free users
		$rsFreeMemberAsContact = $this->db->query("select m.email_address from red_members m inner join red_member_packages mp on m.member_id=mp.member_id where m.is_deleted=0 and m.status='active' and mp.package_id < 1");
		foreach($rsFreeMemberAsContact->result_array() as $m_rec){	
			$member_email = $m_rec['email_address'];
			$sid = $this->addMemberAsRCContact($member_email);
			$this->Subscriber_Model->replace_subscription_subscriber(array('subscriber_id'=>$sid,'subscription_id'=>'4')); // Free Users
		}
		$rsFreeMemberAsContact->free_result();
		// Populate paid users
		$yesterday 	= date("Y-m-d", strtotime("-1 days"));
		$rsPaidMemberAsContact = $this->db->query("select m.email_address from red_members m inner join red_member_packages mp on m.member_id=mp.member_id where m.is_deleted=0 and m.status='active' and mp.package_id > 0 and next_payement_date > '$yesterday'");
		foreach($rsPaidMemberAsContact->result_array() as $m_rec){	
			$member_email = $m_rec['email_address'];
			$sid = $this->addMemberAsRCContact($member_email);
			$this->Subscriber_Model->replace_subscription_subscriber(array('subscriber_id'=>$sid,'subscription_id'=>'5')); // Paid Users
		}
		$rsPaidMemberAsContact->free_result();
		// Populate failed-cc users
		$rsFailedccPaidMemberAsContact = $this->db->query("select m.email_address from red_members m inner join red_member_packages mp on m.member_id=mp.member_id where m.is_deleted=0 and m.status='active' and mp.package_id > 0 and next_payement_date < now()");
		foreach($rsFailedccPaidMemberAsContact->result_array() as $m_rec){	
			$member_email = $m_rec['email_address'];
			$sid = $this->addMemberAsRCContact($member_email);
			$this->Subscriber_Model->replace_subscription_subscriber(array('subscriber_id'=>$sid,'subscription_id'=>'7')); // Failed-cc Users
		}
		$rsFailedccPaidMemberAsContact->free_result();
		
	}
	function addMemberAsRCContact($eml){
		$rsCheckMemberAsContact = $this->db->query("select subscriber_id from red_email_subscribers where subscriber_created_by=197 and subscriber_email_address='$eml'");
		if($rsCheckMemberAsContact->num_rows() > 0){
			$sid =$rsCheckMemberAsContact->row()->subscriber_id;
		}else{
			$arrEmailExploded = explode( '@',$eml );
			$eml_domain = $arrEmailExploded[1];
			$qry = "INSERT INTO red_email_subscribers SET ";
			$flds = '';
			$flds .=  'subscriber_email_address = \'' . mysqli_real_escape_string($this->is_authorized->get_mysqli(), $eml) . '\', ';
			$flds .=  'subscriber_email_domain = \'' . mysqli_real_escape_string($this->is_authorized->get_mysqli(), $eml_domain) . '\', ';

			$flds .=  'subscriber_created_by = 197' ;
			$qry .=  $flds .' ON DUPLICATE KEY UPDATE ' . $flds . ', is_deleted = 0 , subscriber_id=LAST_INSERT_ID(subscriber_id)';
			$this->db->query($qry);
			
			$sid = $this->db->insert_id();	
		}
		$rsCheckMemberAsContact->free_result();
		return $sid;
	}
}
?>
