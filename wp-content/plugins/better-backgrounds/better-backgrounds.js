var farbtastic;

function pickColor(color) {
	farbtastic.setColor(color);
	jQuery('#background-color').val(color);
	//Preview:
	jQuery('#custom-background-image').css('background-color', color);
}

function bbg_changeLayout(){
	layout = jQuery('input[name=background-layout]:checked').val();
	
	//Hide everything before unhiding what is needed
	jQuery('#bbg_option_attachment').hide();
	jQuery('#bbg_option_position').hide();
	jQuery('#bbg_option_repeat').hide();
	jQuery('#bbg_option_fade').hide();
	jQuery('#bbg_option_fade_speed').hide();
	jQuery('#bbg_option_fade_out').hide();
	jQuery('#bbg_option_fade_out_speed').hide();
	jQuery('#bbg_option_height').hide();
	jQuery('#bbg_option_developer').hide();
	
	switch(layout){
		case 'better':
			jQuery('#bbg_option_attachment').show();
			jQuery('#bbg_option_fade').show();
			jQuery('#bbg_option_fade_out').show();
			
			//Show fade speed box only if fade in is checked
			if(jQuery('#background-fade').is(':checked'))
				jQuery('#bbg_option_fade_speed').show();

			//Show fade out speed box only if fade out is checked
			if(jQuery('#background-fade-out').is(':checked'))
				jQuery('#bbg_option_fade_out_speed').show();
			
			//Show height box only if attachment = scroll
			if(jQuery('#background-attachment-scroll').is(':checked'))
				jQuery('#bbg_option_height').show();
			
			break;
		case 'classic':
			jQuery('#bbg_option_attachment').show();
			jQuery('#bbg_option_position').show();
			jQuery('#bbg_option_repeat').show();
			break;
		case 'developer':
			jQuery('#bbg_option_developer').show();
			jQuery('#bbg_option_fade').show();
			jQuery('#bbg_option_fade_out').show();
			
			//Show fade speed box only if fade in is checked
			if(jQuery('#background-fade').is(':checked'))
				jQuery('#bbg_option_fade_speed').show();

			//Show fade out speed box only if fade out is checked
			if(jQuery('#background-fade-out').is(':checked'))
				jQuery('#bbg_option_fade_out_speed').show();			
				
			break;
	}
}

jQuery(document).ready(function() {
	//Select a Color popup color wheel
	jQuery('#pickcolor').click(function() {
		jQuery('#colorPickerDiv').show();
		return false;
	});

	//Select A Color - Error Checker?
	jQuery('#background-color').keyup(function() {
		var _hex = jQuery('#background-color').val(), hex = _hex;
		if ( hex.charAt(0) != '#' )
			hex = '#' + hex;
		hex = hex.replace(/[^#a-fA-F0-9]+/, '');
		if ( hex != _hex )
			jQuery('#background-color').val(hex);
		if ( hex.length == 4 || hex.length == 7 )
			pickColor( hex );
	});

	//?
	farbtastic = jQuery.farbtastic('#colorPickerDiv', function(color) {
		pickColor(color);
	});
	pickColor(jQuery('#background-color').val());

	jQuery(document).mousedown(function(){
		jQuery('#colorPickerDiv').each(function(){
			var display = jQuery(this).css('display');
			if ( display == 'block' )
				jQuery(this).fadeOut(2);
		});
	});
	
	
	//Only show options that are applicable to the current selected layout
	jQuery("input[name=background-layout],input[name=background-attachment],#background-fade,#background-fade-out").change(bbg_changeLayout);	

	//Run on page load to only show settings pertinent to user
	bbg_changeLayout();
	
});