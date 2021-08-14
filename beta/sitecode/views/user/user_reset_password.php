<!-- start: FORGOT BOX -->
			<div class="box-forgot" style="display:block;">
				<h3>Reset Password</h3>
				<p>
					Create your new password.
					<?php 
		if(validation_errors()){
			echo '<div style="color:#FF0000;" class="info">'.validation_errors().'</div>';
		}
		?>
		 <?php
				// display all messages

				if (is_array($messages)):
					echo '<div class="info" style="background:none; border:none;">';
					foreach ($messages as $type => $msgs):
						foreach ($msgs as $message):
							echo ('<span class="' .  $type .'">' . $message . '</span>');
						endforeach;
					endforeach;
					echo '</div>';
				endif;

		?>
				</p>
				<?php			
					echo form_open('user/reset_password/'.$token, array('id' => 'frmResetPassword', 'class' => 'body-form'));
				?>
					<div class="errorHandler alert alert-danger no-display">
						<i class="fa fa-remove-sign"></i> You have some form errors. Please check below.
					</div>
					<fieldset>
						<div class="form-group">
							<span class="input-icon">
								<?php echo  form_password(array('name'=>'password','id'=>'password','maxlength'=>50,'size'=>40 ,'class'=>'west' ,'value'=>set_value('password'),'title'=>'New Password','placeholder'=>'New Password'));?>
								<i class="fa fa-lock"></i> </span>
						</div>
						<div class="form-group">
							<span class="input-icon">
								<?php echo  form_password(array('name'=>'confirm_password','id'=>'confirm_password','maxlength'=>50,'size'=>40 ,'class'=>'west' ,'value'=>set_value('confirm_password'),'title'=>'Confirm Password','placeholder'=>'Confirm Password'));?>
								<i class="fa fa-lock"></i> </span>
						</div>
						<div class="form-actions">
							<a class="btn btn-light-grey go-back">
								<i class="fa fa-circle-arrow-left"></i> Back
							</a>
							<button type="submit" class="btn btn-bricky pull-right" name="submit" id="submit" value="Submit">
								Submit <i class="fa fa-arrow-circle-right"></i>
							</button>
						</div>
					</fieldset>
				<?php 
					echo form_close();
				?> 
			</div>
			<!-- end: FORGOT BOX -->