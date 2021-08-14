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

$thisClass = $this->router->fetch_class();
$thisMethod = $this->router->fetch_method();

$arrClass = array('promotions' => 'Campaigns', 'contacts' => 'Contacts', 'stats' => 'Campaign Stats', 'subscription' => 'Signup Form', 'account' => 'Settings', 'change_package' => 'Settings', 'user' => 'Settings' );
$arrClassMethod = array(
						'promotions' => array( 'index'=>'Campaign List', 'layouts'=> 'Campaign Builder', 'plain_text'=> 'Text Email', 'html_code'=> 'Paste HTML Code', 'url_import'=> 'Generate from URL', 'zip_import'=> 'Generate from Zip File'),
						'contacts' => array( 'index'=>'Email Contact List', 'add_contacts' => 'Add New Contacts'),
						'stats' => array( 'display'=>''),
						'subscription' => array( 'index'=>'Signup Form List', 'edit' => 'Create Signup Form'),
						'account' => array( 'index'=>'Account'),
						'change_package' => array( 'index'=>'Package'),
						'user' => array( 'change_password'=>'Change Password')
						);
						

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>BoldInbox.Com:Simple | Easy | Clean - Simplest Ever Email Marketing Tool | We Really Mean It.</title>

     <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Poppins:100,200,300,400,500,600,700,800,900&amp;display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo $this->config->item('locker').'themes/march2020/';?>fonts/font-awesome/css/font-awesome.min.css">

   
	<link rel="stylesheet" href="<?php echo $this->config->item('locker').'themes/march2020/';?>styles/bootstrap.css">

    <link rel="stylesheet" href="<?php echo $this->config->item('locker').'themes/march2020/';?>styles/main.css" />
    <link rel="stylesheet" href="<?php echo $this->config->item('locker').'themes/march2020/';?>styles/privatepage.css?1.0" />
	<link rel="stylesheet" href="<?php echo $this->config->item('locker').'themes/march2020/';?>styles/<?php echo $this->router->fetch_class();?>.css" /> 	
	<script language = "javascript" type="text/javascript" src="<?php echo $this->config->item('locker').'themes/march2020/';?>scripts/vendors/jquery-3.4.1.min.js"></script> 
	<script src="<?php echo $this->config->item('locker').'themes/march2020/';?>scripts/vendors/bootstrap.js"></script>	
	<script language = "javascript" type="text/javascript" src="<?php echo $this->config->item('locker').'themes/march2020/';?>scripts/generic.js"></script>
	<script language = "javascript" type="text/javascript" src="<?php echo $this->config->item('locker').'themes/march2020/';?>scripts/site.js?1.102"></script>	
	<script type="text/javascript">var site_url="<?php echo base_url();?>";var base_url="<?php echo base_url();?>";var locker="<?php echo $this->config->item('locker');?>";var memid="<?php echo $this->session->userdata('member_id'); ?>";</script>
	<script language = "javascript" type="text/javascript" src = '<?php echo $this->config->item('locker').'themes/march2020/';?>scripts/<?php echo $this->router->fetch_class();?>.js?v=1.2.0'></script>

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
    
    <!-- Preloader -->
    <div id="js-preloader" class="js-preloader">
      <div class="content">
        <img src="images/logo-icon.png" alt="">
      </div>
      <div class="preloader-inner">
      </div>
    </div>

	
<!-- Modal -->
<div class="modal fade" id="messageBox" tabindex="-1" role="dialog" aria-labelledby="message_title" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="message_title">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="message">
        ...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <div id="message_button"><button type="button" class="btn btn-primary">Save Changes</button></div>
      </div>
    </div>
  </div>
</div>


	

   <!-- Mobile Menu -->
    <div class="mobile-nav-wrapper">
      <div class="mobile-menu-inner">
         <ul class="mobile-menu">
			<?php  	
					if($this->session->userdata('member_staff') == 0 or $this->session->userdata('manage_campaigns') > 0){
					if($this->uri->segment(1) == 'promotions'){$styl = 'class="link_active"'; $promoClass1 = 'class="sub_link_active"'; $isactiv = '-';}else{$styl='';}
					if($this->uri->segment(1) == 'promotions' && $this->uri->segment(2) == 'layouts'){$promoClass1 = ''; $promoClass2 = 'class="sub_link_active"';}
					if($this->uri->segment(1) == 'promotions' && $this->uri->segment(2) == 'plain_text'){$promoClass1 = ''; $promoClass3 = 'class="sub_link_active"';}
					if($this->uri->segment(1) == 'promotions' && $this->uri->segment(2) == 'html_code'){$promoClass1 = ''; $promoClass4 = 'class="sub_link_active"';}
					if($this->uri->segment(1) == 'promotions' && $this->uri->segment(2) == 'url_import'){$promoClass1 = ''; $promoClass5 = 'class="sub_link_active"';}
					if($this->uri->segment(1) == 'promotions' && $this->uri->segment(2) == 'zip_import'){$promoClass1 = ''; $promoClass6 = 'class="sub_link_active"';}
			?>
          <li class="has-sub"><a href = 'javascript:void(0);' <?php echo $styl;?>>Campaigns<i class="sub-icon fa fa-angle-down"></i></a>
			<ul class="sub-menu">
				<li><a href = '<?php echo site_url('promotions');?>' <?php echo $promoClass1;?>>Campaigns List</a></li>
				<li><a href = '<?php echo site_url('promotions/layouts');?>' <?php echo $promoClass2;?>>Campaign Builder</a></li>
				<li><a href = '<?php echo site_url('promotions/plain_text');?>' <?php echo $promoClass3;?>>Text Email</a></li>			
				<li><a href = '<?php echo site_url('promotions/html_code');?>' <?php echo $promoClass4;?>>Paste HTML Code</a></li>
				<li><a href = '<?php echo site_url('promotions/url_import');?>' <?php echo $promoClass5;?>>Generate from URL</a></li>		
				<li><a href = '<?php echo site_url('promotions/zip_import');?>' <?php echo $promoClass6;?>>Generate from Zip File</a></li>
			  </ul>
		  </li>
		  <?php
		  }
		  ?>
		  <?php 
				if($this->session->userdata('member_staff') == 0 or $this->session->userdata('manage_contacts') > 0){
				if((strpos($this->uri->segment(1), 'contacts') !== FALSE) or (strpos($this->uri->segment(1), 'subscriber') !== FALSE)){$styl = 'class="active"';}else{$styl='';}
			?>				
			<li class="has-sub"><a href = 'javascript:void(0);' <?php echo $styl;?>>Contacts<i class="sub-icon fa fa-angle-down"></i></a>
				<ul class="sub-menu">
					<li><a href='<?php  echo  site_url("contacts"); ?>' <?php if($isactiv =='-')echo'class="active-open"';?>>Email Contact List</a></li>
					<li><a onclick="createList();" href="javascript:void(0);" <?php if($isactiv =='-')echo'class="active-open"';?>>Create a New List</a></li>
					<li><a href='<?php  echo  site_url("contacts/add_contacts"); ?>' <?php if($isactiv =='-')echo'class="active-open"';?>>Add New Contacts</a></li>
			  </ul>
			</li>
			<?php 
				} 
			?>
		  	<?php if($this->session->userdata('member_staff') == 0 or $this->session->userdata('manage_stats') > 0){
				if(strpos($this->uri->segment(1), 'stats') !== FALSE){$styl = 'class="active"';}else{$styl='';}
			?>
				<li><a href = '<?php echo site_url('stats/display');?>'  <?php echo $styl;?>>Campaign Stats</a></li>
			<?php } ?>
          	<?php 	
				if($this->session->userdata('member_staff') == 0 or $this->session->userdata('manage_signupforms') > 0){
				if((strpos($this->uri->segment(1), 'subscription') !== FALSE)){$styl = 'class="active"';}else{$styl='';}
			?>
				<li class="has-sub"><a href = 'javascript:void(0);' <?php echo $styl;?>>Signup-form<i class="sub-icon fa fa-angle-down"></i></a>				
				<ul class="sub-menu">
				<li><a href='<?php  echo  site_url("subscription"); ?>' <?php if($isactiv =='-')echo'class="active-open"';?>>Signup Form List</a></li>
				<li><a href='<?php  echo  site_url("subscription/create"); ?>' <?php if($isactiv =='-')echo'class="active-open"';?>>Create Signup Form</a></li>
				</ul>
			</li>
			<?php					 
			} 
			?>
          	<?php					
				if((strpos($this->uri->segment(1), 'account') !== FALSE) or ($this->uri->segment(1) == 'user') or ($this->uri->segment(1) == 'change_package')){$styl = 'class="active"';$isactiv = '-';}else{$styl='';$isactiv = '+';}
			?> 
			<li class="has-sub"><a href = 'javascript:void(0);'   <?php echo $styl;?>>Settings<i class="sub-icon fa fa-angle-down"></i></a>				 			
				<ul class="sub-menu">
					<li><a href='<?php  echo  site_url("account/index"); ?>' <?php if($isactiv =='-')echo'class="active-open"';?>>Account</a></li>
					<li><a href='<?php  echo  site_url("change_package"); ?>' <?php if($isactiv =='-')echo'class="active-open"';?>>Package</a></li>
					<li><a href='<?php  echo  site_url("user/change_password"); ?>' <?php if($isactiv =='-')echo'class="active-open"';?>>Change Password</a></li>
				</ul>
			</li>
			<li class = 'last_link'><a href = '<?php echo site_url('home/support');?>'  <?php echo $styl;?>>Support</a></li>
			<li class = 'button_login'><a href="<?php echo site_url("user/logout")?>">Logout</a></li>
					
        </ul>
      </div>
    </div>
    <div class="mobile-menu-overlay"></div>

    <!-- Header -->
    <header class="site-header fixed-header is-fixed-private">
      <div class="container expanded">
        <div class="header-wrap">
          <div class="fixed-header-logo">
          	<a href="<?php echo site_url("home")?>"><img src="<?php echo $this->config->item('locker').'themes/march2020/';?>images/logo-white.png" alt="Boldinbox.com - Logo"></a>
          </div>
          <div class="is-fixed-header-logo private-page">
          	<a href="<?php echo site_url("home")?>"><img src="<?php echo $this->config->item('locker').'themes/march2020/';?>images/logo.png" alt="Boldinbox.com - Logo"></a>
          </div>
         <div class="header-nav">
              <ul class="main-menu">
              <?php  	
					if($this->session->userdata('member_staff') == 0 or $this->session->userdata('manage_campaigns') > 0){
					if($this->uri->segment(1) == 'promotions'){$styl = 'class="link_active"'; $promoClass1 = 'class="sub_link_active"'; $isactiv = '-';}else{$styl='';}
					if($this->uri->segment(1) == 'promotions' && $this->uri->segment(2) == 'layouts'){$promoClass1 = ''; $promoClass2 = 'class="sub_link_active"';}
					if($this->uri->segment(1) == 'promotions' && $this->uri->segment(2) == 'plain_text'){$promoClass1 = ''; $promoClass3 = 'class="sub_link_active"';}
					if($this->uri->segment(1) == 'promotions' && $this->uri->segment(2) == 'html_code'){$promoClass1 = ''; $promoClass4 = 'class="sub_link_active"';}
					if($this->uri->segment(1) == 'promotions' && $this->uri->segment(2) == 'url_import'){$promoClass1 = ''; $promoClass5 = 'class="sub_link_active"';}
					if($this->uri->segment(1) == 'promotions' && $this->uri->segment(2) == 'zip_import'){$promoClass1 = ''; $promoClass6 = 'class="sub_link_active"';}
				?>
                <li class="menu-item-has-children"><a href = '<?php echo site_url('promotions');?>' <?php echo $styl;?>>Campaigns <i class="sub-icon fa fa-angle-down"></i></a>
					<ul class="sub-menu">
						<li><a href = '<?php echo site_url('promotions');?>' <?php echo $promoClass1;?>>Campaigns List</a></li>
						<li><a href = '<?php echo site_url('promotions/layouts');?>' <?php echo $promoClass2;?>>Campaign Builder</a></li>
						<li><a href = '<?php echo site_url('promotions/plain_text');?>' <?php echo $promoClass3;?>>Text Email</a></li>			
						<li><a href = '<?php echo site_url('promotions/html_code');?>' <?php echo $promoClass4;?>>Paste HTML Code</a></li>
						<li><a href = '<?php echo site_url('promotions/url_import');?>' <?php echo $promoClass5;?>>Generate from URL</a></li>		
						<li><a href = '<?php echo site_url('promotions/zip_import');?>' <?php echo $promoClass6;?>>Generate from Zip File</a></li>
					</ul>
		  		</li>
			  <?php
			  }
			  ?>
			  <?php 
					if($this->session->userdata('member_staff') == 0 or $this->session->userdata('manage_contacts') > 0){
					if((strpos($this->uri->segment(1), 'contacts') !== FALSE) or (strpos($this->uri->segment(1), 'subscriber') !== FALSE)){$styl = 'class="active"';}else{$styl='';}
				?>				
          		<li class="menu-item-has-children"><a href = '<?php echo site_url('contacts');?>' <?php echo $styl;?>>Contacts <i class="sub-icon fa fa-angle-down"></i></a>
					<ul class="sub-menu">
						<li><a href='<?php  echo  site_url("contacts"); ?>' <?php if($isactiv =='-')echo'class="active-open"';?>>Email Contact List</a></li>
						<li><a onclick="createList();" href="javascript:void(0);" <?php if($isactiv =='-')echo'class="active-open"';?>>Create a New List</a></li>
						<li><a href='<?php  echo  site_url("contacts/add_contacts"); ?>' <?php if($isactiv =='-')echo'class="active-open"';?>>Add New Contacts</a></li>
                  </ul>
		  		</li>
		  		<?php 
					} 
				?>
				<?php if($this->session->userdata('member_staff') == 0 or $this->session->userdata('manage_stats') > 0){
					if(strpos($this->uri->segment(1), 'stats') !== FALSE){$styl = 'class="active"';}else{$styl='';}
				?>
					<li><a href = '<?php echo site_url('stats/display');?>'  <?php echo $styl;?>>Campaign Stats</a></li>
				<?php } ?>
          		<?php 	
					if($this->session->userdata('member_staff') == 0 or $this->session->userdata('manage_signupforms') > 0){
					if((strpos($this->uri->segment(1), 'subscription') !== FALSE)){$styl = 'class="active"';}else{$styl='';}
				?>
					<li class="menu-item-has-children"><a href = '<?php echo site_url('subscription');?>' <?php echo $styl;?>>Signup-form <i class="sub-icon fa fa-angle-down"></i></a>				
					<ul class="sub-menu">
                    <li><a href='<?php  echo  site_url("subscription"); ?>' <?php if($isactiv =='-')echo'class="active-open"';?>>Signup Form List</a></li>
					<li><a href='<?php  echo  site_url("subscription/create"); ?>' <?php if($isactiv =='-')echo'class="active-open"';?>>Create Signup Form</a></li>
					</ul>
		  		</li>
				<?php					 
				} 
				?>
          		<?php					
					if((strpos($this->uri->segment(1), 'account') !== FALSE) or ($this->uri->segment(1) == 'user') or ($this->uri->segment(1) == 'change_package')){$styl = 'class="active"';}else{$styl='';}
				?> 
				<li class="menu-item-has-children"><a href = '<?php echo site_url("account/index");?>'   <?php echo $styl;?>>Settings <i class="sub-icon fa fa-angle-down"></i></a>				 			
					<ul class="sub-menu">
                    	<li><a href='<?php  echo  site_url("account/index"); ?>' <?php if($isactiv =='-')echo'class="active-open"';?>>Account</a></li>
						<li><a href='<?php  echo  site_url("change_package"); ?>' <?php if($isactiv =='-')echo'class="active-open"';?>>Package</a></li>
						<li><a href='<?php  echo  site_url("user/change_password"); ?>' <?php if($isactiv =='-')echo'class="active-open"';?>>Change Password</a></li>
					</ul>
		  		</li>
				<li><a href = '<?php echo site_url('home/support');?>'  <?php echo $styl;?>>Support</a></li>
				<li><a href = '<?php echo site_url("user/logout");?>' class = 'special_link'>Logout</a></li>
              </ul>   
          </div>
          <div class="header-widgets">
            <ul class="right-menu">               
              <li class="menu-item menu-mobile-nav">
                <a href="#" class="menu-bar" id="menu-show-mobile-nav">
                  <span class="hamburger"></span>
                </a>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </header>


    <!-- Header -->
    <div class="main-content"> 			
	
	<!-- User Panel -->
	 <section class="user-panel">
        <div class="container">
          <div class="row">
            <div class="col-lg-12">
              <div class="inner-content">
				<div class = 'user-box'>					
					<div class="row">						
						<div class="col-lg-6 col-md-12 col-sm-12">
							<div class = 'you-are-here'><a href = '<?php echo site_url($this->router->fetch_class());?>' class = 'buttonSmPink'><?php echo $this->session->userdata('member_username');?></a> <a href = '<?php echo site_url('promotions');?>'><?php echo $arrClass[$this->router->fetch_class()];?></a> <?php if($this->router->fetch_class() !='stats')echo '&raquo;';?> <?php echo $arrClassMethod[$this->router->fetch_class()][$this->router->fetch_method()];?></div>
						</div>				
						<div class="col-lg-6 col-md-12 col-sm-12">
							<div class=" user-details">
								<div class="row">									
									<div class="col-sm-8">										
										<div class="progress">
										  <div class="progress-bar progress-bar-success" style="width:<?php echo (($contactDetail['totContacts']/$contactDetail['PlanMaxContact'])*100);?>%">											
										  </div>
										  <div class = 'progress-bar-text'>Using <?php echo $contactDetail['totContacts']. " / ".$contactDetail['PlanMaxContact']." Contacts Plan";?></div>
										</div>										
									</div>
									<div class="col-sm-4">
										<div class = 'upgrade-package'><?php if($this->session->userdata('member_staff') == 0){?><a href = '<?php echo  site_url("change_package");?>' class = 'buttonSm'>Upgrade Package</a><?php } ?></div>
									</div>								
								</div>
							</div>
						</div>				
					</div>
				</div>				
				<?php
				// display all messages
				if (is_array($campaign_data['messages'])){
				  echo '<div class = "message-box">';
				  foreach ($campaign_data['messages'] as $type => $msgs):
				  foreach ($msgs as $message):
					echo $message;
				  endforeach;
				  endforeach;
				  echo '</div>';
				//}elseif(($campaign_data['active_campaign_count']>0)||($campaign_data['ready_campaign_count']>0)||($campaign_data['queueing_campaign_count']>0)){
				}else{
				  // echo '<div class = "message-box">Your email campaign is in our sending queue. Sending may take a bit longer, depending on the number of emails queued before yours.</div>';
				}
				?>			
              </div>
            </div>
          </div>
        </div>
      </section>
	<!-- User Panel -->