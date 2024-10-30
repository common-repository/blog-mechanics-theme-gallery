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

if(  !TWOJ_GALLERY_FULL_VERSION ){

	if(!function_exists('twoJGalleryJSDialog')){
		function twoJGalleryJSDialog(){

			$newPost = twoJGalleryIsEdit('new') && !isset( $_GET['post'] );
			$openDialog = 0;
			if( $newPost ){
	        	$n=4;
	        	$all_wp_pages = wp_count_posts (TWOJ_GALLERY_TYPE_POST );
	        	$allCount =
	        		$all_wp_pages->publish +
	        		$all_wp_pages->draft +
	        		$all_wp_pages->future +
	        		$all_wp_pages->pending +
	        		$all_wp_pages->private +
	        		$all_wp_pages->trash;
	        	if( $allCount >= ++$n &&  ( ($allCount % $n) == 0 ) ) $openDialog = 1;
			}
			wp_enqueue_style("wp-jquery-ui-dialog");
			wp_enqueue_script('jquery-ui-dialog');

			wp_enqueue_script( TWOJ_GALLERY_ASSETS_PREFIX.'-desc', TWOJ_GALLERY_ASSETS_JS_ADMIN_URL.'dialog.js', array( 'jquery' ), TWOJ_GALLERY_VERSION, true );
			wp_enqueue_style ( TWOJ_GALLERY_ASSETS_PREFIX.'-desc', TWOJ_GALLERY_ASSETS_CSS_ADMIN_URL.'dialog.css', array( ), '' );

			echo '<div id="twoj_gallery_dialog_options" '
						.'style="display: none;" '
						.'data-open="'.( $openDialog ? 1 : 0 ).'" '
						.'data-title="'.__('2J Gallery', 'blog-mechanics-theme-gallery').' '.__('Premium', 'blog-mechanics-theme-gallery').'" '
						.'data-close="'.__('Continue with Free version', 'blog-mechanics-theme-gallery').'" '
						.'data-info="'.	__('Buy Premium', 'blog-mechanics-theme-gallery').'"'
					.'>'
					.__('Get full freedom and much more functionality with', 'blog-mechanics-theme-gallery')
					.' '
					.__('2J Gallery', 'blog-mechanics-theme-gallery')
					.__('Premium version', 'blog-mechanics-theme-gallery')
				.' </div>';

		}
		add_action( 'in_admin_header', 'twoJGalleryJSDialog' );
	}
}
