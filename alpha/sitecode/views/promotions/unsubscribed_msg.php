<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>BoldInbox: Unsubscribe</title>
<style>

.unsubscribe_box{width:600px;background:#ffffff; text-align: left !important; margin:40px auto;}
.unsubscribe_box h3{font-size: 16px;
  font-weight: bold;
  color: #000;
  padding: 15px 25px;
  font-family: arial;
  margin: 15px 0px;
  text-align: left !important;
  }
.unsubscribe_box label {
  -webkit-font-smoothing: antialiased;
  display: inline-block;
  font-weight: 300;
  font-size: 14px;
  padding: 0 0 7px;
  margin-left:40px; 
}
.unsubscribe_box .submit_button {
  vertical-align: middle;
  text-align: center;
  width: 30%;
  padding: 7px 0;
  line-height: 20px;
  font-size: 16px;
  display: block;
  cursor: pointer;
  margin: 0 auto 25px 40px;
  border: 1px solid #ccc;
  -webkit-box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.2), 0 1px 2px rgba(0, 0, 0, 0.05);
  -moz-box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.2), 0 1px 2px rgba(0, 0, 0, 0.05);
  box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.2), 0 1px 2px rgba(0, 0, 0, 0.05);
  -webkit-border-radius: 4px;
  -moz-border-radius: 4px;
  border-radius: 4px;
  -webkit-transition: all 0.2s linear;
  -moz-transition: all 0.2s linear;
  -ms-transition: all 0.2s linear;
  -o-transition: all 0.2s linear;
  color: #fff;
  text-shadow: 0 1px 0 rgba(0, 0, 0, 0.25);
  background-color: #ec1e11;
  background-image: -moz-linear-gradient(top, #ff3019, #cf0404);
  background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#ff3019), to(#cf0404));
  background-image: -webkit-linear-gradient(top, #ff3019, #cf0404);
  background-image: -o-linear-gradient(top, #ff3019, #cf0404);
  background-image: linear-gradient(to bottom, #ff3019, #cf0404);
  background-repeat: repeat-x;
  filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#ffff3019', endColorstr='#ffcf0404', GradientType=0);
  -ms-filter: "progid:DXImageTransform.Microsoft.gradient(enabled=false)";
  background-color: #cf0404;
  border-color: #51a351 #51a351 #387038;
  border-color: rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.25);
}
</style>
<script type="text/javascript" src="<?php echo $this->config->item('locker');?>js/jquery-1.5.1.min.js"></script>
<script type="text/javascript" language="javascript">
jQuery('.rd_feedback').live('click',function(e){
	var opt = jQuery(this).val();
	if(opt == 6){
		jQuery('#unsubscribed_feedback_text').attr('readonly',false);
	}else{	
		jQuery('#unsubscribed_feedback_text').attr('readonly',true);
		jQuery('#unsubscribed_feedback_text').val('');
	}	
});
jQuery('#btnSubmit').live('click', function(e){
	if(jQuery($('input[name=unsubscribed_feedback]:radio')).is(':checked')){
		var opt = jQuery($('input[name=unsubscribed_feedback]:radio:checked')).val();
	 	var opt_txt = jQuery('#unsubscribed_feedback_text').val();
		var cid_sid = jQuery('#cid_sid').val();
		jQuery.ajax({
			url: "/cprocess/unsubscribe_feedback/",
			type:"POST",
			data : 'opt='+opt+'&cid_sid='+cid_sid+'&opt_txt='+opt_txt,
			success: function(msg) {
				jQuery('.unsubscribe_box').html('<h3>'+msg+'</h3>');
			}
		});	
	}else{
		alert("Please select a reason");
	}	
});
</script>
<!--[body]-->
<div style="width:100%;text-align:center;margin:100px auto;">
  <div  class="thanks-box" style="width:100%;text-align:center;">
    <h3>You unsubscribed successfully from this mailing list.</h3>
      <?php //echo $msg; ?>
	  <br/>
      <?php if($cid_sid != ''){?>
        <h2>Unsubscribed accidentally? <a href="<?php echo base_url().'cprocess/resubscribe/'.$rc_logo.'/'.$cid_sid;?>">Click here to re-subscribe</a></h2>
        <br/>
      <?php }?>
    <hr/>
	<?php if($isFeedback == 0){?>
	<div class="thanks-msg unsubscribe_box">
		<h3>We'd love to know why you unsubscribed:</h3>
		<input type="hidden" name="cid_sid" id="cid_sid" value="<?php echo $cid_sid;?>" />
		<?php $arrUnsubscribeFeedbackTxt = config_item('unsubscribe_feedback');
		$i=1;
		foreach($arrUnsubscribeFeedbackTxt as $unsub_txt){
			echo "<label><input type='radio' name='unsubscribed_feedback' class='rd_feedback' value='$i'/> ". $unsub_txt."</label><br />";		
			$i++;
		}
		?>		
		<label><textarea name="unsubscribed_feedback_text" id="unsubscribed_feedback_text" readonly style="width:500px;height:100px;"></textarea></label>
		<input type="button" name="btnSubmit" id="btnSubmit" class="submit_button"  value="Submit" />
	</div>
	<?php } ?>
    <div class="gap"></div>
    <div class="gap"></div>
    <?php if($rc_logo==1){
      // echo '<a href="'. site_url("/").'"> <img src="'. $this->config->item('locker').'images/powered-by-logo-blue.png" alt="logo" title="logo" border="0"></a>';
	 
    } ?>
  </div>
</div>
</body>
</html>