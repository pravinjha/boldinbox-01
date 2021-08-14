<link rel="stylesheet" href="<?php echo $this->config->item('locker');?>css/nivo-slider.css" type="text/css" media="screen" />
<!-- Page Body - Public Access Starts -->
<div class = 'body-public'>
<div class = "body-punchline-big">Simplest Ever Email Marketing Tool</div>
<div class = 'body-punchline-small-line'></div><div class = "body-punchline-small">We really mean it !</div><div class = 'body-punchline-small-line'></div>

<div class = 'footer-signup'>
<?php echo form_open('user/register/'); ?>
	<div class = 'footer-signup-left'><input type = 'text' placeholder = 'Email Address...'  name="emailOuter" id="emailOuter" title="Valid Email Id" /></div>
	<div class = 'footer-signup-right'>
		<input type="image" name="btnRegisterOuter" value="start here" src = '<?php echo base_url();?>locker/images/icons/get-started-free.jpg' />	
	</div>	
    </form>
</div>


<div class = 'body-videos'><a name = 'features'></a>
<div class = 'body-vodeos-sec'>
<div class = 'body-videos-sec1 fl'><img src = 'locker/images/header-images/b1.jpg' width = '400'/></div>
<div class = 'body-videos-sec2 fr'>
	<u>GET YOUR CAMPAIGN SENDING IN MINUTES.</u>
	<br />
	<div class = 'body-videos-sec21'>
	The simplest ever email marketing tool allows you to choose from a number of pre-built layouts. BoldInbox has done half the job for you, so you do not have to think much about your email template design. You can just select the one you like for your next email promotion and that's it - "Get your campaign sending in minutes". 
	</div>
</div>

<div class = 'clear10'></div><div class = 'clear10'></div><div class = 'clear10'></div><div class = 'clear10'></div>

<div class = 'body-videos-sec1 fr'><img src = 'locker/images/header-images/b2.jpg' width = '400'/></div>
<div class = 'body-videos-sec2 fl'>
	<u>EASY TO USE DRAG-N-DROP EDITOR</u>
	<br />
	<div class = 'body-videos-sec21'>
	The very easy to use Drag-N-Drop editor helps you design your fresh email promotion as per your liking with no technical skills at all. You can always preview your email promotion in real time without actually sending it. You can edit your campaign to make any changes or that's it - "Your campiagn is just one click away to send out".
	</div>
</div>

<div class = 'clear10'></div><div class = 'clear10'></div><div class = 'clear10'></div><div class = 'clear10'></div>

<div class = 'body-videos-sec1 fl'><img src = 'locker/images/header-images/b3.jpg' width = '400'/></div>
<div class = 'body-videos-sec2 fr'>
	<u>GENUINE CAMPAIGN STATS MANAGEMENT</u>
	<br />
	<div class = 'body-videos-sec21'>
	BoldInbox never cheats or goes wrong in generating all the very genuine and oragnic campaign statistics for you. The stats for every campaign you send out will give you the  real time figures for Opens, Un-opens, Clicks, Bounces, Unsubscribes, etc to help you oragnize the email list balance and to maintain the healthy engagement with your subscribers.
	</div>
</div>

<div class = 'clear10'></div><div class = 'clear10'></div><div class = 'clear10'></div><div class = 'clear10'></div>

<div class = 'body-videos-sec1 fr'><img src = 'locker/images/header-images/b4.jpg' width = '400'/></div>
<div class = 'body-videos-sec2 fl'>
	<u>SUPER EASY LIST MANAGEMENT TOOL</u>
	<br />
	<div class = 'body-videos-sec21'>
	The super cool and easiest of all list management tool will help you organize your email contacts without any special skill set. You can quickly create multiple lists to manage the list balance and you can anytime add or delete your contacts as well as copy or move contacts between different list. 
	</div>
</div>

</div>


<!-- div id="non_slider">			
	<img src = '<?php //echo base_url();?>locker/images/header-images/b1.jpg' />	
</div -->
<!-- div id="slider" class="nivosSlider" style = 'display:none;'>
	<img src = '<?php //echo base_url();?>locker/images/header-images/banner-2.png' />	
	<img src = '<?php //echo base_url();?>locker/images/header-images/banner-3.jpg' />	
	<img src = '<?php //echo base_url();?>locker/images/header-images/banner-4.jpg' />	
	<img src = '<?php //echo base_url();?>locker/images/header-images/banner-1.png' />	
</div -->
</div>
</div>
<!-- Page Body - Public Access Ends -->
<script language = 'javascript' type="text/javascript" src = '<?php echo $this->config->item('locker');?>jquery/jquery.nivo.slider.pack.js'></script>
<script type="text/javascript">
    $(window).load(function() {
       $('#slider').nivoSlider({effect:'boxRandom',	pauseTime:5000, captionOpacity:0.8 });		  
    });
	function show(){		
		$('#non_slider').hide();	
		$('#slider').fadeIn('slow');
	}
	//setTimeout('show()', 3000);
</script>