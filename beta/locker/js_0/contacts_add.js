function importContact(typ) {
  var action = $('#sel_import_contacts').val();
  var terms =($('#terms').is(':checked'))? 1 : 0;    
  var list_id = $('#subscription_contact_one').val();
	$.blockUI({
      message: '<h3 style="z-index:2000;">Please wait...</h3>'
    });
	if(	terms == 0){
		  $('.subscriber_msg').html('You have not agreed to the terms & conditions');
		  $('.subscriber_msg').show(); 
		  $('.subscriber_msg').addClass('info');
		  $.unblockUI();
	} 
  var block_data = "";
  if (action == "import_file") {
	importUploadedContacts() ;
	return;
  } else if (action == "copy_pase_contacts") {    
    var pasted_contacts = $('#copy_csv').val().replace(/(\t)/gm, ",");
    var copy_arr = pasted_contacts.split('\n');
    if (copy_arr.length <= 0) copy_arr = pasted_contacts.split('\r');    
    if (copy_arr.length <= 0) copy_arr = pasted_contacts.split('\r\n');
    if (copy_arr.length > 500) {
       $('.subscriber_msg').html("Use \"Upload a file\" for contact import.");
		$('.subscriber_msg').addClass('info');
		return; // exit from this function
    }
    var pasted_contacts = pasted_contacts.replace(/"/g, "");
    pasted_contacts = escape(pasted_contacts);
    block_data += "action=copy_pase_contacts&subscription_id="+list_id+"&copy_csv=" + pasted_contacts + "&terms=" + terms;
  }else if (action == "ony_by_one") {
    block_data += "action=ony_by_one&subscription_id=" + list_id + "&terms="+terms +"&"+ $("#contact_import_form").serialize();
  }   
    $.ajax({
      url: base_url + "newsletter/subscriber/create/" + list_id,
      type: "POST",
      data: block_data,
      success: function(data){
        var data_arr = data.split(":", 2);
        if (data_arr[0] == "error") {
          $('.subscriber_msg').html('');
          $('.subscriber_msg').show();
          $('.subscriber_msg').html(data_arr[1]);
          $('.subscriber_msg').addClass('info');
          $.unblockUI();
        } else if (data_arr[0] == "copy_success") {
          $('.subscriber_msg').show();
          $('.subscriber_msg').html('Import is under progress.......');
          $('.subscriber_msg').addClass('info');
          window.location.href = base_url + 'newsletter/contacts/index/' + list_id;
        } else if (data_arr[0] == "success") {
          $('.subscriber_msg').show();
          $('.subscriber_msg').html('Contacts added successfully.');
          $('.subscriber_msg').addClass('info');
          setTimeout(function() {$('.subscriber_msg').fadeOut();}, 4000);
           
          window.location.href = base_url + 'newsletter/contacts/index/' + list_id;
          
          $.unblockUI();

        }
      }
    });
 
  return false;
}

function importUploadedContacts() {  
  var terms =($('#terms').is(':checked'))? 1 : 0;
 var list_id = $('#subscription_contact_one').val();
  $.ajaxFileUpload({
    url: base_url + 'newsletter/subscriber/importcsv/' + list_id + '/' + terms,
    secureuri: false,
    fileElementId: 'subscriber_csv_file',
    dataType: 'json',
	error: function (data, status, e){		 
                    $(".subscriber_msg").html(e);
					$('.subscriber_msg').addClass('info');
    },
    success: function(data, status) {

      if (typeof(data.error) != 'undefined') {
        if (data.error != '') {
			var msg = data.error;			
			$('.subscriber_msg').html(decodeURIComponent((msg+'').replace(/\+/g, '%20')));		  
			$('.subscriber_msg').addClass('info');
			$.unblockUI();
        } else {

          var msg = "Your list import is under process. Larger lists will take longer. However, navigating away from this page will not interrupt the upload. After completion of process, you will be informed by email.";
          $('.subscriber_msg').html(msg);
          $('.subscriber_msg').addClass('info');


          var data_msg = data.msg;
          var data_arr = data_msg.split(":", 2);
		  
          $.unblockUI();

          window.location.href = base_url + 'newsletter/contacts/index/' + $('#subscription_select').val();
        }
      }
    }
  });
  return false;
}

function checkImportStatus() {
  $.ajax({
    url: base_url + 'newsletter/subscriber/checkImportStatus/',
    success: function(data) {
      if (data == 0) {
        window.location.reload();
        return;
      }
    }
  });
}


function reinit() {
  $(".fancybox").fancybox({
    'autoDimensions': false,
    'transitionIn': 'fade',
    'transitionOut': 'fade',
    'height': 'auto',
    'width': '600',
    'centerOnScroll': true,
    'scrolling': false
  });
  $(".fancybox_delete").fancybox({
    'autoDimensions': false,
    'transitionIn': 'fade',
    'transitionOut': 'fade',
    'height': 'auto',
    'width': '600'
  });
}

/*
	fancyAlert to display message
*/

function fancyAlert(msg) {
  $.fancybox({'content': "<div style=\"margin:20px;width:240px;font-weight:bold;\">" + msg + "</div>"});
}
// Removes leading whitespaces


function LTrim(value) {
  var re = /\s*((\S+\s*)*)/;
  return value.replace(re, "$1");
}
// Removes ending whitespaces


function RTrim(value) {
  var re = /((\s*\S+)*)\s*/;
  return value.replace(re, "$1");
}
