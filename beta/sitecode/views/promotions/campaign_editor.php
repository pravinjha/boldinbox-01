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
<!-- CSS
================================================== -->
<link rel="stylesheet" type="text/css" media="all" href="<?php echo base_url(); ?>promotions/editorstyle/<?php echo time();?>">
<link rel="stylesheet" href="<?php echo $this->config->item('locker');?>css/base.css?as3">
<link rel="stylesheet" href="<?php echo $this->config->item('locker');?>css/utils.css">
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
<!--[if IE]>
<script type="text/javascript">ie = 1;</script>
<![endif]-->
</head>
<body>
<div class = 'main'>
<textarea id='temp'></textarea>
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
		<div class = 'header-private-user'><strong>Username: <?php echo $this->session->userdata('member_username');?> | </strong><?php if($this->session->userdata('member_staff') == 0){?><a href = '<?php echo  site_url("change_package/index");?>'><strong>Upgrade Now</strong></a> <strong>|</strong><?php } ?> <a href='<?php echo site_url("user/logout");?>'><strong>Logout</strong></a></div>		
	</div>
	<!-- header - private access ends -->
	

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
			<div class = 'body-logo-private'><a href ='<?php if($is_auotresponder)echo site_url("userboard/autoresponder");else echo site_url("promotions");?>'><img src = '<?php echo $this->config->item('locker');?>images/logo-blue.png' /></a></div>
			
			<div class = 'body-private-campaignname' align = 'center'>
			<div align = 'center' class = 'body-private-userboard'><input type = 'button' class = 'button blueD large' name = 'btn' value = 'USERBOARD' onclick = "javascript:window.location.href = '<?php echo site_url("promotions");?>';"></div>
				
			</div>	
			<div class = 'body-private-menu items' id = 'campaign_editor_left_menu' align = 'center'>
				<div class = 'body-private-campaignname' align = 'center'>
				Campaign Name: <br />
				<form action="" method="post">
				<input type="text" class = 'campaignname-input' id="campaign_title" name="campaign_title" value="<?php echo $email_template_info['campaign_title']; ?>" onchange="javascript:pagechange=true;" />				
				<div align = 'center' class = 'body-private-nextbtn'>
					<input type = 'button' class = 'button blueD large' onclick="javascript:changeLayout();" name = 'btn' value = '<< Change Layout'  id="back_step" />
					<input type = 'button' class = 'button blueD large save_campaign_changes' name = 'btn' value = 'Save & Stay.'  id="save_stay" />
				</div>	
				</form>
				<div class = 'clear10'></div>
				</div>
				<a href = 'javascript:void(0);' class = 'blockrefh active' id = 'DIY_email_content_show'><span>+</span> Email Content</a>
				<div class = 'DIY-menu-open toolbar db blockref' id = 'DIY_email_content_shown'>
					<div class = 'DIY-content-box block-banner'><a href = 'javascript:void(0);'>Banner<br />Box</a></div>
					<div class = 'DIY-content-box block-title'><a href = 'javascript:void(0);'>Title<br />Box</a></div>	
					<div class = 'DIY-content-box block-text'><a href = 'javascript:void(0);'>Text<br />Box</a></div>
					<div class = 'DIY-content-box block-image'><a href = 'javascript:void(0);'>Image<br /> Box</a></div>
					<div class = 'DIY-content-box block-image-text'><a href = 'javascript:void(0);'>Text + Image</a></div>
					<div class = 'DIY-content-box block-button'><a href = 'javascript:void(0);'>Button Link</a></div>
					<div class = 'DIY-content-box block-table'><a href = 'javascript:void(0);'>Insert Table</a></div>
					<div class = 'DIY-content-box block-divider-rule'><a href = 'javascript:void(0);'>Hor. Divider</a></div>
					<div class = 'DIY-content-box block-offer'><a href = 'javascript:void(0);'>Offer<br />Box</a></div>
					<div class = 'DIY-content-box block-youtube'><a href = 'javascript:void(0);'>Video Player</a></div>
					<div class = 'DIY-content-box block-social-media'><a href = 'javascript:void(0);'>Social Icons</a></div>
					<div class = 'DIY-content-box block-partition'><a href = 'javascript:void(0);'>Partition Box</a></div>			
				</div>
				<div class = 'clear0'></div>
				
				<a href = 'javascript:void(0);'  onclick="imageBankDisplay();" class = 'imagebank blockrefh' id = 'DIY_images_show'><span>+</span> Images</a>
				<div class = 'DIY-menu-open dn blockref' id = 'DIY_images_shown'>					
					
					<div class = 'DIY-menu-open-sublink upload_image_bank' align = 'center'><a style = "margin:auto;" href = 'javascript:void(0);'> + Click to Upload Your Image</a></div>
					<div class = 'clear5' style = 'border-bottom:solid 1px #AEC3D6;'></div>
					<div  class="img_bank_div">
						<ul class="img-bank">
							<li class="load_images">
								<img src="<?php echo base_url() ?>locker/images/icons/ajax-loader.gif?v=6-20-13" border="0" width = '50'/>
							</li>
						</ul>
					</div>
					
					
					
				</div>
				<div class = 'clear0'></div>
				<a href = 'javascript:void(0);'  onclick="colorboxDisplay();" class = 'blockrefh' id = 'DIY_color_themes_show'><span>+</span> Color Themes</a>
				<div class = 'DIY-menu-open dn blockref' id = 'DIY_color_themes_shown'>
						<div class = 'DIY-menu-open-sublink'><a href = 'javascript:void(0);' onclick="javascript:$('#default-theme').slideDown();$('#custom-theme').slideUp(); $(this).addClass('active');$('#custom_theme').removeClass('active');" style = 'float:left;width:48%;margin-right:2px;' id = 'ready_to_use' class = 'active'>Ready To Use</a><a href = 'javascript:void(0);' id = 'custom_theme' onclick="javascript:customColors();$('#custom-theme').slideDown();$('#default-theme').slideUp();$(this).addClass('active');$('#ready_to_use').removeClass('active'); " style = 'float:left;width:48%;margin-left:2px;'>Custom Theme</a></div>
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
				I am done <br />
				<div align = 'center' class = 'body-private-nextbtn'><input type = 'button' class = 'button blueD large save_campaign_changes' href="<?php  if($is_auotresponder)echo  site_url('preview/index/'.$encrypted_cid); else echo site_url('preview/index/'.$encrypted_cid); ?>" name = 'btn' value = 'Save & Preview >>'  id="next_step" onclick="return false;">
				
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

	
	
	
	
	<!-- Block Style popup -->
	<div class="img_style" style="display:none;">	
		<table border = '0' cellpadding = '0' cellspacing = '0' style = 'margin-top:10px;margin-left:15px;'>
			
			<!--tr>
				<td width = '50%'><strong>Background-color:</strong></td>
				<td><input type="text" name="txt_blkBGColor" id="txt_blkBGColor" value="#AEC3D6" style="width:60px;height:15px;color:transparent;background-color:#AEC3D6;" /></td>
			</tr-->
			<tr>
				<td width = '50%'><strong>Border-color:</strong></td>
				<td><input type="text" name="txt_blkBrdrColor" id="txt_blkBrdrColor" value="#111111" style="width:60px;height:15px;color:transparent;background-color:#111111;" /></td>
			</tr>
			<tr>
				<td width = '50%'><strong>Border-width:</strong></td>
				<td>
					<select name="sel_brdrWidth" id="sel_brdrWidth">
						<option value="0">0</option>
						<option value="1">1</option>
						<option value="2">2</option>
						<option value="3">3</option>
						<option value="4">4</option>
						<option value="5">5</option>
					</select>
				</td>
			</tr>			
			<tr><td colspan="2"><p id="btnmsg" style="color:#ff0000; font-weight:bold;"></p></td></tr>	
		</table>	
		
		<div class="message_button">
			<span class="image_group_span"><a onclick="updateImgStyle();">Submit</a></span>
		</div>
	</div>
	
	
	
	
	
	<!-- Block Style popup -->
	<div class="blk_style" style="display:none;">	
		<table border = '0' cellpadding = '0' cellspacing = '0' style = 'margin-top:10px;margin-left:15px;'>
			
			<tr>
				<td width = '50%'><strong>Background-color:</strong></td>
				<td><input type="text" name="txt_blkBGColor" id="txt_blkBGColor" value="#AEC3D6" style="width:60px;height:15px;color:transparent;background-color:#AEC3D6;" /></td>
			</tr>
			 		
			<tr><td colspan="2"><p id="btnmsg" style="color:#ff0000; font-weight:bold;"></p></td></tr>	
		</table>	
		
		<div class="message_button">
			<span class="image_group_span"><a onclick="updateBlockStyle();">Submit</a></span>
		</div>
	</div>
	
	
	<!-- Button group popup box -->	
	<div id="btn_dialog" style="display:none;">	
		<table border = '0' cellpadding = '0' cellspacing = '0' style = 'margin-top:10px;margin-left:15px;'>
			<tr>
				<td width = '50%'><strong>Button-text:</strong></td>
				<td><input type="text" name="btnText" id="btnText" value="Click Here" /></td>
			</tr>
			<tr>
				<td width = '50%'><strong>Button-url:</strong></td>
				<td><input type="text" name="btnURL" id="btnURL" value="http://" /></td>
			</tr>
			<tr>
				<td width = '50%'><strong>Background-color:</strong></td>
				<td><input type="text" name="btnBGColor" id="btnBGColor" value="#AEC3D6" style="width:60px;height:15px;color:transparent;background-color:#AEC3D6;" /></td>
			</tr>
			<tr>
				<td width = '50%'><strong>Font-color:</strong></td>
				<td><input type="text" name="btnFontColor" id="btnFontColor" value="#111111" style="width:60px;height:15px;color:transparent;background-color:#111111;" /></td>
			</tr>
			<tr>
				<td width = '50%'><strong>Alignment:</strong></td>
				<td>
					<select name="btn_alignment" id="btn_alignment">
						<option value="left">Left</option>
						<option value="center">Center</option>
						<option value="right">Right</option>
					</select>
				</td>
			</tr>			
			<tr><td colspan="2"><p id="btnmsg" style="color:#ff0000; font-weight:bold;"></p></td></tr>	
		</table>	
		
		<div class="message_button">
			<span class="image_group_span"><a onclick="addButton();">Submit</a></span>
		</div>
	</div>
	<!-- Table group popup box -->	
	<div id="tbl_dialog" style="display:none;">
		<p style="padding: 0 15px">
			<strong>Select the number of Rows and columns:</strong>
		<br />
		<br />
		
			Rows: <select name='tbl_rows' id='tbl_rows'>
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
			Cols: <select name='tbl_cols' id='tbl_cols'>
				<option value='1'>1</option>
				<option value='2'>2</option>
				<option value='3'>3</option>
				<option value='4'>4</option>
				<option value='5'>5</option>
				<option value='6'>6</option>
				</select>	
		</p>
		<div class="message_button">			
			<a onclick="addTable();" class="header_link_submit" style = 'margin-top:12px !important;'>Submit</a>
		</div>
	</div>
	<!-- Image group popup box -->
	<div id="image_group_dialog" style="display:none;">
			<form action="#" method="post" id="select-images">
				
				<p style="margin: 10px 23px 0">
					<strong>Select the number of images to be inserted:</strong>
				</p>
					<ol>
						<li><a onclick="saveImageGroupOption('1')"><img src="<?php echo base_url() ?>locker/images/icons/change-header.png" alt="" style = 'border:solid 1px #cccccc;' /></a></li>
						<li><a onclick="saveImageGroupOption('2')"><img src="<?php echo base_url() ?>locker/images/icons/change-header.png" alt="" style = 'border:solid 1px #cccccc;' /><img src="<?php echo base_url() ?>locker/images/icons/change-header.png" alt="" style = 'border:solid 1px #cccccc;' /></a></li>
						<li><a onclick="saveImageGroupOption('3')"><img src="<?php echo base_url() ?>locker/images/icons/change-header.png" alt="" style = 'border:solid 1px #cccccc;' /><img src="<?php echo base_url() ?>locker/images/icons/change-header.png" alt="" style = 'border:solid 1px #cccccc;' /><img src="<?php echo base_url() ?>locker/images/icons/change-header.png" alt="" style = 'border:solid 1px #cccccc;' /></a></li>
						<li><a onclick="saveImageGroupOption('4')"><img src="<?php echo base_url() ?>locker/images/icons/change-header.png" alt="" style = 'border:solid 1px #cccccc;' /><img src="<?php echo base_url() ?>locker/images/icons/change-header.png" alt="" style = 'border:solid 1px #cccccc;' /><img src="<?php echo base_url() ?>locker/images/icons/change-header.png" alt="" style = 'border:solid 1px #cccccc;' /><img src="<?php echo base_url() ?>locker/images/icons/change-header.png" alt="" style = 'border:solid 1px #cccccc;' /></a></li>
					</ol>
			</form>
		<div class="clear"></div>
	</div>
		
	<!-- Youtube block popup box -->
	<div id="youtube_edit_dialog" style="display:none;">		
		<p style="padding: 0 10px">
			Paste the URL of the video you want to embed in your campaign. (Only Vimeo and YouTube are accepted)
		</p>
		<span colspan="2" class="youtube_msg" style="color:red;font-weight:bold;"></span>
		<p style="padding: 0 10px">
			<input type="text" name="youtube_url" class="img_link" id="youtube_url" style="width: 400px" placeholder="Enter the URL here..." />
		</p>
		<div class="message_button">
			<a onclick="checkYoutubevideoOrVimeovideo();">Submit</a>
		</div>
	</div>

	<!-- Confirm message popup box -->
	<div id="confirm_msg" style="display:none;">
		<div class="confirm_msg_div">			
			<p>Are you sure you want to delete this block?</p>
			<input type="hidden" name="element_name" id="element_name" />
			<div class="message_button">				
				<a href = 'javascript:void(0);' class="cancel_delete-link">No, keep it.</a>				
				<a href = 'javascript:void(0);' class="delete-block">Yes, Delete it.</a>
			</div>
		</div>
	</div>
	<!-- Confirm message popup box for Image removal-->
	<div id="confirm_msg_img_remove" style="display:none;">
		<div class="confirm_msg_div">			
			<p>Are you sure you want to delete this image?</p>
			<input type="hidden" name="element_name" id="element_name" />
			<div class="message_button">
				<a href = 'javascript:void(0);' class="cancel_delete-link">No, keep it.</a>				
				<a href = 'javascript:void(0);' class="delete-block">Yes, Delete it.</a>
			
			</div>
		</div>
	</div>
	<!-- Image caption popup box -->
	<div id="image_caption" style="display:none;">
		<div style="clear:both"></div>
		<textarea name="image_link_caption" id="image_link_caption" placeholder="Enter caption..."></textarea>
		<div style="clear:both;height:10px;"></div>
		<div class="message_button">
			<a onclick="saveImageCaption();" class="image_option_submit">Submit</a>
		</div>
	</div>
	<!-- Image Link popup box -->
	<div id="image_option" style="display:none;">		
		<div style="clear:both"></div>
		<input name="image_link" id="image_link" class="image_link" type="text"  value="http://" />
		<div class="clear_image_link"></div>
		<div class="message_button">
			<a onclick="saveImageLink();" class="image_option_submit" >Submit</a>
		</div>
	</div>
	<!-- Header Link popup box -->
	<div id="header_link_option">		
		<input name="header_link_text" id="header_link_text" class="image_link" type="text" value = 'http://'/>
		<div class = 'message_button'><a onclick="addHeaderLink();" class="header_link_submit" style = 'margin-top:12px !important;'>Submit</a></div>
	</div>
	<!-- Logo  popup box -->
	<div id="logo_dialog" style="display:none;">
		<div class="logo_dialog">
			<form action="#" method="post">			
			<input name="logo_file" id="logo_file" type="file" style = 'border:solid 1px #ccc;'>
			</form>
			<div class = 'message_button'></div>
		</div>
	</div>
	<!-- Upload image in image bank popup box -->
	<div id="upload_image_bank_dialog" style="display:none;height:160px; margin:5px 0px;">		
			<div id="image_bank_file_container">
				<p class="img_upload_msg"></p>
				<input name="image_bank_file" id="image_bank_file" type="file" />
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
	</div>
	
	<!-- Quota Exceeded popup box -->
	<div id="block_upload_image_bank_dialog" style="display:none;height:160px; margin:5px 0px;">
		<div style="float:left;margin:0px 20px;width:300px;">
			<p class="img_upload_msg"></p>
			<p>Quota Exceeded</p><br/>
			<div style="clear:both"></div>
			<p>
				Size of your image-bank has exceeded the allowed limit. To upload image, you need to remove some of your already uploaded image from your image-library.
			</p>
		</div>
	</div>
	
	<!-- Campaign-footer address form popup box -->
	<div id="footer_link_option" style="display:none;">
		<table width="95%" align="center" style="margin-top: 8px">
			<tr>
				<td colspan="2" class="msg" style="font-color:red;"></td>
			</tr>
			<tr>
				<td class="td_footer">Company Name</td>
				<td><input type="text" name="company_name_footer" id="company_name_footer" value="<?php echo $user_data['company'];?>" size="40"/></td>
			</tr>
			<tr>
				<td class="td_footer">Address</td>
				<td><input type="text" name="address_footer" id="address_footer" value="<?php echo $user_data['address_line_1'];?>" size="40" /></td>
			</tr>
			<tr>
				<td class="td_footer">City</td>
				<td><input type="text" name="city_footer" id="city_footer"  value="<?php echo $user_data['city'];?>" size="40" /></td>
			</tr>
			<tr>
				<td class="td_footer">State or Province</td>
				<td><input type="text" name="state_footer" id="state_footer"  value="<?php echo $user_data['state'];?>" size="40" /></td>
			</tr>
			<tr>
				<td class="td_footer">Zip/Postal Code</td>
				<td><input type="text" name="zip_footer" id="zip_footer"  value="<?php echo $user_data['zipcode'];?>" size="40" /></td>
			</tr>
			<tr>
				<td class="td_footer">Country</td>
				<td>
					<select name="country_name_footer" id="country_name_footer" style="height:34px;width:241px" class="country_footer" onchange="javascript: showCustom(this);">
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
					<div style="height:29px;"><span id="country_custom_div"><input type="text" maxlength="50" name="country_custom_name_footer" id="country_custom_name_footer" value="<?php echo  $user_data['country_custom'];?>" /></span></div>
				</td>
			</tr>
			<tr>
				<td class="td_footer">Font Color</td>
				<td>
				<input id="footer_color_txt" class="color font-color" value="<?php echo $footer_color_txt; ?>" style="width:60px;height:15px;color:transparent;background-color:#<?php echo $footer_color_txt; ?>;"  />
				</td>
			</tr>
			<tr>
				<td class="td_footer">Font Size</td>
				<td>
					<select onchange="changeStyle('footer','footer_font_size','font_size');" class="select_font_size" id="footer_font_size">
						<option value="9px" size="1" >1</option>
						<option value="11px" size="2" >2</option>
						<option value="13px" size="3" >3</option>
						<option value="15px" size="4" >4</option>
						<option value="17px" size="5">5</option>
					</select>
					<span class="selected_font" style="font-size:17px;">Abc</span>
				</td>
			</tr>
			<tr>
				<td class="td_footer">Alignment</td>
				<td>
					<select onchange="changeFooterAlignment();"  id="footer_alignment">
						<option value="">select alignment</option>
						<option value="left">Left</option>
						<option value="center">Center</option>
						<option value="right">Right</option>
					</select>
				</td>
			</tr>
			<tr>
				<td colspan="2">&nbsp;</td>
			</tr>
			<tr>
				<td colspan="2"><div class = 'message_button'><a onclick="updateFooter();">Submit</a></div></td>
			</tr>
		</table>
	</div>
	
	<!-- Unsaved changes alert popup box -->
	<div style="display:none;" id="preview_msg" class="">
		<h2 style="font-weight: 300;background-color: #f8f8f8;padding: 8px 12px !important;color: #333;border-bottom:1px solid #ddd;">Notice</h2>
		<div style="padding: 5px 15px">
			<p>Your campaign may have unsaved changes. Would you like to save it?</p>
			<button class="btn save_campaign_changes add">Save</button>
			<button class="btn discard_campaign_changes cancel">Cancel Changes</button>
			<a class="btn cancel cancel_campaign_changes" title="">Close</a>
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
</body>

</html>