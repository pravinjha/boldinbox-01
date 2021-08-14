<?php
$strLayouts = '';
	if(count($customLayout) > 0){
	foreach($customLayout as $cid=>$screenshot){	 
		$src= ($screenshot !='')? $screenshot : $this->config->item('locker').'images/campaign_placeholder.jpg';
		$strLayouts .= "<div class = 'layout_thumbs_custom'>
					<a href='".site_url("promotions/create_from_campaign/$cid/0")."' alt='$cid'><img src='$src' width='205' height='154' /></a>
					<div class = 'layout_delete'><a href = 'javascript:void(0);'>X</a></div>
				</div>";
	}
	$strLayouts .= "<div class = 'clear10'></div><div style='clear:both;border-top:dashed 1px #CCC;margin-bottom:10px;margin-bottom:10px;'></div>";
	}
	for($i=1;$i < count($layout_list);$i++){
		
		$imgPath = $this->config->item('locker') ."templates/layouts/$i.png";
		$strLayouts .= "<div class = 'layout_thumbs'>
					<a href='".site_url("promotions/create_diy_campaign/$i")."'><img src='$imgPath' width = '205' height = '250' /></a>					
				</div>";
	}
?>

<div align = 'left' class = 'page_top_button_row'>		
	<div class = 'page_top_button_row_l'>Select a layout of your choice from below to start creating your new email campaign:</div>
	<div class = 'page_top_button_row_r'>
		<a href = '<?=site_url("promotions/create_diy_campaign/0")?>'><input type = 'button' name = 'blank_layout' id="blank_layout" value = 'Start with the blank layout' class = 'button blue large textCap' /></a>
	</div>
</div>


				<div class = 'layouts_container'>
				<?=$strLayouts?>
				</div>
			
        
<script type="text/javascript">
$(function() {
	$('.layout_delete a').live('click',function(){
		var cid = $(this).parent().parent().find('a').attr('alt');
		bibConfirm("Are you sure to delete this layout?",'delLayout("'+cid+'")');
	});
});

function delLayout(enc_cid){ 
		jQuery.ajax({ url: base_url+"promotions/update_campaign/"+enc_cid+"/delete_template", type:"POST", success: function(data){	window.location.reload();}});
}
</script>