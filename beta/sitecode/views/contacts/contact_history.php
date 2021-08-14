
				<?php foreach($email_report as $activity){

				$emailSubject = ($activity['email_subject']!='')?$activity['email_subject']:$activity['campaign_title'];

					if($activity['email_track_bounce']>0){
				?>
				<div class = 'history_list_roww'>
					<div class = 'history_list_d1'>
						<a href="<?php echo CAMPAIGN_DOMAIN.'c/'.$this->is_authorized->encryptor('encrypt',$activity['campaign_id']);?>"   title="view" target="_blank"><?php echo $emailSubject ?></a>
					</div>
					<div class = 'history_list_d2'>
						<a href="<?php echo  site_url("stats/display/".$activity['campaign_id']);?>"><?php echo date('l F j, Y \a\t g:i a', strtotime( getGMTToLocalTime($activity['email_send_date'], $this->session->userdata('member_time_zone')))); ?></a>
					</div>
					<div class = 'history_list_d3'><?php 
					if($activity['email_track_bounce']>0){
						if($activity['email_track_bounce']==2){ echo 'Soft Bounce';}elseif($subscriptions[0]['subscrber_bounce']!=1){echo 'Hard Bounce';}elseif($activity['soft_bounce_status']>$max_soft_bounce){echo 'Hard Bounce';}else{echo 'Soft Bounce';}
					}elseif($activity['email_track_complaint']>0){
						echo 'Complaint';
					}elseif(($activity['email_track_read']<=0)&&($activity['email_sent']>0)){
						echo 'Campaign Sent';
					}else{
						echo '';
					}	
					?></div>
				</div>