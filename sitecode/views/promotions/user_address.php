<div id="user_account_option">
    <div id="user-contact-info-form-container">
    
      <div id="user-contact-info-form">
       
        <table>
          <tr><td><b>Company Name</b></td><td><input type="text" name="company_name" id="company_name" size="40" value="<?php echo $campaign_data['user_data']['company'];?>" /></td></tr>
          <tr><td><b>Address</b></td><td><input type="text" name="address" id="address" size="40" value="<?php echo $campaign_data['user_data']['address_line_1'];?>" /></td></tr>
          <tr><td><b>City</b></td><td><input type="text" name="city" id="city" size="40" value="<?php echo $campaign_data['user_data']['city'];?>" /></td></tr>
          <tr><td><b>State or Province</b></td><td><input type="text" name="state" id="state" size="40" value="<?php echo $campaign_data['user_data']['state'];?>" /></td></tr>
          <tr><td><b>Zip/Postal Code</b></td><td><input type="text" name="zip" id="zip" size="40" value="<?php echo $campaign_data['user_data']['zipcode'];?>" /></td></tr>
          <tr><td><b>Country</b></td><td>
		  <select name="country" id="country" onchange="javascript: showCustom(this);" style="border:solid 1px #CCCCCC;font-size:14px;height: 30px; padding: 0px 0; width: 200px;  margin-left: 0px;background:#fafafa;color:#666;">
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
		  </td></tr>
		  <tr><td colspan="2" align="right"><div style="height:29px;"><span id="country_custom_div" style="margin-left:103px;<?php if($country['country_name'] != 'Custom') echo 'display:none';?>"><input type="text" maxlength="50" size="40" name="country_custom" id="country_custom" value="<?php echo  $campaign_data['user_data']['country_custom'];?>" /></span></div></td></tr>
			</table>        
      </div>
      <div class="message_button">
        <a href="javascript:void(0);"  onclick="save_user_info();" class="btn add">Submit</a>
      </div>
    </div>
</div>
