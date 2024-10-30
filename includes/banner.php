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

if(!function_exists('twoJGalleryBanner')){
	function twoJGalleryBanner(){
		
		wp_enqueue_style ( 	TWOJ_GALLERY_ASSETS_PREFIX.'-banner-css', TWOJ_GALLERY_ASSETS_CSS_ADMIN_URL.'banner.css', array( ), TWOJ_GALLERY_VERSION );
		
		echo '<div class="twoJGalleryBanner twojgallery_get_premium_version_blank">
				<div class="twoJGalleryBannerUp">'.		__( 'Get full freedom with Premium version', 'blog-mechanics-theme-gallery').' </div>
				<div class="twoJGalleryBannerBottom">'.	__( 'BUY NOW', 'blog-mechanics-theme-gallery').'</div>	
			</div>';
	}
	if(!TWOJ_GALLERY_FULL_VERSION) add_action( 'in_admin_header', 'twoJGalleryBanner' );
}
