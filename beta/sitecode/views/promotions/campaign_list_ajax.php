<?php		 
            //Fetch campaings from campaigns array
            if(count($campaign_data['campaigns'])) {
            $i=1;
            ?>
            <?php foreach($campaign_data['campaigns'] as $campaign){ 
			 
			if($campaign['campaign_template_option'] == 3){
				$strEditorURL =  site_url('promotions/campaign_editor/'.$campaign['enc_cid']);	
			}elseif($campaign['campaign_template_option'] == 5){
				$strEditorURL =  site_url('promotions/plain_text/'.$campaign['enc_cid']);
			}elseif($campaign['campaign_template_option'] == 1){
				$strEditorURL =  site_url('promotions/url_import/'.$campaign['enc_cid']);
			}elseif($campaign['campaign_template_option'] == 2){
				$strEditorURL =  site_url('promotions/zip_import/'.$campaign['enc_cid']);
			}elseif($campaign['campaign_template_option'] == 4){
				$strEditorURL =  site_url('promotions/html_code/'.$campaign['enc_cid']);
			}else{	
				$strEditorURL =  '';
			} 
			 
			 
			 
			?>
			<div class = 'campaign-block'>
				<div class = 'campaign-info'>
					<div class = 'campaign-info-left'>
						<a href="<?php echo CAMPAIGN_DOMAIN.'c/'.$campaign['enc_cid'];?>" target="_blank" class="title">
							<?php if($campaign['screenshot'] !=''){ $src= $campaign['screenshot'];}else{$src = $this->config->item('locker')."images/campaign_placeholder.jpg";}?>
							<img src='<?php echo $src;?>' width='128' />						
						</a>
					</div>
					<div class = 'campaign-info-right'>
						<div class = 'campaign-info-left-title'>
							<?php echo ucfirst($campaign['campaign_title']);?><br/>
						</div>
						
						
						
						<div class = 'campaign-info-left-status'><strong>Status: </strong><?php
						 // get campaign status
						$thisCampaignStatusInt = $campaign['campaign_status_show'];	
						if(trim($thisCampaignStatusInt)=='1'){
							echo 'Draft, '.date('F j, Y \a\t g:i a', strtotime($campaign['draftDate'])); //campaign not scheduled yet
							$thisCampaignStatus = 'draft';	
						}elseif(trim($thisCampaignStatusInt)=='3'){
							echo 'Suspended, '.date('F j, Y', strtotime( $campaign['email_send_date']))."";  //campaign disallowed by admin	
							$thisCampaignStatus = 'draft';	
						//}elseif(($campaign['campaign_status']=='active')or((date('Y-m-d H:i:s', strtotime( $campaign['campaign_sheduled'])+ (($campaign['campaign_delay_minute'] + 30)*60)) < date("Y-m-d H:i:s")))){
						}elseif(trim($thisCampaignStatusInt)=='5'){
							//campaign sent or [ scheduled-time + delay-added-by-admin + 30 minutes] already past then show it as SENT 	
							echo "Sent, ".date('F j, Y \a\t g:i a', strtotime( $campaign['email_send_date']))."";                  
							$thisCampaignStatus = 'sent';	
						}elseif(trim($thisCampaignStatusInt)=='4'){
							echo 'Processing.., '.date('F j, Y', strtotime( $campaign['email_send_date']))."";  // Campaign Approved by admin
							$thisCampaignStatus = 'draft';	
						}else{
							echo "Scheduled, ".date('F j, Y  \a\t g:i a', strtotime( $campaign['email_send_date']))."";  //campaign  waiting admin approval 
							$thisCampaignStatus = 'queued';		
						}
               /*
			   // get campaign status
                if($campaign['campaign_status']=='draft'){
					echo ucfirst($campaign['campaign_status']).", ".date('F j, Y \a\t g:i a', strtotime($campaign['draftDate'])).""; //campaign not sent
                }elseif(($campaign['campaign_status']=='archived' or $campaign['campaign_status']=='queueing')&&(date('Y-m-d H:i:s', strtotime( $campaign['campaign_sheduled']))<date("Y-m-d H:i:s"))){
					echo "In Queue, ".date('F j, Y', strtotime( $campaign['email_send_date'])).""; //campaign not sent yet
                }elseif(($campaign['campaign_status']=='archived') ||($campaign['campaign_status']=='queueing') ||($campaign['campaign_status']=='active_ready')){
					echo "<span id='campaign_status_".$campaign['enc_cid']."'>Scheduled, </span>". date('F j, Y \a\t g:i a', strtotime( $campaign['campaign_sheduled']))." <br/> <a href='javascript:void(0);' onclick='confirm_cancel_delivery(\"".$campaign['enc_cid']."\")' id='cancel_".$campaign['enc_cid']."'>Cancel Delivery</a>";  //campaign  scheduled
                }elseif($campaign['campaign_status']=='active'){
					echo "Sent, ".date('F j, Y \a\t g:i a', strtotime( $campaign['email_send_date']))."";  //campaign sent
                }elseif($campaign['campaign_status']=='ready'){
					echo "In Queue, ".date('F j, Y  \a\t g:i a', strtotime( $campaign['email_send_date']))."";  //campaign  waiting admin approval
                }elseif($campaign['campaign_status']=='disallow'){
					echo "Suspended, ".date('F j, Y', strtotime( $campaign['email_send_date']))."";  //campaign disallowed by admin
                }else{
					echo ucfirst($campaign['campaign_status']).", ".date('F j, Y \a\t g:i a', strtotime( $campaign['campaign_date_added']))."";
                }
				*/
               ?></div>
					<div class = 'info' id='<?php echo $campaign['enc_cid']; ?>_action'></div>
						<div class = 'campaign-info-right-actions'>
							<a href="<?php echo CAMPAIGN_DOMAIN.'c/'.$campaign['enc_cid'];?>" title="view" target="_blank">View</a>  
								<?php if($thisCampaignStatus  =='sent'){ ?>
									<a href="javascript:void(0);" onclick="bibAlert('To edit, first click copy button to replicate this campaign, then edit and save changes');"  title="Edit">Edit</a>
								<?php }elseif($thisCampaignStatus =='queued'){ ?>								
									<a href="javascript:void(0);" id='campaign_edit_<?php echo $campaign['enc_cid']?>' onclick="bibAlert('This email campaign is already in queue and will be sent at scheduled time. To edit, first click Cancel Delivery and then edit campaign.');"  title="Edit" class="btn cancel" >Edit</a></li>
								<?php }else{?>
									<a href="javascript:void(0);" onclick="window.location.href='<?php echo $strEditorURL;?>'"  title="Edit">Edit</a> 							
								<?php } ?>
								
								
								<a href="<?php echo  site_url('promotions/create_from_campaign/'.$campaign['enc_cid'].'/1');?>" title="Copy">Copy</a>
							
								<?php if($thisCampaignStatus =='sent'){ ?>					
									<a href="<?php echo  site_url('promotions/create_from_campaign/'.$campaign['enc_cid'].'/2');?>" title="Resend">Resend</a>
								<?php }elseif($thisCampaignStatus == 'queued'){ ?>
									<!-- a href='javascript:void(0);' id='campaign_send_<?php //echo $campaign['enc_cid']?>' onclick="stopCampaign('<?php // echo $campaign['enc_cid']?>')" title="Send">Stop</a-->
								<?php }else{ ?>										
									<a href='javascript:void(0);' id='campaign_send_<?php echo $campaign['enc_cid']?>' <?php if($campaign_data['upgrade_package']==1) {?>onclick="bibAlert('You are over your current plan limit. Please Upgrade Now.');" <?php }else{ ?>  onclick="javascript:window.location.href='<?php echo  site_url("campaign_email_setting/index/".$campaign['enc_cid']);?>';" <?php } ?> title="Send">Send</a>						
								<?php } ?>
					
							<?php if($thisCampaignStatus =='sent'){ ?>
							  <a href="javascript:void(0);" onclick="window.location.href='<?php echo site_url("stats/display/".$campaign['enc_cid']);?>';"  title="Stats">Stats</a>
							  <?php }else{ ?>
							  <a href="javascript:void(0);" onclick="bibAlert('Nothing to track. Campaign is not sent.')" title="Stats">Stats</a> 
							  <?php } ?> 
							
							<?php if($thisCampaignStatus !='sent'){ ?>													
							<a class="delete-campaign btn cancel" href="javascript:void(0);" title="Delete" name="<?php echo $campaign['enc_cid']; ?>" >Delete</a>							
							<?php } ?> 	
							
							<?php if(3 == $campaign['campaign_template_option']){?>		
								<a class="create_template btn cancel"  title="Delete" name="<?php echo $campaign['enc_cid']; ?>" href="javascript:void(0);" style="width:110px;" >Save as Template</a>							
							<?php } ?>
						</div>
						<?php if($campaign['campaign_status']=='active'){ ?>
						<div class = 'campaign-info-right-share'>							
							<a href="http://www.facebook.com/share.php?u=<?php echo CAMPAIGN_DOMAIN.'c/'.$campaign['enc_cid'];?>&t=<?php echo $campaign['email_subject']?>" title="Click to share this post on Facebook" target="_blank"><img src = '<?=$this->config->item('locker')."images/icons/share-on-facebook.png"?>' height = '25' /></a> 
							<a href="http://twitter.com?status=Here is our newest campaign : <?php echo CAMPAIGN_DOMAIN.'c/'.$campaign['enc_cid'];?> via BoldInbox" title="Click to share this post on Twitter" target="_blank"><img src = '<?=$this->config->item('locker')."images/icons/share-on-twitter.png"?>' height = '25' /></a>
						</div>
						<?php } ?>
					</div>
				</div>
				<div class = 'clear0'></div>				
			</div>
		<?php $i++; }// FOR ends
			}else{  //record not found 
		?>	
				<div class = 'contacts_message'>You do not have any promotional campaigns created yet.<br/>
                <a class = 'au' href = '<?php echo  site_url('promotions/layouts') ;?>'><b>Create Campaign Now</b></a>
				
				</div>
		 <?php } ?>	
        <!--Display paging links -->
        <div class="pagination-container" style="margin:15px;"><?php echo $paging_links ?></div>
<script language = 'javascript' type="text/javascript">
function stopCampaign(cid){
	bibConfirm("Are you sure to cancel delivery of this campaign?",'stopSending("'+cid+'")');
}
function stopSending(cid){alert(cid);
	jQuery.ajax({ url: base_url+"promotions/update_campaign/"+cid+"/stop/", type:"POST", success: function(data){	window.location.reload();}});
}
</script>