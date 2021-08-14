<script type="text/javascript">
<?php if($autoresponder_subscription==1){?>
<?php }else{ ?>
function delete_list_frm(){
var block_data="";
block_data+='submit_action=submit';
	jQuery.ajax({
		url: "<?php echo base_url() ?>contacts/delete/<?php echo $subscription_id; ?>",
		type:"POST",
		data:block_data,
		success: function(data) {
			$('#messageBox').find('.subscription_msg').html('List deleted');
			window.location.reload();
		}
	});
}
<?php } ?>
</script>
<!--[page html]-->
<div class="fancybox-page registration-page_contact_delete">
  <div class="fancybox-form contact_frm">
    <?php if($autoresponder_subscription==1){?>    
    <p>This list is linked with autoresponder(s). Update autoresponder and un-link this list to delete it.</p>
    <?php } else { ?>      
      <form method="post" name="contact_frm_submit" id="contact_frm_submit" onsubmit="delete_list_frm(); return false;">
        <input type="hidden" name="subscription_id" id="subscription_id" value="<?php echo $subscription_id; ?>" />
        <div class="subscriber_msg info"></div>
        <p>
          Only <?php if($lname !='')echo "\"$lname\"";?> list will be deleted while contacts can be found in "All My Contacts". Are you sure to do this?
        </p>
        <div class="btn-group message_button">
          <input type="hidden" name="contact_list" id="all_contact_list" value="<?php echo $subscription_id; ?>" style="width:10px;" checked />
          <?php echo form_submit(array('name' => 'subscription_submit', 'id' => 'btnEdit','class'=>'add_more btn danger form_submit_btn_class','content' => 'Submit'), 'Delete'); ?>
          <?php echo form_button(array('name' => 'subscription_cancel', 'id' => 'subscription_cancel','class'=>'btn cancel fast_confirm_cancel form_submit_btn_class','content' => 'Cancel','style' => 'margin-left:5px;','onclick'=>'javascript:$.modalBox.close();'), 'Cancel'); ?>
        </div>
      </form>
    <?php } ?>
  </div>
</div>
