	<div style="height:400px; text-align:center;">
	<div class="clear10"></div>
    <div class="clear10"></div>
 
      <?php echo ($msg); ?>
     
    <div class="clear10"></div>
    <div class="clear10"></div>
	</div>
    
    <?php if($rc_logo==1){ 
		echo '<div id="footesr-logo" style="text-align:center;border:solid 0px;width:216px;margin:20px auto;margin-bottom:40px;box-shadow:2px 3px 5px #888;"><a href="'.site_url("/").'" style = "border:solid 0px;text-decoration:none;color:#111;">Powered By<img src="'. $this->config->item('locker').'images/powered-by-logo-blue.png" alt="logo" title="logo" border="0" /></a></div>';
    } ?>