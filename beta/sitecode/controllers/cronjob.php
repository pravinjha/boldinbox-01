<?php
/**
*	Controller class for cronjob 
*	It have controller functions for subscribers management.
*/
class Cronjob extends CI_Controller
{
	private $confg_arr = array();
	/**
	*	Contructor for controller.
	*	It checks user session and redirects user if not logged in
	*/
	function __construct(){
        parent::__construct();	
		$this->load->helper('send_campaign');
		$this->load->helper('transactional_notification');	
		$this->load->helper('notification');
		$this->load->helper('admin_notification');
		
		$this->load->model('ConfigurationModel');
		$this->load->model('UserModel');		
		$this->load->model('userboard/Subscriber_Model');
		$this->load->model('Activity_Model');		
		$this->load->model('userboard/Campaign_Model');
		$this->load->model('userboard/Emailreport_Model');	
		$this->load->model('userboard/Campaign_Autoresponder_Model');
		$this->load->model('userboard/Cronjob_Model');
		$this->load->model('userboard/Autoresponder_Model');
		 	
		$this->confg_arr=$this->ConfigurationModel->get_site_configuration_data_as_array();
		$this->session->set_userdata('member_time_zone', 'GMT');
		date_default_timezone_set('GMT');
	}
	
	function bgprocess($memberid=0, $sublistid=0,$file_name="",$list_type=0, $do_notify=0){		
		$this->import_csv($memberid, $sublistid,$file_name,$list_type, $do_notify);		
	}	
	function import_csv($memberid=0, $sublistid=0,$file_name="",$list_type=0, $do_notify=0){
		ini_set('memory_limit', '-1');
		// Check if folder with modulo of User ID exists on server
		$user_dir = $memberid % 1000;		
			
		$upload_path= $this->config->item('user_private').$user_dir .'/'.$memberid.'/csv_files/'.$file_name.".csv";
		
		//open file for reading and create file handle 
		ini_set('auto_detect_line_endings', true);
		$file_handle=fopen($upload_path,'r');
		
		 
			
		$this->imported_subscribers=array();
		if($file_handle!==false){
		// update red_members with contact_import_progress
		$this->db->query("update `red_members` set `contact_import_progress`='1' where `member_id`='$memberid'");
		
		// Add a record to import history table
		$sqlImportHistory = "insert into `red_contact_import_batch`(`import_file_name`,`import_batch_size`,`member_id`, `list_id`) 
								values('{$file_name}.csv',0, '$memberid', '0')";
		$this->db->query($sqlImportHistory);
		$import_batch_id	= $this->db->insert_id();
		
				
		//  create array for insert values in activty table
		$values=array('user_id'=>$memberid, 'activity'=>'contact_import_starts', 'number_of_contacts'=>0, 'campaign_id'=>$import_batch_id, 'contact_list_type'=>$list_type, 'file_name'=>$file_name);
		$this->Activity_Model->create_activity($values);
		
		
		
			$isFirstRowHeader = true;
			$emailCol = -1;
			$firstNameCol = -1;
			$lastNameCol = -1;
			$dobCol = -1;
			$colheaders = array();
			$torow = 0;
			$new_contacts = 0;
			
			$email_header_found = false;
			
			if (!$data_array=fgetcsv($file_handle)) return false;
			
			$num = count($data_array);
			$arr_headers = array();
			for ($c=0; $c < $num && $isFirstRowHeader; $c++) {
				$data_array[$c] = trim($data_array[$c]);
				if($this->Cronjob_Model->checkEmail($data_array[$c])){
					$isFirstRowHeader = false;
					continue;
				}
				
				if($this->Cronjob_Model->isFirstName($data_array[$c])) $arr_headers['subscriber_first_name'] = $c;				
				else if($this->Cronjob_Model->isLastName($data_array[$c])) $arr_headers['subscriber_last_name'] = $c;
				else if($this->Cronjob_Model->isName($data_array[$c])) $arr_headers['subscriber_name'] = $c;
				else if($this->Cronjob_Model->isState($data_array[$c])) $arr_headers['subscriber_state'] = $c;
				else if($this->Cronjob_Model->isZipcode($data_array[$c])) $arr_headers['subscriber_zip_code'] = $c;
				else if($this->Cronjob_Model->isCountry($data_array[$c])) $arr_headers['subscriber_country'] = $c;
				else if($this->Cronjob_Model->isCity($data_array[$c])) $arr_headers['subscriber_city'] = $c;
				else if($this->Cronjob_Model->isBirthday($data_array[$c])) $arr_headers['subscriber_dob'] = $c;
				else if($this->Cronjob_Model->isCompany($data_array[$c])) $arr_headers['subscriber_company'] = $c;
				else if($this->Cronjob_Model->isPhone($data_array[$c])) $arr_headers['subscriber_phone'] = $c;
				else if($this->Cronjob_Model->isAddress($data_array[$c])) $arr_headers['subscriber_address'] = $c;
				else if($this->Cronjob_Model->isEmailAddress($data_array[$c])){
					$arr_headers['subscriber_email_address'][] = $c;
					$colheaders[$c] = $data_array[$c] ;
					$email_header_found = true;
				}
				if(!in_array($c, $arr_headers)){
					$colheaders[$c] = $data_array[$c] ;
				}
			}
			//Read the csv file
			while(($data_array=fgetcsv($file_handle))!==false){
				$thisEmailID =  '';
				$num = count($data_array);
				//  CHECK FOR HEADER ROW AND ASSIGN HEADERS
					
				//  ASSIGN VALUES
				$subscriber_array = array();
				if ($isFirstRowHeader){
					foreach ($arr_headers as $key=>$n){
						if('subscriber_email_address' == $key){
							foreach($n as $n1){
								if($this->Cronjob_Model->checkEmail($data_array[$n1])){
									$subscriber_array[$key] = $data_array[$n1];
									$arrEmailExploded = explode( '@',$data_array[$n1] );
									$subscriber_array['subscriber_email_domain'] = $arrEmailExploded[1];
									unset($colheaders[$n1]);
								}
							}
						}else
						$subscriber_array[$key] = trim($data_array[$n]);
						//$subscriber_array[$key] = $data_array[$n];
					}
				}
				
				if (!$isFirstRowHeader || !$email_header_found){
					foreach ($data_array as $val){
						if($this->Cronjob_Model->checkEmail($val)){
							$subscriber_array['subscriber_email_address'] = $val;
							$arrEmailExploded = explode( '@',$val );
							$subscriber_array['subscriber_email_domain'] = $arrEmailExploded[1];
							$isFirstRowHeader = false;
							break;
						}
					}
				}
					
				if (!array_key_exists('subscriber_email_address', $subscriber_array)) continue;
				if(!$this->Cronjob_Model->checkEmail($subscriber_array['subscriber_email_address'])) continue;
					
				if ($isFirstRowHeader){
					$arr_extra = array();
					foreach ($colheaders as $key=>$val) $arr_extra[$val] = trim($data_array[$key]);
					$subscriber_array['subscriber_extra_fields'] = serialize($arr_extra);
				}
				
				$qry = "INSERT INTO red_email_subscribers SET ";
				$flds = '';
				foreach ($subscriber_array as $key=>$val) $flds .= $key . ' = \'' . mysqli_real_escape_string($this->is_authorized->get_mysqli(), $this->fixEncoding(trim($val))) . '\', ';
				$flds .=  'subscriber_created_by = ' . $memberid ;
				//$qry .=  $flds .',import_batch_id='.$import_batch_id.' ON DUPLICATE KEY UPDATE ' . $flds . ', is_deleted = 0, subscriber_id=LAST_INSERT_ID(subscriber_id)';
				$qry .=  $flds .',import_batch_id='.$import_batch_id.' ON DUPLICATE KEY UPDATE ' . $flds . ', is_deleted = 0';
				
				$this->db->query( $qry );				
				// 0 - Row existed, nothing updated. 1 - No row existed, inserted. 2 - Row existed, something updated
				$contactAddedOrUpdated = $this->db->affected_rows(); 
				if($contactAddedOrUpdated == 1)$new_contacts++;
				
				
				if($this->db->affected_rows() == 1){	
					$last_inserted_id = $this->db->insert_id();		
					$torow++;						
				}else{
					$last_inserted_id = $this->db->query("select subscriber_id from red_email_subscribers where subscriber_created_by='$memberid' and subscriber_email_address='".$subscriber_array['subscriber_email_address']."'")->row()->subscriber_id;
				}
				 
				if ($last_inserted_id > 0){
					$arrListId = explode(',', rawurldecode($sublistid));
					foreach($arrListId as $lid){
						if($lid > 0){
							$input_array=array('subscriber_id'=>$last_inserted_id,'subscription_id'=>$lid);
							$this->Subscriber_Model->replace_subscription_subscriber($input_array);
							// echo $this->db->last_query();
						}
					}
				}
				
				 				
				
			} //  END OF WHILE
			if($torow>0){
				// Update record in import history table				
				$this->db->query("update `red_contact_import_batch` set `import_batch_size`='$torow' where `import_batch_id`='$import_batch_id'");	
				
				//  create array for insert values in activty table
				$values=array('user_id'=>$memberid, 'activity'=>'contact_imported','number_of_contacts'=>$torow,'campaign_id'=>$import_batch_id,'contact_list_type'=>$list_type, 'file_name'=>$file_name);
				$this->Activity_Model->create_activity($values);
				
				//  Fetch max_contacts_to_unauthenticate from configuration table 								
				$max_contacts_to_unauthenticate = $this->confg_arr['max_contacts_to_unauthenticate'];  
				$max_contacts_for_list_growing_alert = $this->confg_arr['max_contacts_for_list_growing_alert'];  
				
				// JSAMANI 2016.08.12 - Adding In Package Information for the RC Alert and # Contacts
				$total_contacts = $this->db->query("SELECT COUNT(subscriber_id) as totContacts FROM red_email_subscribers WHERE subscriber_created_by ='$memberid' AND subscriber_status =1 and is_deleted=0")->row()->totContacts;
 
				//  Update unauthentic_contacts in user table				
				$total_unauthentic_contacts = $this->db->query("select count(subscriber_id) as totFreshContact from red_email_subscribers where subscriber_created_by='$memberid' and subscriber_status=1 and is_deleted=0 and `sent`=0")->row()->totFreshContact;
				//$total_unauthentic_contacts = $this->db->query("select unauthentic_contacts as totFreshContact from red_members where member_id='$memberid'")->row()->totFreshContact;
				//$total_unauthentic_contacts = $total_unauthentic_contacts + $torow ;
				$rsIsAuthentic = $this->db->query("select is_authentic,apply_unauthentication_message from red_members where member_id='$memberid'");
				$member_is_authentic = $rsIsAuthentic->row()->is_authentic;
				$apply_unauthentication_message = $rsIsAuthentic->row()->apply_unauthentication_message;	
				$rsIsAuthentic->free_result();
				// Send List-growing User-notice, RC-alert & attach dashboard message to user's account
				// If user is paid & 1st-payment was 15 days before this contact import. 
				$rsIsListGrowingAlert = $this->db->query("select member_id from red_member_packages where member_id='$memberid' and package_id > 0  and next_payement_date > now() and start_payment_date < DATE_SUB(CURRENT_DATE(), INTERVAL 15 DAY)");
				if($rsIsListGrowingAlert->num_rows() > 0){
					$isMsgAttached = $this->db->query("select count(*) c from red_member_message where member_id='$memberid' and message_id=5 and is_deleted=0")->row()->c;
					if($apply_unauthentication_message and ($isMsgAttached < 1) and $total_unauthentic_contacts > $max_contacts_for_list_growing_alert){
						// Attach "List is growing" message in user dashboard
						$this->UserModel->attachMessage(array('member_id'=>$memberid, 'message_id'=>5));
						// Send user-notice
						$user_data_array=$this->UserModel->get_user_data(array('member_id'=>$memberid));
						$mname = $user_data_array[0]['member_username'];	
						$user_name = ($user_data_array[0]['first_name'] != '')? $user_data_array[0]['first_name'] : $mname ;
							
						$user_info=array($user_name);
						create_transactional_notification("list_growing",$user_info,$user_data_array[0]['email_address']);
					
						// Admin notification starts					 		
						$to = $this->confg_arr['admin_notification_email'];		
						$message = "<p>Hello admin,</p><p>List is growing for RC Member: $mname [$memberid]</p><p>Regards,<br />BoldInbox Team</p>";		
						$text_message= "List is growing for RC Member: $mname [$memberid]";
						// Removed by pravinjha@gmail.com
						// admin_notification_send_email($to, SYSTEM_EMAIL_FROM,'BoldInbox', "List growing for $mname [$memberid]",$message,$text_message);
						// Admin notification ends
					}	
					
				}
				$rsIsListGrowingAlert->free_result();
				//List-growing User-notice ENDS
				
				$unauthenticNotes = '';	
				if($total_unauthentic_contacts > $max_contacts_to_unauthenticate){
					$approval_notes = 'Unauthenticated after contact import by system';
					$unauthenticNotes = ", campaign_approval_notes = IFNULL(concat(replace(campaign_approval_notes, '$approval_notes','') , '$approval_notes' ), '$approval_notes')";	
				}
								
				if( ($total_unauthentic_contacts > $max_contacts_to_unauthenticate)  && ($member_is_authentic > 0)){	
					// Unauthentic count rest and mark user as unauthentic					
					$this->db->query("update red_members set unauthentic_contacts=0, is_authentic=0, is_automatic_segmentation=0 $unauthenticNotes where `member_id`='$memberid'");
					// Admin notification starts												
					$mname = $this->db->query("SELECT member_username FROM `red_members` where `member_id` = '$memberid'")->row()->member_username;			
					$to = $this->db->query('SELECT config_value FROM `red_site_configurations` where `config_name` = "admin_notification_email"')->row()->config_value;			
					$message = "<p>Hello admin,</p><p>RC Member :$mname [$memberid] is unauthenticated after contacts import</p><p>Regards,<br />BoldInbox Team</p>";		
					$text_message= "RC Member :$mname [$memberid] is unauthenticated after contacts import";
					// Removed by pravinjha@gmail.com
					// admin_notification_send_email($to, SYSTEM_EMAIL_FROM,'BoldInbox', 'User unauthenticated',$message,$text_message);
					// Admin notification ends	
				}else{	
					$this->db->query("update red_members set unauthentic_contacts='$total_unauthentic_contacts'  $unauthenticNotes where `member_id`='$memberid'");	
				}
				// Load log configuration model class which handles database interaction				 
				$maximum_add_contact = $this->confg_arr['maximum_add_contact'];  
				if($torow  > $maximum_add_contact){
					$this->contact_notification($torow,$maximum_add_contact,'add',$memberid, $total_contacts);
				}
			}
			//----------------------------------------
			if($do_notify){
			/*
			** 	Check whether now user requires Upgradation or not.
			**	 And accordingly send notification email.
			*/
			$package_max_contacts = $this->UserModel->get_current_packages_maxcontact($memberid);		 
			$totalContactsNow = $this->Subscriber_Model->get_subscriber_count(array('subscriber_created_by'=>$memberid,'subscriber_status'=>1,'is_deleted'=>0));		
			/* End Comparision */ 
			$user_data_array=$this->UserModel->get_user_data(array('member_id'=>$memberid));
			if($user_data_array[0]['first_name']){
				$user_name=$user_data_array[0]['first_name'];
			}else{
				$user_name=$user_data_array[0]['member_username'];
			}		
			$user_info=array($user_name);
			
			
			if($totalContactsNow >  $package_max_contacts)
			create_transactional_notification("contact_imported_upgrade_notification",$user_info,$user_data_array[0]['email_address']);
			else
			create_transactional_notification("contact_imported_notification",$user_info,$user_data_array[0]['email_address']);
		}
		//----------------------------------------
			//close file handle and delete the file
			fclose($file_handle);
			if($list_type==2){
				// unlink($upload_path);
			}
			// update red_members with contact_import_progress as false
			$this->db->query("update `red_members` set `contact_import_progress`='0' where `member_id`='$memberid'");
		
			$msg="File imported successfully";
			//return success message
			return $msg;
		}
	}
	function fixEncoding($in_str) { 
        $cur_encoding = mb_detect_encoding($in_str); 
        if($cur_encoding == "UTF-8" && mb_check_encoding($in_str,"UTF-8")) { 
            return $in_str; 
        } else { 
            return utf8_encode($in_str); 
        } 
    }
	
	function contact_notification($add_contacts=0,$max_contacts=0,$action="",$member_id=0, $total_number_contacts=0){
		if($member_id<=0){
			$member_id=$this->session->userdata('member_id');
		}
		// Fetch user data from database
		$user_data_array=$this->UserModel->get_user_data(array('member_id'=>$member_id));
		$user_info=array($user_data_array[0]['member_username'],$add_contacts,$max_contacts,$action,$total_number_contacts);
		
		if($action=="add"){
			@create_notification("add_contact_limit",$user_info,0,0,0,$total_number_contacts);
		}else{
			@create_notification("delete_contact_limit",$user_info);
		}
	}
	/**
	*	'sendBomb' controller function to send DNMs for scheduled campaign emails.
	*/
	function sendDNM(){		 
		set_time_limit(0);
		//Check cronjob status: completed or working	
		if(trim($this->confg_arr['preprocessing_cron']) == '1'){
			exit;
		}else{
			// update cronjob status to completed
			$this->ConfigurationModel->update_site_configuration(array('config_value'=>'1'), array('config_name'=>'preprocessing_cron')); 			
			$this->ConfigurationModel->update_site_configuration(array('config_value'=>date("Y-m-d H:i:s", time())),array('config_name'=>'preprocessing_start_date'));			
			
			// process starts
			$do_not_mail_list_arr =	@explode(',' ,$this->confg_arr['do_not_mail_list']);	
			$arr_unresponsive_ignored = @explode(',' ,$this->confg_arr['unresponsive_ignored']);				
			
			// Fetch list of active campaign
			$fetch_conditions_array=array( 'campaign_sheduled IS NOT NULL '=>NULL, 'is_deleted'=>0, 'is_preprocessed'=>0, 'campaign_status'=>'archived', 'campaign_contacts >' => 8000,'is_segmentation'=>0);

			//$campaign_count=$this->Campaign_Model->get_campaign_count($fetch_conditions_array);		 
			//echo "<br/>".$this->db->last_query(); 
			//$campaign_count= ($campaign_count >  2) ? 2 : $campaign_count ;
			 
			//$campaigns=$this->Campaign_Model->get_campaign_data($fetch_conditions_array,$campaign_count, 0, 'asc', 'campaign_sheduled');	
			$campaigns=$this->Campaign_Model->get_campaign_data($fetch_conditions_array,1, 0, 'asc', 'campaign_sheduled');	
			//echo "<br/>".$this->db->last_query();  
			$defaultSegmentSize = 70000;	 
			foreach($campaigns as $campaign){
				$campaign_id = $campaign['campaign_id'];				
				$campaign_created_by = $campaign['campaign_created_by'];
				$fetch_condiotions_array=array( 'campaign_id'=>$campaign_id, 'email_sent'=>0);				
				$email_subscriber=$this->Emailreport_Model->get_subscriber_emailreport_data($fetch_condiotions_array, true, $defaultSegmentSize,0, true);		
				$pq = "<br/>".$this->db->last_query(); 
				$pq .= "<br/>campaign_created_by = $campaign_created_by"; 				
				$total_contact_selected=count($email_subscriber);
				if($total_contact_selected > 0){
					// Check for the user who has scheduled that what is his package's max. contact at present compare it with the count(contacts) of that campaign
					if($this->check_user_contacts($total_contact_selected,$campaign_created_by)){					 
						// add links:emailtrack_img,footer,unsubscribe,forward links with campaign
						$user=$this->UserModel->get_user_data(array('member_id'=>$campaign_created_by));					 
						foreach($email_subscriber as $subscriber_info){
							// IF valid-email= true, DNM= false and Ignore-unresponsive=false then Campaign will be sent
							//  and !($this->is_global_dnm($subscriber_info,$campaign))
							$not_sent_reason =0;
							if(!$this->is_authorized->ValidateAddress($subscriber_info['subscriber_email_address'])){
								$not_sent_reason = 1;
							}elseif(!$this->is_contact_active($subscriber_info['subscriber_id'])){
								$not_sent_reason = 2;
							}elseif($this->do_not_mail($subscriber_info['subscriber_email_address'],$do_not_mail_list_arr,$user[0]['member_dnm'])){
								$not_sent_reason = 3;
							}elseif($this->is_soft_bounced($subscriber_info)){
								$not_sent_reason = 4;							
							//}elseif($this->ignore_unresponsive_global_domain($subscriber_info,'yahoo.com')){ // STOP unresponsive yahoo or whatever completely
							//		$not_sent_reason = 5;							
							//}elseif($this->ignore_unresponsive_global_domain($subscriber_info,'@aol.com')){ // STOP unresponsive AOL or whatever completely
							//		$not_sent_reason = 5;							
							}elseif($this->ignore_unresponsive($subscriber_info,$arr_unresponsive_ignored, $user,$campaign)){					
								$not_sent_reason = 5;							
							}
							if($not_sent_reason > 0){
								$thisEmailId = $subscriber_info['subscriber_email_address'];
								$arrEml = explode('@',$thisEmailId);
								$emlDomain = $arrEml[1];
								// mark contact as sent. whether actually campaign gets released or not is not important here.
								$this->db->query("update red_email_subscribers set `sent`= `sent` + 1,`last_sent_date`=current_timestamp()  where `subscriber_id`='".$subscriber_info['subscriber_id']."'");						
								$this->db->query("update `red_email_campaigns_scheduled` set `email_track_sent` = `email_track_sent`+1 where campaign_id='$campaign_id'");					
							
								$this->db->query("update `red_member_packages` set `campaign_sent_counter`=(`campaign_sent_counter` + 1) where `member_id`='$campaign_created_by'");								
								//$update_qry="update red_email_queue set email_sent=1,not_sent_reason='$not_sent_reason' where campaign_id ='$campaign_id' AND `subscriber_id`='".$subscriber_info['subscriber_id']."'";
								//$this->db->query($update_qry);	
								$this->db->trans_start();
								$this->db->query("INSERT INTO `red_email_track` set `campaign_id`='$campaign_id', `user_id`='$campaign_created_by', `subscriber_id`='".$subscriber_info['subscriber_id']."', `subscriber_email_address`='$thisEmailId', `subscriber_email_domain`='$emlDomain', `email_sent`=1, `email_sent_date`=now(), `not_sent_reason`='$not_sent_reason'");								
								$this->db->query("delete from red_email_queue where campaign_id ='$campaign_id' AND `subscriber_id`='".$subscriber_info['subscriber_id']."'"); 								
								$this->db->trans_complete();								
								
								// Update Daily-global-IPR																	
								$IPR_Domain = (in_array($emlDomain,config_item('major_domains')))? $emlDomain : 'all' ;
								$this->db->query("insert into red_global_ipr_daily set `mail_domain` = '$IPR_Domain' ,  `log_date`=CURDATE() ,  `pipeline`='$vmta', `user_id`='$campaign_created_by', total_sent= total_sent + 1 ON DUPLICATE  KEY UPDATE  total_sent= total_sent + 1");								
							}														
						}// For loop ends : Subscriber	
						// Mark it processed
						$this->db->query("update red_email_campaigns set is_preprocessed=1 where campaign_id='$campaign_id' ");
						//$this->move_email_queue_to_track();		
					}else{						
						$this->Campaign_Model->update_campaign(array('campaign_status'=>'disallow'),array('campaign_id'=>$campaign_id));							
						$this->Emailreport_Model->delete_emailqueue(array('campaign_id'=>$campaign_id));						
						$this->campaign_not_scheduled_notification($campaign_created_by,$campaign_id,$campaign['email_subject'],$total_contact_selected);											
					}
				}
				if($total_contact_selected < $defaultSegmentSize){
					$this->db->query("update red_email_campaigns set is_preprocessed=1 where campaign_id='$campaign_id' ");											
				}
			}	// For loop Ends: Campaign
			// update cronjob status to completed
			$this->ConfigurationModel->update_site_configuration(array('config_value'=>'0'),array('config_name'=>'preprocessing_cron'));
			$this->ConfigurationModel->update_site_configuration(array('config_value'=>date("Y-m-d H:i:s", time())),array('config_name'=>'preprocessing_start_date'));		
		}
		exit;	
	}	
	/**
	*	Function to send scheduled campaign emails.
	*/
	function send($send=""){		 
		set_time_limit(0);		
		// $this->output->enable_profiler(TRUE);
		// If stopped by admin then dont send any campaign
		if( trim($this->confg_arr['continue_campaign_send']) !="1"){
			exit;
		}			
		// Check cronjob status :completed or working		 		
		$maxProcessLimit = $this->confg_arr['max_concurrent_processes'];		
		$campaign_batch_size = $this->confg_arr['campaign_batch_size'];		
		$do_not_mail_list_arr =	@explode(',' ,$this->confg_arr['do_not_mail_list']);	
		$arr_unresponsive_ignored = @explode(',' ,$this->confg_arr['unresponsive_ignored']);	  
			
		$totalRunningProcess = $this->checkProcessStatus();			
		if( $this->confg_arr['cronjob_status'] =="working"){
			$this->alertIfStucked();
			//$this->move_email_queue_to_track();		
			exit;
		}elseif($totalRunningProcess >20){
			// wait for 2 minutes and then reset the processes in red_campaign_thread
			$this->waitForProcessToComplete(120);			
			//  before proceeding reset threads
			$this->db->query("update `red_campaign_thread` set `thread_status`='0' where 1");			 
			exit;

		}else{	
		
			//  before proceeding reset threads
			$this->db->query("update `red_campaign_thread` set `thread_status`='0' where 1");	
			
			// update cronjob status to completed
			$this->ConfigurationModel->update_site_configuration(array('config_value'=>'working'),array('config_name'=>'cronjob_status'));
			$utc_str = gmdate("Y-m-d H:i:s", time());
			$this->ConfigurationModel->update_site_configuration(array('config_value'=>$utc_str),array('config_name'=>'campaign_cron_status_change_time'));
				
			// count segmented campaigns
			$fetch_conditions_array=array('date_add(campaign_sheduled, INTERVAL campaign_delay_minute MINUTE) <='=>date("Y-m-d H:i:s", time()), 'campaign_sheduled IS NOT NULL '=>NULL, 'is_deleted'=>0, 'campaign_status'=>'archived', 'is_segmentation'=>1);
			$segmented_campaign_count=$this->Campaign_Model->get_campaign_count($fetch_conditions_array);		 
			
			// Fetch list of active campaign
			//$fetch_conditions_array=array('campaign_sheduled <='=>date("Y-m-d H:i:s", time()), 'campaign_sheduled IS NOT NULL '=>NULL, 'is_deleted'=>0, 'campaign_status'=>'archived', 'is_segmentation'=>0);
			$fetch_conditions_array=array('date_add(campaign_sheduled, INTERVAL campaign_delay_minute MINUTE) <='=>date("Y-m-d H:i:s", time()), 'campaign_sheduled IS NOT NULL '=>NULL, 'is_deleted'=>0, 'campaign_status'=>'archived');
			$campaign_count=$this->Campaign_Model->get_campaign_count($fetch_conditions_array);		
						
			//$campaign_count= ($campaign_count >  7) ? 7 : $campaign_count ;
			// release segmented-campaigns & 5 non-segmented campaigns
			$campaign_count= ($campaign_count >  ($segmented_campaign_count + 3)) ? ($segmented_campaign_count + 3) : $campaign_count ;
			$campaigns=$this->Campaign_Model->get_campaign_data($fetch_conditions_array,$campaign_count, 0, 'asc', 'campaign_sheduled');			
			 
			foreach($campaigns as $campaign){
				$defaultSegmentSize = 8000;	 
				$thisCampaignId = $campaign['campaign_id'];
				$campaign_created_by = $campaign['campaign_created_by'];
				$thisIsPreprocessed = $campaign['is_preprocessed'];
				// update campaign under-progress  
				$this->ConfigurationModel->update_site_configuration(array('config_value'=>$thisCampaignId),array('config_name'=>'campaign_under_progress'));
				// Mark campaign_status "ready", if sender-name or sender-email or subject or campaign-content is empty 
				if(trim($campaign['email_subject']) == '' or trim($campaign['sender_name']) == '' or trim($campaign['sender_email']) == '' or trim($campaign['campaign_content']) == ''){
					$this->db->query("update red_email_campaigns set campaign_status='ready' where campaign_id='$thisCampaignId'");
					break;
				}
				// PAUSE Start: If campaign is delivered to 25% and open rate is less than 2%, then pause the campaign.
				//$this->pauseIfLowOpen($thisCampaignId, $campaign_created_by);				
								
				$user=$this->UserModel->get_user_data(array('member_id'=>$campaign_created_by));
				$always_slow_release = $user[0]['always_slow_release'];
				$is_seedlist = $user[0]['attach_seedlist'];
				$vmta	= $user[0]['vmta']; 
				$apply_unresponsive_filter	= $user[0]['apply_unresponsive_filter']; 

									
				// For non-segmented campaigns, defaultSize is as defined above.
				// For segmented campaigns, segmentationSize = defaultSize for contacts who were sent in past.
				$fetch_condiotions_array=array( 'campaign_id'=>$thisCampaignId, 'email_sent'=>0);				
				if($campaign['is_segmentation']==1){
					if($always_slow_release == 1){
						$email_subscriber=$this->Emailreport_Model->get_subscriber_emailreport_data($fetch_condiotions_array,false,$campaign['number_of_contacts'],0,true);
					}elseif($thisIsPreprocessed == 2){//Release ongoing-campaigns mails to fresh-contacts as segmented-size
						$email_subscriber=$this->Emailreport_Model->get_subscriber_emailreport_data($fetch_condiotions_array,false,$campaign['number_of_contacts'],0,true);				
					}else{
						$email_subscriber=$this->Emailreport_Model->get_subscriber_emailreport_data($fetch_condiotions_array, false, $defaultSegmentSize,0, true);
					}
				}else{
				 	$email_subscriber=$this->Emailreport_Model->get_subscriber_emailreport_data($fetch_condiotions_array, false, $defaultSegmentSize,0, true);
				}
				 
				$total_contact_selected=count($email_subscriber);
				
				// Check for the user who has scheduled that what is his package's max. contact at present compare it with the count(contacts) of that campaign
				if($this->check_user_contacts($total_contact_selected,$campaign_created_by)){					 
						// add links:emailtrack_img,footer,unsubscribe,forward links with campaign
						$camapign_message=$this->Campaign_Autoresponder_Model->attach_campaign_link($campaign,$user);				 					 
						// collect text message
						$campaign_footer_text_only = $this->Campaign_Autoresponder_Model->campaign_footer_text_only($user, $thisCampaignId, false, true);
						$campaign_text_message=$campaign['campaign_text_content'].$campaign_footer_text_only;
						$campaign_text_message=str_replace('This is a pre-header. Use this area to write a short preview of your email content.','',$campaign_text_message);
											
						/* $camapign_message=$this->is_authorized->webCompatibleString($camapign_message);
						$camapign_message=utf8_decode($camapign_message); */
	
						// set subject of email
						$campaign_subject=$campaign['email_subject'];
						$campaign_type=('5' == trim($campaign['campaign_template_option']))?'text':'html';
						 
						// set sender of email
						$sender_name = $campaign['sender_name'];
						$sender	= $campaign['sender_email'];
						$reply_to_email	= $campaign['reply_to_email'];
						
						
						$email_personalization=true;
					unset($subscriber_replace_arr);
					if($total_contact_selected > 0){
						$mailCounter = 0;
						$arrCampaigns = array();
						// START: Transaction variables for unsent contacts
						$sqlAddtoTrack = '';
						$arrUpdateQueue = array();
						$arrUpdateContact = array();
						$arrDeleteQueue = array();
						// ENDS: Transaction variables for unsent contacts
						foreach($email_subscriber as $subscriber_info){
							// If not-active or unsent-yet contact is found, break out of the loop Else, release as normal campaign
							if($campaign['is_segmentation']==1 && $always_slow_release == 0 && $subscriber_info['is_active'] == 0 && $thisIsPreprocessed < 2){
								$this->db->query("update red_email_campaigns set is_preprocessed = 2 where campaign_id='$thisCampaignId'");
								break;
							}
							
							// mark contact as sent. whether actually campaign gets released or not is not important here.
							$this->db->query("update red_email_subscribers set `sent`= `sent` + 1,`last_sent_date`=current_timestamp()  where `subscriber_id`='".$subscriber_info['subscriber_id']."'");						
							$this->db->query("update `red_email_campaigns_scheduled` set `email_track_sent` = `email_track_sent`+1 where campaign_id='$thisCampaignId'");					
							//  BLOCK PROCESS TILL ALL PROCESSES ARE BUSY OR NOT							
							$this->waitTillThreadIsBusy();
							 
							// AS SOON AS NO. OF PROCESSES IS LESS THAN 20 PROCEED
							// IF valid-email= true, DNM= false and Ignore-unresponsive=false then Campaign will be sent
							//  and !($this->is_global_dnm($subscriber_info,$campaign))
							$isMailSent = false;
							if(!$this->is_authorized->ValidateAddress($subscriber_info['subscriber_email_address'])){
								$not_sent_reason = 1;
							}elseif(!$this->is_contact_active($subscriber_info['subscriber_id'])){
								$not_sent_reason = 2;
							}elseif($this->do_not_mail($subscriber_info['subscriber_email_address'],$do_not_mail_list_arr,$user[0]['member_dnm'])){
								$not_sent_reason = 3;
							}elseif($this->is_soft_bounced($subscriber_info)){
								$not_sent_reason = 4;							
							//}elseif($this->ignore_unresponsive_global_domain($subscriber_info,'yahoo.com',$campaign)){ // STOP unresponsive yahoo or whatever completely
							//		$not_sent_reason = 5;							
							//}elseif($this->ignore_unresponsive_global_domain($subscriber_info,'@aol.com',$campaign)){ // STOP unresponsive AOL or whatever completely
							//		$not_sent_reason = 5;							
							}elseif($this->ignore_unresponsive($subscriber_info,$arr_unresponsive_ignored, $user,$campaign)){					
								$not_sent_reason = 5;							
							}else{							
									$isMailSent = true;
									$not_sent_reason = 0;
									$mailCounter++;
									
									$message=$camapign_message;
									$text_message=$campaign_text_message;
									$subject=$campaign_subject;
									// Replace campaign content according to subscriber detail
									$subscriber_vmta = (!is_null($subscriber_info['subscriber_vmta']) && $subscriber_info['subscriber_vmta'] != '')? $subscriber_info['subscriber_vmta'] : $vmta;
									$this->Campaign_Autoresponder_Model->getPersonalization($message,$text_message,$subject,$subscriber_info,false,$thisCampaignId,$subscriber_vmta, $email_personalization);
									/*			
									echo "<br/>=========================================================<br/>";
									echo $subscriber_info['subscriber_email_address'];
									echo "<br/>=========================================================<br/>";
									echo $message;
									echo "<br/>=========================================================<br/>";   
									echo 'reply_to_email='.$reply_to_email;
									echo 'sender='.$sender;
									echo "<br/>=========================================================<br/>";   
									exit;
									*/  
									 
									//  Create array of arrays for the campaign to be sent with the details of subscriber								 
									$arrCampaigns[] =
										array('campaign_type'=>$campaign_type,'message'=>$message,'text_message'=>$text_message,'subject'=>$subject, 'sender_name'=>$sender_name,'sender'=>$sender,'reply_to_email'=>$reply_to_email,'campaign_id'=>$thisCampaignId,'subscriber_info'=>$subscriber_info,'vmta'=>$subscriber_vmta,'campaign_created_by'=>$campaign_created_by);
									//  IF ARRAY SIZE BECOMES 100 THEN SEND IN THREAD	
									if($mailCounter == $campaign_batch_size){
										$this->send_campaign_in_threads($arrCampaigns);
										unset( $arrCampaigns );
										$arrCampaigns = array();
										$mailCounter = 0;
									}																
							}
							//echo $isMailSent;
							if($isMailSent === false){
								$thisEmailId = $subscriber_info['subscriber_email_address'];
								$arrEml = explode('@', $thisEmailId);
								$emlDomain = $arrEml[1];
								
								$this->db->query("update `red_member_packages` set `campaign_sent_counter`=(`campaign_sent_counter` + 1) where `member_id`='$campaign_created_by'");								
								
								//$this->db->query("update red_email_queue set email_sent=1,not_sent_reason='$not_sent_reason' where campaign_id ='$thisCampaignId' AND `subscriber_id`='".$subscriber_info['subscriber_id']."'");	
								$this->db->trans_start();
								$this->db->query("INSERT INTO `red_email_track` set `campaign_id`='$thisCampaignId', `user_id`='$campaign_created_by', `subscriber_id`='".$subscriber_info['subscriber_id']."', `subscriber_email_address`='$thisEmailId', `subscriber_email_domain`='$emlDomain', `email_sent`=1, `email_sent_date`=now(), `not_sent_reason`='$not_sent_reason'");								
								$this->db->query("delete from red_email_queue where campaign_id ='$thisCampaignId' AND `subscriber_id`='".$subscriber_info['subscriber_id']."'"); 								
								$this->db->trans_complete();
								
								// Update Daily-global-IPR																	
								$IPR_Domain = (in_array($emlDomain,config_item('major_domains')))? $emlDomain : 'all' ;
								$this->db->query("insert into red_global_ipr_daily set `mail_domain` = '$IPR_Domain' ,  `log_date`=CURDATE() ,  `pipeline`='$vmta', `user_id`='$campaign_created_by', total_sent= total_sent + 1 ON DUPLICATE  KEY UPDATE  total_sent= total_sent + 1");					
							}// IF End : DNM					
						} // FOR End : contacts				
						//  IF ARRAY SIZE IS LESS THAN 100
						$this->send_campaign_in_threads($arrCampaigns);
						unset( $arrCampaigns );
						$arrCampaigns = array();
						
						//$this->move_email_queue_to_track();		
					} // IF End : contacts				
					
					//if($total_contact_selected < $defaultSegmentSize or $campaign['is_segmentation']==1) 																	
					if($campaign['is_segmentation']==1){
						// Archive campaign
						$date=date("Y-m-d H:i:s");	
						$this->Campaign_Model->update_campaign(array('campaign_status'=>'active','pipeline'=>$vmta,'email_send_date'=>$date),array('campaign_id'=>$thisCampaignId));
					}elseif($total_contact_selected < $defaultSegmentSize){
						// Archive campaign
						$date=date("Y-m-d H:i:s");	
						$this->Campaign_Model->update_campaign(array('campaign_status'=>'active','pipeline'=>$vmta,'email_send_date'=>$date),array('campaign_id'=>$thisCampaignId));
						
						// Notify to user						
						$this->campaign_send_notification($campaign_created_by, $thisCampaignId, $campaign['email_subject'],$total_contact_selected);	
						//$this->UserModel->incrementCampaignSentCounter($campaign_created_by, $thisCampaignId);	
					}
					// Seedlist
					if(trim($is_seedlist) == '1'){						
						$arrSeedlistMails = @explode(',',trim($this->confg_arr['seedlist']));
						if(count($arrSeedlistMails) > 0){
							foreach($arrSeedlistMails as $seedlist_contact){
							$subscriber_info = array('subscriber_id'=>'-99','subscriber_email_address'=>$seedlist_contact,'subscriber_first_name'=>'','subscriber_last_name'=>'','subscriber_state'=>'','subscriber_zip_code'=>'','subscriber_country'=>'','subscriber_city'=>'','subscriber_company'=>'','subscriber_dob'=>'','subscriber_phone'=>'','subscriber_address'=>'','subscriber_extra_fields'=>'');
							
							$message=$camapign_message;							
							$text_message=$campaign_text_message;
							$subject=$campaign_subject;
							
							
							// Replace campaign content according to subscriber detail
							$this->Campaign_Autoresponder_Model->getPersonalization($message,$text_message,$subject,$subscriber_info,false,$thisCampaignId,$vmta, $email_personalization);
							
							
							$arrCampaigns[] =
									array('campaign_type'=>$campaign_type,'message'=>$message,'text_message'=>$text_message,'subject'=>$subject,'sender_name'=>$sender_name,'sender'=>$sender,'campaign_id'=>$thisCampaignId,'subscriber_info'=>$subscriber_info,'vmta'=>$vmta);
							}		
						}			
						if(count($arrCampaigns) > 0){				
							$this->send_campaign_in_threads($arrCampaigns);
							unset( $arrCampaigns );
							$arrCampaigns = array();			
						}
					} // IF End : Seedlist									
				}else{
					$this->Campaign_Model->update_campaign(array('campaign_status'=>'disallow'), array('campaign_id'=>$thisCampaignId));
					$this->Emailreport_Model->delete_emailqueue(array('campaign_id'=>$thisCampaignId));
					$this->campaign_not_scheduled_notification($campaign_created_by,$thisCampaignId,$campaign['email_subject'],$total_contact_selected);
				}// IF : RC-member Eliginbility test
				
			
			}// FOR : Campaign 
			
			
			// update cronjob status to completed
			$this->ConfigurationModel->update_site_configuration(array('config_value'=>'completed'),array('config_name'=>'cronjob_status'));
			$utc_str = gmdate("Y-m-d H:i:s", time());
			$this->ConfigurationModel->update_site_configuration(array('config_value'=>$utc_str),array('config_name'=>'campaign_cron_status_change_time'));
	
		}// IF : Campaign Process
	 
		
	}	
	function checkProcessStatus(){
		 
		$totalActiveProcess = $this->db->where('thread_status', 1)->count_all_results('red_campaign_thread');
		return $totalActiveProcess;
		
	}
	function waitTillThreadIsBusy(){
		//  BLOCK PROCESS TILL ALL PROCESSES ARE BUSY OR NOT
		$totalRunningProcess = $this->checkProcessStatus();
		
		while( $totalRunningProcess >= $this->confg_arr['max_concurrent_processes']){								
			sleep(10); //  Wait for 10 seconds and then move sent campaigns from queue to track.
			$this->move_email_queue_to_track();		
			$totalRunningProcess = $this->checkProcessStatus();
		}
		return;	
	}
	function waitForProcessToComplete($noSeconds=180){
		$tick=time();
		$nexttick = $tick + $noSeconds;
		while($tick < $nexttick){
			sleep(10);
			$tick=time();		
		}
		return;
	
	}
	/**
	  *	Function move_email_queue_to_track to insert data in email track table and to remove from queue table
	**/
	function move_email_queue_to_track(){		
		$queryCopyToTrack = "INSERT INTO `red_email_track`(`campaign_id`, `user_id`, `subscriber_id`, `subscriber_email_address`, `subscriber_email_domain`, `email_sent`, `email_sent_date`, `not_sent_reason`)  select `campaign_id`, `user_id`, `subscriber_id`, `subscriber_email_address`, substring_index(subscriber_email_address,'@',-1), `email_sent`, `email_sent_date`,`not_sent_reason` from red_email_queue where `email_sent` = 1";
		
		 $queryDeleteFromQueue = "Delete from red_email_queue where `email_sent` = 1";
		$this->db->trans_start();
		$a1 = $this->db->query($queryCopyToTrack);
		$a2 = $this->db->query($queryDeleteFromQueue);
		$this->db->trans_complete();
		if ($this->db->trans_status() === FALSE){
			// generate an error... or use the log_message() function to log your error			
		}		
	}
	function move_unsent_to_track($sqlInsertToTrack, $arrMarkQueueAsSent, $arrIncrementContactSentCounter, $arrDeleteFromQueue, $mid ){		
		/**
		* START: CREATE TRANSACTION FOR UNSENT CAMPAIGNS
		*/					
		$countUpdateQueueArray = count($arrMarkQueueAsSent);						
		if($countUpdateQueueArray > 0){
			// Start transactions
			$this->db->trans_start();
			$sqlInsertToTrack = rtrim($sqlInsertToTrack,',');			
			$sqlInsertToTrack = "INSERT INTO `red_email_track`(`campaign_id`, `user_id`, `subscriber_id`, `subscriber_email_address`, `subscriber_email_domain`, `email_sent`, `email_sent_date`, `not_sent_reason`) values ".$sqlInsertToTrack ;
			$this->db->query($sqlInsertToTrack);
			foreach($arrMarkQueueAsSent as $sqlUpdateQueue)
			$this->db->query($sqlUpdateQueue);
			
			foreach($arrIncrementContactSentCounter as $sqlUpdateContact)
			$this->db->query($sqlUpdateContact);
			
			foreach($arrDeleteFromQueue as $sqlDeleteQueue)
			$this->db->query($sqlDeleteQueue);
			
			$this->db->query("update `red_member_packages` set `campaign_sent_counter`=(`campaign_sent_counter` + $countUpdateQueueArray) where `member_id`='$mid'");
			
			$this->db->trans_complete();			
			// END transactions
			if ($this->db->trans_status() === FALSE){
				log_message('error', $sqlInsertToTrack); // ERROR
			}
		}					
		/**
		* ENDS: CREATE TRANSACTION FOR UNSENT CAMPAIGNS
		*/		
	}
	/**
	  *	Function send_campaign_in_threads to send campaigns in thread of 100 campaigns each
	**/
	
	function send_campaign_in_threads($arrCampaigns){		  
		$i = $this->getFreeThreadID();		 
		$campaign_fname =  date('YmdHis').'-'.$i; 
		file_put_contents($this->config->item('campaign_files').$campaign_fname, serialize($arrCampaigns));
		// update campaign_sending_process to active			
		$thread_log = config_item('campaign_files').'thread_log_'.date('Ymdhis');
		$this->db->query("update `red_campaign_thread` set `thread_status` = '1' where `thread_id` = '$i'");
		$command = config_item('php_path')." ".FCFOLDER."/index.php  cronjob campaign_thread $campaign_fname $i";		 
		
		exec( "$command >> $thread_log  2>&1 &", $arrOutput );						
	}
 
	function getFreeThreadID(){		
		$query = $this->db->query("SELECT `thread_id` FROM `red_campaign_thread` where `thread_status` = '0' and `thread_id` < 30 limit 0,1 ");
		
		if ($query->num_rows() == 1){
			return $query->row()->thread_id;
		}else{
			$this->waitTillThreadIsBusy();
			return $this->getFreeThreadID();		
		}
		$query->free_result();
	}
	function campaign_thread($c_file, $pid){	 
		send_campaign_batch($c_file, $pid);
		//  Remove emails from queue table and insert in to email track table
		$this->move_email_queue_to_track();						
	}

	/**
	*	'send_autoresponder' controller function to send scheduled autoresponder emails.
	*/
	function send_autoresponder(){
		// set execution time
		set_time_limit(0);
		// If stopped by admin then dont send any campaign
		if( trim($this->confg_arr['continue_autoresponder_send']) !="1"){
			exit;
		}	
		 
		//  Load the user model which interact with database
		
		$cronjob_array=$this->Cronjob_Model->get_autoresponder_cronjob_data(array('recs.is_deleted'=>0,'rec.autoresponder_scheduled_id !='=>0,'recs.autoresponder_scheduled_status'=>1,'set_sheduled'=>0,'rec.campaign_status'=>1,'rec.is_deleted'=>0,'rec.is_status'=>0,'rec.is_verified'=>1));

		if(count($cronjob_array)>0){
			//  Get do not mail list 			
			$do_not_mail_list_arr=explode(",",$this->confg_arr['do_not_mail_list']);
			foreach($cronjob_array as $cronjobs){
				$subscriber_arr=array();
				$subscriptions[]=$cronjobs['subscription_ids'];
				
				if("-".$cronjobs['campaign_created_by']==$cronjobs['subscription_ids']){
					$subscriber_count=$this->Subscriber_Model->get_subscriber_count(array('subscriber_status'=>1,'res.is_deleted'=>0,'subscrber_bounce'=>0,'is_signup'=>1,'subscriber_created_by'=>$cronjobs['campaign_created_by']));
					$subscriber_array=$this->Subscriber_Model->get_subscriber_data(array('subscriber_status'=>1,'res.is_deleted'=>0,'subscrber_bounce'=>0,'is_signup'=>1,'subscriber_created_by'=>$cronjobs['campaign_created_by']),$subscriber_count);
				}else{
					// Get Subscibers list (where is_signup=1)	
					$subscriber_count=$this->Subscriber_Model->get_subscription_subscriber_count(array('subscriber_status'=>1,'res.is_deleted'=>0,'subscrber_bounce'=>0,'is_signup'=>1,'subscriber_created_by'=>$cronjobs['campaign_created_by'],'ress.subscription_id'=>$cronjobs['subscription_ids']));	
					$subscriber_array=$this->Subscriber_Model->get_subscription_subscriber_data(array('subscriber_status'=>1,'res.is_deleted'=>0,'subscrber_bounce'=>0,'is_signup'=>1,'subscriber_created_by'=>$cronjobs['campaign_created_by'],'ress.subscription_id'=>$cronjobs['subscription_ids']),$subscriber_count);	
				}
				$user=$this->UserModel->get_user_data(array('member_id'=>$cronjobs['campaign_created_by']));
				$vmta = $user[0]['vmta'];								
				$campaign_subject = $cronjobs['email_subject'];									
				$sender_name =  ($cronjobs['sender_name'] !='')? $cronjobs['sender_name']: $user[0]['company'];				 
				$sender = ($cronjobs['sender_email'] != '')? $cronjobs['sender_email'] :  $user[0]['email_address'];
				
				$campaign_type= ('5' == trim($cronjobs['campaign_template_option']))?'text':'html';				
				
				//if($cronjobs['emailtrack_img_link']==""){
					//$autoresponder_arr=$this->Campaign_Autoresponder_Model->save_campaign_view_detail($cronjobs['campaign_id'],$user[0]['language'],true);
					//$cronjobs['emailtrack_img_link']=$autoresponder_arr['emailtrack_img'];
					//$cronjobs['mail_view_link']=$autoresponder_arr['mail_view_link'];
					//$cronjobs['unsubscribe_link']=$autoresponder_arr['unsubscribe_link'];
					//$cronjobs['forward_link']=$autoresponder_arr['forward_link'];
					//$cronjobs['footer_link']=$autoresponder_arr['footer_link'];
					//$cronjobs['footer_html']=$autoresponder_arr['footer_html'];
					if(($cronjobs['campaign_template_option']!=3)&&($cronjobs['campaign_template_option']!=5)){
						$page_html=html_entity_decode($cronjobs['campaign_content'], ENT_QUOTES, "utf-8" );
					}else{
						$page_html=$cronjobs['campaign_content'];
					}
					$cronjobs['campaign_after_encode_url']=$this->Campaign_Autoresponder_Model->encode_url($cronjobs['campaign_id'],$page_html,true);
				
				
				//}
				// add links:emailtrack_img,footer,unsubscribe,forward links with campaign
				// $camapign_message=$this->Campaign_Autoresponder_Model->attach_campaign_link($cronjobs,$user[0]['rc_logo']);	
				 $camapign_message=$this->Campaign_Autoresponder_Model->attach_campaign_link($cronjobs,$user, true);	
				
				 
				// collect text message
				$campaign_footer_text_only = $this->Campaign_Autoresponder_Model->campaign_footer_text_only($user, $cronjobs['campaign_id'], true, true);
				$campaign_text_message=$cronjobs['campaign_text_content']."\n".$campaign_footer_text_only;				
				$campaign_text_message=str_replace('This is a pre-header. Use this area to write a short preview of your email content.','',$campaign_text_message);
				$camapign_message=$camapign_message;
				
			 
				$email_personalization=true;
				foreach($subscriber_array as $subscriber){
					if(!($this->do_not_mail($subscriber['subscriber_email_address'],$do_not_mail_list_arr, $user[0]['member_dnm']))){					 
				
						//check that subscribers have receive the notification or not
						$subscriber_schedule=$this->Cronjob_Model->get_autoresponder_signup_data(array('autoresponder_scheduled_id'=>$cronjobs['autoresponder_scheduled_id'],'subscriber_email'=>$subscriber['subscriber_email_address']));

						if(count($subscriber_schedule)<=0){						
							$subscrber_date_arr=explode(" ",$subscriber['subscriber_date_added']);
							$date_arr=explode("-",$subscrber_date_arr[0]);
						 
							$date_diff =  $this->dateDiff("-",date("m-d-Y"),$date_arr[1]."-".$date_arr[2]."-".$date_arr[0]);	
 					
							// if(($date_diff == $cronjobs['autoresponder_scheduled_interval'])||($cronjobs['autoresponder_scheduled_interval']==0)){
							if($date_diff == $cronjobs['autoresponder_scheduled_interval']){							 

								$subscriber['schedule_id']=$cronjobs['autoresponder_scheduled_id'];
								$message=$camapign_message;
								$text_message=$campaign_text_message;
								$subject=$campaign_subject;
								// Replace campaign content according to subscriber detail								
								$this->Campaign_Autoresponder_Model->getPersonalization($message,$text_message,$subject,$subscriber,true,$cronjobs['campaign_id'],$vmta, $email_personalization);
								
							 
						 
								//  Send Autoresponder and if successfully sent update contact for this in red_autoresponder_signup
							
							 	if(send_autoresponder_batch($message,$text_message,$subject,$sender_name,$sender,$cronjobs['campaign_id'],$subscriber, $campaign_type, $vmta)){
									$this->Cronjob_Model->add_autoresponder_signup_subscriber(array('autoresponder_scheduled_id'=>$cronjobs['autoresponder_scheduled_id'],'email_track_subscriber_id'=>$subscriber['subscriber_id'],'subscriber_created_by'=>$cronjobs['campaign_created_by'],'subscriber_email'=>$subscriber['subscriber_email_address']));
								}
								
							}
						}
					}//  Filter DoNotMail list
				}// foreach subscriber
			}//  Foreach cronjob
		}
	}
	function dateDiff($dformat, $endDate, $beginDate){
		$date_parts1=explode($dformat, $beginDate);
		$date_parts2=explode($dformat, $endDate);
		$start_date=gregoriantojd($date_parts1[0], $date_parts1[1], $date_parts1[2]);
		$end_date=gregoriantojd($date_parts2[0], $date_parts2[1], $date_parts2[2]);
		return  $end_date - $start_date;
	}
	
	/**
		Function to send campaign  notification email to member
	**/
	function campaign_send_notification($user_id=0,$campaign_id=0,$campaign_name="",$total_contact_selected=0){
		
		// Fetch user data from database
		$user_data_array=$this->UserModel->get_user_data(array('member_id'=>$user_id));
		
		if($user_data_array[0]['first_name']){
			$user_name=$user_data_array[0]['first_name'];
		}else{
			$user_name=$user_data_array[0]['member_username'];
		}
		$campaign_view_link= CAMPAIGN_DOMAIN.'c/'.$this->is_authorized->encryptor('encrypt',$campaign_id);
		$campaign_stat_link=site_url('stats/display/'.$campaign_id);
		$user_info=array($user_name,$campaign_name,$total_contact_selected,$campaign_view_link,$campaign_stat_link);
		
		create_transactional_notification("campaign_send_notification",$user_info,$user_data_array[0]['email_address']);
	}
	/**
	*	Function check_user_contacts to check for the user who has scheduled that what is his package's max. contact at present and compare it with the count(contacts) of that 
	*	campaign
	*/
	function check_user_contacts($total_contact_selected=0,$user_id=0){
		/**
		*	Get Maximum Contacts according to selected user package id
		*/		
		
		$package_max_contacts = $this->UserModel->get_user_plan_status($user_id);
	
		// echo "<br/>total_contact_selected=".$total_contact_selected;
		if($total_contact_selected > $package_max_contacts){
			return false;
		}else{
			return true;
		}
	}
	/**
	*	function campaign_not_scheduled_notification to send a notification mail to user and admin for  Upgradion of package required to send a campaign
	*/
	function campaign_not_scheduled_notification($user_id=0,$campaign_id=0,$campaign_name="",$total_contact_selected=0){
		 
		// Get Maximum Contacts according to selected user package id
		
		$user_packages_array=$this->UserModel->get_user_packages(array('member_id'=>$user_id,'is_deleted'=>0));		
		$package_array=$this->UserModel->get_packages_data(array('package_id'=>$user_packages_array[0]['package_id']));
		$package_max_contacts=$package_array[0]['package_max_contacts'];
		
		//  Fetch user data from database
		$user_data_array=$this->UserModel->get_user_data(array('member_id'=>$user_id));
		if($user_data_array[0]['first_name']){
			$user_name=$user_data_array[0]['first_name'];
		}else{
			$user_name=$user_data_array[0]['member_username'];
		}
		$campaign_view_link= CAMPAIGN_DOMAIN.'c/'.$campaign_id; 
		$user_info=array($user_name,$campaign_name,$total_contact_selected,$package_max_contacts,$campaign_view_link);
			
		// create_transactional_notification("campaign_suspended_notification",$user_info,$user_data_array[0]['email_address']);
		create_transactional_notification("campaign_suspended_notification",$user_info,'pravinjha@gmail.com');
		// sned notification to admin
		$user_info=array($user_data_array[0]['member_username'],$campaign_name);
		create_notification("campaign_not_scheduled_notification",$user_info);
	}
	 
	/**
	*  Function is_contact_active checks for status and ignore status
	*  If this function returns false, means this user is to be ignored and no campaign will be sent.
	*/
	function is_contact_active($cid=0){ 
		$rsContactStatus = $this->db->query("select subscriber_status,`ignore` from red_email_subscribers where subscriber_id='$cid' and is_deleted=0");
		$retVal = false;
		if($rsContactStatus->num_rows() > 0){
			foreach($rsContactStatus->result_array() as $contact_row){
				if($contact_row['subscriber_status']==1 and $contact_row['ignore'] == 0)
					$retVal = true;			
			}
		}
		return $retVal;
	}
	/**
	*	Function do_not_mail to remove mail ids from email list
	*  If this function returns true, means this user is to be ignored and no campaign will be sent.
	*/
	function do_not_mail($email_id="", $do_not_mail_arr=array(),$memberDNM){
		$arrMemberDNM = array();
		if(!is_null($memberDNM) and $memberDNM !='' ){
			$arrMemberDNM = @explode(',',$memberDNM);
		}
		if(count($arrMemberDNM) > 0)
		$do_not_mail_arr = array_merge($do_not_mail_arr, $arrMemberDNM);
	 
		foreach ($do_not_mail_arr as $value) {
			if(trim($value) != '' and trim($email_id) !=''){
				if (stristr(trim($email_id), trim($value)) !== FALSE){
					return true;
					exit;
				}
			}
		}
		 
		return false;
	}
	/**
	*	Function is_global_dnm to remove mail ids from email list
	*  If this function returns true, means this user is to be ignored and no campaign will be sent.
	*/
	function is_global_dnm($arr_contact, $arr_campaign){
		$email_id =  $arr_contact['subscriber_email_address'];
		$sid =  $arr_contact['subscriber_id'];
		$cid =  $arr_campaign['campaign_id'];
		$user_id =  $arr_campaign['campaign_created_by'];
		if($user_id > 3266){
			// Check in Global DNM list. If found, don't send campaign and also mark it as "hardbounced"
			$rsIfDNM = $this->db->query("select `email_address` from `red_global_dnm` where `email_address` ='$email_id' and `dnm_type`=1");
			$isDNM = $rsIfDNM->num_rows();
			$rsIfDNM->free_result();
			if( $isDNM > 0){
				$utc_str = gmdate("Y-m-d H:i:s", time());	
				// Update subscriber
				$this->Subscriber_Model->update_subscriber(array('subscrber_bounce'=>1,'subscriber_status'=>3,'status_change_date'=>$utc_str),array('subscriber_id'=>$sid));					
				//update  email report	
				$this->db->query("update `red_email_queue` set `email_track_bounce`=1,`bounce_date`='$utc_str' where `campaign_id`='$cid' and `subscriber_id`='$sid'");
				$this->db->query("update `red_email_campaigns_scheduled` set `email_track_bounce` = `email_track_bounce`+1 where campaign_id='$cid'");					
				return true;
				exit;
			}
		}
		return false;
	}
	/**
	* If this function returns true, means this user is to be ignored and no campaign will be sent.
	*/
	function is_soft_bounced($arr_contact){
		$is_bounced = false;
		 
		$last_bounced_date = '';
		$ignore_softbounced_for_x_days = 0 - $this->confg_arr['ignore_softbounced_for_x_days'];  
		$sid = $arr_contact['subscriber_id'];					
		$rsIsBounced = $this->db->query("select `last_bounced_date` from red_email_subscribers where subscriber_id='$sid'");		 
		if($rsIsBounced->num_rows() > 0){						
			$last_bounced_date = $rsIsBounced->row()->last_bounced_date;
		}
		$rsIsBounced->free_result();	
		if( !is_null($last_bounced_date) and (strtotime($last_bounced_date) > strtotime($ignore_softbounced_for_x_days.' days',time()) )) 
		$is_bounced = true;
	
	return $is_bounced;
	}
	/**
	* If this function returns true, means this user is to be ignored and no campaign will be sent.
	*/
	function ignore_unresponsive($arr_contact, $arrIgnore=array(),$user =array(),$campaign=array()){		
		$apply_unresponsive_filter = $user[0]['apply_unresponsive_filter'];
		$unresponsive_release_count = $user[0]['unresponsive_release_count'];
		// Exit if filter is OFF for the member
		if($apply_unresponsive_filter == 0){
			return false; // Since this member is not applied for unresponsive-filter, there will be no check and all campaign will be sent.
			exit;
		}
		if($campaign['campaign_contacts'] <= 100){
			return false; // Release all emails if campaign is small.
			exit;
		}
		 
		
		
		
		$email_id = trim($arr_contact['subscriber_email_address']);
		$sid = $arr_contact['subscriber_id'];
		// For users whose FILTER is ON and RELEASE-COUNT = 0, unresponsives after first sent will not get any campaign.
		if($unresponsive_release_count == 0){
			 // Sent gets updated above these checks to send or not. So, actually when mail-sent=0, then only mail-sent becomes = 1. But still mail is not sent yet.
			$rsEngaged = $this->db->query("select (`read` + `clicked` + `forwarded`) as engaged from red_email_subscribers where subscriber_id='$sid' and sent > 1");
			$num =  $rsEngaged->num_rows();		 
			
			$intEngaged = 1;
			if($num > 0){						
				$intEngaged = $rsEngaged->row()->engaged;				
			}else{				
				return false; // They are not unresponsives,because this contact has SENT-COUNT = 0 or is new-contact.
				exit;
			}
			$rsEngaged->free_result();
			if($intEngaged > 0 ){	
				return false; // This contact is Responsives
				exit;
			}else{
				return true; // This contact is Un-Responsive and will not get campaign
				exit;
			}		
		}else{ // Where [RELEASE-COUNT] > 0
			// For users whose FILTER is ON and RELEASE-COUNT > 0, [RELEASE-COUNT] number of unresponsive-contacts from listed domains will get campaign. 
			//  And unresponsive-contacts will be defined after 9 sent.
			$rsUnresponsiveWebmailCount = $this->db->query("select unresponsive_gmail,unresponsive_yahoo,unresponsive_hotmail,unresponsive_aol,unresponsive_others from red_email_campaigns where campaign_id='".$campaign[campaign_id]."'");
			$recordUnresponsiveCounter = $rsUnresponsiveWebmailCount->row();
			$rsUnresponsiveWebmailCount->free_result();
			foreach ($arrIgnore as $value) {		
				if(trim($value) != '' and $email_id !='' and stristr($email_id, trim($value)) !== FALSE){	
					$sid = $arr_contact['subscriber_id'];					
					$rsEngaged = $this->db->query("select (`read` + `clicked` + `forwarded`) as engaged from red_email_subscribers where subscriber_id='$sid' and sent > 9");
					$intEngaged = 1;
					if($rsEngaged->num_rows() > 0){						
						$intEngaged = $rsEngaged->row()->engaged;
					}else{
						return false;
						exit;
					}
					$rsEngaged->free_result();				
					if($intEngaged <= 0 ){					
						if(strpos(strtolower($email_id), 'gmail') !== FALSE and $unresponsive_release_count > $recordUnresponsiveCounter->unresponsive_gmail){						
								$this->db->query("update red_email_campaigns set unresponsive_gmail = unresponsive_gmail + 1 where campaign_id='".$campaign[campaign_id]."'");
								return false;
								exit;						
						}elseif(strpos(strtolower($email_id), 'hotmail') !== FALSE and $unresponsive_release_count > $recordUnresponsiveCounter->unresponsive_hotmail){						
								$this->db->query("update red_email_campaigns set unresponsive_hotmail = unresponsive_hotmail + 1 where campaign_id='".$campaign[campaign_id]."'");
								return false;
								exit;						
						}elseif(strpos(strtolower($email_id), 'aol') !== FALSE and $unresponsive_release_count > $recordUnresponsiveCounter->unresponsive_aol){
								$this->db->query("update red_email_campaigns set unresponsive_aol = unresponsive_aol + 1 where campaign_id='".$campaign[campaign_id]."'");
								return false;
								exit;
						}elseif(strpos(strtolower($email_id), 'yahoo') !== FALSE and $unresponsive_release_count > $recordUnresponsiveCounter->unresponsive_yahoo){
						//}elseif(strpos(strtolower($email_id), 'yahoo') !== FALSE ){
								$this->db->query("update red_email_campaigns set unresponsive_yahoo = unresponsive_yahoo + 1 where campaign_id='".$campaign[campaign_id]."'");
								return true;
								exit;
						}elseif(strpos(strtolower($email_id), 'gmail') === FALSE and strpos(strtolower($email_id), 'hotmail') === FALSE and strpos(strtolower($email_id), 'yahoo') === FALSE and strpos(strtolower($email_id), 'aol') === FALSE and $unresponsive_release_count > $recordUnresponsiveCounter->unresponsive_others){ 
							$this->db->query("update red_email_campaigns set unresponsive_others = unresponsive_others + 1 where campaign_id='".$campaign[campaign_id]."'");
							return false;
							exit;							
						}																
						return true;
						exit;					
					}
				}
			}
		}
		return false;	
	}
	
	/**
	* If this function returns true, means this contact will be ignored and campaign will be not sent.
	* IF returns false, mail will be sent Else, mail will be not sent
	*/
	function ignore_unresponsive_global_domain($arr_contact, $ignore_domain='yahoo.com',$campaign=array()){				
			$email_id = trim($arr_contact['subscriber_email_address']);			
			if( $email_id !='' and stristr($email_id, $ignore_domain) !== FALSE){	
				// Get Unresponsive-count for domain
				$rsUnresponsiveWebmailCount = $this->db->query("select unresponsive_gmail,unresponsive_yahoo,unresponsive_hotmail,unresponsive_aol,unresponsive_others from red_email_campaigns where campaign_id='".$campaign[campaign_id]."'");
				$recordUnresponsiveCounter = $rsUnresponsiveWebmailCount->row();
				$rsUnresponsiveWebmailCount->free_result(); 
				// Check contact is engaged or not
				$sid = $arr_contact['subscriber_id'];					
				$rsEngaged = $this->db->query("select (`read` + `clicked` + `forwarded`) as engaged from red_email_subscribers where subscriber_id='$sid' and sent > 0");
				$intEngaged = 1;
				if($rsEngaged->num_rows() > 0){						
					$intEngaged = $rsEngaged->row()->engaged;
				}
				$rsEngaged->free_result();	
				// IF contact is not engaged(contact is unresponsive), check unresponsive-allowed-count. 
				// If unresponsive-allowed-count is less than XXX, increment it and return false. So that mail can be sent.			
				if($intEngaged <= 0 ){
					// IF contact is AOL and unresponsive-allowed-count is less than 200, increment unresponsive-allowed-count and send mail.
					if(strpos(strtolower($email_id), 'aol') !== FALSE and 400 > $recordUnresponsiveCounter->unresponsive_aol){
							$this->db->query("update red_email_campaigns set unresponsive_aol = unresponsive_aol + 1 where campaign_id='".$campaign[campaign_id]."'");
							return false;
							exit;
					}elseif(strpos(strtolower($email_id), 'yahoo') !== FALSE and 500 > $recordUnresponsiveCounter->unresponsive_yahoo){
							$this->db->query("update red_email_campaigns set unresponsive_yahoo = unresponsive_yahoo + 1 where campaign_id='".$campaign[campaign_id]."'");
							return true;
							exit;
					}
					return true; // Stop campaign if not engaged yahoo
					exit;					
				}
			}
		
		return false;	// If engaged yahoo or email-other than yahoo, do not IGNORE IT.
	}
	function addToQueue($campaign_id, $mid, $list_ids, $c_status){
		$campaign_current_status = $this->db->query("Select campaign_status from red_email_campaigns where campaign_id='$campaign_id'")->row()->campaign_status;
		
		if($campaign_current_status == 'draft'){		
		$this->Campaign_Model->update_campaign(array('campaign_status'=>'queueing'),array('campaign_id'=>$campaign_id));			
		$arrContacts=$this->Subscriber_Model->get_distinct_contacts(array('subscriber_status'=>1,'res.is_deleted'=>0,'res.subscriber_created_by'=>$mid),$mid,$list_ids);
		
		if(count($arrContacts)>0){
			foreach($arrContacts as $subscriber){
					
				// Insert mail description in email queue table
				$emailtrack_insert_id=$this->Emailreport_Model->replace_emailqueue(array('campaign_id'=>$campaign_id,'user_id'=>$mid,'subscriber_id'=>$subscriber['subscriber_id'],'subscriber_email_address'=>$subscriber['subscriber_email_address']));
				//$emailtrack_insert_id=$this->Emailreport_Model->replace_emailqueue(array('campaign_id'=>$campaign_id,'user_id'=>$mid,'subscriber_id'=>$subscriber['subscriber_id'],'subscriber_email_address'=>$subscriber['subscriber_email_address'],'subscriber_first_name'=>$subscriber['subscriber_first_name'],'subscriber_last_name'=>$subscriber['subscriber_last_name'],'subscriber_state'=>$subscriber['subscriber_state'],'subscriber_zip_code'=>$subscriber['subscriber_zip_code'],'subscriber_country'=>$subscriber['subscriber_country'],'subscriber_company'=>$subscriber['subscriber_company'],'subscriber_city'=>$subscriber['subscriber_city'],'subscriber_dob'=>$subscriber['subscriber_dob'],'subscriber_phone'=>$subscriber['subscriber_phone'],'subscriber_address'=>$subscriber['subscriber_address'],'subscriber_extra_fields'=>$subscriber['subscriber_extra_fields']));
			}
		}		
		$this->Campaign_Model->update_campaign(array('campaign_status'=>$c_status),array('campaign_id'=>$campaign_id));			
		}
		exit;
	}
	
	function addToQueueCron(){
		set_time_limit(0); 				 
		
		//Check cronjob status: completed or working		
		if(trim($this->confg_arr['queueing_cron']) == '1'){
			exit;
		}else{
			// update cronjob status to completed
			$this->ConfigurationModel->update_site_configuration(array('config_value'=>'1'),array('config_name'=>'queueing_cron'));
			$this->ConfigurationModel->update_site_configuration(array('config_value'=>date("Y-m-d H:i:s", time())),array('config_name'=>'queueing_start_date'));
					
			$rsQueueingCampaigns = $this->db->query("Select campaign_id, campaign_created_by, subscription_list, sent_counter, campaign_contacts, tobe_campaign_status from red_email_campaigns where campaign_status='queueing' and is_deleted=0 limit 5");
			if($rsQueueingCampaigns->num_rows() > 0){
				foreach($rsQueueingCampaigns->result_array() as $rowQueingCampaign){
					$cid = $rowQueingCampaign['campaign_id'];
					$mid = $rowQueingCampaign['campaign_created_by'];
					$subscription_list = $rowQueingCampaign['subscription_list'];
					$subscription_list = (is_null($subscription_list) or trim($subscription_list) =='') ? (0 - $mid) : $subscription_list;
					$sent_counter = $rowQueingCampaign['sent_counter'];
					$campaign_contacts = $rowQueingCampaign['campaign_contacts'];
					$tobe_campaign_status = $rowQueingCampaign['tobe_campaign_status'];
					
					$btachSize = QUEUEING_BATCH_SIZE;
					if($sent_counter < $campaign_contacts){
						$isAllContact = false;
						$arrSubscription_list = explode(',',$subscription_list);
						
						foreach($arrSubscription_list as $l){
							if($l < 0)$isAllContact = true;						
						}
						if(!$isAllContact){
							//$rsContactID = $this->db->query("select distinct subscriber_id from red_email_subscription_subscriber where subscription_id in ($subscription_list)  limit $sent_counter,$btachSize ");
							$rsContactID = $this->db->query("select distinct ss.subscriber_id from red_email_subscription_subscriber ss inner join red_email_subscribers s on ss.subscriber_id=s.subscriber_id where ss.subscription_id in ($subscription_list) and s.subscriber_status=1 and s.is_deleted=0 order by ss.subscriber_id  limit $sent_counter,$btachSize ");
						}else{
							$rsContactID = $this->db->query("select distinct subscriber_id from red_email_subscribers where subscriber_created_by='$mid' and subscriber_status=1 and is_deleted=0 order by subscriber_id limit $sent_counter,$btachSize ");
						}
						
						if($rsContactID->num_rows() > 0){
							foreach($rsContactID->result_array() as $rowContact){
								$thisContactId = $rowContact['subscriber_id'];
								
								$rsContactDetail = $this->db->query("select subscriber_id, subscriber_email_address, `sent` from red_email_subscribers where subscriber_id='$thisContactId' and subscriber_created_by='$mid' and subscriber_status=1 and is_deleted=0");
								if($rsContactDetail->num_rows() > 0){
									foreach($rsContactDetail->result_array() as $rowContactDetail){
										$is_previously_sent = ($rowContactDetail['sent'] > 0)? 1 : 0;
										// Insert mail description in email queue table
										$emailtrack_insert_id=$this->Emailreport_Model->replace_emailqueue(array('campaign_id'=>$cid,'user_id'=>$mid,'subscriber_id'=>$thisContactId,'subscriber_email_address'=>$rowContactDetail['subscriber_email_address'],'is_active'=>$is_previously_sent)); 								
									}
								}
								$rsContactDetail->free_result();							
							}
							$this->db->query("update red_email_campaigns set sent_counter = sent_counter + $btachSize where campaign_id='$cid'");							
						}
						$rsContactID->free_result(); 
				 
					}else{
						$this->Campaign_Model->update_campaign(array('campaign_status'=>$tobe_campaign_status),array('campaign_id'=>$cid));	
						
						$arrCampaign = $this->Campaign_Model->get_campaign_data(array('campaign_id'=>$cid));
						$emlHTML = $arrCampaign[0]['campaign_content'];
						$emlText = $arrCampaign[0]['campaign_text_content'];
						$emlSubject = $arrCampaign[0]['email_subject'];
						
						$eml = $this->getheaders($emlSubject, $emlText) . $emlHTML;
						 
						$arrSpam = $this->filter($eml,"long");
						
						$sreport = (isset($arrSpam['report']))?$arrSpam['report'] : '';
						$sscore =  (isset($arrSpam['score']))?$arrSpam['score'] : 0;  
						$this->db->query("update red_email_campaigns set spamscore='$sscore',spamreport='$sreport' where campaign_id=$cid");
					}
				}// End of for loop	
			}// End of IF to check numrow
			$rsQueueingCampaigns->free_result(); 
			// update cronjob status to completed
			$this->ConfigurationModel->update_site_configuration(array('config_value'=>'0'),array('config_name'=>'queueing_cron'));
			$this->ConfigurationModel->update_site_configuration(array('config_value'=>date("Y-m-d H:i:s", time())),array('config_name'=>'queueing_start_date'));				
		}
		exit;
	}	

	function resegment_new(){
		$rsGetSegment = $this->db->query("select campaign_id, segment_size,last_released_on, DATE_ADD(ifnull(last_released_on,added_on), INTERVAL (segment_interval+ interval_variance) MINUTE)now_release_at  from `red_ongoing_segmentation`");
		if($rsGetSegment->num_rows() > 0){
			foreach($rsGetSegment->result_array() as $recSegment){
				$campaign_id = $recSegment['campaign_id'];
				$segment_size = $recSegment['segment_size'];				
				$now_release_at = $recSegment['now_release_at'];
				$last_released_on = $recSegment['last_released_on'];
				$timenow = gmdate("Y-m-d H:i:s", time());
				if(is_null($last_released_on) or $now_release_at <= $timenow){
					$rsCountQueue = $this->db->query("Select count(subscriber_id) queue from `red_email_queue` where `campaign_id`='$campaign_id' and `email_sent`=0");				
					$in_queue_subscribers = $rsCountQueue->row()->queue;
					$rsCountQueue->free_result();
					if($in_queue_subscribers > 0){
						$this->Campaign_Model->update_campaign(array('is_segmentation'=>'1','number_of_contacts'=>$segment_size,'campaign_status'=>'archived'), array('campaign_id'=>$campaign_id));	
						$this->db->query("update red_ongoing_segmentation set last_released_on= '$timenow', interval_variance = FLOOR((RAND() * (11))) where campaign_id=$campaign_id");					
						// SELECT FLOOR((RAND() * (max-min+1))+min)
					}else{
						// Remove ONGOING-SEGMENTATION RECORD
						$this->db->query("delete from `red_ongoing_segmentation` where `campaign_id` = '$campaign_id'");
					}
				}
			}
		}
		$rsGetSegment->free_result();
	}
	function resegment(){
		$rsGetSegment = $this->db->query("select * from `red_ongoing_segmentation`");
		if($rsGetSegment->num_rows() > 0){
			foreach($rsGetSegment->result_array() as $recSegment){
				$campaign_id = $recSegment['campaign_id'];
				$segment_size = $recSegment['segment_size'];
				$rsCountQueue = $this->db->query("Select count(subscriber_id) queue from `red_email_queue` where `campaign_id`='$campaign_id' and `email_sent`=0");
				$in_queue_subscribers = $rsCountQueue->row()->queue;
				$rsCountQueue->free_result();
				if($in_queue_subscribers > 0){
					$this->Campaign_Model->update_campaign(array('is_segmentation'=>'1','number_of_contacts'=>$segment_size,'campaign_status'=>'archived'),array('campaign_id'=>$campaign_id));	
				}else{
					// Remove ONGOING-SEGMENTATION RECORD
					$this->db->query("delete from `red_ongoing_segmentation` where `campaign_id` = '$campaign_id'");
				}
			}
		}
		$rsGetSegment->free_result();
	}
	
	function contact_analysis(){
		ini_set('max_execution_time', 0);
		ini_set('memory_limit', '-1');
		set_time_limit(0); 
		
		$site_configuration_array=$this->ConfigurationModel->get_site_configuration_data(array('config_name'=>'contact_analysis_cron_status'));
		$contact_analysis_cron_status=$site_configuration_array[0]['config_value'];
		if($contact_analysis_cron_status == 'working'){
			exit;
		}else{
			$this->ConfigurationModel->update_site_configuration(array('config_value'=>'working'),array('config_name'=>'contact_analysis_cron_status'));
			$utc_str = gmdate("Y-m-d H:i:s", time());
			$this->ConfigurationModel->update_site_configuration(array('config_value'=>$utc_str),array('config_name'=>'contact_analysis_cron_time'));
		}
		$rsMembersToAnalyse = $this->db->query("select member_id from `red_subscriber_analysis` where `reanalyse_it` = 1");
		foreach($rsMembersToAnalyse->result_array() as $recMembersToAnalyse){
			$mid = $recMembersToAnalyse['member_id'];
			$webmails = array('gmail','yahoo','hotmail','msn','aol','all'); 
			
			$rsCheckUnanalysedContacts = $this->db->query("select subscriber_id,subscriber_email_address from red_email_subscribers where subscriber_created_by='$mid' and subscriber_status=1 and is_deleted=0 and is_analysed=0");	
			if($rsCheckUnanalysedContacts->num_rows() > 0){
			foreach($webmails as $webdomain){
				if($webdomain != 'all')$webdomainClause = " and subscriber_email_domain like'".$webdomain."%' "; else $webdomainClause ='';
				$rsActiveContacts = $this->db->query("select count(subscriber_id) as total_contacts from red_email_subscribers where subscriber_created_by='$mid' and subscriber_status=1 and is_deleted=0 $webdomainClause");	
				$total_contacts = $rsActiveContacts->row()->total_contacts;
				$this->db->query("update red_subscriber_analysis set `{$webdomain}_total`= $total_contacts where `member_id`='$mid'");				
				
				$rsActiveContacts->free_result();	
				
				$rsEachContacts = $this->db->query("select subscriber_id,subscriber_email_address from red_email_subscribers where subscriber_created_by='$mid' and subscriber_status=1 and is_deleted=0 and is_analysed=0 $webdomainClause limit 5000");	
				foreach($rsEachContacts->result_array() as $recContacts){		
					$sidForContact = $recContacts['subscriber_id'];
					$emlForContact = $recContacts['subscriber_email_address'];
					$update_fields = "`member_id`='$mid' ";
					// spam kind of contact
					$arrDNM = explode(',', $this->confg_arr['do_not_mail_list']);  
					if($this->do_not_mail($emlForContact,$arrDNM,'')){
						//$this->db->query("update red_subscriber_analysis set `{$webdomain}_spam`= `{$webdomain}_spam` + 1 where `member_id`='$mid'");
						$update_fields .= ", `{$webdomain}_spam`= `{$webdomain}_spam` + 1 ";
					}		
					// repeated or virgin
					$rsVirginContacts = $this->db->query("select subscriber_id from red_email_subscribers where subscriber_created_by !='$mid'  and subscriber_email_address='$emlForContact' limit 1");	
					if($rsVirginContacts->num_rows() == 0){						
						//$this->db->query("update red_subscriber_analysis set `{$webdomain}_new`= `{$webdomain}_new` + 1 where `member_id`='$mid'");
						$update_fields .= ", `{$webdomain}_new`= `{$webdomain}_new` + 1 ";
					}else{	
						// $this->db->query("update red_subscriber_analysis set `{$webdomain}_existing`= `{$webdomain}_existing` + 1 where `member_id`='$mid'");
						$update_fields .= ", `{$webdomain}_existing`= `{$webdomain}_existing` + 1 ";	
						
						$rsVirginContacts->free_result();
						// responsive or un-responsive
						$rsResponsiveContacts = $this->db->query("select subscriber_id from red_email_subscribers where subscriber_created_by !='$mid'  and subscriber_email_address='$emlForContact' and `read` > 0 limit 1");	
						if($rsResponsiveContacts->num_rows() > 0){
							//$this->db->query("update red_subscriber_analysis set `{$webdomain}_responsive`= `{$webdomain}_responsive` + 1 where `member_id`='$mid'");	
							$update_fields .= ", `{$webdomain}_responsive`= `{$webdomain}_responsive` + 1 ";								
						}else{
							//$this->db->query("update red_subscriber_analysis set `{$webdomain}_unresponsive`= `{$webdomain}_unresponsive` + 1 where `member_id`='$mid'");			
							$update_fields .= ", `{$webdomain}_unresponsive`= `{$webdomain}_unresponsive` + 1 ";	
						}	
						$rsResponsiveContacts->free_result();
						
						// unsubscribed
						$rsUnsubscribedContacts = $this->db->query("select subscriber_id from red_email_subscribers where subscriber_email_address='$emlForContact' and `subscriber_status` = 0 limit 1");	
						if($rsUnsubscribedContacts->num_rows() > 0){
							//$this->db->query("update red_subscriber_analysis set `{$webdomain}_unsubscribe`= `{$webdomain}_unsubscribe` + 1 where `member_id`='$mid'");	
							$update_fields .= ", `{$webdomain}_unsubscribe`= `{$webdomain}_unsubscribe` + 1 ";	
						}	
						$rsUnsubscribedContacts->free_result();
					}
					// bounced
					$rsBouncedContacts = $this->db->query("select email_address from red_global_dnm where email_address='$emlForContact' and dnm_type=1 ");	
					if($rsBouncedContacts->num_rows() > 0){
						//$this->db->query("update red_subscriber_analysis set `{$webdomain}_bounce`= `{$webdomain}_bounce` + 1 where `member_id`='$mid'");	
						$update_fields .= ", `{$webdomain}_bounce`= `{$webdomain}_bounce` + 1 ";	
					}	
					$rsBouncedContacts->free_result();
					// complaint
					$rsComplaintContacts = $this->db->query("select email_address from red_global_fbl where email_address='$emlForContact' ");	
					if($rsComplaintContacts->num_rows() > 0){
						//$this->db->query("update red_subscriber_analysis set `{$webdomain}_complaint`= `{$webdomain}_complaint` + 1 where `member_id`='$mid'");	
						$update_fields .= ", `{$webdomain}_complaint`= `{$webdomain}_complaint` + 1 ";	
					}	
					$rsComplaintContacts->free_result();							
					$this->db->query("update red_subscriber_analysis set $update_fields where `member_id`='$mid'");			
					$this->db->query("update red_email_subscribers set is_analysed=1 where `subscriber_id`='$sidForContact'");	
				}
				$rsEachContacts->free_result();	
			}	//webmailforloop		
			}else{
					$this->db->query("update red_subscriber_analysis set `analysis_date`=now(), `reanalyse_it`=0 where `member_id`='$mid'");	
					$strAnalysisTable = $this->UserModel->contact_analysis_html($mid);
					$membername = $this->db->query("select member_username from red_members where member_id='$mid'")->row()->member_username;
					$email_msg ="<p>Hello admin,</p><p>Contacts analysed for member_id :<b>$membername [$mid]</b></p>$strAnalysisTable<p>Regards,<br />BoldInbox Team</p>" ;				
					$this->load->helper('admin_notification');				
					$site_configuration_array=$this->ConfigurationModel->get_site_configuration_data(array('config_name'=>'admin_notification_email'));
					$admin_notification_email=$site_configuration_array[0]['config_value'];			 		
					admin_notification_send_email($admin_notification_email, SYSTEM_EMAIL_FROM,"BoldInbox", "Contacts analysed",$email_msg,$email_msg);	
			}
			$rsCheckUnanalysedContacts->free_result();	
		}
		$this->ConfigurationModel->update_site_configuration(array('config_value'=>'complete'),array('config_name'=>'contact_analysis_cron_status'));
		$utc_str = gmdate("Y-m-d H:i:s", time());
		$this->ConfigurationModel->update_site_configuration(array('config_value'=>$utc_str),array('config_name'=>'contact_analysis_cron_time'));			
	}

	function pauseIfLowOpen($cid, $mid){
	//  Check this member's campaign is pausable or not
		$rsIsPausable = $this->db->query("select is_pausable from red_members where member_id='$mid'");
		$is_pausable = $rsIsPausable->row()->is_pausable; 	
		$rsIsPausable->free_result();
	
		
		if($is_pausable){
			$rsTotalDelivered = $this->db->query("select email_track_delivered, email_track_read from red_email_campaigns_scheduled where campaign_id='$cid'");
			$totalDelivered = $rsTotalDelivered->row()->email_track_delivered;
			$totalOpened = $rsTotalDelivered->row()->email_track_read;
			$rsTotalDelivered->free_result();
		
			$rsTotalQueued = $this->db->query("select campaign_contacts from red_email_campaigns where campaign_id='$cid'");
			$totalQueued = $rsTotalQueued->row()->campaign_contacts;
			$rsTotalQueued->free_result();
			if( ($totalDelivered > (0.50 * $totalQueued)) and ($totalOpened < (0.10 * $totalDelivered)) ){
				$this->db->query("delete from `red_ongoing_segmentation` where `campaign_id` = '$cid'");
				$this->Campaign_Model->update_campaign(array('is_segmentation'=>'1','number_of_contacts'=>0,'campaign_status'=>'active'),array('campaign_id'=>$cid));
				// Send block RC-Alert	
				$message = "<p>Hello admin,</p><p>A campaign [$cid] is paused because of low open rate </p>
				<p>User is un-authenticated (if authentic)<br/>
				Auto-segmentation disabled (If enabled in past)<br/>
				His next campaign will be un-approvable. (To approve it Admin needs to modify member-settings.)
				</p>
				<p>Regards,<br />BoldInbox Team</p>";		
				$text_message= "A campaign [$cid] is paused because of low open rate:\n\nUser is un-authenticated (if authentic)\n
				Auto-segmentation disabled (If enabled in past)\n
				His next campaign will be un-approvable. (To approve it Admin needs to modify member-settings.)\n\n";	
												
				$to = $this->confg_arr['admin_notification_email'];
		
				admin_notification_send_email($to, SYSTEM_EMAIL_FROM,'BoldInbox', "Campaign [$cid] paused due to low open rate",$message,$text_message);				
				// Send block RC-Alert Ends		
				// 1. Un-authenticate member, 2. Stop is_automatic_segmentation & 3. mark his next-campaign Un-approvable
				$approval_notes = 'Unauthenticated after low open rate received';
				$unauthenticNotes = ", campaign_approval_notes = IFNULL(concat(replace(campaign_approval_notes, '$approval_notes','') , '$approval_notes' ), '$approval_notes')";					
				$this->db->query("update red_members set is_authentic=0, is_automatic_segmentation=0, stop_campaign_approval=1 $unauthenticNotes where `member_id`='$mid'");
				//  For this RC-alert will be not sent.	
			}	
		}	
	}

	/**
	* Function to get spamscore
	*/
	function getheaders($subject,$textBody){
		return "Delivered-To: pravinjha@gmail.com
Date: Sun, 24 Jan 2016 22:46:58 +0000
To: redsoftsolutions@yahoo.in
From: RedSoft Solutions <pravinjha@outlook.com>
Subject: {$subject}
MIME-Version: 1.0
Content-Type: multipart/alternative;
	boundary='b1_7099735c9469f56081952e912cbc68a5'
Message-ID: <0.0.11.3AD.1D156F922C680B4.0@rc74.rcmailsv.com>

--b1_7099735c9469f56081952e912cbc68a5
Content-Type: text/plain; charset=utf-8
Content-Transfer-Encoding: 8bit

{$textBody}


--b1_7099735c9469f56081952e912cbc68a5
Content-Type: text/html; charset=utf-8
Content-Transfer-Encoding: 8bit";
	}
	function filter($email, $options){
		if (empty($email) || empty($options)){
			return false;
		}

        if (!function_exists('curl_init')){
            return false;
        }
		$headers = array('Accept: application/json', 'Content-Type: application/json' );

		$encoded_data = json_encode(array('email'=>$email, 'options'=>$options));

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'http://spamcheck.postmarkapp.com/filter');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
		curl_setopt($ch, CURLOPT_POSTFIELDS, $encoded_data);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

		$return = curl_exec($ch);

		if (curl_error($ch) != '') {
			echo curl_error($ch);
			return false;
		}

		return json_decode($return, 1);
	}
		
	/**
	* function to send mails to 250ok seedlist
	*/
	function sendToSeedlist(){
		$todayDt = date('Ymd');
		$CIDHeader = '<!-- X-250ok-CID: RC'.$todayDt.'-->';
		
		$arrPools = array_keys(config_item('pool_vmta'));
		$arr_pipeline_domain = config_item('vmta_domain');
		$todayDt = date('d');
		$dtMod = $todayDt % 10;
		$vmta = $arrPools[$dtMod];
		$pipeline_domain = $arr_pipeline_domain[$vmta];
		$pipeline_domain =  (trim($pipeline_domain) != '')? $pipeline_domain : 'www.boldinbox.com';
		/*
		echo $pipeline_domain; echo "<br/>";
		echo $vmta; echo "<br/>";
		echo $dtMod; echo "<br/>";		
		print_r($arrPools);
		*/
//		foreach($arrPools as $vmta){
//		for($i=0; $i < 10; $i++){
//			$vmta = $arrPools[$i];
//			$pipeline_domain = $arr_pipeline_domain[$vmta];
//		}	
			$strSeedlist = "peejha@yahoo.com,pravinjha@gmail.com,a-279-1986@seed.250ok.net,b-114-1986@seed.250ok.net,y-878-1986@seed.250ok.net,z-208-1986@seed.250ok.net,paultester250@gmail.com,paultester150@yahoo.com,paultester250@hotmail.com,paultester250@aol.com,jebanglin250@hotmail.com,jebanglin250@yahoo.com,jebanglin250@aol.com,jebanglin250@gmail.com,sam250mitchell@hotmail.com,sam250mitchell@gmail.com,sam250mitchell@yahoo.com,sam250mitchell@aol.com,yellow25jacket0@hotmail.com,yellow25jacket0@aol.com,yellow25jacket0@yahoo.com,yellow25jacket0@gmail.com,aaronschuster250@yahoo.com,aaronschuster250@aol.com,aaronschuster250@hotmail.com,aaronschuster250@gmail.com,curtis250thompson@aol.com,wesley2jones50@aol.com,curtis250thompson@gmail.com,curtis250thompson@hotmail.com,wesley2jones50@yahoo.com,wesley2jones50@gmail.com,wesley2jones50@hotmail.com,curtis250thompson@yahoo.com,sarah250sherwood@aol.com,sarah250sherwood@gmail.com,sarah250sherwood@hotmail.com,sarah250sherwood@yahoo.com,karen250matthews@aol.com,karen250matthews@gmail.com,karen250matthews@hotmail.com,karen250matthews@yahoo.com,kendra2cox50@aol.com,kendra2cox50@gmail.com,kendra2cox50@hotmail.com,jessbussert250@aol.com,jessbussert250@gmail.com,jessbussert250@hotmail.com,jessbussert250@yahoo.com,erin2grider50@aol.com,erin2grider50@gmail.com,erin2grider50@hotmail.com,erin2grider50@yahoo.com,buster250finkle@aol.com,buster250finkle@gmail.com,buster250finkle@hotmail.com,buster250finkle@yahoo.com,tedturner250@aol.com,tedturner250@gmail.com,tedturner250@hotmail.com,tedturner250@yahoo.com,john2titor50@aol.com,john2titor50@gmail.com,john2titor50@hotmail.com,greg250jackson@aol.com,greg250jackson@gmail.com,greg250jackson@hotmail.com,greg250jackson@yahoo.com,matt2ehresman50@aol.com,matt2ehresman50@gmail.com,matt2ehresman50@hotmail.com,steven25bruce0@aol.com,steven25bruce0@gmail.com,steven25bruce0@hotmail.com,nancy2grace50@aol.com,nancy2grace50@gmail.com,nancy2grace50@hotmail.com,nancy2grace50@yahoo.com,brianjones250@aol.com,brianjones250@gmail.com,brianjones250@hotmail.com,brianjones250@yahoo.com,traviswallace@me.com,gregjones250@netzero.com,mattjones250@netzero.com,judyjones250@netzero.com,bobjones250@netzero.com,joejones250@netzero.com,nancyjones250@netzero.com,davejones250@netzero.com,merich@netzero.com,chrisjones250@netzero.com,aaronschuster250@me.com,brianjones250@me.com,busterfinkle250@me.com,paulsmall250@me.com,paulbig250@me.com,richardrogers1@earthlink.net,cindyrogers1@earthlink.net,rachelrogers1@earthlink.net,waynesnapp@earthlink.net,mariettasnapp@earthlink.net,johnsnapp1@earthlink.net,harpertitus1@earthlink.net,lizatitus1@earthlink.net,kristensmith250@comcast.net,frankhenthorn40@comcast.net,doug2bradford50@comcast.net,jennifer250smail@comcast.net,bradharrell67194@bol.com.br,jakeskillman16754@bol.com.br,pamwillits09354@bol.com.br,joewhite15874@bol.com.br,timyontz75431@bol.com.br,bradharrell67194@freenet.de,jakeskillman16754@freenet.de,pamwillits09354@freenet.de,joewhite15874@freenet.de,timyontz75431@freenet.de,bradharrell67194@gmx.de,jakeskillman167541@gmx.de,pamwillits093541@gmx.de,joewhite158741@gmx.de,davidmyers67432@gmx.de,brad.harrell@web.de,Jakeskillman16754@web.de,pamwillits09354@web.de,junelee6759817@yahoo.com.hk,skillman.jake@yahoo.com.hk,davidmyers432@yahoo.com.hk,craigparker913@yahoo.com.hk,PamWillits09354@yahoo.co.jp,JoeWhite15874@yahoo.co.jp,skillman_jake1234@yahoo.com.tw,pamwillits1234@yahoo.com.tw,joewhite929@yahoo.com.tw,yontztim@yahoo.com.tw,BradHarrell67194@mail.ru,JakeSkillman16754@mail.ru,PamWillits09354@mail.ru,JoeWhite15874@mail.ru,TimYontz75431@mail.ru,EricAshley45791@mail.ru,JuneLee67598@mail.ru,DavidMyers67432@mail.ru,MarieWagoner96425@mail.ru,CraigParker56973@mail.ru,BradHarrell67194@rambler.ru,JakeSkillman16754@rambler.ru,PamWillits09354@rambler.ru,JoeWhite15874@rambler.ru,TimYontz75431@rambler.ru,EricAshley45791@rambler.ru,JuneLee67598@rambler.ru,DavidMyers67432@rambler.ru,MarieWagoner96425@rambler.ru,CraigParker56973@rambler.ru,BradHarrell67194@yahoo.co.uk,JakeSkillman16754@yahoo.co.uk,PamWillits09354@yahoo.co.uk,JoeWhite15874@yahoo.co.uk,TimYontz75431@yahoo.co.uk,JuneLee67598@yahoo.co.uk,DavidMyers67432@yahoo.co.uk,CraigParker56973@yahoo.co.uk,BradHarrell67194@yandex.com,JakeSkillman16754@yandex.com,PamWillits09354@yandex.com,JoeWhite15874@yandex.com,TimYontz75431@yandex.com,EricAshley45791@yandex.com,JuneLee67598@yandex.com,DavidMyers67432@yandex.com,MarieWagoner964251@yandex.com,CraigParker569731@yandex.com,bradharrell67194@wp.pl,JakeSkillman16754@wp.pl,pamwillits09354@wp.pl,Joewhite15874@wp.pl,timyontz75431@wp.pl,bradharrell1234@yahoo.com.tw,ericashley12345678@yahoo.co.uk,TimYontz75431@web.de,JuneLee67598@web.de,JoeWhite15874@web.de,DavidMyers67432@web.de,MarieWagoner96425@web.de,CraigParker56973@web.de,Eric7654Ashley@web.de,pam.willits1234@indy.rr.com,bradharrell1234@indy.rr.com,Jakeskillman1234@indy.rr.com,ericashley1234@indy.rr.com,craigparker760@indy.rr.com,mariewagoner786@indy.rr.com,davidmyers786@indy.rr.com,khannick1278@indy.rr.com,junelee786@indy.rr.com,timyontz1278@indy.rr.com,wayne.rogers1954@comcast.net,crystal.rogers300@comcast.net,jasdolland84@comcast.net,bstricker79@comcast.net,christys85@comcast.net,p.neff76@comcast.net,jstevenson@godaddy.250ok.net,sbranson@godaddy.250ok.net,pgriff@godaddy.250ok.net,lsmith@godaddy.250ok.net,wsousley@godaddy.250ok.net,awhite@godaddy.250ok.net,mphillips@godaddy.250ok.net,cthompson@godaddy.250ok.net,fmyers@godaddy.250ok.net,rroberts@godaddy.250ok.net,tjefferson@rackspace.250ok.net,bjenkins@rackspace.250ok.net,rcole@rackspace.250ok.net,hstump@rackspace.250ok.net,lsandars@rackspace.250ok.net,osparks@rackspace.250ok.net,mwise@rackspace.250ok.net,alee@rackspace.250ok.net,gcross@rackspace.250ok.net,dstackhouse@rackspace.250ok.net,iolsen@gapps.250ok.net,pfrench@gapps.250ok.net,vconner@gapps.250ok.net,staitz@gapps.250ok.net,lstaples@gapps.250ok.net,djohns@gapps.250ok.net,sbaner@gapps.250ok.net,twittman@gapps.250ok.net,brudd@gapps.250ok.net,hpride@gapps.250ok.net,hjefferson@two50ok.com,vjenkins@two50ok.com,lcole@two50ok.com,lstump@two50ok.com,asandars@two50ok.com,xsparks@two50ok.com,swise@two50ok.com,klee@two50ok.com,ucross@two50ok.com,ostackhouse@two50ok.com,bob250@charter.net,eric250@charter.net,gene250@charter.net,hector250@charter.net,irene250@charter.net,larry250@charter.net,oscar250@charter.net,randy250@charter.net,harrymichaels@mail.com,johnmwright@mail.com,allie.miche@mail.com,c.cult@mail.com,mbilbad@mail.com,kissaneskimo@mail.com,pattywlong@post.com,rogerplong@mail.com,safewater2014@mail.com,walterblack2014@mail.com,aparkerd@rcn.com,cparkerd@rcn.com,fparkerd@rcn.com,jparkerd@rcn.com,kparkerd@rcn.com,mparkerd@rcn.com,pparkerd@rcn.com,sparkerd@rcn.com,vparkerd@rcn.com,yparkerd@rcn.com,bobjones@250okmail.com,davidjones@250okmail.com,fredjones@250okmail.com,haroldjones@250okmail.com,jeffjones@250okmail.com,larryjones@250okmail.com,nickjones@250okmail.com,pauljones@250okmail.com,tomjones@250okmail.com,williamjones@250okmail.com,AMcQueen@250ok.onmicrosoft.com,Cculberson@250ok.onmicrosoft.com,EJones@250ok.onmicrosoft.com,GStenn@250ok.onmicrosoft.com,INorth@250ok.onmicrosoft.com,KWalterson@250ok.onmicrosoft.com,PTranley@250ok.onmicrosoft.com,RKnox@250ok.onmicrosoft.com,TSmythe@250ok.onmicrosoft.com,WChandre@250ok.onmicrosoft.com,a5309@bell.net,d5309@bell.net,f5309@bell.net,h5309@bell.net,j5309@bell.net,m5309@bell.net,p5309@bell.net,q5309@bell.net,u5309@bell.net,w5309@bell.net,bobsmith@250ok-mail.com,davidsmith@250ok-mail.com,fredsmith@250ok-mail.com,haroldsmith@250ok-mail.com,jeffsmith@250ok-mail.com,larrysmith@250ok-mail.com,nicksmith@250ok-mail.com,paulsmith@250ok-mail.com,tomsmith@250ok-mail.com,williamsmith@250ok-mail.com,Clittle55@cox.net,eddysmart2@cox.net,gezz.wayne@cox.net,inzermcintosh@cox.net,k.white57@cox.net,marryseider@cox.net,rmax99@cox.net,vneet@cox.net,bobharris@250ok-mail.net,davidharris@250ok-mail.net,fredharris@250ok-mail.net,haroldharris@250ok-mail.net,jeffharris@250ok-mail.net,larryharris@250ok-mail.net,nickharris@250ok-mail.net,paulharris@250ok-mail.net,tomharris@250ok-mail.net,williamharris@250ok-mail.net,adangelis@verizon.net,cdangelis2@verizon.net,fdangelis@verizon.net,hdangelis@verizon.net,jdangelis@verizon.net,ldangelis@verizon.net,ndangelis@verizon.net,pdangelis@verizon.net,tdangelis@verizon.net,adupond01@aliceadsl.fr,bdupond@aliceadsl.fr,cdupond@aliceadsl.fr,ddupond@aliceadsl.fr,edupond@aliceadsl.fr,fmartin01@aliceadsl.fr,gmartin@aliceadsl.fr,hmartin@aliceadsl.fr,imartin@aliceadsl.fr,jmartin01@aliceadsl.fr,apidou@orange.fr,bpidou@orange.fr,cpidou@orange.fr,dpidou@orange.fr,aranson@orange.fr,branson01@orange.fr,cranson@orange.fr,dranson@orange.fr,afaroux01@laposte.net,bfaroux@laposte.net,cfaroux01@laposte.net,efaroux@laposte.net,aclement01@laposte.net,bclement01@laposte.net,cclement02@laposte.net,dclement01@laposte.net,eclement01@laposte.net,alast15@shaw.ca,jlast1@shaw.ca,johnnyj21@shaw.ca,jwhite33@shaw.ca,jtroy356@shaw.ca,debbieritter@shaw.ca,aingari9@shaw.ca,sonyat45@shaw.ca,jimercer@shaw.ca,apfenn11@shaw.ca,aripley@talktalk.net,bripley@talktalk.net,cripley@talktalk.net,dripley@talktalk.net,eripley01@talktalk.net,aasmith001@btinternet.com,bbsmith002@btinternet.com,ccsmith003@btinternet.com,ddsmith004@btinternet.com,eesmith005@btinternet.com,aholmes3948@rogers.com,charlie4294@rogers.com,fmiller5903@rogers.com,haroldp3984@rogers.com,johnp3950@rogers.com,kpost2349@rogers.com,bobsmith@2fiftyok.com,davidsmith@2fiftyok.com,fredsmith@2fiftyok.com,haroldsmith@2fiftyok.com,jeffsmith@2fiftyok.com,larrysmith@2fiftyok.com,nicksmith@2fiftyok.com,paulsmith@2fiftyok.com,tomsmith@2fiftyok.com,williamsmith@2fiftyok.com,billyh3480@yahoo.com.hk,frankoi3890@yahoo.com.hk,pfearnow@icloud.com,john.white206@yahoo.com.tw,e.hall2398@yahoo.com.tw,gjonas3498@yahoo.com.tw,olgav4345@yahoo.com.tw,pfenn3434@naver.com,zebrastripes@naver.com,victorv398@naver.com,tidopolson1@naver.com,rpfennin@naver.com,david22lane@naver.com,kim3434kim@naver.com,sora11bai@naver.com,fredsmith250@qq.com,haroldsmith250@qq.com,jeffsmith250@qq.com,nicksmith250@qq.com,tomsmith250@qq.com,bobsmith250@qq.com,dwade9853@gmx.com,ftorry3895@gmx.com,jschu555@gmx.com,rrood0909@gmx.com,vtine3059@gmx.com,aduchemin01@sfr.fr,bduchemin01@sfr.fr,cduchemin01@sfr.fr,dduchemin01@sfr.fr,c-gros@sfr.fr,d-fayot@sfr.fr,z-simplet@sfr.fr,m-levilain@sfr.fr,b-moncheri@sfr.fr,agriffee8094@126.com,charlest3089@126.com,freddy2089@126.com,hwalter3895@126.com,johnj3089@126.com,mandir3498@126.com,psmith2094@126.com,randyk0903@126.com,agriffee8094@163.com,charlest3089@163.com,freddy2089@163.com,mandir3498@163.com,psmith2094@163.com,tomwright309@163.com,whetherly7983@163.com,tomwright309@126.com,whetherly7983@126.com,j.boudin01@free.fr,d.w.langlais@free.fr,f.e.zoubidou@free.fr,e.deguemps@free.fr,s.o.simplet2014@free.fr,c.pasundado@free.fr,m.t.calais01@free.fr,j.v.toultemps@free.fr,ryanpf@qq.com,timyatz29@yahoo.co.jp,wubilly777666@yahoo.co.jp,Angie9854@bigpond.com,Don3009@bigpond.com,Freddy0909@bigpond.com,Hill3334@bigpond.com,KenT3340@bigpond.com,Nancy3988@bigpond.com,RickS0935@bigpond.com,Vick9892@bigpond.com,Ylinn3431@bigpond.com,ZebR9831@bigpond.com,abbyjax5829@t-online.de,bellb2053@t-online.de,eddyr3095@t-online.de,j.mccalle250@t-online.de,kpolls242@t-online.de,maryalice245@t-online.de,pfenninger34@t-online.de,mknight30@teksavvy.com,jknight25@teksavvy.com,aknight04@teksavvy.com,mknight09@teksavvy.com,nknight16@teksavvy.com,jknight08@teksavvy.com,lknight19@teksavvy.com,kknight12@teksavvy.com,pknight19@teksavvy.com,rknight33@teksavvy.com,abbyt34785@nate.com,charles3498@nate.com,eddye3985@nate.com,noworries093@nate.com,pault0937@nate.com,quren0934@nate.com,tommytom3417@nate.com,bigbob501@hanmail.net,duckydan15@hanmail.net,frank2110@hanmail.net,icer71@hanmail.net,jylliantome@hanmail.net,kyleman@hanmail.net,madridborn@hanmail.net,opera.lover@hanmail.net,sillysam68@hanmail.net,zellerton@hanmail.net,fresh9833@onet.pl,lance0888@onet.pl,nicki9898@onet.pl,ricki3424@onet.pl,vicky0011@onet.pl,yin1901@onet.pl,zen9835@onet.pl,asmith1956@onet.pl,agriffee8094@yeah.net,charlest3089@yeah.net,freddy2089@yeah.net,hwalter3895@yeah.net,johnj3089@yeah.net,mandir3498@yeah.net,psmith2094@yeah.net,randyk0903@yeah.net,tomwright309@yeah.net,whetherly7983@yeah.net,agriffee8094@sohu.com,charlest3089@sohu.com,freddy2089@sohu.com,mandir3498@sohu.com,psmith2094@sohu.com,randyk0903@sohu.com,tomwright309@sohu.com,rpfenn34@bol.com.br,raboss929@bol.com.br,fangyuan4276@21cn.com,niexin396818@21cn.com,duchao7896@21cn.com,shihui30329@21cn.com,wanjiang72814@21cn.com,hushe511985@21cn.com,daitong13433@21cn.com,canyi2677@21cn.com,jiaofu24804@21cn.com,yanfen65795@21cn.com,jietiao206004@21cn.com,andy3988@139.com,gabe3433@139.com,paul3958@139.com,vick3858@139.com,anna.malofsky@onet.pl,l.verdure01@free.fr,g.malpartout@free.fr,hanky19988@nate.com,piotr799@onet.pl,kinyu10@nate.com,westfrancis940@yahoo.com.hk,wuping890@yahoo.com.hk,pingfu792@yahoo.com.hk,zhizhi8989@yahoo.com.hk,abby2424@alice.it,charly2098@alice.it,frank9888@alice.it,jewel8934@alice.it,nancy9222@alice.it,abbyanne@sapo.pt,cherese983@sapo.pt,ereneau13@sapo.pt,larry898@alice.it,gmaer1959@sapo.pt,bobwilliams@inbox-informant.com,davidwilliams@inbox-informant.com,fredwilliams@inbox-informant.com,haroldwilliams@inbox-informant.com,jeffwilliams@inbox-informant.com,larrywilliams@inbox-informant.com,nickwilliams@inbox-informant.com,paulwilliams@inbox-informant.com,tomwilliams@inbox-informant.com,williamwilliams@inbox-informant.com,18760491457@189.cn,18760496118@189.cn,18760488281@189.cn,18760489737@189.cn,18760486552@189.cn,18760495463@189.cn,18760490209@189.cn,18760492411@189.cn,18760491169@189.cn,18760485713@189.cn,bobwilson@two50ok.net,davidwilson@two50ok.net,fredwilson@two50ok.net,haroldwilson@two50ok.net,jeffwilson@two50ok.net,larrywilson@two50ok.net,nickwilson@two50ok.net,paulwilson@two50ok.net,tomwilson@two50ok.net,williamwilson@two50ok.net,xman19892@sapo.pt,abbynancy24@alice.it,charlyjewel@alice.it,larrypolo@alice.it,mrpaulf@alice.it,fc439848@skynet.be,fd672255@skynet.be,fb958914@skynet.be,mb662392@proximus.be,mb662390@proximus.be,mb662389@proximus.be,mb662077@proximus.be,mb662074@proximus.be,mb662075@proximus.be,om.tar01@btinternet.com,ot.mar78@btinternet.com,tar.ot99@btinternet.com,ari.tar7@btinternet.com,o.backup3@btinternet.com,donnado24@freenet.de,freidmanj@freenet.de,rickyfan200@freenet.de,ulgaschvetzle@freenet.de,yellowsubmarine2@freenet.de,13144900678@wo.cn,13144902487@wo.cn,13144903112@wo.cn,13144903651@wo.cn,13144903848@wo.cn,13144905071@wo.cn,13144905271@wo.cn,13144905577@wo.cn,13144906166@wo.cn,13144907070@wo.cn,meridethheinz@sapo.pt,pablos151@sapo.pt,rmette79@sapo.pt,sammyw8080@sapo.pt,iwalsh99@sapo.pt,ryanpf@seznam.cz,agriffee8094@seznam.cz,charlest3089@seznam.cz,freddy2089@seznam.cz,hwalter3895@seznam.cz,johnj3089@seznam.cz,mandir3498@seznam.cz,psmith2094@seznam.cz,tomwright309@seznam.cz,whetherly7983@seznam.cz,rpfennin@terra.com,aiden3985@terra.com,chucky398@terra.com,yanker3434@terra.com,larry8589@terra.com,mholts3435@terra.com,bobbyk1@spray.se,mottez6@spray.se,gregsonjunior@spray.se,jasminasolo@spray.se,hespel007@spray.se,simoncave@spray.se,juliawest@spray.se,westside500@spray.se,eastside500@spray.se,ronnysenior@spray.se,samm3553@naver.com,ricardomont3@naver.com,admin@250ok.in,abi@250ok.in,jaini@250ok.in,saurin@250ok.in,gaurav@250ok.in,priyanka@250ok.in,neha@250ok.in,vip@250ok.in,ramesh@250ok.in,nirav@250ok.in,bobbrown@250ok.co,davidbrown@250ok.co,fredbrown@250ok.co,haroldbrown@250ok.co,jeffbrown@250ok.co,larrybrown@250ok.co,nickbrown@250ok.co,paulbrown@250ok.co,tombrown@250ok.co,williambrown@250ok.co,bobmiller@250ko.com,davidmiller@250ko.com,fredmiller@250ko.com,haroldmiller@250ko.com,jeffmiller@250ko.com,larrymiller@250ko.com,nickmiller@250ko.com,paulmiller@250ko.com,tommiller@250ko.com,williammiller@250ko.com,ryan.pfenninger@o2.pl,agriffee8094@o2.pl,charlest3089@o2.pl,freddy2089@o2.pl,hwalter3895@o2.pl,fadrian@smalik01.plus.com,gadrian@smalik01.plus.com,hadrian@smalik01.plus.com,iadrian@smalik01.plus.com,jadrian@smalik01.plus.com,abruno@smalik01.plus.com,bbruno@smalik01.plus.com,cbruno@smalik01.plus.com,dbruno@smalik01.plus.com,ebruno@smalik01.plus.com,billybunter@jubiimail.dk,mikeyrook@jubiimail.dk,fredwilliams@jubiimail.dk,simonpeers@jubiimail.dk,zoebones@jubiimail.dk,jimbatman@jubiimail.dk,domfriars@jubiimail.dk,yoblob@jubiimail.dk,ryan250@terra.com.br,greg250@terra.com.br,victors250@terra.com.br,adrianoalves250@terra.com.br,annafer250@terra.com.br,gustavodias250@terra.com.br,felipeb250@terra.com.br,pedrog250@terra.com.br,isabelac250@terra.com.br,carloss250@terra.com.br,ryan250@interia.pl,aiden3985@interia.pl,aiden3985@centrum.cz,ryan250@centrum.cz,aiden3985@volny.cz,ryan250@volny.cz,chucky398@interia.pl,jonny384@centrum.cz,mholts3435@volny.cz,xman19892@interia.pl,gregy9874@interia.pl,jonny384@interia.pl,gregy9874@volny.cz,larry8589@centrum.cz,larry8589@interia.pl,nschwartzman@videotron.ca,xman19892@volny.cz,yanker3434@centrum.cz,mholts3435@interia.pl,poiuyt94@interia.pl,yanker3434@interia.pl,american.pharoah@videotron.ca,california.chrome@videotron.ca,rachel.alexandra@videotron.ca,smarty.jones@videotron.ca,war.emblem@videotron.ca,allen.jones@35.250ok.info,allen.jones@250ok.work,phil.mason@35.250ok.info,amanda.lear@35.250ok.info,rob.rivers@35.250ok.info,tom.jones@35.250ok.info,daniel.scott@35.250ok.info,bob.smith@35.250ok.info,cristina.adams@35.250ok.info,luke.walker@35.250ok.info,han.solo@35.250ok.info,phil.mason@250ok.work,amanda.lear@250ok.work,rob.rivers@250ok.work,tom.jones@250ok.work,daniel.scott@250ok.work,bob.smith@250ok.work,cristina.adams@250ok.work,luke.walker@250ok.work,han.solo@250ok.work,allen.jones@250ok.email,phil.mason@250ok.email,amanda.lear@250ok.email,rob.rivers@250ok.email,tom.jones@250ok.email,daniel.scott@250ok.email,bob.smith@250ok.email,cristina.adams@250ok.email,luke.walker@250ok.email,han.solo@250ok.email,allen.jones@263.250ok.info,phil.mason@263.250ok.info,amanda.lear@263.250ok.info,rob.rivers@263.250ok.info,tom.jones@263.250ok.info,daniel.scott@263.250ok.info,bob.smith@263.250ok.info,cristina.adams@263.250ok.info,luke.walker@263.250ok.info,han.solo@263.250ok.info,allen.jones@250ok.info,han.solo@250ok.info,phil.mason@250ok.info,amanda.lear@250ok.info,rob.rivers@250ok.info,tom.jones@250ok.info,daniel.scott@250ok.info,bob.smith@250ok.info,cristina.adams@250ok.info,luke.walker@250ok.info,abonaducci@libero.it,bbonaducci@libero.it,dbonaducci@libero.it,ebonaducci@libero.it,ggiaco01@libero.it,hgiaco01@libero.it,igiaco01@libero.it,jgiaco01@libero.it,allen.jones@hk.com,phil.mason@hk.com,amanda.lear@hk.com,rob.rivers@hk.com,tom.jones@hk.com,daniel.scott@hk.com,bob.smith@hk.com,cristina.adams@hk.com,luke.walker@hk.com,han.solo@hk.com,aschrades1@charter.net,aschrades2@charter.net,alex@250oknotes.com,bossy@250oknotes.com,frank@250oknotes.com,greg@250oknotes.com,jeff@250oknotes.com,kate@250oknotes.com,mike@250oknotes.com,paul@250oknotes.com,ryan@250oknotes.com,tim@250oknotes.com,bobtaylor@email-seeds.net,davidtaylor@email-seeds.net,fredtaylor@email-seeds.net,haroldtaylor@email-seeds.net,jefftaylor@email-seeds.net,larrytaylor@email-seeds.net,nicktaylor@email-seeds.net,paultaylor@email-seeds.net,tomtaylor@email-seeds.net,williamtaylor@email-seeds.net,bobanderson@250okpp.com,davidanderson@250okpp.com,fredanderson@250okpp.com,haroldanderson@250okpp.com,jeffanderson@250okpp.com,larryanderson@250okpp.com,nickanderson@250okpp.com,paulanderson@250okpp.com,tomanderson@250okpp.com,ryan@250okpp.com,pesaveana@r7.com,alberta321@r7.com,arborio54@r7.com,mguiloga@r7.com,patavento2@r7.com,mpasavet21@r7.com,pocolocho@r7.com,barbaruta@r7.com,ecolana@r7.com,colabeca@r7.com,pesaveana@vera.com.uy,alberta321@vera.com.uy,arborio54@vera.com.uy,mguiloga@vera.com.uy,patavento2@vera.com.uy,mpasavet21@vera.com.uy,pocolocho@vera.com.uy,barbaruta@vera.com.uy,ecolana@vera.com.uy,colabeca@vera.com.uy,adupontel@globo.com,barnardblue@globo.com,nanijulio@globo.com,pololameri@globo.com,marcotemeri@globo.com,tutialgo@globo.com,mjakola@globo.com,lapolufe@globo.com,laracraft12@globo.com,miriambaka@globo.com,miriamgonzalez12@fibertel.com.ar,fabriciomatan32@fibertel.com.ar,fabriciomatana32@fibertel.com.ar,mamivie@fibertel.com.ar,papivie@fibertel.com.ar,totivie@fibertel.com.ar,lamucavie@fibertel.com.ar,coletavie@fibertel.com.ar,gabrieltuti@fibertel.com.ar,facundoariel@fibertel.com.ar,alex@250ok.awsapps.com,bossy@250ok.awsapps.com,chad@250ok.awsapps.com,frank@250ok.awsapps.com,greg@250ok.awsapps.com,jeff@250ok.awsapps.com,kate@250ok.awsapps.com,mike@250ok.awsapps.com,paul@250ok.awsapps.com,ryan@250ok.awsapps.com,maranza@139.com,mikewesley@optimum.net,j.white201@optimum.net,leslie.dunn@optimum.net,s.fitzgerald12@optimum.net,chinnzer@optimum.net,sklipt2@optimum.net,jmerlou2@optimum.net,torse1@optimum.net,jritte2@optimum.net,eugeneupland@optimum.net,bossy_250@ziggo.nl,greg_250@ziggo.nl,paul_250@ziggo.nl,jeff_250@ziggo.nl,ryan_250@ziggo.nl,tim_250@ziggo.nl,andyc3po@att.net,charleyd@att.net,eddyoswald@att.net,gloria.fuente@att.net,johnnywalkerton@att.net,merry.mary@att.net,paulteeter@att.net,tommybolen@att.net,wilsonsmith68@att.net,yanzmanz@att.net,all3n.iverson@yahoo.com,eddiemalofsky@yahoo.com,kenny.rog3rs@yahoo.com,k3vin.costner@yahoo.com,250okllc@tpg.com.au,ryanp250@tpg.com.au,bossy250@tpg.com.au,gregk250@tpg.com.au,paulf250@tpg.com.au";

			$arrSeedlistMails = @explode(',',trim($strSeedlist));
			
				
			if(count($arrSeedlistMails) > 0){
				$arrCampaigns = array();	
				foreach($arrSeedlistMails as $seedlist_contact){
				$subscriber_info = array('subscriber_id'=>'-99','subscriber_email_address'=>$seedlist_contact,'subscriber_first_name'=>'','subscriber_last_name'=>'', 
									 'subscriber_state'=>'','subscriber_zip_code'=>'','subscriber_country'=>'','subscriber_city'=>'','subscriber_company'=>'','subscriber_dob'=>'', 
									 'subscriber_phone'=>'','subscriber_address'=>'','subscriber_extra_fields'=>'');
			
				$text_message= "This is the seedlist message for BoldInbox" ;
				
				$message= $CIDHeader."<table width='100%' style='#ffffff'><tr><td align='center'>
							<font size='1' style='color:#777;font-family:helvetica;font-size:11px;line-height:125%;'> 
								<a href='".CAMPAIGN_DOMAIN."c/-1/-99'>View in browser</a>
							</font>
							</td></tr>
							<tr><td>$text_message</td></tr>
							<tr>
							<td align='center'>$to_ensure_delivery $add_us_to_your_address_book.</td>
							</tr>
							<tr>
								<td align='center'>
								<a style='color:#606060;' href='".CAMPAIGN_DOMAIN."'>unsubscribe</a> | <a style='color:#606060;' href='".CAMPAIGN_DOMAIN."'>Forward</a>
								</td>
							</tr>
							<tr><td>&nbsp;</td></tr>
							<tr><td  align='center'><a href='".CAMPAIGN_DOMAIN."cprocess/powered_by_bib/-1/-99'>
									<img border='0' alt='Powered by BoldInbox' src='".CAMPAIGN_DOMAIN."locker/images/powered-by-logo-blue.png' />
								</a></td></tr>
								</table>
							</td>
						</tr>			
					</table>		
					</body></html>";
				$subject="Seedlist Testing";
			
				$arrCampaigns[] =
					array('message'=>$message,'text_message'=>$text_message,'subject'=>$subject,'sender_name'=>'pravin','sender'=>'rohan@globetrekkerz.co.in','campaign_id'=>-1,'subscriber_info'=>$subscriber_info,'vmta'=>$vmta);
				}
				
				if(count($arrCampaigns) > 0){	
					$this->send_campaign_in_threads($arrCampaigns);
					unset( $arrCampaigns );
					$arrCampaigns = array();	
				}	
			}		
	}
	/**
	* FUNCTION to check cronjob, if stucked send rcAlert
	*/
	function alertIfStucked(){
		$last_sending_started_dt 	= $this->confg_arr['campaign_cron_status_change_time'];		
		$timenow = gmdate("Y-m-d H:i:s", time());	
		$timeDiffMinute = round(abs(strtotime($timenow) - strtotime($last_sending_started_dt)) / 60);
		if( $timeDiffMinute > 40 && ($timeDiffMinute % 5)===0 ) {
			$to = $this->confg_arr['admin_notification_email'];		
			$msg = "Cron is stucked. Check cron-status in admin and mark it as complete. Or, call Pravin.";
			admin_notification_send_email($to, SYSTEM_EMAIL_FROM,'BoldInbox', "Cron is stucked",$msg,$msg);	
		}
	}
}
?>
