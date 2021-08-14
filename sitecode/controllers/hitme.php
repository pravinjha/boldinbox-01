<?php
/**
* A Hitme class
*
* This class is for unsubscriber mail
*
* @version 1.0
* @author Pravin Jha <pravinjha@gmail.com>
* @project BoldInbox
*/
class Hitme extends CI_Controller
{
	/**
	*	Contructor for controller.
	*	It checks user session and redirects user if not logged in
	*/
	function __construct(){
        parent::__construct();
        
        $this->load->model('userboard/Campaign_Model');
		$this->load->model('UserModel');					
		$this->load->model('userboard/Subscriber_Model');		
		$this->load->model('userboard/Emailreport_Model');
		$this->load->library('encrypt');
		$this->load->helper('admin_notification');	

    }
	
	function setme($enc_cid=0,$subscriber_id=0){
		$id =  $this->is_authorized->encryptor('decrypt', $enc_cid);
		$subscriber_id = str_replace('.html','',$subscriber_id);
		$arrSubscriber = $this->is_authorized->decodeSubscriber($subscriber_id);
		list($subscriber_id,$subscriber_email) = $arrSubscriber;	
		if(!(intval($subscriber_id) > 0 ))exit;
		$subscriber_email = $this->is_authorized->webCompatibleString($subscriber_email);
		$mIP = $this->is_authorized->getRealIpAddr();
		
		$fetch_condiotions_array=array('campaign_id'=>$id,'subscriber_id'=>$subscriber_id,'subscriber_email_address'=>$subscriber_email,'email_sent'=>1);
		$email_report=$this->Emailreport_Model->get_emailreport_data($fetch_condiotions_array);
		if($email_report[0]['email_track_read']<=0){					
			// update for subscription			
			$this->Emailreport_Model->update_emailreport(array('email_track_read'=>1,'email_track_read_date'=>date('Y-m-d H:i:s',now())), array('campaign_id'=>$id,'subscriber_id'=>$subscriber_id));
			$this->db->query("update `red_email_campaigns_scheduled` set `email_track_read` = `email_track_read`+1 where campaign_id='$id'");			 
			$this->db->query("update red_email_subscribers set `read` = `read`+1, `last_read_ip`='$mIP', `last_read_date`=current_timestamp() where subscriber_id='$subscriber_id'");	
			// Increment OPENED global_ipr_daily for major webmails
			$arrEml = @explode('@',$subscriber_email);
			$emlDomain = $arrEml[1];
			$IPR_Domain = (in_array($emlDomain,config_item('major_domains')))? $emlDomain : 'all' ;	
			
			$rsCampaign = $this->db->query("select DATE_FORMAT(email_send_date, '%Y-%m-%d')email_send_date, pipeline,campaign_created_by user_id from red_email_campaigns where campaign_id='$id'" );
			$vmta = $rsCampaign->row()->pipeline;
			$email_send_date = $rsCampaign->row()->email_send_date;
			$user_id = $rsCampaign->row()->user_id;
			$rsCampaign->free_result();			
			$this->db->query("insert into red_global_ipr_daily set `mail_domain` = '$IPR_Domain' ,  `log_date`='$email_send_date' ,  `pipeline`='$vmta', `user_id`='$user_id', total_opened=(total_opened + 1) ON DUPLICATE  KEY UPDATE  total_opened=(total_opened + 1) ");			
		}
		
		 
		
		/*
		$img_path=substr(FCPATH,0,strrpos(FCPATH,'/'));		
		$img_path= $img_path.'/locker/images/pix.gif';
		header('Content-Type: image/gif');

		$img=@imagecreatefromgif($img_path);
		imagegif($img);
		imagedestroy($img);
				
			*/
		
		
	}
}
?>