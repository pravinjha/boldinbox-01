<?php  
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ',							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE',		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE',	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE',					'ab');
define('FOPEN_READ_WRITE_CREATE',				'a+b');
define('FOPEN_WRITE_CREATE_STRICT',				'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');




define('SITE_ID', '0');
define('SYSTEM_DOMAIN_NAME', 'boldinbox.com');
define('OTHER_ALLOWED_DOMAIN_ARRAY', serialize(array('beonlist.com','www.beonlist.com')));
define('OTHER_DOMAIN_ALLOWED_PAGE_ARRAY', serialize(array('/c/','/a/','/s/', '/false_link_message', '/subscription/subscribe', '/subscription/verify_subscription/', '/subscription/signupform_url/', '/subscription/signup_confirmation/', '/subscription/showpblogo/', '/locker/', '/cprocess/unsubscribe/',  '/cprocess/powered_by_bib/',  '/cprocess/opened/', '/clickrate/create/',  '/clickrate/create_autoresponder/', '/userboard/autoresponder_email/unsubscribe/', '/userboard/autoresponder_email/read/')));
define('CAMPAIGN_DOMAIN', 'http://www.beonlist.com/');
define('SYSTEM_EMAIL_FROM', 'support@boldinbox.com');
define('SYSTEM_NOTICE_EMAIL_TO', 'support@boldinbox.com');
define('DEVELOPER_EMAIL', 'pravinjha@gmail.com');
//define('WEBMASTER_TIMEZONE', 'America/Los_Angeles');
define('WEBMASTER_TIMEZONE', 'Asia/Calcutta');
define('RIGHT_TO_LEFT_LANGUAGE_ARRAY', serialize(array('ar','ur','iw','fa','yi')));
// Based on the Development(DEV) and Production(PH) server and my laptop(PRVN)
define('CAMPAIGN_HEADER_SUFFIX','PRVN');
define('WWW_AUTHENTICATE','NO');
define('WWW_AUTHENTICATION_UNM','KyalLyla');
define('WWW_AUTHENTICATION_PWD','L0v3T0Pl@yTog3th3r');
// define('IMAGE_BANK_QUOTA', 1048576 * 400); //1MB(megabyte) =1048576 Bytes
define('IMAGE_BANK_QUOTA', 1048576 * 500); //1MB(megabyte) =1048576 Bytes
define('QUEUEING_BATCH_SIZE', 10000); //Used to fetch records while queueing a campaign
//stats table array
define('STATS_DBTABLE_ARRAY', serialize(array('red_email_stats_zero','red_email_stats_one','red_email_stats_two')));
define('STATS_TABLE_IN_USE',1); // 0,1,2
//USD to INR conversion rate
define('USDtoINR',77);

// MAINTENACE SETTINGS
define('MAINTENANCE_MODE_FOR_LOGGED_USERS', 'no'); // yes / no
define('MAINTENANCE_MODE_FOR_ALL_USERS', 'no'); // yes / no, front end down for logged in members and visitors


/* End of file constants.php */
/* Location: ./application/config/constants.php */