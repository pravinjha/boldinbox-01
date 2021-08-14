<form action="<?php echo site_url('change_plan/pum_send'); ?>" class="form-website" method="post" name="payuForm">
<fieldset class = 'DIY-campaign-options-sendmail' style = 'padding:10px;'>
	<legend>Upgrade Your Account</legend>
	 <?php  if(count($messages) > 0) { ?>
	
      <span style="color:red">All fields are mandatory.</span>
      <br/>
      <br/>
    <?php } ?>
	
    <div class = 'user_account'>
		Select Plan:<br /> <select name="selected_package" id="selected_package" style = 'height:30px;'>		  
		  <?php
		 // print_r($packages);
		  foreach($packages as $p){
			if($p['package_id'] == $posted['selected_package'] )
				echo "<option value='".$p['package_id']."' selected>".$p['package_title'].' : $'.$p['package_price']."</option>";
			elseif($p['package_id'] == $eligiblePackage)
				echo "<option value='".$p['package_id']."' selected>".$p['package_title'].' : $'.$p['package_price']."</option>";
			else
				echo "<option value='".$p['package_id']."'>".$p['package_title'].' : $'.$p['package_price']."</option>";
		  }
		  ?>
		  </select>
		<br/>  		
	</div>
	<div class = 'user_account' >
		Payment Months:<br/>
		<select name="payment_months" id="payment_months">
			<?php
			for($i=1;$i<=12;$i++){
				if($i==3)
				echo"<option value='$i' selected>$i</option>";
				else
				echo"<option value='$i'>$i</option>";
			}	
			?>
		</select>
		<br />	
	</div>

	<div class = 'user_account'> 
		<b>Promotion Code: </b><input type="text" size="45" name="promotion_code" id="promotion_code" />
	</div>
	<div  class = 'user_account'> <br/><br/>
		<a href="javascript:void(0);" onclick="jsApply();"><b>Apply now</b></a>
		<br/> <span id="promo_code_msg"></span>
	</div>

</fieldset>
<?php if($posted['hash'] == '') { ?>
	<div align = 'center' class = 'page_top_button_row'>	
	
	<div align = 'center'>
		<input type="submit" class="button blue large textCap" name="submit" value="Submit" title="Submit" alt="Submit" style="" />		
	</div>
		
<br/><br/>
<span style="font-weight:bold;font-size:14px;color:#ff0000;">FOR ANY ISSUES REGARDING PAYMENT, PLEASE CONTACT US AT +91-8130972229</span>
	</div> 
	 <?php } ?>			
       
    </form>
<script type="text/javascript" language="javascript">
function jsApply(){
	var p = $('#selected_package').val();
	var pm = $('#payment_months').val();
	var pc = $('#promotion_code').val();
	if(p=='-1'){alert("Please select a plan for payment"); exit;	}
	if(pc==''){alert("Please enter a promotion-code"); exit;}
	var b_data = "p="+p+"&pm="+pm+"&pc="+pc
	 jQuery.ajax({
        url: base_url+"change_package/getPromoDetail",
        type:"POST",
        data:b_data,
        success: function(data) {$('#promo_code_msg').html(data);}
		});
}
</script>