<script type="text/javascript" src="<?php echo $this->config->item('locker');?>js/jquery-1.5.1.min.js?v=6-20-13"></script>
<script type="text/javascript" src="<?php echo $this->config->item('locker');?>js/fancybox/jquery.mousewheel-3.0.4.pack.js?v=6-20-13"></script>
<script type="text/javascript" src="<?php echo $this->config->item('locker');?>js/fancybox/jquery.fancybox-1.3.4.pack.js?v=6-20-13"></script>
<link href="<?php echo $this->config->item('locker');?>js/fancybox/jquery.fancybox-1.3.4.css?v=6-20-13" rel="stylesheet" type="text/css" />
<script type="text/javascript">
	jQuery('.tblheading').find('a').live('click',function(){
		//alert(jQuery(this).attr('href'));
		$("#Src_frm").attr("action", jQuery(this).attr('href'));
		$('#Src_frm').submit();
		return false;
	});
	jQuery('.disallow').live('click',function(){		 
		return (confirm('Are you sure you want to disallow campaign?'))? true : false;		
	});
	jQuery('.draft').live('click',function(){		 
		return (confirm('Are you sure to make this campaign draft again?'))? true : false;		
	});
	jQuery('.archive').live('click',function(){		 
		return (confirm('Are you sure to archive this campaign?'))? true : false;		
	});
    $(document).ready(function(){
		$(".fancybox").fancybox({'centerOnScroll':true,'height':'200','width':'300','scrolling':'auto'});
    });
	function updateVmta(c, v){
		var vmta =v.value;
		jQuery.ajax({
			url: "<?php echo base_url() ?>bandook/campaign/update_vmta/"+c+"/"+vmta,
			type:"POST",
			data: "mode=authentic",
			success: function(data) {
			$('#vmta-'+c).text(data);
			}
		});
	}
</script>
<style type="text/css">
ul.ul_actions{margin:0;padding:0;}
ul.ul_actions li{list-style-type:disc;margin-left:10px;padding:5px;width:100px;float:left;}
</style>
<form name="Src_frm" id="Src_frm" method="post" action="<?php echo base_url().'bandook/campaign/'.$mode;?>">
	<table border="0" cellspacing="0" cellpadding="0" class="tbl_forms">
		<tr>
			<td width="10%">Username</td>
			<td><?php echo form_input(array('name'=>'username','id'=>'username','maxlength'=>50,'size'=>40 ,'value'=>$contacts_array['username'])) ; ?></td>

		</tr>
		<tr>
			<td><input type="hidden" name="mode" value="search"/></td>
			<td>
				<input type="submit" name="btn_search" id="btn_search" value="Search" class="inputbuttons"/>
				<input type="submit" name="btn_cancel" id="btn_cancel" value="Show All" class="inputbuttons"/>
			</td>
		</tr>
	</table>
</form>
<div id="messages" style="color:#FF0000;">
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
<div class="tblheading"><?php echo ucfirst($mode); ?> Campaigns <?php echo '['. count($campaigns). ']';

//Display paging links
echo $paging_links ?> </div>
<table class="tbl_listing" width="100%">
	<thead>
		<tr>
			<th width="5%">ID</th><th width="15%">Campaign</th><th width="10%">User</th><th width="10%">VMTA</th><th width="10%">Contacts</th><th width="15%">Queued/Scheduled Date</th>
			<th width="10%">Released Date</th><th width="10%">Status</th><th width="15%">Actions</th>		
		</tr>
	</thead>
	<?php
//List all users
if(count($campaigns)) {
	foreach($campaigns as $campaign){

	?>
	<tr <?php if($campaign['is_segmentation']==1){echo"title='Ongoing campaign'";echo"style=background-color:#A5F2C0;";}?>>
		<td><?php 
		echo $campaign['campaign_id'];  
		if($campaign['campaign_id'] == $campaign_in_progress)echo "<br/><img title='Sending in progress' src='".$this->config->item('locker')."images/ajax-loader.gif' border='0' />";
		?>		
		</td>
		<td>
			<a target="_blank" href="<?php echo CAMPAIGN_DOMAIN.'c/'.$this->is_authorized->encryptor('encrypt',$campaign['campaign_id']);?>" <?php if($campaign['is_deleted']==1) echo "style='color:#ff0000;'"?>><?php echo substr($campaign['campaign_title'],0,20);  ?></a>
			<br/>
			Subject: <?php echo $campaign['email_subject'];  ?>
			<br/>
			From: <?php echo $campaign['sender_name']."<br/>".$campaign['sender_email'];?>
		</td>
		<td>
			<a href="<?php echo  site_url("bandook/users_manage/users_list/".$campaign['member_username']); ?>"><?php echo $campaign['member_username'];  ?></a>
		</td>
		<td>
		<select name="vmta"  style="width:150px;" onchange="javascript:updateVmta(<?php echo $campaign['campaign_id'];?>,this );">
		<?php
			$arrVMTAPool = $this->config->item('pool_and_vmta');
			$campaign['vmta'] = (!is_null($campaign['pipeline'] ))?$campaign['pipeline'] : $campaign['vmta']; 
			for($i=0; $i < count($arrVMTAPool); $i++){				 
				for($j=0; $j <  count($arrVMTAPool[$i]); $j++){
					if($j==0)echo "<optgroup label='".$arrVMTAPool[$i][$j]."'></optgroup>";
					if($campaign['vmta'] == $arrVMTAPool[$i][$j])$selIt = 'selected';else $selIt = '';
						echo "<option value='".$arrVMTAPool[$i][$j]."' $selIt>".$arrVMTAPool[$i][$j]."</option>";
				}
			}
		?>
		</select>		 
<span id="vmta-<?php echo $campaign['campaign_id'];?>" style="color:#ff6500"></span>
	</td>
		<td>
			<a  href="<?php echo  site_url("bandook/campaign/get_contacts/".$campaign['campaign_id']."/".$campaign['member_id']); ?>"><?php echo $contacts[$campaign['campaign_id']];  ?></a>
			<br/>
			<?php echo ltrim($campaign['list_names'],',');?>
		</td>
		<td>
			<?php 
				if($campaign['campaign_status']!="draft"){
					if(is_null($campaign['campaign_queued'])){
						echo date('F j, Y \a\t g:i a', strtotime( getGMTToLocalTime($campaign['campaign_sheduled'], date_default_timezone_get() )));
					}else{
						echo date('F j, Y \a\t g:i a', strtotime( getGMTToLocalTime($campaign['campaign_queued'], date_default_timezone_get() ) ));
					}
					echo "<br/>".date('F j, Y \a\t g:i a', strtotime( getGMTToLocalTime($campaign['campaign_sheduled'], date_default_timezone_get() ))); 
					if($campaign['campaign_delay_minute'] > 0)
						echo "<br/>".date('F j, Y \a\t g:i a', strtotime( getGMTToLocalTime($campaign['campaign_sheduled'], date_default_timezone_get() ))+ (60 * $campaign['campaign_delay_minute']) ); 
				
				}else{
					echo 'Not Sent';
				} 				
			?>
		</td>
		<td>
			<?php 			
				echo date('F j, Y \a\t g:i a', strtotime( getGMTToLocalTime($campaign['email_send_date'], date_default_timezone_get() ) )); 
				//if($campaign['campaign_status']=="active"){ 
				//	echo date('F j, Y \a\t g:i a', strtotime( getGMTToLocalTime($campaign['email_send_date'], date_default_timezone_get() ) )); 
				//}else{ 
				//	echo 'Not Sent';
				//} 
			?>
		</td>
		<td>
			
			
			<?php
				
				if(($campaign['campaign_status']=="ready")||($campaign['campaign_status']=="active_ready")){
					echo "Queued";
				  // }else if(($campaign['campaign_status']=="active")&&($campaign['is_segmentation']==1)&&($campaign['number_of_contacts']<$contacts[$campaign['campaign_id']])&&($email_not_send_contacts[$campaign['campaign_id']]>0)){
				  }else if(($campaign['is_segmentation']==1)&&($campaign['number_of_contacts']<$contacts[$campaign['campaign_id']])&&($email_not_send_contacts[$campaign['campaign_id']]>0)){
					echo "In-Queue: ".$email_not_send_contacts[$campaign['campaign_id']];
					echo "<br/>Segment: ".$campaign['number_of_contacts'];
					echo "<br/>Interval: ".$campaign['segment_interval']. ' minutes';
				  }else if($campaign['campaign_status']=="active"){
					echo "Archived";
				  }else if($campaign['campaign_status']=="archived"){
					echo "In-Queue: ".$email_not_send_contacts[$campaign['campaign_id']];
				  }else{
					echo ucfirst($campaign['campaign_status']);
				}
				
			?>
		</td>
		<td>
		<ul class="ul_actions">
			<li><a id="allow_<?php echo $campaign['campaign_id']; ?>" href="<?php echo  site_url("bandook/campaign/campaign_segmentation/".$campaign['campaign_id']."/".$campaign['member_id']."/$mode");?>" class="allow">Segmentation</a></li>	
			<li><a id="archive_<?php echo $campaign['campaign_id']; ?>" href="<?php echo site_url("bandook/campaign/archiveIt/".$campaign['campaign_id']."/$mode/");?>" class="archive">Archive</a></li>
			<li><a class="fancybox" href="<?php echo  site_url('bandook/campaign/showStats/'.$campaign['campaign_id']);?>">Stats</a></li>
			<li><a class="fancybox" href="<?php echo  site_url('bandook/campaign/ipr/'.$campaign['campaign_id']);?>">IPR</a></li>
			<li><a onclick="return confirm('Are you sure to delete campaign')" href="<?php echo  site_url('bandook/campaign/delete/'.$mode.'/'.$campaign['campaign_id']);?>"><font color="red">Delete</font></a></li>	
			</ul>					 
		</td>
		 
	</tr>
<?php }
	}else{
?>
	<tr><td colspan="10" align="center">No Campaign Available</td></tr>
<?php } ?>
</table>
