<?php // Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/*
Plugin Name: Featured Foto Mod
Plugin URI: https://github.com/seumsims/ffm
Description: Featured Foto Mod plugin is useful to add 
Featured Foto Modification Ability .
Version: 1.00
Author: Seum
Author URI: https://github.com/seumsims/
License: MIT License
License URI: https://www.mit.edu/~amini/LICENSE.md
*/
// Exit if accessed directly
// if ( !defined( 'ABSPATH' ) ) exit;
add_action('wp_head', function() {
    if ( !is_user_logged_in() ) {
    _e("SALAM from front end");
}
});

function cust_ffm_meta_box($post) {

	//$screens = array( 'post', 'page' );
	$screens = get_post_types();
 
	foreach ( $screens as $screen ) {
        add_meta_box(
            'featured_foto_mod',
            __( 'Featured Foto Modifier', 'cust_ffm' ),
            'ffm_meta_box_callback',// $callback
            $screen,
			'side',// $context
			'low'// $priority
        );
		}
	}
    function ffm_meta_box_callback($post) {
		 
        // Add a nonce field so we can check for it later.
       wp_nonce_field( 'ffm_nonce', 'ffm_nonce' );
   
       $cust_ffm_value = get_post_meta( $post->ID, '_custom_ffm', true );
       
       echo '<input type="url" name="custom_ffm" id="custom_ffm"  placeholder="https://github.com/seumsims/ffm" size="35" value="' . esc_attr( $cust_ffm_value ) . '">';
           
       }

       function save_cust_ffm_meta_box_data( $post_id ) {

        // Check if our nonce is set.
        if ( ! isset( $_POST['ffm_nonce'] ) ) {
            return;
        }
    
        // Verify that the nonce is valid.
        if ( ! wp_verify_nonce( $_POST['ffm_nonce'], 'ffm_nonce' ) ) {
            return;
        }
    
        // If this is an autosave, our form has not been submitted, so we don't want to do anything.
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }
    
        // Check the user's permissions.
        if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {
    
            if ( ! current_user_can( 'edit_page', $post_id ) ) {
                return;
            }
    
        }
        else {
    
            if ( ! current_user_can( 'edit_post', $post_id ) ) {
                return;
            }
        }

        /* OK, it's safe for us to save the data now. */

    // Make sure that it is set.
    if ( ! isset( $_POST['custom_ffm'] ) ) {
        return;
    }
    // Sanitize user input.
    $my_data = sanitize_text_field( $_POST['custom_ffm'] );

    // Update the meta field in the database.
    update_post_meta( $post_id, '_custom_ffm', $my_data );
    }

    function cust_ffm_thumbnail_fallback( $html, $post_id, $post_thumbnail_id, $size, $attr ) {
		 
		$clink = get_post_meta( get_the_ID(), '_custom_ffm', true );
		
		if ( empty( $html ) || !empty ($clink) && is_singular()) {
			
			$html = '<a href="' . $clink . '" title="' . esc_attr( get_the_title( $post_id ) ) . '" target="_blank" class="ex-link">' . $html . '</a>';
		}
	return $html;
	}

    //add action to add meta box for ffm in single post | page
	add_action( 'add_meta_boxes',  'cust_ffm_meta_box'  );

    // Update meta box for custom url in single post | page in the database.
	add_action( 'save_post', 'save_cust_ffm_meta_box_data' );

    // add custom link in href to featured image in front of page | post
	add_action( 'post_thumbnail_html', 'cust_ffm_thumbnail_fallback', 20, 5 );