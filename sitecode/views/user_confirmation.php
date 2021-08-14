
  <?php if($this->session->userdata('member_status')=='inactive'){?>
    <script type="text/javascript">

        $(".resend_confirmation").live('click',function(){
		$(".resend_confirmation").text('Processing...');		
          $.ajax({
            url: "<?php echo base_url() ?>user/user_confirmation_notification/<?php echo $this->session->userdata('member_id'); ?>/confirmation_msg",
            type:"POST",
            success: function(data) {
              if(data=="success"){
				$(".resend_confirmation").text('Please check your email.');				 
              }
            }
          });
        });		
    </script>
  <?php } ?>
<div class = 'body-public'>
	<div class="body-box-login">
		<div class = 'body-form-text'>Please Confirm & Activate Your FREE BoldInbox.Com Account</div>
		<div class = 'clear10'></div>		
		<div class = 'body-form-desc'>Your BoldInbox.Com account has been successfully created, we have sent you an email with the activation link, all you need to do is follow the link in your email to activate your account now.</div>		
		<div class = 'body-form-desc' style = 'text-align:center;'>Activation Email Not Received? <a href="javascript:void(0);" class="resend_confirmation"><b>Please Send Again</b></a></div>
		<div class = 'body-form-desc' style = 'text-align:center;'>Still Having Trouble? <a href = 'mailto:support@boldinbox.com'><b>Write to Support</b></a></div>
		<div class = 'clear10'></div>
		<div class = 'clear10'></div>
		<div class = 'clear10'></div>
		<div class = 'clear10'></div>
		<div class = 'body-form-desc' style = 'text-align:center;'><a href="<?php echo base_url();?>user/register_different_user"><b>Register As a Different User</b></a></div>
	</div>
</div>
