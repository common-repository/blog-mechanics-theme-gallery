<?php
/**
 * Plugin Name: 2J Gallery
 * Plugin URI: http://2joomla.net/wordpress-plugins/2j-gallery
 * Description: 2J Gallery powerful photo gallery with eye-catching interface and amazing hover animation effects
 * Version: 2.3.4
 * Author: 2jgallery
 * Author URI: http://2joomla.net/wordpress-plugins/2j-gallery
 * License: GNU/GPLv3
 * Text Domain: blog-mechanics-theme-gallery
 * Domain Path: /languages
 */

if ( ! defined( 'WPINC' ) )  die;

define("TWOJ_GALLERY_PLUGIN", 			1);
define("TWOJ_GALLERY_VERSION", 			"2.3.4");
define("TWOJ_GALLERY_PATH", 			plugin_dir_path( __FILE__ ));

add_action( 'plugins_loaded', 'twoj_gallery_load_textdomain' );
function twoj_gallery_load_textdomain() {
  load_plugin_textdomain( 'blog-mechanics-theme-gallery', false, dirname(plugin_basename( __FILE__ )) . '/languages' );
}

define("TWOJ_GALLERY_INCLUDE_PATH",     TWOJ_GALLERY_PATH.'includes/');
define("TWOJ_GALLERY_FRONTEND_PATH",    TWOJ_GALLERY_PATH.'frontend/');
define("TWOJ_GALLERY_EXTENSIONS_PATH",  TWOJ_GALLERY_INCLUDE_PATH.'extensions/');
define("TWOJ_GALLERY_FILEDS_MODULE",  	TWOJ_GALLERY_EXTENSIONS_PATH.'fields/');

define("TWOJ_GALLERY_URL",              		    plugin_dir_url( __FILE__ ));
define("TWOJ_GALLERY_ASSETS_URL",       		    TWOJ_GALLERY_URL.'assets/');
define("TWOJ_GALLERY_ASSETS_JS_URL",    		    TWOJ_GALLERY_ASSETS_URL.'js/');
define("TWOJ_GALLERY_ASSETS_JS_ADMIN_URL",    	TWOJ_GALLERY_ASSETS_JS_URL.'admin/');
define("TWOJ_GALLERY_ASSETS_CSS_URL",    		     TWOJ_GALLERY_ASSETS_URL.'css/');
define("TWOJ_GALLERY_ASSETS_CSS_ADMIN_URL",    	TWOJ_GALLERY_ASSETS_CSS_URL.'admin/');

define( "TWOJ_GALLERY",     					'twoj_gallery_');

define( "TWOJ_GALLERY_TYPE_POST",  				'2j_gallery');
define( "TWOJ_GALLERY_ASSETS_PREFIX",  			'2jgallery-');


if(!function_exists('twoj_gallery_full_check')){
  function twoj_gallery_full_check(){
    $proPath  = '';
    $key_dir    = '2jgallerykey';
    $key_file   = $key_dir.'.php';
    $fullPath = TWOJ_GALLERY_PATH.$key_file;
    if( file_exists($proPath) ) return $fullPath;
    for($i=-1;$i<6;$i++){
      $proPath = WP_PLUGIN_DIR.'/'.$key_dir.($i!=-1?'-'.$i:'').'/'.$key_file;
      if ( file_exists($proPath) ) return $proPath;
    }
    return false;
  }
}

if( $keyPath = twoj_gallery_full_check() ){
  define("TWOJ_GALLERY_FULL_VERSION", 1);
  define("TWOJ_GALLERY_FULL_PATH", $keyPath );
  require_once($keyPath);
} else {
  define("TWOJ_GALLERY_FULL_VERSION", 0);
}

function activateTwoJGallery() {
  require_once TWOJ_GALLERY_INCLUDE_PATH.'activator.php';
  TwoJGalleryActivator::activate();
}
register_activation_hook( __FILE__, 'activateTwoJGallery' );

function deactivateTwoJGallery() {
  require_once TWOJ_GALLERY_INCLUDE_PATH.'activator.php';
  TwoJGalleryActivator::deactivate();
}
register_deactivation_hook( __FILE__, 'deactivateTwoJGallery' );

if( file_exists(TWOJ_GALLERY_INCLUDE_PATH.'init.php') ) require_once TWOJ_GALLERY_INCLUDE_PATH.'init.php';
