<!doctype html>
<html lang="en">
<head>

<!-- Basic Page Needs
================================================== -->
<meta charset="utf-8" />
<title>BoldInbox.Com:Simple | Easy | Clean - Simplest Ever Email Marketing Tool | We Really Mean It.</title>
<meta name="description" content="">
<meta name="author" content="">
<!-- Mobile Specific Metas
================================================== -->
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
<!-- CSS
================================================== -->
<link rel="stylesheet" href="<?php echo $this->config->item('locker');?>css/base.css">
<link rel="stylesheet" href="<?php echo $this->config->item('locker');?>css/utils.css">
<link rel="stylesheet" href="<?php echo $this->config->item('locker');?>css/dev.css">

<link rel="shortcut icon" href="<?php echo $this->config->item('locker');?>images/favicon.ico">
<script type="text/javascript" src="<?php echo $this->config->item('locker');?>js/jquery-1.5.1.min.js"></script>
<script type="text/javascript">var site_url="<?php  echo base_url(); ?>";var base_url="<?php echo base_url();?>";var locker="<?php  echo $this->config->item('locker'); ?>";var memid="<?php echo $this->session->userdata('member_id');?>";</script>

<script language = 'javascript' type="text/javascript" src = '<?php echo $this->config->item('locker');?>jquery/jquery-tools.min.js'></script>
<script language = 'javascript' type="text/javascript" src = '<?php echo $this->config->item('locker');?>jquery/jquery.modalBox.js'></script>
<script language = 'javascript' type="text/javascript" src = '<?php echo $this->config->item('locker');?>jquery/jquery-ui.js'></script>
<script language = 'javascript' type="text/javascript" src = '<?php echo $this->config->item('locker');?>js/generic.js?v=1'></script>
<script type="text/javascript" src="<?php  echo $this->config->item('locker');?>js/contacts_management.js?v=2"></script>
<script language = 'javascript' type="text/javascript" src = '<?php echo $this->config->item('locker');?>js/site.js?v=2'></script>

</head>
<body>

<!--[page html]-->
<div id="wrapper">
  <!--[header]-->
    

  <div id="header-main">
    <div id="header-menu">
      <a href="<?php echo base_url();?>newsletter/campaign" id="logo" title="BoldInbox.com"></a>
    </div>
  </div>
  <!--[header]-->

  <!--[body]-->
<div id="body-dashborad" align="center">
  <div id="first-time-sender" class="container" style="text-align:center" align="center">
  <h1>BoldInbox.com is currently down for maintenance</h1>
    <div>

      <p style="font-size:20px;">
        <div class = 'body-logo-private'><a href ='<?php echo site_url("promotions");?>'><img src = '<?php echo $this->config->item('locker');?>images/logo-blue.png' /></a></div>
      
	  
	  <br/> 
         
        
          We promise to be back soon. Thanks for your patience.
        
		
		
		<br/>
		<br/>
		<br/>
          Cheers,<br />
          BoldInbox.com Support Team<br/>
          <?php echo SYSTEM_EMAIL_FROM ;?>
        </p>	 
    </div>
  </div>
</div>

  <!--[/body]-->
</body>
</html>