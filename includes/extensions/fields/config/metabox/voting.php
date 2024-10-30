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

if( get_option( 'twoj_gallery_fields_voting1' ) || TwoJGalleryHelper::checkEvent() ) return array();

function twoj_gallery_fields_voting_scripts(){
	wp_register_script( TWOJ_GALLERY_ASSETS_PREFIX.'-field-type-voting', TWOJ_GALLERY_FILEDS_URL.'asset/metabox/voting/script.js', array('jquery'), TWOJ_GALLERY_VERSION, true);
	$translation_array = array(
		'messageOk' 	=> 	__( 'Thank you very much for your opinion!', 'blog-mechanics-theme-gallery'),
		'messageError' 	=> 	__( 'Voting error. Please try again later.', 'blog-mechanics-theme-gallery')
	);
	wp_localize_script( TWOJ_GALLERY_ASSETS_PREFIX.'-field-type-voting', 'twojGalleryVotingTr', $translation_array );
	wp_enqueue_script( TWOJ_GALLERY_ASSETS_PREFIX.'-field-type-voting' );
}

add_action( 'in_admin_header',  'twoj_gallery_fields_voting_scripts' );

return array(
	'active' => true,
	'order' => 8,
	'settings' => array(
		'id' => 'twoj_gallery_voting',
		'title' => 'Vote for New Feature',
		'screen' => array(TWOJ_GALLERY_TYPE_POST),
		'context' => 'side',
		'priority' => 'high',
		'callback_args' => null,
	),
	'view' => 'default',
	'state' => 'open',
	'style' => null,
	'content' => 'template::content/voting/content',
);
