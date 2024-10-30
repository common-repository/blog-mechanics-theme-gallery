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


class twoJGalleryAjax{

	public function __construct(){
		add_action('init', array($this, 'init'));
	}


	public function init(){
		add_action('wp_ajax_'. 			twoJGallery::POST_TYPE . '_config', 	array($this, 'config'));
		add_action('wp_ajax_nopriv_'. 	twoJGallery::POST_TYPE . '_config', 	array($this, 'config'));
		add_action('wp_ajax_'. 			twoJGallery::POST_TYPE . '_block',		array($this, 'block'));
		add_action('wp_ajax_nopriv_'. 	twoJGallery::POST_TYPE . '_block', 		array($this, 'block'));
	}

	public function config(){
		if (!isset($_GET['gallery_id'])) {
			header('HTTP/1.0 403 Forbidden');
			die( 'Invalid request. Gallery id is absent.' );
		}

		$post = get_post(absint($_GET['gallery_id']));
		$gallery = new twoJGallery($post);
		
		echo json_encode($gallery->getConfig()->get());
		die();
	}


	public function block(){
		$galleryId = isset($_GET['gallery_id']) 		? absint($_GET['gallery_id']) 	: null;
		$blockName = isset($_GET['block']) 				? $_GET['block'] 				: null;
		$blockView = isset($_GET[$blockName]['view']) 	? $_GET[$blockName]['view'] 	: null;

		if (!$galleryId) {
			header('HTTP/1.0 403 Forbidden');
			die( 'Invalid request. Gallery ID is empty.');
		}
		if (!$blockName) {
			header('HTTP/1.0 403 Forbidden');
			die( 'Invalid request. Block name empty.' );
		}

		$post = get_post($galleryId);
		if (!$post || is_wp_error($post)) {
			header('HTTP/1.0 403 Forbidden');
			die( 'Cannot find post.' );
		}

		$gallery = new twoJGallery($post);
		switch ($blockName) {
			case twoJGalleryNavigation::BLOCK_NAME:
				$block = $gallery->getBlockNavigation();
				break;
			case twoJGalleryContent::BLOCK_NAME:
				$block = $gallery->getBlockContent();
				break;
			case twoJGalleryBreadcrumbs::BLOCK_NAME:
				$block = $gallery->getBlockBreadcrumbs();
				break;
			case twoJGalleryPagination::BLOCK_NAME:
				$block = $gallery->getBlockPagination();
				break;
			default:
				$block = null;
		}

		if (null === $block) {
			header('HTTP/1.0 403 Forbidden');
			die( 'Cannot find block.');
		}

		echo $block->getContent($blockView);
		die();
	}
}
