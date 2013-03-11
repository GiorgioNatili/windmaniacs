<?php

add_action( 'init', 'ninja_forms_save_progress_register_select_sub_option' );
function ninja_forms_save_progress_register_select_sub_option(){
	$args = array(
		'page' => 'ninja-forms-subs',
		'tab' => 'view_subs',
		'sidebar' => 'select_subs',
		'type' => '',
		'display_function' => 'ninja_forms_save_progress_select_sub_option',
		'label' => __( 'Include Incomplete Submissions?', 'ninja-forms' ),
	);
	if( function_exists( 'ninja_forms_register_sidebar_option' ) ){
		ninja_forms_register_sidebar_option( 'add_incomplete', $args );
	}
	add_filter( 'ninja_forms_view_subs_results', 'ninja_forms_save_progress_filter_subs_results' );
}

function ninja_forms_save_progress_select_sub_option( $slug, $data ){
	if( isset( $_REQUEST['add_incomplete'] ) AND $_REQUEST['add_incomplete'] == '' ){
		unset( $_SESSION['ninja_forms_add_incomplete'] );
		$add_incomplete = '';
	}else if( isset( $_REQUEST['add_incomplete'] ) AND $_REQUEST['add_incomplete'] != '' ){
		$_SESSION['ninja_forms_add_incomplete'] = $_REQUEST['add_incomplete'];
		$add_incomplete = $_REQUEST['add_incomplete'];
	}else if( isset( $_SESSION['ninja_forms_add_incomplete']) AND $_SESSION['ninja_forms_add_incomplete'] != '' ){
		$add_incomplete = $_SESSION['ninja_forms_add_incomplete'];
	}else{
		$add_incomplete = '';
	}
	?>
	<input type="hidden" name="add_incomplete" value="0">
	<label>
		<input type="checkbox" name="add_incomplete" value="1" <?php checked( $add_incomplete, 1 );?>>
		<?php _e( 'Include incomplete submissions?', 'ninja-forms' );?>
	</label>
	<br />
	<?php
}

function ninja_forms_save_progress_filter_subs_results( $sub_results ){
	if( isset( $_REQUEST['add_incomplete'] ) AND $_REQUEST['add_incomplete'] == '' ){
		unset( $_SESSION['ninja_forms_add_incomplete'] );
		$add_incomplete = '';
	}else if( isset( $_REQUEST['add_incomplete'] ) AND $_REQUEST['add_incomplete'] != '' ){
		$_SESSION['ninja_forms_add_incomplete'] = $_REQUEST['add_incomplete'];
		$add_incomplete = $_REQUEST['add_incomplete'];
	}else if( isset( $_SESSION['ninja_forms_add_incomplete']) AND $_SESSION['ninja_forms_add_incomplete'] != '' ){
		$add_incomplete = $_SESSION['ninja_forms_add_incomplete'];
	}else{
		$add_incomplete = '';
	}
	if( is_array( $sub_results ) AND !empty( $sub_results ) ){
		for ($x=0; $x < count( $sub_results ); $x++) { 
			if( $add_incomplete != 1 ){
				if( $sub_results[$x]['status'] != 1 ){
					unset( $sub_results[$x] );
				}
			}
		}
	}

	$sub_results = array_values( $sub_results );

	return $sub_results;
}