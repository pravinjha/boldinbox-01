<section class="section-new">
	<div class="container">
	  <div class="row">
		<div class="col-lg-12">
		  <div class="section-inner campaign-from-zip">
				<?php
				   if($campaign_data['is_autoresponder']){
					echo form_open_multipart('userboard/campaign_template_options/autoresponder/'.$campaign_data['campaign_id'].'/import_zip', array('id' => 'form_campaign_zip','name'=>'form_campaign_zip'));
				  }else{
					echo form_open_multipart('promotions/zip_import_process/', array('id' => 'form_campaign_zip','name'=>'form_campaign_zip'));
				  }             
				?>
			  <div class="row">
			   <div class="col-lg-12">
				<div class="campaign-post">
					<div class = 'row'>
				<?php if(validation_errors()){
						echo '<div style="color:#FF0000;" class="info">'.validation_errors().'</div>';
					  }	
				  ?>
				<div class="col-lg-12 col-md-12 col-sm-12">
					<div class = 'formLabel'>Campaign Name:</div>					
					<input type="text" value="<?php echo $campaign_data['campaign_title']; ?>" id="campaign_title" name="campaign_title" class="form-control" required="required" />
				</div>
				<div class="col-lg-6 col-md-8 col-sm-4">
					<div class = 'formLabel'>
						<div class = 'row'>
							<div class="col-lg-8 col-md-8 col-sm-6">Select a Zip File to Import:</div>
							<div class="col-lg-4 col-md-4 col-sm-6 show-instrcutions">
								<a href = 'javascript:void(0);' id = 'zip_instructions_show'  data-toggle='modal' data-target='#messageBox' class = 'font6 font600 floatRight'>Show Instructions</a>
							</div> 
						</div>
						
					</div>
					<?php 
						 echo form_upload(array('name'=>'campaign_import_zip_file','id'=>'campaign_import_zip_file','value'=>set_value('campaign_import_zip_file'), 'class'=>'form-control-file' ));
					?>
					<input type="hidden" name="enc_cid" value="<?php echo $campaign_data['enc_cid']; ?>" />
				</div>
				                
				<div class="col-lg-12 col-md-12 col-sm-12">
					<div class = 'formLabel'>Footer Address:</div>
					<div class = "campaignFooter">
						<div class = "row">
							<div class="col-sm-10">					
								<div id='footer_address'>
									<?php if( '' != $campaign_data['user_data']['company']){ 
											echo "&copy; ".$campaign_data['user_data']['company']."<br />".$campaign_data['user_data']['address_line_1']." | ".$campaign_data['user_data']['city'].", ".$campaign_data['user_data']['state']." - ".$campaign_data['user_data']['zipcode'].", ". $campaign_data['country_name'];
										//	print_r($campaign_data['country_info']);
									 }else{?>	
										&copy; Company name<br />Street Address | City, State - Zipcode, Country
									<?php } ?>	
								</div>									
							</div>
							<div class="col-sm-2">
								<?PHP
									$footerAddressSubmitButton = htmlentities('<button type="button" class="btn btn-primary" data-dismiss="modal" onclick = "save_user_info();">Save Changes</button>');
								?>
								<div class = 'btn_edit'><a class = 'pink round round32 floatRight' data-toggle='modal' data-target='#messageBox' href ='javascript:void(0);' onclick="javascript:updateFooterAddress('<?PHP echo $footerAddressSubmitButton;?>');"><i class = 'fa fa-edit'></i></a></div>
							</div>
						</div>
					</div>
				</div> 
				<div class="col-lg-12 col-md-12 col-sm-12">&nbsp;</div>
				<div class="col-lg-12 col-md-12 col-sm-12">
					<button type = 'button' name = 'import_zip_now' id = 'import_zip_now' value = 'Save & Preview' class = 'blue rectangle'>Save & Preview</button>
				</div> 
				</div> 
				</div> 
				</div> 
			  </div>
			</form>
		  </div>
		</div>
	  </div>
	</div>
</section>
<script type="text/javascript">
  $("#import_zip_now").on("click",function(){
		$("#form_campaign_zip").submit();
  }); 
  
  $("#zip_instructions_show").on("click",function(){
	   displayAlertMessage('Instructon to Import a Zip File','','0',true,350,150,false,'');
	   $( "#message" ).html( $("#zip_instructions").html() ); 
  });
</script>
<div id = 'zip_instructions' style = 'display:none;'>
<ol>
<li>Include one index.html file.</li>
<li>All your web friendly images (PNG, GIF, JPG, JPEG).</li>
<li>Your CSS file(s) or inline CSS in your HTML.</li>
<li>Make sure your file size is less than 2MB.</li>
</ol>
</div>