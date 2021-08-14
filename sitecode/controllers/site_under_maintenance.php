<?php
/************About Us class********************/
class Site_under_maintenance extends CI_Controller
{
	function __construct(){
        parent::__construct();
		/* if(($_SERVER["SERVER_NAME"]=="beonlist.com")||($_SERVER["SERVER_NAME"]=="bib1.com")||($_SERVER["SERVER_NAME"]=="www.beonlist.com")||($_SERVER["SERVER_NAME"]=="www.bib1.com")){
			redirect(base_url());
		}	 */	
	}
	function index()
	{
		 
		$this->load->view('site_under_maintenance');
		
	}
}
/* End of file */
?>
