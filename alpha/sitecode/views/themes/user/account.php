<script type="text/javascript">
// Popup login form
$(document).ready(function(){
  if('245' != $('#country').val())
    $('div#country_custom_div').hide();
  else
    $('div#country_custom_div').show();
});
function showCustom(dpdCountry){	
  // if('245' == dpdCountry.value || '163' == dpdCountry.value){	
  if('163' == dpdCountry.value){	
    $('div#country_custom_div').show();
  }else{
    $('div#country_custom_div').hide();
  }
}
</script>
<section class="section-new">
	<div class="container">
		<div class="row">
			<div class="col-lg-12">
				<div class="section-inner">
					<form  method="post" class="form-website" name="account_update" >
						<div class="row">
							<div class="col-sm-12">
								<?php
								/// display all messages
								if (is_array($messages)): echo "<div class='alert alert-warning'>"; foreach ($messages as $type => $msgs):	foreach ($msgs as $message): echo ($message . '<br />'); endforeach; endforeach; echo "</div>"; endif;
								?>
								<?php
								if(validation_errors()){ echo '<div class="alert alert-danger">'.validation_errors().'</div>';
								}
								?>
							</div>
							<?PHP if($user_info['show_sent_counter']){ ?>
							<div class="col-lg-12">
								<div class = 'alert alert-info'>Total email campaigns sent in current cycle: <?php echo $user_credit_card_info['campaign_sent_counter']; ?>  of <?php echo $user_credit_card_info['max_campaign_quota'];?> </div>	
							</div>
							<?PHP } ?>
							<div class="col-lg-12">
								<div class="campaign-post">
									<div class = 'row'>
										<div class="col-lg-6 col-md-6 col-sm-12">
											<div class = 'formLabel'>First Name:</div>											
											<input type="text" value="<?php echo $user_info['first_name']; ?>" id="first_name" name="first_name" class="form-control" required="required" />
										</div>
										<div class="col-lg-6 col-md-6 col-sm-12">
											<div class = 'formLabel'>Last Name:</div>											
											<input type="text" name="last_name" id="last_name" value="<?php echo $user_info['last_name']; ?>" class="form-control" required="required" />
										</div>
										<div class="col-lg-6 col-md-6 col-sm-12">
											<div class = 'formLabel'>Email Address:</div>											
											<input type="text" name="email_address" id="email_address" value="<?php echo $user_info['email_address']; ?>" class="form-control" required="required" />
										</div>
										<div class="col-lg-6 col-md-6 col-sm-12">
											<div class = 'formLabel'>Company:</div>											
											<input type="text" name="company" id="company" value="<?php echo $user_info['company']; ?>" class="form-control" required="required" />
										</div>
										<div class="col-lg-6 col-md-6 col-sm-12">
											<div class = 'formLabel'>Address:</div>											
											<input type="text" name="address_line_1" id="address_line_1" value="<?php echo $user_info['address_line_1']; ?>" class="form-control" required="required" />
										</div>
										<div class="col-lg-6 col-md-6 col-sm-12">
											<div class = 'formLabel'>City:</div>											
											<input type="text" name="city" id="city" value="<?php echo $user_info['city']; ?>" class="form-control" required="required" />
										</div>
										<div class="col-lg-6 col-md-6 col-sm-12">
											<div class = 'formLabel'>State:</div>											
											<input type="text" name="state" id="state" value="<?php echo $user_info['state']; ?>" class="form-control" required="required" />
										</div>
										<div class="col-lg-6 col-md-6 col-sm-12">
											<div class = 'formLabel'>Zip Code:</div>											
											<input type="text" name="zipcode" id="zipcode" value="<?php echo $user_info['zipcode']; ?>" class="form-control" required="required" />
										</div>
										<div class="col-lg-6 col-md-6 col-sm-12">
											<div class = 'formLabel'>Country:</div>											
											<select name="country" id="country" onchange="javascript: showCustom(this);" class="form-control" required="required">
											<?php
											  foreach($country_info as $country){
												if($country['country_id']==$user_info['country']){
												  echo "<option value='".$country['country_id']."' selected>".$country['country_name']."</option>";
												}else{
												  echo "<option value='".$country['country_id']."'>".$country['country_name']."</option>";
												}
											  }
											?>
											</select>
											<div id="country_custom_div">
											<input type="text" maxlength="50" name="country_custom" placeholder = 'Country Name' id="country_custom" value="<?php echo $user_info['country_custom'];?>" class="form-control" required="required" />
											</div>
										</div>
										<div class="col-lg-6 col-md-6 col-sm-12">
											<div class = 'formLabel'>Time Zone:</div>											
											<select name="member_time_zone" id="member_time_zone" class="form-control" required="required">
												<?php		
												$timezones = getTimezones();
												foreach($timezones as $k=>$v){	
												if($v == $user_info['member_time_zone'])$sel = "selected";else $sel = '';
												echo"<option value='$v' {$sel}>$k</option>";
												}			
												?>
											</select>											
										</div>										
										
										<div class="col-sm-12">&nbsp;</div>
										<div class="col-lg-6 col-md-6 col-sm-12">					
											<button type = 'submit'  name="action" value="Save Details" class = 'blue rectangle'>Save Details</button>
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