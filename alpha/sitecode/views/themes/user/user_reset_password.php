<div class = 'body-public'>
	<div class = 'body-box-login'>
<h2>Reset Password</h2>	
<div id="credential" class="content key-points">
  <?php			
		echo form_open('user/reset_password/'.$token, array('id' => 'frmResetPassword', 'class' => 'body-form'));
	?>
    
	<div class="clear"></div>
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
		<?php echo  form_password(array('name'=>'password','id'=>'password','maxlength'=>50,'size'=>40 ,'class'=>'west' ,'value'=>set_value('password'),'title'=>'New Password','placeholder'=>'New Password'));?>
		 
		<?php echo  form_password(array('name'=>'confirm_password','id'=>'confirm_password','maxlength'=>50,'size'=>40 ,'class'=>'west' ,'value'=>set_value('confirm_password'),'title'=>'Confirm Password','placeholder'=>'Confirm Password'));?>
		 
		<?php echo form_submit(array('name' => 'submit', 'id' => 'submit','content' => 'submit','class'=>'button-input'), 'Submit'); ?>
		 
	<?php 
		echo form_close();
	?> 
 </div>
 </div> 
 </div> 
 
