<?php
/**
 * Outputs the HTML of the form title.
 * The form title can be filtered with 'ninja_forms_form_title'.
**/
add_action( 'init', 'ninja_forms_register_display_form_title' );
function ninja_forms_register_display_form_title(){
	add_action( 'ninja_forms_display_form_title', 'ninja_forms_display_form_title', 10 );
}

function ninja_forms_display_form_title( $form_id ){
	$form_row = ninja_forms_get_form_by_id( $form_id );
	$show_title = $form_row['data']['show_title'];
	$form_title = $form_row['data']['form_title'];
	$form_title = apply_filters('ninja_forms_form_title', $form_title);
	if($show_title == 1){
		?>
		<h2 class=""><?php echo $form_title;?></h2>
		<?php
	}
}