<html>
  <head>
  </head>
  <body onload="javascript:document.forms.ppalForm.submit();">   
	
    <form action="<?php echo $this->config->item('PAYPAL_SUBMIT_URL'); ?>" method="post" name="ppalForm">   
		 <input type="hidden" name="payment_year_month" value="months" />
                    <input type='hidden' name='business' value="<?php echo $this->config->item('PAYPAL_EMAIL'); ?>" />
                    <input type='hidden' name='cpp-logo-image' value="<?php echo $this->config->item('locker');?>images/logo_blue.png" />                   
                    <!-- input type="hidden" name="custom" id="custom" value="1|months" / -->
                    <input type="hidden" name="custom" id="custom" value="<?php echo $posted['custom'];?>" />
                    <input type='hidden' name='cmd' value='_xclick-subscriptions' />
                    <input type="hidden" name="page_style" value="paypal" />
                    <input type="hidden" name="lc" value="" />
                    <input type="hidden" name="no_shipping" value="1" />                    
					<input type="hidden" name="no_note" value="1" />
                    <input type="hidden" name="charset" value="utf-8" />
                    <input type="hidden" name="src" value="1" />
                    <input type="hidden" name="a1" id="a1" value="<?php echo $posted['trial_amount'];?>" /> 
                    <input type="hidden" name="p1" id="p1" value="<?php echo $posted['trial_cycle'];?>" />
                    <input type="hidden" name="t1" id="t1" value="<?php echo $posted['payment_year_month'];?>"/>
					
                    <input type="hidden" name="a3" id="a3" value="<?php echo $posted['package_price'];?>" /> 
                    <input type="hidden" name="p3" id="p3" value="1" />
                    <input type="hidden" name="t3" id="t3" value="<?php echo $posted['payment_year_month'];?>"/>
                    
                    
					
					<input type="hidden" name="zip" id="zip" value="" />

                    
                    
                    <input type='hidden' id='item_name' name='item_name' value='BoldInbox.com: Email Service' />
                    <input name="notify_url" value="<?php echo $this->config->item('PAYPAL_NOTIFY_URL'); ?>" type="hidden" />
                    <!--<input type='hidden' id='amount' name='amount' value='100'>-->
					<input type="hidden" value="2" name="rm" />
<!-- Set the "rm" variable to: 2 (means = "the buyer's browser is redirected to the return URL by using the POST method, and all payment variables are included") -->					
                    <input type='hidden' name='currency_code' value='USD' />
                    <input type='hidden' name='handling' value='0' />
                    <input type='hidden' name='cancel_return' value="<?php echo $this->config->item('PAYPAL_CANCEL_URL'); ?>"  />
                    <input type='hidden' id="return_url" name='return' value="<?php echo $this->config->item('PAYPAL_SUCCESS_URL'); ?>" />
                                      

				<!-- input type="submit" / -->					  
									       
    </form>
	<div style="width:100%; padding-top:40px; text-align: center">Processing. Please wait..
	<br/><br/>
	<img src = '<?php echo base_url();?>locker/images/loader.gif' border = '0' />
	</div>
  </body>
</html>