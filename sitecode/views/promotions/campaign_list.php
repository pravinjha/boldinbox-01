<script type="text/javascript">

jQuery(".create_template").live('click',function(event) {
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
$('#fancybox-wrap').find('.fast_confirm_cancel').live('click',function(){
  $.fancybox.close();
});
/*
  ajax call to delete campaign
*/
function deleteCampaign(campaign_id){
  jQuery.ajax({
  url: "<?php echo base_url() ?>promotions/delete/"+campaign_id,
  type:"POST",
  success: function(data){
    jQuery("#campaign_"+campaign_id).remove();
    $.fancybox.close();
  }});
}
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
	<?php
    // display all messages
    if (is_array($campaign_data['messages'])){
      echo '<div class = "ub-message">';
      foreach ($campaign_data['messages'] as $type => $msgs):
      foreach ($msgs as $message):
        echo ('<span class="' .  $type .'">' . $message . '</span>');
      endforeach;
      endforeach;
      echo '</div>';
    //}elseif(($campaign_data['active_campaign_count']>0)||($campaign_data['ready_campaign_count']>0)||($campaign_data['queueing_campaign_count']>0)){
	}elseif(trim($campaign_data['campaign_status_show']) == '2'){
      echo '<div class = "ub-message">Your email campaign is in our sending queue.<br/>Sending may take a bit longer, depending on the number of emails queued before yours.</div>';
    }
    ?>
    <!-- NEW Searchbox -->
   <div class="ub-contact-top">
    <form method="post" class="form-website" id="campaignSearchFrm"  name="campaignSearchFrm">

				<div class="contacts_select_new" id="campaign_search">					
					<select name="campaign_search_by" id="campaign_search_by" style="width: 99%;height: 20px; margin-bottom: 5px;margin-top: 5px;border: solid 0px #CCCCCC; font-size:14px;font-weight: 700;color: #666;"><option value="subject" <?php if($campaign_data['campaign_search_by'] =='subject')echo'selected'; ?>>Campaign Subject</option><option value="title" <?php if($campaign_data['campaign_search_by'] =='title')echo'selected'; ?>>Campaign title</option><option value="content" <?php if($campaign_data['campaign_search_by'] =='content')echo'selected'; ?>>Campaign content</option>
					</select>	
				</div>
				
				<div class="contacts_search">
					<input type="text" name="campaign_search" id="campaign_search" placeholder="search.." value="<?php echo $campaign_data['campaign_search'];?>" />
					<input type="image" src="<?php echo base_url();?>locker/images/icons/search.png" width="" name="btnSearchCampaign" id="btnSearchCampaign">
				</div>
				<div class="clear5"></div>
				<div class="contacts_message" id="msg"></div>	
	</form>			
	</div>
    
    
    
    
    <div class = 'clear0'></div>
    
    
    <!-- list starts -->
	<div class="campaigns_container">
		<?php require_once("campaign_list_ajax.php");?>
	</div>