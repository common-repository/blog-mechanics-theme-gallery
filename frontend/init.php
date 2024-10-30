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

define('TWOJ_GALLERY_DIR', 			dirname(__FILE__).'/');

define('TWOJ_GALLERY_URI', 			plugin_dir_url(__FILE__));

define('TWOJ_GALLERY_TEMPLATE_DIR', 	TWOJ_GALLERY_DIR.'template/');
define('TWOJ_GALLERY_BLOCK_DIR', 		TWOJ_GALLERY_DIR.'block/');

if (!class_exists('twojGalleryPostConfigParent')){
	class twojGalleryPostConfigParent{};
}

twoj_gallery_include( array(
	'function.php', 
	'configPost.php', 
	'config.php', 
	'options.php', 
	'registrator.php', 
	'ajax.php', 
	'view.php', 
	'gallery.php' 
), TWOJ_GALLERY_FRONTEND_PATH);

twoj_gallery_include( array(
	'blockInterface.php', 
	'navigation.php', 
	'content.php', 
	'breadcrumbs.php', 
	'pagination.php', 
), TWOJ_GALLERY_BLOCK_DIR);


/*add_action( 'wp_enqueue_scripts', 'twoj_gallery_clear_js', 1 );
function twoj_gallery_clear_js(){ remove_action( 'wp_enqueue_scripts', 'rw_jquery_updater' ); }*/


new twoJGalleryAjax();
