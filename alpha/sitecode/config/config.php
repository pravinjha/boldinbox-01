<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

 
define('DS',DIRECTORY_SEPARATOR);		
/*
|--------------------------------------------------------------------------
| Base Site URL
|--------------------------------------------------------------------------
|
| URL to your CodeIgniter root. Typically this will be your base URL,
| WITH a trailing slash:
|
|	http://example.com/
|
| If this is not set then CodeIgniter will guess the protocol, domain and
| path to your installation.
|
*/
$config['version'] = '1.0'; 
$config['domain_name'] = "www.boldinbox.com/alpha/"; 
if($config['domain_name'] == "www.boldinbox.com/alpha/"){
$config['php_path'] =  "/usr/bin/php"; // "/usr/local/bin/php"; 
$config['root_path'] = "/srv/users/serverpilot/apps/boldinbox-com/";
$config['site_folder'] = "public/"; 
$config['site_assets'] = "locker/"; 
$config['rcdata_folder'] = "capacitor/";
}else{
$config['php_path'] =  "/Applications/XAMPP/xamppfiles/php/php"; 
$config['root_path'] =  "/Applications/XAMPP/xamppfiles/htdocs/"; 
$config['site_folder'] = "boldinbox.com/"; 
$config['site_assets'] = "locker/"; 
$config['rcdata_folder'] = "capacitor/";
}



$config['base_url'] = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") ? "https" : "http");
$config['base_url'] .= "://".$config['domain_name'];

$config['ssl_base_url'] = "https://".$config['domain_name'];



$config['site_root_path'] = $config['root_path'].$config['site_folder'];

/*
static files for the website
*/
$config['locker'] = $config['base_url'].$config['site_assets'];
$config['locker_path'] = $config['site_root_path'].$config['site_assets'];
/*
dynamic file paths for upload
*/
//$config['rcdata'] 		= $config['root_path'].$config['rcdata_folder'];
$config['rcdata'] 		= $config['site_root_path'].$config['rcdata_folder'];
//$config['rcdata'] 		= $config['rcdata_folder'];
$config['user_public'] 	= $config['rcdata']."user/public/";
$config['user_private'] = $config['rcdata']."user/private/";
/*
dynamic file paths for pmta
*/
$config['pmta_logs'] 	= $config['rcdata']."pmta/logs/";
$config['pmta_archives'] = $config['rcdata']."pmta/archives/";
/*
Blog Images
*/
$config['blog_files'] = $config['rcdata']."blog/";
$config['campaign_files'] = $config['rcdata']."campaign/";
$config['payment_files'] = $config['rcdata']."payment/";

$config['theme_folder'] = "themes/"; // '','themes/'

/* Start Paypal Details*/
$config['DV_API_KEY'] = 'fffae22f007a99c5d003b2ccccbf31c0';
$config['DV_UPLOAD_PATH'] = $config['rcdata'].'dv_validation/';
$config['DV_CSV_COUNT'] = 10;
/* END DV Details*/

/*START Paypal Details*/
$config['PAYPAL_TESTMODE'] = False;

$config['PAYPAL_URL'] = ($config['PAYPAL_TESTMODE'])? 'https://api-3t.sandbox.paypal.com/nvp' : 'https://api-3t.paypal.com/nvp';
$config['PAYPAL_SUBMIT_URL'] = ($config['PAYPAL_TESTMODE'])? 'https://www.sandbox.paypal.com/cgi-bin/webscr' : 'https://www.paypal.com/cgi-bin/webscr';
$config['VERIFY_URI'] =  ($config['PAYPAL_TESTMODE'])? 'https://ipnpb.sandbox.paypal.com/cgi-bin/webscr' : 'https://ipnpb.paypal.com/cgi-bin/webscr'; // For IPN
/*
$config['PAYPAL_EMAIL'] = ($config['PAYPAL_TESTMODE'])?  'pravinjha-shop@gmail.com' : 'sumit@multiplat.in';
$config['PAYPAL_PASSWORD'] = ($config['PAYPAL_TESTMODE'])? '888K2TJ94VMWENSH' : 'H9V4XN3BUTMRWC9L';
$config['PAYPAL_SIGNATURE'] = ($config['PAYPAL_TESTMODE'])? 'An5ns1Kso7MWUdW4ErQKJJJ4qi4-AYpCuZzCWCvcDLo06Jbit2iUOOym' : 'Afe7b2rDpdLqzCjlnhQjM3OXyfr9AdrhVRfJexLARFf8j.xhStdpNZkU';
$config['PAYPAL_USERNAME'] = ($config['PAYPAL_TESTMODE'])? 'pravinjha-shop_api1.gmail.com' : 'sumit.boldinbox.com';
*/
/*
$config['PAYPAL_EMAIL'] = ($config['PAYPAL_TESTMODE'])?  'pravinjha-shop@gmail.com' : 'redsoftsolutions@yahoo.in';
$config['PAYPAL_PASSWORD'] = ($config['PAYPAL_TESTMODE'])? '888K2TJ94VMWENSH' : '9T833U32VZSPU2F8';
$config['PAYPAL_SIGNATURE'] = ($config['PAYPAL_TESTMODE'])? 'An5ns1Kso7MWUdW4ErQKJJJ4qi4-AYpCuZzCWCvcDLo06Jbit2iUOOym' : 'AiPC9BjkCyDFQXbSkoZcgqH3hpacAnp2lFHq1hhmcyWLzemhElUUB5a-';
$config['PAYPAL_USERNAME'] = ($config['PAYPAL_TESTMODE'])? 'pravinjha-shop_api1.gmail.com' : 'redsoftsolutions_api1.yahoo.in';
*/

$config['PAYPAL_EMAIL'] = ($config['PAYPAL_TESTMODE'])?  'pravinjha-shop@gmail.com' : 'boldinbox@gmail.com';
$config['PAYPAL_PASSWORD'] = ($config['PAYPAL_TESTMODE'])? '888K2TJ94VMWENSH' : '9BGPKVZJFHVD3RNK';
$config['PAYPAL_SIGNATURE'] = ($config['PAYPAL_TESTMODE'])? 'An5ns1Kso7MWUdW4ErQKJJJ4qi4-AYpCuZzCWCvcDLo06Jbit2iUOOym' : 'Azm5iRwPAcQUbPmB3Jwr9rC9rmHJAtHSmgMKGooQIAQ0vOt63a5FkT0K';
$config['PAYPAL_USERNAME'] = ($config['PAYPAL_TESTMODE'])? 'pravinjha-shop_api1.gmail.com' : 'boldinbox_api1.gmail.com';


$config['PAYPAL_SUCCESS_URL'] =  $config['base_url'].'change_plan/successpaypal/';
$config['PAYPAL_CANCEL_URL'] = $config['base_url'].'change_plan/cancelpaypal';
$config['PAYPAL_NOTIFY_URL'] =  $config['base_url'].'change_plan/verifyIPN';

/*END Paypal Details*/


$config['major_domains'] = array('gmail.com', 'yahoo.com', 'hotmail.com', 'aol.com', 'msn.com', 'outlook.com', 'windowslive.com', 'live.com', 'mail.ru', 'me.com', 'mac.com', 'comcast.net', 'cox.net', 'rediffmail.com');
/*
|--------------------------------------------------------------------------
| Index File
|--------------------------------------------------------------------------
|
| Typically this will be your index.php file, unless you've renamed it to
| something else. If you are using mod_rewrite to remove the page set this
| variable so that it is blank.
|
*/
$config['index_page'] = '';

/*
|--------------------------------------------------------------------------
| URI PROTOCOL
|--------------------------------------------------------------------------
|
| This item determines which server global should be used to retrieve the
| URI string.  The default setting of 'AUTO' works for most servers.
| If your links do not seem to work, try one of the other delicious flavors:
|
| 'AUTO'			Default - auto detects
| 'PATH_INFO'		Uses the PATH_INFO
| 'QUERY_STRING'	Uses the QUERY_STRING
| 'REQUEST_URI'		Uses the REQUEST_URI
| 'ORIG_PATH_INFO'	Uses the ORIG_PATH_INFO
|
*/
$config['uri_protocol']	= 'AUTO';

/*
|--------------------------------------------------------------------------
| URL suffix
|--------------------------------------------------------------------------
|
| This option allows you to add a suffix to all URLs generated by CodeIgniter.
| For more information please see the user guide:
|
| http://codeigniter.com/user_guide/general/urls.html
*/

$config['url_suffix'] = '';

/*
|--------------------------------------------------------------------------
| Default Language
|--------------------------------------------------------------------------
|
| This determines which set of language files should be used. Make sure
| there is an available translation if you intend to use something other
| than english.
|
*/
$config['language']	= 'english';

/*
|--------------------------------------------------------------------------
| Default Character Set
|--------------------------------------------------------------------------
|
| This determines which character set is used by default in various methods
| that require a character set to be provided.
|
*/
$config['charset'] = 'UTF-8';

/*
|--------------------------------------------------------------------------
| Enable/Disable System Hooks
|--------------------------------------------------------------------------
|
| If you would like to use the 'hooks' feature you must enable it by
| setting this variable to TRUE (boolean).  See the user guide for details.
|
*/
$config['enable_hooks'] = FALSE;


/*
|--------------------------------------------------------------------------
| Class Extension Prefix
|--------------------------------------------------------------------------
|
| This item allows you to set the filename/classname prefix when extending
| native libraries.  For more information please see the user guide:
|
| http://codeigniter.com/user_guide/general/core_classes.html
| http://codeigniter.com/user_guide/general/creating_libraries.html
|
*/
$config['subclass_prefix'] = 'MY_';


/*
|--------------------------------------------------------------------------
| Allowed URL Characters
|--------------------------------------------------------------------------
|
| This lets you specify with a regular expression which characters are permitted
| within your URLs.  When someone tries to submit a URL with disallowed
| characters they will get a warning message.
|
| As a security measure you are STRONGLY encouraged to restrict URLs to
| as few characters as possible.  By default only these are allowed: a-z 0-9~%.:_-
|
| Leave blank to allow all characters -- but only if you are insane.
|
| DO NOT CHANGE THIS UNLESS YOU FULLY UNDERSTAND THE REPERCUSSIONS!!
|
*/
$config['permitted_uri_chars'] = 'a-z 0-9~%.:_\-@#';


/*
|--------------------------------------------------------------------------
| Enable Query Strings
|--------------------------------------------------------------------------
|
| By default CodeIgniter uses search-engine friendly segment based URLs:
| example.com/who/what/where/
|
| By default CodeIgniter enables access to the $_GET array.  If for some
| reason you would like to disable it, set 'allow_get_array' to FALSE.
|
| You can optionally enable standard query string based URLs:
| example.com?who=me&what=something&where=here
|
| Options are: TRUE or FALSE (boolean)
|
| The other items let you set the query string 'words' that will
| invoke your controllers and its functions:
| example.com/index.php?c=controller&m=function
|
| Please note that some of the helpers won't work as expected when
| this feature is enabled, since CodeIgniter is designed primarily to
| use segment based URLs.
|
*/
$config['allow_get_array']		= TRUE;
$config['enable_query_strings'] = FALSE;
$config['controller_trigger']	= 'c';
$config['function_trigger']		= 'm';
$config['directory_trigger']	= 'd'; // experimental not currently in use

/*
|--------------------------------------------------------------------------
| Error Logging Threshold
|--------------------------------------------------------------------------
|
| If you have enabled error logging, you can set an error threshold to
| determine what gets logged. Threshold options are:
| You can enable error logging by setting a threshold over zero. The
| threshold determines what gets logged. Threshold options are:
|
|	0 = Disables logging, Error logging TURNED OFF
|	1 = Error Messages (including PHP errors)
|	2 = Debug Messages
|	3 = Informational Messages
|	4 = All Messages
|
| For a live site you'll usually only enable Errors (1) to be logged otherwise
| your log files will fill up very fast.
|
*/
$config['log_threshold'] = 1;

/*
|--------------------------------------------------------------------------
| Error Logging Directory Path
|--------------------------------------------------------------------------
|
| Leave this BLANK unless you would like to set something other than the default
| application/logs/ folder. Use a full server path with trailing slash.
|
|GoTo: /srv/users/serverpilot/apps/boldinbox/public/codeigniter_2.2.6/system/core/Common.php
|Find the _exception_handler() function (should be at the bottom), and change this line:
|if ($severity == E_STRICT) to this: if ($severity == E_STRICT OR $severity == E_NOTICE)
|
*/
$config['log_path'] = $config['root_path'].'rclogs/';

/*
|--------------------------------------------------------------------------
| Date Format for Logs
|--------------------------------------------------------------------------
|
| Each item that is logged has an associated date. You can use PHP date
| codes to set your own date formatting
|
*/
$config['log_date_format'] = 'Y-m-d H:i:s';

/*
|--------------------------------------------------------------------------
| Cache Directory Path
|--------------------------------------------------------------------------
|
| Leave this BLANK unless you would like to set something other than the default
| system/cache/ folder.  Use a full server path with trailing slash.
|
*/
$config['cache_path'] = '';

/*
|--------------------------------------------------------------------------
| Encryption Key
|--------------------------------------------------------------------------
|
| If you use the Encryption class or the Session class you
| MUST set an encryption key.  See the user guide for info.
|
*/
$config['encryption_key'] = 'pravinjha';

/*
|--------------------------------------------------------------------------
| Session Variables
|--------------------------------------------------------------------------
|
| 'sess_cookie_name'		= the name you want for the cookie
| 'sess_expiration'			= the number of SECONDS you want the session to last.
|   by default sessions last 7200 seconds (two hours).  Set to zero for no expiration.
| 'sess_expire_on_close'	= Whether to cause the session to expire automatically
|   when the browser window is closed
| 'sess_encrypt_cookie'		= Whether to encrypt the cookie
| 'sess_use_database'		= Whether to save the session data to a database
| 'sess_table_name'			= The name of the session database table
| 'sess_match_ip'			= Whether to match the user's IP address when reading the session data
| 'sess_match_useragent'	= Whether to match the User Agent when reading the session data
| 'sess_time_to_update'		= how many seconds between CI refreshing Session Information
|
*/
$config['sess_cookie_name']		= 'cisession';
$config['sess_expiration']		= 86400;
$config['sess_expire_on_close']	= FALSE;
$config['sess_encrypt_cookie']	= FALSE;
$config['sess_use_database']	= FALSE;
$config['sess_table_name']		= 'ci_sessions';
$config['sess_match_ip']		= FALSE;
$config['sess_match_useragent']	= TRUE;
$config['sess_time_to_update']	= 300;

/*
|--------------------------------------------------------------------------
| Cookie Related Variables
|--------------------------------------------------------------------------
|
| 'cookie_prefix' = Set a prefix if you need to avoid collisions
| 'cookie_domain' = Set to .your-domain.com for site-wide cookies
| 'cookie_path'   =  Typically will be a forward slash
| 'cookie_secure' =  Cookies will only be set if a secure HTTPS connection exists.
|
*/
$config['cookie_prefix']	= "";
$config['cookie_domain']	= "";
$config['cookie_path']		= "/";
$config['cookie_secure']	= FALSE;
$config['http_only']		= TRUE;

/*
|--------------------------------------------------------------------------
| Global XSS Filtering
|--------------------------------------------------------------------------
|
| Determines whether the XSS filter is always active when GET, POST or
| COOKIE data is encountered
|
*/
$config['global_xss_filtering'] = FALSE;

/*
|--------------------------------------------------------------------------
| Cross Site Request Forgery
|--------------------------------------------------------------------------
| Enables a CSRF cookie token to be set. When set to TRUE, token will be
| checked on a submitted form. If you are accepting user data, it is strongly
| recommended CSRF protection be enabled.
|
| 'csrf_token_name' = The token name
| 'csrf_cookie_name' = The cookie name
| 'csrf_expire' = The number in seconds the token should expire.
*/
$config['csrf_protection'] = FALSE;
$config['csrf_token_name'] = 'rcgenpi';
$config['csrf_cookie_name'] = 'rchidgulla';
$config['csrf_expire'] = 7200;

/*
|--------------------------------------------------------------------------
| Output Compression
|--------------------------------------------------------------------------
|
| Enables Gzip output compression for faster page loads.  When enabled,
| the output class will test whether your server supports Gzip.
| Even if it does, however, not all browsers support compression
| so enable only if you are reasonably sure your visitors can handle it.
|
| VERY IMPORTANT:  If you are getting a blank page when compression is enabled it
| means you are prematurely outputting something to your browser. It could
| even be a line of whitespace at the end of one of your scripts.  For
| compression to work, nothing can be sent before the output buffer is called
| by the output class.  Do not 'echo' any values with compression enabled.
|
*/
$config['compress_output'] = FALSE;

/*
|--------------------------------------------------------------------------
| Master Time Reference
|--------------------------------------------------------------------------
|
| Options are 'local' or 'gmt'.  This pref tells the system whether to use
| your server's local time as the master 'now' reference, or convert it to
| GMT.  See the 'date helper' page of the user guide for information
| regarding date handling.
|
*/
$config['time_reference'] = 'GMT';


/*
|--------------------------------------------------------------------------
| Rewrite PHP Short Tags
|--------------------------------------------------------------------------
|
| If your PHP installation does not have short tag support enabled CI
| can rewrite the tags on-the-fly, enabling you to utilize that syntax
| in your view files.  Options are TRUE or FALSE (boolean)
|
*/
$config['rewrite_short_tags'] = FALSE;


/*
|--------------------------------------------------------------------------
| Reverse Proxy IPs
|--------------------------------------------------------------------------
|
| If your server is behind a reverse proxy, you must whitelist the proxy IP
| addresses from which CodeIgniter should trust the HTTP_X_FORWARDED_FOR
| header in order to properly identify the visitor's IP address.
| Comma-delimited, e.g. '10.0.1.200,10.0.1.201'
|
*/
$config['proxy_ips'] = '';
/*
Google Map API Key
*/

$config['gmap_key']='ABQIAAAAbcPdaPLhbS66qEHW87GJRRQiLJwTp1-lBW4bOjX0QE5poNmmbRQOr11s1B6sfAUx9eh40Q93S3mvuw';
/*
PeakHost and Liquidweb vmtas and pools array
*/
$config['pool_and_vmta'] = array( 
						array('pmta-pool-1','pmta-vmta1','pmta-vmta2','pmta-vmta3'), 
						array('pmta-pool-2','pmta-vmta4','pmta-vmta5','pmta-vmta6'), 
						array('pmta2-pool-1','pmta2-vmta1','pmta2-vmta2','pmta2-vmta3','pmta2-vmta6','pmta2-vmta7'), 
						array('pmta2-pool-2','pmta2-vmta4','pmta2-vmta5','pmta2-vmta8','pmta2-vmta9','pmta2-vmta10'), 
						array('pmta3-pool-1','pmta3-vmta1','pmta3-vmta2','pmta3-vmta3'),
						array('pmta3-pool-2','pmta3-vmta4','pmta3-vmta5','pmta3-vmta6'), 
						array('mailgun'),
						array('sendgrid'),
						array('amazon')
						);	 
$config['mailgun']=array('mailgun');
//$config['vmta_domain']=array('pmta-pool-1'=>'www.beonlist.com', 'pmta-pool-2'=>'www.beonlist.com', 'pmta2-pool-1'=>'www.beonlist.com', 'pmta2-pool-2'=>'www.beonlist.com', 'mailgun'=>'www.beonlist.com');
$config['vmta_domain']=array('pmta-pool-1'=>'www.boldinbox.com', 'pmta-pool-2'=>'www.boldinbox.com', 'pmta2-pool-1'=>'www.beonlist.com', 'pmta2-pool-2'=>'www.boldinbox.com',  'pmta3-pool-1'=>'www.boldinbox.com', 'pmta3-pool-2'=>'www.boldinbox.com', 'mailgun'=>'www.boldinbox.com', 'sendgrid'=>'www.boldinbox.com');
$config['pool_vmta'] = array('pmta-pool-1'=>array('pmta-vmta1','pmta-vmta2','pmta-vmta3'), 
							'pmta-pool-2'=>array('pmta-vmta4','pmta-vmta5','pmta-vmta6'), 
							'pmta2-pool-1'=>array('pmta2-vmta1','pmta2-vmta2','pmta2-vmta3','pmta2-vmta6','pmta2-vmta7'), 
							'pmta2-pool-2'=>array('pmta2-vmta4','pmta2-vmta5','pmta2-vmta8','pmta2-vmta9','pmta2-vmta10'), 
							'pmta3-pool-1'=>array('pmta3-vmta1','pmta3-vmta2','pmta3-vmta3'), 
							'pmta3-pool-2'=>array('pmta3-vmta4','pmta3-vmta5','pmta3-vmta6'), 
							'mailgun'=>array('mailgun'),
							'sendgrid'=>array('sendgrid'),
							'amazon'=>array('amazon')							
							);

// array(host, user, pwd, port, sender);
$config['SMTP'] = array(array('mail1.bibesp.com', 'smtp1','vR265bibespcom1', '26', 'bounce@bibesp.com','bibesp.com'), 
						array('mail1.mailsoni.com', 'smtp2','vR265bibespcom2', '26', 'bounce@mailsoni.com','mailsoni.com') , 
						array('mail.chillmailer.com', 'smtp1','vR2summit1', '26', 'bounce@chillmailer.com','chillmailer.com') , 
						array('mail.mailposh.com', 'smtp2','vR2summit2', '26', 'bounce@mailposh.com','mailposh.com') , 
						array('mail1.travelpromo.in', 'smtp1','smtp1040418', '26', 'bounce@travelpromo.in','travelpromo.in'),
						array('mail1.travelpromo.in', 'smtp2','smtp2040418', '26', 'bounce@travelpromo.in','travelpromo.in'),						
						array('smtp.mailgun.org','postmaster@m.massmailexpert.com','0fadc1fc90336ea9fc811ad7d3fa1da0', '587', 'bounce@bibesp.com','bibesp.com'),
						array('smtp.sendgrid.net','apikey','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', '2525', 'bounce@boldinbox.net','boldinbox.net'),
						array('email-smtp.us-west-2.amazonaws.com','AKIAJJ553AB6AC4L545Q','AhGW7PP+FitF/EnbPBdwIu9KRYp+DIMdejiHQccXO9SQ', '587', 'boldinbox@gmail.com','boldinbox.net') 
						);
// 						array('smtp.sendgrid.net','YXBpa2V5','U0cudkV2MG9nd3lReGlUbzBlTldTbE8wQS5JSHBWVkQ3eEQwcHNsamx5aERqaHpRaUtyWnNnQzczaDdUOE1oM3BhNjVR', '587', 'bounce@bibesp.com','boldinbox') 
//POOL_DNM is used from DB now
$config['POOL_DNM'] = array('pmta-pool-1'=>'@gmail111.com, @sasktel.net, @li11ve., @m11sn.', 'pmta-pool-2'=>'@gmail33.com,@sasktel.net');						
$config['unsubscribe_feedback']	= array('Not interested', 
							'Never subscribed', 
							'These are offensive and inappropriate', 
							'These are spam',
							'Many emails',
							'Other (Fill in the reason below)'
							)	;									
/*
|--------------------------------------------------------------------------
| Authorize.Net
|--------------------------------------------------------------------------
|
| All of the stuff we need to connect with Authorize.Net
|
|
*/
  

$config['loginname']         	= "34nWQkwdLX8H";
$config['transactionkey']       = "9D757g7v5rwJ8jYf";
$config['host']                 = "apitest.authorize.net"; 
$config['path']                 = "/xml/v1/request.api";
$config['post_url']             = 'https://test.authorize.net/gateway/transact.dll'; 
$config['test_mode'] = true;
/*
|--------------------------------------------------------------------------
| Free trial period
|--------------------------------------------------------------------------
|
| How long (in months) should a new paid account have as a free trial?
|
|
*/
$config['trial_period'] = 1;

/* End of file config.php */
/* Location: ./application/config/config.php */
