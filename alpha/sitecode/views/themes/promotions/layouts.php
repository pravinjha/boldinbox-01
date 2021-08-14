<script type="text/javascript">
$(function() {
	$(document).on('click','.layout-delete a',function(){
		var cid = $(this).parent().parent().find('a').attr('alt');
		bibConfirm("Are you sure to delete this template?",'delLayout("'+cid+'")');
	});
});

function delLayout(enc_cid){ 
		jQuery.ajax({ url: base_url+"promotions/update_campaign/"+enc_cid+"/delete_template", type:"POST", success: function(data){	window.location.reload();}});
}
</script>
<?php
$strLayoutsUserDefined = '';
$strLayoutsPreDefined = '';
	if(count($customLayout) > 0){
		foreach($customLayout as $cid=>$screenshot){	 
			$src= ($screenshot !='')? $screenshot : $this->config->item('locker').'images/campaign_placeholder.jpg';
			$strLayoutsUserDefined .= "<div class='card'>
										<a href='".site_url("promotions/create_from_campaign/$cid/0")."' alt='$cid'><img src='$src' /></a>
										<div class = 'layout-delete'>
										<button type='button' class = 'pink round round32'><i class='fa fa-trash'></i></button>										
									</div>
									</div>";
		}	
	}
	
	for($i=1;$i < count($layout_list);$i++){
		$imgPath = $this->config->item('locker') ."templates/layouts/$i.png";
		$strLayoutsPreDefined .= "<div class='card'>
										<a href='".site_url("promotions/create_diy_campaign/$i")."'><img src='$imgPath' /></a>
									</div>";
	}

?>

<section class="section-new">
<div class="container">
  <div class="row">
	<div class="col-lg-12">
	  <div class="section-inner">
		<div class="row">
			<div class="col-lg-12">
				<div class="campaign-post">
					<div class = 'row'>
						<div class="col-lg-8">
							<div class = 'pageHeading'>Select a template of your choice to start creating your new email campaign.
							</div>
						</div>
						<div class="col-lg-4">
							<button class = 'blue rectangle start_blank_banner' onclick = "javscript:window.location.href='<?=site_url("promotions/create_diy_campaign/0")?>';">Start With A Blank Template</button>
						</div>
					</div>
				</div>
			</div>		
			<?PHP if($strLayoutsUserDefined != ''){ ?>
			<div class="col-lg-12">
				<div class="campaign-post">
					<div class="col-lg-12">
						<div class = 'sectionHeading'>Your Saved Templates</div>
						<div class='card-columns'>
						<?=$strLayoutsUserDefined?>			  
						</div>
					</div>
				</div>
			</div>
			<?PHP } ?>
			<div class="col-lg-12">
				<div class="campaign-post">
					<div class="col-lg-12">
						<div class = 'sectionHeading'>Pre-Defined System Templates</div>
						<div class='card-columns'>
						<?=$strLayoutsPreDefined?>		  
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