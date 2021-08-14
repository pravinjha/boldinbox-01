<?php if(MAINTENANCE_MODE_FOR_ALL_USERS == 'yes'){ redirect('/site_under_maintenance/');exit;} ?>
<!doctype html>
<html lang="en">
<head>

<!-- Basic Page Needs
================================================== -->

<title>
<?php if($this->uri->segment(2) == 'login'){ 
echo "BoldInbox.Com: SignIn  | Login to Your Userboard.";
}elseif($this->uri->segment(2) == 'register'){ 
echo "BoldInbox.Com: SignUp For Free | No Payment Required.";
}else{
echo "BoldInbox.Com:Simple | Easy | Clean - Simple Email Marketing Tool | We Really Mean It.";
}
?>
</title>
<meta charset="utf-8" />
<meta name="description" content="Clean - Simple Ever Email Marketing Tool | We Really Mean It.">
<meta name="author" content="BoldInbox">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Content-Language" content="en-US" />
<meta name="msvalidate.01" content="F20291881CA4263B43E45923C943F3C0" />
<meta name="Robots" content="index, follow" />
<meta name="GoogleBot" content="index, follow" />
<meta name="Publisher" content="BoldInbox" />    
<meta name="Copyright" content="BoldInbox" />
<!-- Mobile Specific Metas
================================================== -->
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
<!-- CSS
================================================== -->
<link rel="stylesheet" href="<?php echo base_url();?>locker/css/utils.css">
<link rel="stylesheet" href="<?php echo base_url();?>locker/css/base.css">
<link rel="stylesheet" href="<?php echo base_url();?>locker/css/dev.css">
<!-- JS
================================================== -->
<script type="text/javascript" src="<?php echo $this->config->item('locker');?>js/jquery-1.5.1.min.js"></script>
<link rel="shortcut icon" href="<?php echo $this->config->item('locker');?>images/favicon.ico">
</head>
<body>
<div class = 'main'>
<!-- Header - Public Acess Starts -->
<div class = 'header-public'>
	
	<div class = 'header-logo'><a href = '<?php echo site_url()?>'><img src = '<?php echo base_url();?>locker/images/logo-blue.png' /></a></div>
		
</div>
<!-- Header - Public Acess Ends -->

 
   
   