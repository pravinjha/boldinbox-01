<?php
/*
	Model class for blog feed
*/
class Blogfeed_Model extends CI_Model
{
	//Constructor class with parent constructor
	function Blogfeed_Model()
	{
		parent::__construct();
	}
	function getRecentPosts ($category_id=0)
	{
		$this->db->select("rb.*,rc.category_name") ;
        $this->db->order_by('rb.added_on', 'desc');
        $this->db->where('rb.is_deleted', 0);
        $this->db->where('rb.status', 1);
		if($category_id>0){
			$this->db->where('rb.cat_id', $category_id);
		}
		$this->db->join('red_blog_tblcategory as rc','cat_id=rc.id');
        $this->db->limit(10);
        return $this->db->get('red_blog_tblpost as rb');
	} 

}
?>