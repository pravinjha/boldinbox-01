<?php
class UserModel extends CI_Model
{
	//Constructor class with parent constructor
	function UserModel(){
		parent::__construct();
		$this->load->helper('cookie');
		$this->load->library('encrypt');
	}
	function insert_user(){
        $this->title   = $_POST['title']; // please read the below note
        $this->content = $_POST['content'];
        $this->date    = time();

        $this->db->insert('red_members', $this);
    }
	
	function create_user($input_array){
	// GET Cookie for publisher and check it is set or not
		$siteid = $this->encrypt->decode($this->input->cookie('rc_ls_site_id'));
		$time_entered = $this->encrypt->decode($this->input->cookie('rc_ls_added_on'));
		If ($siteid != "") {
			$input_array['ls_site_id'] = $siteid;
			$input_array['ls_added_on'] = $time_entered;
		}
		$this->db->insert('red_members',$input_array);
		return $this->db->insert_id();
	}
	
	function update_user($input_array,$conditions_array){
		$this->db->update('red_members',$input_array,$conditions_array);
		return $this->db->affected_rows();
	}
	
	//Delete function, actually updating 'is_deleted' status of table to 1
	function delete_user($user_id=0)
	{
		# Delete user's payment profile from database
		$this->db->delete('red_member_packages',array('member_id'=>$user_id));
		#delete user account from memeber table
		$this->db->delete('red_members',array('member_id'=>$user_id));
	}
	
	function get_user_data($conditions_array,$rows_per_page=10,$start=0,$package_join=false){
		$rows=array();
		if($_POST['mode']=='search' AND !isset($_POST['btn_cancel'])){
			if($_POST['field_name']){
				$fld_value= trim($_POST['field_value']);				
				if($_POST['field_name']=="status"){
					$fld_value=$_POST['select_status'];					
					if($fld_value=="Active-Paid"){
						$this->db->join('red_member_packages as rmp','rmp.member_id =rm.member_id');
						$this->db->join('red_packages as rp','rp.package_id =rmp.package_id');
						$this->db->where('`rm.'.$_POST['field_name'].'`','active');
						$this->db->where('`rmp.package_id` >','0');
						$this->db->where('`rmp.is_admin`','0');
						$this->db->where('DATEDIFF(next_payement_date,CURDATE())<=','30');
						$this->db->where('DATEDIFF(next_payement_date,CURDATE())>=','0');
					}else if($fld_value=="Admin-comped"){
						$this->db->join('red_member_packages as rmp','rmp.member_id =rm.member_id');
						$this->db->join('red_packages as rp','rp.package_id =rmp.package_id');
						$this->db->where('`rm.'.$_POST['field_name'].'`','active');
						$this->db->where('`rmp.package_id` >','0');
						$this->db->where('`rmp.is_admin`','1'); 
					}else if($fld_value=="Active-Free"){						
						$this->db->join('red_member_packages as rmp','rmp.member_id =rm.member_id ');
						$this->db->join('red_packages as rp','rp.package_id =rmp.package_id');
						$this->db->where('`rm.'.$_POST['field_name'].'`','active');
						$this->db->where('`rmp.package_id` <=','0');
						$this->db->where('`rmp.is_admin`','0');
					}else if($fld_value=="Inactive-Policy related"){
						$this->db->where('`rm.'.$_POST['field_name'].'`','inactive');
						$this->db->where('`rm.status_inactive_description`','policy related');
					}else if($fld_value=="Inactive- Unconfirmed"){
						$this->db->where('`rm.'.$_POST['field_name'].'`','inactive');
						$this->db->where('`rm.status_inactive_description`','unconfirmed');
					}else if($fld_value=="Inactive- Failed CC"){						
						$this->db->join('red_member_packages as rmp','rmp.member_id =rm.member_id');
						$this->db->join('red_packages as rp','rp.package_id =rmp.package_id');
						$this->db->where('`rm.'.$_POST['field_name'].'`','active');
						$this->db->where('`rmp.package_id` >','0');
						$this->db->where('`rmp.is_admin`','0');
						$this->db->where('DATEDIFF(next_payement_date,CURDATE())<','0');
					}
				}else if($_POST['field_name']=="member_id"){
					$this->db->where('`rm.member_id`',$fld_value);	
				}else if($_POST['field_name']!="package_id"){
					$this->db->like('`rm.'.$_POST['field_name'].'`',$fld_value);				
				}else{
					$this->db->join('red_member_packages as rmp','rmp.member_id =rm.member_id');
					$this->db->join('red_packages as rp','rp.package_id =rmp.package_id');					
					$fld_value=$_POST['select_package'];
					$this->db->where('`rp.'.$_POST['field_name'].'`',$fld_value);
				}
			}
		}else if($package_join){
			$this->db->join('red_member_packages as rmp','rmp.member_id =rm.member_id');
			$this->db->join('red_packages as rp','rp.package_id =rmp.package_id');	
		}
		$this->db->from('red_members as rm');
		$this->db->join('red_countries as rc','rm.country=rc.country_id');
		$this->db->where($conditions_array);
		$this->db->limit($rows_per_page,$start);
		$this->db->order_by('rm.member_id','desc');
		$result=$this->db->get();
		 
		foreach($result->result_array() as $row)
		{
			$rows[]=$row;
		}
		$result->free_result(); 
		return $rows;
	
	}
	// get paid user detail
	
	//Function to fetch Users count
	function get_user_count($conditions_array=array(),$package_join=false)
	{
		$this->db->select('count(*) as count');
		if($_POST['mode']=='search' AND !isset($_POST['btn_cancel'])){
			if($_POST['field_name']){
				$fld_value=$_POST['field_value'];				
				if($_POST['field_name']=="status"){
					$fld_value=$_POST['select_status'];						
					if($fld_value=="Active-Paid"){
						$this->db->join('red_member_packages as rmp','rmp.member_id =rm.member_id ');
						$this->db->join('red_packages as rp','rp.package_id =rmp.package_id');
						$this->db->where('`rm.'.$_POST['field_name'].'`','active');
						$this->db->where('`rmp.package_id` >','0');
						$this->db->where('`rmp.is_admin`','0');
						$this->db->where('DATEDIFF(next_payement_date,CURDATE())<=','30');
						$this->db->where('DATEDIFF(next_payement_date,CURDATE())>=','0');
					}else if($fld_value=="Active-Free"){						
						$this->db->join('red_member_packages as rmp','rmp.member_id =rm.member_id ');
						$this->db->join('red_packages as rp','rp.package_id =rmp.package_id');
						$this->db->where('`rm.'.$_POST['field_name'].'`','active');
						$this->db->where('`rmp.package_id` <=','0');
						$this->db->where('`rmp.is_admin`','0');
					}else if($fld_value=="Inactive-Policy related"){
						$this->db->where('`rm.'.$_POST['field_name'].'`','inactive');
						$this->db->where('`rm.status_inactive_description`','policy related');
					}else if($fld_value=="Inactive- Unconfirmed"){
						$this->db->where('`rm.'.$_POST['field_name'].'`','inactive');
						$this->db->where('`rm.status_inactive_description`','unconfirmed');
					}else if($fld_value=="Inactive- Failed CC"){						
						$this->db->join('red_member_packages as rmp','rmp.member_id =rm.member_id');
						$this->db->join('red_packages as rp','rp.package_id =rmp.package_id');
						$this->db->where('`rm.'.$_POST['field_name'].'`','active');
						$this->db->where('`rmp.package_id` >','0');
						$this->db->where('`rmp.is_admin`','0');
						$this->db->where('DATEDIFF(next_payement_date,CURDATE())<','0');
					}
				}else if($_POST['field_name']!="package_id"){
					$this->db->like('`rm.'.$_POST['field_name'].'`',$fld_value);
				}else{
					$this->db->join('red_member_packages as rmp','rmp.member_id =rm.member_id');
					$this->db->join('red_packages as rp','rp.package_id =rmp.package_id');					
					$fld_value=$_POST['select_package'];
					$this->db->where('`rp.'.$_POST['field_name'].'`',$fld_value);
				}
			}
		}else if($package_join){
			$this->db->join('red_member_packages as rmp','rmp.member_id =rm.member_id');
			$this->db->join('red_packages as rp','rp.package_id =rmp.package_id');	
		}
		
		$this->db->from('red_members as rm');
		$this->db->join('red_countries as rc','rm.country=rc.country_id');
		
		$this->db->where($conditions_array);
		$result=$this->db->get();
		
		$row=$result->result_array() ;
		$result->free_result();
		return $row[0]['count'];
	}
	function get_user_package($conditions_array,$rows_per_page=10,$start=0){
		$rows=array();		
		$this->db->from('red_member_packages as rmp');
		$this->db->join('red_packages as rp','rp.package_id =rmp.package_id');
		$this->db->where($conditions_array);
		$this->db->limit($rows_per_page,$start);
		$result=$this->db->get();
		foreach($result->result_array() as $row)
		{
			$rows[]=$row;
		}
		return $rows;
	}
	function get_packages_data($conditions_array,$rows_per_page=10,$start=0)
	{
		$rows=array();
		//$result=$this->db->order_by("package_price", "asc")->get_where('red_packages',$conditions_array,$rows_per_page,$start);
		$result=$this->db->order_by("package_price asc,is_special asc ,package_recurring_interval asc")->get_where('red_packages',$conditions_array,$rows_per_page,$start);
		  
		foreach($result->result_array() as $row){
			$pid = $row['package_id'];
			$row['total_members'] = $this->packageUserCount($pid);
			$row['total_transactions'] = $this->packageTransactionCount($pid);			
			$rows[]=$row;
		}
		return $rows;
	}
	function packageUserCount($pid){
		$rsPackageUsers = $this->db->query("Select count(member_id) m from red_member_packages where package_id='$pid'");
		$packageUsers = $rsPackageUsers->row()->m;
		$rsPackageUsers->free_result();
		return $packageUsers;
	}
	function packageTransactionCount($pid){
		$rsPackageTransactions = $this->db->query("Select count(transaction_id) t from red_member_transactions where package_id='$pid'");
		$packageTransactions = $rsPackageTransactions->row()->t;
		$rsPackageTransactions->free_result();
		return $packageTransactions;
	}
	
	function get_packages_data_special($conditions_array,$rows_per_page=10,$start=0)
	{
		$rows=array();
		$result=$this->db->order_by("package_price", "asc")->get_where('red_packages',$conditions_array,$rows_per_page,$start);		
		 
		foreach($result->result_array() as $row)
		{
			$rows[]=$row;
		}
		return $rows;
	}
	function get_current_packages_maxcontact($mid){
		$package_id = $this->db->query("select `package_id` from `red_member_packages` where `member_id`='$mid'")->row()->package_id;
		
		if($package_id > 0){
			$package_max_contacts = $this->db->query("select `package_max_contacts` from `red_packages` where `package_id`='$package_id'")->row()->package_max_contacts;
			if($package_max_contacts > 0)return $package_max_contacts;
			exit;
		}	
		return 100;
	}
	function get_user_plan_status($mid){
		$rs_package_id = $this->db->query("select `package_id` from `red_member_packages` where `member_id`='$mid' and package_id > 0 and (DATE_ADD(next_payement_date,INTERVAL 1 DAY) > now()  or is_admin=1)");	
		if($rs_package_id->num_rows() > 0){
			$package_id = $rs_package_id->row()->package_id;
			$package_max_contacts = $this->db->query("select `package_max_contacts` from `red_packages` where `package_id`='$package_id'")->row()->package_max_contacts;
			if($package_max_contacts > 0)return $package_max_contacts;
			exit;
		}	
		return 100;
	}	
 
	
	//Function to fetch packages count
	function get_packages_count($conditions_array=array())
	{
		$this->db->where($conditions_array);
		return $this->db->count_all_results('red_packages');
	}
	
	function create_package($input_array)
	{
		$this->db->insert('red_packages',$input_array);
		return $this->db->insert_id();
	}
	
	function update_package($input_array,$conditions_array)
	{
		$this->db->update('red_packages',$input_array,$conditions_array);
		return $this->db->affected_rows();
	}
	
	//Delete function, actually updating 'package_deleted' status of table to 1
	function delete_package($conditions_array)
	{
		$this->db->update('red_packages',array('package_deleted'=>1),$conditions_array);
		return $this->db->affected_rows();
	}
	
	function insert_payment_transactions($input_array)
	{
		$this->db->insert('red_member_transactions',$input_array);
		return $this->db->insert_id();
	} 
	// Start code - CB 
	function update_payment_transactions($input_array,$conditionArray)
	{
		$this->db->update('red_member_transactions',$input_array,$conditionArray);
		return $this->db->affected_rows();
	} 
	// End:  code - CB 
	function insert_member_package($input_array)
	{
		$mid = $input_array['member_id'];
		if(isset($mid) && $mid > 0){
			$mp_id = $this->db->query("Select `red_member_package_id` from `red_member_packages` where `member_id`='$mid'")->row()->red_member_package_id;		
		}
		if(isset($mp_id) && $mp_id > 0){
			$this->db->update('red_member_packages',$input_array, array('red_member_package_id'=>$mp_id));
			return $this->db->affected_rows();
		}else{
			$this->db->insert('red_member_packages',$input_array);
			return $this->db->insert_id();
		}
	}
	
	function update_member_package($input_array,$conditions_array)
	{
		$this->db->update('red_member_packages',$input_array,$conditions_array);
		
		return $this->db->affected_rows();
	}
       
        
        
	
	function get_user_packages($conditions_array,$rows_per_page=10,$start=0)
	{
		$rows=array();
		 
		$result=$this->db->get_where('red_member_packages',$conditions_array,$rows_per_page,$start);
		foreach($result->result_array() as $row)
		{
			$rows[]=$row;
		}
		
		return $rows;
	}
	

    function get_user_packages_with_canceldt($conditions_array,$rows_per_page=10,$start=0)
	{
		$rows=array();
		$this->db->select('m.cancel_subscription_date,p.*');
		$this->db->join('red_members as m','m.member_id =p.member_id');
		$result=$this->db->get_where('red_member_packages p',$conditions_array,$rows_per_page,$start);
		foreach($result->result_array() as $row)
		{
			$rows[]=$row;
		}
		
		return $rows;
	}

    


	function get_user_packages_count($conditions_array=array())
	{
		$this->db->where($conditions_array);
		return $this->db->count_all_results('red_member_packages');
	}
	// Start:  code - CB 
    function get_user_transaction_count($conditions_array=array())
	{
		$this->db->where($conditions_array);
		return $this->db->count_all_results('red_member_transactions');
	}
	// End:  code - CB 
	function get_member_packages_count($conditions_array=array())
	{
		$this->db->where($conditions_array);
		$this->db->join('red_members as m','m.member_id =rp.member_id');
		return  $this->db->count_all_results('red_member_packages as rp');
	}
	
	function get_user_packages_with_details($conditions_array,$rows_per_page=10,$start=0,$sort_by='package_type')
	{
		$rows=array();
		$this->db->from('red_member_packages as rmp');
		$this->db->join('red_packages as rp','rmp.package_id=rp.package_id');
		$this->db->where($conditions_array);
		$this->db->limit($rows_per_page,$start);
		$this->db->order_by($sort_by);
		$result=$this->db->get();
		
		foreach($result->result_array() as $row)
		{
			$rows[]=$row;
		}
		return $rows;
	}
	
	function get_user_packages_with_details_count($conditions_array,$rows_per_page=10,$start=0)
	{
		$this->db->select('count(*) as count');
		$this->db->from('red_member_packages');
		$this->db->join('red_packages','red_member_packages.package_id=red_packages.package_id');
		$this->db->where($conditions_array);
		$this->db->limit($rows_per_page,$start);
		$result=$this->db->get();
		
		$row=$result->result_array() ;
		
		return $row[0]['count'];
	}
	
	//Delete function, actually updating 'is_deleted' status of table to 1
	function delete_user_package($conditions_array)
	{
		$this->db->update('red_member_packages',array('is_deleted'=>1),$conditions_array);
		return $this->db->affected_rows();
	}
	//Fetch vrdit card info
	function get_user_credit_card_info($conditions_array){
		$rows=array();
		$this->db->from('red_members as m');
		// $this->db->join('red_member_packages as rmp','rmp.red_member_package_id=m.package_id');
		$this->db->join('red_member_packages as rmp','rmp.member_id=m.member_id');
		$this->db->join('red_packages as rp','rmp.package_id=rp.package_id');
		$this->db->where($conditions_array);
		$result=$this->db->get();
		
		foreach($result->result_array() as $row)
		{
			$rows[]=$row;
		}
		return $rows;
	}
	
	//Fetch user transaction form user table
	function get_user_transactions($conditions_array,$rows_per_page=10,$start=0,$like=""){
		$rows=array();
		$this->db->select('rmp.*,m.company,m.phone_number,m.email_address,t.*,rp.*');
		$this->db->from('red_member_transactions as t');
		$this->db->join('red_member_packages as rmp','rmp.member_id=t.user_id');
		$this->db->join('red_members as m','m.member_id=t.user_id');
		$this->db->join('red_packages as rp','t.package_id=rp.package_id');
		$this->db->where($conditions_array);
		if($rows_per_page!=0){
			$this->db->limit($rows_per_page,$start);		 
		} 
		$this->db->order_by('t.transaction_id','desc');
		
		if($like){			
			
			$this->db->where('t.status = "SUCCESS"');
			#$this->db->like('t.gateway_response',"Ok,I00001,Successful");
		}
		#echo $this->db->_compile_select(); 
		$result=$this->db->get();
#		echo $this->db->last_query();
		foreach($result->result_array() as $row)
		{
			$rows[]=$row;
		}
		return $rows;
	}
	//Fetch user transaction form user table
	function get_transaction_count($conditions_array,$like=""){
		$rows=array(); 
		$this->db->select('count(*) as count');
		$this->db->from('red_member_transactions as t');
		$this->db->join('red_member_packages as rmp','rmp.member_id=t.user_id');
		$this->db->join('red_members as m','m.member_id=t.user_id');
		$this->db->join('red_packages as rp','t.package_id=rp.package_id');
		$this->db->where($conditions_array);
		//$this->db->order_by('t.transaction_id','desc');
		
		if($like){
			$this->db->where('t.gateway_response like"1,%"');//'t.gateway_response',"Admin"
		}
		//$this->db->group_by('user_id');
		$result=$this->db->get();
		
		$row=$result->result_array() ;
		
		return $row[0]['count'];
	}
	/**
		Function to get countries
	**/
	function get_country_data()
	{
		$rows=array();
		$result=$this->db->get_where('red_countries');
		foreach($result->result_array() as $row)
		{
			$rows[]=$row;
		}
		return $rows;
	
	}
	
	/**
	 *	Function get_subscriber_data
	 *
	 *	Function to fetch subscriber data
	 *
	 *	@param (array) (conditions_array)  conditions to checked with database with conditions
	 *
	 *	@param (string) (srch) to search records according to search condition submit by user
	 *
	 *	@param (string) (order_by)  define order by "Asc" or "Desc"
	 *
	 *	@param (string) (order_by_column)  // define order by column name
	 *
	 *	@param (int) (rows_per_page)  number of record per page
	 *
	 *	@param (int) (start)  These determine which number to start the record
	 *
	 *	@return (array)	return fetch records
	 */
	function get_subscriber_data($conditions_array=array(),$rows_per_page=10,$start=0)
	{
		$rows=array();
		$this->db->select('*');
		$this->db->from('red_email_subscribers as res');
		if($_POST['mode']=='search' AND !isset($_POST['btn_cancel'])){
				if($_POST['subscriber_email_address']){
					$subscriber_email_address=$_POST['subscriber_email_address'];
					$this->db->like('res.subscriber_email_address',$subscriber_email_address);
				}
				if($_POST['subscriber_name']){
					$subscriber_name=$_POST['subscriber_name'];
					$this->db->like('subscriber_first_name',$subscriber_name);
					$this->db->or_like('subscriber_last_name',$subscriber_name);
				}
				if($_POST['keyword']!=""){
					$keyword=$this->is_authorized->escape_str($_POST['keyword'],true);
					$this->db->where('(`subscriber_email_address` LIKE \'%'.$keyword.'%\' OR `subscriber_first_name` LIKE \'%'.$keyword.'%\' OR `subscriber_last_name` LIKE \'%'.$keyword.'%\')');
				}
		}
		$where = "( subscrber_bounce='0' OR ( subscrber_bounce='1' AND soft_bounce <=3 ) )";
		$this->db->where($where);		
		$this->db->where($conditions_array);
		$this->db->order_by('res.subscriber_email_address');
		$this->db->limit($rows_per_page, $start);		
		$result=$this->db->get();
		foreach($result->result_array() as $row)
		{
			$rows[]=$row;
		}
		return $rows;
	}
	
	
	/**
	 *	Function get_subscription_count
	 *
	 *	Function to fetch count of subscriber data
	 *
	 *	@param (array) (conditions_array)  conditions to checked with database with conditions
	 *
	 *	@param (string) (srch) to search records according to search condition submit by user
	 *
	 *	@return (int)	return total number of records
	 */
	function get_subscriber_count($conditions_array=array())
	{
		$rows=array();		
		$this->db->from('red_email_subscribers as res');
		$where = "( subscrber_bounce='0' OR ( subscrber_bounce='1' AND soft_bounce <=3 ) ) and (subscriber_status = 1)";
		#$where = "( subscrber_bounce='0' OR ( subscrber_bounce='1' AND soft_bounce <=3 ) ) ";
		$this->db->where($where);
		if($_POST['mode']=='search' AND !isset($_POST['btn_cancel'])){
				if($_POST['subscriber_email_address']){
					$subscriber_email_address=$_POST['subscriber_email_address'];
					$this->db->like('res.subscriber_email_address',$subscriber_email_address);
				}
				if($_POST['subscriber_name']){
					$subscriber_name=$_POST['subscriber_name'];
					$this->db->like('subscriber_first_name',$subscriber_name);
					$this->db->or_like('subscriber_last_name',$subscriber_name);
				}
				if($_POST['keyword']!=""){
					$keyword=$this->is_authorized->escape_str($_POST['keyword'],true);
					$this->db->where('(`subscriber_email_address` LIKE \'%'.$keyword.'%\' OR `subscriber_first_name` LIKE \'%'.$keyword.'%\' OR `subscriber_last_name` LIKE \'%'.$keyword.'%\')');
				}
		}		
		
		$this->db->where($conditions_array);
		
		$result=$this->db->get();		
		
		foreach($result->result_array() as $row)
		{
			$rows[]=$row;
		}
		
		return count($rows);
	}
	
	/**
		Function delete_user_account is to delete  user's: Stats, campaigns, sign up forms, contacts form database
		@param user_id: user id
	*/
	function delete_user_account($user_id=0){
		#cancel user's subscription
		$this->cancel_subscription($user_id);
		#Delete user's stat
		$this->delete_stat($user_id);
		#Delete user's campaigns
		$this->delete_campaign($user_id);
		#Delete user's autoresponders
		$this->delete_autoresponder($user_id);
		#Delete user's signup forms
		$this->delete_signup_forms($user_id);
		#Delete user's contacts from database
		$this->delete_contacts($user_id);
	}
	/**
		Function delete_stat is to delete user's stat from database
		@param user_id: user id
	*/
	function delete_stat($user_id=0){
		##########################################
		# Delete Campaign's stat from database	 #
		##########################################
		# Delete user's stat from email track table
		$this->db->delete('red_email_track',array('user_id'=>$user_id));
		 
		# Delete user's stat from email track queue table
		$this->db->delete('red_email_queue',array('user_id'=>$user_id));
		 
		#Fetch autoresponder for user
		$rows=array();
		$this->db->select('campaign_id');
		$this->db->from('red_email_campaigns');
		$this->db->where(array('campaign_created_by'=>$user_id));
		$result=$this->db->get();		
		foreach($result->result_array() as $row)
		{
			# Delete user's stat from email track list table
			$this->db->delete('red_email_campaigns_scheduled',array('campaign_id'=>$row['campaign_id']));
		}
		
		# Delete user's click link detail from click rate table
		$this->db->delete('red_click_rate',array('user_id'=>$user_id));
		#Delete user's stat from email track archive table
		$this->db->delete('red_email_track_freezed',array('user_id'=>$user_id));
		##############################################
		# Delete Autoresponder's stat from database	 #
		##############################################
		# Delete user's stat from autoresponder signup table
		$this->db->delete('red_autoresponder_signup',array('subscriber_created_by'=>$user_id));
		#Fetch autoresponder for user
		$rows=array();
		$this->db->select('autoresponder_scheduled_id');
		$this->db->from('red_email_autoresponders');
		$this->db->where(array('campaign_created_by'=>$user_id));
		$result=$this->db->get();		
		foreach($result->result_array() as $row)
		{
			# Delete user's stat from autoresponder scheduled table
			$this->db->delete('red_autoresponder_scheduled',array('autoresponder_scheduled_id'=>$row['autoresponder_scheduled_id']));
		}
	}
	/**
		Function delete_campaign is to delete user's campaigns from database
		@param user_id: user id
	*/
	function delete_campaign($user_id=0){
		#Fetch campaigns for user
		$rows=array();
		$this->db->select('campaign_id','rcp.id');
		$this->db->from('red_email_campaigns');
		$this->db->join('red_email_campaigns_pages as rcp','rcp.site_id=campaign_id AND is_autoresponder=1');
		$this->db->where(array('campaign_created_by'=>$user_id));
		$result=$this->db->get();		
		foreach($result->result_array() as $row)
		{
			# Delete block' content from database
			$this->db->delete('red_email_campaigns_background_color_block_content',array('red_background_color_page_id'=>$row['id']));
			# Delete campaign's pages from datbase
			$this->db->delete('red_email_campaigns_pages',array('site_id'=>$row['campaign_id'],'is_autoresponder'=>0));
		}
		# Delete image bank images from database
		$this->db->delete('red_image_bank',array('img_user_id'=>$user_id));
		# Delete color's theme from databse
		$this->db->delete('red_email_campaigns_color_themes',array('member_id'=>$user_id));
		# delete user's campaigns from database
		$this->db->delete('red_email_campaigns',array('campaign_created_by'=>$user_id));
	}
	/**
		Function delete_autoresponder is to delete user' autoresponders from database
		@param user_id: user id
	*/
	function delete_autoresponder($user_id=0){
		#Fetch campaigns for user
		$rows=array();
		$this->db->select('campaign_id','rcp.id');
		$this->db->from('red_email_autoresponders');
		$this->db->join('red_email_campaigns_pages as rcp','rcp.site_id=campaign_id AND is_autoresponder=1');
		$this->db->where(array('campaign_created_by'=>$user_id));
		$result=$this->db->get();		
		foreach($result->result_array() as $row)
		{
			# Delete block' content from database
			$this->db->delete('red_email_campaigns_background_color_block_content',array('red_background_color_page_id'=>$row['id']));
			# Delete campaign's pages from datbase
			$this->db->delete('red_email_campaigns_pages',array('site_id'=>$row['campaign_id'],'is_autoresponder'=>1));
		}
		# Delete image bank images from database
		$this->db->delete('red_image_bank',array('img_user_id'=>$user_id));
		# Delete color's theme from databse
		$this->db->delete('red_email_campaigns_color_themes',array('member_id'=>$user_id));
		# Delete user's campaigns from database
		$this->db->delete('red_email_autoresponders',array('campaign_created_by'=>$user_id));
		# Delete user's autoresponder group from database
		$this->db->delete('red_autoresponder_group',array('autoresponder_created_by'=>$user_id));
	}
	/**
		Function delete_signup_forms to delete signup forms from database
		@param user_id: user id
	*/
	function delete_signup_forms($user_id=0){
		# Delete user's signup form from databse
		$this->db->delete('red_signup_form',array('member_id'=>$user_id));
	}
	/**
		Function delete_contacts is to delete user's subscription list, subscribers from database
		@param user_id: user id
	*/
	function delete_contacts($user_id=0){
		#Fetch subscription list for user
		$rows=array();
		$this->db->select('subscription_id');
		$this->db->from('red_email_subscriptions');
		$this->db->where(array('subscription_created_by'=>$user_id));
		$result=$this->db->get();		
		foreach($result->result_array() as $row)
		{
			# Delete user's subscribers id from databse
			$this->db->delete('red_email_subscription_subscriber',array('subscription_id'=>$row['subscription_id']));
		}
		# Delete user's subscribers info from database
		$this->db->delete('red_email_subscribers',array('subscriber_created_by'=>$user_id));
		# Delete user's subscriptions list from database
		$this->db->delete('red_email_subscriptions',array('subscription_created_by'=>$user_id));
	}
	/**
		Function cancel_subscription is to delete coustomer profile and payment profile from CIM
		@param user_id: user id
	*/
	function cancel_subscription($user_id=0){
		global $CI;
        $CI =& get_instance();
        
		//collect config varibales
        $this->loginname    	 = $CI->config->item('loginname');	#Collect loginame
        $this->transactionkey    = $CI->config->item('transactionkey');	#Collect transactionkey
		
		$this->load->library('Billingcim'); # load billing library		
		$this->billingcim->loginKey($this->loginname, $this->transactionkey, $CI->config->item('test_mode'));
		#Fetch subscription list for user
		$rows=array();
		$this->db->select('customer_profile_id,customer_payment_profile_id');
		$this->db->from('red_member_packages');
		$this->db->where(array('member_id'=>$user_id));
		$result=$this->db->get();		
		foreach($result->result_array() as $row)
		{
			if($row['customer_profile_id']>0){
				# Merchant-assigned reference ID for the request
				$this->billingcim->setParameter('refId', "ref_".$user_id); // Up to 20 characters (optional)			
				# Payment gateway assigned ID associated with the customer profile
				$this->billingcim->setParameter('customerProfileId', $row['customer_profile_id']); // Numeric (required)		
				# Payment gateway assigned ID associated with the customer payment profile
				$this->billingcim->setParameter('customerPaymentProfileId', $row['customer_payment_profile_id']); // Numeric (required)
				
				# STOPPED ACTUAL DELETION FROM CIM ON 8th August 2012 (next 2 line commented)
				
				#$this->billingcim->deleteCustomerProfileRequest();	#delete customer profile
				#$this->billingcim->deleteCustomerPaymentProfileRequest();	#delete customer payment profile
			}
		}
	}
	/**
		Function get_user_account_info to fetch user's account information
	*/
	function get_user_account_info($conditions_array=array()){
		$rows=array();
		$this->db->select('m.member_id,m.member_username,m.email_address,last_login_time');
		$this->db->from('red_members as m');
		$this->db->join('red_member_packages as rmp','rmp.red_member_package_id=m.package_id');
		$this->db->where($conditions_array);
		$result=$this->db->get();
		
		foreach($result->result_array() as $row)
		{
			$rows[]=$row;
		}
		return $rows;
	}
	/**
		Function get_language_text to fetch languages text from database
	*/
	function get_language_text($conditions_array=array()){
		$rows=array();
		$this->db->from('red_text_translation_languages as l');
		$this->db->where($conditions_array);
		$result=$this->db->get();
		
		foreach($result->result_array() as $row)
		{
			$rows[]=$row;
		}
		return $rows;
	}
	/**
		Function create_language to create language text
	*/
	function create_language($input_array){
		$this->db->insert('red_text_translation_languages',$input_array);
		return $this->db->insert_id();
	}
	/**
		Function get_languages_text to fetch languages from database
	*/
	function get_languages_text($conditions_array=array()){
		$rows=array();
		$this->db->from('red_text_translation_languages as l');
		$this->db->where($conditions_array);
		$result=$this->db->get();

		foreach($result->result_array() as $row)
		{
			$rows[]=$row;
		}
		return $rows;
	}
		
	function lastPaymentStatus(){
		$rows=array();
		$this->db->select('transaction_id, package_id, 	status');
		$this->db->from('red_member_transactions');
	 
		//$this->db->where(array('user_id'=>$this->session->userdata('member_id'),'is_deleted'=>0,'gateway'=>'AUTHORIZE'));
		$this->db->where(array('user_id'=>$this->session->userdata('member_id'),'is_deleted'=>0));
		 
			$this->db->order_by('transaction_id','desc');
		 
		 $this->db->limit(1, 0);	
		#echo $this->db->_compile_select(); 
		
		$result=$this->db->get();
		#echo $this->db->last_query();
		foreach($result->result_array() as $row)
		{
			$rows[]=$row;
		}
		 
		return $rows[0]['status'];
	
	}
	/*
		dateDiff function is for calculating number of days between two dates
	*/
	function dateDiff($dformat, $endDate, $beginDate){
		$date_parts1=explode($dformat, $beginDate);
		$date_parts2=explode($dformat, $endDate);
		$start_date=gregoriantojd($date_parts1[0], $date_parts1[1], $date_parts1[2]);
		$end_date=gregoriantojd($date_parts2[0], $date_parts2[1], $date_parts2[2]);
		return   $end_date - $start_date;
	}
	/**
	- Quota implementation based on selected package
	*/
	
	function updateMemberCampaignQuota($mid, $packageupdateType = 'downgrade'){
		$member_campaign_quota = $this->getMemberCampaignQuota($mid);
		if($packageupdateType == 'downgrade'){
			//$this->update_member_package(array('max_campaign_quota'=>$member_campaign_quota,'campaign_sent_counter'=>0),array('member_id'=>$mid));		
			$this->update_member_package(array('max_campaign_quota'=>$member_campaign_quota),array('member_id'=>$mid));		
		}else{
		$this->update_member_package(array('max_campaign_quota'=>$member_campaign_quota),array('member_id'=>$mid));		
		}
	}
	function getMemberCampaignQuota($mid){
		// We need package_max_contacts, quota_multiplier and user_quota_multiplier for calculation
		$member_campaign_quota = 0;
		$rsUserPackageDetail = $this->db->query("select `package_id`, `user_quota_multiplier` from `red_member_packages` where `member_id`='$mid'");
		if ($rsUserPackageDetail->num_rows() > 0){
			$rowUserPackageDetail = $rsUserPackageDetail->row_array(); 
			$package_id = $rowUserPackageDetail['package_id'];  
			$user_quota_multiplier = $rowUserPackageDetail['user_quota_multiplier'];  
			
			$member_campaign_quota = $this->get_package_quota($package_id) * $user_quota_multiplier;
		}	
		return $member_campaign_quota;
	}
	function get_package_quota($pid){
		$pid= intval ($pid);
		$pid = ($pid == 0)?-1:$pid;
		$max_quota =0;
		$sqlPackageQuota = "select (`package_max_contacts` * `quota_multiplier`) as max_quota from red_packages where `package_id`='$pid'";
		 
		$result=$this->db->query($sqlPackageQuota);
		if ($result->num_rows() > 0){
			$row = $result->row_array(); 
			$max_quota = $row['max_quota'];   
		}
		return $max_quota;
	}
	function incrementCampaignSentCounter($mid, $cid){
		$result= $this->db->query("select count(queue_id) as totcampaign from `red_email_track` where `campaign_id`='$cid'");
		 
		if ($result->num_rows() > 0){
			$row = $result->row_array(); 
			$totCampaignSent = $row['totcampaign'];  
			$this->db->query("update `red_member_packages` set `campaign_sent_counter`=(`campaign_sent_counter` + $totCampaignSent) where `member_id`='$mid'");
		}
	
	}
	function getRemainingCampaignSendingQuota($mid){
		$intRemainingCampaignSendingQuota = 0;
		$rsUserQuotaDetail = $this->db->query("select `max_campaign_quota`, `campaign_sent_counter` from `red_member_packages` where `member_id`='$mid'");
		if ($rsUserQuotaDetail->num_rows() > 0){
			$rowUserQuotaDetail = $rsUserQuotaDetail->row_array(); 
			$max_campaign_quota = $rowUserQuotaDetail['max_campaign_quota'];  
			$campaign_sent_counter = $rowUserQuotaDetail['campaign_sent_counter'];  
			
			$intRemainingCampaignSendingQuota = $max_campaign_quota - $campaign_sent_counter;
		}
		$rsContactsinQueue = $this->db->query("select count(`subscriber_id`) as queue from `red_email_queue` where `user_id`='$mid'");
		$intContactsInqueue = $rsContactsinQueue->row()->queue;
		return $intRemainingCampaignSendingQuota - $intContactsInqueue;
	}
	
	/**
	* Dashboard stats display function
	* to show total paid users yet from begining
	*/
	function get_paid_user_from_beginning(){
		return $this->db->query("select count( distinct user_id) as usr from  red_member_transactions t1 inner join red_members t2 on t1.user_id=t2.member_id where gateway='AUTHORIZE' and t1.status='SUCCESS'")->row()->usr;
		
	}
	/**
	* Dashboard stats display function
	* to show total paid months for all the users yet from begining
	*/
	function get_avg_subscription_lifetime(){
		$this->db->select('count(transaction_id) as count');
		$this->db->from('red_member_transactions');
		$this->db->where(array('gateway'=>'AUTHORIZE','status'=>'SUCCESS'));		
		$result=$this->db->get();		
		$row=$result->result_array() ;
		
		return $row[0]['count'];
	}
	/**
	* User Panel - Dashboard Extra 
	* Function to check API Key
	*/
	function get_user_api(){		
		$this->db->from('red_member_api');
		$this->db->where(array('member_id'=>$this->session->userdata('member_id')));		
		$result=$this->db->get();	
		if ($result->num_rows() > 0){		
			$row=$result->result_array();		
			return $row[0];
		}else{
			return null;
		}	
	}
	/**
	* Attach message with member
	*/
	function attachMessage($input_array){
		$this->db->replace_into('red_member_message',$input_array);		
		return $this->db->affected_rows();	
	}
	/**
	* Detach message with member
	*/
	function detachMessage($input_array){
		//$this->db->delete('red_member_message',$input_array);	
		$this->db->update('red_member_message',array('is_deleted'=>1),$input_array);		
		return $this->db->affected_rows();	
	}
	/**
	* Function to show Contact Analysis in Admin panel
	*/
	function contact_analysis_html($mid=0){
		$strTblBody ='';
		
		$rsContactAnalysis = $this->db->query("select * from red_subscriber_analysis where member_id='$mid'");
		foreach($rsContactAnalysis->result_array() as $recContacts){
			$analysis_date = $recContacts['analysis_date'];
			$reanalyse_it = $recContacts['reanalyse_it'];
			$strTblBody .= '<tr><td colspan="8">Analysed on date:'.$analysis_date.' </td>';
			if($reanalyse_it){
			$strTblBody .= '<td colspan="2">Analysis is under process</td></tr>';
			}else{
			$strTblBody .= '<td><a href="javascript:void(0);" onclick="javascript:fnAnalyseNew('.$mid.')">Analyse New Contacts</a> </td>
							<td><a href="javascript:void(0);" onclick="javascript:fnReanalyse('.$mid.')">Re-analyse All Contacts</a> </td>
							</tr>';
			}
			$strTblBody .= '<tr><th>Domain</th><th>Total</th><th>Unique/Fresh</th><th>Repeated</th><th>Responsive</th><th>Un-responsive</th><th>Bounced</th><th>Complaints</th> <th>Un-subscribes</th><th>Role-based/spam</th></tr>';
			
			$strTblBody .= '<tr><td>YAHOO</td><td>'.$recContacts['yahoo_total'].'</td><td>'.$recContacts['yahoo_new'].'</td><td>'.$recContacts['yahoo_existing'].'</td><td>'.$recContacts['yahoo_responsive'].'</td>
							<td>'.$recContacts['yahoo_unresponsive'].'</td>
							<td><a href="javascript:void(0);" class="exp" id="'.$mid.'_yahoo_bounce">'.$recContacts['yahoo_bounce'].'</a>&nbsp;|&nbsp;<a href="javascript:void(0);" class="del" id="'.$mid.'_yahoo_bounce">delete</a>&nbsp;|&nbsp;<a href="javascript:void(0);" class="sup" id="'.$mid.'_sup_yahoo_bounce">suppress</a></td>
							<td><a href="javascript:void(0);" class="exp" id="'.$mid.'_yahoo_complaint">'.$recContacts['yahoo_complaint'].'</a>&nbsp;|&nbsp;<a href="javascript:void(0);" class="del" id="'.$mid.'_yahoo_complaint">delete</a>&nbsp;|&nbsp;<a href="javascript:void(0);" class="sup" id="'.$mid.'_sup_yahoo_complaint">suppress</a></td>
							<td><a href="javascript:void(0);" class="exp" id="'.$mid.'_yahoo_unsubscribe">'.$recContacts['yahoo_unsubscribe'].'</a>&nbsp;|&nbsp;<a href="javascript:void(0);" class="del" id="'.$mid.'_yahoo_unsubscribe">delete</a>&nbsp;|&nbsp;<a href="javascript:void(0);" class="sup" id="'.$mid.'_sup_yahoo_unsubscribe">suppress</a></td>
							<td>'.$recContacts['yahoo_spam'].'</td></tr>';
			
			$strTblBody .= '<tr><td>GMail</td><td>'.$recContacts['gmail_total'].'</td><td>'.$recContacts['gmail_new'].'</td><td>'.$recContacts['gmail_existing'].'</td><td>'.$recContacts['gmail_responsive'].'</td><td>'.$recContacts['gmail_unresponsive'].'</td>
			<td><a href="javascript:void(0);" class="exp" id="'.$mid.'_gmail_bounce">'.$recContacts['gmail_bounce'].'</a>&nbsp;|&nbsp;<a href="javascript:void(0);" class="del" id="'.$mid.'_gmail_bounce">delete</a>&nbsp;|&nbsp;<a href="javascript:void(0);" class="sup" id="'.$mid.'_sup_gmail_bounce">suppress</a></td>
			<td><a href="javascript:void(0);" class="exp" id="'.$mid.'_gmail_complaint">'.$recContacts['gmail_complaint'].'</a>&nbsp;|&nbsp;<a href="javascript:void(0);" class="del" id="'.$mid.'_gmail_complaint">delete</a>&nbsp;|&nbsp;<a href="javascript:void(0);" class="sup" id="'.$mid.'_sup_gmail_complaint">suppress</a></td>
			<td><a href="javascript:void(0);" class="exp" id="'.$mid.'_gmail_unsubscribe">'.$recContacts['gmail_unsubscribe'].'</a>&nbsp;|&nbsp;<a href="javascript:void(0);" class="del" id="'.$mid.'_gmail_unsubscribe">delete</a>&nbsp;|&nbsp;<a href="javascript:void(0);" class="sup" id="'.$mid.'_sup_gmail_unsubscribe">suppress</a></td>
			<td>'.$recContacts['gmail_spam'].'</td></tr>';
			
			$strTblBody .= '<tr><td>HotMail</td><td>'.$recContacts['hotmail_total'].'</td><td>'.$recContacts['hotmail_new'].'</td><td>'.$recContacts['hotmail_existing'].'</td><td>'.$recContacts['hotmail_responsive'].'</td><td>'.$recContacts['hotmail_unresponsive'].'</td>
			<td><a href="javascript:void(0);" class="exp" id="'.$mid.'_hotmail_bounce">'.$recContacts['hotmail_bounce'].'</a>&nbsp;|&nbsp;<a href="javascript:void(0);" class="del" id="'.$mid.'_hotmail_bounce">delete</a>&nbsp;|&nbsp;<a href="javascript:void(0);" class="sup" id="'.$mid.'_sup_hotmail_bounce">suppress</a></td>
			<td><a href="javascript:void(0);" class="exp" id="'.$mid.'_hotmail_complaint">'.$recContacts['hotmail_complaint'].'</a>&nbsp;|&nbsp;<a href="javascript:void(0);" class="del" id="'.$mid.'_hotmail_complaint">delete</a>&nbsp;|&nbsp;<a href="javascript:void(0);" class="sup" id="'.$mid.'_sup_hotmail_complaint">suppress</a></td>
			<td><a href="javascript:void(0);" class="exp" id="'.$mid.'_hotmail_unsubscribe">'.$recContacts['hotmail_unsubscribe'].'</a>&nbsp;|&nbsp;<a href="javascript:void(0);" class="del" id="'.$mid.'_hotmail_unsubscribe">delete</a>&nbsp;|&nbsp;<a href="javascript:void(0);" class="sup" id="'.$mid.'_sup_hotmail_unsubscribe">suppress</a></td>
			<td>'.$recContacts['hotmail_spam'].'</td></tr>';
			
			$strTblBody .= '<tr><td>AOL</td><td>'.$recContacts['aol_total'].'</td><td>'.$recContacts['aol_new'].'</td><td>'.$recContacts['aol_existing'].'</td><td>'.$recContacts['aol_responsive'].'</td><td>'.$recContacts['aol_unresponsive'].'</td>
			<td><a href="javascript:void(0);" class="exp" id="'.$mid.'_aol_bounce">'.$recContacts['aol_bounce'].'</a>&nbsp;|&nbsp;<a href="javascript:void(0);" class="del" id="'.$mid.'_aol_bounce">delete</a>&nbsp;|&nbsp;<a href="javascript:void(0);" class="sup" id="'.$mid.'_sup_aol_bounce">suppress</a></td>
			<td><a href="javascript:void(0);" class="exp" id="'.$mid.'_aol_complaint">'.$recContacts['aol_complaint'].'</a>&nbsp;|&nbsp;<a href="javascript:void(0);" class="del" id="'.$mid.'_aol_complaint">delete</a>&nbsp;|&nbsp;<a href="javascript:void(0);" class="sup" id="'.$mid.'_sup_aol_complaint">suppress</a></td>
			<td><a href="javascript:void(0);" class="exp" id="'.$mid.'_aol_unsubscribe">'.$recContacts['aol_unsubscribe'].'</a>&nbsp;|&nbsp;<a href="javascript:void(0);" class="del" id="'.$mid.'_aol_unsubscribe">delete</a>&nbsp;|&nbsp;<a href="javascript:void(0);" class="sup" id="'.$mid.'_sup_aol_unsubscribe">suppress</a></td>
			<td>'.$recContacts['aol_spam'].'</td></tr>';
			
			$strTblBody .= '<tr><td>MSN</td><td>'.$recContacts['msn_total'].'</td><td>'.$recContacts['msn_new'].'</td><td>'.$recContacts['msn_existing'].'</td><td>'.$recContacts['msn_responsive'].'</td><td>'.$recContacts['msn_unresponsive'].'</td>
			<td><a href="javascript:void(0);" class="exp" id="'.$mid.'_msn_bounce">'.$recContacts['msn_bounce'].'</a>&nbsp;|&nbsp;<a href="javascript:void(0);" class="del" id="'.$mid.'_msn_bounce">delete</a>&nbsp;|&nbsp;<a href="javascript:void(0);" class="sup" id="'.$mid.'_sup_msn_bounce">suppress</a></td>
			<td><a href="javascript:void(0);" class="exp" id="'.$mid.'_msn_complaint">'.$recContacts['msn_complaint'].'</a>&nbsp;|&nbsp;<a href="javascript:void(0);" class="del" id="'.$mid.'_msn_complaint">delete</a>&nbsp;|&nbsp;<a href="javascript:void(0);" class="sup" id="'.$mid.'_sup_msn_complaint">suppress</a></td>
			<td><a href="javascript:void(0);" class="exp" id="'.$mid.'_msn_unsubscribe">'.$recContacts['msn_unsubscribe'].'</a>&nbsp;|&nbsp;<a href="javascript:void(0);" class="del" id="'.$mid.'_msn_unsubscribe">delete</a>&nbsp;|&nbsp;<a href="javascript:void(0);" class="sup" id="'.$mid.'_sup_msn_unsubscribe">suppress</a></td>
			<td>'.$recContacts['msn_spam'].'</td></tr>';
			
			$other_total = ($recContacts['all_total'] - $recContacts['yahoo_total'] - $recContacts['gmail_total'] - $recContacts['hotmail_total'] - $recContacts['msn_total']);
			$total_new = ($recContacts['all_new'] + $recContacts['yahoo_new'] + $recContacts['gmail_new'] + $recContacts['hotmail_new'] + $recContacts['msn_new']+ $recContacts['aol_new']);
			$total_existing = ($recContacts['all_existing'] + $recContacts['yahoo_existing'] + $recContacts['gmail_existing'] + $recContacts['hotmail_existing'] + $recContacts['msn_existing'] + $recContacts['aol_existing']);
			$total_responsive = ($recContacts['all_responsive'] + $recContacts['yahoo_responsive'] + $recContacts['gmail_responsive'] + $recContacts['hotmail_responsive'] + $recContacts['msn_responsive'] + $recContacts['aol_responsive']);
			$total_unresponsive = ($recContacts['all_unresponsive'] + $recContacts['yahoo_unresponsive'] + $recContacts['gmail_unresponsive'] + $recContacts['hotmail_unresponsive'] + $recContacts['msn_unresponsive'] + $recContacts['aol_unresponsive']);
			$total_bounce = ($recContacts['all_bounce'] + $recContacts['yahoo_bounce'] + $recContacts['gmail_bounce'] + $recContacts['hotmail_bounce'] + $recContacts['msn_bounce'] + $recContacts['aol_bounce']);
			$total_complaint = ($recContacts['all_complaint'] + $recContacts['yahoo_complaint'] + $recContacts['gmail_complaint'] + $recContacts['hotmail_complaint'] + $recContacts['msn_complaint'] + $recContacts['aol_complaint']);
			$total_unsubscribe = ($recContacts['all_unsubscribe'] + $recContacts['yahoo_unsubscribe'] + $recContacts['gmail_unsubscribe'] + $recContacts['hotmail_unsubscribe'] + $recContacts['msn_unsubscribe'] + $recContacts['aol_unsubscribe']);
			$total_spam = ($recContacts['all_spam'] + $recContacts['yahoo_spam'] + $recContacts['gmail_spam'] + $recContacts['hotmail_spam'] + $recContacts['msn_spam'] + $recContacts['aol_spam']);
			
			$strTblBody .= '<tr><td>Others</td><td>'.$other_total.'</td><td>'.$recContacts['all_new'].'</td><td>'.$recContacts['all_existing'].'</td><td>'.$recContacts['all_responsive'].'</td><td>'.$recContacts['all_unresponsive'].'</td>
			<td><a href="javascript:void(0);" class="exp" id="'.$mid.'_other_bounce">'.$recContacts['all_bounce'].'</a>&nbsp;|&nbsp;<a href="javascript:void(0);" class="del" id="'.$mid.'_other_bounce">delete</a>&nbsp;|&nbsp;<a href="javascript:void(0);" class="sup" id="'.$mid.'_sup_other_bounce">suppress</a></td>
			<td><a href="javascript:void(0);" class="exp" id="'.$mid.'_other_complaint">'.$recContacts['all_complaint'].'</a>&nbsp;|&nbsp;<a href="javascript:void(0);" class="del" id="'.$mid.'_other_complaint">delete</a>&nbsp;|&nbsp;<a href="javascript:void(0);" class="sup" id="'.$mid.'_sup_other_complaint">suppress</a></td>
			<td><a href="javascript:void(0);" class="exp" id="'.$mid.'_other_unsubscribe">'.$recContacts['all_unsubscribe'].'</a>&nbsp;|&nbsp;<a href="javascript:void(0);" class="del" id="'.$mid.'_other_unsubscribe">delete</a>&nbsp;|&nbsp;<a href="javascript:void(0);" class="sup" id="'.$mid.'_sup_other_unsubscribe">suppress</a></td>
			<td>'.$recContacts['all_spam'].'</td></tr>';
			
			$strTblBody .= '<tr><td>ALL</td><td>'.$recContacts['all_total'].'</td><td>'.$total_new.'</td><td>'.$total_existing.'</td><td>'.$total_responsive.'</td><td>'.$total_unresponsive.'</td>
			<td><a href="javascript:void(0);" class="exp" id="'.$mid.'_all_bounce">'.$total_bounce.'</a>&nbsp;|&nbsp;<a href="javascript:void(0);" class="del" id="'.$mid.'_all_bounce">delete</a>&nbsp;|&nbsp;<a href="javascript:void(0);" class="sup" id="'.$mid.'_sup_all_bounce">suppress</a></td>
			<td><a href="javascript:void(0);" class="exp" id="'.$mid.'_all_complaint">'.$total_complaint.'</a>&nbsp;|&nbsp;<a href="javascript:void(0);" class="del" id="'.$mid.'_all_complaint">delete</a>&nbsp;|&nbsp;<a href="javascript:void(0);" class="sup" id="'.$mid.'_sup_all_complaint">suppress</a></td>
			<td><a href="javascript:void(0);" class="exp" id="'.$mid.'_all_unsubscribe">'.$total_unsubscribe.'</a>&nbsp;|&nbsp;<a href="javascript:void(0);" class="del" id="'.$mid.'_all_unsubscribe">delete</a>&nbsp;|&nbsp;<a href="javascript:void(0);" class="sup" id="'.$mid.'_sup_all_unsubscribe">suppress</a></td>
			<td>'.$total_spam.'</td></tr>';
			
			/*
			$other_existing = ($recContacts['all_existing'] - $recContacts['yahoo_existing'] - $recContacts['gmail_existing'] - $recContacts['hotmail_existing'] - $recContacts['msn_existing'] - $recContacts['aol_existing']);
			$other_responsive = ($recContacts['all_responsive'] - $recContacts['yahoo_responsive'] - $recContacts['gmail_responsive'] - $recContacts['hotmail_responsive'] - $recContacts['msn_responsive'] - $recContacts['aol_responsive']);
			$other_unresponsive = ($recContacts['all_unresponsive'] - $recContacts['yahoo_unresponsive'] - $recContacts['gmail_unresponsive'] - $recContacts['hotmail_unresponsive'] - $recContacts['msn_unresponsive'] - $recContacts['aol_unresponsive']);
			$other_bounce = ($recContacts['all_bounce'] - $recContacts['yahoo_bounce'] - $recContacts['gmail_bounce'] - $recContacts['hotmail_bounce'] - $recContacts['msn_bounce'] - $recContacts['aol_bounce']);
			$other_complaint = ($recContacts['all_complaint'] - $recContacts['yahoo_complaint'] - $recContacts['gmail_complaint'] - $recContacts['hotmail_complaint'] - $recContacts['msn_complaint'] - $recContacts['aol_complaint']);
			$other_unsubscribe = ($recContacts['all_unsubscribe'] - $recContacts['yahoo_unsubscribe'] - $recContacts['gmail_unsubscribe'] - $recContacts['hotmail_unsubscribe'] - $recContacts['msn_unsubscribe'] - $recContacts['aol_unsubscribe']);
			$other_spam = ($recContacts['all_spam'] - $recContacts['yahoo_spam'] - $recContacts['gmail_spam'] - $recContacts['hotmail_spam'] - $recContacts['msn_spam'] - $recContacts['aol_spam']);
			
			
			$strTblBody .= '<tr><td>Others</td><td>'.$other_total.'</td><td>'.$other_new.'</td><td>'.$other_existing.'</td><td>'.$other_responsive.'</td><td>'.$other_unresponsive.'</td>
			<td><a href="javascript:void(0);" class="exp" id="'.$mid.'_other_bounce">'.$other_bounce.'</a>&nbsp;|&nbsp;<a href="javascript:void(0);" class="del" id="'.$mid.'_other_bounce">delete</a>&nbsp;|&nbsp;<a href="javascript:void(0);" class="sup" id="'.$mid.'_sup_other_bounce">suppress</a></td>
			<td><a href="javascript:void(0);" class="exp" id="'.$mid.'_other_complaint">'.$other_complaint.'</a>&nbsp;|&nbsp;<a href="javascript:void(0);" class="del" id="'.$mid.'_other_complaint">delete</a>&nbsp;|&nbsp;<a href="javascript:void(0);" class="sup" id="'.$mid.'_sup_other_complaint">suppress</a></td>
			<td><a href="javascript:void(0);" class="exp" id="'.$mid.'_other_unsubscribe">'.$other_unsubscribe.'</a>&nbsp;|&nbsp;<a href="javascript:void(0);" class="del" id="'.$mid.'_other_unsubscribe">delete</a>&nbsp;|&nbsp;<a href="javascript:void(0);" class="sup" id="'.$mid.'_sup_other_unsubscribe">suppress</a></td>
			<td>'.$other_spam.'</td></tr>';
			
			$strTblBody .= '<tr><td>ALL</td><td>'.$recContacts['all_total'].'</td><td>'.$recContacts['all_new'].'</td><td>'.$recContacts['all_existing'].'</td><td>'.$recContacts['all_responsive'].'</td><td>'.$recContacts['all_unresponsive'].'</td>
			<td><a href="javascript:void(0);" class="exp" id="'.$mid.'_all_bounce">'.$recContacts['all_bounce'].'</a>&nbsp;|&nbsp;<a href="javascript:void(0);" class="del" id="'.$mid.'_all_bounce">delete</a>&nbsp;|&nbsp;<a href="javascript:void(0);" class="sup" id="'.$mid.'_sup_all_bounce">suppress</a></td>
			<td><a href="javascript:void(0);" class="exp" id="'.$mid.'_all_complaint">'.$recContacts['all_complaint'].'</a>&nbsp;|&nbsp;<a href="javascript:void(0);" class="del" id="'.$mid.'_all_complaint">delete</a>&nbsp;|&nbsp;<a href="javascript:void(0);" class="sup" id="'.$mid.'_sup_all_complaint">suppress</a></td>
			<td><a href="javascript:void(0);" class="exp" id="'.$mid.'_all_unsubscribe">'.$recContacts['all_unsubscribe'].'</a>&nbsp;|&nbsp;<a href="javascript:void(0);" class="del" id="'.$mid.'_all_unsubscribe">delete</a>&nbsp;|&nbsp;<a href="javascript:void(0);" class="sup" id="'.$mid.'_sup_all_unsubscribe">suppress</a></td>
			<td>'.$recContacts['all_spam'].'</td></tr>';
			
			*/
		}
		return '<div style="padding:20px;">
		<table cellspacing="0" cellpadding="4" border="1">' .$strTblBody .'</table></div>';
	}
	
}
?>