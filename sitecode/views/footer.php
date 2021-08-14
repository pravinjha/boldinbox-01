
			<!-- body - campaign listing ends -->		
		</div>
		<!-- body - right side ends -->
	</div>
	<!-- body - private access ends -->
	
	<!-- footer - private access starts -->
	<div class = 'footer-private'>
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
		<div class = 'footer-logo'><img src = '<?php echo $this->config->item('locker');?>images/logo.png' /></div>	
	</div>
	<div class = 'footer-copyright'>
		&copy; BoldInbox.Com <?php echo date('Y'); ?>, All Rights Reserved.
	</div>
	<!-- footer - private access ends -->
</div>
</body>

</html>