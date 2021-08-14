<script type="text/javascript">
	$(document).ready(function() {
		jQuery('#add_number_of_contacts').live('click',function(){
			if ($(this).attr('checked')) {
				$('#all').attr('checked', false);
				$('#number_of_contacts').show();
			}else{
				$('#all').attr('checked', true);
				$('#number_of_contacts').hide();
			}
		});
		
		jQuery('#automate').live('click',function(){
			if ($(this).attr('checked')) {				
				$('#segment_interval').show();
			}else{				
				$('#segment_interval').hide();
			}
		});
		jQuery('#all').live('click',function(){
			if ($(this).attr('checked')) {
				$('#add_number_of_contacts').attr('checked', false);
				$('#number_of_contacts').hide();
				$('#segment_interval').hide();
			}else{
				$('#add_number_of_contacts').attr('checked', true);
				$('#number_of_contacts').show();
				$('#segment_interval').show();
			}
		});
		jQuery('#btnAddNote').live('click',function(){
			var approval_notes = $('#approval_notes').val();
			var member_id = $('#member_id').val();
			jQuery.ajax({
			  url: "<?php echo base_url() ?>bandook/campaign/saveNoteOnly/",
			  type:"POST",
			  data: 'approval_notes='+approval_notes+'&member_id='+member_id,
			  success: function(data) {
				alert(data);
			  }
			});				 
		});
		jQuery('#btnAttachMsg').live('click',function(){
			var message_id = $('#message_id :selected').val();
			var member_id = $('#member_id').val();
			var cid = $('#campaign_id').val();			
			$("form#frmCampaignSegmentation").attr("action", "/bandook/campaign/attachMessage/"+cid+'/'+member_id+'/'+message_id);
			$('form#frmCampaignSegmentation').submit();			
		});
		
	});
	
</script>
<div class="tblheading">Campaign Segmentation For: <?php echo $campaign_data['member_username'];?></div>
<div id="messages">
<?php
// display all messages

if (is_array($messages)):
    foreach ($messages as $type => $msgs):
        foreach ($msgs as $message):
            echo ('<span class="' .  $type .'">' . $message . '</span>');
        endforeach;
    endforeach;
endif;
?>
</div>
<?php
echo '<div style="color:#FF0000;">'.validation_errors().'</div>';
echo form_open('bandook/campaign/campaign_segmentation/'.$campaign_id.'/'.$member_id.'/'.$mode, array('id' => 'frmCampaignSegmentation'));
echo '<table class="tbl_forms"><tr><td colspan="2"><table class="tbl_forms">';

 
echo "<tr><td>Approval Note:<br/> ".form_textarea(array('name'=>'approval_notes','id'=>'approval_notes' ,'value'=>trim($approval_notes),'rows'=>3, 'style'=>'width:500px; height:20px;'));
echo "<br/><span style='margin-left:50px;'> <input name='btnAddNote' id='btnAddNote' type='button' value='Add/Update note only' /></span>";
echo  "</td>";
echo "<td>Assign Message to Memeber:<br/> <select name='message_id' id='message_id'><option value=''>--select--</option>";

foreach($message_list as $msg_rec){
echo "<option value=\"{$msg_rec['message_id']}\">{$msg_rec['message_name']}</option>";

}


echo "</select>
	<br/><span style='margin-left:50px;'> <input name='btnAttachMsg' id='btnAttachMsg' type='button' value='Attach message' /></span>";
echo  "</td><td>";
foreach($member_message_list as $mem_msg){
	if($mem_msg['message_status'] == 0){
		echo "<br/><font color='black'> - ". $mem_msg['message_name']."</font>";
	}else{ 
		echo "<br/><font color='red'> - ". $mem_msg['message_name']."</font>";
	} 
}
echo  "</td></tr></table></td></tr>";



$arrSendToChk = array('name'=>'all','id'=>'all' ,'value'=>'All','style'=>'width:50px;');

$arrSegmentSizeChk = array('name'=>'add_number_of_contacts','id'=>'add_number_of_contacts','value'=>'1','style'=>'width:50px;');
$arrSegmentSizeText = array('name'=>'number_of_contacts','id'=>'number_of_contacts');

$arrAutomateItChk = array('name'=>'automate','id'=>'automate' ,'value'=>'automate','style'=>'width:50px;');
$arrAutomateItText = array('name'=>'segment_interval','id'=>'segment_interval');

if(!$is_segmented){
	$arrSendToChk['checked']= 'checked';
	$arrSegmentSizeText['style']= 'width:100px;display:none';
	$arrAutomateItText['value'] = 2;
	$arrAutomateItText['style'] = 'width:100px; display:none';
}else{
	$arrSegmentSizeChk['checked']= 'checked';
	$arrSegmentSizeText['style']= 'width:100px;';
	$arrSegmentSizeText['value']= $segment_size;
	$arrAutomateItChk['checked']= 'checked';
	$arrAutomateItText['value'] = $segment_interval;
	$arrAutomateItText['style'] = 'width:100px;';
}	
echo "<tr><td style='width:40%;'>";
echo '<table class="tbl_forms">';
echo "<tr><td><b>Sent: </b>".$campaign_data['sent']."<b> | Unsent-yet: </b>".$campaign_data['unsent']."</td></tr>";
echo "<tr><td>Send Campaign To:<br/> ".form_checkbox($arrSendToChk) ." All (Total $subscriber_count Contacts)</td></tr>";
echo "<tr><td>OR<br/></td></tr>";
echo "<tr><td>Add Number Of Contacts (Total $subscriber_count Contacts): ";
echo form_checkbox($arrSegmentSizeChk);
echo form_input($arrSegmentSizeText);
echo "</td></tr>";
echo "<tr><td>Automate It: ".form_checkbox($arrAutomateItChk) ;
echo form_input($arrAutomateItText)."<span style='display:inline;font-size:9px;'>Put segment-interval in minutes.</span></td></tr>";
echo "<tr><td><hr /></td></tr>";
if($campaign_data['campaign_template_option'] == 5){
echo "<tr><td>Upvote: TEXT CAMPAIGN <span style='width:260px; float:right;display:inline;border:0px solid #ff0000;'>Initial delay: ".form_input( array('name'=>'add_delay','id'=>'add_delay','value'=>$campaign_data['campaign_delay_minute'],'style'=>'width:50px;') ) ."minutes</span></td></tr>";
}else{
echo "<tr><td>Upvote:<input type='text' name='add_open' id='add_open' value='".$campaign_data['add_open']."' style='width:50px;' /> <span style='width:260px; float:right;display:inline;border:0px solid #ff0000;'>Initial delay: ".form_input( array('name'=>'add_delay','id'=>'add_delay','value'=>$campaign_data['campaign_delay_minute'],'style'=>'width:50px;') ) ."minutes</span></td></tr>";
}
echo "<tr><td><hr /></td></tr>";

$pmta_priority_high = ($campaign_data['pmta_priority'] > 50)?'selected':'';
$pmta_priority_normal = ($campaign_data['pmta_priority'] < 100)?'selected':'';

echo "<tr><td>PMTA Priority <select name='pmta_priority' id='pmta_priority'><option value='50' $pmta_priority_normal>Normal</option><option value='100' $pmta_priority_high>High</option></select></td></tr>";
echo "<tr><td><hr /></td></tr>";



$dmarc_no = ($campaign_data['is_dmarc'] < 1)?'selected="yes"':'';
$dmarc_yes = ($campaign_data['is_dmarc'] > 0)?'selected="yes"':'';
// get sender-email's domain. if gmail enable dmarc.
list($eml_local, $eml_domain) = explode('@', $campaign_data['sender_email']);
$is_gmail = (strtolower(trim($eml_domain)) == 'gmail.com' or strtolower(trim($eml_domain)) == 'yahoo.com')?'1':'0';

if($dmarc_no && $is_gmail){
	$dmarc_yes = 'selected="yes"';
	$campaign_data['dmarc_from_email'] = ($campaign_data['dmarc_from_email'] !='')?$campaign_data['dmarc_from_email']:$eml_local;
}
echo"<tr><td>DMARC Enabled: <select name='is_dmarc' id='is_dmarc'><option value='0' $dmarc_no>No</option><option value='1' $dmarc_yes>Yes</option></select></td></tr>";

echo"<tr><td>DMARC From Email: <input type='text' name='dmarc_from_email' id='dmarc_from_email' value='".$campaign_data['dmarc_from_email']."'> @pipeline_domain</td></tr>";


echo "</table></td>";

echo "<td style='width:60%;'>";
echo '<table class="tbl_forms">';
echo "<tr><td><b>Subject: </b>".$campaign_data['email_subject']."</td></tr>";
echo "<tr><td><b>From: </b>".$campaign_data['sender_name']."<b>< </b><font color='red'>".$campaign_data['sender_email']."</font><b> ></b></td></tr>";
echo"<tr><td>
<P ALIGN='center'><IFRAME SRC='". site_url('preview/index/'.$this->is_authorized->encryptor('encrypt',$campaign_id))."/y'   title='". $campaign_data['email_subject']."' WIDTH='700' HEIGHT='600' FRAMEBORDER='0' SCROLLING='auto'></IFRAME></P></td></tr>";
echo "</table>";



echo"</td></tr>";

echo "<tr><td colspan='2'><hr/><br/>";
echo form_submit(array('name' => 'btnEdit', 'id' => 'btnEdit','class'=>'inputbuttons1','content' => 'edit'), 'Submit');
echo '&nbsp;';
echo " <input name='btnCancel' id='btnCancel' type='button' value='Cancel' content='Cancel' onclick='window.location.href=\"".base_url()."bandook/campaign/ongoing/\"'  />";
echo "</td></tr><tr><td colspan='2'>";
echo "<input name='action' id='action' type='hidden' value='submit' />"; 
echo "<input name='member_id' id='member_id' type='hidden' value='$member_id' />"; 
echo "<input name='campaign_id' id='campaign_id' type='hidden' value='$campaign_id' />";  
echo "</td></tr>";
echo "</table>";
echo form_close();
?>
<table class="tbl_forms">
<tr><td>
<div id="pmSpam">
<b>Score:</b><?php echo $campaign_data['spamscore'];?>
<br/>
<br/>
<b>Report:</b><br/>
<?php echo $campaign_data['spamreport'];?>
</div>
 
</td><td></td></tr>
</table>
</div>
