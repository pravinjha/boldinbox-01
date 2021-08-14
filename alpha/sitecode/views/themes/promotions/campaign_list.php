<script type="text/javascript">

jQuery(".create_template").on('click',function(event) {
  var cid = $(this).attr('name');
  jQuery.ajax({ url: "<?php echo base_url() ?>promotions/update_campaign/"+cid+"/create_template", type:"POST", success: function(data){$('#'+cid+'_action').html(data); setTimeout("$('#"+cid+"_action').html('')", 2000); }});
});
function confirm_cancel_delivery(campaign_id){
  var msg='<h5>Confirm</h5><p>Are you sure you want to cancel your scheduled email campaign and revert back to draft mode.</p><button class="btn danger fast_confirm_proceed" onclick="cancel_delivery(\''+campaign_id+'\')">Yes</button><button class="btn cancel fast_confirm_cancel">No</button>';
  $.fancybox({'content' : "<div style=\"width:400px;\">"+msg+"</div>"});
}
function cancel_delivery(campaign_id){
  jQuery.ajax({
  url: "<?php echo base_url() ?>promotions/cancel_campaign_delivery/"+campaign_id,
  type:"POST",
  success: function(data){
    jQuery("#campaign_status_"+campaign_id).html('Canceled');
    jQuery("#cancel_"+campaign_id).remove();
    jQuery("#campaign_send_"+campaign_id).attr('onclick','').click(function(){window.location.href='<?php echo base_url() ?>campaign_email_setting/index/'+ campaign_id;});
    jQuery("#campaign_edit_"+campaign_id).attr('onclick','').click(function(){window.location.href='<?php echo base_url() ?>promotions/campaign_editor/'+ campaign_id;});
    
  }});
}
$('#fancybox-wrap').find('.fast_confirm_cancel').on('click',function(){
  $.fancybox.close();
});
/*
  ajax call to delete campaign
*/

function openDiv(){
  document.getElementById("topnav").innerHTML='<a  href="#" class="signin" onclick="return closeDiv();"><span>Login</span></a>';
  $("#topnav a").addClass("menu-open");
  $("#signin_menu").slideDown("slow");
}
function closeDiv(){
  document.getElementById("topnav").innerHTML='<a  href="#" class="signin " onclick="return openDiv();"><span>Login</span></a>';
  $("#topnav a").removeClass("menu-open");
  $("#signin_menu").slideUp("slow");
}
</script>

   
      
  <div class="campaigns_container"><?php require_once("campaign_list_ajax.php");?></div>
	