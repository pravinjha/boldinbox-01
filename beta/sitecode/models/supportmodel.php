<?php
class SupportModel extends CI_Model
{	
	/**
		Function get_category_data to fetch categories from database
	*/
	function get_category_data($conditions_array=array(),$rows_per_page=10,$start=0)
	{
		$rows=array();
		$this->db->from('red_support_category as rsc');
		$this->db->where($conditions_array);
		$result=$this->db->get();
		foreach($result->result_array() as $row)
		{
			$rows[]=$row;
		}
		return $rows;
	
	}
	/**
		Function get_category_productdata to fetch products from database
	*/
	function get_category_productdata($conditions_array=array(),$rows_per_page=10,$start=0)
	{
		$rows=array();
		$this->db->from('red_support_category as rsc');
		$this->db->join('red_support_product as rsp','rsc.id=rsp.category_id');
		$this->db->where($conditions_array);
		$result=$this->db->get();
		foreach($result->result_array() as $row)
		{
			$rows[]=$row;
		}
		return $rows;
	
	}
	/**
		Function search_product_result to fetch search products
	*/
	function search_product_result($conditions_array=array(),$rows_per_page=10,$start=0){
		$this->db->from('red_support_product as rsp');
		$this->db->where($conditions_array);
		$search_text=($this->input->post('search_text'))? $this->input->post('search_text') : '';		
		$this->db->where("(product like'%$search_text%' or description  like'%$search_text%')");
		$this->db->limit($rows_per_page, $start);		
		$result=$this->db->get();		
		$rows = array();
		if($result->num_rows() > 0){
			foreach($result->result_array() as $row){
				$rows[]=$row;
			}
		}
		return $rows;
	}
	/**
		Function count_search_product_result to count search products
	*/
	function count_search_product_result($conditions_array=array()){				
		$strSearch = '';
		if(trim($this->input->post('search_text')) != ''){
			$strSearchText = trim($this->input->post('search_text'));
			$strSearch = " and  (product like '%$strSearchText%' or description like '%$strSearchText%')";
		}
		$rsCount = $this->db->query("select count(id)x from red_support_product where is_delete=0 and is_active=1 $strSearch");
		return $rsCount->row()->x;
	}	
}
?>