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

if( get_option( 'twoj_gallery_fields_feedback' ) ) return array();

function twoj_gallery_fields_feedback_scripts(){
	wp_register_script( TWOJ_GALLERY_ASSETS_PREFIX.'-field-type-feedback', TWOJ_GALLERY_FILEDS_URL.'asset/metabox/feedback/script.js', array('jquery'), TWOJ_GALLERY_VERSION, true);
	$translation_array = array(
		'messageOk' => 				__( 'Thank you very much for your feedback!', 'blog-mechanics-theme-gallery'),
		'messageError' => 			__( 'Submit error. Please try again later.',  'blog-mechanics-theme-gallery'),
		'messageEmpty' => 			__( 'Please fill the form before click on send button.',  'blog-mechanics-theme-gallery'),
		'messageCorrectEmail' => 	__( 'Incorrect email.',  'blog-mechanics-theme-gallery'),
	);
	wp_localize_script( TWOJ_GALLERY_ASSETS_PREFIX.'-field-type-feedback', 'twojGalleryFeedbackTr', $translation_array );
	wp_enqueue_script( 	TWOJ_GALLERY_ASSETS_PREFIX.'-field-type-feedback' );
}

add_action( 'in_admin_header',  'twoj_gallery_fields_feedback_scripts' );

return array(
	'active' => true,
	'order' => 8,
	'settings' => array(
		'id' => 'twoj_gallery_feedback',
		'title' => 'Speedy Contact',
		'screen' => array(TWOJ_GALLERY_TYPE_POST),
		'context' => 'side',
		'priority' => 'default',
		'callback_args' => null,
	),
	'view' => 'default',
	'state' => 'open',
	'style' => null,
	'content' => 'template::content/feedback/content',
);
