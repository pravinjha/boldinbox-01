	<!-- body - campaign other starts -->	
	<?php
		if($campaign_data['is_autoresponder']){
		  echo form_open_multipart('campaign_template_options/autoresponder/'.$campaign_data['campaign_id'].'/text_email', array('id' => 'form_campaign_text_email','name'=>'form_campaign_text_email'));
		}else{
		  echo form_open_multipart('promotions/plain_text_process/', array('id' => 'form_campaign_text_email','name'=>'form_campaign_text_email'));
		}
	?>	
	
	<fieldset class = 'other-campaign-box'>
		<legend>Plain Text Email</legend>
		<div class = 'create-campaign-other'>	
		<strong>Campaign Name:</strong> 
				<input type="text" value="<?php echo $campaign_data['campaign_title']; ?>" id="campaign_title" name="campaign_title" class="textbox" /><br /><br />
		<strong>Text content:</strong> 		
		<textarea name="campaign_text_email" id="campaign_text_email" class="paste_text_html_width"><?php echo $campaign_data['campaign_content']; ?></textarea>
			<input type="hidden" name="enc_cid" value="<?php echo $campaign_data['enc_cid']; ?>" />
		</div>
		<div class = 'create-campaign-other-footer-address'>
			<span id='footer_address'>
		<?php if( '' != $campaign_data['user_data']['company']){ 
				echo "&copy; ".$campaign_data['user_data']['company']."<br />".$campaign_data['user_data']['address_line_1']." | ".$campaign_data['user_data']['city'].", ".$campaign_data['user_data']['state']." - ".$campaign_data['user_data']['zipcode'].", ". $campaign_data['country_name'];
			//	print_r($campaign_data['country_info']);
		 }else{?>	
			&copy; Company name<br />Street Address | City, State - Zipcode, Country
		<?php } ?>	
			</span>
			<div class = 'btn_edit'><a href ="javascript:void(0);" onclick="javascript:updateFooterAddress();"><img  title="Edit Footer Address" src = "<?php echo base_url() ?>locker/images/icons/edit.png" /></a></div>
		</div>
		<div class = 'clear10'></div>
	<?php  echo form_submit(array('name'=>'text_email','value'=>'Save & Preview >>', 'class'=>'button blueD large')); ?>
	</fieldset>
	</form>  



<!--[CAN-SPAM form for Account info]-->

<script type="text/javascript">
  $("#campaign_text_email").focus();
</script>