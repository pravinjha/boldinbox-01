$.fn.spinner=function(data){$.each(["show","hide"],function(i,e){var $e=$.fn[e];$.fn[e]=function(){this.trigger(e);return $e.apply(this,arguments)}});var $spinner={color:"red",background:"rgba(0,0,0,0.5)",html:"&#x21BB;"};this.init=function($spinner){return "<style id='data-spinner-style'>\n"+"body.unselectable {\n"+"\t-webkit-user-select: none;"+"\t-moz-user-select: none;"+"\t-ms-user-select: none;"+"\t-o-user-select: none;"+"\tuser-select: none;\n"+"}\n"+"[data-spinner-layer] {\n"+"\tdisplay: none; position: fixed; top: 0; left: 0;"+"\tbackground: "+$spinner.background+";"+"\twidth: 100%; height: 100%; padding: 0; margin: 0;z-index:999\n"+"}\n"+"[data-spinner-bar] {\n"+"\tcolor: "+$spinner.color+";"+"\tposition: absolute; top: calc(50% - 30px); left: calc(50% - 15px); font-weight: bold; font-size: 40px;"+"}\n"+"</style>"}
$.extend($spinner,data);var $style=this.init($spinner);return this.each(function(){if($("#data-spinner-style").length===0){$("head").append($style)}
$spin=$(this);$spin.attr({"data-spinner-layer":"","data-spinner-body":$("body").css("overflow")}).append("<div data-spinner-bar>"+$spinner.html+"</div>").live("show",function(){$("body").css("overflow","hidden")}).live("hide",function(){$("body").css("overflow",$spin.attr("data-spinner-body")).removeClass("unselectable")});$("body").addClass("unselectable")})}
// overlay-ends	

function showList(){
	if($('#subscription_contact_one').length){ 
		$.ajax({ url: site_url+"contacts/showListsDpd", type:"POST", data:"", success: function(data) { $('#subscription_contact_one').html(data).multiselect("refresh"); }  });
	}else if($('.contacts_select_show').length){ 
	$.ajax({ url: site_url+"contacts/showLists", type:"POST", data:"", success: function(data) { 
							$('.contacts_select_show').html(data); 
							var selid = $('#subscription_selected_id').val();
							$('#contacts_selected').html($(data).find('#'+selid).html());	
							}  
		});
	} 	
	$("#spin").hide();
} 
function showMoveToCopyToLists(lid){
	var main_list = 0 - memid;
		var dnm_str = "<li onclick='unsubscribe_list("+main_list+",\"unsubscribe\")' name='"+main_list+"' class='do-not-mail-option' ><a href='javascript:void(0);' style = 'font-size:15px;font-weight:700;background:none;box-shadow:none;color:#CC0000;'>Do Not Mail List</a></li>";	
	  $.ajax({ url: site_url+"contacts/showMoveToCopyToLists/"+lid, type:"POST", data:"", success: function(data) {
					if(lid < 0)	$('.move_list').html(dnm_str);	else $('.move_list').html(data+dnm_str);
					$('.copy_list').html(data.replace('move','copy'));				
				}  
			});	 
} 

$(".listdelete").live('click',function(event){
	var lid = $(this).attr('id').substr(5);
	displayAlertMessage('Delete List','','0',true,450,150,false,'');
	$( "#message" ).load( site_url+'contacts/delete/'+lid ); 
});

	function display_contacts(subscription_id,srch_email,order_by,order_by_column,unsubscribe,bounce,page_start,copied_or_moved){
	$("#spin").show();
		page_start=(!page_start)?0: page_start;
		subscription_id = (subscription_id)?subscription_id:(0-memid);
		
		$("#subscription_selected_id").val(subscription_id);
		$('#delete_subscriber').attr('href',base_url+'subscriber/subscriber_delete/'+subscription_id);
		var block_data="";
		block_data += ($("#email_search").val())?"srch_email="+$("#email_search").val() : '';
		//block_data += (srch_email)?  "srch_email="+srch_email : '';
		block_data += (order_by)? "&order_by="+order_by + "&order_by_column="+order_by_column : '';
		
		$('.contacts_tools').show();
		if(unsubscribe==5){
			block_data+="&unsubscribe="+unsubscribe;
			$('#action_notmail').val('removed');			
		}else if(unsubscribe==1){
			block_data+="&unsubscribe="+unsubscribe;
			$('#action_notmail').val('unsubscribe');				
		}else if(unsubscribe==2){
			block_data+="&complaints="+unsubscribe;
			$('#action_notmail').val('complaints');
		}
		if(bounce==1){
			block_data+="&bounce="+bounce;
			$('#action_notmail').val('bounce');
		}

		$.ajax({
		  url: base_url+"subscriber/subscriber_list/"+subscription_id+'/'+page_start,
		  type:"POST",
		  data:block_data,
		  success: function(data) {
				var data_arr=data.split("|-|");
				$('.contacts_change').remove();
				$('.tbl-contacts').removeClass("loading-table").html(data_arr[1]);
				$('.pagination_div').html(data_arr[0]);
				//$('.show_records_per_page').html(data_arr[7]);
				$('.editing-theme-box').removeClass("active");
				if((unsubscribe!=5)&&(unsubscribe!=1)&&(unsubscribe!=2)&&(bounce!=1)){
					$('#'+subscription_id).addClass("active");
				}
				if(unsubscribe==5){
					$('.contacts_tools').hide();
					$("input.check-boxalign").attr("disabled", true);					
					$(".cl_2,.cl_4").find("a").attr("href", "javascript:void(0)").removeClass("fancybox_delete").addClass( "restrictIt");
					$('.contacts_list_row_b,.contacts_list_row_w').css('background-color','#f8f8f8');	
					$('#action_notmail').val('removed');
					$('.list_title').find('span').html('Removed');
				}else if(unsubscribe==1){
					$('.contacts_tools').hide();
					$("input.check-boxalign").attr("disabled", true);					
					$(".cl_2,.cl_4").find("a").attr("href", "javascript:void(0)").removeClass("fancybox_delete").addClass( "restrictIt");
					$('.contacts_list_row_b,.contacts_list_row_w').css('background-color','#f8f8f8');					
					$('#action_notmail').val('unsubscribe');
					$('.list_title').find('span').html('Unsubscribed');
				}else if(unsubscribe==2){
					$('.contacts_tools').hide();
					$("input.check-boxalign").attr("disabled", true);					
					$(".cl_2,.cl_4").find("a").attr("href", "javascript:void(0)").removeClass("fancybox_delete").addClass( "restrictIt");					
					$('.contacts_list_row_b,.contacts_list_row_w').css('background-color','#f8f8f8');
					$('#action_notmail').val('complaints');
					$('.list_title').find('span').html('Complaints');
				}else if(bounce==1){
					$('.contacts_tools').hide();
					$("input.check-boxalign").attr("disabled", true);					
					$(".cl_2,.cl_4").find("a").attr("href", "javascript:void(0)").removeClass("fancybox_delete").addClass( "restrictIt");
					$('.contacts_list_row_b,.contacts_list_row_w').css('background-color','#f8f8f8');
					$('#action_notmail').val('bounce');
					$('.list_title').find('span').html('Bounced');
				}else{				
					$('#action_notmail').val('');
					$('.list_title').find('span').html($('#subscription_title_'+subscription_id).val());
				}
				//$('#action').val('');//
				reinit();
				$('.do_not_mail_list').slideUp();
				$('.move_list').slideUp();
				$('.copy_list').slideUp();
				$('.pagination_div').show(); 
				$('.show_records_per_page').show();
				
				if(copied_or_moved === undefined || copied_or_moved == '')				
				setTimeout(function() {   $('#msg').html('');}, 5000);				
				$('#removed_count').html('Removed ('+data_arr[6]+')');
				$('#unsubscribe_count').html('Unsubscribed ('+data_arr[2]+')');
				$('#bounce_count').html('Bounced ('+data_arr[3]+')');
				$('#complaint_count').html('Complaints ('+data_arr[4]+')');
				$('#visible_contacts_count').val(data_arr[5]);
$("#spin").hide();
		  }
		});

		$('.tbl-contacts').removeClass('donotmaillist');
		
		
	}
$('.restrictIt').live('click', function(){bibAlert('This contact is inactive. You can not edit or delete it.');});
	
function newPageSize(x){
	var ps = $('#psize').val();
	var dnmType = 0;
	var isBounce = 0;
	  
	if($('#action_notmail').val() == 'unsubscribe'){
		dnmType = 1;
	}else if($('#action_notmail').val() == 'complaints'){
		dnmType = 2;
	}else if($('#action_notmail').val() == 'bounce'){
		isBounce = 1;  
	}
  
   jQuery.ajax({
		url:  base_url+"emailreport/ajx_setpagesize/",
		type:"POST",
		data:"ps="+ps,
		success: function(data) {
		
			display_contacts($('#subscription_selected_id').val(),$('#email_search').val(),'','',dnmType,isBounce,0,'');
			if($('#action_notmail').val() == 'unsubscribe' || $('#action_notmail').val() == 'complaints' || $('#action_notmail').val() == 'bounce'){
				$('.tbl-contacts').addClass('donotmaillist');
				$('.move_subscriber').parent().addClass('disabled');
				$('.delete_subscriber').addClass('disabled_select');
				$('.select_page').addClass('disabled_select');
				$('.select_list').addClass('disabled_select');  
			}
		}
	});
  }

  
  function slideMenu(action){
	$('.contacts_select_show').slideUp();
	if(!($('.move_subscriber_list').parent().hasClass('disabled'))){
		if($('#action_notmail').val()!='unsubscribe'){
			if(action=="move_list"){
				$('.move_list').slideToggle();
				$('.copy_list').slideUp();
				$('.do_not_mail_list').slideUp();
			}
		}
	}
	if(!($('.copy_subscriber_list').parent().hasClass('disabled'))){
		if($('#action_notmail').val()!='unsubscribe'){
			if(action=="copy_list"){
				$('.move_list').slideUp();
				$('.copy_list').slideToggle();
				$('.do_not_mail_list').slideUp();
			}
		}
	}
}


function saveSubscriptionTitle(subscription_id){
	$("#spin").show();
	var new_title = $('#subscription_text_'+subscription_id).val();
	var block_data="subscription_title="+escape(new_title)+"&action=submit&subscription_id="+subscription_id;
	$.ajax({
		url: base_url+"contacts/edit/"+subscription_id,
		type:"POST",
		data:block_data,
		success: function(data) {
			data_arr=data.split(":");
			if(data_arr[0]=="error"){				
				$('#subscription_text_'+subscription_id).val(data_arr[2]);
				$('#subscription_text_'+subscription_id).focus();
			}else{
				$('#subscription_id_'+subscription_id).html($('#subscription_text_'+subscription_id).val().substr(0,19));
				$('#subscription_id_'+subscription_id).show();
				$('#subscription_text_'+subscription_id).hide();
				$('#subscription_text_'+subscription_id).parents('.editing-theme-box').find('.list-icons').show();
				$('#subscription_text_'+subscription_id).parents('.editing-theme-box').find('.right-no').show();
				$('#subscription_text_'+subscription_id).parents('.editing-theme-box').find('.edit_subscription').hide();
				$('#subscription_title_'+subscription_id).val(new_title);
				$('.list_title').find('span').html(new_title);
				showList(); //display_subscription(subscription_id);
			}
			$("#spin").hide();
		}
	});
}

// ajax request for campaign-listing pg.
$(".pagination a").live('click',function(event){
	if(!$(this).hasClass('selected')){
		var block_data="";
		if($('#checked').val()!=""){
			block_data="action="+$('#action').val()+"&checked="+$('#checked').val();
		}else{
			block_data="action="+$('#action').val();
		}
		if($('#action_notmail').val()=="unsubscribe"){
			block_data+="&unsubscribe=1";
		}if($('#action_notmail').val()=="bounce"){
			block_data+="&bounce=1";
		}if($('#action_notmail').val()=="complaints"){
			block_data+="&complaints=2";
		}
		block_data+="&srch_email="+$('#email_search').val();
		if($('#order_by_paging').val()){
			block_data+="&order_by="+$('#order_by_paging').val()+"&order_by_column="+$('#order_by_column').val();
		}
		$('.tbl-contacts').addClass("loading-table").append($(".loading").html());
		
		$.ajax({
		   type: "POST",
		   data: block_data,
		   url: $(this).attr('href'),
		   success: function(data){ 
				// for campaign pages
				$('.campaigns_container').html(data);
				// for contacts pages
				var data_arr=data.split("|-|");
				$('.contacts_change').remove();
				$('.tbl-contacts').removeClass("loading-table").html(data_arr[1]);
				$('.campaigns_container').html(data_arr[0]);
				//$('.show_records_per_page').html(data_arr[7]);
				reinit();
		   }
		});
	}
	//$(".pagination").find('.selected').removeClass('selected');
	//$(this).addClass('selected');
	//reinit();
    return false; // don't let the link reload the page
});




function updateChecked(type,is_check){

	if($('.select_'+type).hasClass('disabled_select')){
		return false;
	}
	if(type=='page'){
		 
		if($('#action').val()!=type){
			$('#msg').html($('#visible_contacts_count').val()+' Contacts selected');
			$('#action').val(type);
			$('#checked').val('');
			$('.check-boxalign').attr('checked',true);
			$('.check-boxalign').attr('disabled',true);
			
			//$('#select_page').onclick = function(){updateChecked('page',false);}
			//$('#select_list').onclick = function(){updateChecked('list',true);}
		}else{
			$('#msg').html('');
			$('#action').val('');
			$('.check-boxalign').attr('checked',false);
			$('.check-boxalign').attr('disabled',false);
			
			//$('#select_page').onclick = function(){updateChecked('page',true);}
			//$('#select_list').onclick = function(){updateChecked('list',true);}
		}		

	}else{
	 
		if($('#action').val()!=type){
		
			$('#msg').html($('.check-boxalign').length+' Contacts selected');
			$('#action').val(type);
			$('.check-boxalign').attr('checked',true);
			//$('.check-boxalign').attr('disabled',true);			
		}else{
			$('#msg').html('');			
			$('#action').val('');
			$('.check-boxalign').attr('checked',false);
			//$('.check-boxalign').attr('disabled',false);			
		}	
	}
}




function Search_Array(ArrayObj, SearchFor){
  var Found = false;
  for (var i = 0; i < ArrayObj.length; i++){
    if (ArrayObj[i] == SearchFor){
      return (++i);
      var Found = true;
      break;
    }
    else if ((i == (ArrayObj.length - 1)) && (!Found)){
      if (ArrayObj[i] != SearchFor){
        return false;
      }
    }
  }
}
function submit_frm(subscription_id,action){
	$("#spin").show();
	var page_id=0;
	if(($('.check-boxalign').length>1)&&($('.pagination').find('.selected').html()>1)){
		page_id=25*($('.pagination').find('.selected').html()-1);
	}
	var block_data="";
	if(action=="copy"){
		document.form1.contact_list_action.value='copy_to_list';
		block_data+="select_subscription="+subscription_id+"&action_from="+document.form1.subscription_selected_id.value+"&+action="+$('#action').val()+"&";
		$('.copy_list').slideUp();
	}else if(action=="move"){
		document.form1.contact_list_action.value='move_to_list';
		block_data+="select_subscription="+subscription_id+"&action_from="+document.form1.subscription_selected_id.value+"&+action="+$('#action').val()+"&";
		$('.move_list').slideUp();
	}else if(action=="unsubscribe"){
		document.form1.contact_list_action.value='unsubscribe_list';
		block_data+="action_from="+document.form1.subscription_selected_id.value+"&+action="+$('#action_notmail').val()+"&";
		$('.move_list').slideUp();
	}
	block_data+=$('#form1').serialize();
	$('.tbl-contacts').addClass("loading-table").append($(".loading").html());

		$.ajax({
		  url: base_url+"subscriber/subscriber_action/",
		  type:"POST",
		  data:block_data,
		  success: function(data){
			var data_arr=data.split("|-|");
			
			if(data_arr.length > 0){
				$('#msg').html(data_arr[8]);
				//bibAlert(data_arr[8]);
			}else{
				$('#msg').html(data);
				//bibAlert(data);
			}
			showList(); //display_subscription(document.form1.subscription_selected_id.value);			
			//display_contacts(document.form1.subscription_selected_id.value,document.getElementById('email_search').value,'','','','',page_id);			
		  }
		});

	return false;
}
function unsubscribe_list(subscription_id,action){
	if($('#action').val() == 'page')
	$('#total_unsubscribe_count').html($("#visible_contacts_count").val());
	else
	$('#total_unsubscribe_count').html($("input[type=checkbox]:checked").length);

	$('#unsubscriber_subscription_id').val(subscription_id);
	var unsubscriber_box=$('.unsubscriber_box').html();
	displayAlertMessage('Please Confirm!',unsubscriber_box,'0',true,450,150,false,'');	
}
function submit_unsubscribe_form(){
	var subscription_id=$('#unsubscriber_subscription_id').val();
	var action="unsubscribe";
	submit_frm(subscription_id,action);
	$.modalBox.close();
}

$("#btnSearch").live('click',function(e){
e.preventDefault();
//function search_form(){
	$('#search_key').val($('#email_search').val());
	if($('#action_notmail').val()=="unsubscribe"){ 
		display_contacts(document.form1.subscription_selected_id.value, document.getElementById('email_search').value,'','',1,0,0,'');
	}else if($('#action_notmail').val()=="bounce"){
		display_contacts(document.form1.subscription_selected_id.value, document.getElementById('email_search').value,'','',0,1);
	}else if($('#action_notmail').val()=="complaints"){		
		display_contacts(document.form1.subscription_selected_id.value,document.getElementById('email_search').value,"","",2);
	}else{
		display_contacts(document.form1.subscription_selected_id.value, document.getElementById('email_search').value);
	}
	//$('.tbl-contacts').addClass('searchmaillist');
	if($('#action_notmail').val() == 'unsubscribe' || $('#action_notmail').val() == 'complaints' || $('#action_notmail').val() == 'bounce'){
		$('.tbl-contacts').addClass('donotmaillist');
		$('.move_subscriber').parent().addClass('disabled');
		$('.delete_subscriber').addClass('disabled_select');
		$('.select_page').addClass('disabled_select');
		$('.select_list').addClass('disabled_select');  
	}
	return false;
});


function createList(){	
		$('.subscription_msg').html('');	
		displayAlertMessage('Create a new contacts list',$("#add-list").html(),'0',true,400,170,false,'');		 
}
function ajaxSubscriptionFrm(frm){
	$("#spin").show();
	//var frm=document.frmLogin;
		 var block_data="";
		block_data+="action=submit&"+'&subscription_title='+escape(frm.subscription_title.value);
		   $.ajax({
		  url: base_url+"contacts/create/",
		   type:"POST",
			data:block_data,
		  success: function(data) {
		  $("#spin").hide();
		  var data_arr=data.split(":", 2);
		  if(data_arr[0]=="error"){
			$('.subscription_msg').html(data_arr[1]);
			//setTimeout(function(){$.modalBox.close();} , 2000);
		  }else if(data_arr[0]=="success"){			
			frm.subscription_title.value="";
			showList();
			$('.subscription_msg').html("List created successfully.");			
			setTimeout(function(){$.modalBox.close();} , 2000);			
		  }
		  }
		});
		//alert(block_data);
		return false;
}
function exportcsv(){
	var search_key = $('#search_key').val();
	var sid = $('#subscription_selected_id').val();
	
	document.form1.action=base_url+"subscriber/exportcsv/"+sid+'/'+search_key;
	
	document.form1.submit();
}
function exportcsv_0(){
	var search_key = $('#search_key').val();
	window.location = base_url+"subscriber/exportcsv/"+ document.form1.subscription_selected_id.value +'/'+search_key+'/';
}
function exportcsv_do_not_mail_list(){
	window.location = base_url+"subscriber/exportcsv_do_not_mail_list/"+$('#action_notmail').val();
}
 
 function checkImportStatus() {


      $.ajax({
        url:  base_url+'subscriber/checkImportStatus/',
        success: function (data) {
          if (data == 0) {
            window.location.reload();
			return;
          }
        }
      });

}


function reinit() {
	$(".fancybox_delete").click(function(){				
		$('#action').val('');
		$('.check-boxalign').attr('checked','');
		$(this).parent().parent().find(".check-boxalign").attr('checked','checked');
		$this = $(this);		
		displayAlertMessage('Please Confirm!','','0',true,450,150,false,'');
		$( "#message" ).load( $this.attr('href') ); return false; 
		$.modalBox.close();
	});
}

function order_by(order_by_column){
	var order_by=$('#order_by').val();
	$('#order_by_paging').val(order_by);
	if(order_by=="asc"){
		$('#order_by').val('desc');
	}else{
		$('#order_by').val('asc');
	}
	$('#order_by_column').val(order_by_column);
	var subscription_id=$('#subscription_selected_id').val();
	if($('#action_notmail').val()=="unsubscribe"){
		display_contacts(subscription_id,"",order_by,order_by_column,1);
	}else if($('#action_notmail').val()=="complaints"){
		display_contacts(subscription_id,"",order_by,order_by_column,2);
	}else if($('#action_notmail').val()=="bounce"){
		display_contacts(subscription_id,"",order_by,order_by_column,0,1);
	}else{
		display_contacts(subscription_id,"",order_by,order_by_column);
		$('#action_notmail').val('');
	}
}
/*
		ajax call to delete contact
*/

function delCampaign(enc_cid){
		$.ajax({
				url: base_url+"promotions/update_campaign/"+enc_cid+'/delete',
				type:"POST",
				success: function(data) { $( "#message" ).html( "<p class='msg info'>"+data+"</div>" ); 	window.location.reload();}
			});
}
$(".delete-campaign").live('click',function(event){
var cid = $(this).attr('name');
	bibConfirm("Are you sure to delete this campaign. All the stats will also be deleted associated to this campaign.",'delCampaign("'+cid+'")');
	//bibConfirm("Are you sure?",function(){$.ajax({url: base_url+"promotions/update_campaign/"+cid+'/delete', type:"POST", success: function(data){ window.location.reload();} }); });
});
$(".delete-row").live('click',function(event){
	$(this).fastConfirm({
		position: "top",
		questionText: "Are you sure you want <br/>to delete Contact?",
		onProceed: function(trigger) {
			var subscriber_id=$(trigger).attr('name');
			 $.ajax({
				url: base_url+"subscriber/delete/"+subscriber_id,
				type:"POST",
				success: function(data) {
					$(trigger).parents('.contacts_change').remove();
				}
			});
		},
		onCancel: function(trigger) {
		}
	});
});

/*
	checked checkbox on click of delete link
*/
$(".fancybox_delete").live('click',function(event){
	$('#action').val('');
	$('.check-boxalign').attr('checked','');
	$('.check-boxalign').removeAttr('disabled');
	$('.contacts_change').css("background-color","");
	$(this).parents(".contacts_change").find('.check-boxalign').attr('checked','checked');
});
/*
		export csv confirmation
*/
$(".export_csv").live('click',function(event){	  
	if($('#action_notmail').val()!=""){
		exportcsv_do_not_mail_list();
	}else{
		exportcsv();
	}	 
});
/*
	edit subscription title
*/
$(".subscriber_edit").live('click',function(event){
	var subscription_id=$(this).attr('name');
	$('.list-icons').show();
	$('.subscription_text').hide();
	$('.subscription_strong').show();
	$('.right-no').show();
	$('.edit_subscription').hide();
	$(this).parents('.editing-theme-box').find('.list-icons').hide();
	$(this).parents('.editing-theme-box').find('.right-no').hide();
	$(this).parents('.editing-theme-box').find('.edit_subscription').show();
	$('#subscription_id_'+subscription_id).hide();
	$('#subscription_text_'+subscription_id).show();
	$('#subscription_text_'+subscription_id).focus();
});

 
function saveSubscriptionTitle(frm){
	var lid = $('#messageBox').find('#hidListId').val();	
	var new_title = $('#messageBox').find('#subscription_title').val();	
	var block_data="subscription_title="+escape(new_title)+"&action=submit&subscription_id="+lid;
	
	$.ajax({
		url: base_url+"contacts/edit/"+lid,
		type:"POST",
		data:block_data,
		success: function(data) {
			data_arr=data.split(":");
			if(data_arr[0]=="error"){
				$( "#message" ).html( data_arr[2] ); 
			}else{
				jQuery('#messageBox').fadeOut('slow');
				window.location.reload();
			}
		}
	});
}
function slideMenu(action){
	$('.contacts_select_show').slideUp();
	if(!($('.move_subscriber_list').parent().hasClass('disabled'))){
		if($('#action_notmail').val()!='unsubscribe'){
			if(action=="move_list"){
				$('.move_list').slideToggle();
				$('.copy_list').slideUp();
				$('.do_not_mail_list').slideUp();
			}
		}
	}
	if(!($('.copy_subscriber_list').parent().hasClass('disabled'))){
		if($('#action_notmail').val()!='unsubscribe'){
			if(action=="copy_list"){
				$('.move_list').slideUp();
				$('.copy_list').slideToggle();
				$('.do_not_mail_list').slideUp();
			}
		}
	}
}
function newPageSize(x){
	var ps = $('#psize').val();
	var dnmType = 0;
	var isBounce = 0;
	  
	if($('#action_notmail').val() == 'unsubscribe'){
		dnmType = 1;
	}else if($('#action_notmail').val() == 'complaints'){
		dnmType = 2;
	}else if($('#action_notmail').val() == 'bounce'){
		isBounce = 1;  
	}
  
   jQuery.ajax({
		url:  base_url+"emailreport/ajx_setpagesize/",
		type:"POST",
		data:"ps="+ps,
		success: function(data) {
		 
			display_contacts($('#subscription_selected_id').val(),$('#email_search').val(),'','',dnmType,isBounce,0,'');
			if($('#action_notmail').val() == 'unsubscribe' || $('#action_notmail').val() == 'complaints' || $('#action_notmail').val() == 'bounce'){
				$('.tbl-contacts').addClass('donotmaillist');
				$('.move_subscriber').parent().addClass('disabled');
				$('.delete_subscriber').addClass('disabled_select');
				$('.select_page').addClass('disabled_select');
				$('.select_list').addClass('disabled_select');  
			}
		}
	});
  }
/**
* js for My Contact's Search box starts
*/
$(window).load(function(){
(function ($, undefined) {
    $.fn.clearable = function () {
        var $this = this;

        //$this.wrap('<div class="clear-holder" style=" position:relative;float:left;" />');
        var helper = $('<span class="clear-helper" style="margin-left:400px;color:red;font-weight:bold;border:1px solid #f0f0f0;"> X </span>');
        $this.parent().append(helper);
        helper.click(function(){
            $this.val("");
			clear_form();
			$('.clear-helper').hide();
        });
		$('.clear-helper').hide();

		$this.keyup(function(){
			if($this.val()!='')$('.clear-helper').show();else $('.clear-helper').hide();
		});

    };
})(jQuery);

//$("#email_search").clearable();
});
/**
* js for My Contact's Search box ends
*/



// Removes leading whitespaces
function LTrim( value ) {
	var re = /\s*((\S+\s*)*)/;
	return value.replace(re, "$1");
}
// Removes ending whitespaces
function RTrim( value ) {
	var re = /((\s*\S+)*)\s*/;
	return value.replace(re, "$1");
}