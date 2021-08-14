<!-- start: COPYRIGHT -->
			<div class="copyright">
				 &copy; <?php echo SYSTEM_DOMAIN_NAME.' '.date('Y');?>, All Rights Reserved.
			</div>
			<!-- end: COPYRIGHT -->
		</div>
		<!-- start: MAIN JAVASCRIPTS -->
		<!--[if lt IE 9]>
		<script src="<?php echo base_url();?>innerassets/plugins/respond.min.js"></script>
		<script src="<?php echo base_url();?>innerassets/plugins/excanvas.min.js"></script>
		<script type="text/javascript" src="<?php echo base_url();?>innerassets/plugins/jQuery-lib/1.10.2/jquery.min.js"></script>
		<![endif]-->
		<!--[if gte IE 9]><!-->
		<script src="<?php echo base_url();?>innerassets/plugins/jQuery-lib/2.0.3/jquery.min.js"></script>
		<!--<![endif]-->
		<script src="<?php echo base_url();?>innerassets/plugins/jquery-ui/jquery-ui-1.10.2.custom.min.js"></script>
		<script src="<?php echo base_url();?>innerassets/plugins/bootstrap/js/bootstrap.min.js"></script>
		<script src="<?php echo base_url();?>innerassets/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js"></script>
		<script src="<?php echo base_url();?>innerassets/plugins/blockUI/jquery.blockUI.js"></script>
		<script src="<?php echo base_url();?>innerassets/plugins/iCheck/jquery.icheck.min.js"></script>
		<script src="<?php echo base_url();?>innerassets/plugins/perfect-scrollbar/src/jquery.mousewheel.js"></script>
		<script src="<?php echo base_url();?>innerassets/plugins/perfect-scrollbar/src/perfect-scrollbar.js"></script>
		<script src="<?php echo base_url();?>innerassets/plugins/less/less-1.5.0.min.js"></script>
		<script src="<?php echo base_url();?>innerassets/plugins/jquery-cookie/jquery.cookie.js"></script>
		<script src="<?php echo base_url();?>innerassets/plugins/bootstrap-colorpalette/js/bootstrap-colorpalette.js"></script>
		<script src="<?php echo base_url();?>innerassets/js/main.js"></script>
		<!-- end: MAIN JAVASCRIPTS -->
		<!-- start: JAVASCRIPTS REQUIRED FOR THIS PAGE ONLY -->
		<script src="<?php echo base_url();?>innerassets/plugins/jquery-validation/dist/jquery.validate.min.js"></script>
		<script src="<?php echo base_url();?>innerassets/js/login.js"></script>
		<!-- end: JAVASCRIPTS REQUIRED FOR THIS PAGE ONLY -->
		<script>
			jQuery(document).ready(function() {
				Main.init();
				Login.init();
			});
		</script>
	</body>
	<!-- end: BODY -->
</html>