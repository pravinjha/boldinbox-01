<script type="text/javascript">    
    function fnShowNow(x){
		var selval = x.value;
		var cid = "<?php echo $this->is_authorized->encryptor('encrypt',$campaign_id);?>";		      
        window.location = "<?php echo  site_url('stats/detail');?>/"+selval+"/"+cid;		
    }
	function confirmExport(act, cid){
		var u = base_url+'stats/exportcsv/'+ act +'/'+cid+'/';		
		bibConfirm("Are you sure you want to export this list?",'window.location.href = "'+u+'"');      
    }
	function addToList(act, cid){
		displayAlertMessage('Add to list','','0',true,450,250,false,'');
		$( "#message" ).load( base_url+"contacts/add_emailreport_to_contact_list/"+act+"/"+cid ); return false; 		 
	}
</script>
			<div class = 'stats_block pd0 mgb0'>
				<div class = 'stats_header'>
					<div class = 'stats_info_left'>
						<div class = 'stats_campaign_name'><?php echo (trim($campaign_data['email_subject']) !='')?$campaign_data['email_subject']:$campaign_data['campaign_title']; ?></div>
						<div class = 'stats_campaign_date'><strong>Sent On:</strong> <?php echo date('l F j, Y \a\t g:i a', strtotime( getGMTToLocalTime($campaign_data['email_send_date'], $this->session->userdata('member_time_zone')) ))?></div>
						<div class = 'stats_campaign_from'><strong>Sent From:</strong> <?php echo ucfirst($campaign_data['sender_name'])." &lt;"; ?><?php echo $campaign_data['sender_email']."&gt;"; ?></div>
						<div class = 'stats_campaign_lists'><strong>Sent To:</strong> <?php echo ucfirst($campaign_data['subscription_list_title']); ?></div>
					</div>
					<div class = 'stats_info_right'>
						<div class = 'stats_info_right_actions'>
							<a href = "<?php echo CAMPAIGN_DOMAIN.'c/'.$this->is_authorized->encryptor('encrypt',$campaign_id);?>">View</a> | 
							<a href = "<?php echo  site_url('promotions/create_from_campaign/'.$this->is_authorized->encryptor('encrypt',$campaign_id).'/1');?>">Copy</a> | 
							<a href = "<?php echo  site_url('promotions/create_from_campaign/'.$this->is_authorized->encryptor('encrypt',$campaign_id).'/2');?>">Re-Send</a>
						</div>
						<div class = 'stats_info_right_share'><strong>Share on:</strong> 
							<a href="http://www.facebook.com/share.php?u=<?php echo CAMPAIGN_DOMAIN.'c/'.$this->is_authorized->encryptor('encrypt',$campaign_id);?>&t=<?php echo $campaign_data['campaign_title']?>" title="Click to share this post on Facebook" target="_blank">Facebook</a> | 
							<a href="http://twitter.com?status=Here is our newest campaign : <?php echo CAMPAIGN_DOMAIN.'c/'.$this->is_authorized->encryptor('encrypt',$campaign_id);?> via BoldInbox" title="Click to share this post on Twitter" target="_blank">Twitter</a>
						</div>
						<div class = 'stats_info_select_b'>				
							<div class = 'stats_info_select'>
								<select name="select" onchange="javascript:fnShowNow(this);">						
									<option value='sent' <?php if($current_tab == 'sent')echo'selected';?>>Sent (<?php echo $sent_total_count;?>)</option>
									<option value='read' <?php if($current_tab == 'read')echo'selected';?>>Opened (<?php echo $read_total_count;?>)</option>
									<option value='unread' <?php if($current_tab == 'unread')echo'selected';?>>Unopened (<?php echo $unread_total_count;?>)</option>
									<option value='click' <?php if($current_tab == 'click')echo'selected';?>>Clicks (<?php echo $clicks_total_count;?>)</option>
									<option value='forwardemail' <?php if($current_tab == 'forwardemail')echo'selected';?>>Forwards (<?php echo $forward_total_count;?>)</option>
									<option value='unsubscribes' <?php if($current_tab == 'unsubscribes')echo'selected';?>>Unsubscribes (<?php echo $unsubscribes_total_count;?>)</option>
									<option value='bounced' <?php if($current_tab == 'bounced')echo'selected';?>>Bounces (<?php echo $bounced_total_count;?>)</option>
									<option value='complaints' <?php if($current_tab == 'complaints')echo'selected';?>>Complaints (<?php echo $complaints_total_count;?>)</option>		
								</select>
							</div>										
						</div>
					</div>
				</div>
				
			</div>
			<div class = 'contacts_section mgt0'>	
				<?php  if($current_tab != 'click'){?>
				<div class = 'contacts_tools'>
					<a href="javascript:void(0);" onclick="javascript:addToList('<?php echo trim($this->uri->segment(3));?>', '<?php echo $this->is_authorized->encryptor('encrypt',$campaign_id);?>');">Add to List</a>	
					<?php  if($extra['manage_contacts'] >  1){ ?>
					<a href="javascript:void(0);" onclick="javascript:confirmExport('<?php echo trim($this->uri->segment(3));?>','<?php echo $this->is_authorized->encryptor('encrypt',$campaign_id);?>');">Export To CSV</a>
					<?php } ?>
				</div>
				<?php  } ?>
				<div  class = 'contacts_heads'>
					<div class = 'ch_1'></div>
					<div class = 'ch_2'>Email Address</div>
					<div class = 'ch_3'>Name</div>
					<div class = 'ch_4'></div>
				</div>
				<div  class = 'contacts_list'>
				<?php
				$i=0;
           if(isset($emailreport_data) and count($emailreport_data)>0){
                foreach($emailreport_data as $subscriber){ 
					$i++;
				?>
					<div class = 'contacts_list_row_<?php if($i%2)echo"b";else echo"w";?>'>
						<div class = 'cl_1'>&nbsp;</div>
						<div class = 'cl_2'><a href = '#'><?php echo $subscriber['subscriber_email_address']; ?></a></div>
						<div class = 'cl_3'><?php echo $subscriber['subscriber_first_name']." ".$subscriber['subscriber_last_name']; ?></div>						
					</div>
				<?php
					}	
				}else{?>
					<div class = 'contacts_list_row_w'>
						<div class = 'cl_1'></div>
						<div class = 'cl_2'>No record found!</div>
					</div>
				<?php }?>					
				</div>	
			</div>	
<div class="pagination-container noajax"><?php echo $paging_links ?></div>					
			