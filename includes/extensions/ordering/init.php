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

if ( ! defined( 'ABSPATH' ) ) exit;

twoj_gallery_include('ordering.class.php',  plugin_dir_path( __FILE__ ) );
new Gallery_2J_Ordering( TWOJ_GALLERY_TYPE_POST );