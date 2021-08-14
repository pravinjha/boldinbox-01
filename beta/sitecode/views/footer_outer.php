<!-- start: FOOTER -->
		<footer id="footer">
			<div class="container">
				<div class="row">
					<div class="col-md-3">
						<div class="newsletter">
							<h4>Register</h4>
							<p>
								Create a Free Account, No Hidden Cost, No Credit Card Required!
							</p>
							
							<form method="POST" action="<?php echo site_url('user/register');?>" id="newsletterForm">
								<div class="input-group">
									<input type="text" id="emailOuter" name="emailOuter" placeholder="Email Address.." title="Valid Email Id" class="form-control">
									<span class="input-group-btn">
										<button type="submit" class="btn btn-default">
											Go!
										</button> </span>
								</div>
							</form>
						</div>
					</div>
					<div class="col-md-3">
						<!-- Blank -->
					</div>
					<div class="col-md-4">
						<div class="contact-details">
							<h4>Contact Us</h4>
							<ul class="contact">
								
								<li>
									<p>
										<i class="fa fa-phone"></i><strong>Phone:</strong>+91 8130 972 229
									</p>
								</li>
								<li>
									<p>
										<i class="fa fa-envelope"></i><strong>Email:</strong>
										<a href="mailto:support@boldinbox.com">
											support@boldinbox.com
										</a>
									</p>
								</li>
								<li>
									<p>
										<i class="fa fa-microphone"></i><strong>Skype:</strong> <a href="skype:sumitthakkar?chat"><img src="https://secure.skypeassets.com/i/scom/images/skype-buttons/chatbutton_16px.png" alt="Skype chat, instant message" role="Button" style="border:0;"></a>
									</p>
								</li>
							</ul>
						</div>
					</div>
					<div class="col-md-2">
						<h4>Follow Us</h4>
						<div class="social-icons">
							<ul>
								<li class="social-twitter tooltips" data-original-title="Twitter" data-placement="bottom">
									<a target="_blank" href="http://www.twitter.com/">
										Twitter
									</a>
								</li>
								<li class="social-facebook tooltips" data-original-title="Facebook" data-placement="bottom">
									<a target="_blank" href="http://facebook.com/" data-original-title="Facebook">
										Facebook
									</a>
								</li>
								<li class="social-linkedin tooltips" data-original-title="LinkedIn" data-placement="bottom">
									<a target="_blank" href="http://linkedin.com/" data-original-title="LinkedIn">
										LinkedIn
									</a>
								</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
			<div class="footer-copyright">
				<div class="container">
					<div class="row">
						<div class="col-md-2">
							<img src="https://www.boldinbox.dev/locker/images/logo-blue.png" style="height: 38px; width: auto;">
						</div>
						<div class="col-md-4">
							<p>
								&copy; Copyright <?php echo date('Y');?> by BoldInbox. All Rights Reserved.
							</p>
						</div>
						<div class="col-md-10">
							<nav id="sub-menu">
								<ul>
									<li><a href = '<?php echo base_url();?>'>Home</a></li>
									<li><a href = '<?php echo site_url('home/about');?>'>About Us</a></li>
									<li><a href = '<?php echo site_url('home/contact');?>'>Contacts Us</a></li>
									<li><a href = '<?php echo site_url('home/pricing');?>'>Pricing</a></li>
									<li><a href = '<?php echo base_url();?>#features'>Features</a></li>
									<li><a href = 'javascript:void(0);'>Support</a></li>
									<li><a href = '<?php echo site_url('home/terms#anti-spam-policy');?>'>Anti-Spam Policy</a></li>									
									<li><a href = '<?php echo site_url('home/terms');?>'>Policies & Terms</a></li>									
								</ul>
							</nav>
						</div>
					</div>
				</div>
			</div>
		</footer>
		<a id="scroll-top" href="#"><i class="fa fa-angle-up"></i></a>
		<!-- end: FOOTER -->
		<!-- start: MAIN JAVASCRIPTS -->
		<!--[if lt IE 9]>
		<script src="<?php echo base_url();?>assets/plugins/respond.min.js"></script>
		<script src="<?php echo base_url();?>assets/plugins/excanvas.min.js"></script>
		<script src="<?php echo base_url();?>assets/plugins/html5shiv.js"></script>
		<script type="text/javascript" src="<?php echo base_url();?>assets/plugins/jQuery-lib/1.10.2/jquery.min.js"></script>
		<![endif]-->
		<!--[if gte IE 9]><!-->
		<script src="<?php echo base_url();?>assets/plugins/jQuery-lib/2.0.3/jquery.min.js"></script>
		<!--<![endif]-->
		<script src="<?php echo base_url();?>assets/plugins/bootstrap/js/bootstrap.min.js"></script>
		<script src="<?php echo base_url();?>assets/plugins/jquery.transit/jquery.transit.js"></script>
		<script src="<?php echo base_url();?>assets/plugins/hover-dropdown/twitter-bootstrap-hover-dropdown.min.js"></script>
		<script src="<?php echo base_url();?>assets/plugins/jquery.appear/jquery.appear.js"></script>
		<script src="<?php echo base_url();?>assets/plugins/blockUI/jquery.blockUI.js"></script>
		<script src="<?php echo base_url();?>assets/plugins/jquery-cookie/jquery.cookie.js"></script>
		<script src="<?php echo base_url();?>assets/js/main.js"></script>
		<!-- end: MAIN JAVASCRIPTS -->
		<!-- start: JAVASCRIPTS REQUIRED FOR THIS PAGE ONLY -->
		<script src="<?php echo base_url();?>assets/plugins/revolution_slider/rs-plugin/js/jquery.themepunch.plugins.min.js"></script>
		<script src="<?php echo base_url();?>assets/plugins/revolution_slider/rs-plugin/js/jquery.themepunch.revolution.min.js"></script>
		<script src="<?php echo base_url();?>assets/plugins/flex-slider/jquery.flexslider.js"></script>
		<script src="<?php echo base_url();?>assets/plugins/stellar.js/jquery.stellar.min.js"></script>
		<script src="<?php echo base_url();?>assets/plugins/colorbox/jquery.colorbox-min.js"></script>
		<script src="<?php echo base_url();?>assets/js/index.js"></script>
		<!-- end: JAVASCRIPTS REQUIRED FOR THIS PAGE ONLY -->
		<script>
			jQuery(document).ready(function() {
				Main.init();
				Index.init();
				$.stellar();
			});
		</script><script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-88358076-1', 'auto');
  ga('send', 'pageview');

</script>
	</body>
</html>