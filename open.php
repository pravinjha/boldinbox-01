<?php
 // http://www.beonlist.com/cprocess/opened/dHdteVdBNEpxRzU0UHBRVzQva1JoUT09/ODg4MDQ3NS1wcmF2aW4uamhhQGdtYWlsLmNvbQ
//https://www.boldinbox.com/open.php?a=dHdteVdBNEpxRzU0UHBRVzQva1JoUT09&b=ODg4MDQ3NS1wcmF2aW4uamhhQGdtYWlsLmNvbQ



$enc_cid=$_GET['a'];
$subscriber_id = $_GET['b'];

markRead($enc_cid, $subscriber_id);






// ------------------- Area for functions----------------------------	



function markRead($enc_cid=0,$subscriber_id=0){
$id =  encryptor('decrypt', $enc_cid);



$subscriber_id = str_replace('.html','',$subscriber_id);
$arrSubscriber = decodeSubscriber($subscriber_id);
list($subscriber_id,$subscriber_email) = $arrSubscriber;	
if(!(intval($subscriber_id) > 0 ))exit;
$subscriber_email = webCompatibleString($subscriber_email);


$mIP = getRealIpAddr();

//Following IPs are from google and should be discarded
		if(trim($mIP) !='')$arrMyIP = @explode(',',$mIP);
		for($i=0;$i < count($arrMyIP);$i++){
			$ip = $arrMyIP[$i];
			$ip_needle = ip2long($ip);			
			//if(ip2long('64.233.160.0') <= $ip_needle  &&  $ip_needle <= ip2long('64.233.191.255')) exit; // "in range";
			//if(ip2long('66.249.64.0') <= $ip_needle  && $ip_needle <= ip2long('66.249.95.255')) exit; // "in rang";
			
			//if(ip2long('66.102.0.0') <= $ip_needle  &&  $ip_needle <= ip2long('66.102.15.255') ) exit; // "in range";
			
			//if(ip2long('72.14.192.0')  <= $ip_needle  &&  $ip_needle <= ip2long('72.14.255.255')) exit; // "in rang";
			//if(ip2long('74.125.0.0')   <= $ip_needle  &&  $ip_needle <= ip2long('74.125.255.255')) exit; // "in rang";
			//if(ip2long('209.85.128.0') <= $ip_needle  &&  $ip_needle <= ip2long('209.85.255.255')) exit; // "in rang";
			//if(ip2long('216.239.32.0') <= $ip_needle  &&  $ip_needle <= ip2long('216.239.63.255')) exit; // "in rang";			
		}
		
		$statsTable = getStatsTable($id);  		

		//echo "select * from $statsTable as ret where campaign_id='$id' and subscriber_id='$subscriber_id' and subscriber_email_address='$subscriber_email' and email_sent=1";
		$result = dbconnect()->query("select * from $statsTable as ret where campaign_id='$id' and subscriber_id='$subscriber_id' and subscriber_email_address='$subscriber_email' and email_sent=1"); 
		while ($row = $result->fetch_assoc()) {
			if($row['email_track_read']<=0){					
				dbconnect()->query("update 	$statsTable set email_track_read=1, email_track_read_date=	'".date('Y-m-d H:i:s',time())."'	 where campaign_id='$id' and subscriber_id='$subscriber_id'");

				dbconnect()->query("update `red_email_campaigns_scheduled` set `email_track_read` = `email_track_read`+1 where campaign_id='$id'");			 
				dbconnect()->query("update red_email_subscribers set `read` = `read`+1, `last_read_ip`='$mIP', `last_read_date`=current_timestamp() where subscriber_id='$subscriber_id'");	
				// Increment OPENED global_ipr_daily for major webmails
				$arrEml = @explode('@',$subscriber_email);
				$emlDomain = $arrEml[1];
				$IPR_Domain = (in_array($emlDomain,array('gmail.com', 'yahoo.com', 'hotmail.com', 'aol.com', 'msn.com', 'outlook.com', 'windowslive.com', 'live.com', 'mail.ru', 'me.com', 'mac.com', 'comcast.net', 'cox.net', 'rediffmail.com')))? $emlDomain : 'all' ;	
			
				$rsCampaign = dbconnect()->query("select DATE_FORMAT(email_send_date, '%Y-%m-%d')email_send_date, pipeline,campaign_created_by from red_email_campaigns where campaign_id='$id'" );
				while ($c_row = $rsCampaign->fetch_assoc()) {
					$vmta = $c_row['pipeline'];
					$email_send_date = $c_row['email_send_date'];
					$user_id = $c_row['campaign_created_by'];
								
					//echo "insert into red_global_ipr_daily set `mail_domain` = '$IPR_Domain' ,  `log_date`='$email_send_date' ,  `pipeline`='$vmta', `user_id`='$user_id', total_opened=(total_opened + 1) ON DUPLICATE  KEY UPDATE  total_opened=(total_opened + 1) ";
					dbconnect()->query("insert into red_global_ipr_daily set `mail_domain` = '$IPR_Domain' ,  `log_date`='$email_send_date' ,  `pipeline`='$vmta', `user_id`='$user_id', total_opened=(total_opened + 1) ON DUPLICATE  KEY UPDATE  total_opened=(total_opened + 1) ");			
				}
				mysqli_free_result($rsCampaign); 
			}

		
		}// while closes
		


		$name= '/srv/users/serverpilot/apps/boldinbox-com/public/locker/images/pix.gif';
		$fp = fopen($name, 'rb');

		// send the right headers
		header("Content-Type: image/gif");
		header("Content-Length: " . filesize($name));

		// dump the picture and stop the script
		fpassthru($fp);
		exit(0);
		 
}		
	
function getStatsTable($cid=0){
		//$mysqli = new mysqli("localhost", "6545bd86607c", "a61cbc8f72208fa5", "bibData");
		$arrStatsTable = array('red_email_stats_zero','red_email_stats_one','red_email_stats_two');
		$statsTableIndex = 0;
		if($cid > 0){
			$statsTableIndex = dbconnect()->query("select stats_table_id from red_email_campaigns where campaign_id = '$cid'")->fetch_object()->stats_table_id;
		}
		
		return $arrStatsTable[$statsTableIndex];
}
function encryptor($action, $string) {
    	$output = false;

    	$encrypt_method = "AES-256-CBC";
    	//pls set your unique hashing key
    	$secret_key = 'j0TumK0h0P@s@nDw@h!b@@Tk@h3ng3';
    	$secret_iv = 'pr@v!njh@r@nd0m';

   		// hash
    	$key = hash('sha256', $secret_key);

    	// iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
    	$iv = substr(hash('sha256', $secret_iv), 0, 16);

    	//do the encyption given text/string/number
    	if( $action == 'encrypt' ) {
        	$output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
        	$output = base64UrlSafeEncode($output);
    	}elseif( $action == 'decrypt' ){
    		//decrypt the given text/string/number
        	$output = openssl_decrypt(base64UrlSafeDecode($string), $encrypt_method, $key, 0, $iv);
    	}

    return $output;
	}		
function base64UrlSafeEncode($data){		
		return (trim($data))? rtrim(strtr(base64_encode(trim($data)), '+/', '-_'), '=') : '';		
	}
function base64UrlSafeDecode($base64){
	  return base64_decode(strtr($base64, '-_', '+/'));
	}	
function webCompatibleString($str){
		$theBad = 	array("ì","î","ë","í","Ö","ó","ñ","¬");
		$theGood = array("\"","\"","'","'","...","-","-","");
		$str = str_replace($theBad,$theGood,$str);
		$str = preg_replace('/[^(\x20-\x7F)\x0A]*/','', $str);
		return $str;
	}
function decodeSubscriber($encoded_subscriber){
		$arrSubscriber = array();
		$strDecodedSubscriber = base64_decode(strtr($encoded_subscriber, '-_', '+/'));
		$arrSubscriber = explode('-',$strDecodedSubscriber,2);
		
		return $arrSubscriber;
	}
function getRealIpAddr(){
	 
		if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
		{
		  $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
		}
		elseif (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
		{
		  $ip=$_SERVER['HTTP_CLIENT_IP'];
		}		 
		else
		{
		  $ip=$_SERVER['REMOTE_ADDR'];
		}
		return $ip;
	}
function dbconnect(){
   $mysqli = new mysqli("localhost", "6545bd86607c", "a61cbc8f72208fa5", "bibData");
   if ($mysqli->connect_error){
      //echo ($mysqli->connect_error);
      return null;
   } else {
      return $mysqli;
   }
}
?>