<form action="<?php echo site_url('change_plan/ppal_send'); ?>" class="form-website" method="post" name="payuForm" id="payuForm">
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
	
	<div class = 'user_account'> 
		<b>Coupon Code: </b><input type="text" size="45" name="coupon_code" id="coupon_code" />
	</div>
	<div  class = 'user_account' id="package_required" style="color:#ff0000;font-weight:bold;float:left; "> <br/></div>
	<div  class = 'user_account'> 
		<a href="javascript:void(0);" onclick="jsApply();"><b>Apply now</b></a>
		<br/> <span id="promo_code_msg"></span>
	</div>

</fieldset>
<?php if($posted['hash'] == '') { ?>
	<div align = 'center' class = 'page_top_button_row'>	
	
	<div align = 'center'>
		<input type="submit" class="button blue large textCap" name="submit" value="Submit" title="Submit" alt="Submit" style="" />		
	</div>
	</div> 
	 <?php } ?>			
       
    </form>
<script type="text/javascript" language="javascript">
$('#payuForm').submit(function(){ 
	if($('#selected_package').val() < 0){
		$('#package_required').text('* Required');
		return false;
	}
});
function jsApply(){
	var ccode = $('#coupon_code').val();	
	if(ccode==''){alert("Please enter a coupon-code"); exit;}
	var b_data = "ccode="+ccode ;
	 jQuery.ajax({
        url: base_url+"change_plan/getDiscountedAmount",
        type:"POST",
        data:b_data,
        success: function(data) {$('#promo_code_msg').html(data);}
		});
}
</script>