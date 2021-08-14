<section class="section-new">
	<div class="container">
		<div class="row">
			<div class="col-lg-12">
				<div class="section-inner cp_form">
					<?php
						echo form_open('user/change_password/', array('id' => 'frmChangePassword'));
					?>
						<div class="row">							
							<div class="col-lg-12">
								  <?php
								  // display all messages

								  if (is_array($messages)):
									echo '<div class="alert alert-warning">';
									foreach ($messages as $type => $msgs):
									  foreach ($msgs as $message):
										echo ('<span class="' .  $type .'">' . $message . '</span>');
									  endforeach;
									endforeach;
									echo '</div>';
								  endif;

								  ?>
							</div>
							<?php  if(validation_errors()){ ?>
								<div class="col-lg-12">
								  <div class = 'alert alert-danger'><?PHP echo validation_errors(); ?></div>	
								</div>							  
							<?php } ?>	
							<div class="col-lg-12">
								<div class="campaign-post">
									<div class = 'row'>
										<div class="col-lg-6 col-md-6 col-sm-12">
											<div class = 'formLabel'>Current Password:</div>											
											<input type="password" value="" id="member_password" name="member_password" maxlength = '50' class="form-control" required="required" />
										</div>
										<div class="col-lg-6 col-md-6 col-sm-12">
											&nbsp;
										</div>
										<div class="col-lg-6 col-md-6 col-sm-12">
											<div class = 'formLabel'>New Password:</div>											
											<input type="password" value="" id="member_new_password" name="member_new_password" maxlength = '50' class="form-control" required="required" />
										</div>
										<div class="col-lg-6 col-md-6 col-sm-12">
											<div class = 'formLabel'>Confirm New Password:</div>											
											<input type="password" value="" id="member_confirm_password" name="member_confirm_password" maxlength = '50' class="form-control" required="required" />
										</div>
										
										<div class="col-sm-12">&nbsp;</div>
										<div class="col-lg-6 col-md-6 col-sm-12">					
											<button type = 'submit'  name="btnChangePasssword" id="btnChangePasssword" value="Change Password" content="Change Password" class = 'blue rectangle'>Change Password</button>
										</div>
									</div>
								</div>
							</div>	
						</div>
						<?php
						echo form_hidden('action','submit');						
						?>
					</form>
				</div>
			</div>
		</div>
	</div>
</section>