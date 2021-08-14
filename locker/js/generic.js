var nonNegNumb = '0123456789.';
var numb = '0123456789';
var lwr = 'abcdefghijklmnopqrstuvwxyz';
var upr = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ ';
var special = '-';
var othr = ' +';
 
function isValid(parm,val) {
  if (parm == "") return false;
  for (i=0; i<parm.length; i++) {
	if (val.indexOf(parm.charAt(i),0) == -1) return false;
  }
  return true;
}
function countit(parm,val) {
  var intCounter = 0;
   
  if (parm == "") return false;
  if (parm.length < 8) return false;
  for (i=0; i<parm.length; i++) {
	if(!(val.indexOf(parm.charAt(i),0) == -1))
		intCounter++;		
  }
  if(intCounter == 0)return false;
  else
  return intCounter;
}

function isPassword(parm) {return(countit(parm,numb)>=2);}
 
function isNonNegNum(parm) {return isValid(parm,nonNegNumb);}
function isNum(parm) {return isValid(parm,numb);}
function isLower(parm) {return isValid(parm,lwr);}
function isUpper(parm) {return isValid(parm,upr);}
function isAlpha(parm) {return isValid(parm,lwr+upr);}
function isAlphaSpecial(parm) {return isValid(parm,lwr+upr+special);}
function isAlphanum(parm) {return isValid(parm,lwr+upr+numb);}
function isPhonenumber(parm) {return isValid(parm,othr+numb);}
	
function isEmail(email){
	var emailAddress = email;
	if (emailAddress.indexOf ('@',0) == -1 || emailAddress.indexOf ('.',0) == -1){
		return false;
	}	
	return true;	
}
function openAWindow(pageToLoad, winName, width, height, center) {
		xposition=0; yposition=0;
		if ((parseInt(navigator.appVersion) >= 4 ) && (center)){
			xposition = (screen.width - width) / 2;
			yposition = (screen.height - height) / 2;
		}
   args = "width=" + width + "," + "height=" + height + "," + "location=0," + "menubar=0," + "resizable=0,"
     + "scrollbars=1,"
     + "status=0,"
    + "titlebar=0,"
    + "toolbar=0,"
    + "hotkeys=0,"
     + "screenx=" + xposition + ","  
     + "screeny=" + yposition + ","  
    + "left=" + xposition + ","     
    + "top=" + yposition;          
     window.open( pageToLoad,winName,args );
 } 

 function CalcKeyCode(aChar) {
  var character = aChar.substring(0,1);
  var code = aChar.charCodeAt(0);
  return code;
}

function checkNumber1(val) {
  var strPass = val.value;
  var strLength = strPass.length;
  var lchar = val.value.charAt((strLength) - 1);
  var cCode = CalcKeyCode(lchar);

  /* Check if the keyed in character is a number
     do you want alphabetic UPPERCASE only ?
     or lower case only just check their respective
     codes and replace the 48 and 57 */

  if (cCode < 48 || cCode > 57 ) {
    var myNumber = val.value.substring(0, (strLength) - 1);
    val.value = myNumber;
  }
  return false;
}
function checkNumber(val) {
	//alert(val);
 // var strPass = val.value;
  var strPass = document.getElementById(val).value;
  var strLength = strPass.length;
  var lchar = strPass.charAt((strLength) - 1);
  var cCode = CalcKeyCode(lchar);

  /* Check if the keyed in character is a number
     do you want alphabetic UPPERCASE only ?
     or lower case only just check their respective
     codes and replace the 48 and 57 */

  if (cCode < 48 || cCode > 57 ) {
    var myNumber = strPass.substring(0, (strLength) - 1);
    document.getElementById(val).value = myNumber;
  }
  return false;
}


function numbersonly(myfield, e, dec) {
  var key;
  var keychar;

  if (window.event)
    key = window.event.keyCode;
  else if (e)
    key = e.which;
  else
    return true;
  keychar = String.fromCharCode(key);

  // control keys
  if ((key==null) || (key==0) || (key==8) || (key==9) || (key==13) || (key==27) )
    return true;

  // numbers
  else if ((("0123456789").indexOf(keychar) > -1))
    return true;

  // decimal point jump
  else if (dec && (keychar == ".")) {
    myfield.form.elements[dec].focus();
    return false;
  } else
    return false;
}
function isMatch(field1,field2) {
		if(field1 != field2){			
			return false;
		}else{
			return true;
		}
	}
	
//Display Loading Image
function Display_Load(){		
	$("#loading").fadeIn(900,0);
	$("#loading").html("<img src='images/ajax-loader.gif' />");
}
//Hide Loading Image
function Hide_Load(){
	$("#loading").fadeOut('slow');
}	
function bibAlert(message) {
	displayAlertMessage('Alert!',message,'<input type="button" value=" Ok " onclick="$.modalBox.close();" style="margin:10px 0 0 190px;" />',true,400,150,false,'');
}
function bibConfirm(message,fn) {
	var confirmbtn = "<a href = 'javascript:void(0);' onclick='"+fn+"' >OK, Proceed</a>";
	var cancelbtn = "<a href = 'javascript:void(0);' onclick='$.modalBox.close()' >Cancel</a>";
	displayAlertMessage('Confirm!',message,'<div class = "message_button">'+confirmbtn+cancelbtn+'</div>',true,400,150,false,'');
}
function displayAlertMessage(title,message,button,timeOut,width,height,overflow,action) {
	//jQuery('#messageBox').slideUp();
	//alert(timeOut);
	// var timeOutEnable = false;
	// var timeOutDefault = 5;
	// jQuery('#messageBox').css('top',113+$(window).scrollTop());	
	jQuery('#message_title').html(title);
	jQuery('#message').html(message);	
	
	if(button != '0'){
		if(button != '1'){			
			jQuery('#message_button').html(button);
		}
		jQuery('#message_button').show();
	}else{		
		jQuery('#message_button').hide();
	}
	
	
	if(width != ''){		
		var mLeft = width*1/2;
		var widthTitle = width*1-10;
		jQuery('#messageBox').css('margin-left','-'+mLeft+'px');
		jQuery('#messageBox').css('width',width+'px');
		jQuery('#message_title').css('width',widthTitle+'px');	
	}
	if(height != ''){
		jQuery('#messageBox').css('height',height+'px');	
		
		if(overflow == true){			
			var msgHt = height*1 - 30;
			jQuery('#message').css('height',msgHt+'px');		
			jQuery('#message').css('overflow','auto');
			jQuery('#message').css('margin-right','5px');
		}else{
			jQuery('#message').css('height',height+'px');		
			jQuery('#message').css('overflow','none');
			jQuery('#message').css('margin-right','0px');
		}
	}
	// jQuery('#messageBox').fadeIn('fast');
	jQuery('#messageBox').modalBox({
		onClose:function(){
			if(action == ''){			
				return false;
			}else if(action == 'self'){
				window.location.reload();
			}else{
				//alert(action);
				var rAction = action.split(':');
				if(rAction[0] == 'overlay'){
					$('#'+rAction[1]).click();
				}else if(rAction[0] == 'fn'){
					// call rAction[0] which is a function name passed as js:close_it
					var fn = rAction[1];
					window[fn]();
				}else{
					window.location.href = action;
				}	
			}
		}
	});
	
	// if(timeOutEnable){
		// if(timeOut != 'CLOSE'){
			// if(timeOut == '') timeOut = timeOutDefault;
			// setTimeout(function() {
				// jQuery('#messageBox').fadeOut('fast');
			// }, timeOut * 1000);
		// }
	// }
	
}
function modifyAlertMessage(title,message,button,timeOut,width,height,overflow,action) {	
	jQuery('#message_title').html(title);
	jQuery('#message').html(message);
	
	if(button != '0'){
		if(button != '1'){			
			jQuery('#message_button').html(button);
		}
		jQuery('#message_button').show();
	}else{		
		jQuery('#message_button').hide();
	}	
	
	if(width != ''){		
		var mLeft = width*1/2;
		var widthTitle = width*1-10;
		jQuery('#messageBox').css('margin-left','-'+mLeft+'px');
		jQuery('#messageBox').css('width',width+'px');
		jQuery('#message_title').css('width',widthTitle+'px');	
	}
	if(height != ''){
		jQuery('#messageBox').css('height',height+'px');	
		
		if(overflow == 'true'){
			var msgHt = height*1 - 80;
			jQuery('#message').css('height',msgHt+'px');		
			jQuery('#message').css('overflow','auto');
			jQuery('#message').css('margin-right','5px');
		}
	}
	
}
function closeAlertMessage(){
	jQuery('#messageBox').fadeOut('slow');
}

function number_format(number, decimals, dec_point, thousands_sep) {
  //  discuss at: http://phpjs.org/functions/number_format/
  // original by: Jonas Raoni Soares Silva (http://www.jsfromhell.com)
  // improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // improved by: davook
  // improved by: Brett Zamir (http://brett-zamir.me)
  // improved by: Brett Zamir (http://brett-zamir.me)
  // improved by: Theriault
  // improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // bugfixed by: Michael White (http://getsprink.com)
  // bugfixed by: Benjamin Lupton
  // bugfixed by: Allan Jensen (http://www.winternet.no)
  // bugfixed by: Howard Yeend
  // bugfixed by: Diogo Resende
  // bugfixed by: Rival
  // bugfixed by: Brett Zamir (http://brett-zamir.me)
  //  revised by: Jonas Raoni Soares Silva (http://www.jsfromhell.com)
  //  revised by: Luke Smith (http://lucassmith.name)
  //    input by: Kheang Hok Chin (http://www.distantia.ca/)
  //    input by: Jay Klehr
  //    input by: Amir Habibi (http://www.residence-mixte.com/)
  //    input by: Amirouche
  //   example 1: number_format(1234.56);
  //   returns 1: '1,235'
  //   example 2: number_format(1234.56, 2, ',', ' ');
  //   returns 2: '1 234,56'
  //   example 3: number_format(1234.5678, 2, '.', '');
  //   returns 3: '1234.57'
  //   example 4: number_format(67, 2, ',', '.');
  //   returns 4: '67,00'
  //   example 5: number_format(1000);
  //   returns 5: '1,000'
  //   example 6: number_format(67.311, 2);
  //   returns 6: '67.31'
  //   example 7: number_format(1000.55, 1);
  //   returns 7: '1,000.6'
  //   example 8: number_format(67000, 5, ',', '.');
  //   returns 8: '67.000,00000'
  //   example 9: number_format(0.9, 0);
  //   returns 9: '1'
  //  example 10: number_format('1.20', 2);
  //  returns 10: '1.20'
  //  example 11: number_format('1.20', 4);
  //  returns 11: '1.2000'
  //  example 12: number_format('1.2000', 3);
  //  returns 12: '1.200'
  //  example 13: number_format('1 000,50', 2, '.', ' ');
  //  returns 13: '100 050.00'

  number = (number + '')
    .replace(/[^0-9+\-Ee.]/g, '');
  var n = !isFinite(+number) ? 0 : +number,
    prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
    sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
    dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
    s = '',
    toFixedFix = function(n, prec) {
      var k = Math.pow(10, prec);
      return '' + Math.round(n * k) / k;
    };
  // Fix for IE parseFloat(0.55).toFixed(0) = 0;
  s = (prec ? toFixedFix(n, prec) : '' + Math.round(n))
    .split('.');
  if (s[0].length > 3) {
    s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
  }
  if ((s[1] || '')
    .length < prec) {
    s[1] = s[1] || '';
    s[1] += new Array(prec - s[1].length + 1)
      .join('0');
  }
  return s.join(dec);
}