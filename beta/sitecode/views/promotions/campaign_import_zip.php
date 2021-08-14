<fieldset class = 'other-campaign-box'>
	<legend>Generate Campaign from zip-file:</legend>
	<div class = 'create-campaign-other'>
	<?php if(validation_errors()){
        echo '<div style="color:#FF0000;" class="info">'.validation_errors().'</div>';
      }
	  
      // display all messages
      if (is_array($messages)):
        echo '<div class="info">';
        foreach ($messages as $type => $msgs):
          foreach ($msgs as $message):
            echo ('<span class="' .  $type .'">' . $message . '</span>');
          endforeach;
        endforeach;
        echo '</div>';
      endif;
     

	  if($campaign_data['is_autoresponder']){
		echo form_open_multipart('userboard/campaign_template_options/autoresponder/'.$campaign_data['campaign_id'].'/import_zip', array('id' => 'form_campaign_zip','name'=>'form_campaign_zip'));
	  }else{
		echo form_open_multipart('promotions/zip_import_process/', array('id' => 'form_campaign_zip','name'=>'form_campaign_zip'));
	  }
	?>
			<strong>Campaign Name:</strong> 
			<input type="text" value="<?php echo $campaign_data['campaign_title']; ?>" id="campaign_title" name="campaign_title" class="textbox" /><br /><br />
			<strong>Select a Zip File to Import:</strong><br />
			<div style = 'margin-top: 5px;border: solid 1px #CCCCCC;width: 50%;padding:2px;'>
            <?php echo form_upload(array('name'=>'campaign_import_zip_file','id'=>'campaign_import_zip_file','value'=>set_value('campaign_import_zip_file') ));?>
			 </div> <div style = 'width: 50%;text-align:right;padding-left:4px;border: solid 0px #CCCCCC;'><a href = 'javascript:void(0);' id = 'zip_instructions_show'><b>Show Instructions</b></a></div>
			 <br />
		   <input type = 'button' class="button blueD large" id="import_zip_now" value = 'Save & Preview >>' />
            
            <input type="hidden" name="enc_cid" value="<?php echo $campaign_data['enc_cid']; ?>" />

            <script type="text/javascript">
              $("#import_zip_now").bind("click",function(){
                    $("#form_campaign_zip").submit();
              }); 
			  
			  $("#zip_instructions_show").live("click",function(){
                   displayAlertMessage('Instructon to Import a Zip File','','0',true,350,150,false,'');
				   $( "#message" ).html( $("#zip_instructions").html() ); 
              });
            </script>
            </form>

			<div id = 'zip_instructions' style = 'display:none;'>
			<ol>
			<li>Include one index.html file.</li>
			<li>All your web friendly images (PNG, GIF, JPG, JPEG).</li>
			<li>Your CSS file(s) or inline CSS in your HTML.</li>
			<li>Make sure your file size is less than 2MB.</li>
			</ol>
			</div>
</div>
</fieldset>