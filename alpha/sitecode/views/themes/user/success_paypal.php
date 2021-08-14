<?php echo link_tag('locker/css/base.css?v=6-20-13'); ?>
<?php echo link_tag('locker/css/utils.css?v=6-20-13'); ?>
<style>body{background: #f4f4f4}</style>
<!--[body]-->
<div style="width:100%;text-align:center;margin:100px auto;">
  <div  class="thanks-box" style="width:100%;text-align:center;">
    <div class="thanks-msg" style="width:350px;background:#fff">
      <?php echo $msg; ?>
    </div>
    <div class="gap"></div>
    <div class="gap"></div>
    <?php if($rc_logo==1){
       echo '<a href="'. site_url("/").'"> <img src="'. $this->config->item('locker').'images/powered-by-logo-blue.png" alt="logo" title="logo" border="0"></a>';
	 
    } ?>
  </div>
</div>
