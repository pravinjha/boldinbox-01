<?php echo link_tag('locker/css/base.css'); ?>
<?php echo link_tag('locker/css/utils.css'); ?>

<!--[body]-->
<div style="width:100%;text-align:center;margin:100px auto;">
  <div  class="thanks-box" style="width:100%;text-align:center;">
    
      <?php echo $msg; ?>
    
    <div class="gap"></div>
    <div class="gap"></div>
    <?php if($rc_logo==1){
      // echo '<a href="'. site_url("/").'"> <img src="'. $this->config->item('locker').'images/powered-by-logo-blue.png" alt="logo" title="logo" border="0"></a>';	 
    } ?>
  </div>
</div>
