<?php 
/*  
 * 2J Gallery			http://2joomla.net/wordpress-plugins/2j-gallery
 * Version:           	2.2.6 - 57233
 * Author:            	2J Team (c)
 * Author URI:        	http://2joomla.net
 * License:           	GPL-2.0+
 * License URI:       	http://www.gnu.org/licenses/gpl-2.0.txt
 * Date:              	Thu, 26 Oct 2017 17:09:25 GMT
 */

function twoj_gallery_editor_wizard(){
	wp_enqueue_style('wp-jquery-ui-dialog');
	wp_enqueue_script('jquery-ui-dialog');

  	wp_enqueue_style ( 	TWOJ_GALLERY_ASSETS_PREFIX.'-editor-wizard-css', 	TWOJ_GALLERY_ASSETS_CSS_ADMIN_URL.'editor.wizard.css', array( ), TWOJ_GALLERY_VERSION );
		
  	wp_register_script( TWOJ_GALLERY_ASSETS_PREFIX.'-editor-wizard', 		TWOJ_GALLERY_ASSETS_JS_ADMIN_URL.'editor.wizard.js', array( 'jquery' ), TWOJ_GALLERY_VERSION, true );    

	wp_localize_script( TWOJ_GALLERY_ASSETS_PREFIX.'-editor-wizard', 'TwoJGalleryEditorWizardText', array( 
		'title' 		=> __('2J Gallery', 'blog-mechanics-theme-gallery'),
		'closeButton'	=> __('Close', 'blog-mechanics-theme-gallery'),
		'insertButton'	=> __('Add Shortcode', 'blog-mechanics-theme-gallery'),
	) );
	
	wp_enqueue_script( TWOJ_GALLERY_ASSETS_PREFIX.'-editor-wizard' );

  	echo 	'<a href="#twoj-gallery-editor-wizard-content" id="twoj-gallery-editor-wizard-button" class="button">'
  				.'<span class="dashicons dashicons-editor-kitchensink" style="margin: 4px 5px 0 0;"></span>'
  				.__( 'Add' , 'blog-mechanics-theme-gallery')
  				.' '
  				.__( '2J Gallery' , 'blog-mechanics-theme-gallery')
  			.'</a>';
	
	$args = array(
	    'child_of'     => 0,
	    'sort_order'   => 'ASC',
	    'sort_column'  => 'post_title',
	    'hierarchical' => 1,
	    'echo'		=> 0,
	    'post_type' => TWOJ_GALLERY_TYPE_POST
	);
  	echo '<div id="twoj-gallery-editor-wizard-content" style="display: none;">'
  			.'<p>'
  				.__('Use', 'blog-mechanics-theme-gallery')
  				.' <a href="edit.php?post_type='.TWOJ_GALLERY_TYPE_POST.'" class="button-link" target="_blank">'
  					.__( 'galleries manager', 'blog-mechanics-theme-gallery')
  				.'</a> '
  				.__('for settings configuration.', 'blog-mechanics-theme-gallery')
  			.'</p> '
  			.__('Here select one from the pre-configured galleries', 'blog-mechanics-theme-gallery').'<br /> '.wp_dropdown_pages( $args )
  		.'</div>';
}
add_action('media_buttons', 'twoj_gallery_editor_wizard', 15);
