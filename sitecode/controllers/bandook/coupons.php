<?php
class Coupons extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		if($this->session->userdata('webmaster_username')!='sitemaster')redirect(base_url());

		// Load the user model which interact with database
		$this->load->model('UserModel');
		# HTTPS/SSL enabled
		force_ssl();

	}
	
	function index()
	{
		$this->coupons_list();
	}
	
	function coupons_list($start=0){		
		// Define config parameters for paging like base url, total rows and record per page.
		$config['base_url']=base_url().'bandook/coupons/coupons_list';
		$config['total_rows']=$this->db->query("select count(coupon_id)ct from red_coupons where status='1'")->row()->ct ;
		$config['per_page']=20;
		$config['uri_segment']=4;

		// Initialize paging with above parameters
		$this->pagination->initialize($config);
		
		//Create paging inks
		$paging_links=$this->pagination->create_links();
		$condition = array('status'=>1);
		$coupons=$this->db->get_where('red_coupons',$condition,$config['per_page'],$start)->result_array();		
		
		// Recieve any messages to be shown, when package is added or updated
		$messages=$this->messages->get();
		$logo_link="bandook/dashboard_stat";
		//Loads header, users listing  and footer view.
		$this->load->view('bandook/header',array('title'=>'Manage Coupons','logo_link'=>$logo_link));
		$this->load->view('bandook/coupons_list',array('coupons'=>$coupons,'paging_links'=>$paging_links,'messages' =>$messages));
		$this->load->view('bandook/footer');
	}
	
	function coupon_create(){	
		if($this->input->post('action')=='submit')
		{
			// Validation rules are applied
			$this->form_validation->set_rules('coupon_code', 'Coupon code', 'required|min_length[8]|max_length[40]trim');
			$this->form_validation->set_rules('coupon_value', 'Coupon value', 'required|trim');
			$this->form_validation->set_rules('max_number_of_members', 'No. of members allowed', 'required|trim');
			$this->form_validation->set_rules('usable_number_of_times', 'No. of months', 'required|trim');
			
			// To check form is validated
			if($this->form_validation->run()==true){
				$ccode = $this->input->post('coupon_code',true);
				$cvalue = $this->input->post('coupon_value',true);
				$ctype = $this->input->post('coupon_type',true);
				$member_allowed = $this->input->post('max_number_of_members',true);
				$months_allowed = $this->input->post('usable_number_of_times',true);
				$valid_untill = $this->input->post('valid_untill',true);
			
				//$sqlAddCoupon = "insert into red_coupons set coupon_code='$ccode', coupon_value='$cvalue', coupon_type='$ctype', max_number_of_members='$member_allowed', usable_number_of_times='$months_allowed',valid_untill='$valid_untill', status=1 ";
				$sqlAddCoupon = "insert into red_coupons set coupon_code='$ccode', coupon_value='$cvalue', max_number_of_members='$member_allowed', usable_number_of_times='$months_allowed',valid_untill='$valid_untill', status=1 ";
				$this->db->query($sqlAddCoupon);
				 
				$this->messages->add('Coupon added successfully', 'success');
				redirect('bandook/coupons');
			}
		}
		
		// Recieve any messages to be shown
		$messages=$this->messages->get();
		$logo_link="bandook/coupons";
		//Loads header, users listing  and footer view.
		$this->load->view('bandook/header',array('title'=>'Create New Package','logo_link'=>$logo_link));
		$this->load->view('bandook/coupon_create',array('messages' =>$messages));
		$this->load->view('bandook/footer');
	}
}
?>