<?php		 
            //Fetch campaings from campaigns array
            if(count($campaign_data['campaigns'])) {
            $i=1;
            ?>
<section class="section-new campaign-list">
        <div class="container">
          <div class="row">            
            <div class="col-lg-12">		
              <div class="section-inner campaign-list1"> 		 
				
              <div class="row">	
				<div class="col-lg-12">
                    <div class="campaign-post">
						<form method="post" class="form-website" id="campaignSearchFrm"  name="campaignSearchFrm">
                  <div class="row">
                    <div class="col-sm-3">
                    	<select name="campaign_search_by" id="campaign_search_by"  class="form-control"><option value="subject" <?php if($campaign_data['campaign_search_by'] =='subject')echo'selected'; ?>>Campaign Subject</option><option value="title" <?php if($campaign_data['campaign_search_by'] =='title')echo'selected'; ?>>Campaign title</option><option value="content" <?php if($campaign_data['campaign_search_by'] =='content')echo'selected'; ?>>Campaign content</option></select>                    
                    </div>
                    <div class="col-sm-9">
                    	<input type="text" name="campaign_search" id="campaign_search" placeholder="Campaign Search Keyword.." value="<?php echo $campaign_data['campaign_search'];?>"  class="form-control" required="" />
					  <button type="submit" class="primary-button pink round round40" name="btnSearchCampaign" id="btnSearchCampaign"><i class="fa fa-search"></i></button>
                    </div>                   
                    
                  </div>
                </form>
					</div>
			   </div>
			  
			  
			  
			  
			  
            <?php 
			
			foreach($campaign_data['campaigns'] as $campaign){ 
			
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
		
                	  
				  <div class="col-lg-12" id = "campaign_<?PHP echo $campaign['enc_cid'];?>">
                    <div class="campaign-post">
				   <div class="row">
					  <div class="col-sm-3">	
						  <div>
						  	<a href="<?php echo CAMPAIGN_DOMAIN.'c/'.$campaign['enc_cid'];?>"  target="_blank">
							<?php if($campaign['screenshot'] !=''){ $src= $campaign['screenshot'];}else{$src = $this->config->item('locker')."images/campaign_placeholder.jpg";}?>
							<img src='<?php echo $src;?>' />						
							</a>							
						  </div>
                      </div>
                      <div class="col-sm-9">	
					  <div>
					  <ul class ="campaign-links">
							<li><a class = 'pink round round40' href="<?php echo CAMPAIGN_DOMAIN.'c/'.$campaign['enc_cid'];?>" title="view" target="_blank"><i class="fa fa-eye"></i></a></li>
							<li>
								<?php if($thisCampaignStatus =='sent'){ ?>					
									<a class = 'pink round round40' href="<?php echo  site_url('promotions/create_from_campaign/'.$campaign['enc_cid'].'/2');?>" title="Resend">
								<?php }elseif($thisCampaignStatus == 'queued'){ ?>
									<!-- a href='javascript:void(0);' id='campaign_send_<?php //echo $campaign['enc_cid']?>' onclick="stopCampaign('<?php // echo $campaign['enc_cid']?>')" title="Stop" -->
								<?php }else{ ?>										
									<a class = 'pink round round40' href='javascript:void(0);' id='campaign_send_<?php echo $campaign['enc_cid']?>' <?php if($campaign_data['upgrade_package']==1) {?>onclick="bibAlert('You are over your current plan limit. Please Upgrade Now.');" <?php }else{ ?>  onclick="javascript:window.location.href='<?php echo  site_url("campaign_email_setting/index/".$campaign['enc_cid']);?>';" <?php } ?> title="Send">					
								<?php } ?>
								<i class="fa fa-paper-plane-o"></i></a></li>
							<li><a class = 'pink round round40' href="<?php echo  site_url('promotions/create_from_campaign/'.$campaign['enc_cid'].'/1');?>" title="Copy"><i class="fa fa-clone"></i></a></li>
							
							<?php if(3 == $campaign['campaign_template_option']){?>		
								<li><a class = 'pink round round40' class="create_template btn cancel"  title="Save as Template" name="<?php echo $campaign['enc_cid']; ?>" href="javascript:void(0);"><i class="fa fa-floppy-o"></i></a></li>
							<?php } ?>
							<li><?php if($thisCampaignStatus  =='sent'){ ?>
									<a class = 'pink round round40' href="javascript:void(0);" onclick="bibAlert('To edit, first click copy button to replicate this campaign, then edit and save changes');"  title="Edit">
								<?php }elseif($thisCampaignStatus =='queued'){ ?>								
									<a class = 'pink round round40' href="javascript:void(0);" id='campaign_edit_<?php echo $campaign['enc_cid']?>' onclick="bibAlert('This email campaign is already in queue and will be sent at scheduled time. To edit, first click Cancel Delivery and then edit campaign.');"  title="Edit" class="btn cancel" >
								<?php }else{?>
									<a class = 'pink round round40' href="javascript:void(0);" onclick="window.location.href='<?php echo $strEditorURL;?>'"  title="Edit">							
								<?php } ?><i class="fa fa-pencil-square-o"></i></a></li>
							
							<?php if($thisCampaignStatus =='sent'){ ?>
							  <li><a class = 'pink round round40' href="javascript:void(0);" onclick="window.location.href='<?php echo site_url("stats/display/".$campaign['enc_cid']);?>';"  title="Stats"><i class="fa fa-align-left"></i></a></li>
							  <?php }else{ ?>
							  <li><a class = 'pink round round40' href="javascript:void(0);" onclick="javascript: delCampaign('<?php echo $campaign['enc_cid'];?>');" data-toggle='modal' data-target='#messageBox' name="<?php echo $campaign['enc_cid']; ?>"  title="Delete"><i class="fa fa-trash"></i></a></li>
							<?php } ?>						
						</ul>	
						<?php if($campaign['campaign_status']=='active'){ ?>
						 <ul class ="campaign-share">
							<li><a href="http://www.facebook.com/share.php?u=<?php echo CAMPAIGN_DOMAIN.'c/'.$campaign['enc_cid'];?>&t=<?php echo $campaign['email_subject']?>" title="Click to share this post on Facebook" target="_blank" class = 'pink btnfb round round40'><i class="fa fa-facebook"></i></a></li>
							<li><a href="http://twitter.com?status=Here is our newest campaign : <?php echo CAMPAIGN_DOMAIN.'c/'.$campaign['enc_cid'];?> via BoldInbox" title="Click to share this post on Twitter" target="_blank" class = 'pink btntw round round40'><i class="fa fa-twitter"></i></a></li>
							<li><a href="#search" class = 'pink btninsta round round40'><i class="fa fa-instagram"></i></a></li>
							<li><a href="#search" class = 'pink btnwa round round40'><i class="fa fa-whatsapp"></i></a></li>
							<li><a href="#search" class = 'pink btnpin round round40'><i class="fa fa-pinterest-p"></i></a></li>
							<li><a href="#search" class = 'pink btnli round round40'><i class="fa fa-linkedin"></i></a></li>
						</ul>
						<?php }?>
						<div class = 'divider font3 fontBlack font500'><?php echo ucfirst($campaign['campaign_title']);?></div>
						<div class = 'font5 fontBlue font500'>
							<?php
								 // get campaign status
								$thisCampaignStatusInt = $campaign['campaign_status_show'];	
								if(trim($thisCampaignStatusInt)=='1'){
									echo 'Drafted On, '.date('F j, Y \a\t g:i a', strtotime($campaign['draftDate'])); //campaign not scheduled yet
									$thisCampaignStatus = 'draft';	
								}elseif(trim($thisCampaignStatusInt)=='3'){
									echo 'Suspended on, '.date('F j, Y', strtotime( $campaign['email_send_date']))."";  //campaign disallowed by admin	
									$thisCampaignStatus = 'draft';	
								//}elseif(($campaign['campaign_status']=='active')or((date('Y-m-d H:i:s', strtotime( $campaign['campaign_sheduled'])+ (($campaign['campaign_delay_minute'] + 30)*60)) < date("Y-m-d H:i:s")))){
								}elseif(trim($thisCampaignStatusInt)=='5'){
									//campaign sent or [ scheduled-time + delay-added-by-admin + 30 minutes] already past then show it as SENT 	
									echo "Sent on, ".date('F j, Y \a\t g:i a', strtotime( $campaign['email_send_date']))."";                  
									$thisCampaignStatus = 'sent';	
								}elseif(trim($thisCampaignStatusInt)=='4'){
									echo 'Processing.., '.date('F j, Y', strtotime( $campaign['email_send_date']))."";  // Campaign Approved by admin
									$thisCampaignStatus = 'draft';	
								}else{
									echo "Scheduled, ".date('F j, Y  \a\t g:i a', strtotime( $campaign['email_send_date']))."";  //campaign  waiting admin approval 
									$thisCampaignStatus = 'queued';		
								}			   
					   		?>						
						</div>
						<?php if(trim($campaign['email_subject']) != ''){
						echo "<div class = 'font4 fontGrey font400'>Subject: ". ucfirst($campaign['email_subject']) ."</div>";
						}?>
						<div class = 'font4 fontGrey font400'>
							<?php 
							if($campaign['subscription_list_title']  !=''){  
								echo "Sent To:" .  $campaign['subscription_list_title'] ;
							} 
							?>
						</div>
												
                      </div>
                      </div>
                      
                  </div>
                    </div>
                  </div>
                  			
		<?php $i++; }// FOR ends ?>
				<!--Display paging links -->
				<div class="col-lg-12">
					<div class="campaign-post">
						<div class="campaign-pagination"><?php echo $paging_links ?></div>
					</div>
				</div>
                </div>
              
             </div>
            </div>
          </div>
        </div>
      </section>
      <?php
			
			}else{  //record not found 
		?>	<section class="section-new campaign-list">
				<div class="container">
				  <div class="row">            
					<div class="col-lg-12">		
					  <div class="section-inner campaign-list1"> 
						<div class="row"> 
						<div class="col-lg-12">
							<div class="campaign-post">
								<div class = 'row'>
									<div class="col-lg-8">
										<div class = 'pageHeading'>You do not have any promotional campaigns created yet.
										</div>
									</div>
									<div class="col-lg-4">
										<button class = 'blue rectangle floatRight' onclick = "javscript:window.location.href='<?php echo  site_url('promotions/layouts') ;?>';">Create Campaign Now</button>
									</div>
								</div>
							</div>
						</div>	
					</div>	
					  </div>
					</div>			
				  </div>
				</div>
			  </section>
		 <?php } ?>	
		 
       
       
<script language = 'javascript' type="text/javascript">
function stopCampaign(cid){
	bibConfirm("Are you sure to cancel delivery of this campaign?",'stopSending("'+cid+'")');
}
function stopSending(cid){alert(cid);
	jQuery.ajax({ url: base_url+"promotions/update_campaign/"+cid+"/stop/", type:"POST", success: function(data){	window.location.reload();}});
}
</script>