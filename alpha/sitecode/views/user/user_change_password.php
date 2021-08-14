<?php
echo form_open('user/change_password/', array('id' => 'frmChangePassword'));
?>

<fieldset class = 'DIY-campaign-options-sendmail' style = 'padding:10px;'>
	<legend>Change Password:</legend>
	<?php
	if(validation_errors()){
	  echo '<div class="info">'.validation_errors().'</div>';
	}
  ?>

  <?php
  // display all messages

  if (is_array($messages)):
	echo '<div class="info" style="width: auto; display: inline-block; margin-bottom: 20px">';
	foreach ($messages as $type => $msgs):
	  foreach ($msgs as $message):
		echo ('<span class="' .  $type .'">' . $message . '</span>');
	  endforeach;
	endforeach;
	echo '</div>';
  endif;

  ?>
	<div class = 'user_account'>
		Current Password:<br /><?php echo form_password(array('name'=>'member_password','id'=>'member_password','maxlength'=>50,'value'=>set_value('member_password'))) ; ?>		
		<br />
		New Password:<br />				
		 <?php echo form_password(array('name'=>'member_new_password','id'=>'member_new_password','maxlength'=>50,'value'=>set_value('member_new_password'))); ?><br />
		Confirm New Password:<br />				
		<?php echo form_password(array('name'=>'member_confirm_password','id'=>'member_confirm_password','maxlength'=>50 ,'value'=>set_value('member_confirm_password'))); ?><br />			
	</div>
</fieldset>

<div align = 'center' class = 'page_top_button_row'>				
	<div align = 'center'>
		<?php echo form_submit(array('name' => 'btnChangePasssword', 'id' => 'btnChangePasssword','class'=>'button blue large textCap','content' => 'Change Password'), 'Change Password'); ?>
				
	</div>
</div>
<?php
echo form_hidden('action','submit');
echo form_close();
?>