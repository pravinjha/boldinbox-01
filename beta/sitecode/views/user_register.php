<!-- script src='https://www.google.com/recaptcha/api.js'></script -->
<!-- start: REGISTER BOX -->
			<div class="box-register" style="display:block;">
				<h3>Sign Up</h3>
				<p>
					Enter your account details below:
				</p>
				<form action='<?php echo site_url("user/register/");?>' id="signup_form" method="post" class = 'form-register body-form' onsubmit="javascript:loader();">
					<div class="errorHandler alert alert-danger no-display">
						<i class="fa fa-remove-sign"></i> You have some form errors. Please check below.
						<?php if(validation_errors()) echo validation_errors(); ?>
						<?php
							// display all messages
							if (is_array($messages)):
							  echo '<div id="messages" class="msg info">';
							  foreach ($messages as $type => $msgs):
								  foreach ($msgs as $message):
									echo ('<span class="' .  $type .' error">' . $message . '</span>');
								  endforeach;
							  endforeach;
							  echo '</div>';
							endif;
						?>
					</div>
					<fieldset>
						<div class="form-group">
							<span class="input-icon">
							<?php $returnEmal = ($this->input->post('emailOuter')!='')?$this->input->post('emailOuter'):set_value('email'); ?>
							<input type="text" class="form-control" name="email" id="email" maxlength="50" placeholder="Valid Email Id" value="<?php echo $returnEmal;?>" autocorrect='off' autocapitalize='off' title="Valid Email Id">
							<i class="fa fa-envelope"></i> </span>
						</div>
						<div class="form-group">
							<input type="text" class="form-control" name="username" id="username" maxlength="50" value="<?php echo set_value('username');?>" autocorrect="off" autocapitalize="off" title="Username" placeholder="Username">
						</div>
						<div class="form-group">
							<span class="input-icon">
							<input type="password" name="password" class="form-control" value="" maxlength="50" placeholder = 'Password'  title="Pasword" />
							<i class="fa fa-lock"></i> </span>
						</div>
						  
						
						<div class="form-group">
							<span class="input-icon">
								<input type="password" class="form-control" name="con_password" id="con_password" placeholder="Confirm Password">
								<i class="fa fa-lock"></i> </span>
						</div>
						<div class="form-group">
							<div>
								<label for="agree" class="checkbox-inline">
									<input type="checkbox" class="grey agree" id="agree" name="agree">
									I agree to the Terms of Service and Privacy Policy 
									<!-- div class="g-recaptcha" data-sitekey="6LcN4wYUAAAAAKf4cDNbc_VT01tFh2IPZ_4S5s3W"></div -->
								</label>
							</div>
						</div>
						<div class="form-actions">
							<a class="btn btn-light-grey go-back" href="<?php echo site_url('user/login');?>">
								<i class="fa fa-circle-arrow-left"></i> Back
							</a>
							<button type="submit" name="btnRegister" id="btnRegister" class="btn btn-bricky pull-right" value="save">
								Submit <i class="fa fa-arrow-circle-right"></i>
							</button>
						</div>
					</fieldset>
				</form>
			</div>
			<!-- end: REGISTER BOX -->

<script type="text/javascript">
function loader(){
 jQuery('.msg').html("<img border='0'  style='margin:0;' src='<?php echo $this->config->item('locker');?>images/icons/ajax-loader.gif' />");
}
$(function() {
$('#email').focus(function(){	$('.msg').hide(1000);	});
$('#username').focus(function(){	$('.msg').hide(1000);});
});
</script>

