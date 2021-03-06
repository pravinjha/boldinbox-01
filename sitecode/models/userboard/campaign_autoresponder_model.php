<?php
/*
	Model class for campaign
*/
class Campaign_Autoresponder_Model extends CI_Model
{
	//Constructor class with parent constructor
	function __construct(){
		parent::__construct();
		$this->load->model('UserModel');
		$this->load->model('userboard/Campaign_Model');
		$this->load->model('userboard/Autoresponder_Model');
		$this->load->helper('simple_html_dom');		
	}
	/**
	  * Function encode_url to encrypt campaign link in datbase
	  *
	 */
	function encode_url($campaign_id=0,$email_data="",$autoresponder=false){
		 
		$arrSearchStr = array('"\'','\'"');
		$replace_arr = array('"','"');
		$email_data=str_ireplace($arrSearchStr, $replace_arr, $email_data);
		$is_clicktracking = $this->db->query("select is_clicktracking from red_email_campaigns where campaign_id='$campaign_id'")->row()->is_clicktracking;
		// Check is_clicktracking ON or OFF
		//IF($is_clicktracking){
			if($autoresponder){
				$email_array=change_link($email_data,$campaign_id,true);
				$this->Autoresponder_Model->update_autoresponder(array('click_url'=>$email_array[1],'campaign_after_encode_url'=>$email_array[0]),array('campaign_id'=>$campaign_id));
				return $email_array[0];
			}else{
				$email_array=change_link($email_data,$campaign_id);
				$this->Campaign_Model->update_campaign(array('click_url'=>$email_array[1],'campaign_after_encode_url'=>$email_array[0]),array('campaign_id'=>$campaign_id));
			}
		/*}else{
			if($autoresponder){			
				$this->Autoresponder_Model->update_autoresponder(array('click_url'=>'','campaign_after_encode_url'=>$email_data),array('campaign_id'=>$campaign_id));
				return $email_data;
			}else{			
				$this->Campaign_Model->update_campaign(array('click_url'=>'','campaign_after_encode_url'=>$email_data),array('campaign_id'=>$campaign_id));
			}
		}
		*/
	}

	/*
	 * Function attach_campaign_link to attach campaign links:emailtrack,mailview,footer,unsubscribe,
	 * forward links
	 * @param (array) (campaign)  contains campaign detail
	 */
	function attach_campaign_link($campaign=array(),$user=array(), $is_autoresponder = false){		
		
		
		if($campaign['campaign_template_option']==5){ // Text only
			$campaign_footer = $this->campaign_footer_text_only($user, $campaign['campaign_id'], $is_autoresponder, true);
			$message= nl2br($campaign['campaign_content']).$campaign_footer;
		}elseif($campaign['campaign_template_option']==3){ // DIY	
			$campaign['campaign_content']=$campaign['campaign_after_encode_url'];  // This is only for DIY
			
		 	$campaign_top = $this->get_campaign_top($user, $campaign['campaign_id'], $is_autoresponder);		
			$campaign_footer = $this->get_campaign_footer_diy($user, $campaign['campaign_id'], $is_autoresponder, false);
			$campaign['campaign_content'] = replaceTopLinks($campaign['campaign_content'], $campaign_top);
			$campaign['campaign_content'] = removeDefaultPreheader( $campaign['campaign_content']);
			
			//$pref = "<table style='min-width: 100%; table-layout: fixed; background-color:".$campaign['campaign_outer_bg'].";' align='center'><tr><td align='center'>".$campaign_top;			
			$pref = "<table style='min-width: 100%; table-layout: fixed; background-color:".$campaign['campaign_outer_bg'].";' align='center'><tr><td align='center'>";			
			$suf = "";
			$message = wrap_element_around_element_in_html($campaign['campaign_content']."</td></tr><tr><td>&nbsp;</td></tr></table>".$campaign_footer,$pref,$suf);	
		}elseif($campaign['campaign_template_option']==1){ // ImportFromURL
			$campaign_top = $this->get_campaign_top($user, $campaign['campaign_id'], $is_autoresponder);
			$campaign_footer = $this->get_campaign_footer($user, $campaign['campaign_id'], $is_autoresponder, true);
			 
			$message= $campaign_top.$campaign['campaign_after_encode_url'].$campaign_footer;
		}else{ // Import from url, from zip and pasted HTML
			$campaign_top = $this->get_campaign_top($user, $campaign['campaign_id'], $is_autoresponder);
			$campaign_footer = $this->get_campaign_footer($user, $campaign['campaign_id'], $is_autoresponder, true);
			 
			$message= $campaign_top.$campaign['campaign_content'].$campaign_footer;

		}
		if($campaign['campaign_template_option']!=5){ // Non Text Emails
			//$preheader = $this->db->query("select preheader from red_email_campaigns where campaign_id='".$campaign['campaign_id']."'")->row()->preheader;
			
			//$message = "<span style='color:#FFFFFF;display:none !important;font-size:1px;'>$preheader</span>".$message;
		}
		$arr_pipeline_domain = config_item('vmta_domain'); 
		$vmta = $user[0]['vmta'];
		$pipeline_domain = $arr_pipeline_domain[$vmta];
		$pipeline_domain =  (trim($pipeline_domain) != '')? $pipeline_domain : 'www.boldinbox.com';
				
		//$message = str_ireplace('www.'.SYSTEM_DOMAIN_NAME, $pipeline_domain, $message);
		//$message = str_ireplace('https://'.$pipeline_domain, 'http://'.$pipeline_domain, $message);
		$message = str_ireplace("xxxbackground_colorxxx", "background-color:".$campaign['campaign_outer_bg'].";", $message);
		return $message;
	}
	 
	
	function get_campaign_top($user, $email_campaign_id=0, $is_autoresponder=false){
		$arr_pipeline_domain = config_item('vmta_domain'); 
		$vmta = $user[0]['vmta'];
		$pipeline_domain = $arr_pipeline_domain[$vmta];
		$pipeline_domain =  (trim($pipeline_domain) != '')? $pipeline_domain : 'www.boldinbox.com';
		
		$language_text_arr=$this->UserModel->get_language_text(array('language'=>$user[0]['language']));
		foreach($language_text_arr as $language){
			${$language['text_code']}=$language['text'];
		}	
		//$email_not_displaying_correctly;
		$strUnsubscribelink = ucwords($unsubscribe);
		$strAbuseLink = 'Report Abuse';
		$encode_camapign_id =  $this->is_authorized->encryptor('encrypt', $email_campaign_id);
		if($is_autoresponder){	
			if($user[0]['is_risky']){
				$mail_view_link="<table width='100%' style='xxxbackground_colorxxx'><tr><td align='center'><font size='1' style='color:#777;font-family:helvetica;font-size:11px;line-height:125%;'><a href='".CAMPAIGN_DOMAIN."a/{$encode_camapign_id}/[scheduled_id]/[subscriber_id]'>$view_in_browser</a> &nbsp; | &nbsp; <a href='".CAMPAIGN_DOMAIN."autoresponder_email/unsubscribe/{$encode_camapign_id}/[scheduled_id]/[subscriber_id]'>$strUnsubscribelink</a></font></td></tr></table>";
			}else{
				$mail_view_link="<table width='100%' style='xxxbackground_colorxxx'><tr><td align='center'><font size='1' style='color:#777;font-family:helvetica;font-size:11px;line-height:125%;'>$email_not_displaying_correctly <a href='".CAMPAIGN_DOMAIN."a/{$encode_camapign_id}/[scheduled_id]/[subscriber_id]'>$view_in_browser</a></font></td></tr></table>";
			}						
		}else{
			
			$mail_view_link="<table width='100%' style='xxxbackground_colorxxx'><tr><td align='center'><font size='1' style='color:#777;font-family:helvetica;font-size:11px;line-height:125%;'><a href='".CAMPAIGN_DOMAIN."c/{$encode_camapign_id}/[subscriber_id]'>$view_in_browser</a> &nbsp; | &nbsp; <a href='".CAMPAIGN_DOMAIN."cprocess/unsubscribe/{$encode_camapign_id}/[subscriber_id]'>$strUnsubscribelink</a> &nbsp; | &nbsp; <a href='".CAMPAIGN_DOMAIN."cprocess/abuse/[ENCRYPT_CID_AND_SID]'>$strAbuseLink</a></font></td></tr></table>";
			/*
			if($user[0]['is_risky']){
				$mail_view_link="<table width='100%' style='xxxbackground_colorxxx'><tr><td align='center'><font size='1' style='color:#777;font-family:helvetica;font-size:11px;line-height:125%;'><a href='".CAMPAIGN_DOMAIN."c/{$encode_camapign_id}/[subscriber_id]'>$view_in_browser</a> &nbsp; | &nbsp; <a href='".CAMPAIGN_DOMAIN."cprocess/unsubscribe/{$encode_camapign_id}/[subscriber_id]'>$strUnsubscribelink</a> &nbsp; | &nbsp; <a href='".CAMPAIGN_DOMAIN."cprocess/abuse/[ENCRYPT_CID_AND_SID]'>$strAbuseLink</a></font></td></tr></table>";	
			}else{
				$mail_view_link="<table width='100%' style='xxxbackground_colorxxx'><tr><td align='center'><font size='1' style='color:#777;font-family:helvetica;font-size:11px;line-height:125%;'>$email_not_displaying_correctly <a href='".CAMPAIGN_DOMAIN."c/{$encode_camapign_id}/[subscriber_id]'>$view_in_browser</a></font></td></tr></table>";
			}	
			*/
		}		
		return $mail_view_link;
	}
	function get_campaign_footer_diy($user, $email_campaign_id=0, $is_autoresponder=false, $is_address=false){	
		$arr_pipeline_domain = config_item('vmta_domain'); 
		$vmta = $user[0]['vmta'];
		$pipeline_domain = $arr_pipeline_domain[$vmta];
		$pipeline_domain =  (trim($pipeline_domain) != '')? $pipeline_domain : 'www.boldinbox.com';
				
		$user_selected_language = 	$user[0]['language'];
		$rtl_language_array=unserialize(RIGHT_TO_LEFT_LANGUAGE_ARRAY);		
		$encode_camapign_id =  $this->is_authorized->encryptor('encrypt', $email_campaign_id);
		$rand_number=rand(0,1);
		$powered_by_logo = '';
		
		$language_text_arr=$this->UserModel->get_language_text(array('language'=>$user_selected_language));
		foreach($language_text_arr as $language){
			${$language['text_code']}=$language['text'];
		}		
		if(in_array($user_selected_language,$rtl_language_array)){
			$dir="dir='rtl'";
			$align="align='left'";
			$is_rtl=true;
		}else{
			$dir="";
			$align="align='right'";
			$is_rtl=false;
		}
		if($user[0]['rc_logo']==1){
			$powered_by_logo = "<a href='".CAMPAIGN_DOMAIN."cprocess/powered_by_bib/{$encode_camapign_id}/[subscriber_id]'>
								<img border='0' alt='Powered by BoldInbox' src='".CAMPAIGN_DOMAIN."locker/images/powered-by-logo-blue.png' />
							</a>";
		}
		if($is_autoresponder){			
			$emailtrack_img="<img border='0' src='".CAMPAIGN_DOMAIN."autoresponder_email/read/{$encode_camapign_id}/[scheduled_id]/[subscriber_id]' width='1' height='5' /> ";			
			 
			$unsubscribe_link= "".CAMPAIGN_DOMAIN."autoresponder_email/unsubscribe/{$encode_camapign_id}/[scheduled_id]/[subscriber_id]" ;			
			$forward_link= "".CAMPAIGN_DOMAIN."forward_to_friend/autoresponder/{$encode_camapign_id}/[scheduled_id]/[subscriber_id]";
		}else{	
			//if($user[0]['member_id'] > 1){
			//$emailtrack_img="<img border='0' src='".CAMPAIGN_DOMAIN."cprocess/opened/{$encode_camapign_id}/[subscriber_id]'  width='1' height='2' /> ";			 
			//$emailtrack_img = "<img border='0' src='http://gowebby.in/bib/check.php?abc={$encode_camapign_id}&pqr=[subscriber_id]' width='1' height='2' alt='seeit' /> ";  
			//$emailtrack_img = "<img border='0' src='http://www.trupiz.com/getstats/worked/{$encode_camapign_id}/[subscriber_id]' width='1' height='2' alt='..' /> ";  
			//}else{
				$emailtrack_img="<img border='0' src='https://boldinbox.com/open.php?a=".$encode_camapign_id."&b=[subscriber_id]' alt='' width='1' height='2' /> ";			 
			//}
			$unsubscribe_link= "".CAMPAIGN_DOMAIN."cprocess/unsubscribe/{$encode_camapign_id}/[subscriber_id]";			
			$forward_link= "".CAMPAIGN_DOMAIN."send_forward/index/{$encode_camapign_id}/[subscriber_id]";
		} 


		// START: We need to show Footer Address row for non-diy created campaigns to satisfy can-spam-law.
		
			$company =($user[0]['company'] != '')? $user[0]['company']: '';
			$address =($user[0]['address_line_1'] != '')? $user[0]['address_line_1']: '';
			$address .=($user[0]['address_line_2'] != '')? $user[0]['address_line_2']: '';
			$city =($user[0]['city'] != '')? $user[0]['city']: '';
			$state =($user[0]['state'] != '')? $user[0]['state']: '';
			$zipcode =($user[0]['zipcode'] != '')? $user[0]['zipcode']: '';
			$country =($user[0]['country_name'] != '' and $user[0]['country_name'] != 225)? $user[0]['country_name']: 'USA';
			$country =(strtolower($country) =='custom')? $user[0]['country_custom']: $country;
		if($is_address){	
			$footerAddress = "<tr><td align='center'>" .$company ." | ".$address." | ". $city." | ".$state." | ".$zipcode;
			$footerAddress .= ($country !='USA' and $country !='United States')? " | ". $country : '';			
			$footerAddress .= "</td></tr>";

		}	
		// ENDS: We need to show Footer Address row for non-diy created campaigns to satisfy can-spam-law.
		
		if($user_selected_language!="en"){
			$to_ensure_delivery=$to_ensure_delivery.",";
		}
			
		
		// new footer starts
	
		$footer_link="
		<table width='100%' align='center'  style='table-layout: fixed; background-color:#ffffff;' $dir>
			<tr>
				<td>
					<table width='595' align='center' style='margin: 0 auto; color:#777777; font-family:helvetica; font-size:11px;line-height:125%;'>
						{$footerAddress}
						<tr>
							<td style='font-family:Helvetica, arial, sans-serif;font-size:11px;font-weight:normal;text-align:left;color:#777777;margin:6px 0px 0px 0px;line-height:125%' >This message was sent by $company to [CONTACT_EMAIL_ID] </td>
							<td  rowspan='3' $align>{$powered_by_logo}</td>
						</tr>";
	if($user[0]['is_disclaimer']){					
		$footer_link .="<tr>
							<td style='font-family:Helvetica, arial, sans-serif;font-size:11px;font-weight:normal;text-align:left;color:#777777;margin:6px 0px 0px 0px;line-height:125%'>These emails are purely for marketing purposes.</td>
						</tr>";
	}else{

		$footer_link .="<tr>
							<td style='font-family:Helvetica, arial, sans-serif;font-size:11px;font-weight:normal;text-align:left;color:#777777;margin:6px 0px 0px 0px;line-height:125%'>$to_ensure_delivery $add_us_to_your_address_book.</td>
						</tr>";
	}	
	//Remove forward-link for user:sensation[99]	
	if( trim($user[0]['member_id']) == '99'){
		$footer_link .="<tr>
							<td style='font-family:Helvetica, arial, sans-serif;font-size:11px;font-weight:normal;text-align:left;color:#777777;margin:6px 0px 0px 0px;line-height:125%'>
							<a style='font-family:Helvetica, arial, sans-serif;font-size:11px;font-weight:normal;text-align:left;color:#777777;margin:6px 0px 0px 0px;line-height:125%' href='{$unsubscribe_link}'>$unsubscribe</a>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td>{$emailtrack_img}</td>
			</tr>
		</table>		
		</body></html>";
	}else{
		$footer_link .="<tr>
							<td style='font-family:Helvetica, arial, sans-serif;font-size:11px;font-weight:normal;text-align:left;color:#777777;margin:6px 0px 0px 0px;line-height:125%'>
							<a style='font-family:Helvetica, arial, sans-serif;font-size:11px;font-weight:normal;text-align:left;color:#777777;margin:6px 0px 0px 0px;line-height:125%' href='{$unsubscribe_link}'>$unsubscribe</a> | <a style='font-family:Helvetica, arial,  sans-serif;font-size:11px;font-weight:normal;text-align:left;color:#777777;margin:6px 0px 0px 0px;line-height:125%' href='{$forward_link}'>$forward_to_friend</a>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td>{$emailtrack_img}</td>
			</tr>
		</table>		
		</body></html>";
	
	}
	
		
		
		// New footer ENDS
		return $footer_link;	 
	}
	function get_campaign_footer($user, $email_campaign_id=0, $is_autoresponder=false, $is_address=false){
		$arr_pipeline_domain = config_item('vmta_domain'); 
		$vmta = $user[0]['vmta'];
		$pipeline_domain = $arr_pipeline_domain[$vmta];
		$pipeline_domain =  (trim($pipeline_domain) != '')? $pipeline_domain : 'www.boldinbox.com';
				
		$user_selected_language = 	$user[0]['language'];
		$rtl_language_array=unserialize(RIGHT_TO_LEFT_LANGUAGE_ARRAY);		
		$encode_camapign_id =  $this->is_authorized->encryptor('encrypt', $email_campaign_id);
		$rand_number=rand(0,1);
		$powered_by_logo = '';
		
		$language_text_arr=$this->UserModel->get_language_text(array('language'=>$user_selected_language));
		foreach($language_text_arr as $language){
			${$language['text_code']}=$language['text'];
		}		
		if(in_array($user_selected_language,$rtl_language_array)){
			$dir="dir='rtl'";
			$align="align='left'";
			$is_rtl=true;
		}else{
			$dir="";
			$align="align='right'";
			$is_rtl=false;
		}
		if($user[0]['rc_logo']==1){
			$powered_by_logo = "<a href='".CAMPAIGN_DOMAIN."cprocess/powered_by_bib/{$encode_camapign_id}/[subscriber_id]'>
								<img border='0' alt='Powered by BoldInbox' src='".CAMPAIGN_DOMAIN."locker/images/powered-by-logo-blue.png' />
							</a>";
		}
		if($is_autoresponder){			
			$emailtrack_img="<img border='0' src='".CAMPAIGN_DOMAIN."autoresponder_email/read/{$encode_camapign_id}/[scheduled_id]/[subscriber_id]' width='".$rand_number."' height='".$rand_number."' /> ";			
			 
			$unsubscribe_link= "".CAMPAIGN_DOMAIN."autoresponder_email/unsubscribe/{$encode_camapign_id}/[scheduled_id]/[subscriber_id]" ;			
			$forward_link= "".CAMPAIGN_DOMAIN."forward_to_friend/autoresponder/{$encode_camapign_id}/[scheduled_id]/[subscriber_id]";
		}else{
			$emailtrack_img="<img border='0' src='https://boldinbox.com/open.php?a=".$encode_camapign_id."&b=[subscriber_id]'  alt='' width='1' height='2' /> ";			 
		
			//$emailtrack_img="<img border='0' src='".CAMPAIGN_DOMAIN."cprocess/opened/{$encode_camapign_id}/[subscriber_id]' width='1' height='2' /> ";			 
			//$emailtrack_img = "<img border='0' src='http://gowebby.in/bib/check.php?abc={$encode_camapign_id}&pqr=[subscriber_id]' width='1' height='2' alt='seeit' /> "; 
			//$emailtrack_img = "<img border='0' src='http://www.trupiz.com/getstats/worked/{$encode_camapign_id}/[subscriber_id]' width='1' height='2' alt='..' /> ";  
			
			$unsubscribe_link= "".CAMPAIGN_DOMAIN."cprocess/unsubscribe/{$encode_camapign_id}/[subscriber_id]";			
			$forward_link= "".CAMPAIGN_DOMAIN."send_forward/index/{$encode_camapign_id}/[subscriber_id]";
		} 


		// START: We need to show Footer Address row for non-diy created campaigns to satisfy can-spam-law.		
			$company =($user[0]['company'] != '')? $user[0]['company']: '';
			$address =($user[0]['address_line_1'] != '')? $user[0]['address_line_1']: '';
			$address .=($user[0]['address_line_2'] != '')? $user[0]['address_line_2']: '';
			$city =($user[0]['city'] != '')? $user[0]['city']: '';
			$state =($user[0]['state'] != '')? $user[0]['state']: '';
			$zipcode =($user[0]['zipcode'] != '')? $user[0]['zipcode']: '';
			$country =($user[0]['country_name'] != '' and $user[0]['country_name'] != 225)? $user[0]['country_name']: 'USA';
			$country =(strtolower($country) =='custom')? $user[0]['country_custom']: $country;
		if($is_address && trim($user[0]['member_id']) != 959){	
			$footerAddress = "<tr><td align='center'>" .$company ." | ".$address." | ". $city." | ".$state." | ".$zipcode;
			$footerAddress .= ($country !='USA' and $country !='United States')? " | ". $country : '';			
			$footerAddress .= "</td></tr>";

		}	
		// ENDS: We need to show Footer Address row for non-diy created campaigns to satisfy can-spam-law.
		
		if($user_selected_language!="en"){
			$to_ensure_delivery=$to_ensure_delivery.",";
		}
	
		
		$footer_link="
		<table width='100%' align='center'  style='background-color:#ffffff;' $dir>
			<tr>
				<td>
					<table width='100%' border='0' align='center' style='font-family:Helvetica, arial, sans-serif; font-size:11px; font-weight:normal; text-align:center; color:#606060; line-height:150%'>
						{$footerAddress}
						<tr>
							<td align='center'>This message was sent by $company to [CONTACT_EMAIL_ID] </td>
						</tr>";
						
	if($user[0]['is_disclaimer']){					
		$footer_link .="<tr>
							<td  align='center'>These emails are purely for marketing purposes.</td>
						</tr>";
	}else{

		$footer_link .="<tr>
							<td align='center'>$to_ensure_delivery $add_us_to_your_address_book.</td>
						</tr>";
	}	
	//Remove forward-link for user:sensation[99]	
	if( trim($user[0]['member_id']) == '99'){
		$footer_link .="<tr>
							<td align='center'>
							<a style='color:#606060;' href='{$unsubscribe_link}'>$unsubscribe</a>
							</td>
						</tr>
						<tr><td>&nbsp;</td></tr>
						<tr><td  align='center'>{$powered_by_logo}</td></tr>
					</table>
				</td>
			</tr>
			<tr>
				<td>{$emailtrack_img}</td>
			</tr>
		</table>		
		</body></html>";
	}else{
		$footer_link .="<tr>
							<td align='center'>
							<a style='color:#606060;' href='{$unsubscribe_link}'>$unsubscribe</a> | <a style='color:#606060;' href='{$forward_link}'>$forward_to_friend</a>
							</td>
						</tr>
						<tr><td>&nbsp;</td></tr>
						<tr><td  align='center'>{$powered_by_logo}</td></tr>
					</table>
				</td>
			</tr>
			<tr>
				<td>{$emailtrack_img}</td>
			</tr>
		</table>		
		</body></html>";
	}	
		return $footer_link;	 
	}
	/**
		Function footer_text to fetch footer content for campaign
		@return (string) footer_text_html: return footer text content
	*/
	function campaign_footer_text_only($user, $email_campaign_id=0, $is_autoresponder=false, $is_address=true){
		$arr_pipeline_domain = config_item('vmta_domain'); 
		$vmta = $user[0]['vmta'];
		$pipeline_domain = $arr_pipeline_domain[$vmta];
		$pipeline_domain =  (trim($pipeline_domain) != '')? $pipeline_domain : 'www.boldinbox.com';
				
		$user_selected_language = 	$user[0]['language'];
		$rtl_language_array=unserialize(RIGHT_TO_LEFT_LANGUAGE_ARRAY);
		
		$encode_camapign_id =  $this->is_authorized->encryptor('encrypt', $email_campaign_id);
		$rand_number=rand(0,1);		 
		
		$language_text_arr=$this->UserModel->get_language_text(array('language'=>$user_selected_language));
		foreach($language_text_arr as $language){
			${$language['text_code']}=$language['text'];
		}		
		if(in_array($user_selected_language,$rtl_language_array)){
			$dir="dir='rtl'";
			$align="align='left'";
			$is_rtl=true;
		}else{
			$dir="";
			$align="align='right'";
			$is_rtl=false;
		} 
		// START: We need to show Footer Address row for non-diy created campaigns to satisfy can-spam-law.
		
			$company =($user[0]['company'] != '')? $user[0]['company']: '';
			$address =($user[0]['address_line_1'] != '')? $user[0]['address_line_1']: '';
			$address .=($user[0]['address_line_2'] != '')? $user[0]['address_line_2']: '';
			$city =($user[0]['city'] != '')? $user[0]['city']: '';
			$state =($user[0]['state'] != '')? $user[0]['state']: '';
			$zipcode =($user[0]['zipcode'] != '')? $user[0]['zipcode']: '';
			$country =($user[0]['country_name'] != '' and $user[0]['country_name'] != 225)? $user[0]['country_name']: 'USA';
			$country =(strtolower($country) =='custom')? $user[0]['country_custom']: $country;
		if($is_address){	
			$footerAddress =  $company ." | ".$address." | ". $city." | ".$state." | ".$zipcode;
			$footerAddress .= ($country !='USA' and $country !='United States')? " | ". $country : '';			
		}	
		// ENDS: We need to show Footer Address row for non-diy created campaigns to satisfy can-spam-law.
	if($is_autoresponder){	 
		$unsubscribe_link	= "".CAMPAIGN_DOMAIN."autoresponder_email/unsubscribe/{$encode_camapign_id}/[scheduled_id]/[subscriber_id]";			
		$forward_link		= "".CAMPAIGN_DOMAIN."forward_to_friend/autoresponder/{$encode_camapign_id}/[scheduled_id]/[subscriber_id]";
	}else{
		$unsubscribe_link	= "".CAMPAIGN_DOMAIN."cprocess/unsubscribe/{$encode_camapign_id}/[subscriber_id]";
	//Remove forward-link for user:sensation[99]	
		if( trim($user[0]['member_id']) != '99'){
			$forward_link		= "".CAMPAIGN_DOMAIN."send_forward/index/{$encode_camapign_id}/[subscriber_id]";
		}
	}	
		$footer_text_html="
	 
---------------------------------------------------------------------

$this_email_was_sent_to [CONTACT_EMAIL_ID]

";

$footer_text_html .="$unsubscribe : 
$unsubscribe_link

$forward_to_friend : 
$forward_link

".$footerAddress ;

if($user[0]['is_disclaimer']){	
$footer_text_html .="

These emails are purely for marketing purposes.";
}

		return $footer_text_html;
	}	
	
	
	
	function getURLPersonalization($url="",$subscriber_info=array()){
		$arrContactPersonalization = $this->getPersonalizationToken($subscriber_info);		 
		$pattren="/\{([a-zA-Z0-9_\s-!#&(),.@])*\}/";
		preg_match_all($pattren,urldecode($url),$arr_personalization );
		foreach($arr_personalization[0] as $personalization_str){	
			
			$this_personalization_arr =explode(",",$personalization_str,2);			
			$token = trim(trim($this_personalization_arr[0], '{}'));			
			$token_fallback = trim(rtrim($this_personalization_arr[1], '}'));
			
			$fallback_search_arr[]=$personalization_str;							
			$fallback_replace_arr[]= (isset($arrContactPersonalization[$token]) and trim($arrContactPersonalization[$token]) !='')?trim($arrContactPersonalization[$token]): $token_fallback;			 
		}
		 
		return str_replace($fallback_search_arr, $fallback_replace_arr, urldecode($url));	
	}
	function getPersonalization(&$message="",&$text_message="",&$subject="",$subscriber_info=array(),$autoresponder=false,$campaign_id=0,$vmta='rcmailsv.com', &$email_personalization){
		$email_personalization =  true;
		$arr_pipeline_domain = config_item('vmta_domain'); 
		$pipeline_domain = $arr_pipeline_domain[$vmta];		
		$pipeline_domain =  (trim($pipeline_domain) != '')? $pipeline_domain : 'www.'.SYSTEM_DOMAIN_NAME; 
		
		$abuse_id = $this->is_authorized->encryptor('encrypt', $campaign_id.':-:'.$subscriber_info['subscriber_id'].':-:'.$subscriber_info['subscriber_email_address']);	
		
		
		$encodedSubscriber = $this->is_authorized->encodeSubscriber($subscriber_info['subscriber_id'],$subscriber_info['subscriber_email_address']);		
		if($autoresponder){
			$schedule_subscriber='[scheduled_id]/[subscriber_id]';
			$fallback_search_arr = array('[subscriber_id]','%5Bsubscriber_id%5D','%5bsubscriber_id%5d','[scheduled_id]','%5Bscheduled_id%5D','%5bscheduled_id%5d','[CONTACT_EMAIL_ID]','[CAMPAIGN_SENDING_COMPANY_NAME]','[campaign_id]');
			$fallback_replace_arr =array($encodedSubscriber,$encodedSubscriber,$encodedSubscriber,$subscriber_info['schedule_id'],$subscriber_info['schedule_id'],$subscriber_info['schedule_id'],$subscriber_info['subscriber_email_address'],'BoldInbox',$campaign_id);			
		}else{
			$schedule_subscriber='[subscriber_id]';
			$fallback_search_arr = array('[subscriber_id]','%5Bsubscriber_id%5D','%5bsubscriber_id%5d','[CONTACT_EMAIL_ID]','[campaign_id]', '[ENCRYPT_CID_AND_SID]');			
			$fallback_replace_arr =array($encodedSubscriber,$encodedSubscriber,$encodedSubscriber,$subscriber_info['subscriber_email_address'],$campaign_id, $abuse_id);
		}	 
		$fallback_search_arr[] = 'www.'.SYSTEM_DOMAIN_NAME; 
		$fallback_search_arr[] = 'https://'.$pipeline_domain; 
		$fallback_replace_arr[] = $pipeline_domain;
		$fallback_replace_arr[] = 'http://'.$pipeline_domain;
		
		$arrContactPersonalization = $this->getPersonalizationToken($subscriber_info);
		
		$message = $this->personalizeNow($message, $arrContactPersonalization);
		$text_message = $this->personalizeNow($text_message, $arrContactPersonalization);
		$subject = $this->personalizeNow($subject, $arrContactPersonalization);
		
		$message		= str_replace($fallback_search_arr, $fallback_replace_arr, $message);	
		$text_message	= str_replace($fallback_search_arr, $fallback_replace_arr, $text_message);	
		$subject		= str_replace($fallback_search_arr, $fallback_replace_arr, $subject);		
	}
	function personalizeNow($strtoPersonalize, $arrContactPersonalizationDetail){
		$fallback_search_arr = array();
		$fallback_replace_arr = array();
		// $pattren="/\{([a-zA-Z0-9_-])*,([a-zA-Z0-9_\s-])*\}/";
		//$pattren="/\{([a-zA-Z0-9_-])*,([^\/])*\}/";	
		//$pattren="/\{([a-zA-Z0-9_-])*,([-\w\s])*\}/"; 
		$pattren="/\{([a-zA-Z0-9_\s-!#&(),.@])*\}/";
		preg_match_all($pattren,$strtoPersonalize,$arr_personalization );
		 
		foreach($arr_personalization[0] as $personalization_str){	
			
			$this_personalization_arr =explode(",",$personalization_str,2);			
			$token = trim(trim($this_personalization_arr[0], '{}'));			
			$token_fallback = trim(rtrim($this_personalization_arr[1], '}'));
			
			$fallback_search_arr[]=$personalization_str;
			if(strtoupper($token) == 'UNSUBSCRIBE'){				
				$unsubscribe_text =	($token_fallback =='')?'Unsubscribe': $token_fallback;				 
				$fallback_replace_arr[]="<a href='".base_url()."cprocess/unsubscribe/[campaign_id]/{$schedule_subscriber}'>{$unsubscribe_text}</a>";
			}elseif(strtoupper($token) == 'FORWARD'){				
				$forward_text =	($token_fallback =='')?'Forward': $token_fallback;				 
				$fallback_replace_arr[]="<a href='".base_url()."send_forward/index/[campaign_id]/[subscriber_id]/'>{$forward_text}</a>";
			}else{				
				$fallback_replace_arr[]= (isset($arrContactPersonalizationDetail[$token]) and trim($arrContactPersonalizationDetail[$token]) !='')?trim($arrContactPersonalizationDetail[$token]): $token_fallback;				 
			}			
		}		 
		return str_replace($fallback_search_arr, $fallback_replace_arr, $strtoPersonalize);		
	}
	function getPersonalizationToken($subscriber_info){
	
		if(trim($subscriber_info['subscriber_extra_fields']) != ''){
			$arrExtraField = unserialize($subscriber_info['subscriber_extra_fields']);
			if(is_array($arrExtraField) && count($arrExtraField) > 0){
				foreach($arrExtraField as $k=>$v){
					$arrPersonalizationToken[$k]=$v;
				} 
			}
		}
		// If first_name and Last_name are null and subscriber_name has saved value.
		if(is_null($subscriber_info['subscriber_first_name']) && !is_null($subscriber_info['subscriber_name'])){
			$thisSubscriberName = $subscriber_info['subscriber_name'];
			$arrSubscribername = @explode(' ',$thisSubscriberName, 2);
			$subscriber_info['subscriber_first_name'] = $arrSubscribername[0];
			$subscriber_info['subscriber_last_name'] = $arrSubscribername[1];			
		}
		$arrPersonalizationToken['name']	= $subscriber_info['subscriber_first_name'] .' '. $subscriber_info['subscriber_last_name'];
		$arrPersonalizationToken['f_name']	= $subscriber_info['subscriber_first_name'];
		$arrPersonalizationToken['first_name']	= $subscriber_info['subscriber_first_name'];
		$arrPersonalizationToken['l_name']	= $subscriber_info['subscriber_last_name'];
		$arrPersonalizationToken['last_name']	= $subscriber_info['subscriber_last_name'];
		$arrPersonalizationToken['email']	= $subscriber_info['subscriber_email_address'];
		$arrPersonalizationToken['state']	= $subscriber_info['subscriber_state'];
		$arrPersonalizationToken['zip']		= $subscriber_info['subscriber_zip_code'];
		$arrPersonalizationToken['country']	= $subscriber_info['subscriber_country'];
		$arrPersonalizationToken['city']	= $subscriber_info['subscriber_city'];
		$arrPersonalizationToken['company']	= $subscriber_info['subscriber_company'];
		$arrPersonalizationToken['dob']		= $subscriber_info['subscriber_dob'];
		$arrPersonalizationToken['phone']	= $subscriber_info['subscriber_phone'];
		$arrPersonalizationToken['address']	= $subscriber_info['subscriber_address'];
		
		return 	$arrPersonalizationToken;
	}
	
	
	
	
}
?>