<!doctype html>
<html lang="en">
<head></head>
<body>
<div class = 'main-preview1'>
	
		<?PHP
			if($campaign_template_option==5){
				$c = "<pre>".htmlspecialchars($campaign_content).'</pre>';
			}else if(($campaign_template_option==4)||($campaign_template_option==2)){
				$c = html_entity_decode($campaign_content, ENT_QUOTES, "utf-8" );
			}else if($campaign_template_option==1){
				$c = htmlspecialchars_decode ($campaign_content);
			}else{
				$c = $campaign_content;
			}
			echo ($c);
		?>

</div>
</body>

</html>