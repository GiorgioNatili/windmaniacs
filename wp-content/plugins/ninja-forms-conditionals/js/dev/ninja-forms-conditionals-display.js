jQuery(document).ready(function(jQuery) {
	
	/* * * Begin Conditional Logic JS * * */
	jQuery(".ninja-forms-field-conditional-listen").change(function(){
		ninja_forms_check_conditional(this, true);
	});	
	
	jQuery(".ninja-forms-field-conditional-listen").keyup(function(){
		ninja_forms_check_conditional(this, true);
	});

	/* * * End Conditional Logic JS * * */

});
function ninja_forms_check_conditional(element, action_value){
	
	var form_id = ninja_forms_get_form_id(element);
	form_id = form_id.replace("ninja_forms_form_", "");
	var conditional = ninja_forms_conditionals_settings[form_id];

	var field_id = element.id.replace("ninja_forms_field_", "");
	if(element.type == 'checkbox'){
		if(element.checked){
			var field_value = 'checked';
		}else{
			var field_value = 'unchecked';
		}
	}else{
		var field_value = element.value;
	}

	for(field in conditional){
		var target_field = field.replace("field_", "");
		var conditional_length = jQuery(conditional[field]['conditional']).length;
		for (i = 0; i < conditional_length; i++){
			var cr_length = jQuery(conditional[field]['conditional'][i]['cr']).length;
			for (x = 0; x < cr_length; x++){
				if(conditional[field]['conditional'][i]['cr'][x]['field'] == field_id){
					var cr_field = field_id;
					var cr_operator = conditional[field]['conditional'][i]['cr'][x]['operator'];
					var cr_value = conditional[field]['conditional'][i]['cr'][x]['value'];

					ninja_forms_conditional_change(element, target_field, action_value); //target_field, change value?
				}
			}
		}
	}

}

function ninja_forms_conditional_change(element, target_field, action_value){

	var form_id = ninja_forms_get_form_id(element);
	form_id = form_id.replace("ninja_forms_form_", "");
	var conditional = ninja_forms_conditionals_settings[form_id];

	var cond = conditional["field_" + target_field]['conditional'];
	conditional_length = jQuery(cond).length;
	for (i = 0; i < conditional_length; i++){
		var action = cond[i]['action'];
		var value = cond[i]['value'];
		var connector = cond[i]['connector'];
		var cr_row = cond[i]['cr'];
		cr_length = jQuery(cr_row).length;
		
		if(connector == 'and'){
			var pass = true;
		}else if(connector == 'or'){
			var pass = false;				
		}

		for (x = 0; x < cr_length; x++){
			cr_field = cr_row[x]['field'];
			cr_operator = cr_row[x]['operator'];
			cr_value = cr_row[x]['value'];
			cr_type = jQuery("#ninja_forms_field_" + cr_field).prop("type");
			if(cr_type == 'checkbox'){
				if(jQuery("#ninja_forms_field_" + cr_field).prop("checked")){
					var field_value = 'checked';
				}else{
					var field_value = 'unchecked';
				}
			}else{
				field_value = jQuery("#ninja_forms_field_" + cr_field).val();				
			}

			var tmp = ninja_forms_conditional_compare(field_value, cr_value, cr_operator);
			//alert(tmp);
			if(connector == 'and'){
				if(!tmp){
					pass = false;
				}
			}else if(connector == 'or'){
				if(tmp){
					pass = true;
				}
			}
		}
		
		if(action == 'show'){
			if(pass){
				jQuery("#ninja_forms_field_" + target_field + "_div_wrap").show();
			}else{
				jQuery("#ninja_forms_field_" + target_field + "_div_wrap").hide();	
			}
		}else if(action == 'hide'){
			if(pass){
				jQuery("#ninja_forms_field_" + target_field + "_div_wrap").hide();
			}else{
				jQuery("#ninja_forms_field_" + target_field + "_div_wrap").show();				
			}
		}else if(action == 'change_value'){
			var input_type = jQuery("#ninja_forms_field_" + target_field).prop("type");
			if(typeof input_type === "undefined"){
				input_type = jQuery('input[name^=ninja_forms_field_' + target_field + ']').prop("type");
			}

			if(input_type == 'checkbox'){
				if(pass){
					if(value == 'checked'){
						jQuery("#ninja_forms_field_" + target_field).attr("checked", true);
					}else if(value == 'unchecked'){
						jQuery("#ninja_forms_field_" + target_field).attr("checked", false);					
					}
				}	
			}else if(input_type == 'radio'){
				if(pass){
					jQuery("[name='ninja_forms_field_" + target_field + "'][value='" + value + "']").attr("checked", true);
				}else{
					jQuery("[name='ninja_forms_field_" + target_field + "'][value='" + value + "']").attr("checked", false);
				}
				
			}else{
				if(pass){
					jQuery("#ninja_forms_field_" + target_field).val(value);
				}
			}
			if(pass){
				jQuery("#ninja_forms_field_" + target_field).change();
			}
		}else if(action == 'remove_value'){
			var input_type = jQuery("#ninja_forms_field_" + target_field).prop("type");
			if(typeof input_type === "undefined"){
				input_type = jQuery('input[name^=ninja_forms_field_' + target_field + ']').prop("type");
			}
			
			if(input_type == 'select-one'){
				if(pass){
					var selected_var = jQuery("#ninja_forms_field_" + target_field).val();
					if(selected_var == value){
						var next_val = jQuery("#ninja_forms_field_" + target_field + " option[value='" + value + "']").next().val();
						jQuery("#ninja_forms_field_" + target_field).val(next_val);
					}
					jQuery("#ninja_forms_field_" + target_field + " option[value='" + value + "']").hide();
				}else{
					jQuery("#ninja_forms_field_" + target_field + " option[value='" + value + "']").show();
				}
			}else if(input_type == 'select-multiple'){
				if(pass){
					var selected_var = jQuery("#ninja_forms_field_" + target_field).val();
					if(!!selected_var){
						var index = selected_var.indexOf(value);
						if(index != -1){
							selected_var.splice(index, 1);
							jQuery("#ninja_forms_field_" + target_field).val(selected_var);
						}
					}
					var opt_index = jQuery("#ninja_forms_field_" + target_field + " option[value='" + value + "']").prop("index");
					var clone = jQuery("#ninja_forms_field_" + target_field + " option[value='" + value + "']").clone();
					jQuery(clone).attr("title", opt_index);
					jQuery("#ninja_forms_field_" + target_field + "_clone").append(clone);
					jQuery("#ninja_forms_field_" + target_field + " option[value='" + value + "']").remove();

				}else{
					var clone = jQuery("#ninja_forms_field_" + target_field + "_clone option[value='" + value + "']");
					var opt_index = jQuery(clone).attr("title");
					opt_index++;
					var selected_var = jQuery("#ninja_forms_field_" + target_field).val();
					jQuery("#ninja_forms_field_" + target_field + " option:nth-child(" + opt_index + ")").before(clone);
					jQuery("#ninja_forms_field_" + target_field).val(selected_var);
				}
			}else if(input_type == 'checkbox' || input_type == 'radio'){
				if(pass){
					jQuery("input[name^=ninja_forms_field_" + target_field + "][value='" + value + "']").attr("checked", false);
					jQuery("input[name^=ninja_forms_field_" + target_field + "][value='" + value + "']").parent().hide();
				}else{
					jQuery("input[name^=ninja_forms_field_" + target_field + "][value='" + value + "']").parent().show();
				}
			}
		}else if(action == 'add_value'){
			var input_type = jQuery("#ninja_forms_field_" + target_field).prop("type");
			if(typeof input_type === "undefined"){
				input_type = jQuery("input[name^=ninja_forms_field_" + target_field + "]").prop("type");
			}
			
			if(typeof value.value === "undefined" || value.value == "_ninja_forms_no_value"){
				value.value = value.label;
			}

			if(input_type == "select-one" || input_type == "select-multiple"){
				if(pass){
					var current_count = jQuery("#ninja_forms_field_" + target_field + " option[value='" + value.value + "']").length;
					if(current_count == 0){
						jQuery("#ninja_forms_field_" + target_field).append("<option value='" + value.value + "'>" + value.label + "</option>");
					}
				}else{
					jQuery("#ninja_forms_field_" + target_field + " option[value='" + value.value + "']").remove();
				}
			}else if(input_type == "checkbox" || input_type == "radio"){
				if(pass){
					var current_count = jQuery("input[name^=ninja_forms_field_" + target_field + "][value='" + value.value + "']").length;
					if(current_count == 0){
						var clone = jQuery("#ninja_forms_field_" + target_field + "_template_label").clone();
						var count = jQuery(".ninja-forms-field-" + target_field + "-options").length;
						var label_id = jQuery(clone).prop("id").replace("template", count);
						jQuery(clone).prop("id", label_id);
						var checkbox_id = jQuery(clone).find(":checkbox").prop("id") + count;
						if(input_type == "checkbox"){
							var checkbox_name = "ninja_forms_field_" + target_field + "[]";
						}else{
							var checkbox_name = "ninja_forms_field_" + target_field;
						}
						
						jQuery(clone).find(":" + input_type).prop("id", checkbox_id);
						jQuery(clone).find(":" + input_type).attr("name", checkbox_name);
						jQuery(clone).find(":" + input_type).val(value.value);
						jQuery(clone).find(":" + input_type).after(value.label);
						jQuery(clone).attr("style", "");
						jQuery("#ninja_forms_field_" + target_field + "_options_span").append(clone);
					}
				}else{
					jQuery("input[name^=ninja_forms_field_" + target_field + "][value='" + value.value + "']").parent().remove();
				}
			}
		}else{
			//Put code here to call javascript function.

		}
	}
}

function ninja_forms_conditional_compare(param1, param2, op){
	
	switch(op) {
		case "==":
			return param1 == param2;
		case "!=":
			return param1 != param2;
		case "<":
			return param1 < param2;
		case ">":
			return param1 > param2;
	}
}
