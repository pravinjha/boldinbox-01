<?php
class Ajax extends CI_Controller {

	function __construct(){
		parent::__construct();
		// if memeber is not login then redirect to login page
		if($this->session->userdata('member_id')=='')
			redirect('user/index');
		$this->load->model('userboard/Campaign_Model');
		$this->load->model('payment/payment_model');
		$this->load->library('upload');
		$this->load->helper('notification');
		
		// Check if folder with modulo of User ID exists on server
		$user_dir = $this->session->userdata('member_id') % 1000;
		
		// Get absolute path for uploading		
		$this->upload_path= $this->config->item('user_public').$user_dir .'/'.$this->session->userdata('member_id');		
	}

	function index(){}
	// Function to save email template logo in directory
	function signup_background_img($bg_type='hbg'){
		$bg_folder = ($bg_type == 'hbg')? 'hbg':'bbg';
			 		
		if(!file_exists($this->upload_path.'/'.$bg_folder)){
			mkdir($this->upload_path.'/'.$bg_folder ,0777 , true);			 
			chmod($this->upload_path.'/'.$bg_folder ,0777);
		}
		if(isset($_FILES[$bg_type.'_file'])) {
			
			$maxsize    = 2097152;
			$acceptable = array('image/jpeg','image/jpg','image/gif','image/png');

			if(($_FILES[$bg_type.'_file']['size'] >= $maxsize) || ($_FILES[$bg_type.'_file']["size"] == 0)) {
				$data1 = array('error' => 'true','error_msg'=> strip_tags('File too large. File must be less than 2 MB.'));
				//die(htmlspecialchars(json_encode($data1), ENT_NOQUOTES));				
				die('err|1');
			}

			if(!in_array($_FILES[$bg_type.'_file']['type'], $acceptable) && (!empty($_FILES[$bg_type.'_file']["type"]))) {
				$data1 = array('error' => 'true','error_msg'=> strip_tags('Invalid file type. Only JPG, GIF and PNG types are accepted.'));
				//die(htmlspecialchars(json_encode($data1), ENT_NOQUOTES));				
				die('err|2');
			}
			// Dont upload faltu files
			$f_content = @file_get_contents($_FILES[$bg_type.'_file']['tmp_name']);
			if(stripos($f_content, "<?php") !== false) {
				@unlink($this->upload_path.$file_name);
				send_mail(SYSTEM_NOTICE_EMAIL_TO, SYSTEM_EMAIL_FROM  ,'system' , SYSTEM_DOMAIN_NAME.': Hacking Attempt',"Signup-HBG image(".$this->upload_path.") having PHP into it tried to upload","Signup-HBG image(".$this->upload_path.") having PHP into it tried to upload");
				$data1 = array('error' => 'true','error_msg'=> strip_tags('The filetype you are attempting to upload is not allowed.'));
				//die(htmlspecialchars(json_encode($data1), ENT_NOQUOTES));				
				die('err|3');
			}
		
		
			$file_name=$_FILES[$bg_type.'_file']['name'];
			
			$logo_ext=substr($file_name,strrpos($file_name,'.'));
			//$logo_name=time().'_'.str_replace(array(' ','&','(',')'),array('_','_and_','',''),$file_name);
			$logo_name=time().$logo_ext;
			
			$file_path=base_url().'asset/user_files/'.$this->session->userdata('member_id').'/'.$bg_folder.'/'.$logo_name;
			
			if(move_uploaded_file($_FILES[$bg_type.'_file']['tmp_name'],$this->upload_path.'/'.$bg_folder.'/'.$logo_name)){			
				 $data1 = array('error' => 'false','file_path' => $file_path);
				 die("noerr|$file_path");
			}else{
				 $data1 = array('error' => 'true','error_msg'=> strip_tags('File could not be uploaded.Try again after sometime.'));
				//die(htmlspecialchars(json_encode($data1), ENT_NOQUOTES));	 
				die('err|4');
			}
		}
		$data1 = array('error' => 'true','error_msg'=> strip_tags('File could not be uploaded.Try again after sometime.'));
		//die(json_encode($data1));			
		die('err|5');
	}
	
	 
 	// Function to save email template logo in directory
	function upload_diy_logo($campaign_type='c',$cid=0){
		$campaign_folder = ($campaign_type == 'c')?	'email_templates' : 'autoresponders';
		
		if(!file_exists($this->upload_path.'/'.$campaign_folder.'/'.$cid.'/uploaded_images')){
			mkdir($this->upload_path.'/'.$campaign_folder.'/'.$cid.'/uploaded_images',0777 , true);
			 
			chmod($this->upload_path.'/'.$campaign_folder.'',0777);
			chmod($this->upload_path.'/'.$campaign_folder.'/'.$cid,0777);
			chmod($this->upload_path.'/'.$campaign_folder.'/'.$cid.'/uploaded_images',0777);
		}
		
		// Dont upload faltu files
		$f_content = @file_get_contents($_FILES['logo_file']['tmp_name']);
		if(stripos($f_content, "<?php") !== false) {			
			send_mail(SYSTEM_NOTICE_EMAIL_TO, SYSTEM_EMAIL_FROM  ,'system' , SYSTEM_DOMAIN_NAME.': Hacking Attempt',"DIY logo file(".$this->upload_path.") having PHP into it tried to upload","DIY logo file(".$this->upload_path.") having PHP into it tried to upload");			
			die('error: The filetype you are attempting to upload is not allowed.');				
		}
		// Dont upload faltu files	
		
		$file_name = $_FILES['logo_file']['name'];
		$logo_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
		$logo_name = 'promo_logo.'.$logo_ext;		
		
		$arrAllowedImg = array("jpg","png","jpeg","gif");
		$response = '';
		if($_FILES['file']['error'] !=''){$response .= "<br> $i. Error - ".$_FILES['file']['error']; }
		if($_FILES['file']['size'] > 2000000){$response .= "<br> $i. Error - File size is more than 2MB"; }
		if( !in_array( $logo_ext, $arrAllowedImg) ){$response .= "<br> $i. Error - File type is not allowed"; }
	 	
		$file_path = base_url().'asset/user_files/'.$this->session->userdata('member_id').'/'.$campaign_folder.'/'.$cid.'/uploaded_images/'.$logo_name;
		
		if(move_uploaded_file($_FILES['logo_file']['tmp_name'],$this->upload_path.'/'.$campaign_folder.'/'.$cid.'/uploaded_images/'.$logo_name))		
			 $response = 'succ'.$file_path;
		else
			 $response = 'error:'.$response;
 
		echo $response;
	}
	
	
	// Function to save email template logo in directory
	function upload_diy_logo_NOT_IN_USE($campaign_type='c',$email_template_id=0){
		if($campaign_type == 'c')
			$campaign_folder = 'email_templates';
		else
			$campaign_folder = 'autoresponders';
	
		// Dont upload faltu files
		$f_content = @file_get_contents($_FILES['logo_file']['tmp_name']);
		if(stripos($f_content, "<?php") !== false) {
			@unlink($upload_path.$file_name);
			send_mail(SYSTEM_NOTICE_EMAIL_TO, SYSTEM_EMAIL_FROM  ,'system' , SYSTEM_DOMAIN_NAME.': Hacking Attempt',"DIY logo file(".$this->upload_path.") having PHP into it tried to upload","DIY logo file(".$this->upload_path.") having PHP into it tried to upload");
			$data1 = array('error' => 'true','error_msg'=> strip_tags('The filetype you are attempting to upload is not allowed.'));
			die(htmlspecialchars(json_encode($data1), ENT_NOQUOTES));				
		}
		// Dont upload faltu files		 
		 $file_name=$_FILES['logo_file']['name'];
		 $this->session->set_userdata('email_template_id', $email_template_id);		
		
		if(!file_exists($this->upload_path.'/'.$campaign_folder.'/'.$this->session->userdata('email_template_id').'/uploaded_images')){
			mkdir($this->upload_path.'/'.$campaign_folder.'/'.$this->session->userdata('email_template_id').'/uploaded_images',0777 , true);
			 
			chmod($this->upload_path.'/'.$campaign_folder.'',0777);
			chmod($this->upload_path.'/'.$campaign_folder.'/'.$this->session->userdata('email_template_id'),0777);
			chmod($this->upload_path.'/'.$campaign_folder.'/'.$this->session->userdata('email_template_id').'/uploaded_images',0777);
		}

		$logo_name='website_logo';
		$logo_ext=substr($file_name,strrpos($file_name,'.'));
		$logo_name=$logo_name.$logo_ext;
	 	
		$file_path=base_url().'asset/user_files/'.$this->session->userdata('member_id').'/'.$campaign_folder.'/'.$this->session->userdata('email_template_id').'/uploaded_images/'.$logo_name;
		
		if(move_uploaded_file($_FILES['logo_file']['tmp_name'],$this->upload_path.'/'.$campaign_folder.'/'.$this->session->userdata('email_template_id').'/uploaded_images/'.$logo_name))
		
			 $data1 = array('error' => 'false','file_path' => $file_path);
		else
			 $data1 = array('error' => 'true');
 
		echo json_encode($data1);			
	}
	
	 
	 // Upload image of image bank
	function upload_image_bank_image(){
		if(!file_exists($this->upload_path.'/image_bank')){
			mkdir($this->upload_path.'/image_bank',0777);
			chmod($this->upload_path.'/image_bank',0777);
		}
		$upload_path= $this->upload_path.'/image_bank/'; 
		$arrAllowedImg = array("jpg","png","jpeg","gif");
		$countfiles = count($_FILES['files']['name']);		
		$response = '';
		//die('Totalfiles ='.$countfiles);
		for($i=0;$i<$countfiles;$i++){
			if(!empty($_FILES['files']['name'][$i])){
				// Define new $_FILES array - $_FILES['file']
				$file_name=$_FILES['files']['name'][$i];
				$file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
				//$file_name=date('YmdHis').'.'.$file_ext;				
				
				
				$file_tmpname= $_FILES['files']['tmp_name'][$i];
				$file_error = $_FILES['files']['error'][$i];
				$file_size = $_FILES['files']['size'][$i];
				
				if($_FILES['file']['error'] !=''){$response .= "<br> $file_name upload error. ".$_FILES['file']['error']; continue;}
				if($_FILES['file']['size'] > 20000000){$response .= "<br> $file_name upload error. File size is more than 2MB"; continue;}
				if(!in_array( $file_ext, $arrAllowedImg) ){$response .= "<br> $file_name upload error. File type is not allowed"; continue;}
				// Dont upload faltu files	
				$f_content = @file_get_contents($file_tmpname);
				if(stripos($f_content, "<?php") !== false) {					
					send_mail(SYSTEM_NOTICE_EMAIL_TO, SYSTEM_EMAIL_FROM  ,'system' , SYSTEM_DOMAIN_NAME.': Hacking Attempt',"DIY image-bank file(".$upload_path.$file_name.") having PHP into it tried to upload","DIY image-bank file(".$upload_path.$file_name.") having PHP into it tried to upload");
					$response .= "<br> $file_name upload error. The filetype you are attempting to upload is not allowed."; continue;			
				}	
				// Dont upload faltu files	
				// Without CI - Traditional PHP
				//	move_uploaded_file($file_tmpname, $upload_path.$file_name );
				
				
				// CI Upload - STARTS
				// Define new $_FILES array - $_FILES['file']
				$_FILES['file']['name'] = $_FILES['files']['name'][$i];
				$_FILES['file']['type'] = $_FILES['files']['type'][$i];
				$_FILES['file']['tmp_name'] = $_FILES['files']['tmp_name'][$i];
				$_FILES['file']['error'] = $_FILES['files']['error'][$i];
				$_FILES['file']['size'] = $_FILES['files']['size'][$i];
		
				$file_name=date('YmdHis').'.'.$file_ext;	
				
				$config['upload_path'] = $upload_path;
				$config['allowed_types'] = 'gif|jpg|png|jpeg';
				$config['max_size']	= 1024*2; //2MB
				$config['file_name']	= $file_name;
				
				//$this->load->library('upload',$config); 
				$this->upload->initialize($config);		
				if(!$this->upload->do_upload('file')){
					$error_msg=$this->upload->display_errors();
					$response .= "<br> $file_name upload error.".$error_msg;				
				}else{
					// Get data about the file
					$uploadData = $this->upload->data();
					$filename = $uploadData['file_name'];
					
					$img_bank_id	= $this->saveImageDatabase($file_name);			
					$this->_createThumbnail($upload_path.$file_name);
				}
				// CI Upload - ENDS		
			}
		}	
		if($response != ''){
			$data1 = array('error' => 'true','error_msg' => strip_tags($response));
			echo htmlspecialchars(json_encode($data1), ENT_NOQUOTES);	
		}else{
			$data1 = array('error' => 'false','error_msg' => '');
			echo htmlspecialchars(json_encode($data1), ENT_NOQUOTES);
		}	
		
	}	
		
		
	// Function to save imagebank's images url 
	function copy_image_bank_image_url(){		
		$img_url=$_POST['image_url'];		
		if($this->checkRemoteFile($img_url)){			
			if(!file_exists($this->upload_path.'/image_bank')){
				mkdir($this->upload_path.'/image_bank',0777);
				chmod($this->upload_path.'/image_bank',0777);
			}
			
			list($width_orig, $height_orig,$extension) = getimagesize($img_url);
			$file_name='img_'.time().".".$extension;
			
			$file_path=base_url().'asset/user_files/'.$this->session->userdata('member_id').'/image_bank/'.$file_name;
			$msg=$this->save_image($img_url,$this->upload_path.'/image_bank/'.$file_name);

			if(!$msg){
				if (file_exists($this->upload_path.'/image_bank/'.$file_name)){
					$img_bank_id=$this->saveImageDatabase($file_name);
				}
				$this->_createThumbnail($this->upload_path.'/image_bank/'.$file_name);
					list($width, $height, $type, $attr) = getimagesize($this->upload_path.'/image_bank/'.$file_name);
					/***********Thumb image path**********************/
					$path_info=pathinfo($this->upload_path.'/image_bank/'.$file_name);
					$thumb_image_path=base_url().'asset/user_files/'.$this->session->userdata('member_id').'/image_bank/'.$path_info['filename']."_thumb.".$path_info['extension'];
					
					$data1 = array('error' => 'false','file_path' => $file_path,'height'=>$height,'width'=>$width,'thumb_image_path'=>$thumb_image_path,'img_id'=>$img_bank_id);
			}else
				 $data1 = array('error' => 'true','error_msg'=>$msg);

		}else{
			$data1 = array('error' => 'true','error_msg'=>'Image url cannot be empty');
		}
		//header('Content-type: text/html');
		echo json_encode($data1);
	}
	function save_image($inPath,$outPath){
		$filesize = ($this->getSizeFile($inPath)* .0009765625) * .0009765625; ;	// bytes to MB		
		if($filesize<=2){
			//Download images from remote server
			$in=    fopen($inPath, "rb");
			$out=   fopen($outPath, "wb");
			while ($chunk = fread($in,8192)){
				if(stripos($chunk, "<?php") !== false) {	
					send_mail(SYSTEM_NOTICE_EMAIL_TO, SYSTEM_EMAIL_FROM  ,'system' , SYSTEM_DOMAIN_NAME.': Hacking Attempt',"DIY image-bank file(".$upload_path.$file_name.") having PHP into it tried to upload","DIY image-bank file(".$upload_path.$file_name.") having PHP into it tried to upload");
				return "The file you are attempting to upload cannot be uploaded.";
				exit;				
				}	
				$fwrite=fwrite($out, $chunk, 8192);
				if ($fwrite === false) {
					return "The file you are attempting to upload cannot be uploaded.";
				}
			}
			fclose($in);
			fclose($out);
		}else{
			return "The file you are attempting to upload is larger than the permitted size.";
		}
		
	}
	function checkRemoteFile($url){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);
		// don't download content
		curl_setopt($ch, CURLOPT_NOBODY, 1);
		curl_setopt($ch, CURLOPT_FAILONERROR, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		if(curl_exec($ch)!==FALSE){
			return true;
		}else{
			return false;
		}
	}
	function getSizeFile($url) {
		if (substr($url,0,4)=='http') {
			$x = array_change_key_case(get_headers($url, 1),CASE_LOWER);
			if ( strcasecmp($x[0], 'HTTP/1.1 200 OK') != 0 ) { $x = $x['content-length'][1]; }
			else { $x = $x['content-length']; }
		}else { 
			$x = @filesize($url); 
		}
		return $x;
	} 
	
	
	 
	 function _createThumbnail($fileName) {
		$config['image_library'] = 'gd2';
		$config['source_image'] = $fileName;
		$config['create_thumb'] = TRUE;
		$config['maintain_ratio'] = TRUE;
		$config['width'] = 65;
		$config['height'] = 65;
		$this->load->library('image_lib', $config);
		if(!$this->image_lib->resize())  echo $this->image_lib->display_errors(); 
    }
	// Function to save images in database
	function saveImageDatabase($file_name){			
		return $this->Campaign_Model->create_image_bank(array('img_user_id'=>$this->session->userdata('member_id'), 'img_name'=>$file_name, 'img_is_status'=>1));		
	}
	
	// Function to unlink image from image bank 
	function unlink_image_bank($img_id){					
		$this->Campaign_Model->delete_image_bank(array('img_id'=>$img_id));		
		$image_bank_data = $this->Campaign_Model->get_image_bank_data(array('img_id'=>$img_id),1,0);	
		if(count($image_bank_data)>0){		
			$img_file_nm = $image_bank_data[0]['img_name'];
			$file_ext = strtolower(pathinfo($img_file_nm, PATHINFO_EXTENSION));
			$thmb_img_nm = str_replace('.'.$file_ext, '_thumb.'.$file_ext , $img_file_nm );
			@unlink($this->upload_path.'/image_bank/'.$img_file_nm);
			@unlink($this->upload_path.'/image_bank/'.$thmb_img_nm);
		}
	}
	// Function to unlink theme color
	function unlink_theme_color($theme_id){
		$this->Campaign_Model->delete_theme_color(array('id'=>$theme_id));		
	}
	// Function to add color theme in database
	function add_color_theme(){
		
		$this->form_validation->set_rules('color_theme_name', 'Theme Name', 'required|max_length[30]');
		
		// To check form is validated
		if($this->form_validation->run()){
			// Retrieve data posted in form posted by user using input class
								/* 'header_bg'=>'#'.$this->input->get_post('theme_header_color', true), */
			$insert_array=array('theme_name'=>$this->input->get_post('color_theme_name', true),
								'body_bg'=>'#'.$this->input->get_post('theme_body_color', true),
								'border_color'=>'#'.$this->input->get_post('theme_border_color', true),
								'footer_bg'=>'#'.$this->input->get_post('theme_footer_color', true),
								'body_font_color'=>'#'.$this->input->get_post('theme_body_font_color', true),
								'footer_font_color'=>'#'.$this->input->get_post('theme_footer_font_color', true),
								'preheader_font_color'=>'#'.$this->input->get_post('theme_preheader_color', true),
								'outer_bg'=>'#'.$this->input->get_post('theme_outer_bg_color', true),
								'member_id'=>$this->session->userdata('member_id')
			);
			// Sends form delete data to database via model object
			$inserted_id=$this->Campaign_Model->create_color_theme($insert_array);
			echo "Success:".$inserted_id;
		}else{
			// print validation errors
			echo "error:".validation_errors();
		}
		
	}
	/**
	*	Function get_vimeo_video_image to get vimeo video image from http://vimeo.com/api/v2/username/request.output
	*/
	function get_vimeo_video_image($video_id=0){
		$url = "https://vimeo.com/api/v2/video/$video_id.php";	#Making the URL
		$contents = @file_get_contents($url);
		$thumb = @unserialize(trim($contents));
		if($thumb[0][thumbnail_large]){
			$data=array('image_path'=>$thumb[0][thumbnail_large],'title'=>$thumb[0][title]);
		}else{
			$data=array('error'=>'Video not found');
		}
		echo json_encode($data);
	}
	
	
}
?>