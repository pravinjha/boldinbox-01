<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
	<title>BoldInbox - Simple Email System</title>
	<meta name="description" content="Clean - Simple Ever Email Marketing Tool | We Really Mean It.">
	<meta name="author" content="BoldInbox">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="Content-Language" content="en-US" />
	<meta name="msvalidate.01" content="F20291881CA4263B43E45923C943F3C0" />
	<meta name="Robots" content="index, follow" />
	<meta name="GoogleBot" content="index, follow" />
	<meta name="Publisher" content="BoldInbox" />    
	<meta name="Copyright" content="BoldInbox" />
    <link rel="icon" href="<?php echo $this->config->item('locker').'themes/march2020/';?>images/icon.png" type="image/x-icon">
	<!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Poppins:100,200,300,400,500,600,700,800,900&amp;display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo $this->config->item('locker').'themes/march2020/';?>fonts/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="<?php echo $this->config->item('locker').'themes/march2020/';?>styles/bootstrap.css" />
    <link rel="stylesheet" href="<?php echo $this->config->item('locker').'themes/march2020/';?>styles/main.css" />  
    <link rel="stylesheet" href="<?php echo $this->config->item('locker').'themes/march2020/';?>styles/<?php echo $this->router->fetch_method();?>.css?s" />    
  </head>

  <body>
    
    <!-- Preloader -->
    <div id="js-preloader" class="js-preloader">
      <div class="content">
        <img src="<?php echo $this->config->item('locker').'themes/march2020/';?>images/logo-icon.png" alt="">
      </div>
      <div class="preloader-inner">
      </div>
    </div>

    <!-- Mobile Menu -->
    <div class="mobile-nav-wrapper">
      <div class="mobile-menu-inner">
        <ul class="mobile-menu">
          	<li><a href="<?php echo site_url("home")?>">Home</a></li>          
          	<li><a href="<?php echo site_url("home/about")?>">About us</a></li>
			<li><a href="<?php echo site_url("home/features")?>">Features</a></li>
			<li><a href="<?php echo site_url("home/pricing")?>">Pricing</a></li>
			<li><a href="<?php echo site_url("home/support")?>">Support</a></li>
			<li class = 'last_link'><a href="<?php echo site_url("home/contact")?>">Contact us</a></li>
			
			
			<li class = 'button_login'><a href="<?php echo site_url("user/login")?>">Login Now</a></li>          
          	<li class = 'button_signup'><a href="<?php echo site_url("user/register")?>">Sign Up Now</a></li>
        </ul>		
      </div>
    </div>
    <div class="mobile-menu-overlay"></div>

    <!-- Header -->
    <header class="site-header fixed-header">
      <div class="container expanded">
        <div class="header-wrap">
          <div class="fixed-header-logo">
            <a href="<?php echo site_url("home")?>"><img src="<?php echo $this->config->item('locker').'themes/march2020/';?>images/logo-white.png?121" alt="Boldinbox.com - Logo"></a>
          </div>
          <div class="is-fixed-header-logo">
            <a href="<?php echo site_url("home")?>"><img src="<?php echo $this->config->item('locker').'themes/march2020/';?>images/logo.png?43" alt="Boldinbox.com - Logo"></a>
          </div>
          <div class="header-nav">
              <ul class="main-menu">
                <li><a href="<?php echo site_url("home")?>">Home</a></li>
                <li><a href="<?php echo site_url("home/about")?>">About us</a></li>
                <li><a href="<?php echo site_url("home/features")?>">Features</a></li>
               	<li><a href="<?php echo site_url("home/pricing")?>">Pricing</a></li>
                <li><a href="<?php echo site_url("home/support")?>">Support</a></li>
                <li><a href="<?php echo site_url("home/contact")?>">Contact us</a></li>
              </ul>    
          </div>
          <div class="header-widgets">
            <ul class="right-menu">
              <!-- li class="menu-item menu-search">
                <a href="<?php //echo site_url("user/login")?>" id="menu-search-btn">
                	<i class="fa fa-sign-in"></i>
                </a>                 
              </li -->
			 <li class="menu-item free-quote">
                <div class="main-pink-button">
                  <a href="<?php echo site_url("user/login")?>">Login</a>
                </div>
              </li>	
              <li class="menu-item free-quote">
                <div class="main-pink-button">
                  <a href="<?php echo site_url("user/register")?>">Get Started Free</a>
                </div>
              </li>
              <li class="menu-item menu-mobile-nav">
                <a href="#" class="menu-bar" id="menu-show-mobile-nav">
                  <span class="hamburger"></span>
                </a>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </header>
    <!-- Header -->
    
    

    <div class="main-content">
      
	  
	  
	  <?PHP
		if($this->router->fetch_class() == 'home' && $this->router->fetch_method() == 'index'){
		?>
			<!-- Main Banner -->
      <div class="parallax-banner">
        <!--Content before waves-->
        <div class="inner-header">
          <div class="inner-content">
            <h4>what are you waiting for?</h4>
            <h1>Social Network Marketing<br>&amp; SEO Agency</h1>
            <form action="#">
              <input type="text" placeholder="http://yoursite.com" required="">
              <button>Analyze!</button>
            </form>
            <div class="main-decoration">
              <img src="<?php echo $this->config->item('locker').'themes/march2020/';?>images/baner-main-decoration.png" alt="">
            </div>
          </div>
        </div>

        <!--Waves Container-->
        <div>
          <svg class="waves" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
          viewBox="0 24 150 28" preserveAspectRatio="none" shape-rendering="auto">
          <defs>
          <path id="gentle-wave" d="M-160 44c30 0 58-18 88-18s 58 18 88 18 58-18 88-18 58 18 88 18 v44h-352z" />
          </defs>
          <g class="parallax">
          <use xlink:href="#gentle-wave" x="48" y="0" fill="rgba(255,255,255,0.7" />
          <use xlink:href="#gentle-wave" x="48" y="3" fill="rgba(255,255,255,0.5)" />
          <use xlink:href="#gentle-wave" x="48" y="5" fill="rgba(255,255,255,0.3)" />
          <use xlink:href="#gentle-wave" x="48" y="7" fill="#fff" />
          </g>
          </svg>
        </div>
        <!--Waves end-->
      </div>
		<?PHP		
		}else{
		?>
			<!-- Page Heading -->
		  <div class="purple-page-heading">
			<!-- div class="container">
			  <div class="row">
				<div class="col-lg-12">
				  <h1>Say Hello To Us!</h1>
				  <span><a href="javascript:void(0);"><?php echo $this->router->fetch_class();?>/<?php echo ucwords($this->router->fetch_method());?></a></span>
				</div>
			  </div>
			</div -->
		  </div>
		<?PHP		
		}
	  ?>
	  