<section class="section-new  campaign-list">
	<div class="container">
		<div class="row">
			<div class="col-lg-12">
				<div class="section-inner campaign-list1">					
					<div class="row">
						
						<?php if(count($emailreport_data)>0){ ?>
							<!-- Pie Chart includeables -->
								 <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
							<script type="text/javascript">
							$(document).ready(function(){
							google.charts.load('current', {'packages':['corechart']});
							
							
							<?php foreach($emailreport_data as $key=>$email){  
							if($email['total_delivered_emails']>0){
							?>
								// google.charts.setOnLoadCallback(function(){ drawChart('20') });
							google.charts.setOnLoadCallback(function(){ drawChart("<?php echo $key; ?>","<?php echo $email['total_read_emails']; ?>","<?php echo $email['total_click_emails']; ?>","<?php echo $email['email_track_forward']; ?>","<?php echo $email['total_unread_emails']; ?>","<?php echo $email['email_track_bounce']; ?>","<?php echo $email['total_unsubscribes']; ?>","<?php echo $email['total_complaint_emails']; ?>")});
							<?php }
							}
							 ?>	
							function drawChart(key,open,click,forward,unopen,bounce,unsubs,abuse) {								
							var data = google.visualization.arrayToDataTable([
							['Task', 'Campaign Stats'],
							['Open', Number(open)],
							['Clicks', Number(click)],
							['Forward', Number(forward)],
							['Unopen', Number(unopen)],
							['Bounce', Number(bounce)],
							['Unsubs', Number(unsubs)],
							['Abuse', Number(abuse)]
							]);
							// alert(open);
							var options = {							
							is3D:false,				
							/* legend: {position: 'right', textStyle: {color: 'grey', fontSize: 10}}, */
							width: '100%',
							height: '100%',
							legend: 'none',
							pieSliceText: 'label',							
							/* pieSliceText: 'percentage',		 */					
							pieStartAngle: 0,
							backgroundColor: 'transparent',
							/* chartArea: {'left':'10','width': '100%', 'height': '100%'}, */
							chartArea: {'top':'5','width': '90%', 'height': '90%'},
							colors: ['#3B5998', '#06D755', '#FF7C00', '#D73532', '#BB55BB', '#009999', '#FF0000'],
							slices: {  0: {offset: 0.1} },
							pieStartAngle: 220
							
												
							};

							var chart = new google.visualization.PieChart(document.getElementById('piechart3d_'+key));

							chart.draw(data, options);
							}


							});
							</script>
							
							<!-- Pie Chart includeables -->
							<?php foreach($emailreport_data as $key=>$email){
									if($email['total_delivered_emails']>0){	
							?>
							<div class="col-lg-12" id = "campaign_<?PHP echo $campaign['enc_cid'];?>">
								<div class="campaign-post">
							   <div class="row">
								  <div class="col-lg-3 col-md-5 col-sm-3">	
									  <div class = 'piechart3d' id="piechart3d_<?PHP echo $key; ?>"></div>
								  </div>
								  <div class="col-lg-9 col-md-7 col-sm-9">
									<div class = 'stats_details'>			
										<a style = 'color:#262626;' href = '<?php echo ($email['total_delivered_emails']==0) ?  "javascript:void(0)" :   site_url("stats/detail/sent/".$email['enc_cid']);?>'  class = 'buttonSmToLinks'>Sent: <?php echo $email['total_delivered_emails']; ?></a>
										<a style = 'color:#3B5998;' href = '<?php echo site_url("stats/detail/read/".$email['enc_cid']);?>'  class = 'buttonSmToLinks'>Opened: <?php echo $email['total_read_emails']; ?></a>
										<a style = 'color:#06D755;' href = '<?php echo site_url("stats/detail/click/".$email['enc_cid']);?>'  class = 'buttonSmToLinks'>Clicks: <?php echo $email['total_click_emails']; ?></a>
										<a style = 'color:#FF7C00;' href = '<?php echo site_url("stats/detail/forwardemail/".$email['enc_cid']);?>'  class = 'buttonSmToLinks'>Forwards: <?php echo $email['email_track_forward']; ?></a>
										<a style = 'color:#D73532;' href = '<?php echo site_url("stats/detail/unread/".$email['enc_cid']);?>'  class = 'buttonSmToLinks'>Unopened: <?php echo $email['total_unread_emails']; ?></a>
										<a style = 'color:#BB55BB;' href = '<?php echo site_url("stats/detail/bounced/".$email['enc_cid']);?>'  class = 'buttonSmToLinks'>Bounces: <?php echo $email['email_track_bounce']; ?></a>
										<a style = 'color:#009999;' href = '<?php echo site_url("stats/detail/unsubscribes/".$email['enc_cid']);?>'  class = 'buttonSmToLinks'>Unsubscribes: <?php echo $email['total_unsubscribes']; ?></a>
										<a style = 'color:#FF0000;' href = '<?php echo site_url("stats/detail/complaints/".$email['enc_cid']);?>'  class = 'buttonSmToLinks'>Complaints: <?php echo $email['total_complaint_emails']; ?></a>										
									</div>
								  
								    <ul class ="campaign-share">
										<li><a href="http://www.facebook.com/share.php?u=<?php echo CAMPAIGN_DOMAIN.'c/'.$email['enc_cid'];?>&t=<?php echo $email['campaign_title']?>" title="Click to share this post on Facebook" target="_blank" class = 'pink btnfb round round40'><i class="fa fa-facebook"></i></a></li>
										<li><a href="http://twitter.com?status=Here is our newest campaign : <?php echo CAMPAIGN_DOMAIN.'c/'.$email['enc_cid'];?> via BoldInbox" title="Click to share this post on Twitter" target="_blank" class = 'pink btntw round round40'><i class="fa fa-twitter"></i></a></li>
										<li><a href="#search" class = 'pink btninsta round round40'><i class="fa fa-instagram"></i></a></li>
										<li><a href="#search" class = 'pink btnwa round round40'><i class="fa fa-whatsapp"></i></a></li>
										<li><a href="#search" class = 'pink btnpin round round40'><i class="fa fa-pinterest-p"></i></a></li>
										<li><a href="#search" class = 'pink btnli round round40'><i class="fa fa-linkedin"></i></a></li>
									</ul>
								  
									<ul class ="campaign-links">										
										<li><a class = 'pink round round40' href = "<?php echo CAMPAIGN_DOMAIN.'c/'.$email['enc_cid'];?>" title="view" target="_blank"><i class="fa fa-eye"></i></a></li>
										<li><a class = 'pink round round40' href = "<?php echo  site_url('promotions/create_from_campaign/'.$email['enc_cid'].'/1');?>" title="Copy"><i class="fa fa-clone"></i></a></a></li>
										<li><a class = 'pink round round40' href = '<?php echo  site_url('promotions/create_from_campaign/'.$email['enc_cid'].'/2');?>' title="Re-send"><i class="fa fa-paper-plane-o"></i></a></li>
									</ul>
									
									 
									
									<div class = 'divider font3 fontBlack font500'><?php echo ucfirst($email['campaign_title']);?></div>
									<div class = 'font5 fontGrey font500'>
										Sent On: <?php echo date('l F j, Y \a\t g:i a', strtotime( getGMTToLocalTime($email['email_send_date'], $this->session->userdata('member_time_zone')) ))?>				
									</div>									
									<div class = 'font4 fontGrey font400'>
										Sent From: <?php echo ucfirst($email['sender_name'])." &lt;"; ?><?php echo $email['sender_email']."&gt;"; ?>
									</div>
									
									<div class = 'font4 fontGrey font400'>
										<?php 
										if($email['subscription_list_title']  !=''){  
										?>
										Sent To: <?php echo ucfirst($email['subscription_list_title']); ?>
										<?php } ?>
									</div>
									
									
								  
								  </div>
								  
							  </div>
								</div>
							  </div>
							<?php 
									}// End of IF
								  }// End of For loop 
							?>
							<div class="col-lg-12">
								<div class="campaign-post">
									<div class="campaign-pagination"><?php echo $paging_links ?></div>
								</div>
							</div>
						<?php }else {// End of IF ?>						
							<div class="col-lg-12">
								<div class="alert alert-warning" role="alert">
									You have not created any campaign yet.
								</div>
							</div>			
						<?php } ?>
						
						
						
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
					
					
					
					
					
					
					
					
					
				
					
					
