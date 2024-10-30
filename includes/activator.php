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

class TwoJGalleryActivator {
	public static function activate(){
		delete_option("2JGalleryCheckAfterInstall");
		add_option( '2JGalleryCheckAfterInstall', '1' );
	}
	public static function deactivate(){}
}
