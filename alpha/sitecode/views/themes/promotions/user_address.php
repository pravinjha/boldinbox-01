<div class = 'overlay-page'>
  <div class="form-group">
	<label for="recipient-name" class="col-form-label">Company Name:</label>
	<input type="text" name="company_name" class="form-control" id="company_name" value="<?php echo $campaign_data['user_data']['company'];?>" />
  </div>
  <div class="form-group">
	<label for="message-text" class="col-form-label">Address:</label>
	<input type="text" name="address" class="form-control" id="address" value="<?php echo $campaign_data['user_data']['address_line_1'];?>" />
  </div>
  <div class="form-group">
	<label for="message-text" class="col-form-label">City:</label>
	<input type="text" name="city" class="form-control" id="city" value="<?php echo $campaign_data['user_data']['city'];?>" />
  </div><div class="form-group">
	<label for="message-text" class="col-form-label">State or Province:</label>
	<input type="text" name="state" class="form-control" id="state" value="<?php echo $campaign_data['user_data']['state'];?>" />
  </div><div class="form-group">
	<label for="message-text" class="col-form-label">Zip/Postal Code:</label>
	<input type="text" name="zip" class="form-control" id="zip" value="<?php echo $campaign_data['user_data']['zipcode'];?>" />
  </div>
  <div class="form-group">
	<label for="message-text" class="col-form-label">Country:</label>
	<select name="country" class="form-control" id="country" onchange="javascript: showCustom(this);" >
            <?php
              if($campaign_data['user_data']['country_id']){
                $selectd_id=$campaign_data['user_data']['country_id'];
              }else{
                $selectd_id=225;
              }
              foreach($campaign_data['country_info'] as $country){
                if($country['country_id']==$selectd_id){
                  echo "<option value='".$country['country_id']."' selected>".$country['country_name']."</option>";
                }else{
                  echo "<option value='".$country['country_id']."'>".$country['country_name']."</option>";
                }
              }
            ?>
          </select>	
  </div>
  <div class="form-group">
	<span id="country_custom_div" style="<?php if($country['country_name'] != 'Custom') echo 'display:none';?>"><input type="text" maxlength="50" name="country_custom" id="country_custom" class="form-control" value="<?php echo  $campaign_data['user_data']['country_custom'];?>" /></span>
  </div>
</div>