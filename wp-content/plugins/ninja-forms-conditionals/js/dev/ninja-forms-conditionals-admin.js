
/*!
 * jQuery serializeFullArray - v0.1 - 28/06/2010
 * http://github.com/jhogendorn/jQuery-serializeFullArray/
 *
 * Copyright (c) 2010 Joshua Hogendorn
 *
 *
 * Whereas .serializeArray() serializes a form into a key:pair array, .serializeFullArray()
 * builds it into a n-tier object, respecting form input arrays.
 *
 */

(function($){
	'$:nomunge'; // Used by YUI compressor.

	$.fn.serializeFullArray = function () {
		// Grab a set of name:value pairs from the form dom.
		var set = $(this).serializeArray();
		var output = {};

		for (var field in set)
		{
			if(!set.hasOwnProperty(field)) continue;

			// Split up the field names into array tiers
			var parts = set[field].name
				.split(/\]|\[/);

			// We need to remove any blank parts returned by the regex.
			parts = $.grep(parts, function(n) { return n != ''; });

			// Start ref out at the root of the output object
			var ref = output;

			for (var segment in parts)
			{
				if(!parts.hasOwnProperty(segment)) continue;

				// set key for ease of use.
				var key = parts[segment];
				var value = {};

				// If we're at the last part, the value comes from the original array.
				if (segment == parts.length - 1)
				{
					var value = set[field].value;
				}

				// Create a throwaway object to merge into output.
				var objNew = {};
				objNew[key] = value;

				// Extend output with our temp object at the depth specified by ref.
				$.extend(true, ref, objNew);

				// Reassign ref to point to this tier, so the next loop can extend it.
				ref = ref[key];
			}
		}

		return output;
	};
})(jQuery);


jQuery(document).ready(function($) {
	
	/* * * Conditional Settings JS * * */
	
	//Listen to the "hidden list value" checkbox.
	$(".ninja-forms-field-list-show-value").live('change', function(){
		var field_id = this.id.replace("ninja_forms_field_", "");
		field_id = field_id.replace("_list_show_value", "");
		var new_values = new Object();
		if(this.checked){
			$(".ninja-forms-field-" + field_id + "-list-option-value").each(function(){
				var x = this.id.replace("ninja_forms_field_" + field_id + "_list_option_", "");
				x = x.replace("_value_span", "");
				new_values[x] = $(this).children(".ninja-forms-field-list-option-value").val();
			});
			$(".ninja-forms-field-" + field_id + "-list-option-value").show();
			$(".ninja-forms-field-conditional-cr-field").each(function(){
				if(this.value == field_id){
					$(this).nextElementInDom(".ninja-forms-field-conditional-cr-value-list:first").children('option').each(function(){
						this.value = new_values[this.title];
					});
				}
			});
			$(".ninja-forms-field-" + field_id + "-conditional-value").each(function(){
				$(this).children('option').each(function(){
					this.value = new_values[this.title];
				});
			});
		}else{

			$("#ninja_forms_field_" + field_id + "_list_options").children(".ninja-forms-field-" + field_id + "-list-option").find(".ninja-forms-field-list-option-label").each(function(){
				var parent_id = $(this).parent().parent().parent().parent().parent().prop("id");

				var x = parent_id.replace("ninja_forms_field_" + field_id + "_list_option_", "");

				new_values[x] = this.value;
			});
			
			$(".ninja-forms-field-conditional-cr-field").each(function(){
				if(this.value == field_id){
					$(this).nextElementInDom(".ninja-forms-field-conditional-cr-value-list:first").children('option').each(function(){
						this.value = new_values[this.title];
					});
				}
			});	
			
			$(".ninja-forms-field-" + field_id + "-conditional-value").each(function(){
				$(this).children('option').each(function(){
					this.value = new_values[this.title];
				});
			});
			
			$(".ninja-forms-field-" + field_id + "-list-option-value").hide();			
		}
	});
	
	//Conditional Action Change
	$(".ninja-forms-field-conditional-action").live('change', function(){
		var value_id = this.id.replace('action', 'value');
		var label_id = this.id.replace('action', 'value_label');
		var form_id = $("#_form_id").val();
		var field_id = $(this).parent().parent().attr("name");
		var conditional_value_type = $("#ninja_forms_field_" + field_id + "_conditional_value_type").val();
		var list_show_value = $("#ninja_forms_field_" + field_id + "_list_show_value").prop("checked");
		var x = $(".ninja-forms-field-" + field_id + "-conditional").length;
		x--;
		var action_slug = this.value;
		var field_data = ninja_forms_serialize_data( field_id );

		$.post(ajaxurl, { form_id: form_id, field_id: field_id,  x: x, action_slug: action_slug, field_data: field_data, action:"ninja_forms_change_action"}, function(response){

			$("#ninja_forms_field_" + field_id + "_" + x + "_value_span").prop("innerHTML", response.new_html);

			if(response.new_type == 'list'){
				$("#" + value_id).children().remove().end();
				$(".ninja-forms-field-" + field_id + "-list-option").each(function(){

					var label = $(this).find(".ninja-forms-field-list-option-label").val();

					if(list_show_value){
						var value = $(this).find(".ninja-forms-field-list-option-value").val();
					}else{
						var value = label;
					}

					var i = this.id.replace("ninja_forms_field_" + field_id + "_list_option_", "");
					$("#" + value_id).append('<option value="' + value + '" title="' + i + '">' + label + '</option>');
				});
			}

		});
	});
	
	//Add New Conditional
	$(".ninja-forms-field-add-conditional").live("click", function(event){
		event.preventDefault();
		var field_id = this.id.replace("ninja_forms_field_", "");
		field_id = field_id.replace("_add_conditional", "");
		var form_id = $("#_form_id").val();
		var x = $(".ninja-forms-field-" + field_id + "-conditional").length;
		$.post(ajaxurl, { form_id: form_id, field_id: field_id,  x: x, action:"ninja_forms_add_conditional"}, function(response){
			$("#ninja_forms_field_" + field_id + "_conditionals").append(response);
		});
	});

	//Remove Conditional
	$(".ninja-forms-field-remove-conditional").live("click", function(event){
		event.preventDefault();
		var field_id = this.id.replace("ninja_forms_field_", "");
		field_id = field_id.replace("_remove_conditional", "");
		var x = this.name;
		$("#ninja_forms_field_" + field_id + "_conditional_" + x).remove();	
	});
	
	//Add New Criterion
	$(".ninja-forms-field-add-cr").live("click", function(event){
		event.preventDefault();
		var field_id = this.id.replace("ninja_forms_field_", "");
		field_id = field_id.replace("_add_cr", "");
		var form_id = $("#_form_id").val();
		var x = this.name;
		var y = $(".ninja-forms-field-" + field_id + "-conditional-" + x + "-cr").length;
		$.post(ajaxurl, { form_id: form_id, field_id: field_id, x: x, y: y, action:"ninja_forms_add_cr"}, function(response){
			$("#ninja_forms_field_" + field_id + "_conditional_" + x + "_cr").append(response.new_html);
			var title = '';
			var title_id = '';
			$(".ninja-forms-field-title").each(function(){
				title = this.innerHTML;
				title_id = this.id.replace("ninja_forms_field_", "");
				title_id = title_id.replace("_title", "");
				$(".ninja-forms-field-conditional-cr-field > option").each(function(){
					if(this.value == title_id){
						this.text = title;
					}
				});
			});
		});
	});
	
	//Remove Criterion
	$(".ninja-forms-field-remove-cr").live("click", function(event){
		event.preventDefault();
		var field_id = this.id.replace("ninja_forms_field_", "");
		field_id = field_id.replace("_remove_cr", "");
		var x = this.name;
		var y = this.rel;
		$("#ninja_forms_field_" + field_id + "_conditional_" + x + "_cr_" + y).remove();
	});
	
	//Change Criterion Select List
	$(".ninja-forms-field-conditional-cr-field").live("change", function(){
		var field_id = this.id.replace("ninja_forms_field_", "");
		field_id = field_id.replace("_cr_field", "");
		var tmp = this.title.split("_");
		var x = tmp[0];
		var y = tmp[1];
		var field_value = this.value;
		
		if(this.value != ''){
			$.post(ajaxurl, { field_id: field_id, field_value: field_value, x: x, y: y, action:"ninja_forms_change_cr_field"}, function(response){
				$("#ninja_forms_field_" + field_id + "_conditional_" + x + "_cr_" + y + "_value").prop("innerHTML", response.new_html);
				if(response.new_type == 'list'){
					$(".ninja-forms-field-" + field_value + "-list-option").each(function(){
						var label = $(this).find(".ninja-forms-field-list-option-label").val();
						if($("#ninja_forms_field_" + field_value + "_list_show_value").prop("checked") == true){
							var value = $(this).find(".ninja-forms-field-list-option-value").val();
						}else{
							var value = label;
						}
						var i = this.id.replace("ninja_forms_field_" + field_value + "_list_option_", "");
						$('select[name="ninja_forms_field_' + field_id + '\\[conditional\\]\\[' + x + '\\]\\[cr\\]\\[' + y + '\\]\\[value\\]"]').append('<option value="' + value + '" title="' + i + '">' + label + '</option>');
					});
				}
			});
		}else{
			$("#ninja_forms_field_" + field_id + "_conditional_" + x + "_cr_" + y + "_value").prop("innerHTML", "");
		}
	});
	
	/* * * End Conditional Settings JS * * */
}); //Document.read();

function ninja_forms_serialize_data( field_id ){
	var data = jQuery('input[name^=ninja_forms_field_' + field_id + ']');
	var field_data = jQuery(data).serializeFullArray();
	return field_data;
}