<div class = 'body-public'>
	<div class = 'body-box-support' style = 'float:left;margin-left:5px;'>		
		<div class = 'body-form-text2'>Contact Us</div>
		
		<div class = 'contact_form'>
			<?php echo form_open(base_url().'home/contact/', array('id' => 'signup', 'class' => 'body-form contact-form'));?>
			<?php echo validation_errors('<span class="error">', '</span>'); ?>
			<?php if(isset($msg1)){ ?>
			<?php echo $msg1; ?>
			<?php } ?>
			
			<div class = 'body-form-desc'>Please fill in the form below send us your queries, feedback and / or suggestions.</div>
			<div><input type="text" name="name" placeholder = 'Your Name' value="<?php echo $name;?>" /></div>		
			<input type="text" name="email" placeholder = 'Your Valid Email' value="<?php echo $email;?>" />			
			<input type="text" name="phone" placeholder = 'Your Phone #'  value="<?php echo $phone;?>" />			
			<textarea name="desc" placeholder = 'Your Message to Us' style = 'height:100px;'><?php echo $desc;?></textarea>
			<input type="hidden" name="word" value="<?php echo $word;?>" />			
			<div><div class="g-recaptcha" data-sitekey="6LcN4wYUAAAAAKf4cDNbc_VT01tFh2IPZ_4S5s3W"></div></div>
			<input type="submit" class="button blue" value = 'Submit Now' />
			</form>
		</div>
		<div class = 'contact_info'>
			<div class = 'body-form-desc'>You can also write to us directly for any "<b>Customer Support</b>" at the following addresses:</div>
			<div class = 'body-form-desc'><b>Email:</b><div class = 'clear10'></div> <a href = 'mailo:support@boldinbox.com'><b>support@boldinbox.com</b></a></div>
			
			<div class = 'body-form-desc'><b>Contact:</b><div class = 'clear10'></div> <a href="tel:+918130972229">+91 8130 972 229</a></div>
			<div class = 'body-form-desc'><a href="skype:sumitthakkar?chat"><img src="https://secure.skypeassets.com/i/scom/images/skype-buttons/chatbutton_32px.png" alt="Skype chat, instant message" role="Button" style="border:0;"></a><div class = 'clear10'></div> </div>			
			
			
			<div class = 'clear5' style = 'border-bottom:solid 1px #FAFAFA;'></div>
			<div class = 'body-form-desc'>
				<b>Registered Office:</b><div class = 'clear10'></div>
				BoldInbox.com<br/>
				Lyle Dr, San Jose<br/>
				California 95129				
			</div>
		</div>
	</div>
</div>
<script src='https://www.google.com/recaptcha/api.js'></script>