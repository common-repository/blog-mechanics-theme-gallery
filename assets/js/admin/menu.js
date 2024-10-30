/*  
 * 2J Gallery			http://2joomla.net/wordpress-plugins/2j-gallery
 * Version:           	2.2.6 - 57233
 * Author:            	2J Team (c)
 * Author URI:        	http://2joomla.net
 * License:           	GPL-2.0+
 * License URI:       	http://www.gnu.org/licenses/gpl-2.0.txt
 * Date:              	Thu, 26 Oct 2017 17:09:25 GMT
 */

(function($){
	var urlOpen = twoj_gallery_js_text.urlOpen;
	var urlFind = 'edit.php?post_type='+twoj_gallery_js_text.TWOJ_GALLERY_TYPE_POST+'&page='+twoj_gallery_js_text.TWOJ_GALLERY;

	jQuery('a[href="'+urlFind+'support"]').click( function(event ){
		event.preventDefault();
		window.open(urlOpen+"support", "_blank");
	});
	jQuery('a[href="'+urlFind+'demo"]').click( function(event ){
		event.preventDefault();
		window.open(urlOpen+"demo", "_blank");
	});
	jQuery('a[href="'+urlFind+'guides"]').click( function(event ){
		event.preventDefault();
		window.open(urlOpen+"guides", "_blank");
	});

	jQuery('.twojgallery_get_premium_version_blank').click( function(event ){
		event.preventDefault();
		window.open(urlOpen+"goPremium",'_blank');
	});
	jQuery('a[href="'+urlFind+'goPremium"]').click( function(event ){
		event.preventDefault();
		window.open(urlOpen+"goPremium",'_blank');
	});
})(jQuery);