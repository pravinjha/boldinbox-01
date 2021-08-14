<script src='https://www.google.com/recaptcha/api.js'></script>
<!-- Page Body - Public Access Starts -->
<div class = 'body-public'>
	<div class = 'body-box'>
		<div  class="msg info"><?php if(validation_errors()) echo validation_errors(); ?></div>
        <?php
            // display all messages
            if (is_array($messages)):
              echo '<div id="messages" class="msg info">';
              foreach ($messages as $type => $msgs):
                  foreach ($msgs as $message):
                    echo ('<span class="' .  $type .' error">' . $message . '</span>');
                  endforeach;
              endforeach;
              echo '</div>';
            endif;
        ?>
		<form action='<?php echo site_url("user/register/");?>' id="signup_form" method="post" class = 'body-form' onsubmit="javascript:loader();">			
			<div class = 'body-form-text'>It's Free! No Payment Required!</div>
			<div><?php
			$returnEmal = ($this->input->post('emailOuter')!='')?$this->input->post('emailOuter'):set_value('email');
			echo form_input(array('name'=>'email','type'=>'text','id'=>'email','maxlength'=>50,'placeholder' => 'Valid Email Id','value'=>$returnEmal,'autocorrect'=>'off','autocapitalize'=>'off','title'=>"Valid Email Id")) ; ?></div>
			<div><?php echo form_input(array('name'=>'username','type'=>'text','id'=>'username','maxlength'=>50,'value'=>set_value('username'),'autocorrect'=>'off','autocapitalize'=>'off','title'=>"Username",'placeholder' => 'Username')) ; ?></div>
			<div><input type="password" name="password" value="" maxlength="50" placeholder = 'Password'  title="Pasword" /></div>
			<div><input type = 'password' name = 'con_password' placeholder = 'Confirm Password'  /></div>
			<div><div class="g-recaptcha" data-sitekey="6LcN4wYUAAAAAKf4cDNbc_VT01tFh2IPZ_4S5s3W"></div></div>		 
			<div><?php echo form_submit(array('name' => 'btnRegister', 'id' => 'btnRegister', 'class' => 'button blue','content' => 'Create My Userboard','title'=>'Create My Userboard'), 'Create My Userboard'); ?></div>
			<div><a href = '<?php echo site_url("user/login");?>'><b>Already have an account? Sign-in here<b></a></div>			
		</form>		
	</div>
	<div class = 'clear10'></div>
</div>
<!-- Page Body - Public Access Ends -->
<script type="text/javascript">
function loader(){
 jQuery('.msg').html("<img border='0'  style='margin:0;' src='<?php echo $this->config->item('locker');?>images/icons/ajax-loader.gif' />");
}
$(function() {
$('#email').focus(function(){	$('.msg').hide(1000);	});
$('#username').focus(function(){	$('.msg').hide(1000);});
});
</script>

<!-- /WhatsHelp.io widget -->
<script type='text/javascript'>
var onWebChat={ar:[], set: function(a,b){if (typeof onWebChat_==='undefined'){this.ar.
push([a,b]);}else{onWebChat_.set(a,b);}},get:function(a){return(onWebChat_.get(a));},w
:(function(){ var ga=document.createElement('script'); ga.type = 'text/javascript';ga.
async=1;ga.src='//www.onwebchat.com/clientchat/b70471f4ec7221b8da9015247afe9de4';
var s=document.getElementsByTagName('script')[0];s.parentNode.insertBefore(ga,s);})()}
</script>


<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-88358076-1', 'auto');
  ga('send', 'pageview');

</script>