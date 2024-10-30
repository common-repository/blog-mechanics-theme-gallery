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

class twoJGalleryContent implements twoJGalleryBlockInterface{

	const BLOCK_NAME 					= 'content';
	const VIEW_SUBGALLERIES_IMAGES 		= 'subgalleries_images';
	const VIEW_SUBGALLERIES 			= 'subgalleries';
	const VIEW_IMAGES 					= 'images';
	const GALLERY_IMAGE_TYPE_SELECTED 	= 'selected';
	const GALLERY_IMAGE_TYPE_FIRST 		= 'first';
	const GALLERY_IMAGE_TYPE_RANDOM 	= 'random';
	const GALLERY_LINK_TYPE_SELF 		= 'self';
	const GALLERY_LINK_TYPE_BLANK 		= 'blank';
	const GALLERY_LINK_TYPE_VIDEO 		= 'video';

	/**
	 * @var twoJGallery
	 */
	protected $gallery;

	/**
	 * @constructor
	 * @param twoJGallery $gallery
	 */
	public function __construct(twoJGallery $gallery){
		$this->gallery = $gallery;
	}

	/**
	 * @param string|null $view
	 * @return string
	 */
	public function getContent($view = null){
		$view = new twoJGalleryView();

		return $view->content(
			'block/content.php',
			array(
				'items' 	=> $this->getPageItems(),
				'textColor' => $this->gallery->getConfig()->get('gallery/block/content/grid/setting/textColor'),
				'hoverTextHide' => $this->gallery->getConfig()->get('gallery/block/content/grid/setting/hoverTextHide'),
			)
		);
	}

	/**
	 * Get block view
	 *
	 * @return string
	 */
	public function getView(){
		$defaultView = $this->gallery->getConfig()->get('gallery/block/content/view');

		if (!isset($_GET['gallery_id']) || $_GET['gallery_id'] != $this->gallery->getData('post/ID')) {
			return $defaultView;
		}
		
		return isset($_GET[self::BLOCK_NAME]['view'])
			? ((string) $_GET[self::BLOCK_NAME]['view'])
			: $defaultView;
	}

	/**
	 * @return array
	 */
	protected function getPageItems(){
		$currentPage = $this->gallery->getBlockPagination()->getCurrentPage();
		$itemPerPage = $this->gallery->getConfig()->get('gallery/block/content/per_page');
		$itemOffset = absint($currentPage - 1) * $itemPerPage;
		$itemIds = $this->gallery->getItemIds();
		if (0 < $itemPerPage) {
			$itemIds = array_slice($itemIds, $itemOffset, $itemPerPage);
		}
		return $this->gallery->getItems($itemIds);
	}
}
