<script type="text/javascript">
function submit_form(){
	var block_data="";
	block_data=$('#form_subscription').serialize();
	var url="";
	<?php if($autoresponder==1){?>
		url="<?php echo base_url() ?>contacts/add_autoresponder_emailreport_to_contact_list/<?php echo $action; ?>/<?php echo $campaign_id; ?>/<?php echo $scheduled_id; ?>";
	<?php }else{ ?>
		url="<?php echo base_url() ?>contacts/add_emailreport_to_contact_list/<?php echo $action; ?>/<?php echo $campaign_id; ?>/<?php echo $tinyUrl; ?>";
	<?php } ?>
	jQuery.ajax({
		url: url,
		type:"POST",
		data:block_data,
		success: function(data) {
			var data_arr=data.split(":", 2);
			if(data_arr[0]=="error"){
				$('#msg').show();
				$('#msg').addClass('error');
				$('#msg').removeClass('info');
				$('#msg').html(data_arr[1]);
				setTimeout( function(){$('#msg').hide();} , 4000);
			}else if(data_arr[0]=="success"){
				$('#msg').show();
				$('#msg').html(data_arr[1]);
				$('#msg').addClass('info');
				$('#msg').removeClass('error');
				setTimeout( function(){$('#msg').hide();} , 4000);
				setTimeout( function(){$.modalBox.close();} , 4000);
			}
		}
	});
}
</script>
<!--[body]-->
<div class="fancybox-page">
  <div style="width:400px;">
		<div class="fancybox-form contact_frm">
			<form  method="post" name="form_subscription" id="form_subscription" onsubmit="submit_form(); return false;">
				<input type="hidden" name="action" value="submit" />
				<div id="msg" class="info"></div>
				<?php if(count($subscription_list) > 0){?>
					<p>
						<strong>Select List:</strong>
						<select name="subscriptions" id="subscriptions" style="height:20px;">
							<?php
								foreach($subscription_list as $subscription){
										echo '<option value="'.$subscription['subscription_id'].'">'.ucwords($subscription['subscription_title']).'</option>';
								}
							?>
						</select>
					</p>
			  <?php } else {
			  	echo '<p>There is no list found.</p>';
			  }?>
			  <p style="padding-bottom: 0"><strong>Create New List:</strong></p>
				<input type="text" name="subscription_title" id="subscription_title"/>
				<div id="processing"></div>
				<div class="btn-group message_button">
					<?php echo form_submit(array('name' => 'subscription_submit', 'id' => 'btnEdit','class'=>'form_submit_btn_class','content' => 'Submit','style' => 'margin-left:5px;'), 'Save'); ?>
					<?php echo form_button(array('name' => 'subscription_cancel', 'id' => 'subscription_cancel','class'=>'form_submit_btn_class','content' => 'Cancel','onclick'=>'javascript:$.modalBox.close();'), 'Cancel'); ?>
	      </div>
  		</form>
    </div>
  </div>
</div>
