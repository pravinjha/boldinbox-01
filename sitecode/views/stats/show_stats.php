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

<script type="text/javascript">
$(function() {
  $.ajax({
    url: "<?php echo base_url() ?>user/get_message/",
    type:"POST",
    success: function(data) {		
      if(data !='') {		
        //$(data).prependTo("body");
		$('#ub-message').html(""+data+"");
      }
    }
  });
// overlay
$("#spin").spinner({background: "rgba(0,0,0,0.3)", 'z-index':999, html: "<img border='0'  style='margin:0;' src='<?php echo $this->config->item('locker');?>images/ajax-loader.gif' />"});  
});
</script>

  <?php if($this->session->userdata('member_status')=='inactive'){?>
    <script type="text/javascript">
      $(function() {
      // function for resend confirmation email
      var $resend = $(".resend_confirmation");
      if($resend) {
        $resend.live('click',function(){
          $.ajax({
            url: "<?php echo base_url() ?>user/user_confirmation_notification/<?php echo $this->session->userdata('member_id'); ?>/confirmation_msg",
            type:"POST",
            success: function(data) {
              //display success message
              if(data=="success"){
                $(".new-signup").html("Please check your email.").delay(3000).slideUp(300);
              }
            }
          });
        });
      }
      });
    </script>
  <?php } ?>
</head>
<body>
<div class = 'main'>
<div id="spin" style='background: "rgba(0,0,0,0.3)"'></div>
<div id = 'messageBox'>
	<div id = 'message_title'></div>
	<div class = 'clear5'></div>
	<div id = 'message'></div>	
	<div id = 'message_close'>close</div>
	<div id = 'message_button'></div>
</div>

	<!-- body - private access starts -->
	<div class = 'body-private'>
		
		<!-- body - right side starts -->
		<div class = 'body-private-right' <?php if($this->session->userdata('webmaster_id') =='1' ){echo "style='width:100% !important'";} ?>>
			<!-- body - Ub-message starts -->			
			<div id = 'ub-message'></div>
			<!-- body - Ub-message ends -->
			
  








<!-- Header ends -->

<?php if(count($emailreport_data)>0){ ?>
<script language="javascript" type="text/javascript" src="<?php echo $this->config->item('locker');?>/js/jquery.flot.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo $this->config->item('locker');?>/js/jquery.flot.pie.js"></script>
<script language = 'javascript' type="text/javascript">
	$(document).ready(function(){	
	function getpie(placeholder, series){
		var data = [];
		var i=0;
		$.each(series, function(k,v){data[i] = {	label: k, data:v }; i++; } );
		
		var placeholder = $("#"+placeholder);
		placeholder.unbind();
		$.plot(placeholder, data, {
			series: {	pie: { show: true, radius: 500,  	label: {show: true, formatter: labelFormatter, threshold: 0.1	}	} },
			legend: {	show: false	}
		});
	}

<?php foreach($emailreport_data as $key=>$email){  
if($email['total_delivered_emails']>0){
?>
	var x = {'Open' :"<?php echo $email['total_read_emails']; ?>", 'Un-Open': "<?php echo $email['total_unread_emails']; ?>",'Click': "<?php echo $email['total_click_emails']; ?>",'Forward': "<?php echo $email['email_track_forward']; ?>",'Unsubscribe': "<?php echo $email['total_unsubscribes']; ?>",'Bounce': "<?php echo $email['email_track_bounce']; ?>",'Complaint': "<?php echo $email['total_complaint_emails']; ?>"};
	getpie('piecontainer_<?php echo $key; ?>', x);		
<?php }
}
 ?>	
});
</script>
<h2 style="margin-left:10px;"> Real Time Stats</h2>
<?php foreach($emailreport_data as $key=>$email){  
if($email['total_delivered_emails']>0){
?>

<div class = 'stats_block'>
				<div class = 'stats_header'>
					<div class = 'stats_info_left'>
						<div class = 'stats_campaign_name'><?php echo $email['campaign_title']; ?></div>
						<div class = 'stats_campaign_date'><strong>Sent On:</strong> <?php echo date('l F j, Y \a\t g:i a', strtotime( getGMTToLocalTime($email['email_send_date'], $this->session->userdata('member_time_zone')) ))?></div>
						<div class = 'stats_campaign_from'><strong>Sent From:</strong> <?php echo ucfirst($email['sender_name'])." &lt;"; ?><?php echo $email['sender_email']."&gt;"; ?></div>
						<div class = 'stats_campaign_lists'><strong>Sent To:</strong> <?php echo ucfirst($email['subscription_list_title']); ?></div>
					</div>
					
				</div>
				<div class = 'stats_content'>
					<div class = 'stats_pie' id = 'piecontainer_<?php echo $key; ?>'></div>
					<div class = 'stats_details'>
						<a href = '<?php echo ($email['total_delivered_emails']==0) ?  "javascript:void(0)" :   site_url("stats/detail/sent/".$email['enc_cid']);?>'>Sent: <?php echo $email['total_delivered_emails']; ?></a>
						<a href = '<?php echo site_url("stats/detail/read/".$email['enc_cid']);?>'>Opened: <?php echo $email['total_read_emails']; ?></a>
						<a href = '<?php echo site_url("stats/detail/unread/".$email['enc_cid']);?>'>Unopened: <?php echo $email['total_unread_emails']; ?></a>
						<a href = '<?php echo site_url("stats/detail/click/".$email['enc_cid']);?>'>Clicks: <?php echo $email['total_click_emails']; ?></a>
						<a href = '<?php echo site_url("stats/detail/forwardemail/".$email['enc_cid']);?>'>Forwards: <?php echo $email['email_track_forward']; ?></a>
						<a href = '<?php echo site_url("stats/detail/unsubscribes/".$email['enc_cid']);?>'>Unsubscribes: <?php echo $email['total_unsubscribes']; ?></a>
						<a href = '<?php echo site_url("stats/detail/bounced/".$email['enc_cid']);?>'>Bounces: <?php echo $email['email_track_bounce']; ?></a>
						<a href = '<?php echo site_url("stats/detail/complaints/".$email['enc_cid']);?>'>Complaints: <?php echo $email['total_complaint_emails']; ?></a>
						
					</div>
					<div class = 'stats_details' style="display:none;">
						<a href = '#'>Sent: <?php echo $email['total_delivered_emails']; ?></a>
						<a href = '#'>Opened: <?php echo $email['total_read_emails']; ?></a>
						<a href = '#'>Unopened: <?php echo $email['total_unread_emails']; ?></a>
						<a href = '#'>Clicks: <?php echo $email['total_click_emails']; ?></a>
						<a href = '#'>Forwards: <?php echo $email['email_track_forward']; ?></a>
						<a href = '#'>Unsubscribes: <?php echo $email['total_unsubscribes']; ?></a>
						<a href = '#'>Bounces: <?php echo $email['email_track_bounce']; ?></a>
						<a href = '#'>Complaints: <?php echo $email['total_complaint_emails']; ?></a>
						
					</div>
				</div>
			</div>
<?php 
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
