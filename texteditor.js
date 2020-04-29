<!--/* For Tags...*/
function insert(Form, Textarea, aTag, eTag) {
  var input = document.forms[Form].elements[Textarea];
  input.focus();
  /* for Internet Explorer */
  if(typeof document.selection != 'undefined') {
    /* Insert the formatting code */
    var range = document.selection.createRange();
    var insText = range.text;
    range.text = aTag + insText + eTag;
    /* Adapt to the cursor position */
    range = document.selection.createRange();
    if (insText.length == 0) {
      range.move('character', -eTag.length);
    } else {
      range.moveStart('character', aTag.length + insText.length + eTag.length);      
    }
    range.select();
  }
  /* for newer Browser which are based on Gecko */
  else if(typeof input.selectionStart != 'undefined')
  {
    /* Insert the formatting code */
    var start = input.selectionStart;
    var end = input.selectionEnd;
    var insText = input.value.substring(start, end);
    input.value = input.value.substr(0, start) + aTag + insText + eTag + input.value.substr(end);
    /* Adapt to the cursor position */
    var pos;
    if (insText.length == 0) {
      pos = start + aTag.length;
    } else {
      pos = start + aTag.length + insText.length + eTag.length;
    }
    input.selectionStart = pos;
    input.selectionEnd = pos;
  }
  /* for the remaining Browser */
  else
  {
    /* Inquiry of the a joint position */
    var pos;
    var re = new RegExp('^[0-9]{0,3}$');
    while(!re.test(pos)) {
      pos = prompt("Insert at position (0.." + input.value.length + "):", "0");
    }
    if(pos > input.value.length) {
      pos = input.value.length;
    }
    /* Insert the formatting code */
    var insText = prompt("Please you enter the text which can be formatted:");
    input.value = input.value.substr(0, pos) + aTag + insText + eTag + input.value.substr(pos);
  }
}
//-->
<!--/* For Wizards...*/
function auto_url(Form, Textarea)  {var url=prompt("Please enter the Link URL","http://");  var name=prompt("Please enter the Link Name (optional)","");
  if (name!=null && name!="") {insert([Form], [Textarea], '[url=' + url + ']' + name + '[/url]', '');}  else {if (url!=null && url!="") {insert([Form], [Textarea], '[url=' + url + ']' + url + '[/url]', '');}}}

function auto_flash(Form, Textarea)  {var url=prompt("Please enter the Flash URL","http://");  var width=prompt("Please enter the Flash Width","100");  var height=prompt("Please enter the Flash Height","100");  if (url!=null && url!="") {insert([Form], [Textarea], '[flash=' + width + ',' + height + ']' + url + '[/flash]', '');}}
//-->
<!--/* For Delete Confirm...*/
function checkDelete() {var value=confirm("Are you sure that you want to delete this entry?");
  if (value == true) {return true;} else {return false;}}
//-->
<!--/* For Select Delete Confirm & Submit...*/
function checkseletDelete(fieldid) {var foldervalue=document.getElementById(fieldid).value;
  if (foldervalue == "delete") {var value=confirm("Are you sure that you want to delete this folder?");
    if (value == true) {document.mailboxform.submit();} 
    else {document.getElementById(fieldid).value="";}}
  else {document.mailboxform.submit();}
}
//-->
<!--/* For Poll & Mail Options (Hide & Display)...*/
function viewMore(div) {
  obj = document.getElementById(div);
  col = document.getElementById("x" + div);
  if (div == "poll") {var block="No Poll Options"; var noblock="Poll Options";}
  else if (div == "webmail") {var block="<b>Hide Details</b>"; var noblock="<b>Show Details</b>";
    var date = new Date();
    date.setTime(date.getTime() + (30*24*60*60*1000));
    var expires = "; expires=" + date.toGMTString();
    if (obj.style.display == "none") {var value = "block";} else {var value = "none";}
    document.cookie = 'status' + "=" + escape(value) + expires;
  }
  if (obj.style.display == "none") {
	obj.style.display = "block";
	col.innerHTML = block;
  } else {
	obj.style.display = "none";
	col.innerHTML = noblock;
  }
}
//-->
<!--/* For Mail Option Cookie (Hide & Display)...*/
function mailCookie(status) {
  start = document.cookie.indexOf(status + "=");
  if (start != -1)
  {start = start + status.length + 1; 
  end = document.cookie.indexOf(";", start);
  if (end == -1) end = document.cookie.length;
  status = unescape(document.cookie.substring(start, end));
  obj = document.getElementById("webmail");
  obj.style.display = status;
  if (status == "block") {document.getElementById("xwebmail").innerHTML = "<b>Hide Details</b>";}
  else {document.getElementById("xwebmail").innerHTML = "<b>Show Details</b>";}}
}
//-->
<!--/* For DropDown Default...*/
function selectdefault(value)
{
  value.selectedIndex=0;
}
//-->
<!--/* For Image Verification...*/
function reloadImg()
{
  document.getElementById("image").src="imgverify.php?" + Math.ceil(Math.random() * 1000);
}
//-->
<!--/* For Message Multiple Checkbox...*/
var checkflag = "false";
function check(field) {
if (checkflag == "false") {
for (i = 0; i < field.length; i++) {
field[i].checked = true;}
checkflag = "true";
return "Uncheck All"; }
else {
for (i = 0; i < field.length; i++) {
field[i].checked = false; }
checkflag = "false";
return "Check All"; }
}
//-->