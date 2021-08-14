<?php
class remove_old_img_csv extends CI_Controller
{	
	
	function __construct(){
		parent::__construct();
		
		force_ssl();	
	}
// Delete Old CSV Log Files and records from the DB			
	function index(){
		// get campaigns for users who left BIB before 1 year
		$rsOldMembers = $this->db->query("SELECT t1.member_id, t1.member_username, t2.next_payement_date FROM red_members t1 INNER JOIN red_member_packages t2 ON t1.member_id = t2.member_id WHERE last_login_time < '2018-07-01' AND t2.next_payement_date < '2018-07-01'");
		//$rsOldMembers = $this->db->query("SELECT t1.member_id, t1.member_username, t2.next_payement_date FROM red_members t1 INNER JOIN red_member_packages t2 ON t1.member_id = t2.member_id WHERE 1");
		$gtotal = 0;
		foreach($rsOldMembers->result_array() as $rec){	
			$mid = $rec['member_id'];
			$user_dir = $mid % 1000;
		
			$user_private_folder = $this->config->item('user_private').$user_dir.'/'.$mid.'/' ;	
			$user_public_folder = $this->config->item('user_public').$user_dir.'/'.$mid.'/' ;
			
			$thisPrivate = $this->getDirectorySize($user_private_folder);
			$thisPublic = $this->getDirectorySize($user_public_folder);
			$gtotal += $thisPrivate;
			$gtotal += $thisPublic;
			echo "<br/>size of user_private_folder $user_private_folder=". $thisPrivate;
			
			echo  "<br/>size of user_public_folder $user_private_folder=".$thisPublic;		
			
			$user_zip = $this->config->item('user_public').$user_dir.'/'.$mid.'/imported_zip_files/' ;
			$thisUserZip = $this->getDirectorySize($user_zip);
			if($thisUserZip > 0){
				$gtotal += $thisUserZip;			
				echo  "<br/>size of user_public_folder $user_zip=".$thisUserZip;		
			}
			
			$user_extract = $this->config->item('user_public').$user_dir.'/'.$mid.'/extracted_zip_files/' ;
			$thisUserZipExt = $this->getDirectorySize($user_extract);
			if($thisUserZipExt > 0){
				$gtotal += $thisUserZipExt;			
				echo  "<br/>size of user_public_folder $user_extract=".$thisUserZipExt;		
			}	
			
			
		}
		$rsOldMembers->free_result();
		
		echo "<br/>Grand total size=".$gtotal;
	}
	function oldCampaigns($mid=0){
		// get campaigns which are freezed
		$gtotal = 0;
		if($mid > 0){
			$user_dir = $mid % 1000;
			$rsOldMembers = $this->db->query("SELECT distinct campaign_id FROM red_email_campaigns WHERE is_restore > 0 and campaign_created_by='$mid'");
		 
			
			foreach($rsOldMembers->result_array() as $rec){			 
				$cid = $rec['campaign_id'];			
				$user_emails = $this->config->item('user_public').$user_dir.'/'.$mid.'/email_templates/'.$cid ;
				$thisUserEmails = $this->getDirectorySize($user_emails);
				if($thisUserEmails > 0){
					$gtotal += $thisUserEmails;			
					echo  "<br/>size of user_public_folder $user_emails=".$thisUserEmails;		
				}			
			}
			
			
			
			
		}else{
		
			$rsOldMembers = $this->db->query("SELECT campaign_id, campaign_created_by FROM red_email_campaigns WHERE is_restore > 0 ");
		
			foreach($rsOldMembers->result_array() as $rec){	
				$mid = $rec['campaign_created_by'];
				$cid = $rec['campaign_id'];
				$user_dir = $mid % 1000;
		
			
				$user_emails = $this->config->item('user_public').$user_dir.'/'.$mid.'/email_templates/'.$cid ;
				$thisUserEmails = $this->getDirectorySize($user_emails);
				if($thisUserEmails > 0){
					$gtotal += $thisUserEmails;			
					echo  "<br/>size of user_public_folder $user_emails=".$thisUserEmails;		
				}			
			}
			$rsOldMembers->free_result();
		}
		echo "<br/>Grand total size=".$gtotal;
	}

	function getDirectorySize( $path ){
		if( !is_dir( $path ) ) {
			return 0;
		}

		$path   = strval( $path );
		$io     = popen( "ls -ltrR {$path} |awk '{print \$5}'|awk 'BEGIN{sum=0} {sum=sum+\$1} END {print sum}'", 'r' );
		$size   = intval( fgets( $io, 80 ) );
		pclose( $io );

		return round(($size / 1048576),2) ;
	}


	function showSize($thisPath){
		$totalSize = 0;
		foreach (new DirectoryIterator($thisPath) as $file) {
			if ($file->isFile()) {
				$totalSize += $file->getSize();
			}
		}
		return	round(($totalSize / 1048576),2) ;
	}



}	