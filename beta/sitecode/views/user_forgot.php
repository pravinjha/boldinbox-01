
			<!-- start: FORGOT BOX -->
			<div class="box-forgot" style="display:block;">
				<h3>Forget Password?</h3>
				<p>
					Enter your e-mail address below to reset your password.
				</p>
				
          
				<form class="form-forgot" method="post" id="forgotPwdBlock" name="forgot" action="<?php echo site_url('user/forgot');?>">
					<?php  echo form_hidden('action','submit'); ?>
					<div class="errorHandler alert alert-danger no-display">
						<i class="fa fa-remove-sign"></i> You have some form errors. Please check below.
					</div>
					<fieldset>
						<div class="form-group">
							<span class="input-icon">
								<input type="email" class="form-control" name="email_address" id="email_address" maxlength="50" autocorrect="off" autocapitalize="off" placeholder="Registered Email Address" value="<?php echo set_value('email_address');?>">
								<i class="fa fa-envelope"></i> </span>
						</div>
						<div class="form-actions">
							<a class="btn btn-light-grey go-back" href="<?php echo site_url('user/login');?>">
								<i class="fa fa-circle-arrow-left"></i> Back
							</a>
							<button type="submit" class="btn btn-bricky pull-right">
								Submit <i class="fa fa-arrow-circle-right"></i>
							</button>
						</div>
					</fieldset>
				</form>
			</div>
			<!-- end: FORGOT BOX -->
			