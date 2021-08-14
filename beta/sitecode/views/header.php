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

	<!-- header - private access starts -->
	<div class = 'header-private'>
		
		<div class = 'header-private-social'>
			<a href = 'javascript:void(0);'><img src = '<?php echo base_url();?>locker/images/icons/find-us-on-facebook.png' border = '0' /></a>
			<a href = 'javascript:void(0);'><img src = '<?php echo base_url();?>locker/images/icons/find-us-on-twitter.png' border = '0' /></a>
		</div>
		<div class = 'header-private-user'>Username: <?php echo $this->session->userdata('member_username');?> | <?php if($this->session->userdata('member_staff') == 0){?><a href = '<?php echo  site_url("change_package");?>' style = 'color:#1C4587;text-decoration:underline;'><strong>Upgrade Now</strong></a> |<?php } ?> <a href='<?php echo site_url("user/logout");?>'>Logout</a></div>		
	</div>
	<!-- header - private access ends -->

	<!-- body - private access starts -->
	<div class = 'body-private'>
		<!-- body - left side starts -->
		<div class = 'body-private-left'>
			<div class = 'body-logo-private'><a href ='<?php echo site_url("promotions");?>'><img src = '<?php echo $this->config->item('locker');?>images/logo-blue.png' /></a></div>
			<div align = 'center' class = 'body-private-userboard'><input type = 'button' class = 'button blueD large' name = 'btn' value = 'USERBOARD' onclick = "javascript:window.location.href = '<?php echo site_url("promotions");?>';"></div>			
			<div class = 'body-private-package' style="position:relative;" align = 'center'><div style="width:<?php echo (($contactDetail['totContacts']/$contactDetail['PlanMaxContact'])*100);?>%;height: 100%;max-width: 100%;text-align: center;background-color: #B5F3B5;float:left;"></div><span style="position: absolute;width: 100%;text-align: center;-webkit-font-smoothing: auto;left: 0px;"><?php echo "Contacts: ".$contactDetail['totContacts']. " / ".$contactDetail['PlanMaxContact']." Contacts Plan";?></span></div>
			<div class = 'body-private-menu' align = 'center'>
				<?php  	
					if($this->session->userdata('member_staff') == 0 or $this->session->userdata('manage_campaigns') > 0){
					if($this->uri->segment(1) == 'promotions'){$styl = 'class="active"';$isactiv = '-';}else{$styl='';$isactiv = '+';}
				?>
				<a href = '<?php echo site_url('promotions');?>' <?php echo $styl;?>><?php echo $isactiv;?> Campaigns</a>
				<?php if($isactiv =='-'){?>
				<a href = '<?php echo site_url('promotions');?>' <?php if($this->uri->segment(1) == 'promotions')echo'class="active-open"';?>>Campaigns List</a>
				<a href = '<?php echo site_url('promotions/layouts');?>' <?php if($this->uri->segment(1) == 'promotions')echo'class="active-open"';?>>Campaign Builder</a>
				<a href = '<?php echo site_url('promotions/plain_text');?>' <?php if($this->uri->segment(1) == 'promotions')echo'class="active-open"';?>>Text Email</a>				
				<a href = '<?php echo site_url('promotions/html_code');?>' <?php if($this->uri->segment(1) == 'promotions')echo'class="active-open"';?>>Paste HTML Code</a>
				<a href = '<?php echo site_url('promotions/url_import');?>' <?php if($this->uri->segment(1) == 'promotions')echo'class="active-open"';?>>Generate from URL</a>		
				<a href = '<?php echo site_url('promotions/zip_import');?>' <?php if($this->uri->segment(1) == 'promotions')echo'class="active-open"';?>>Generate from Zip File</a>
				<?php 
					} 
				} 
				?>
				<?php 
					if($this->session->userdata('member_staff') == 0 or $this->session->userdata('manage_contacts') > 0){
					if((strpos($this->uri->segment(1), 'contacts') !== FALSE) or (strpos($this->uri->segment(1), 'subscriber') !== FALSE)){$styl = 'class="active"';$isactiv = '-';}else{$styl='';$isactiv = '+';}
				?>
				<a href = '<?php echo site_url('contacts');?>' <?php echo $styl;?>><?php echo $isactiv;?> Subscribers</a>				
				<?php if($isactiv =='-'){?>
				<a href='<?php  echo  site_url("contacts"); ?>' <?php if($isactiv =='-')echo'class="active-open"';?>>Email Contact List</a>
				<a onclick="createList();" href="javascript:void(0);" <?php if($isactiv =='-')echo'class="active-open"';?>>Create a New List</a>
				<a href='<?php  echo  site_url("bib_add_contacts"); ?>' <?php if($isactiv =='-')echo'class="active-open"';?>>Add New Contacts</a>
				<?php 
					} 
				} 
				?>
				<?php if($this->session->userdata('member_staff') == 0 or $this->session->userdata('manage_stats') > 0){
				if(strpos($this->uri->segment(1), 'stats') !== FALSE){$styl = 'class="active"';$isactiv = '-';}else{$styl='';$isactiv = '+';}
				?>
				<a href = '<?php echo site_url('stats/display');?>'  <?php echo $styl;?>><?php echo $isactiv;?> Campaign Stats</a>
				<?php } ?>
				<!-- Start: Subscription-form -->
				<?php 
				if($this->session->userdata('member_username') == 'pravinjha'){ // Temp	
					if($this->session->userdata('member_staff') == 0 or $this->session->userdata('manage_signupforms') > 0){
					if((strpos($this->uri->segment(1), 'subscription') !== FALSE)){$styl = 'class="active"';$isactiv = '-';}else{$styl='';$isactiv = '+';}
				?>
				<a href = '<?php echo site_url('subscription');?>' <?php echo $styl;?>><?php echo $isactiv;?> Subscription-form</a>				
				<?php if($isactiv =='-'){?>
				<a href='<?php  echo  site_url("subscription"); ?>' <?php if($isactiv =='-')echo'class="active-open"';?>>Subscription Form List</a>
				<a href='<?php  echo  site_url("subscription/create"); ?>' <?php if($isactiv =='-')echo'class="active-open"';?>>Create Subscription Form</a>
				<?php 
					} 
				} 
				}//TEMP
				?>
				<!-- Ends: Subscription-form -->			
				<?php					
					if((strpos($this->uri->segment(1), 'account') !== FALSE) or ($this->uri->segment(1) == 'user') or ($this->uri->segment(1) == 'change_package')){$styl = 'class="active"';$isactiv = '-';}else{$styl='';$isactiv = '+';}
				?> 
				<a href = '<?php echo site_url("account/index");?>'   <?php echo $styl;?>><?php echo $isactiv;?> Settings</a>				 			
				<?php if($isactiv =='-'){?>
				<a href='<?php  echo  site_url("account/index"); ?>' <?php if($isactiv =='-')echo'class="active-open"';?>>Account</a>
				<a href='<?php  echo  site_url("change_package"); ?>' <?php if($isactiv =='-')echo'class="active-open"';?>>Package</a>
				<a href='<?php  echo  site_url("user/change_password"); ?>' <?php if($isactiv =='-')echo'class="active-open"';?>>Change Password</a>
				<?php } ?>
				<!-- a href = '<?php //echo site_url("home/support");?>'  <?php //if($this->uri->segment(1) == 'support')echo'class="active-open"';?>>+ FAQs (Help Questions)</a>
				<a href = '#'>+ Feedback</a -->				
			</div>
			
			<div align = 'center' style = 'font-weight:700;'>Need Help? <a href = 'mailto:support@boldinbox.com'><b>support@boldinbox.com</b></a></div>
			
		</div>
		<!-- body - left side ends -->
		<!-- body - right side starts -->
		<div class = 'body-private-right'>
			<!-- body - Ub-message starts -->			
			<div id = 'ub-message'></div>
			<!-- body - Ub-message ends -->
			
 <!-- Hidden popup to add list -->	
    <div id="subscription_menu" style="display:none;" >
      <div id="add-list">
        <form onsubmit="ajaxSubscriptionFrm(this); return(false);" method="post" class="form-website" id="subscriptionfrm"  name="subscriptionfrm">
          <div class="subscription_msg contacts_message" style = 'height:15px;width:67%;margin-bottom:5px;'>List created successfully.</div>
		  Enter List Name:         
          <div>
            <?php echo form_input(array('name'=>'subscription_title','id'=>'subscription_title','maxlength'=>250,'size'=>40,'value'=>set_value('subscription_title'),'class'=>'subscription_title')); ?>
          </div>
          <div class="btn-group message_button">
            <?php
              echo form_submit(array('name' =>'subscription_submit', 'id' =>'btnEdit','content' =>'Submit', 'class' => "btn confirm form_submit_btn_class"), 'Create List');
              echo form_button(array('name'=>'campaign_cancel', 'value'=>'Cancel','content'=>'Cancel','onclick'=>"$.modalBox.close();", 'class' => "btn cancel form_submit_btn_class"));
            ?>
          </div>
        </form>
      </div>
    </div>
    
