<?php if(MAINTENANCE_MODE_FOR_LOGGED_USERS == 'yes'){ redirect('/site_under_maintenance/');exit; } 
if($this->session->userdata('member_staff') > 0){
	if($this->uri->segment(1) == 'promotions' and $this->session->userdata('manage_campaigns') == 0){
		redirect('contacts');exit;
	}elseif($this->uri->segment(1) == 'contacts' and $this->session->userdata('manage_contacts') == 0){
		redirect('stats/display');exit;
	}elseif($this->uri->segment(1) == 'emailreport' and $this->session->userdata('manage_stats') == 0){
		redirect('autoresponder/display');exit;
	}elseif($this->uri->segment(1) == 'autoresponder' and $this->session->userdata('manage_autoresponders') == 0){
		redirect('subscription');exit;
	}elseif($this->uri->segment(1) == 'subscription' and $this->session->userdata('manage_signupforms') == 0){
		redirect('dashboard_extra/dashboard_extra_list');exit;
	}elseif($this->uri->segment(1) == 'dashboard_extra' and $this->session->userdata('manage_extra') == 0){
		redirect('user/change_password/');exit;
	}elseif($this->uri->segment(1) == 'account'){
		redirect('user/change_password/');exit;
	}	
}
?>
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
<script type="text/javascript" src="<?php  echo $this->config->item('locker');?>js/contacts_management.js?v=8"></script>
<script language = 'javascript' type="text/javascript" src = '<?php echo $this->config->item('locker');?>js/site.js?v=2'></script>



</head>
<body>
<div class = 'main'>
 

	<!-- body - private access starts -->
	<div class = 'body-private'>
		
		<!-- body - right side starts -->
		<div class = 'body-private-right' <?php if($this->session->userdata('webmaster_id') =='1' ){echo "style='width:100% !important'";} ?>>
			<!-- body - Ub-message starts -->			
			<div id = 'ub-message'></div>
			<!-- body - Ub-message ends -->
			
  








<!-- Header ends -->

<?php if(count($emailreport_data)>0){ 

echo '"Subject","Date", "Sent", "Open", "Unopen", "Click", "Forward", "Unsubscribe", "Bounce", "Complaints"';
foreach($emailreport_data as $key=>$email){  
if($email['total_delivered_emails']>0){

echo "<br/>".'"'.$email['campaign_title']. '","' . date('Y-m-d', strtotime( getGMTToLocalTime($email['email_send_date'], $this->session->userdata('member_time_zone')) )) .'","'.$email['total_delivered_emails'].'","'.$email['total_read_emails'].'","'.$email['total_unread_emails'].'","'.$email['total_click_emails'].'","'.$email['email_track_forward'].'","'.$email['total_unsubscribes'].'","'.$email['email_track_bounce'].'","'.$email['total_complaint_emails'].'"';
 
}// End of IF
}// End of For loop ?>
<!--  div class="pagination-container noajax"><?php echo $paging_links ?></div -->			
<?php }else {// End of IF ?>
 
      <div class="empty">
        <p style="padding: 50px; text-align:center; ">There is no campaign sent yet. So, there is no stats available.</p>
		<p style="padding: 0px; text-align:center; "><a href="<?php echo  site_url("promotions");?>" class="button grey2 large">Userboard</a></p>
      </div>
    <?php } ?>
   
<!--[/body]-->
