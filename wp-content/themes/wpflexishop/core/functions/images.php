<?php
function prima_get_image( $attachment_id = 0, $width = null, $height = null, $crop = true, $post_id = null ) {
	global $post;
	if (!$attachment_id) {
		if (!$post_id) {
			$post_id = $post->ID;
		}
		if ( has_post_thumbnail( $post_id ) ) {
			$attachment_id = get_post_thumbnail_id( $post_id );
		} 
		else {
			$attached_images = (array)get_posts( array(
				'post_type'   => 'attachment',
				'numberposts' => 1,
				'post_status' => null,
				'post_parent' => $post_id,
				'orderby'     => 'menu_order',
				'order'       => 'ASC'
			) );
			if ( !empty( $attached_images ) )
				$attachment_id = $attached_images[0]->ID;
		}
	}
	
	if (!$attachment_id)
		return;

	if ( function_exists("gd_info") && (($width >= 10) && ($height >= 10)) && (($width <= 1024) && ($height <= 1024)) ) {
		$vt_image = vt_resize( $attachment_id, '', $width, $height, $crop );
		if ($vt_image) 
			$image_url = $vt_image['url'];
		else
			$image_url = false;
	}
	else {
		$full_image = wp_get_attachment_image_src( $attachment_id, 'full');
		if ($full_image) 
			$image_url = $full_image[0];
		else
			$image_url = false;
	}
	
    if( is_ssl() )
		$image_url = str_replace( 'http://', 'https://', $image_url );
	
	return apply_filters( 'prima_product_image', $image_url );
}

/*-----------------------------------------------------------------------------------*/
/* vt_resize - Resize images dynamically using wp built in functions
/*-----------------------------------------------------------------------------------*/
/*
 * Resize images dynamically using wp built in functions
 * Victor Teixeira
 *
 * php 5.2+
 *
 * Exemplo de uso:
 * 
 * <?php 
 * $thumb = get_post_thumbnail_id(); 
 * $image = vt_resize( $thumb, '', 140, 110, true );
 * ?>
 * <img src="<?php echo $image[url]; ?>" width="<?php echo $image[width]; ?>" height="<?php echo $image[height]; ?>" />
 *
 * @param int $attach_id
 * @param string $img_url
 * @param int $width
 * @param int $height
 * @param bool $crop
 * @return array
 */
if ( !function_exists('vt_resize') ) {
	function vt_resize( $attach_id = null, $img_url = null, $width, $height, $crop = false ) {
	
		// this is an attachment, so we have the ID
		if ( $attach_id ) {
		
			$image_src = wp_get_attachment_image_src( $attach_id, 'full' );
			$file_path = get_attached_file( $attach_id );
		
		// this is not an attachment, let's use the image url
		} else if ( $img_url ) {
			
			$file_path = parse_url( $img_url );
			$file_path = $_SERVER['DOCUMENT_ROOT'] . $file_path['path'];
			
			//$file_path = ltrim( $file_path['path'], '/' );
			//$file_path = rtrim( ABSPATH, '/' ).$file_path['path'];
			
			$orig_size = getimagesize( $file_path );
			
			$image_src[0] = $img_url;
			$image_src[1] = $orig_size[0];
			$image_src[2] = $orig_size[1];
		}
		
		$file_info = pathinfo( $file_path );
	
		// check if file exists
		$base_file = $file_info['dirname'].'/'.$file_info['filename'].'.'.$file_info['extension'];
		if ( !file_exists($base_file) )
			return;
		 
		$extension = '.'. $file_info['extension'];
	
		// the image path without the extension
		$no_ext_path = $file_info['dirname'].'/'.$file_info['filename'];
		
		// checking if the file size is larger than the target size
		// if it is smaller or the same size, stop right here and return
		if ( $image_src[1] > $width || $image_src[2] > $height ) {
	
			if ( $crop == true ) {
			
				$cropped_img_path = $no_ext_path.'-'.$width.'x'.$height.$extension;
				
				// the file is larger, check if the resized version already exists (for $crop = true but will also work for $crop = false if the sizes match)
				if ( file_exists( $cropped_img_path ) ) {
		
					$cropped_img_url = str_replace( basename( $image_src[0] ), basename( $cropped_img_path ), $image_src[0] );
					
					$vt_image = array (
						'url' => $cropped_img_url,
						'width' => $width,
						'height' => $height
					);
					
					return $vt_image;
				}
			}
			elseif ( $crop == false ) {
			
				// calculate the size proportionaly
				$proportional_size = wp_constrain_dimensions( $image_src[1], $image_src[2], $width, $height );
				$resized_img_path = $no_ext_path.'-'.$proportional_size[0].'x'.$proportional_size[1].$extension;			
	
				// checking if the file already exists
				if ( file_exists( $resized_img_path ) ) {
				
					$resized_img_url = str_replace( basename( $image_src[0] ), basename( $resized_img_path ), $image_src[0] );
	
					$vt_image = array (
						'url' => $resized_img_url,
						'width' => $proportional_size[0],
						'height' => $proportional_size[1]
					);
					
					return $vt_image;
				}
			}
	
			// check if image width is smaller than set width
			$img_size = getimagesize( $file_path );
			if ( $img_size[0] <= $width ) $width = $img_size[0];		
	
			// no cache files - let's finally resize it
			$new_img_path = image_resize( $file_path, $width, $height, $crop );
			$new_img_size = getimagesize( $new_img_path );
			$new_img = str_replace( basename( $image_src[0] ), basename( $new_img_path ), $image_src[0] );
	
			// resized output
			$vt_image = array (
				'url' => $new_img,
				'width' => $new_img_size[0],
				'height' => $new_img_size[1]
			);
			
			return $vt_image;
		}
	
		// default output - without resizing
		$vt_image = array (
			'url' => $image_src[0],
			'width' => $image_src[1],
			'height' => $image_src[2]
		);
		
		return $vt_image;
	}
}

if ( !function_exists('vt_resize2') ) {
	function vt_resize2( $img_name, $dir_url, $dir_path, $width, $height, $crop = false ) {
		
		$file_path = trailingslashit($dir_path).$img_name;
		
		$orig_size = getimagesize( $file_path );
		
		$image_src[0] = trailingslashit($dir_url).$img_name;
		$image_src[1] = $orig_size[0];
		$image_src[2] = $orig_size[1];
		
		$file_info = pathinfo( $file_path );
	
		// check if file exists
		$base_file = $file_info['dirname'].'/'.$file_info['filename'].'.'.$file_info['extension'];
		if ( !file_exists($base_file) )
			return;
		 
		$extension = '.'. $file_info['extension'];
	
		// the image path without the extension
		$no_ext_path = $file_info['dirname'].'/'.$file_info['filename'];
		
		// checking if the file size is larger than the target size
		// if it is smaller or the same size, stop right here and return
		if ( $image_src[1] > $width || $image_src[2] > $height ) {
	
			if ( $crop == true ) {
			
				$cropped_img_path = $no_ext_path.'-'.$width.'x'.$height.$extension;
				
				// the file is larger, check if the resized version already exists (for $crop = true but will also work for $crop = false if the sizes match)
				if ( file_exists( $cropped_img_path ) ) {
		
					$cropped_img_url = str_replace( basename( $image_src[0] ), basename( $cropped_img_path ), $image_src[0] );
					
					$vt_image = array (
						'url' => $cropped_img_url,
						'width' => $width,
						'height' => $height
					);
					
					return $vt_image;
				}
			}
			elseif ( $crop == false ) {
			
				// calculate the size proportionaly
				$proportional_size = wp_constrain_dimensions( $image_src[1], $image_src[2], $width, $height );
				$resized_img_path = $no_ext_path.'-'.$proportional_size[0].'x'.$proportional_size[1].$extension;			
	
				// checking if the file already exists
				if ( file_exists( $resized_img_path ) ) {
				
					$resized_img_url = str_replace( basename( $image_src[0] ), basename( $resized_img_path ), $image_src[0] );
	
					$vt_image = array (
						'url' => $resized_img_url,
						'width' => $proportional_size[0],
						'height' => $proportional_size[1]
					);
					
					return $vt_image;
				}
			}
	
			// check if image width is smaller than set width
			$img_size = getimagesize( $file_path );
			if ( $img_size[0] <= $width ) $width = $img_size[0];		
	
			// no cache files - let's finally resize it
			$new_img_path = image_resize( $file_path, $width, $height, $crop );
			$new_img_size = getimagesize( $new_img_path );
			$new_img = str_replace( basename( $image_src[0] ), basename( $new_img_path ), $image_src[0] );
	
			// resized output
			$vt_image = array (
				'url' => $new_img,
				'width' => $new_img_size[0],
				'height' => $new_img_size[1]
			);
			
			return $vt_image;
		}
	
		// default output - without resizing
		$vt_image = array (
			'url' => $image_src[0],
			'width' => $image_src[1],
			'height' => $image_src[2]
		);
		
		return $vt_image;
	}
}


