<?php if(MAINTENANCE_MODE_FOR_ALL_USERS == 'yes'){ redirect('/site_under_maintenance/');exit;} ?>
<!doctype html>
<html lang="en">
<head>

<!-- Basic Page Needs
================================================== -->
<meta charset="utf-8" />
<title>
<?php if($this->uri->segment(2) == 'pricing'){ 
echo "BoldInbox.Com: Pricing | Free Account To Start With.";
}elseif($this->uri->segment(2) == 'about'){ 
echo "BoldInbox.Com: About Us  | Who Are We?";
}elseif($this->uri->segment(2) == 'contact'){ 
echo "BoldInbox.Com: Contact Us  | Don't Hesitate - We Are There For You.";
}elseif($this->uri->segment(2) == 'terms#anti-spam-policyact'){ 
echo "BoldInbox.Com: Anti Spam Policy";
}elseif($this->uri->segment(2) == 'terms'){ 
echo "BoldInbox.Com: Policies & Terms";
}else{
echo "BoldInbox.Com:Simple | Easy | Clean - Simple Email Marketing Tool | We Really Mean It.";
}
?>
</title>
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
	
	<div class = 'header-private-tel' style='float:left; padding-left:20px;padding-top:10px;'>	
		<a href="skype:sumitthakkar?chat"><img src="https://secure.skypeassets.com/i/scom/images/skype-buttons/chatbutton_16px.png" alt="Skype chat, instant message" role="Button" style="border:0;"></a>
		 
	</div>
	<div class = 'header-private-tel' style='float:left; padding-left:20px;padding-top:10px;'>
		Contact: <a href="tel:+918130972229">+91 8130 972 229</a>
	</div>	
	<div class = 'header-social'>
		<a href = 'javascript:void(0);'><img src = '<?php echo base_url();?>locker/images/icons/find-us-on-facebook.png' border = '0' /></a>
		<a href = 'javascript:void(0);'><img src = '<?php echo base_url();?>locker/images/icons/find-us-on-twitter.png' border = '0' /></a>
	</div>
	
	<div style="width:1000px; text-align:center; margin:0;">
        <h3 style="margin:0; font-size: 1.6em;letter-spacing: 0px;color: blue; background-color:#C4DFB8; padding:3px 0;">*FREE! HTML Emailer Design With All Plans.</h3>
    </div>
    
	
	
	<div class = 'header-logo'><a href = '<?php echo site_url()?>'><img src = '<?php echo base_url();?>locker/images/logo-blue.png' /></a></div>
	<div class = 'header-menu'>
		<div class = 'header-menu-links left'>
			<a href = '<?php echo base_url();?>#features'>Features</a> | 
			<a href = '<?php echo site_url("home/pricing")?>'>Pricing</a> | 			
			<a href = 'javascript:void(0);'>Support</a>
		</div>
		<div class = 'header-menu-links right'>
			<?php if('' != $this->session->userdata('member_username')){?>
			<a href = '<?php echo  site_url("promotions");?>'>USERNAME: <?php echo strtoupper($this->session->userdata('member_username'));?></a> | <a href = '<?php echo  site_url("promotions");?>' style = 'color:#1C4587;text-decoration:underline;'><strong>USERBOARD</strong></a> | <a href='<?php echo site_url("user/logout");?>'>LOGOUT</a>
			<?php }else{ ?>
			<a href = '<?php echo site_url("user/login")?>'>Login</a> | <a href = '<?php echo site_url("user/register")?>'><strong>Its Free! Start Here</strong></a>
			<?php }?>
		</div>
	</div>	
</div>

<!-- Header - Public Acess Ends -->

 
   
   