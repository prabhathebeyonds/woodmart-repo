<?php
function add_custom_meta_boxes() {
	add_meta_box( 
		'wp_custom_attachment',
		'Upload PDF File',
		'wp_custom_attachment',
		'casestudy',
		'side'
	) ;
}
add_action( 'add_meta_boxes', 'add_custom_meta_boxes' );

/**
 * Custom attachment metabox markup.
 */
function wp_custom_attachment() {
	wp_nonce_field( plugin_basename(__FILE__), 'wp_custom_attachment_nonce' );
	$html = '<p class="description">Upload your PDF here.</p>';
	$html .= '<input id="wp_custom_attachment" name="wp_custom_attachment" size="25" type="file" value="" />';

	$filearray = get_post_meta( get_the_ID(), 'wp_custom_attachment', true );
	$this_file = '';
	if(!empty($filearray)){
		$this_file = $filearray['url'];
	}
	
	
	if ( $this_file != '' ) { 
	     $html .= '<div><p style="font-size:11px;"><b>Current file:</b> ' . $this_file . '</p></div>'; 
	}
	echo $html; 
}


/**
 * Save custom attachment metabox info.
 */
function save_custom_meta_data( $id ) {
	if ( ! empty( $_FILES['wp_custom_attachment']['name'] ) ) {
		$supported_types = array( 'application/pdf' );
		$arr_file_type = wp_check_filetype( basename( $_FILES['wp_custom_attachment']['name'] ) );
		$uploaded_type = $arr_file_type['type'];

		if ( in_array( $uploaded_type, $supported_types ) ) {
			
			$upload = wp_upload_bits($_FILES['wp_custom_attachment']['name'], null, file_get_contents($_FILES['wp_custom_attachment']['tmp_name']));
			print_r($upload);
			
			if ( isset( $upload['error'] ) && $upload['error'] != 0 ) {
				wp_die( 'There was an error uploading your file. The error is: ' . $upload['error'] );
			} else {
				add_post_meta( $id, 'wp_custom_attachment', $upload );
				update_post_meta( $id, 'wp_custom_attachment', $upload );
			}
		}
		else {
			wp_die( "The file type that you've uploaded is not a PDF." );
		}
	}
}
add_action( 'save_post', 'save_custom_meta_data' );

/**
 * Add functionality for file upload.
 */
function update_edit_form() {
	echo ' enctype="multipart/form-data"';
}
add_action( 'post_edit_form_tag', 'update_edit_form' );

