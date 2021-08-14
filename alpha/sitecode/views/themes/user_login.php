<link rel="stylesheet" href="<?php echo $this->config->item('locker').'themes/march2020/';?>styles/signup.css" />
<!-- Login -->
      <section class="sign-up">
        <div class="container">
          <div class="row">
            <div class="col-lg-6">
              <div class="inner-content">
                
                <form name = 'signin' id="signin" method="post" onsubmit="ajaxLogin(this); return(false);">
                
                	<div class="block-heading"><h4>Sign-in to Your Userboard</h4></div>	
                  <div class="row">
                  	<div class="col-lg-12"><p class="msg info" ></p></div>
                    <div class="col-lg-12">
                      	<input type="text" tabindex="1" class="form-control" title="username" id="log" name="member_username" autocorrect="off" autocapitalize="off" placeholder="Username or Email" />	
                    </div>
                    <div class="col-lg-12">
                      	<input type="password" tabindex="2" title="password" class="form-control" value="" id="pwd" placeholder = 'Password' name="member_password" autocorrect="off" autocapitalize="off">
                    </div>
                    <div class="col-lg-12 remember_me_chk">
						<div class = 'row'>
							<div class = 'col-1'><input type="checkbox" tabindex="3" value="ok" name="remember_login" id="remember_login" class="checkbox" /></div>
							<div class = 'col-10'>Remember Me</div>
						</div>                    	
                    </div>   
					<div class="col-lg-12">
                    	<div>&nbsp;</div>
                    </div>					
                    <div class="col-lg-12 ">
                    	<input type="hidden" value="save" name="action">
                      	<button type="submit" name = 'btnLogin' id="signin_submit" class="filled-button" >Take Me To Userboard</button>
                    </div>
                  </div>
                </form>
                <div class="row">
                  	<div class="col-lg-12"><a onclick="javascript: showForgotPwd();" href="javascript:void(0);"><b>Lost your password? Find it here</b></a></div>
					<div class="col-lg-12"><a href = '<?php echo site_url("user/register");?>'><b>Don't have an account yet? Sign-up here</b></a></div>
				</div>	
				 
				  <form onsubmit="ajaxForgotPassword(this); return(false);" method="post" id="forgotPwdBlock" name="forgot" style="display: none">
					<div class="block-heading"><h4>Forgot Your Passwrod?</h4></div>
					<div class="row">
                  	<div class="col-lg-12"><p class="fr_msg info"></p></div>
                    <div class="col-lg-12">							
						<?php echo form_input(array('name'=>'email_address','id'=>'log','maxlength'=>50, 'autocorrect'=>'off','autocapitalize'=>'off','placeholder'=>'Registered Email Address','value'=>set_value('email_address'))); ?>			
					</div>           
					<div class="col-lg-12">
						<input type="hidden" value="submit" name="action">
						<button type="submit" name = 'btnForgot' id="signin_submit" class="filled-button" >Reset Password</button>
						<a href="javascript:void(0);" id="show_login_block" class="button large grey2" onclick="javascript: showLoginBlock();">Cancel</a>		             
					</div>
				  </form>
		  
		  
              </div>
            </div>
          </div>
        </div>
      </section>
 <script type="text/javascript">
    function ajaxLogin(frm){
       
	  jQuery('.msg').html("<img border='0'  style='margin:0;' src='<?php echo $this->config->item('locker');?>images/icons/ajax-loader.gif' />");
	  // Collect login form variables
      // var block_data+="action=save&"+'&member_username='+frm.member_username.value+'&member_password='+frm.member_password.value;
      
      jQuery.ajax({
        url: "<?php echo base_url() ?>user/login/",
        type:"POST", 
        data:jQuery("form[name=signin]").serialize(),
        success: function(data) { 
          // if get error in login then display error
			if(data=="error"){
				jQuery('#pwd').val('');
				jQuery('.msg').html("Incorrect Username and/or Password.");
				jQuery('.msg').show();
			}else if(data=="locked"){
				jQuery('#pwd').val('');
				jQuery('.msg').html("Your account has been temporarily blocked for too many failed login attempts. Please try again later.");
				jQuery('.msg').show();
			}else if(data=="inactive"){
				jQuery('.msg').html("Redirecting to your userboard");
				jQuery('.msg').show();
				document.location="<?php echo base_url() ?>user/user_account_inactive_message";
			}else if(data=="success"){
				jQuery('.msg').html("Redirecting to your userboard");
				jQuery('.msg').show();
				parent.document.location="<?php echo base_url() ?>promotions";
			}
        } 
      });
      return false;
    }
    /**
    *  function for forgot password using ajax
    */
    function ajaxForgotPassword(frm){
      var block_data="";
      // Collect forgot password form variables
      block_data+="action=submit&"+'&email_address='+frm.email_address.value;
      jQuery.ajax({
        url: "<?php echo base_url() ?>user/forgot_password/",
        type:"POST",
        data:block_data,
        success: function(data) {
          // if get error in forgot password then display error
          if(data=="error"){
            jQuery(".content").css("min-height","20px");
            if(frm.email_address.value==""){
              jQuery('.fr_msg').html('Please enter your registered email address.');
            }else{
              jQuery('.fr_msg').html('Your email doesn\'t seem to be registered with us.');
            }
            jQuery('.fr_msg').show();
          }else if(data=="success"){
            frm.email_address.value ='';
            // if success in forgot password then send mail
            jQuery(".content").css("min-height","20px");
            jQuery('.fr_msg').html('An email has been sent to you containing a link to reset your password.');
            jQuery('.fr_msg').show();
          }
        }
      });
      return false;
    }

    /**
    *  function for show login popup window
    */
    function showForgotPwd(){		
      $('#signin').hide();
      $('#forgotPwdBlock').show();
      jQuery('.msg').hide();
      jQuery('.fr_msg').hide();
    }
    /**
    *  function for show forgot password popup window
    */
    function showLoginBlock(){
      $('#forgotPwdBlock').hide();
      $('#signin').show();
      jQuery('.msg').hide();
      jQuery('.fr_msg').hide();
    }
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