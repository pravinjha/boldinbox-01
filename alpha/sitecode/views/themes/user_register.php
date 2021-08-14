<link rel="stylesheet" href="<?php echo $this->config->item('locker').'themes/march2020/';?>styles/signup.css" />
<script src='//www.google.com/recaptcha/api.js'></script>
	<section class="sign-up">
        <div class="container">
          <div class="row">
            <div class="col-lg-6">
              <div class="inner-content">
                <div class="block-heading">
                  <h4>It's Free! No Payment Required!</h4>
                </div>
                <form action='<?php echo site_url("user/register/");?>' id="signup_frm" method="post">	
                  <div class="row">
                  	<div class="col-lg-12 msg info">
                  		<?php if(validation_errors()) echo validation_errors(); ?>
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
                  	</div>
                  </div>
                  <div class="row">
                    <div class="col-lg-12">
                    	<?php
						$returnEmal = ($this->input->post('emailOuter')!='')?$this->input->post('emailOuter'):set_value('email');
						echo form_input(array('name'=>'email','type'=>'text','id'=>'email','maxlength'=>50,'placeholder' => 'Valid Email Id','value'=>$returnEmal,'autocorrect'=>'off','autocapitalize'=>'off','title'=>"Valid Email Id")) ; 
						?>
                    </div>
                    <div class="col-lg-12">
                      <?php echo form_input(array('name'=>'username','type'=>'text','id'=>'username','maxlength'=>50,'value'=>set_value('username'),'autocorrect'=>'off','autocapitalize'=>'off','title'=>"Username",'placeholder' => 'Username')) ; ?>
                    </div>
                    <div class="col-lg-12">
                      <input type="password" name="password" autocomplete="off" value="" maxlength="50" placeholder = 'Password'  title="Pasword" />
                    </div>    
                    <div class="col-lg-12">
                      <input type = 'password' autocomplete="off" name = 'con_password' placeholder = 'Confirm Password'  />
                    </div>   
                    <div class="col-lg-12">
                    	<div class="g-recaptcha" data-sitekey="6LcN4wYUAAAAAKf4cDNbc_VT01tFh2IPZ_4S5s3W"></div>
                    </div>	
                    <div class="col-lg-12">
                   		<input type="hidden" value="save" name="is_submitted">
                    	<button type="submit" id="btnRegister" value="Create My Userboard" name="btnRegister" class="filled-button">Create My Userboard</button>
                    </div>
                    <div class="col-lg-12">
	                    <a href = '<?php echo site_url("user/login");?>'><b>Already have an account? Log-in here<b></a>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </section>



<script type="text/javascript">
$(document).ready(function(){
	
	//jQuery(function() {
	jQuery('#email').focus(function(){	jQuery('.msg').hide(1000);	});
	jQuery('#username').focus(function(){	jQuery('.msg').hide(1000);});
	//});
});
$(document).ready(function(){
    $("#btnRegister").click(function(){        
        jQuery("#signup_frm")[0].submit(); // Submit the form
    });
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