<form action="<?php echo site_url('change_plan/process'); ?>" class="form-website" method="post" name="billingForm">
<fieldset class = 'DIY-campaign-options-sendmail' style = 'padding:10px;'>
	<legend>Upgrade Your Account</legend>
	 <?php  if(count($messages) > 0) { ?>
	
      <span style="color:red">All fields are mandatory.</span>
      <br/>
      <br/>
    <?php } ?>
	
    <div class = 'user_account'>		
		First Name:<br />
		<input type="text" size="45" name="firstname" id="firstname" value="<?php if(!empty($posted['firstname']))echo $posted['firstname']; else echo $arrUserProfile[0]['first_name']; ?>" />
		<br />
		Last Name:<br />	
		<input type="text" size="45" name="lastname" id="lastname" value="<?php if(!empty($posted['lastname']))echo $posted['lastname']; else echo $arrUserProfile[0]['last_name']; ?>" />
		<br />
Email:<br />	<input type="text" size="45" name="email" id="email" value="<?php if(!empty($posted['email']))echo $posted['email']; else echo $arrUserProfile[0]['email_address']; ?>" />
		<br />
		Phone:<br /><input type="text" size="45" name="phone" value="<?php if(!empty($posted['phone']))echo $posted['phone']; else echo $arrUserProfile[0]['phone_number']; ?>" />				
		<br />
		
	</div>
	<div class = 'user_account' >		
		Address:<br />	  
		<input type="text" size="45" name="address" value="<?php  if(!empty($posted['address']))echo $posted['address']; else echo $arrUserProfile[0]['address']; ?>" />
		<br />
		
		City:<br />	
		<input type="text" size="45" name="city" id="city" value="<?php  if(!empty($posted['city']))echo $posted['city']; else echo $arrUserProfile[0]['city']; ?>" />
		<br />State:<br />
		<input type="text" size="45" name="state" value="<?php  if(!empty($posted['state']))echo $posted['state']; else echo $arrUserProfile[0]['state']; ?>" />
		<br />Zipcode:<br />
		<input type="text" size="45" name="zipcode" value="<?php  if(!empty($posted['zipcode']))echo $posted['zipcode']; else echo $arrUserProfile[0]['zip']; ?>" />
		<br />Country: <br />
		<select name="country" id="country" onchange="javascript: showCustom(this);" style = 'height:30px;'>           
			<?php
			foreach($country_info as $c){
				if($c['country_name'] == $posted['country'])
					echo "<option value='".$c['country_name']."' selected>".$c['country_name']."</option>";
				elseif($c['country_name'] == $arrUserProfile[0]['country'])
					echo "<option value='".$c['country_name']."' selected>".$c['country_name']."</option>";
				else	
					echo "<option value='".$c['country_name']."'>".$c['country_name']."</option>";
			
			}
			?>	
			</select>
		
		
	</div> 
</fieldset>

	<div align = 'center' class = 'page_top_button_row'>
	<div align = 'center'>
		<input type="submit" class="button blue large textCap" name="submit" value="Next >> " title="Submit" alt="Submit" style="" />		
	</div>
<br/><br/>
<span style="font-weight:bold;font-size:14px;color:#ff0000;">FOR ANY ISSUES REGARDING PAYMENT, PLEASE CONTACT US AT +91-8130972229</span>
    </div>    
    </form>