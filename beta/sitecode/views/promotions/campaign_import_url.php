<fieldset class = 'other-campaign-box'>
	<legend>Generate Campaign from URL:</legend>
      <?php if(validation_errors()){
        echo '<div style="color:#FF0000;" class="info">'.validation_errors().'</div>';
      }?>
      <?php
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
      ?>
         
		
          <div  class="campaign_import_url">
            <?php
              if($campaign_data['is_autoresponder']){
                echo form_open_multipart('userboard/campaign_template_options/autoresponder/'.$campaign_data['campaign_id'], array('id' => 'form_campaign_import','name'=>'form_campaign_import'));
              }else{
                echo form_open_multipart('promotions/url_import_process/', array('id' => 'form_campaign_import','name'=>'form_campaign_import'));
              }
             
            ?>
			<strong>Campaign Name:</strong> 
				<input type="text" value="<?php echo $campaign_data['campaign_title']; ?>" id="campaign_title" name="campaign_title" class="textbox" /><br /><br />
			<strong>URL to import:</strong>
            <?php 
			 echo form_input(array('name'=>'campaign_import_url','id'=>'campaign_import_url','class'=>'textbox','maxlength'=>250,'value'=>$campaign_data['import_campaign_url'], 'placeholder'=>'http://www.domain.com/page.html' ));
			
			//echo form_submit(array('name'=>'campaign_import_url_submit','value'=>'Import','onclick'=>'document.form_campaign_import.action.value=\'import_url\'','class'=>'button blueD confirm'))."<p>(eg. http://www.domain.com/page.html)</p>"; 
			echo "<div class = 'clear10'></div>".form_submit(array('name'=>'campaign_import_url_submit','value'=>'Save & Preview >>', 'class'=>'button blueD large')); 
			?>
            <input type="hidden" name="enc_cid" value="<?php echo $campaign_data['enc_cid']; ?>" />
            </form>
          </div>
		  <div style="float:right;"><input type="button" onclick="javascript:updateFooterAddress();" name="btnEdit" id="btnEdit" class="button blueD large" value=" Edit Address " /></div>
</fieldset>         
<script type="text/javascript">
  $("#campaign_import_url").focus();
</script>
<!--[/body]-->
