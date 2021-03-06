<?php
/*
	Model class for email track
*/
class Emailreport_Model extends CI_Model
{
	//Constructor functon for subscriber with parent constructor
	function __construct(){
		parent::__construct();
	}
	
	//function to insert send email in email track table
	function insert_emailtrack($input_array){
		$cid = $input_array['campaign_id'];
		$statsTable = $this->is_authorized->getStatsTable($cid); // get stats table
		$this->db->insert($statsTable, $input_array);
		return $this->db->insert_id();
	}
	//function to replace send email in email track table
	function replace_emailtrack($input_array){
		$cid = $input_array['campaign_id'];
		$statsTable = $this->is_authorized->getStatsTable($cid); // get stats table
		foreach($input_array as $key => $val) $append[] = $key . " = " . $val;
        $this->db->query("REPLACE INTO $statsTable SET " . implode(',',$append));
		return $this->db->insert_id();
	}	
	# Update STATS table from PMTA generated log by PMTA cronjob.
	function update_emailreport_123($input_array,$conditions_array, $retry=0){	
		$cid = $conditions_array['campaign_id'];
		$statsTable = $this->is_authorized->getStatsTable($cid); // get stats table
		$this->db->trans_begin();
		$this->db->update($statsTable,$input_array,$conditions_array);
		if ($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			$error_no = $this->db->_error_number();
			$error_msg = $this->db->_error_message();			
			if($retry < 3 and $error_no == '1213'){
				$retry++;
				sleep(1);
				$this->update_emailreport($input_array,$conditions_array, $retry);			
			}
		}else{
			$this->db->trans_commit();
			return $this->db->affected_rows();
		} 		
	}	
	//function to update email track info
	function update_emailreport($input_array,$conditions_array){
		$cid = $conditions_array['campaign_id'];
		$statsTable = $this->is_authorized->getStatsTable($cid); // get stats table
		$this->db->update($statsTable,$input_array,$conditions_array);
		return $this->db->affected_rows();
	}
	//function to delete email track info
	function delete_emailreport($conditions_array=array()){
		$cid = $conditions_array['campaign_id'];
		$statsTable = $this->is_authorized->getStatsTable($cid); // get stats table
		$this->db->delete($statsTable,$conditions_array);
	}
	
	//function to fetch email report
	function get_emailreport_data($conditions_array=array(),$like=false,$rows_per_page=10,$start=0,$paging=false){
		$rows=array();
		if($like){
			if($_POST['mode']=='search' AND !isset($_POST['btn_cancel'])){
				if($_POST['subscriber_email_address']){
					$subscriber_email_address=$_POST['subscriber_email_address'];
					$this->db->like('subscriber_email_address',$subscriber_email_address);
				}			
				if($_POST['keyword']!=""){
					$keyword=$this->escape_str($_POST['keyword'],true);
					$this->db->where('(`subscriber_email_address` LIKE \'%'.$keyword.'%\' )');
					//$this->db->or_where('(`subscriber_first_name` LIKE \'%'.$keyword.'%\' )');
				}
			}
		}
		$cid = $conditions_array['campaign_id'];
		$statsTable = $this->is_authorized->getStatsTable($cid); // get stats table
		$this->db->from($statsTable.' as ret')->where($conditions_array);
		if($paging){			 
			$this->db->limit($rows_per_page,$start);			
		}
		$this->db->order_by('ret.subscriber_email_address');
		
		$result=$this->db->get();		
		foreach($result->result_array() as $row){
			$rows[]=$row;
		}
		$result->free_result();
		return $rows;
	}
	//function to fetch subscribers email report
	function get_subscriber_emailreport_data($conditions_array=array(),$is_preprocess=false,$rows_per_page=10,$start=0,$paging=false){
		//$this->db->select('ret.subscriber_id, ret.subscriber_email_address, ret.subscriber_first_name, ret.subscriber_last_name, ret.subscriber_state, ret.subscriber_zip_code, ret.subscriber_country, ret.subscriber_city, ret.subscriber_company, ret.subscriber_dob, ret.subscriber_phone, ret.subscriber_address, ret.subscriber_extra_fields',false);
		$this->db->select('ret.subscriber_id, ret.is_active',false);
		$this->db->from('red_email_queue as ret')->where($conditions_array);		
		if($paging){
			$this->db->limit($rows_per_page,$start);			
		}
		#$this->db->order_by('ret.subscriber_email_address');
		if(!$is_preprocess){
			$this->db->order_by("ret.is_active", "desc");
			$this->db->order_by("ret.subscriber_id", "random");
		}else{
			$this->db->order_by("ret.is_active", "asc");		
		}
		$result=$this->db->get();
		
		foreach($result->result_array() as $row){
			$sid = $row['subscriber_id'];
			$rsCDetail = $this->db->query("select subscriber_created_by, subscriber_email_address, subscriber_email_domain, subscriber_vmta, subscriber_first_name, subscriber_last_name, subscriber_name, subscriber_state, subscriber_zip_code, subscriber_country, subscriber_city, subscriber_company, subscriber_dob, subscriber_phone, subscriber_address, subscriber_extra_fields, sent, `read`, last_read_date from red_email_subscribers where subscriber_id='$sid'");
			$row['subscriber_created_by']	= $rsCDetail->row()->subscriber_created_by;
			$row['subscriber_email_address']= $rsCDetail->row()->subscriber_email_address;
			$row['subscriber_email_domain']	= $rsCDetail->row()->subscriber_email_domain;
			$row['subscriber_vmta'] 		= $rsCDetail->row()->subscriber_vmta;
			$row['subscriber_first_name'] 	= $rsCDetail->row()->subscriber_first_name;
			$row['subscriber_last_name'] 	= $rsCDetail->row()->subscriber_last_name;
			$row['subscriber_name'] 		= $rsCDetail->row()->subscriber_name;
			$row['subscriber_state'] 		= $rsCDetail->row()->subscriber_state;
			$row['subscriber_zip_code'] 	= $rsCDetail->row()->subscriber_zip_code;
			$row['subscriber_country'] 		= $rsCDetail->row()->subscriber_country;
			$row['subscriber_city'] 		= $rsCDetail->row()->subscriber_city;
			$row['subscriber_company'] 		= $rsCDetail->row()->subscriber_company;
			$row['subscriber_dob'] 			= $rsCDetail->row()->subscriber_dob;
			$row['subscriber_phone'] 		= $rsCDetail->row()->subscriber_phone;
			$row['subscriber_address'] 		= $rsCDetail->row()->subscriber_address;
			$row['subscriber_extra_fields'] = $rsCDetail->row()->subscriber_extra_fields;
			$row['sent'] 					= $rsCDetail->row()->sent;
			$row['read'] 					= $rsCDetail->row()->read;
			$row['last_read_date'] 			= $rsCDetail->row()->last_read_date;
			
			$rsCDetail->free_result();
			
			$rows[]=$row;
		}
		$result->free_result();
		return $rows;
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
        if ($like === TRUE){
            $str = str_replace(array('%', '_'), array('\\%', '\\_'), $str);
        }        
        return $str;
	}
	//function to fetch email report with campaign info
	function get_emailreport_campaign_data($conditions_array=array(),$rows_per_page=0,$start=0){
		$cid = $conditions_array['campaign_id'];
		$statsTable = $this->is_authorized->getStatsTable($cid); // get stats table
		$rows=array();
		$this->db->order_by('rec.email_send_date','desc');		
		$this->db->from($statsTable.' as ret');		
		$this->db->join('red_email_campaigns as rec','rec.campaign_id=ret.campaign_id');
		$this->db->where($conditions_array);
		if($rows_per_page>0){
			$this->db->limit($rows_per_page, $start);
		}
		$result=$this->db->get();
		foreach($result->result_array() as $row)
		{
			$rows[]=$row;			
		}
		$result->free_result();
		return $rows;		
	}
	//function to delete subscriber
	function delete_subscriber($conditions_array){		
		$this->db->update('red_email_subscribers',array('is_deleted'=>1),$conditions_array);		
		return $this->db->affected_rows();
	}
	//function to fetch count of subscriber data
	function get_emailreport_count($conditions_array=array()){	
		$cid = $conditions_array['campaign_id'];
		$statsTable = $this->is_authorized->getStatsTable($cid); // get stats table
		//$this->db->select("count(queue_id) totrec");
		$this->db->select("count(distinct subscriber_id) totrec");
		$this->db->where($conditions_array);
		$this->db->from($statsTable);
		return $this->db->get()->row()->totrec;
	}
	//function to fetch count of subscriber data
	function get_emailreport_sentcount($conditions_array=array()){	
		$this->db->select("campaign_contacts totrec");		
		$this->db->where($conditions_array);
		$this->db->from('red_email_campaigns');
		return $this->db->get()->row()->totrec;
	}
	//function to fetch email report
	function get_emailreport_listdata($conditions_array=array()){
		$rows=array();
		$result=$this->db->get_where('red_email_campaigns_scheduled',$conditions_array);
		foreach($result->result_array() as $row){
			$rows[]=$row;
		}
		$result->free_result();
		return $rows;
	}
	
	//function to update email track list info
	function update_listemailreport($input_array,$conditions_array){
		$this->db->update('red_email_campaigns_scheduled',$input_array,$conditions_array);
		return $this->db->affected_rows();
	}
	//function to fetch email subscriber
	function get_emailreport_subscriber($conditions_array=array(),$rows_per_page=0,$start=0){
		$cid = $conditions_array['campaign_id'];
		$statsTable = $this->is_authorized->getStatsTable($cid); // get stats table
		$rows=array();
		$this->db->from($statsTable.' as ret');		
		if($rows_per_page>0){
			$this->db->limit($rows_per_page, $start);
		}
		$this->db->where($conditions_array);		
		$result=$this->db->get();			
		foreach($result->result_array() as $row){
			$rows[]=$row;
		}
		$result->free_result();
		return $rows;
	}
	//function to fetch email subscriber
	function get_emailreport_subscriber_count($conditions_array=array()){
		$cid = $conditions_array['campaign_id'];
		$statsTable = $this->is_authorized->getStatsTable($cid); // get stats table
		$rows=array();		
		$this->db->where($conditions_array);
		return $this->db->count_all_results($statsTable.' AS ret');		
	}
	//function to fetch clicks link detail
	function get_emailreport_click($conditions_array=array()){
		$rows=array();		 
		$this->db->select('campaign_id, user_id,  tiny_url, actual_url,  is_autoresponder , sum(counter) as cnt');
		$this->db->from('red_click_rate');		 
		$this->db->group_by(" tiny_url ,campaign_id, user_id,  actual_url,  is_autoresponder");
		$this->db->where($conditions_array);
		$result=$this->db->get();
		
		foreach($result->result_array() as $row){
			$rows[]=$row;
		}
		$result->free_result();
		return $rows;
	}
	//function to fetch subscriber list for click link
	function get_emailreport_subscriber_click($conditions_array=array(),$rows_per_page=0,$start=0){
		$cid = $conditions_array['ret.campaign_id'];
		$statsTable = $this->is_authorized->getStatsTable($cid); // get stats table
		$rows=array();
		$this->db->from('red_click_rate as ret');
		$this->db->join($statsTable.' as res', 'res.subscriber_id=ret.subscriber_id and res.campaign_id=ret.campaign_id');
		$this->db->where($conditions_array);
		if($rows_per_page>0){
			$this->db->limit($rows_per_page, $start);
		}
		$result=$this->db->get();

		foreach($result->result_array() as $row)
		{
			$rows[]=$row;
		}
		$result->free_result();
		return $rows;
	}
	//function to fetch subscriber list for autoresponder click link
	function get_emailreport_autoresponder_subscriber_click($conditions_array=array()){
		$rows=array();
		$this->db->from('red_click_rate as ret');
		$this->db->join('red_autoresponder_signup as res','res.email_track_subscriber_id=ret.subscriber_id');
		
		$this->db->where($conditions_array);
		$result=$this->db->get();
		
		foreach($result->result_array() as $row)
		{
			$rows[]=$row;
		}
		$result->free_result();
		return $rows;
	}
	
	
	/**
	 *	Function update_subscriber
	 *
	 *	Function to update existing subscriber info
	 *
	 *	@param (array) (input_array)  values to update into database
	 *
	 *	@param (array) (conditions_array)  conditions to checked with database with conditions
	 *
	 *	@return (int)	return updated subscriber id
	 */
	function update_subscriber($input_array,$conditions_array){
		$cid = $conditions_array['campaign_id'];
		$statsTable = $this->is_authorized->getStatsTable($cid); // get stats table
		$this->db->update($statsTable, $input_array,$conditions_array);
		return $this->db->affected_rows();
	}
	//function to insert send email in email track table
	function insert_forwardfriend($input_array){
		$this->db->insert('red_forward_friend',$input_array);
		return $this->db->insert_id();
	}
	function get_friends_count($cid){
		$query = "SELECT COUNT(`id`) totForwarded FROM `red_forward_friend` Where `campaign_id` = '$cid'";   
		$row_forward = $this->db->query($query)->result(); 
		$cnt = $row_forward[0];		
		return $cnt;
	}
	//function to fetch forward friend info
	function get_forward_friend($conditions_array=array()){
		$rows=array();
		$this->db->from('red_forward_friend as ret');	
		$this->db->where($conditions_array);
		$result=$this->db->get();	
		
		foreach($result->result_array() as $row){
			$rows[]=$row;
		}
		$result->free_result();
		return $rows;
	}
	
	//function to update email track info for autroesponders
	function update_autoresponder_emailreport($input_array,$conditions_array){
		$this->db->update('red_autoresponder_signup',$input_array,$conditions_array);
		return $this->db->affected_rows();
	}
	//function to subscriber info for autoresponders
	function get_autoresponder_emailreport_subscriber($conditions_array=array(),$rows_per_page=0,$start=0){
		$rows=array();
		$this->db->select('res.email_track_subscriber_id,res.subscriber_email,res.email_track_forward,res.email_track_click,res.email_track_bounce,subs.subscriber_first_name,subs.subscriber_last_name');	
		$this->db->from('red_autoresponder_signup as res');	
		//$this->db->join('red_email_subscribers as subs','res.subscriber_email=subs.subscriber_email_address  and res.subscriber_created_by=subs.subscriber_created_by');
		$this->db->join('red_email_subscribers as subs','res.email_track_subscriber_id=subs.subscriber_id and res.subscriber_created_by=subs.subscriber_created_by');
		if($rows_per_page>0){
			$this->db->limit($rows_per_page, $start);
		}
		$this->db->where($conditions_array);
		$this->db->order_by('res.autoresponder_scheduled_id');
		$result=$this->db->get();	
		
		foreach($result->result_array() as $row){
			$rows[]=$row;
		}
		$result->free_result();
		return $rows;
	}
	//function to count  subscriber info for autoresponders
	function get_autoresponder_emailreport_subscriber_count($conditions_array=array()){		
		#$this->db->select('distinct res.subscriber_email', false);	
		$this->db->where($conditions_array);
		return $this->db->count_all_results('red_autoresponder_signup as res');
	}
	
	//function to replace send email in email queue table
	function replace_emailqueue($input_array){										
		foreach($input_array as $key => $val) $append[] = $key . " = '" . $val."'";
        $this->db->query("REPLACE INTO red_email_queue SET " . implode(",",$append) ."");
		//$this->db->insert('red_email_queue',$input_array);
		return $this->db->insert_id();
	}
	//function to replace send email in email queue table
	function insert_on_duplicate_update_queue($input_array){
		$updatestr = array();
		$keystr    = array();
		$valstr    = array();
	
		foreach($values as $key => $val){
			$updatestr[] = $key." = '".$val."'";
			$keystr[]    = $key;
			$valstr[]    = $val;
		}
	
		$sql  = "INSERT INTO red_email_queue  (".implode(', ',$keystr).") ";
		$sql .= "VALUES (".implode(', ',$valstr).") ";
		$sql .= "ON DUPLICATE KEY UPDATE ".implode(', ',$updatestr);
	
		$this->db->query($sql);
		return $this->db->insert_id();
	}
	/*
	function get_queue_email(){
		$rows=array();
		$this->db->from('red_email_queue');	
		$conditions_array=array('email_sent'=>'1');
		$this->db->where($conditions_array);
		$result=$this->db->get();	
		
		foreach($result->result_array() as $row){
			$rows[]=$row;
		}
		$result->free_result();
		return $rows;
	}
	*/
	function delete_emailqueue($conditions_array=array()){
		$this->db->delete('red_email_queue',$conditions_array);
	}
	 
	//function to fetch email queue
	function get_emailqueue_data($conditions_array=array(),$like=false,$rows_per_page=10,$start=0,$paging=false){
		$rows=array();
		if($like){
			if($_POST['mode']=='search' AND !isset($_POST['btn_cancel'])){
				if($_POST['subscriber_email_address']){
					$subscriber_email_address=$_POST['subscriber_email_address'];
					$this->db->like('subscriber_email_address',$subscriber_email_address);
				}			
				if($_POST['keyword']!=""){
					$keyword=$this->escape_str($_POST['keyword'],true);
					$this->db->where('(`subscriber_email_address` LIKE \'%'.$keyword.'%\' )');
					//$this->db->or_where('(`subscriber_first_name` LIKE \'%'.$keyword.'%\' )');
				}
			}
		}
		
		$this->db->from('red_email_queue as req')->where($conditions_array);
		if($paging){			 
			$this->db->limit($rows_per_page,$start);			
		}
		$this->db->order_by('req.subscriber_email_address');
		
		$result=$this->db->get();
		
		
		foreach($result->result_array() as $row){
			$rows[]=$row;
		}
		$result->free_result();
		return $rows;
	}
	function get_subscription_list_title($conditions_array=array(),$subscription_id_array=array()){
		$this->db->select('res.subscription_title,');
		$this->db->from('red_email_subscriptions as res')->where($conditions_array);
		$this->db->where_in('subscription_id', $subscription_id_array);
		$result=$this->db->get();
		$rows = array();
		foreach($result->result_array() as $row){
			$rows[]=$row['subscription_title'];
		}
		$result->free_result();
		return $rows;
	}
}
?>