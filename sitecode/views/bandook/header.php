<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $title ?></title>
<?php echo link_tag('locker/css-bandook/style.css?v=6-20-13'); ?>

<script type="text/javascript" src="<?php echo $this->config->item('locker'); ?>js/jquery-1.5.1.min.js?v=6-20-13"></script>
<script type="text/javascript">
    $(document).ready(function(){
		var url="<?php echo base_url() ?>bandook/account/set_referal_url";
		jQuery.ajax({
			url: url,
			type:"POST",
			success: function(data){
			}
		});
    });

</script>
<meta name="description"  content="Group On" />

<meta name="keywords" content="Group On" /></head>
<body>
<?php $ci =& get_instance(); ?>
<table width="100%" border="0" cellspacing="0" cellpadding="0" id="page">
  <tr>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="0" id="header">
        <tr>
          <td id="headerbar"><table width="100%" border="0" cellspacing="0" cellpadding="0" >
              <tr>
                <td class="logo"><a href="<?php echo site_url($logo_link); ?>"><img height="50" src="<?=base_url()?>locker/images/logo.png?v=6-20-13" alt="Logo" /></a></td>
				 <?php if($ci->session->userdata('webmaster_id')!='') { ?>
                <td class="right_user_info"><table width="100%" border="0" cellspacing="0" cellpadding="0" >
                    <tr>
                      <td align="right" class="txt_white"> Welcome <span  class="txt_darkgray"><b><?php echo $ci->session->userdata('webmaster_username'); ?> </b></span>| <span class="txt_lightgray"><?php echo date("l M d, Y, h:i A", time()); ?></span></td>
                    </tr>
                    <tr>
                       <td ><ul class="userinfolinks">
                          <!--<li><a href="#"><img src="images/advancedsettings.png?v=6-20-13" alt=""  />Settings</a></li>-->

                          <li><a href="<?php echo  site_url("bandook/account/logout");?>">Logout</a></li>
                        </ul>
                      </td>
                    </tr>
                </table></td>
				<?php } ?>
              </tr>
            </table></td>
        </tr>
        <tr>
          <td class="gradishbar"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="86%" id="navigation"><ul id="menu">

<?php if($ci->session->userdata('webmaster_id')=='1'){ ?>

	<li><a href="<?php echo  site_url("bandook/dashboard_stat");?>">Dashboard</a></li>

	<li>
			<a href="<?php echo  site_url("bandook/users_manage/users_list");?>">Users</a>
			<ul>
			<li><a href="<?php echo  site_url("bandook/users_manage/paid_users");?>">Paid Users</a></li>
			<li><a href="<?php echo  site_url("bandook/users_manage/sub_users");?>">Sub Users</a></li>
			</ul>
	</li>

	<li>
			<a href="<?php echo  site_url("bandook/packages_manage/packages");?>">Package</a>
	</li>

	<li><a href="javascript:void(0);">Campaigns</a>
		<ul>
		<li><a href="<?php echo  site_url("bandook/campaign/approval");?>">Approval</a></li>
		<li><a href="<?php echo  site_url("bandook/campaign/ongoing");?>">Ongoing</a></li>
		<li><a href="<?php echo  site_url("bandook/campaign/scheduled");?>">Scheduled</a></li>
		<!-- li><a href="<?php // echo  site_url("bandook/campaign/sent");?>">Sent</a></li>
		<li><a href="<?php // echo  site_url("bandook/campaign/draft");?>">Draft</a></li -->
		</ul>
	</li>
	<li><a href="javascript:void(0);">Verification</a>
		<ul>
			<li><a href="<?php echo  site_url("bandook/autoresponders/index/verified");?>">Autoresponder</a></li>
			<li><a href="<?php echo  site_url("bandook/signupforms/index/verified");?>">Signup-forms</a></li>
			<li><a href="<?php echo  site_url("bandook/fromemail/index/");?>">From-email</a></li>
		</ul>	
	</li>
	<!--li><a href="javascript:void(0);">Autoresponders</a>
		<ul>
		<li><a href="<?php echo  site_url("bandook/autoresponders/index/verified");?>">Verified</a></li>
		<li><a href="<?php echo  site_url("bandook/autoresponders/index/unverified");?>">Unverified</a></li>
		</ul>
	</li>
	<li><a href="javascript:void(0);">Signup-forms</a>
		<ul>
		<li><a href="<?php echo  site_url("bandook/signupforms/index/verified");?>">Verified</a></li>
		<li><a href="<?php echo  site_url("bandook/signupforms/index/unverified");?>">Unverified</a></li>
		</ul>
	</li-->
	<li>
		<a href="<?php echo  site_url("bandook/manage_messages");?>">Messages</a>
		<ul>
			<li><a href="<?php echo  site_url("bandook/manage_messages/member_message/");?>">Member Message </a>	</li>
		</ul>
	</li>

	<li><a href="javascript:void(0);">Email Settings</a>
		<ul>
		<li><a href="<?php echo  site_url("bandook/template_category/template_category_list");?>">Manage Template Categories </a>	</li>
		<li><a href="<?php echo  site_url("bandook/template_header/template_header_list");?>">Manage Template Header </a>	</li>
		<li><a href="<?php echo  site_url("bandook/template_color/template_color_list");?>">Manage Template Color </a>	</li>
		</ul>

	</li>
	<li><a href="javascript:void(0);">Report</a>
		<ul>
			<li><a href="<?php echo  site_url("bandook/dashboard_stat/sent_campaign_daily");?>">Campaign Stats Daily</a></li>
			<li><a href="<?php echo  site_url("bandook/dashboard_stat/sent_campaign_new");?>">Campaign Stats</a></li>
			<li><a href="<?php echo  site_url("bandook/dashboard_stat/sent_campaign_beta");?>">Campaign Stats Beta</a></li>
			<li><a href="<?php echo  site_url("bandook/dashboard_stat/user_campaign_stats");?>">User Campaign Stats</a></li>
			<li><a href="<?php echo  site_url("bandook/dashboard_stat/sent_autoresponders");?>">Autoresponder Stats</a></li>
			<li><a href="<?php echo  site_url("bandook/campaign/global_ipr");?>">Global IPR</a></li>
			<li><a href="<?php echo  site_url("bandook/report/global_ipr");?>">Global IPR New</a></li>
			<li><a href="<?php echo  site_url("bandook/campaign/global_fbl");?>">Global FBL</a></li>
			<li><a href="<?php echo  site_url("bandook/activity_log");?>">Activty Log</a></li>
			<li><a href="<?php echo  site_url("bandook/contacts_segmentation");?>">Contact Segmentation</a></li>
			<li><a href="<?php echo  site_url("bandook/pmta_log");?>">PMTA Log</a></li>
			<li><a href="<?php echo  site_url("bandook/contacts_segmentation/searchContact/");?>">Search Contacts</a></li>
			<li><a href="<?php echo  site_url("bandook/contacts_segmentation/unsubscribe_feedback/");?>">Unsubscribe Feedback</a></li>
			<li><a href="<?php echo  site_url("bandook/report/paid_users/");?>">Paid Users</a></li>
			<li><a href="<?php echo  site_url("bandook/reseller/");?>">Reseller</a></li>
		</ul>
	</li>
	<li><a href="javascript:void(0);">Report-2</a>
		<ul>
			<li><a target="_blank" href="<?php echo  site_url("bandook/dashboard_stat/sent_stats_for_user/551");?>">User Stats</a></li>
			<li><a target="_blank" href="<?php echo  site_url("bandook/report/remove/unsub_string");?>">Mark Complaint</a></li>
			<li><a target="_blank" href="<?php echo  site_url("bibsend/markSent/47863");?>">Mark Sent</a></li>
			<li><a target="_blank" href="<?php echo  site_url("bibsend/sendBomb/47863");?>">Sendbomb</a></li>
		</ul>
	</li>
	<li><a href="<?php echo  site_url("bandook/coupons");?>">Coupons</a></li>
	<li><a href="<?php echo  site_url("bandook/feedback");?>">Feedback</a></li>
	<li><a href="javascript:void(0);">Blog</a>
		<ul>
			<li><a href="<?php echo  site_url("bandook/blog_listing_category/");?>">Blog Category</a></li>

		</ul>
	</li>

	<li><a href="javascript:void(0);">Support</a>
		<ul>
			<li><a href="<?php echo  site_url("bandook/support_category/support_category_list");?>">Support Category</a></li>
			<li><a href="<?php echo  site_url("bandook/support_content/support_content_list");?>">Support Content</a></li>
		</ul>
	</li>
	<li><a href="<?php echo  site_url("bandook/cms/index");?>">CMS</a></li>
	<!--li><a href="<?php // echo  site_url("bandook/database_backup/index");?>">Database Backup</a></li-->
	<li><a href="javascript:void(0);">Settings</a>
		<ul>
			<li><a href="<?php echo  site_url("bandook/sitesetting_manage/change_password");?>">Change Password</a></li>
			<li><a href="<?php echo site_url("bandook/sitesetting_manage/general_setting"); ?>">General Settings</a></li>
			<li><a href="<?php echo site_url("bandook/sitesetting_manage/cron_setting"); ?>">Cron Settings</a></li>
			<li><a href="<?php echo site_url("bandook/sitesetting_manage/email_personalize"); ?>">Email Personalize</a></li>
		</ul>
	</li>
	<li>
			<a href="<?php echo  site_url("bandook/account/logout");?>">Logout</a>
	</li>

	<?php }elseif($ci->session->userdata('webmaster_id')=='3') { ?>
	<li><a href="<?php echo  site_url("bandook/dashboard_stat/sent_campaign");?>">Campaign Stats</a></li>
	<li><a href="<?php echo  site_url("bandook/dashboard_stat/sent_autoresponders");?>">Autoresponder Stats</a></li>
	<li><a href="<?php echo  site_url("bandook/pmta_log");?>">PMTA Log</a></li>
	<li><a href="<?php echo  site_url("bandook/sitesetting_manage/change_password");?>">Change Password</a></li>
	<li><a href="<?php echo  site_url("bandook/account/logout");?>">Logout</a></li>
	<?php } ?>
                  </ul>

</td>
                <!-- <td width="14%" ><input type="text" title="Search" value="" class="searchbox"/></td>-->
              </tr>
            </table></td>
        </tr>
      </table></td>
  </tr>
  <tr>
    <td id="body"><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>

          <td id="rightside">
