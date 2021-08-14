<?php
	if($email_subject !="")
		$subject=$email_subject;
	else
	$subject=$campaign_title;
	$subject=str_replace('$', '&#36;',$subject);
	
	$title="<title>$subject</title>\n";
	$meta=	'<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />'."\n".
			'<meta name="SKYPE_TOOLBAR" content="SKYPE_TOOLBAR_PARSER_COMPATIBLE" />'."\n".
			'<META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW">';
	
	
	
	$css='<link href="'.$this->config->item("locker").'/css/email_preview.css?v=6-20-13" rel="stylesheet"></link>'."\n";
	$js='<script type="text/javascript" src="https://ws.sharethis.com/button/buttons.js"></script>'."\n".
		'<script type="text/javascript">stLight.options({publisher: "ur-eca47de6-bbd8-292f-ea06-d74b8874e989", doNotHash: false, doNotCopy: false, hashAddressBar: false});</script>';
	
	$fb_meta_data = $this->is_authorized->getOGMetaTags($campaign_content, "$subject");
	
	if (stripos($campaign_content, '<head>') !== false) {
		$campaign_content=str_replace("<head>","<head>".$title . $meta . $fb_meta_data . $css . $js,$campaign_content);	
	}else{		
		$campaign_content = preg_replace('/<body(\s[^>]*)?>/i', '<head>'.$title . $meta . $fb_meta_data . $css . $js.'</head><body\\1>', $campaign_content);  
	}
	$campaign_content=str_replace("<title>".SYSTEM_DOMAIN_NAME." Campaign</title>","", $campaign_content);	
	
	$topBar = '<div class="campaign_view">					
						<div class = "campaign_view_subject">'.$subject.'</div>					
						<div id="share-this" class = "campaign_view_share">
							<span class="st_facebook_hcount" displayText="Facebook"></span>
							<span class="st_twitter_hcount" displayText="Tweet"></span>
							<span class="st_linkedin_hcount" displayText="LinkedIn"></span>
							<span class="st_pinterest_hcount" displayText="Pinterest"></span>
						</div>
				</div>';

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

<meta name="description" content="">
<meta name="author" content="">

<!-- Mobile Specific Metas
================================================== -->
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
<!-- CSS
================================================== -->
<link rel="stylesheet" href="<?php echo $this->config->item('locker');?>css/base.css">
<link rel="stylesheet" href="<?php echo $this->config->item('locker');?>css/utils.css">
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
<script type="text/javascript" src="<?php echo $this->config->item('locker');?>js/jquery-1.5.1.min.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		$('a').attr('target','_blank');
	});
</script>
<!-- Favicons
================================================== -->

<link rel="shortcut icon" href="<?php echo $this->config->item('locker');?>images/favicon.ico">
</head>
<body style="background-color:<?php echo $campaign_outer_bg;?>">
<div class = 'main-preview'>
	<div class = 'body-private-preview' style="background-color:<?php echo $campaign_outer_bg;?>">
		<?PHP
		if(!$hideHeader)echo $topBar;
		
			if($campaign_template_option==5){
				$c = "<div id='outer' style = 'border:solid 0px;'><div id='inner' style = 'border:solid 0px;'><pre>".htmlspecialchars($campaign_content).'</pre></div></div>';
			}else if(($campaign_template_option==4)||($campaign_template_option==2)){
				$c = html_entity_decode($campaign_content, ENT_QUOTES, "utf-8" );
			}else if($campaign_template_option==1){
				$c = htmlspecialchars_decode ($campaign_content);
			}else{
				$c = $campaign_content;
			}
			echo ($c);
		?>
		
		<?php 
		
		if($rc_logo==1){ 		
		echo '<div id="footesr-logo" style="text-align:center;border:solid 0px;width:216px;margin:20px auto;margin-bottom:40px;box-shadow:2px 3px 5px #888;"><a href="'.site_url("/").'" style = "border:solid 0px;text-decoration:none;color:#111;">Powered By<img src="'. $this->config->item('locker').'images/powered-by-logo-blue.png" alt="logo" title="logo" border="0" /></a></div>';
} ?>
	</div>
	
<div class = 'campaign_view_footer'>
	&copy; <?php echo SYSTEM_DOMAIN_NAME.' '.date('Y');?>, All Rights Reserved.
</div>
<!-- footer - private access ends -->
	
</div>
</body>

</html>