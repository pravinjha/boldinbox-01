<table width="100%" cellspacing="0" cellpadding="3">
	<tr><th colspan="2">Custom Confirmation Message</th></tr>
	<tr><td width="50%">
		<table width="100%" cellspacing="0" cellpadding="2">
		<tr><th>From Name: </th><td><input type="text" size="40"  id="from_name" maxlength="100" value="<?php echo $signup_from['form'][0]['from_name'];?>" name="from_name"></td></tr>
		<tr><th>From Email: </th><td>
		<select name="from_email" id="from_email"  class="select_language ui-widget ui-state-default ui-corner-all">
                  <?php
                    foreach($signup_from['email_id'] as $fromEml){
                      if($signup_from['form'][0]['from_email'] == $fromEml)
                      echo "<option value='$fromEml' selected>{$fromEml}</option>";
                      else
                      echo "<option value='$fromEml'>{$fromEml}</option>";
                    }
                  ?>
                </select>
		<span class="autotresponder_list_div" style="margin-left:5px;"><a href="javascript:void(0);" id="btn_add_other_eml" class="edit-interval">Add New</a></span>
                  <span><a href="javascript:void(0);" onclick="javascript: updateFromEmailDpd();" id="btn_refresh" style="margin-left:5px;"><img src="<?php echo $this->config->item('locker');?>images/reload2.png" height="14" alt="Refresh" align="absmiddle" /></a></span>		
		</td></tr>
		<tr><th>Subject: </th><td><input type="text" id="subject" maxlength="100" value="<?php echo $signup_from['form'][0]['subject'];?>" name="subject"></td></tr>
		</table>
	</td><td><table width="100%" cellspacing="0" cellpadding="2">
		<tr><th>Confirmation Email Message: <br/>
		<div class="signup-tbl-confirm-msg">
                  <textarea class="confirmation_emai_message" name="confirmation_emai_message" id="confirmation_emai_message" ><?php echo $signup_from['form'][0]['confirmation_emai_message'];?></textarea>
                  <?php echo base_url();?>{confirmation url}
                </div>
		</td></tr>
		</table>
		</td></tr>
	<tr><th colspan="2">Landing Page</th></tr>	
	<tr><th>Signup "Thank You" Page: </th><td><input type="text" size="40" id="singup_thank_you_message_url" value="<?php echo $signup_from['form'][0]['singup_thank_you_message_url'];?>" name="singup_thank_you_message_url"></td></tr>
	<tr><th>Confirmation Landing Page: </th><td><input type="text" size="40"  id="confirmation_thanks_you_message_url" value="<?php echo $signup_from['form'][0]['confirmation_thanks_you_message_url'];?>" name="confirmation_thanks_you_message_url"></td></tr>
	<tr><th colspan="2">Internationalization</th></tr>	
	<tr><th>Language: </th><td>
	<select name="form_language" id="form_language" class="select_language ui-widget ui-state-default ui-corner-all" onchange="updateLanguage(this.value)">
                  <?php
          $selLang = (trim($signup_from['form'][0]['form_language']) != '')?$signup_from['form'][0]['form_language']:'en';
                    foreach($signup_froms_language as $c => $lang){
                      if($c == $selLang)
                      echo "<option value='$c' selected>$lang</option>";
                      else
                      echo "<option value='$c'>$lang</option>";
                    }
                  ?>
                </select>
	</td></tr>
	
	<tr><th colspan="2"><input type="hidden" name="custom_frm_action" id="custom_frm_action" value="submit" />
              <button id="save-advanced-section" class="btn confirm inline-block"  type="button" name="listing_submit">Save</button>
              <button id="cancel-advanced-section" class="btn cancel inline-block" type="button" name="listing_submit">Cancel</button></th></tr>	
	</table>