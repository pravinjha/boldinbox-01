<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Wln extends CI_Controller {

	// constructor
	function __construct()
	{
		parent::__construct();		
	}
	// Via Stripe for WatchLiveNow.com 
	public function callBackStripe(){		
		// retrieve the request's body and parse it as JSON
		$webhook_response = @file_get_contents('php://input');  		
				
		// grab the event information
		$event_json = json_decode($webhook_response);		
		
		$event_id = $event_json->id;	
		$external_invoice = $event_json->data->object;	
		$recipient_data = $external_invoice->charges->data[0]->billing_details;
		//echo "<pre>";print_r($recipient_data);
		$recipient_email = $recipient_data->name;	
		
		$ticket_amount = $external_invoice->amount / 100; // amount comes in as amount in cents, so we need to convert to dollars
		$show_name = $external_invoice->description;
		$emlBody = $this->getEmailBody($ticket_amount, $show_name);
		
		$this->do_email($emlBody, "CONFIRMED! You're ready to Watch Live Now!",array($recipient_email,'info@standupglobal.com','sumit@multiplat.in'));
		
		$dirPath = 'assets/stripelog/'.date('Y').'/'.date('m').'/'.date('d');	
 		@mkdir($dirPath,0755, true );		
		file_put_contents($dirPath.'/WLN_'.$recipient_email.'_'.time().'.txt', $webhook_response);
		
		
		//print_r($event_json);exit;		
 	}
	
	// created to send manual emails on email errors
	public function callBackStripeManual(){		
		
		// $recipient_email = array('andy.menges@gmail.com','jason.peservich@email.com','donnakayoyler@yahoo.com','djdubya62@yahoo.com');
		$recipient_email = array('sumit@multiplat.in');
		
		$ticket_amount = 2200 / 100; // amount comes in as amount in cents, so we need to convert to dollars
		// $show_name = 'WatchLiveNow | Jim Florentine &#38; Don Jamieson LIVE from Jonathan’s in Ogunquit Maine presented by TheComicsGym.com';
		$show_name = 'WatchLiveNow | Nick Di Paolo LIVE from The Plaza Hotel &#38; Casino in Las Vegas presented by Sheath Underwear!';
		$emlBody = $this->getEmailBody($ticket_amount, $show_name);
		for($i=0; $i < count($recipient_email); $i++){
			//$this->do_email($emlBody, "CONFIRMED! You're ready to Watch Live Now!",array($recipient_email[$i],'info@standupglobal.com'));
			$this->do_email($emlBody, "CONFIRMED! You're ready to Watch Live Now!",array($recipient_email[$i],'pravinjha@gmail.com'));
			echo "Mail Sent To ".$recipient_email[$i]."<br />";
		}		
 	}
	function do_email($msg=NULL, $sub=NULL, $to=array(), $replyTo=NULL, $from=NULL)
	{
		$this->load->helper('wln_send_mail');	
		if($from == NULL)$from	= 'info@standupglobal.com'; 
		if($replyTo == NULL)$replyTo	= 'info@standupglobal.com'; 
				
       	do_send_email($to, $from, 'WATCH LIVE NOW', $sub, $msg, strip_tags($msg),$replyTo) ;	// from helper
		
	}	
	public function getEmailBody($amnt=0, $show_name=''){
		return "<html>
<head>
<title>Watch Live Now Payment Successfull</title>
</head>
<body>
<table width = '610' align = 'center' border = '0' cellpadding = '5' cellspacing = '0' style = 'border:solid 5px #000000;background:#FFFFFF;color:#000000;font-size:16px;padding:10px;font-family: georgia;'>
<tr>
<td style = 'background:#FFFFFF;border-bottom:solid 5px #000000;'>
<a href = 'https://watchlivenow.com' target = '_blank'><img src = 'https://www.thecomicsgym.com/assets/wln/wln.png' border = '0' width = '600' /></a><br/><br/>
</td>
</tr>
<tr>
<td style = 'padding-top:20px;'>
<b>Thank you for the purchase!</b>
<br/><br/>
You are all set to view this great <b>LIVE</b> event:<br/>
$show_name
<br/><br/>
Amount paid:$$amnt
<br/><br/>
Please review your event for the start time (please note time zone) and be sure to 
login about 10 minutes prior to the event. At show time you will be directed to refresh 
your screen when the broadcast begins.
<br/><br/>
Below you will find simple instructions as to how to watch the program on Apple TV, 
Roku, Android TV and Amazon Fire TV. You can watch on your computer internet browser 
as well where you can live chat during the event.
<br/><br/>
We would encourage you to login once prior to the event date/time so it is familiar
to you at showtime.
<br/><br/>
As with all of our events, this event will continue to be viewable for between 7 and
30 days after the initial broadcast. Please see your event description to find out
when your event will be visible until.
<br/><br/>
If you have any questions please contact us at <a href = 'mailto:info@standupglobal.com' style='color:#FD0000'>info@standupglobal.com</a>
<br/><br/>
Thank you,
<br/><br/>
The Comics Gym<br/>
on Watch Live Now<br/><br/>

<img src = 'https://www.thecomicsgym.com/assets/wln/TheComicsGymOnWatchLiveNow.png?1.01' border = '0' width = '600' />
</td>
</tr>
</table>
</body>
</html>";
	
	
	}
	
}
