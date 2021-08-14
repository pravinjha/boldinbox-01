<!-- Footer - Public Acess Starts -->
<div class = 'footer-signup'>
<?php echo form_open('user/register/'); ?>
	<div class = 'footer-signup-left'><input type = 'text' placeholder = 'Email Address...'  name="emailOuter" id="emailOuter" title="Valid Email Id" /></div>
	<div class = 'footer-signup-right'>
		<input type="image" name="btnRegisterOuter" value="start here" src = '<?php echo base_url();?>locker/images/icons/get-started-free.jpg' />	
	</div>	
    </form>
</div>
<div class = 'footer-public'>
	<div class = 'footer-menu'>
		<a href = '<?php echo base_url();?>'>Home</a> | 
		<a href = '<?php echo site_url('home/about');?>'>About Us</a> | 
		<a href = '<?php echo site_url('home/contact');?>'>Contacts Us</a> |
		<a href = '<?php echo site_url('home/pricing');?>'>Pricing</a> | 	
		<a href = '<?php echo base_url();?>#features'>Features</a> | 		
		<a href = 'javascript:void(0);'>Support</a>	|
		<a href = '<?php echo site_url('home/terms#anti-spam-policy');?>'>Anti-Spam Policy</a>	|
		<a href = '<?php echo site_url('home/terms');?>'>Policies & Terms</a>
	</div>
	<div class = 'footer-logo'><img src = '<?php echo base_url();?>locker/images/logo.png' /></div>	
</div>
<div class = 'footer-copyright'>
	&copy; BoldInbox.Com <?php echo date('Y');?>, All Rights Reserved.
</div>
<!-- Footer - Public Acess Ends -->
</div>

<!-- WhatsHelp.io widget -->
<script type="text/javascript">
    (function () {
        var options = {
            whatsapp: " +91 8130 972 229", // WhatsApp number
            call_to_action: "Message us", // Call to action
            position: "left", // Position may be 'right' or 'left'
        };
        var proto = document.location.protocol, host = "whatshelp.io", url = proto + "//static." + host;
        var s = document.createElement('script'); s.type = 'text/javascript'; s.async = true; s.src = url + '/widget-send-button/js/init.js';
        s.onload = function () { WhWidgetSendButton.init(host, proto, options); };
        var x = document.getElementsByTagName('script')[0]; x.parentNode.insertBefore(s, x);
    })();
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
</body>
</html>