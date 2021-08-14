<!-- start: LOGIN BOX -->
			<div class="box-login">
				<h3>Sign in to your account</h3>
				<p class="msg">
					Please enter your name and password to log in.
				</p>
				
				<form name = 'signin' class = 'body-form' id="signin" method="post" onsubmit="ajaxLogin(this); return(false);">
				<input type="hidden" value="save" name="action">
					<div class="errorHandler alert alert-danger no-display">
						<i class="fa fa-remove-sign"></i> You have some form errors. Please check below.
					</div>
					<fieldset>
						<div class="form-group">
							<span class="input-icon">							
								<input type="text" class="form-control" tabindex="1" title="username" id="member_username" name="member_username" autocorrect="off" autocapitalize="off" placeholder="Username or Email" />
								<i class="fa fa-user"></i> </span>
						</div>
						<div class="form-group form-actions">
							<span class="input-icon">
								<input type="password" class="form-control password" tabindex="2" title="password" value="" id="pwd" placeholder = 'Password' name="member_password" autocorrect="off" autocapitalize="off">
								<i class="fa fa-lock"></i>
								<a class="forgot" href="<?php echo site_url('user/forgot');?>">
									I forgot my password
								</a> </span>
						</div>
						<div class="form-actions">
							<label for="remember" class="checkbox-inline">
								<input type="checkbox" class="grey remember" id="remember_login" name="remember_login" tabindex="3" value="ok" />
								Keep me signed in
							</label>
							<button type="submit" class="btn btn-bricky pull-right" name = 'btnLogin' id='signin_submit' value = 'Take Me To Userboard'>
								Login <i class="fa fa-arrow-circle-right"></i>
							</button>
							 
						</div>
						<div class="new-account">
							Don't have an account yet?
							<a href="<?php echo site_url('user/register');?>" class="register">
								Create an account
							</a>
						</div>
					</fieldset>
				</form>
			</div>
			<!-- end: LOGIN BOX -->
			
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