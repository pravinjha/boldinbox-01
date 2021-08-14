<section class="section-new">
	<div class="container">
	  <div class="row">
		<div class="col-lg-12">
		  <div class="section-inner">
			<?php
				//<!-- form open tag -->
				if($campaign_data['is_autoresponder']){
				  echo form_open_multipart('campaign_template_options/autoresponder/'.$campaign_data['campaign_id'].'/text_email', array('id' => 'form_campaign_text_email','name'=>'form_campaign_text_email'));
				}else{
				  echo form_open_multipart('promotions/plain_text_process/', array('id' => 'form_campaign_text_email','name'=>'form_campaign_text_email'));
				}
			?>	
			  <div class="row">
			  
			  <!-- THIS IS MESSAGE BAR -->
			  <div class="col-lg-12">
				<div class="alert alert-danger" role="alert">
				  This is a danger alert—check it out! This is a danger alert—check it out! This is a danger alert—check it out! This is a danger alert—check it out! This is a danger alert—check it out! This is a danger alert—check it out! This is a danger alert—check it out! This is a danger alert—check it out! This is a danger alert—check it out! This is a danger alert—check it out! This is a danger alert—check it out! This is a danger alert—check it out! This is a danger alert—check it out! This is a danger alert—check it out! This is a danger alert—check it out! This is a danger alert—check it out! This is a danger alert—check it out! 
				</div>
				</div>	
				<!-- THIS IS MESSAGE BAR -->
				
			   <div class="col-lg-12">
				<div class="campaign-post">
					<div class = 'row'>
				<div class="col-lg-12 col-md-12 col-sm-12">
					<div class = 'formLabel'>Campaign Name:</div>
					<input type="text" value="<?php echo $campaign_data['campaign_title']; ?>" id="campaign_title" name="campaign_title" class="form-control" required="required" />
				</div>
				<div class="col-lg-12 col-md-12 col-sm-12">
					<div class = 'formLabel'>Plain Text Email Content:</div>
					<textarea name="campaign_text_email" id="campaign_text_email" class="form-control"><?php echo $campaign_data['campaign_content']; ?></textarea>
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
					<button type = 'submit' name = 'text_email' value = 'Save & Preview' class = 'blue rectangle'>Save & Preview</button>
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