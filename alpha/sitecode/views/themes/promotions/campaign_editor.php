<!doctype html>
<html lang="en">
<head>
<!-- Basic Page Needs
================================================== -->
<title>BoldInbox.Com:Simple | Easy | Clean - Simple Ever Email Marketing Tool | We Really Mean It.</title>
<meta name="description" content="BoldInbox.Com:Simple | Easy | Clean - Simple Ever Email Marketing Tool | We Really Mean It.">
<meta name="author" content="Boldinbox">
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<meta http-equiv="x-ua-compatible" content="IE=8; IE=9" />
<meta name="SKYPE_TOOLBAR" content="SKYPE_TOOLBAR_PARSER_COMPATIBLE" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
<!-- CSS ================================================== -->
<!-- Fonts -->
<link href="https://fonts.googleapis.com/css?family=Poppins:100,200,300,400,500,600,700,800,900&amp;display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?php echo $this->config->item('locker');?>themes/march2020/fonts/font-awesome/css/font-awesome.min.css">


<link rel="stylesheet" href="<?php echo $this->config->item('locker');?>themes/march2020/styles/bootstrap.css">
<link rel="stylesheet" type="text/css" media="all" href="<?php echo base_url(); ?>promotions/editorstyle/">
<link rel="stylesheet" href="<?php echo $this->config->item('locker');?>css/base.css">
<!-- link rel="stylesheet" href="<?php echo $this->config->item('locker');?>themes/march2020/styles/main.css?d" -->
<link rel="stylesheet" href="<?php echo $this->config->item('locker');?>themes/march2020/styles/privatepage.css?d">
<!-- link rel="stylesheet" href="<?php echo $this->config->item('locker');?>css/utils.css"  -->
<link href="<?php echo $this->config->item('locker');?>css/diy.css?v=1.4" rel="stylesheet" type="text/css">
<style type="text/css" media="all" >
#template a, #template a:visited {
	color: #4285C6;
	text-decoration:underline;
}
h3{font-size:17px;font-weight:bold;margin:0 10px 0 10px;}

</style>
<link href="<?php echo $this->config->item('locker');?>css/colorpicker.css?v=6-20-13" rel="stylesheet" type="text/css" />
<!-- Favicons
================================================== -->
<link rel="shortcut icon" href="<?php echo $this->config->item('locker');?>images/favicon.ico">

<script type="text/javascript">var site_url="<?php  echo base_url(); ?>";var locker="<?php  echo $this->config->item('locker'); ?>";var memid="<?php  echo $extra['member_id']; ?>";</script>
<script type="text/javascript">	
	var ie=0;
	var base_url="<?php echo base_url();?>";
	var margin_top=0;
	var email_campaign_id=<?php echo $email_template_info['campaign_id']; ?>;
	var campaign_color_theme_id=<?php echo $email_template_info['campaign_color_theme_id']; ?>;
	var user_id=<?php echo $member_id; ?>;
	var logo_dialog_box=0;
	var header_image_change=1;
	var max_font_size=40;
	var min_font_size=10;
	var preview_page=false;
	var footer_font_size = parseInt(<?php echo ($footer_font_txt != '')?$footer_font_txt : 10; ?>);	
	var campaign_text= "<?php echo ($is_auotresponder)? 'auotresponder': 'campaign'; ?>";
</script>
<script type="text/javascript" src="<?php echo base_url(); ?>promotions/editorjs/<?php echo time();?>"></script>
<script src="<?php echo $this->config->item('locker');?>themes/march2020/scripts/vendors/bootstrap.js"></script>	
<!--[if IE]>
<script type="text/javascript">ie = 1;</script>
<![endif]-->
</head>
<body>

<!-- Preloader -->
    <!-- div id="js-preloader" class="js-preloader">
      <div class="content">
        <img src="images/logo-icon.png" alt="">
      </div>
      <div class="preloader-inner">
      </div>
    </div -->

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
        <button type="button" class="btn btn-secondary" onclick="javascript:closeAlertMessage();">Cancel</button>
        <div id="message_button"></div>
      </div>
    </div>
  </div>
</div>


<header class="site-header fixed-header is-fixed-private">
  <div class="container expanded">
	<div class="header-wrap">
		<div class="col-lg-12">
			<div class = 'user-box'>					
				<div class="row">						
					<div class="col-lg-6 col-md-12 col-sm-12">
						<div class = 'you-are-here'><a href = '<?php echo site_url('promotions');?>' class = 'buttonSmPink'><?php echo $this->session->userdata('member_username');?></a> <a href = '<?php echo site_url('promotions');?>'>Campaigns</a> &raquo; Campaigns List</div>
					</div>				
					<div class="col-lg-6 col-md-12 col-sm-12">
						<div class=" user-details">
							<div class = 'upgrade-package floatRight'><a href='<?php echo site_url("user/logout");?>'  class = 'buttonSm'><strong>Logout</strong></a></div>
							<div class = 'upgrade-package floatRight' style = 'margin-right:5px;'><?php if($this->session->userdata('member_staff') == 0){?><a href = '<?php echo  site_url("change_package/index");?>' class = 'buttonSm'>Upgrade Package</a><?php } ?></div>
						</div>
					</div>				
				</div>
			</div>	 
		</div>	 
	</div>
  </div>
</header>

<div class="main">
<?php if($is_auotresponder){ ?>
<?php echo form_open('userboard/autoresponder/change_template', array('id' => 'form_change_template','name' => 'form_change_template')); ?>
<?php }else{ ?>
<?php echo form_open('promotions/change_template', array('id' => 'form_change_template','name' => 'form_change_template')); ?>
<?php } ?>
<input type="hidden" id="preview_template_id" name="preview_template_id" value="<?php echo $template_data[0]['template_id']; ?>">
<input type="hidden" id="current_tab_page" value="<?php echo $pages[0]['id']; ?>">
<input type="hidden" id="is_page_changed" name="is_page_changed" value="0" />
<input type="hidden" id="current_container_id" name="current_container_id">
<?php if($email_template_info['campaign_email_content']==""){?>
	<input type="hidden" id="current_template_id" value="<?php echo $template_info['template_id']; ?>" />
<?php }else{ ?>
	<input type="hidden" id="current_template_id" value="-1" />
<?php } ?>
<?php echo form_close(); ?>

<!-- body - private access starts -->
	<div class = 'body-private main-container' id="main-table_">
		<!-- body - left side starts -->
		<div class = 'body-private-left'>
		<div id = 'body_private_left'>
			<div class="body-logo-private"><a href="<?php echo site_url("promotions");?>"><img src="<?php echo $this->config->item('locker').'themes/march2020/';?>images/logo.png"></a></div>
			<div class = 'body-private-campaignname' align = 'center'>
			<div align = 'center' class = 'body-private-userboard'><input type = 'button' class = 'button blueD large' name = 'btn' value = 'USERBOARD' onclick = "javascript:window.location.href = '<?php echo site_url("promotions");?>';"></div>
				
			</div>	
			<div class = 'body-private-menu items' id = 'campaign_editor_left_menu' align = 'center'>
				<div class = 'body-private-campaignname overlay-page' align = 'center'>
				
				<form action="" method="post">
				<div class="form-group">
					<label class="col-form-label">Campaign Name:</label>
					<input type="text" class = 'campaignname-input form-control' id="campaign_title" name="campaign_title" value="<?php echo $email_template_info['campaign_title']; ?>" onchange="javascript:pagechange=true;" />
			    </div>						
				</form>				
				</div>
				<div align = 'center' class = 'body-private-nextbtn'>
					<input type = 'button' class = 'button blueD large' onclick="javascript:changeLayout();" name = 'btn' value = '<< Change Layout'  id="back_step" />
					<input type = 'button' class = 'button blueD large save_campaign_changes' name = 'btn' value = 'Save & Stay.'  id="save_stay" />
				</div>
				<div class = 'clear10'></div>
				<a href = 'javascript:void(0);' class = 'blockrefh active' id = 'DIY_email_content_show'><span>+</span> Email Content</a>
				<div class = 'DIY-menu-open toolbar db blockref' id = 'DIY_email_content_shown'>
					<div class = 'DIY-content-box block-text'><a href = 'javascript:void(0);'>Text<br />Box</a></div>
					<div class = 'DIY-content-box block-image'><a href = 'javascript:void(0);'>Image<br /> Box</a></div>
					<div class = 'DIY-content-box block-image-text'><a href = 'javascript:void(0);'>Text + Image</a></div>
					<div class = 'DIY-content-box block-button'><a href = 'javascript:void(0);'>Button Link</a></div>
					<div class = 'DIY-content-box block-table'><a href = 'javascript:void(0);'>Insert Table</a></div>
					<div class = 'DIY-content-box block-divider-rule'><a href = 'javascript:void(0);'>Hor. Divider</a></div>
					<div class = 'DIY-content-box block-offer'><a href = 'javascript:void(0);'>Offer<br />Box</a></div>
					<div class = 'DIY-content-box block-youtube'><a href = 'javascript:void(0);'>Video Player</a></div>
					<div class = 'DIY-content-box block-social-media'><a href = 'javascript:void(0);'>Social Icons</a></div>
				</div>
				<div class = 'clear0'></div>
				
				<a href = 'javascript:void(0);'  onclick="imageBankDisplay();" class = 'imagebank blockrefh' id = 'DIY_images_show'><span>+</span> Images</a>
				<div class = 'DIY-menu-open dn blockref' id = 'DIY_images_shown'>					
					
					<div class = 'DIY-menu-open-sublink2 upload_image_bank' align = 'center'>
						<a href = 'javascript:void(0);'> + Click to Upload Your Image</a>
					</div>
					<div class = 'clear5'></div>
					<div  class="img_bank_div2">
						<ul class="img-bank2">
							<li class="load_images">
								<img src="<?php echo base_url() ?>locker/images/icons/ajax-loader.gif?v=6-20-13" border="0" style = 'width:43px !important;' />
							</li>
						</ul>
					</div>
					
					
					
				</div>
				<div class = 'clear0'></div>
				<a href = 'javascript:void(0);'  onclick="colorboxDisplay();" class = 'blockrefh' id = 'DIY_color_themes_show'><span>+</span> Color Themes</a>
				<div class = 'DIY-menu-open dn blockref' id = 'DIY_color_themes_shown'>
						<div class = 'DIY-menu-open-sublink'><a href = 'javascript:void(0);' onclick="javascript:$('#default-theme').slideDown();$('#custom-theme').slideUp(); $(this).addClass('active');$('#custom_theme').removeClass('active');" style = 'float:left;width:101px;margin-right:2px;' id = 'ready_to_use' class = 'active'>Ready To Use</a><a href = 'javascript:void(0);' id = 'custom_theme' onclick="javascript:customColors();$('#custom-theme').slideDown();$('#default-theme').slideUp();$(this).addClass('active');$('#ready_to_use').removeClass('active'); " style = 'float:left;width:101px;margin-left:2px;'>Custom Theme</a></div>
						<div class = 'clear5' style = 'border-top:solid 1px #AEC3D6;'></div>
						<!-- Default Themes -->
						<div id="default-theme" style = 'padding-bottom:10px;'>					
							<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" id="default_theme_color">
								<tr class="load_colors">
									<td style="text-align:center;">
										<img src="<?php echo base_url() ?>locker/images/icons/ajax-loader.gif?v=6-20-13" border="0" width = '50'/>
									</td>
								</tr>
							</table>										 
						</div>						
						<!-- Custom Theme Creation -->
						<div id="custom-theme" style = 'padding-bottom:10px;'>						
							<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tbl-normal custom_color_option" >
								<tr style = 'display:none;'>
									<td colspan="2">
										<div class="theme_color_info" style="padding: 3px 10px; margin: 0"></div>
									</td>
								</tr>
								<tr>
									<td>Background</td>
									<td align="right"><input id="background_color_txt"  class="color background-color body_bg_color" value="<?php echo $body_main; ?>" style="width:60px;height:15px;background-color:#<?php echo $body_main; ?>;color:#<?php echo $body_main; ?>" ></td>
								</tr>
								<tr>
									<td>Outer Background</td>
									<td align="right"><input id="background_outer_txt" class="color background-color outer_bg" value="<?php echo $outer_background; ?>" style="width:60px;height:15px;background-color:#<?php echo $outer_background; ?>;color:#<?php echo $outer_background; ?>;">
									</td>
								</tr>
								 
								<tr>
									<td>Footer Background</td>
									<td align="right"><input id="footer_txt" class="color background-color footer_txt_color" value="<?php echo $footer; ?>" style="width:60px;height:15px;background-color:#<?php echo $footer; ?>;color:#<?php echo $footer; ?>;" ></td>
								</tr>
								<tr>
									<td>Border</td>
									<td align="right"><input id="border_txt" class="color border-color body_border" value="<?php echo $body_border; ?>" style="width:60px;height:15px;background-color:#<?php echo $body_border; ?>;color:#<?php echo $body_border; ?>" ></td>
								</tr>
								<tr>
									<td>Border Style</td>
									<td align="right">
										<select id="body-options-border" class="border border-style" onchange="changeStyle('email_template_table','','border_style');">
											<option value="thin">Thin</option>
											<option value="solid">Normal</option>
											<option value="thick">Thick</option>
											<option value="dashed">Dashed</option>
											<option value="none">None</option>
										</select>
									</td>
								</tr>
								
								<tr>
									<td colspan = '2' height = '10'></td>									
								</tr>
								
								<tr>
									<td colspan = '2'>Theme Name: &nbsp; <input type="text" name="color_theme_name" id="color_theme_name"  style = 'width:128px;padding:0;margin:0;height:25px;border-radius:0px;font-weight:normal;font-size:none;color:none;'/></td>									
																
								</tr>
								<tr>
									<td colspan = '2' height = '1'></td>									
								</tr>
								<tr>
									<td>
										<div class = 'DIY-menu-open-sublink' style = 'float:left;width:133px;'>
											<a style = 'margin:auto;width:133px;' href = 'javascript:void(0);' onclick="saveThemeColor();"  title="Add & Save Custom Theme" > Save Custom Theme</a></div>
									</td>											
									<td>		
										<div class = 'DIY-menu-open-sublink' style = 'float:right;width:70px;text-align:right;'>
											<a  style = 'margin:auto;width:70px;' href = 'javascript:void(0);' onclick="javascript:$('#default-theme').slideDown();$('#custom-theme').slideUp(); "   title="Cancel Custom Theme" > Cancel</a>
										</div>
									</td>									
								</tr>							
							</table>							
					</div>
				</div>
				<div class = 'clear0'></div>
				<div class = 'clear0'></div>
			<div class = 'body-private-campaignname' align = 'center'>				
				<div align = 'center' class = 'body-private-nextbtn'><input type = 'button' class = 'button blueD large2 save_campaign_changes' href="<?php  if($is_auotresponder)echo  site_url('preview/index/'.$encrypted_cid); else echo site_url('preview/index/'.$encrypted_cid); ?>" name = 'btn' value = 'Save & Preview >>'  id="next_step" onclick="return false;">
				
				</div>
			</div>	
			</div>
			
		  
		  
           
        
		  
          </div>
          </div>
		<!-- body - left side ends -->
		<!-- body - right side starts -->
		<div class = 'body-private-right'>
			<!-- body - Ub-message starts -->
			<fieldset class = 'DIY-campaign-box outer_bg' style="height:auto !important;">
				<legend>Campaign Builder Area:</legend>					
				<div class="diy-editor">
					<div id="template_container" class="editor-toolbar">
						<?php if($email_template_info['campaign_email_content']!=""){?>
						<?php echo htmlspecialchars_decode (html_entity_decode($email_template_info['campaign_email_content'], ENT_QUOTES, "utf-8" )); ?>
						<?php }else{ ?>
						<?php echo $theme_css; ?>
						<?php echo ($template_info['filtered_html']); ?>
						<?php } ?>
					</div>
				  </div> 
			</fieldset>
          
        </div> 
		<!-- body - right side ends -->
	</div>
	<!-- body - private access ends -->

		
	
<!-- Button group popup box -->	
<div id="btn_dialog" style="display:none;">
	<div class = 'overlay-page'>
		<div class="form-group">
			<label class="col-form-label">Button Text:</label>		
			<input type="text" name="btnText" id="btnText" class="form-control" value="Click Here" />
		</div>
		<div class="form-group">
			<label class="col-form-label">Button Link:</label>		
			<input type="text" name="btnURL" id="btnURL" class="form-control" value="http://" />
		</div>
		<div class="form-group">
			<label class="col-form-label">Background Color:</label>
			<!-- input type="text" name="btnBGColor" id="btnBGColor" value="#AEC3D6" style="width:60px;height:15px;color:transparent;background-color:#AEC3D6;" / -->	
			<input type="text" name="btnBGColor" id="btnBGColor" class="form-control" value="#AEC3D6" style="color:transparent;background-color:#AEC3D6 !important;" />
		</div>
		<div class="form-group">
			<label class="col-form-label">Font Color:</label>
			<!-- input type="text" name="btnFontColor" id="btnFontColor" value="#111111" style="width:60px;height:15px;color:transparent;background-color:#111111;" / -->	
			<input type="text" name="btnFontColor" id="btnFontColor" class="form-control" value="#CC0000" style="color:transparent;background-color:#CC0000 !important;" />
		</div>
		<div class="form-group">
			<label class="col-form-label">Button Alignment:</label>		
			<select name="btn_alignment" class="form-control" id="btn_alignment">
				<option value="left">Left</option>
				<option value="center">Center</option>
				<option value="right">Right</option>
			</select>
		</div>	
		<div class="message_button"><button type="button" class="btn btn-primary" onclick="addButton();">Submit</button></div>
	</div>
</div>



	<!-- Table group popup box -->	
	<div id="tbl_dialog" style="display:none;">
		<div class = 'overlay-page'>
			<div class="form-group">
				<label class="col-form-label">Select the number of Rows & Columns below:</label>
			</div>
			<div class="form-group">
			<label class="col-form-label">Rows:</label>		
			<select name='tbl_rows' id='tbl_rows' class="form-control" style = 'width:100px;'>
					<option value='1'>1</option>
					<option value='2'>2</option>
					<option value='3'>3</option>
					<option value='4'>4</option>
					<option value='5'>5</option>
					<option value='6'>6</option>
					<option value='7'>7</option>
					<option value='8'>8</option>
					<option value='9'>9</option>
					<option value='10'>10</option>
					<option value='11'>11</option>
					<option value='12'>12</option>
					</select>
			</div>	
				 <div class="form-group">
			<label class="col-form-label">Cols:</label>		
			<select name='tbl_cols' id='tbl_cols' class="form-control" style = 'width:100px;'>
					<option value='1'>1</option>
					<option value='2'>2</option>
					<option value='3'>3</option>
					<option value='4'>4</option>
					<option value='5'>5</option>
					<option value='6'>6</option>
					</select>	
			</div>	
				 	
				
			<div class="message_button"><button type="button" class="btn btn-primary header_link_submit" onclick="addTable();">Submit</button></div>
		</div>
	</div>
	
	
	
	<!-- Image group popup box -->
	<div id="image_group_dialog" style="display:none;">
		<div class = 'overlay-page'>
			<form action="#" method="post" id="select-images">				
				<div class="form-group">
					<label class="col-form-label">Select the number of images to be inserted:</label>
				</div>
				<div class="form-group">
					<label class="col-form-label">
					<ul style = 'margin-left:10px;list-type:disc;'>
						<li style='margin-bottom:8px;'><a onclick="saveImageGroupOption('1')"><img src="<?php echo base_url() ?>locker/images/icons/change-header.png" alt="" style = 'border:solid 1px #cccccc;width:40px;' /></a></li>
						<li style='margin-bottom:8px;'><a onclick="saveImageGroupOption('2')"><img src="<?php echo base_url() ?>locker/images/icons/change-header.png" alt="" style = 'border:solid 1px #cccccc;width:40px;' /><img src="<?php echo base_url() ?>locker/images/icons/change-header.png" alt="" style = 'border:solid 1px #cccccc;width:40px;' /></a></li>
						<li style='margin-bottom:8px;'><a onclick="saveImageGroupOption('3')"><img src="<?php echo base_url() ?>locker/images/icons/change-header.png" alt="" style = 'border:solid 1px #cccccc;width:40px;' /><img src="<?php echo base_url() ?>locker/images/icons/change-header.png" alt="" style = 'border:solid 1px #cccccc;width:40px;' /><img src="<?php echo base_url() ?>locker/images/icons/change-header.png" alt="" style = 'border:solid 1px #cccccc;width:40px;' /></a></li>
						<li><a onclick="saveImageGroupOption('4')"><img src="<?php echo base_url() ?>locker/images/icons/change-header.png" alt="" style = 'border:solid 1px #cccccc;width:40px;' /><img src="<?php echo base_url() ?>locker/images/icons/change-header.png" alt="" style = 'border:solid 1px #cccccc;width:40px;' /><img src="<?php echo base_url() ?>locker/images/icons/change-header.png" alt="" style = 'border:solid 1px #cccccc;width:40px;' /><img src="<?php echo base_url() ?>locker/images/icons/change-header.png" alt="" style = 'border:solid 1px #cccccc;width:40px;' /></a></li>
					</ul>
					</label>
				</div>			
			</form>		
		</div>
	</div>
		
	<!-- Youtube block popup box -->
	<div id="youtube_edit_dialog" style="display:none;">		
		<div class = 'overlay-page'>
			<div class="alert alert-danger youtube_msg" role="alert" style = 'display:none;'></div>
			<div class="form-group">
				<label class="col-form-label">Paste the URL of the video you want to embed in your campaign. (Only Vimeo and YouTube are accepted):</label>
				<input type="text" name="youtube_url" class="img_link form-control" id="youtube_url" placeholder="Enter the URL here..." />
			</div>
		</div>		
		<div class="message_button">
			<button type="button" class="btn btn-primary" onclick="checkYoutubevideoOrVimeovideo();">Submit</button>
		</div>
	</div>

	<!-- Confirm message popup box -->
	<div id="confirm_msg" style="display:none;">
		<div class = 'overlay-page'>
			<div class="form-group">
				<label class="col-form-label">Are you sure you want to delete this block?</label>
			</div>			
		</div>
		<div class="message_button">						
			<button type="button" class="btn btn-primary delete-block">Yes, Delete it</button>		
		</div>
	</div>
	<!-- Confirm message popup box for Image removal-->
	<div id="confirm_msg_img_remove" style="display:none;">
		<div class = 'overlay-page confirm_msg_div'>
			<div class="form-group">
				<label class="col-form-label">Are you sure you want to delete this image?</label>
				<input type="hidden" name="element_name" id="element_name" />
			</div>			
		</div>
		<div class="message_button">						
			<button type="button" class="btn btn-primary delete-block">Yes, Delete it</button>	
		</div>		
	</div>
	<!-- Common Image caption & Link popup box -->
	<div id="image_caption_link" style="display:none;">
		<div class = 'overlay-page'>		
			<div class="form-group">
			<label>Add a link here</label>
				<input name="image_link" id="image_link" class="image_link form-control" placeholder = 'http://www.yourlink.com/' type="text"  value="http://" />
			</div>
			<div class="clear_image_link"></div>
			<label>Add a caption here</label>
			<textarea name="image_link_caption" id="image_link_caption" class = 'form-control' placeholder="Enter caption..."></textarea>
		</div>
		<div class="message_button"><button type="button" class="btn btn-primary image_option_submit" onclick="saveImageLinkCaption();">Submit</button></div>
	</div>
		
	<!-- Image caption popup box -->
	<div id="image_caption" style="display:none;">
	<div class = 'overlay-page'>
		<textarea name="image_link_caption" id="image_link_caption" class = 'form-control' placeholder="Enter caption..."></textarea>
		</div>
		<div class="message_button"><button type="button" class="btn btn-primary image_option_submit" onclick="saveImageCaption();">Submit</button></div>
	</div>
	
	
	<!-- Image Link popup box -->
	<div id="image_option" style="display:none;">
		<div class = 'overlay-page'>
			<div class="form-group"><input name="image_link" id="image_link" class="image_link form-control" placeholder = 'http://www.yourlink.com/' type="text"  value="http://" /></div>
			<div class="clear_image_link"></div>
		</div>
		<div class="message_button">
			<button type="button" class="btn btn-primary image_option_submit" onclick="saveImageLink();">Submit</button>
		</div>
	</div>
	
	
	<!-- Header Link popup box -->
	<div id="header_link_option">	
		<div class = 'overlay-page'>
			<div class="form-group"><input name="header_link_text" id="header_link_text" class="image_link form-control" placeholder = 'http://www.yourlink.com/' type="text" /></div>
		</div>
		<div class = 'message_button'>
			<button type="button" class="btn btn-primary header_link_submit" onclick="addHeaderLink();">Submit</button>
		</div>
	</div>
	<!-- Logo  popup box -->
	<div id="logo_dialog" style="display:none;">
		<div class = 'overlay-page logo_dialog'>
			<form method='post' action='#' enctype="multipart/form-data">	
				<div class="form-group"><input name="logo_file" id="logo_file" class="form-control" type="file" accept="image/*" style = 'border:solid 1px #ccc;'></div>
			</form>
		</div>
		<div class = 'message_button'></div>
	</div>
	
	<!-- Upload image in image bank popup box -->
	<div id="upload_image_bank_dialog" style="display:none;">		
			<div class = 'overlay-page image_bank_file_container'>				
				<div class="alert alert-warning" role="alert" style = 'font-size:12px;'>
					Note: Any un-used images for over 60 days will be automatically deleted from your image bank.
				</div>
				<div class="alert alert-danger img_upload_msg" role="alert" style = 'display:none;'></div>
				<form method='post' action='' enctype="multipart/form-data">
				<div class="form-group">
					<label class="col-form-label">Click Browse or Drag & Drop your images to upload</label>
					<input name="image_bank_file[]" id="image_bank_file" class="form-control" type="file" multiple  accept="image/*" style="height:150px !important;border:solid 0px #C00 !important;" />
				</div>
				</form>
				
			</div>
			<div class = 'message_button'></div>
			<!--
				<div style="clear:both;height:10px;"></div>
				<span class="uplaod_image_url">
				OR
				<p>
				Upload Image using URL
				<div style="clear:both"></div>
					<div style="flotat:left"><input name="image_bank_url" id="image_bank_url" type="text" /></div><div style="float:left;"> <a  onclick="save_image_bank_url();" class="button-red fl" style="margin:10px 0 0px 10px;"><span>Upload</span></a></div>
				</p>
				</span>
				-->
	</div>
	
	<!-- Quota Exceeded popup box -->
	<div id="block_upload_image_bank_dialog" style="display:none;">
		<div class = 'overlay-page'>
			<div class="alert alert-danger img_upload_msg" role="alert" style = 'display:none;'></div>
			<div class="form-group">
				<label class="col-form-label">Quota Exceeded</label>
			</div>
			<div class="form-group">
				<label class="col-form-label">Size of your image-bank has exceeded the allowed limit. To upload image, you need to remove some of your already uploaded images from your image-library.</label>
			</div>
		</div>
	</div>
	
	<!-- Campaign-footer address form popup box -->
	<div id="footer_link_option" style="display:none;">
		<div class = 'overlay-page'>
			<div class="alert alert-danger msg" role="alert" style = 'display:none;'></div>
			
			<div class="form-group">
			<label class="col-form-label">Company Name:</label>			
			<input type="text" name="company_name_footer" class="form-control" id="company_name_footer" value="<?php echo $user_data['company'];?>" />
		  </div>
		<div class="form-group">
			<label class="col-form-label">Address:</label>			
			<input type="text" name="address_footer" class="form-control" id="address_footer" value="<?php echo $user_data['address_line_1'];?>" />
			
		  </div>
		<div class="form-group">
			<label class="col-form-label">City:</label>			
			<input type="text" name="city_footer" id="city_footer" class="form-control" value="<?php echo $user_data['city'];?>" />
		  </div>
		<div class="form-group">
			<label class="col-form-label">State or Province:</label>			
			<input type="text" name="state_footer" id="state_footer" class="form-control"  value="<?php echo $user_data['state'];?>" />
		  </div>
		<div class="form-group">
			<label class="col-form-label">Zip/Postal Code:</label>			
			<input type="text" name="zip_footer" id="zip_footer" class="form-control"  value="<?php echo $user_data['zipcode'];?>" />
		  </div>
		<div class="form-group">
			<label class="col-form-label">Country:</label>			
			<select name="country_name_footer" id="country_name_footer" class="country_footer form-control" onchange="javascript: showCustom(this);">
							<?php
								if($user_data['country_id']){
									$selectd_id=$user_data['country_id'];
								}else{
									$selectd_id=225;
								}
								foreach($country_info as $country){
									if($country['country_id']==$selectd_id){
										echo "<option value='".$country['country_id']."' selected>".$country['country_name']."</option>";
									}else{
										echo "<option value='".$country['country_id']."'>".$country['country_name']."</option>";
									}
								}
							?>
					</select>
		  </div>
		  <div class="form-group">			
			
			<span id="country_custom_div"><input type="text" maxlength="50" class = 'form-control' name="country_custom_name_footer" id="country_custom_name_footer" value="<?php echo  $user_data['country_custom'];?>" /></span>
		  </div>
		  
		  <div class="form-group">
			<label class="col-form-label">Font Color:</label>			
			<input id="footer_color_txt" class="color font-color form-control" value="<?php echo $footer_color_txt; ?>" style="color:transparent;background-color:#<?php echo $footer_color_txt; ?> !important;"  />
		  </div>
		  <div class="form-group">
			<label class="col-form-label">Font Size:</label>			
					<div style = 'position:relative;'>
					<select onchange="changeStyle('footer','footer_font_size','font_size');" class="select_font_size form-control" id="footer_font_size" >
						<option value="9px" size="1" >1:</option>
						<option value="11px" size="2" >2:</option>
						<option value="13px" size="3" >3:</option>
						<option value="15px" size="4" >4:</option>
						<option value="17px" size="5">5:</option>
					</select>
					<div class="selected_font" style="font-size:17px;height:38px;line-height:38px;position:absolute;z-index:1001;top:0px;left:30px;">Abc</div>
					</div>
		  </div>
		  <div class="form-group">
			<label class="col-form-label">Alignment:</label>			
			<select onchange="changeFooterAlignment();"  id="footer_alignment" class="form-control">
						<option value="">Select Alignment</option>
						<option value="left">Left</option>
						<option value="center">Center</option>
						<option value="right">Right</option>
					</select>
		  </div>			
			<div class = 'message_button'><button type="button" class="btn btn-primary" onclick="updateFooter();">Submit</button></div>
		</div>	
	</div>
	
	
<!--Demo-QTips-->
<div id="myNicPanel"></div>

<div id="displaybox" style="display: none;"></div>


	
	<!-- footer - private access starts -->
	<!-- div class = 'footer-private'>
		<div class = 'footer-menu'>
			<a href = '<?php echo  site_url("support/index");?>'>Support</a> | 
			<a href = '<?php echo  site_url("contact");?>'>Contacts Us</a> |
			<a href = '<?php echo  site_url("blog");?>'>Blog</a> | 
			<a href = '<?php echo  site_url("terms");?>'>Terms & Policies</a>
		</div>
		<div class = 'footer-logo'><img src = '<?php echo $this->config->item('locker');?>images/logo.png' /></div>	
	</div>
	<div class = 'footer-copyright'>
		&copy; BoldInbox.Com <?php echo date('Y'); ?>, All Rights Reserved.
	</div -->
	<!-- footer - private access ends -->
	
</div>

<div class = 'clear0'></div>




<section class="footer-content">
	<div class="cta-footer-private">
        <div class="container">
          <div class="row">
            <div class="col-lg-12">&nbsp;</div>            
          </div>
        </div>
      </div>	
      <div class="main-footer-private diy-footer">
        <div class="container">
          <div class="row">            
            <div class="col-lg-12">                      
              
              <ul class="useful-links-private">
                <li><p>Copyright &copy; <?php echo date('Y'); ?> BoldInbox.Com. All rights reserved.</p></li>
              </ul>            
            </div>
          </div>
        </div>
      </div>
    </section>


<script>
$(document).ready(function(){		
	$(window).on('load', function() {
		$('#js-preloader').addClass('loaded');
	});
});
</script>
</body>

</html>