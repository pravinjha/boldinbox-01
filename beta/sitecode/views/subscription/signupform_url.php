<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta content="text/html; charset=UTF-8" http-equiv="Content-Type">
<title><?php echo $title; ?></title>

<!-- ------------Load js----------------------------- -->
<script type="text/javascript" src="<?php echo CAMPAIGN_DOMAIN .'locker/js/jquery-1.5.1.min.js?v=6-20-13';?>"></script>
<script type="text/javascript" src="<?php echo CAMPAIGN_DOMAIN .'locker/js/jquery.validate.js?v=6-20-13';?>"></script>
<script type="text/javascript" src='https://www.google.com/recaptcha/api.js'></script>
<!-- ------------Load Css----------------------------- -->
<link rel="shortcut icon" href="<?php echo base_url(); ?>favicon.ico">
<link rel="stylesheet" type="text/css" href="<?php echo CAMPAIGN_DOMAIN .'locker/css/signup_form.css?v=6-20-13';?>" />
<script type="text/javascript">
	$(document).ready(function() {
    <?php echo $signup_form['copy_js'];?>


    (function() {
      var color;
      var style = $("body").attr("style").split(";");

      for (var x = 0; x < style.length; x++) {
        style[x] = style[x].replace(/\s/g,"");

        if (style[x].indexOf("background-color") === 0) {
          color = style[x].replace("background-color:","");
        }
      }

      if (color) {
        if (color.indexOf("#") === 0) {
          var c = color.substring(1);      // strip #
          var rgb = parseInt(c, 16);   // convert rrggbb to decimal
          var r = (rgb >> 16) & 0xff;  // extract red
          var g = (rgb >>  8) & 0xff;  // extract green
          var b = (rgb >>  0) & 0xff;  // extract blue
        } else if (color.indexOf("rgb") === 0) {
          color = color.replace("rgb(","");
          color = color.replace(")","");
          color = color.split(",");

          var r = parseInt(color[0]);
          var g = parseInt(color[1]);
          var b = parseInt(color[2]);
        }

        var luma = 0.2126 * r + 0.7152 * g + 0.0722 * b; // per ITU-R BT.709

        if (luma > 60) {
          luma = Math.floor(Math.abs(luma - Math.floor(luma/2)));

          $(".footlink").css("color","rgb(" + luma + "," + luma + "," + luma + ")");
          $(".footlink a").css("color","rgb(" + luma + "," + luma + "," + luma + ")");
        } else {
          luma = Math.floor(luma + 180);

          $(".footlink").css("color","rgb(" + luma + "," + luma + "," + luma + ")");
          $(".footlink a").css("color","rgb(" + luma + "," + luma + "," + luma + ")");
        }
      }
    })();
	});
</script>
</head>
<body  style="background-color:<?php echo $signup_form['form_background_color'];?>;<?php echo $signup_form['bg_css'];?>;">
	<?php echo $signup_form['copy_code'];?>
	
	<?php echo '<div id="footesr-logo" style="text-align:center;border:solid 0px;width:216px;margin:20px auto;margin-bottom:40px;box-shadow:2px 3px 5px #888;"><a href="'.site_url("/").'" style = "border:solid 0px;text-decoration:none;color:#111;">Powered By<img src="'. $this->config->item('locker').'images/powered-by-logo-blue.png" alt="logo" title="logo" border="0" /></a></div>';?>
</body>
</html>
