<?php if(count($emailreport_data)>0){ ?>
<script language="javascript" type="text/javascript" src="<?php echo $this->config->item('locker');?>/js/jquery.flot.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo $this->config->item('locker');?>/js/jquery.flot.pie.js"></script>
<script language = 'javascript' type="text/javascript">
	$(document).ready(function(){	
	function getpie(placeholder, series){
		var data = [];
		var i=0;
		$.each(series, function(k,v){data[i] = {	label: k, data:v }; i++; } );
		
		var placeholder = $("#"+placeholder);
		placeholder.unbind();
		$.plot(placeholder, data, {
			series: {	pie: { show: true, radius: 500,  	label: {show: true, formatter: labelFormatter, threshold: 0.1	}	} },
			legend: {	show: false	}
		});
	}

<?php foreach($emailreport_data as $key=>$email){  
if($email['total_delivered_emails']>0){
?>
	var x = {'Open' :"<?php echo $email['total_read_emails']; ?>", 'Un-Open': "<?php echo $email['total_unread_emails']; ?>",'Click': "<?php echo $email['total_click_emails']; ?>",'Forward': "<?php echo $email['email_track_forward']; ?>",'Unsubscribe': "<?php echo $email['total_unsubscribes']; ?>",'Bounce': "<?php echo $email['email_track_bounce']; ?>",'Complaint': "<?php echo $email['total_complaint_emails']; ?>"};
	getpie('piecontainer_<?php echo $key; ?>', x);		
<?php }
}
 ?>	
});
</script>
<h2 style="margin-left:10px;"> Generating Real Time Stats</h2>
<?php foreach($emailreport_data as $key=>$email){  
if($email['total_delivered_emails']>0){
?>

<div class = 'stats_block'>
				<div class = 'stats_header'>
					<div class = 'stats_info_left'>
						<div class = 'stats_campaign_name'><?php echo $email['campaign_title']; ?></div>
						<div class = 'stats_campaign_date'><strong>Sent On:</strong> <?php echo date('l F j, Y \a\t g:i a', strtotime( getGMTToLocalTime($email['email_send_date'], $this->session->userdata('member_time_zone')) ))?></div>
						<div class = 'stats_campaign_from'><strong>Sent From:</strong> <?php echo ucfirst($email['sender_name'])." &lt;"; ?><?php echo $email['sender_email']."&gt;"; ?></div>
						<div class = 'stats_campaign_lists'><strong>Sent To:</strong> <?php echo ucfirst($email['subscription_list_title']); ?></div>
					</div>
					<div class = 'stats_info_right'>
						<div class = 'stats_info_right_actions'>
							<a href = "<?php echo CAMPAIGN_DOMAIN.'c/'.$email['enc_cid'];?>" target="_blank">View</a> | 
							<a href = "<?php echo  site_url('promotions/create_from_campaign/'.$email['enc_cid'].'/1');?>">Copy</a> | 
							<a href = '<?php echo  site_url('promotions/create_from_campaign/'.$email['enc_cid'].'/2');?>'>Re-Send</a>
						</div>
						<div class = 'stats_info_right_share'><strong>Share on:</strong> 
							<a href="http://www.facebook.com/share.php?u=<?php echo CAMPAIGN_DOMAIN.'c/'.$email['enc_cid'];?>&t=<?php echo $email['campaign_title']?>" title="Click to share this post on Facebook" target="_blank">Facebook</a> | 
							<a href="http://twitter.com?status=Here is our newest campaign : <?php echo CAMPAIGN_DOMAIN.'c/'.$email['enc_cid'];?> via BoldInbox" title="Click to share this post on Twitter" target="_blank">Twitter</a>
						</div>
					</div>
				</div>
				<div class = 'stats_content'>
					<div class = 'stats_pie' id = 'piecontainer_<?php echo $key; ?>'></div>
					<div class = 'stats_details'>
						<a href = '<?php echo ($email['total_delivered_emails']==0) ?  "javascript:void(0)" :   site_url("stats/detail/sent/".$email['enc_cid']);?>'>Sent: <?php echo $email['total_delivered_emails']; ?></a>
						<a href = '<?php echo site_url("stats/detail/read/".$email['enc_cid']);?>'>Opened: <?php echo $email['total_read_emails']; ?></a>
						<a href = '<?php echo site_url("stats/detail/unread/".$email['enc_cid']);?>'>Unopened: <?php echo $email['total_unread_emails']; ?></a>
						<a href = '<?php echo site_url("stats/detail/click/".$email['enc_cid']);?>'>Clicks: <?php echo $email['total_click_emails']; ?></a>
						<a href = '<?php echo site_url("stats/detail/forwardemail/".$email['enc_cid']);?>'>Forwards: <?php echo $email['email_track_forward']; ?></a>
						<a href = '<?php echo site_url("stats/detail/unsubscribes/".$email['enc_cid']);?>'>Unsubscribes: <?php echo $email['total_unsubscribes']; ?></a>
						<a href = '<?php echo site_url("stats/detail/bounced/".$email['enc_cid']);?>'>Bounces: <?php echo $email['email_track_bounce']; ?></a>
						<a href = '<?php echo site_url("stats/detail/complaints/".$email['enc_cid']);?>'>Complaints: <?php echo $email['total_complaint_emails']; ?></a>
						
					</div>
					<div class = 'stats_details' style="display:none;">
						<a href = '#'>Sent: <?php echo $email['total_delivered_emails']; ?></a>
						<a href = '#'>Opened: <?php echo $email['total_read_emails']; ?></a>
						<a href = '#'>Unopened: <?php echo $email['total_unread_emails']; ?></a>
						<a href = '#'>Clicks: <?php echo $email['total_click_emails']; ?></a>
						<a href = '#'>Forwards: <?php echo $email['email_track_forward']; ?></a>
						<a href = '#'>Unsubscribes: <?php echo $email['total_unsubscribes']; ?></a>
						<a href = '#'>Bounces: <?php echo $email['email_track_bounce']; ?></a>
						<a href = '#'>Complaints: <?php echo $email['total_complaint_emails']; ?></a>
						
					</div>
				</div>
			</div>
<?php 
}// End of IF
}// End of For loop ?>
<div class="pagination-container noajax"><?php echo $paging_links ?></div>			
<?php }else {// End of IF ?>
 
      <div class="empty">
        <p style="padding: 50px; text-align:center; ">There is no campaign sent yet. So, there is no stats available.</p>
		<p style="padding: 0px; text-align:center; "><a href="<?php echo  site_url("promotions");?>" class="button grey2 large">Userboard</a></p>
      </div>
    <?php } ?>
   
<!--[/body]-->
