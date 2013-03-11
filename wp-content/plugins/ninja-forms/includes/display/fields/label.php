<?php
/**
 * Outputs the HTML of the field label if it is set to display.
 * Also outputs the required symbol if it is set.
**/
add_action('init', 'ninja_forms_register_display_field_label');
function ninja_forms_register_display_field_label(){
	add_action('ninja_forms_display_field_label', 'ninja_forms_display_field_label', 10, 2);
}

function ninja_forms_display_field_label( $field_id, $data ){
	$plugin_settings = get_option("ninja_forms_settings");

	if(isset($data['label'])){
		$label = stripslashes($data['label']);				
	}else{
		$label = '';
	}	

	if(isset($data['label_pos'])){
		$label_pos = stripslashes($data['label_pos']);				
	}else{
		$label_pos = '';
	}

	if(isset($plugin_settings['req_field_symbol'])){
		$req_symbol = $plugin_settings['req_field_symbol'];
	}else{
		$req_symbol = '';
	}

	if(isset($data['req'])){
		$req = $data['req'];
	}else{
		$req = '';
	}

	if(isset($data['display_label'])){
		$display_label = $data['display_label'];
	}else{
		$display_label = true;
	}

	if($display_label){
		if($req == 1){
			$req_span = "<span class='ninja-forms-req-symbol'>$req_symbol</span>";
		}else{
			$req_span = '';
		}
		?>
		<label for="ninja_forms_field_<?php echo $field_id;?>" id="ninja_forms_field_<?php echo $field_id;?>_label"><?php echo $label;?> <?php echo $req_span;?>
		<?php
		if( $label_pos != 'left' ){
			do_action( 'ninja_forms_display_field_help', $field_id, $data );
		}
		?>
		</label>
		<?php
	}
}