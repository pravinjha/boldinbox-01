
$(document).ready(function(){

	$('#create_new_list').on('click',function(){		
		displayAlertMessage('Overlay Title','<span style = "color:#FF0000;">Loading, please wait...</span><br />Overlay Content Here...','0',true,820,520,false,'');
		//$( "#message" ).on('load', "any_page.php?param=var" );
	});
	
	$('#sel_import_contacts').change(function(){
		$('.cl_imp_cnt').removeClass('db');
		$('.cl_imp_cnt').addClass('dn');
		$('#'+this.value).addClass('db');		
	});
	
	$('.transparent_place_holder').click(function(){	
		$('#contacts_selected').html($(this).attr('id'));
		$('#doit').val($(this).attr('rel'));
		$('.cl_vw_cnt,.cl_imp_cnt').removeClass('db');
		$('.cl_vw_cnt,.cl_imp_cnt').addClass('dn');		
		$('#'+$(this).attr('rel')).addClass('db');
	});
	
	$('#show_sample_file').on('click',function(){		
		displayAlertMessage('Excel / CSV FIle Sample View','<span style = "color:#FF0000;">Loading, please wait...</span>','0',true,720,550,false,'');
		$( "#message" ).html( "<img src = '"+locker+"images/sample_file.jpg' /><br /><div align = 'center' style = 'margin:20px;'><a href = 'javascript:void(0);' onclick='window.location = \""+locker+"images/sample.csv\"' style = 'font-size:14px;font-weight:bold;'>Download Sample File</a></div>" );
	});
	
	$('.add_another_field').on('click',function(){		
		$('#add_another_field').removeClass('dn');
		$('#add_another_field').addClass('db');
		$('#add_another_field_choice').val('');
	});
	
	$('.cancel_added_another_field').on('click',function(){		
		$('#added_another_field_'+this.id).remove();
		$('#add_another_field_choice').val('');	
	});
	
	$('.cancel_add_another_field').on('click',function(){		
		$('#add_another_field').removeClass('db');
		$('#add_another_field').addClass('dn');
		$('#add_another_field_choice').val('');
		$('#custom_field_creation').removeClass('db');
		$('#custom_field_creation').addClass('dn');
	});
	
	$('#add_another_field_choice').change(function(){
		var field_name = this.value;
		var fldname = field_name.toLowerCase();
		if(field_name != 'Custom'){
			$('#custom_field_creation').addClass('dn');
			$('#custom_field_creation').removeClass('db');
			var field_html = "<div id = 'added_another_field_"+field_name+"'><div class = 'contacts_import_list_label'>"+field_name+"</div><div class = 'contacts_import_list' style = 'position:relative;'><input type = 'text' value = '' placeholder =  'Enter "+field_name+" here' name="+fldname+"  id="+fldname+" /><a href = 'javascript:void(0);' id = '"+field_name+"' class = 'cancel_added_another_field' style = 'position:absolute;top:5px;right:-35px;'><img src = '"+locker+"images/icons/trash_can.png' alt = 'Remove this field' title = 'Remove this field' /></a></div><div class = 'clear10'></div></div>";
			$('#cnt_edit_info_fields').append(field_html);
		}else{
			$('#custom_field_creation').removeClass('dn');
			$('#custom_field_creation').addClass('db');
		}
		$('#add_another_field_choice').val('');
	});
	
		
	$(document).on('click','#save_cutom_field',function(){	
		var cf_value = $('#custom_field_value').val();
		var fldname = 'custom_'+cf_value.toLowerCase();
		var field_html = "<div id = 'added_another_field_"+cf_value+"'><div class = 'contacts_import_list_label'>"+cf_value+"</div><div class = 'contacts_import_list' style = 'position:relative;'><input type = 'text' value = '' placeholder =  'Enter "+cf_value+" here' name="+fldname+" id="+fldname+" /><a href = 'javascript:void(0);' id = '"+cf_value+"' class = 'cancel_added_another_field' style = 'position:absolute;top:5px;right:-35px;'><img src = '"+locker+"images/icons/trash_can.png' alt = 'Remove this field' title = 'Remove this field' /></a><div class = 'clear10'></div><div class = 'clear10'></div></div>";
		$('#cnt_edit_info_fields').append(field_html);
		$('#custom_field_creation').addClass('dn');
		$('#custom_field_creation').removeClass('db');
		$('#custom_field_value').val('');
	});
	
	$(document).on('click','#cancel_cutom_field',function(){	
		$('#custom_field_creation').addClass('dn');
		$('#custom_field_creation').removeClass('db');
		$('#custom_field_value').val('');
	});
	
	
	// Contacts-List dropdown on contacts-list page
	$(document).on('click','#contacts_selected',function(){
		$('.move_list').slideUp();
		$('.copy_list').slideUp();
		$('.contacts_select_show').slideToggle();		
	});
	$(document).on('click','.contacts_select_show_list_name_a',function(){	
		var lid = this.id;
		$('#contacts_selected').html($(this).find('a').html());
		$('.contacts_select_show').slideUp();
		showMoveToCopyToLists(lid);
		if(lid=='bounce_count'){
			display_contacts(document.form1.subscription_selected_id.value,document.getElementById('email_search').value,"","",0,1);			
		}else if(lid=='unsubscribe_count'){
			display_contacts(document.form1.subscription_selected_id.value,document.getElementById('email_search').value,"","",1);				
		}else if(lid=='complaint_count'){
			display_contacts(document.form1.subscription_selected_id.value,document.getElementById('email_search').value,"","",2);			
		}else if(lid=='removed_count'){
			display_contacts(document.form1.subscription_selected_id.value,document.getElementById('email_search').value,"","",5);			
		}else{
			display_contacts(lid);
		} 
	});
	
	/* $(document).on('click','.main-content',function(event){		
		if(event.target.className != 'contacts_selected_c' && event.target.className != 'contacts_select_show' && event.target.className != 'contacts_select_show_head' && event.target.className != 'contacts_select_show_list_name' && event.target.className != 'contacts_dnm' && event.target.className != 'dropDownCustom' && event.target.className != 'dropDownCustomSelected' && event.target.className != 'dropDownCustomOpened' && event.target.className != 'dropDownCustomSelected contacts_selected_c'){
			$('.contacts_select_show').slideUp();			
		}
	});
	 */
	$(document).on('click','.list_edit',function(){		
		var lid = $(this).parent().parent().find('a').attr('id');
		var lname = $(this).parent().parent().find('span').text();		
		displayAlertMessage('Edit List Name','<span style = "color:#FF0000;">Loading, please wait...</span><br />Overlay Content Here...','0',true,400,170,false,'');
		$('.subscription_msg').html('');
		$( "#message" ).html( $('#subscription_edit').html() ); 
		$('#messageBox').find('#hidListId').val(lid);
		$('#messageBox').find('#subscription_title').val(lname);
	});
	
	
	

	
	// DIY PAGE LEFT MENU
	// $('.blockrefh').on('click',function(){		
	$('.blockrefh').click(function(){		
		$('.blockrefh').removeClass('active');
		$('.blockrefh span').html('+');
		$('.blockref').removeClass('db');
		// $('.blockref').addClass('dn');
		$('.blockref').slideUp();
		// $('#'+this.id+'n').addClass('db');
		$('#'+this.id+'n').slideDown();
		$(this).addClass('active');
		$(this).find('span').html('-');
		
		if(this.id == 'DIY_images_show'){
			organizeImageBank();
		}
		
	});

	
	
	
	// close alert
	$('#message_close2,#message_close').on('click',function(){
		$.modalBox.close();
	});
	
	
		

	
});
function labelFormatter(label, series) {
	return "<div style='font-size:8pt; text-align:center; padding:2px; color:white;'>" + label + "<br/>" + Math.round(series.percent) + "%</div>";
}
function updateFooterAddress( submitButton ){
	//displayAlertMessage('Add / Update Your Physical Address.','','0',true,480,320,false,'');
	//$( "#message" ).html( $("#user_account_option").html() ); 
	//$(document).on('load', '#message', base_url+"promotions/user_address" ); 
	$.get( base_url+"promotions/user_address", function( data ) {	  
	  displayAlertMessage('Add / Update Your Physical Address.', data, submitButton, true,480,320,false,'');
	});
}
function save_user_info(){

  var block_data;
  block_data='company='+encodeURIComponent($('#messageBox').find('#company_name').val())+'&address_line_1='+encodeURIComponent($('#messageBox').find('#address').val())+'&city='+encodeURIComponent($('#messageBox').find('#city').val())+'&state='+encodeURIComponent($('#messageBox').find('#state').val())+'&zipcode='+encodeURIComponent($('#messageBox').find('#zip').val())+'&country='+encodeURIComponent($('#messageBox').find('#country').val())+'&country_custom='+encodeURIComponent($('#messageBox').find('#country_custom').val());  
  jQuery.ajax({
    url: base_url+"account/user_info",
    type:"POST",
    data:block_data,
    success: function(data) {
      var data_arr=data.split(':');
      if(data_arr[0]=="error"){
        $('#messageBox').find('.msg').html(data_arr[1]);
      }else{
		var cntry = ($('#messageBox').find('#country option:selected').text() == 'Custom')?$('#messageBox').find('#country_custom').val() :  $('#messageBox').find('#country option:selected').text();
		 var faddr ="&copy; "+$('#messageBox').find('#company_name').val()+"<br/>"+$('#messageBox').find('#address').val()+', '+$('#messageBox').find('#city').val()+', '+$('#messageBox').find('#state').val()+' - '+$('#messageBox').find('#zip').val()+'  '+cntry;
		 
        $('#footer_address').html(faddr); 
        // $('#messageBox').modal('hide');
    
      }
    }
  });
}
function showCustom(dpdCountry){
	if('245' == dpdCountry.value){
	$('span#country_custom_div').show();
	}else{
	$('span#country_custom_div').hide();
	}
}