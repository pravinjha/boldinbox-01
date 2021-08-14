<script type="text/javascript">
// Popup login form
$(document).ready(function(){
  if('245' != $('#country').val())
    $('div#country_custom_div').hide();
  else
    $('div#country_custom_div').show();
});
function showCustom(dpdCountry){
  if('245' == dpdCountry.value)
    $('div#country_custom_div').show();
  else
    $('div#country_custom_div').hide();
}
</script>
<!-- div align = 'left' class = 'page_top_button_row'>		
	<div class = 'page_top_button_row_2'>Account Profile</div>
</div -->



<form  method="post" class="form-website" name="account_update" >
<fieldset class = 'DIY-campaign-options-sendmail' style = 'padding:10px;'>
	<legend>Account Profile:</legend>
	<?php
/// display all messages
if (is_array($messages)):
  echo "<div class='info'>";
  foreach ($messages as $type => $msgs):
	foreach ($msgs as $message):
	  echo ($message . '<br />');
	endforeach;
  endforeach;
  echo "</div>";
endif;
?>
<?php
  if(validation_errors()){
  echo '<div class="info">'.validation_errors().'</div>';
  }
?>
	<div class = 'user_account'>
		First Name:<br />				
		<input type="text" size="45" name="first_name" value="<?php echo $user_info['first_name']; ?>"/><br />
		Last Name:<br />				
		<input type="text" size="45" name="last_name" value="<?php echo $user_info['last_name']; ?>"/><br />
		Email Address:<br />				
		<input type="text" size="45" name="email_address" value="<?php echo $user_info['email_address']; ?>"/><br />
		Company:<br />				
		<input type="text" size="45" name="company" value="<?php echo $user_info['company']; ?>"/><br />
		Address:<br />				
		<input type="text" size="45" name="address_line_1" value="<?php echo $user_info['address_line_1']; ?>"/><br />
		
			
			</div>
			<div class = 'user_account' >
				
		City:<br />				
		<input type="text" size="45" name="city" value="<?php echo $user_info['city']; ?>"/><br />
				State:<br />				
		<input type="text" size="45" name="state" value="<?php echo $user_info['state']; ?>"/><br />
		Zip Code:<br />				
		<input type="text" size="45" name="zipcode" value="<?php echo $user_info['zipcode']; ?>"/><br />
		Country:<br />				
		<select name="country" id="country" onchange="javascript: showCustom(this);" style = 'height:30px;'>
            <?php
              foreach($country_info as $country){
                if($country['country_id']==$user_info['country']){
                  echo "<option value='".$country['country_id']."' selected>".$country['country_name']."</option>";
                }else{
                  echo "<option value='".$country['country_id']."'>".$country['country_name']."</option>";
                }
              }
            ?>
            </select><br /><div id="country_custom_div"><strong>Country Name</strong></td><td><input type="text" maxlength="50" name="country_custom" id="country_custom" value="<?php echo $user_info['country_custom'];?>" />
            </div>
		
		Time Zone:<br />				
		<select name="member_time_zone" style = 'height:30px;'>
			<?php		
			$timezones = getTimezones();
			foreach($timezones as $k=>$v){	
			if($v == $user_info['member_time_zone'])$sel = "selected";else $sel = '';
			echo"<option value='$v' {$sel}>$k</option>";
			}			
			?>
			</select><br />
			</div>
			
</fieldset>			

<?php
	if($user_info['show_sent_counter']){
		echo "<div style='padding-left:40px;font: italic bold 12px/30px Georgia, serif;color:green;'>Total email sent in this cycle: ".$user_credit_card_info['campaign_sent_counter'] .' of '.$user_credit_card_info['max_campaign_quota']. "</div>";
	}	
	?>
 

<div align = 'center' class = 'page_top_button_row'>	
	
	<div align = 'center'>
		<input type="submit" class="button blue large textCap" name="action" value="Save Details" title="Submit" alt="Submit" style="" />		
	</div>
</div>
</form>
<?php
function getTimezones(){
return
array (
  '(GMT-12:00) International Date Line West' => 'Pacific/Wake',
  '(GMT-11:00) Midway Islands Time' => 'Pacific/Apia',
  '(GMT-10:00) Hawaii Standard Time' => 'Pacific/Honolulu',
  '(GMT-09:00) Alaska Standard Time' => 'America/Anchorage',  
  '(GMT-08:00) Pacific Standard Time' => 'America/Los_Angeles',
  '(GMT-07:00) Mountain/Phoenix Standard Time' => 'America/Phoenix',   
  '(GMT-06:00) Central Standard Time' => 'America/Chicago',  
  '(GMT-05:00) Eastern Standard Time' => 'America/New_York',
  '(GMT-05:00) Indiana Eastern Standard Time' => 'America/Indiana/Indianapolis',
  '(GMT-04:00) Puerto Rico and US Virgin Islands Time' => 'America/Halifax',
  '(GMT-03:30) Canada Newfoundland Time' => 'America/St_Johns',
  '(GMT-03:00) Brazil-Eastern/Argentina Standard Time' => 'America/Sao_Paulo',  
  '(GMT-02:00) Mid-Atlantic' => 'America/Noronha',
  '(GMT-01:00) Central African Time' => 'Atlantic/Azores',
  '(GMT-01:00) Cape Verde Is.' => 'Atlantic/Cape_Verde', 
  '(GMT) Greenwich Mean Time : Dublin' => 'Europe/London',  
  '(GMT+01:00) European Central Time' => 'Europe/Berlin', 
  '(GMT+02:00) Eastern European Time' => 'Europe/Istanbul', 
  '(GMT+02:00) (Arabic) Egypt Standard Time' => 'Asia/Jerusalem',
  '(GMT+03:00) Eastern African Time' => 'Africa/Nairobi',
  '(GMT+03:30) Middle East Time' => 'Asia/Tehran',
  '(GMT+04:00) Near East Time' => 'Asia/Muscat',
  '(GMT+04:30) Kabul' => 'Asia/Kabul',
  '(GMT+05:00) Pakistan Lahore Time' => 'Asia/Karachi',
  '(GMT+05:30) India Standard Time' => 'Asia/Calcutta',
  '(GMT+05:45) Kathmandu' => 'Asia/Katmandu',
  '(GMT+06:00) Bangladesh Standard Time' => 'Asia/Dhaka',
  '(GMT+06:00) Sri Jayawardenepura' => 'Asia/Colombo',
  '(GMT+06:30) Rangoon' => 'Asia/Rangoon',
  '(GMT+07:00) Vietnam Standard Time' => 'Asia/Bangkok',
  '(GMT+07:00) Jakarta' => 'Asia/Bangkok',
  '(GMT+08:00) China Taiwan Time' => 'Asia/Hong_Kong',
  '(GMT+09:00) Japan Standard Time' => 'Asia/Tokyo',
  '(GMT+09:30) Australia Central Time' => 'Australia/Adelaide',
  '(GMT+10:00) Australia Eastern Time' => 'Australia/Sydney',
  '(GMT+11:00) Solomon Standard Time' => 'Asia/Magadan',
  '(GMT+12:00) New Zealand Standard Time' => 'Pacific/Auckland', 
  '(GMT+13:00) Nuku\'alofa' => 'Pacific/Tongatapu',
);
}
?>