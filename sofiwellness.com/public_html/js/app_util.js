/**
 * $Id: app_util.js,v 1.19 2009/11/17 15:57:44 gorsen Exp $
 * Created on 2007-3-4 11:34:21
 * @auther steven
 * 
 * This file contains javscript functiond definitions that not 
 * come from third-party.
 *
 *
 */
 
/*
for debug
*/
function trace( msg ){
  if( typeof( jsTrace ) != 'undefined' ){
    jsTrace.send( msg );
  }
}





/*****************************/

function setfocus(a_field_id) {
    $(a_field_id).focus()
}

function confirmation(msg) {
	var answer = confirm(msg)
	return answer;
}


/* remove the td new class - assuming td node is the parent node */
function removeTdNewClass(obj)
{
 	var trobj = obj.parentNode.parentNode;
 	var rows = trobj.getElementsByTagName('td');
	for( var i = 0, row; row = rows[i]; i++ )
	{
		if (row.className == 'new')
	  		row.className = '';
	}
 	//return false;
}

function validateUserName(str)
{  	  
  var re = /[^a-zA-Z0-9 _]/g
    if (re.test(str)) return false;
  return true;

}

function validatePassword(form, errmsg)
{
	if(form.password.value != "")
	{
		if (form.password.value != form.password_confirm.value)
		{
			document.getElementById('show_message').innerHTML=errMsg;
			form.password.focus();
			return false;
		}
		
		return true;
	}
	return false;
}


function printMsg (url, msgId)
{
	var id = document.getElementById(msgId).value;
	if (id != null && id.length > 0)
	{
		url = url + '?msgId=' + id;
		window.open(url);
	}
	else
		return false;
}


function setEditboxValue (fieldId, val)
{
	document.getElementById(fieldId).value = val;
}



function updateDOM(inputField) {
	if (typeof inputField == "string") {
		inputField = document.getElementById(inputField);
	}
	if (inputField.type == "select-one")
	{
		for (var i=0; i<inputField.options.length; i++)
		{
			if (i == inputField.selectedIndex)
				inputField.options[inputField.selectedIndex].setAttribute("selected","selected");
		}
	}
	else if (inputField.type == "text")
	{
		inputField.setAttribute("value",inputField.value);
	}
	else if (inputField.type == "textarea")
	{
		//inputField.setAttribute("value",inputField.value);
		
		if (inputField.value.length > 0)
		{
			var txtNode = document.createTextNode(inputField.value);
			if (inputField.lastChild != null)
				inputField.removeChild(inputField.lastChild);
			inputField.appendChild(txtNode);		
		}
		
	}
	else if ((inputField.type == "checkbox") || (inputField.type == "radio"))
	{
		if (inputField.checked)
			inputField.setAttribute("checked","checked");
		else
			inputField.removeAttribute("checked");
	}
}

function updateAllDOMFields(theForm){

	//if ie, do nothing
	var browser=navigator.appName;
	
	if (browser=="Microsoft Internet Explorer")
		return;

	var inputNodes = getAllFormFields(theForm);
	for(x=0; x < inputNodes.length; x++)
	{
		var theNode = inputNodes[x];
		updateDOM(theNode)
	} 
}

function getAllFormFields(theForm){
try{
	var inputFields = theForm.getElementsByTagName("input");
	var selectFields = theForm.getElementsByTagName("select");
	var textFields = theForm.getElementsByTagName("textarea");
	var array = new Array(inputFields + selectFields + textFields);
	for(i=0; i < array.length; i++)
	{
		for(x=0; x < inputFields.length; x++){
			array[i] = inputFields[x];
			i++
		}
		for(a=0; a < selectFields.length; a++){
			array[i] = selectFields[a];
			i++
		}
		for(t=0; t < textFields.length; t++){
			array[i] = textFields[t];
			i++
		}
	}
}
catch(e)
{
alert("Error when evoking getAllFormFields(): \nSomething is probably wrong with the form you passed in\n\n"+e.message)
}
return array;
}





/************************************

	functions that doing ajax stuff
	
	need the prototype lib

*************************************/


function ajaxUpdate(id, url)
{ 
	new Ajax.Updater(id, url,{method: 'get'});
}


function canAjax ()
{
    var canAjax = Ajax.getTransport() !== false;  
	return canAjax;
}




/*******************
validations
********************/

// returns true if the string is empty
function isEmpty(str){
  return (str == null) || (str.length == 0);
}

// returns true if the string is a valid email
function isEmail(str){
  if(isEmpty(str)) return false;
  var re = /^[^\s()<>@,;:\/]+@\w[\w\.-]+\.[a-z]{2,}$/i
  return re.test(str);
}
// returns true if the string only contains characters A-Z or a-z
function isAlpha(str){
  var re = /[^a-zA-Z]/g
  if (re.test(str)) return false;
  return true;
}
// returns true if the string only contains characters 0-9
function isNumeric(str){
  var re = /[\D]/g
  if (re.test(str)) return false;
  return true;
}
// returns true if the string only contains characters A-Z, a-z or 0-9
function isAlphaNumeric(str){
  var re = /[^a-zA-Z0-9]/g
  if (re.test(str)) return false;
  return true;
}
// returns true if the string's length equals "len"
function isLength(str, len){
  return str.length == len;
}
// returns true if the string's length is between "min" and "max"
function isLengthBetween(str, min, max){
  return (str.length >= min)&&(str.length <= max);
}
// returns true if the string is a US phone number formatted as...
// (000)000-0000, (000) 000-0000, 000-000-0000, 000.000.0000, 000 000 0000, 0000000000
function isPhoneNumber(str){
  var re = /^\(?[2-9]\d{2}[\)\.-]?\s?\d{3}[\s\.-]?\d{4}$/
  return re.test(str);
}
// returns true if the string is a valid date formatted as...
// mm dd yyyy, mm/dd/yyyy, mm.dd.yyyy, mm-dd-yyyy
function isDate(str){
  var re = /^(\d{1,2})[\s\.\/-](\d{1,2})[\s\.\/-](\d{4})$/
  if (!re.test(str)) return false;
  var result = str.match(re);
  var m = parseInt(result[1]);
  var d = parseInt(result[2]);
  var y = parseInt(result[3]);
  if(m < 1 || m > 12 || y < 1900 || y > 2100) return false;
  if(m == 2){
          var days = ((y % 4) == 0) ? 29 : 28;
  }else if(m == 4 || m == 6 || m == 9 || m == 11){
          var days = 30;
  }else{
          var days = 31;
  }
  return (d >= 1 && d <= days);
}
// returns true if "str1" is the same as the "str2"
function isMatch(str1, str2){
  return str1 == str2;
}
// returns true if the string contains only whitespace
// cannot check a password type input for whitespace
function isWhitespace(str){ // NOT USED IN FORM VALIDATION
  var re = /[\S]/g
  if (re.test(str)) return false;
  return true;
}

/****************************************************************
particular functions
***************************************************************/
function createCookie(name,value,days) {
	if (days) {
		var date = new Date();
		date.setTime(date.getTime()+(days*24*60*60*1000));
		var expires = "; expires="+date.toGMTString();
	}
	else var expires = "";
	document.cookie = name+"="+value+expires+"; path=/";
}

function readCookie(name) {
	var nameEQ = name + "=";
	var ca = document.cookie.split(';');
	for(var i=0;i < ca.length;i++) {
		var c = ca[i];
		while (c.charAt(0)==' ') c = c.substring(1,c.length);
		if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
	}
	return null;
}

function eraseCookie(name) {
	createCookie(name,"",-1);
}


/****************************************************

***************************************************/
