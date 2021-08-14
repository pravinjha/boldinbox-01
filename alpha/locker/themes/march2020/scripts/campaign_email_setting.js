
  function submitFrm(){
    $('save_email').val('1');
    $('#spin').show();
    document.form_campaign_send.submit();
  }
  function scheduleFrm(){
    $('save_email').val('1');
	$('#send_now').val('0');
	displayAlertMessage('Schedule Your Campaign To Send Later','','0',true,350,250,false,'');
	$( "#message" ).html( $(".schedule_delivery").html() );
	$('#scheduled_date').datepicker({ format: 'mm-dd-yyyy',       todayBtn: 'linked'});
      
    //$('.schedule_delivery').toggle();
  }