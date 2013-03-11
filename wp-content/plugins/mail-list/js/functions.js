//return an istance of xmlhttp 
function xmlHttpInit(){
	if (window.XMLHttpRequest)
	  {// code for IE7+, Firefox, Chrome, Opera, Safari
	  xmlhttp=new XMLHttpRequest();
	  }
	else
	  {// code for IE6, IE5
	  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	  }
return xmlhttp;	  
}

//Validate email
function validateEmail(email) { 
    var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
}

//delete this field value
function deleteMe(field)
{
	field.value="";
	jQuery("#ml-email").css("color","#555555");
}

//send the mail to the selected recipient
function subscribeMailList(){
	xmlhttp=xmlHttpInit();
	email=document.getElementById("ml-email").value;
	if(!validateEmail(email)){
		jQuery("#ml-email").css("color","#EB0000");
		return false;
	}
	jQuery("#ml-img").fadeIn(0);
	params="email="+escape(email);
	xmlhttp.open("POST",blog_url+"/wp-content/plugins/mail-list/ajax/add_subscriber.php",false);
	//Send the proper header information along with the requesthttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xmlhttp.setRequestHeader("Content-length", params.length);
	xmlhttp.setRequestHeader("Connection", "close");
	xmlhttp.send(params);
	//hide the form
	//jQuery("#ml-img").fadeOut(0);
	//jQuery('#ml-p').fadeOut(0);
	//jQuery('#ml-p-sent').fadeIn(0);
	jQuery('#ml-form').fadeOut('fast',function(){
		jQuery('#ml-p-sent').fadeIn();
	});
	//jQuery('#ml-newsletterform').delay(3000).fadeOut(1000);
	return false;
}
