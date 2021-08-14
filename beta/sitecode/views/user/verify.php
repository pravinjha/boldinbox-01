<script type="text/javascript">
$(document).ready(function(){
	$('#frmNewEml input').change( function() {
	   var x =($('input[name=domain_reason]:checked', '#frmNewEml').val()); 
		if(x == 1){
			$('#domain_reason_other').text('Changed Domains for my Business');
			$('#domain_reason_other').hide();
		}else if(x == 2){						
			$('#domain_reason_other').text('User is no longer employed with organization');
			$('#domain_reason_other').hide();
		}else if(x == 3){
			$('#domain_reason_other').text('');
			$('#domain_reason_other').show(); 
		}
	});
});

function checkAns(){
	if($('#domain_reason_other').val() != ''){
		return true;
	}else{		
		alert("Please select/enter a reason!");
		return false;
	}
}
</script>
<div style="width:60%;margin:100px auto;text-align:center;">

      <h1>Thanks for verifying your new sending email. Changing your sending email can harm your reputation and ours.  Please choose one of the reasons below for changing your sending email:</h1>
      <br/>
	  <form name="frmNewEml" id="frmNewEml" action="<?php echo base_url();?>user/domain_reason/" method="post" accept-charset="utf-8" onsubmit="javascript:return checkAns();">
		<div style="text-align:left;">
		<ul>
			<li> <input type="radio" class="rdoReason" name="domain_reason" value="1" /> Changed Domains for my Business</li>
			<li> <input type="radio" class="rdoReason" name="domain_reason" value="2" /> User is no longer employed with organization</li>
			<li> <input type="radio" class="rdoReason" name="domain_reason" value="3" /> Other - Fill in Reason
			<br/>
			<textarea id="domain_reason_other" name="domain_reason_other" style="display:none; width:400px; height:130px;"></textarea>
			
			</li>
        </ul>
        </div>
		
		<input type="hidden" name="hidString" value="<?php echo $str;?>" />
		<input type="submit" name="btnSubmit" value=" Submit " class="button blueD large" />
		</form>
      
 
    <div class="gap"></div>
    <div class="gap"></div>
    
</div>