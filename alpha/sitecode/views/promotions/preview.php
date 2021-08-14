<?php 
if($campaign_template_option == 3){
	$strEditorURL =  site_url('promotions/campaign_editor/'.$encrypted_cid);	
}elseif($campaign_template_option == 5){
	$strEditorURL =  site_url('promotions/plain_text/'.$encrypted_cid);
}elseif($campaign_template_option == 1){
	$strEditorURL =  site_url('promotions/url_import/'.$encrypted_cid);
}elseif($campaign_template_option == 2){
	$strEditorURL =  site_url('promotions/zip_import/'.$encrypted_cid);
}elseif($campaign_template_option == 4){
	$strEditorURL =  site_url('promotions/html_code/'.$encrypted_cid);
}else{	
	$strEditorURL =  '';
}	
$strSettingURL = site_url('campaign_email_setting/index/'.$encrypted_cid);


$subject	=	($email_subject !="")? $email_subject : $campaign_title;

$subject=str_replace('$', '&#36;',$subject);
	
	
 /*$campaign_content = preg_replace('/<body(\s[^>]*)?>/i', '<body\\1>'.$topBar, $campaign_content, 1);*/
 //$campaign_content = str_replace('https://boldinbox.com','http://boldinbox.com',$campaign_content);	
 //$campaign_content = hyperlinksAnchored($campaign_content);

	if($campaign_template_option==5){
		$c = '<pre>'.htmlspecialchars($campaign_content).'</pre>';
	}else if(($campaign_template_option==4)||($campaign_template_option==2)){
		$c = html_entity_decode($campaign_content, ENT_QUOTES, "utf-8" );
	}else if($campaign_template_option==1){
		$c = htmlspecialchars_decode ($campaign_content);
	}else{
		$c = $campaign_content;
	}
	
/**
 * Replace links in text with html links
 *
 * @param  string $text
 * @return string
 */
 function hyperlinksAnchored($text) {
    return preg_replace('@(http)?(s)?(://)?(([-\w]+\.)+([^\s]+)+[^,.\s])@', 'http$2://$4', $text);
}
?>
<!doctype html>
<html lang="en">
<head>

<!-- Basic Page Needs
================================================== -->
<meta charset="utf-8" />
<title><?php echo $subject;?></title>
<meta name="description" content="">
<meta name="author" content="">
<!-- Mobile Specific Metas
================================================== -->
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
<!-- CSS
================================================== -->
<link rel="stylesheet" href="<?php echo $this->config->item('locker');?>css/base.css">
<link rel="stylesheet" href="<?php echo $this->config->item('locker');?>css/utils.css">
<script type="text/javascript" src="<?php echo $this->config->item('locker');?>js/jquery-1.5.1.min.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		$('a').attr('target','_blank');
	});
</script>
<!-- Favicons
================================================== -->
<link rel="shortcut icon" href="<?php echo $this->config->item('locker');?>images/favicon.ico">
<style>
#outer{
    width:100%;
	margin-bottom: 30px;
    /* Firefox */
    display:-moz-box;
    -moz-box-pack:center;
    -moz-box-align:center;

    /* Safari and Chrome */
    display:-webkit-box;
    -webkit-box-pack:center;
    -webkit-box-align:center;

    /* W3C */
    display:box;
    box-pack:center;
    box-align:center;
}
#inner{
    width:50%;text-align:left;
}
pre{white-space: pre-wrap;}
.body-private-preview ul li{list-style:disc outside none;}
</style>
</head>
<body style="background-color:<?php echo $campaign_outer_bg;?>">
<div class = 'main-preview'>
	<!-- header - private access starts -->
<?php if($isScreenshot != 'y'){	?>	
	<div class = 'campaign_view'>
		<div class = 'campaign-preview-btn-left'><input type = 'button' class = 'button blue large' name = 'prev_step' value = "<< Go Back, Edit Campaign" onclick="javascript:window.location.href='<?php echo $strEditorURL; ?>';" /></div>
		<div class = 'campaign-preview-title'><?php echo substr($subject,0,150);?></div>
		<div class = 'campaign-preview-btn-right'><input type = 'button' class = 'button blue large' name = 'next_step' value = "Looks Good :) Next Step >>"  onclick="javascript:window.location.href='<?php echo $strSettingURL; ?>';" /></div>		
	</div>
<?php } ?>	
	<!-- header - private access ends -->
	<div class = 'body-private-preview' style="background-color:<?php echo $campaign_outer_bg;?>;border:solid 0px;">
	<!-- body - private access starts -->

<?php 

if($campaign_template_option == 5){
	echo "<div id='outer' style = 'border:solid 0px;'><div id='inner' style = 'border:solid 0px;'>". $c . "</div></div>";
}else{
	echo  $c;
}

if($rc_logo==1){ 		
	echo '<div id="footesr-logo" style="text-align:center;border:solid 0px;width:216px;margin:20px auto;margin-bottom:40px;box-shadow:2px 3px 5px #888;"><a href="'.site_url("/").'" style = "border:solid 0px;text-decoration:none;color:#111;">Powered By<img src="'. $this->config->item('locker').'images/powered-by-logo-blue.png" alt="logo" title="logo" border="0" /></a></div>';
}else{
	echo '<div id="footesr-logo" style="text-align:center;border:solid 0px;width:216px;margin:20px auto;margin-bottom:40px;">&nbsp;</div>';
}

?>
	<!-- body - private access ends -->
</div>
	
	
	<div class = 'campaign_view_footer'>
		&copy; <?php echo SYSTEM_DOMAIN_NAME.' '.date('Y');?>, All Rights Reserved.
	</div>
	<!-- footer - private access ends -->
	
</div>
</body>

</html>