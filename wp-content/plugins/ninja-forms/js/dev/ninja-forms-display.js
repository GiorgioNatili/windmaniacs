jQuery(document).ready(function(jQuery) {
	
	jQuery(".ninja-forms-form input").bind("keypress", function(e) {
		if (e.keyCode == 13) {
			var type = jQuery(this).attr("type");
			if( type != "textarea" ){
				return false;
			}
		}
	});

	/* * * Begin Mask JS * * */
	
	jQuery("div.label-inside input[type=text]").focus(function(){
		var label = jQuery("#" + this.id + "_label_hidden").val();
		if( this.value == label ){
			this.value = '';
		}
	});

	jQuery("div.label-inside input[type=text]").blur(function(){
		var label = jQuery("#" + this.id + "_label_hidden").val();
		if( this.value == '' ){
			this.value = label;
		}
	});

	jQuery(".ninja-forms-mask").each(function(){
		var mask = this.title;
		jQuery(this).mask(mask);
	});

	jQuery(".ninja-forms-date").mask('99/99/9999');

	jQuery(".ninja-forms-datepicker").datepicker({
		dateFormat: ninja_forms_settings.date_format,
	});	
		
	jQuery(".ninja-forms-currency").autoNumeric({aSign: ninja_forms_settings.currency_symbol});
	
	/* * * End Mask JS * * */
	
	/* * * Begin Help Hover JS * * */

	jQuery(".ninja-forms-help-text").qtip({
		style: {
			classes: 'qtip-shadow qtip-dark'
		}
	});

	/* * * End Help Hover JS * * */


	/* * * Begin ajaxForms JS * * */
	/*
	jQuery(".ninja-forms-form").each(function(){
		var form_id = this.id.replace("ninja_forms_form_", "");
		var ajax = ninja_forms_settings.form_settings[form_id].ajax;
		if(ajax == 1){
			var options = { 
			beforeSubmit:  ninja_forms_before_submit, 
			success:       ninja_forms_response,
			};
			jQuery(this).ajaxForm(options);
		}
	});

	function ninja_forms_before_submit(formData, jqForm, options){
		var form_id = formData[1].value;
		jQuery("#ninja_forms_form_" + form_id + "_process_msg").show();		
	}
	
	function ninja_forms_response(responseText, statusText, xhr, jQueryform){
		alert(responseText);
		var data = jQuery.parseJSON(responseText);
		var form_id = data.form_id;
		var hide_complete = ninja_forms_settings.hide_complete;
		var clear_complete = ninja_forms_settings.clear_complete;
		
		jQuery("#ninja_forms_form_" + form_id + "_process_msg").hide();
		
		jQuery("#ninja_forms_form_" + form_id + " .ninja-forms-field").each(function(){
			jQuery("#" + this.id + "_error").prop("innerHTML", "");
			jQuery(this).parent().removeClass("ninja-forms-error");
		});
		
		jQuery("#ninja_forms_form_" + form_id + "_response_msg").prop("innerHTML", "");
		
		if(typeof(data.error) !== 'undefined'){
			for(i in data.error){
				var error_msg = ninja_forms_html_decode(data.error[i].msg);
				if(typeof(data.error[i].field_id) != 'undefined'){
					jQuery("#ninja_forms_field_" + data.error[i].field_id).parent().addClass("ninja-forms-error");
					jQuery("#ninja_forms_field_" + data.error[i].field_id + "_error").prop("innerHTML", error_msg);
				}
				if(typeof(data.error[i].element_id) != 'undefined'){
				
				}
				if(typeof(data.error[i].response_msg) != 'undefined'){
					//jQuery("#ninja_forms_form_" + form_id + "_response_msg").prop("innerHTML", error_msg);
					jQuery("#ninja_forms_form_" + form_id + "_response_msg").append("<p>" + error_msg + "<p>");
				}
			}		
		}
		if(typeof(data.success) !== 'undefined'){
			for(i in data.success){
				var success_msg = ninja_forms_html_decode(data.success[i].msg);
				jQuery("#ninja_forms_form_" + form_id + "_response_msg").append(success_msg);
			}
			
			if(clear_complete == 1){
				jQuery("#ninja_forms_form_" + form_id).resetForm();
			}
			
			if(hide_complete == 1){
				jQuery("#ninja_forms_form_" + form_id + "_wrap").hide();
			}
			
		}
	}
	
	/* * * End ajaxForm JS * * */
	
}); //End document.ready

function ninja_forms_html_decode(value) {
	if (value) {
		var decoded = jQuery('<div />').html(value).text();
		decoded = jQuery('<div />').html(decoded).text();
		return decoded;
	} else {
		return '';
	}
}

function ninja_forms_toggle_login_register(form_type, form_id) {

	var el_id = 'ninja_forms_form_' + form_id + '_' + form_type + '_form';
	if(form_type == 'login'){
		var opp_id = 'ninja_forms_form_' + form_id + '_register_form';
	}else{
		var opp_id = 'ninja_forms_form_' + form_id + '_login_form';
	}
	var ele = document.getElementById(el_id);
	var opp_ele = document.getElementById(opp_id);
	if(ele.style.display == "block") {
		ele.style.display = "none";
  	}else{
		ele.style.display = "block";
		opp_ele.style.display = "none";
	}
} 	

function ninja_forms_get_form_id(element){
	var form_id = jQuery(element).closest('form').prop("id");
	if(form_id == ''){
		form_id = jQuery("#_form_id").val();
	}
	return form_id;
}