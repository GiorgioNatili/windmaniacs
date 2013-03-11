jQuery(document).ready(function(jQuery) {
		
	/* * * Begin File Upload JS * * */

	jQuery(".ninja-forms-change-file-upload").click(function(e){
		e.preventDefault();
		var file_upload_id = this.id.replace('ninja_forms_change_file_upload_', '');
		jQuery("#ninja_forms_file_upload_" + file_upload_id).toggle();
	});

	/* * * End File Upload JS * * */
});