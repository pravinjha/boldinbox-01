
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>BoldInbox: Report Abuse</title>
<?php echo link_tag('locker/css/base.css'); ?>
<?php echo link_tag('locker/css/utils.css'); ?>
</head>
<body>
<!--[body]-->
<div style="width:100%;text-align:center;margin:100px auto;">
  <div  class="thanks-box" style="width:100%;text-align:center;">
    
      <?php echo $msg; ?>
    
   <br/>
   <br/>
   <br/>
   <br/>
    <?php if($rc_logo==1){
       echo '<a href="'. site_url("/").'"> <img src="'. $this->config->item('locker').'images/powered-by-logo-blue.png" alt="logo" title="logo" border="0"></a>';	 
    } ?>
  </div>
</div>
</body>
</html>