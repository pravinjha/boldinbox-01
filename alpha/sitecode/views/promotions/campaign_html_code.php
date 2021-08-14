<script type="text/javascript" language="javascript">
function submitPasteHtmlFrm(){
  document.form_campaign_paste_code.action.value='paste_code';
  document.form_campaign_paste_code.submit();
}
</script>
<?php
if($campaign_data['is_autoresponder']){
		echo form_open_multipart('userboard/campaign_template_options/autoresponder/'.$campaign_data['campaign_id'], array('id' => 'form_campaign_paste_code','name'=>'form_campaign_paste_code','onsubmit'=>"return false;"));
	  }else{
		echo form_open_multipart('promotions/html_code_process/', array('id' => 'form_campaign_paste_code','name'=>'form_campaign_paste_code','accept-charset'=>'utf-8', 'onsubmit'=>"return false;"));
	  }
?>	  
<fieldset class = 'other-campaign-box'>
	<legend>Campaign from HTML code</legend>
	<div class = 'create-campaign-other'>
	<?php if(validation_errors()){
        echo '<div style="color:#FF0000;" class="info">'.validation_errors().'</div>';
      }
      // display all messages
      if (is_array($messages)){
        echo '<div class="info">';
        foreach ($messages as $type => $msgs){
			foreach ($msgs as $message) echo ('<span class="' .  $type .'">' . $message . '</span>');        
        }
        echo '</div>';
      }    
    ?>
			<strong>Campaign Name:</strong> 
				<input type="text" value="<?php echo $campaign_data['campaign_title']; ?>" id="campaign_title" name="campaign_title" class="textbox" /><br /><br />
			<strong>Paste HTML:</strong> 
              <textarea name="paste_code" id="paste_code" class="paste_text_html_width" style="white-space: pre-wrap;"><?php echo $campaign_data['campaign_content']; ?></textarea>
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
                <?php  echo form_submit(array('name'=>'campaign_paste_code','onclick'=>'submitPasteHtmlFrm();','value'=>'Save & Preview >>','class'=>'button blueD large')); ?>
               
           
</fieldset>
 </form>
<!--[/body]-->
<script type="text/javascript">
  $("#paste_code").focus();
</script>
