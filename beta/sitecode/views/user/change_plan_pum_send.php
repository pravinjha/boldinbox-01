<html>
  <head>
  </head>
  <body onload="javascript:document.forms.payuForm.submit();">   
	
    <form action="<?php echo $posted['action']; ?>" method="post" name="payuForm">      
		<input type="hidden" name="hash" value="<?php echo $posted['hash'] ?>"/>   
		<input type="hidden" name="txnid" value="<?php echo $posted['txnid'] ?>"/>   
		<input type="hidden" name="key" value="<?php echo $posted['key'] ?>"/>   
		<input type="hidden" name="amount" value="<?php echo $posted['amount'] ?>"/>   
		<input type="hidden" name="productinfo" value="<?php echo $posted['selected_package'] ?>"/>   
		<input type="hidden" name="surl" value="<?php echo $posted['surl'] ; ?>" />	
		<input type="hidden" name="furl" value="<?php echo $posted['furl']; ?>" />		
		<input type="hidden" name="curl" value="<?php echo $posted['curl']; ?>" />		
		<input type="hidden" name="firstname" value="<?php echo $posted['firstname']; ?>" />		
		<input type="hidden" name="lastname" value="<?php echo $posted['lastname']; ?>" />		
		<input type="hidden" name="address" value="<?php echo $posted['address']; ?>" />
		<input type="hidden" name="city" value="<?php echo $posted['city']; ?>" />		
		<input type="hidden" name="state" value="<?php echo $posted['state']; ?>" />		
		<input type="hidden" name="country" value="<?php echo $posted['country']; ?>" />		
		<input type="hidden" name="zipcode" value="<?php echo $posted['zipcode']; ?>" />		
		<input type="hidden" name="email" value="<?php echo $posted['email']; ?>" />		
		<input type="hidden" name="phone" value="<?php echo $posted['phone']; ?>" />       
		<input type="hidden" name="udf1" value="<?php echo $posted['member_id']; ?>" />       
		<input type="hidden" name="udf2" value="<?php echo $posted['payment_months']; ?>" />       
		<input type="hidden" name="udf3" value="<?php echo $posted['promotion_code']; ?>" />       		     
		<input type="hidden" name="service_provider" value="payu_paisa" />       
    </form>
	<div style="width:100%; padding-top:40px; text-align: center">Processing. Please wait..
	<br/><br/>
	<img src = '<?php echo base_url();?>locker/images/loader.gif' border = '0' />
	</div>
  </body>
</html>