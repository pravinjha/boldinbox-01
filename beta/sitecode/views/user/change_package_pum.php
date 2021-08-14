<html>
  <head>
  <script>
    var hash = '<?php echo $hash ?>';
    function submitPayuForm() {
      if(hash == '') {
        return;
      }
      var payuForm = document.forms.payuForm;
      payuForm.submit();
    }
  </script>
  </head>
  <body onload="submitPayuForm()">
    <h2>PayU Form</h2>
    <br/>
    <?php if($formError) { ?>
	
      <span style="color:red">Please fill all mandatory fields.</span>
      <br/>
      <br/>
    <?php } ?>
    <form action="<?php echo $action; ?>" method="post" name="payuForm">      
		<input type="hiddenX" name="hash" value="<?php echo $hash ?>"/>   
		<input type="hiddenX" name="txnid" value="<?php echo $posted['txnid'] ?>"/>   
		<input type="hiddenX" name="key" value="<?php echo $posted['key'] ?>"/>   
		<input type="hiddenX" name="amount" value="<?php echo $posted['amount'] ?>"/>   
		<input type="hiddenX" name="productinfo" value="<?php echo $posted['selected_package'] ?>"/>   
		<input type="hidden" name="surl" value="<?php echo $posted['surl'] ; ?>" />	
		<input type="hidden" name="furl" value="<?php echo $posted['furl']; ?>" />		
      <table>
        <tr>
          <td>Select Plan: </td>
          <td colspan="3"><select name="selected_package">		  
		  <?php
		 // print_r($packages);
		  foreach($packages as $p){
			if($p['package_id'] == $posted['selected_package'] )
				echo "<option value='".$p['package_id']."' selected>".$p['package_title'].' : $'.$p['package_price']."</option>";
			else
				echo "<option value='".$p['package_id']."'>".$p['package_title'].' : $'.$p['package_price']."</option>";
		  }
		  ?>
		  </select></td>		 
        </tr>
		<tr>
          <td colspan="4"><b>Billing Details</b></td>
        </tr>
		<tr>          
          <td>First Name: </td>
          <td><input name="firstname" id="firstname" value="<?php echo (empty($posted['firstname'])) ? '' : $posted['firstname']; ?>" /></td>
		  <td>Last Name: </td>
          <td><input name="lastname" id="lastname" value="<?php echo (empty($posted['lastname'])) ? '' : $posted['lastname']; ?>" /></td>
        </tr>
		<tr>
          <td>Address1: </td>
          <td><input name="address1" value="<?php echo (empty($posted['address1'])) ? '' : $posted['address1']; ?>" /></td>
          <td>Address2: </td>
          <td><input name="address2" value="<?php echo (empty($posted['address2'])) ? '' : $posted['address2']; ?>" /></td>
        </tr>
        <tr>
          <td>City: </td>
          <td><input name="city" value="<?php echo (empty($posted['city'])) ? '' : $posted['city']; ?>" /></td>
          <td>State: </td>
          <td><input name="state" value="<?php echo (empty($posted['state'])) ? '' : $posted['state']; ?>" /></td>
        </tr>
        <tr>
          <td>Country: </td>
          <td><select name="country">
			<?php
			foreach($country_info as $c){
				if($c['country_name'] == $posted['country'])
					echo "<option value='".$c['country_name']."' selected>".$c['country_name']."</option>";
				else
					echo "<option value='".$c['country_name']."'>".$c['country_name']."</option>";
			
			}
			?>	
			</select></td>
          <td>Zipcode: </td>
          <td><input name="zipcode" value="<?php echo (empty($posted['zipcode'])) ? '' : $posted['zipcode']; ?>" /></td>
        </tr>
        <tr>
          <td>Email: </td>
          <td><input name="email" id="email" value="<?php echo (empty($posted['email'])) ? '' : $posted['email']; ?>" /></td>
          <td>Phone: </td>
          <td><input name="phone" value="<?php echo (empty($posted['phone'])) ? '' : $posted['phone']; ?>" /></td>
        </tr>       
        <tr>
          <?php if(!$hash) { ?>
            <td colspan="4"><input type="submit" value="Submit" /></td>
          <?php } ?>
        </tr>
      </table>
    </form>
  </body>
</html>