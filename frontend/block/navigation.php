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

class twoJGalleryNavigation implements twoJGalleryBlockInterface{

	const BLOCK_NAME 	= 'navigation';
	const VIEW_TREE 	= 'tree';
	const VIEW_LIST 	= 'list';
	const VIEW_HIDDEN 	= 'hidden';

	/**
	 * @var twoJGallery
	 */
	protected $gallery;

	public function __construct(twoJGallery $gallery){
		$this->gallery = $gallery;
	}

	/**
	 * @param string|null $view
	 * @return string
	 * @throws Exception
	 */
	public function getContent($view = null){
		if (!$view) {
			$view = $this->gallery->getConfig()->get('gallery/block/navigation/view');
		}

		switch ($view) {
			case 'list':
				return $this->getContentList();
			case 'tree':
				return $this->getContentTree();
			
			case 'hidden':
				return '';
		}
		throw new Exception(sprintf('Wrong Navigation block view: %s', $view));
	}

	public function getOptions( array $arrayInput  ){
		if( is_array( $arrayInput ) ) $options = $arrayInput;
			else $options = array();

		$options['buttonColor'] 	= $this->gallery->getConfig()->get('gallery/block/navigation/item/buttonColor');
		$options['buttonSize'] 		= $this->gallery->getConfig()->get('gallery/block/navigation/item/buttonSize');
		$options['itemClassActive'] = $this->gallery->getConfig()->get('gallery/block/navigation/item/class-active');
		return $options; 
	}

	/**
	 * @return string
	 */
	protected function getContentList(){
		$view = new twoJGalleryView();
		$navigationData = $this->getNavigationData();

		return $view->content(
			'block/navigation/list.php',
			$this->getOptions(
				array(
					'galleryId' 		=> $this->gallery->getData('post/ID'),
					'navigationData' 	=> $navigationData,
				)
			)
		);
	}

	/**
	 * @return string
	 */
	protected function getContentTree(){
		$view = new twoJGalleryView();
		$navigationData = $this->getNavigationData();

		return $view->content(
			'block/navigation/tree.php',
			$this->getOptions(
				array(
					'galleryId' 		=> $this->gallery->getData('post/ID'),
					'navigationTree' 	=> $this->buildTree($navigationData),
				)
			)
		);
	}

	/**
	 * @return array
	 */
	protected function getNavigationData(){
		return array_merge(array($this->gallery->getData()), $this->gallery->getSubGalleries());
	}

	/**
	 * @param array $galleries
	 * @return array
	 */
	protected function buildTree(array $galleries){
		$galleryMap = array();
		$galleryTree = array();

		// Building tree
		foreach ($galleries as $gallery) {
			if (isset($galleryMap[$gallery['post']['ID']])) {
				$galleryMap[$gallery['post']['ID']]['gallery'] = $gallery;
				$galleryData = &$galleryMap[$gallery['post']['ID']];
			} else {
				$galleryData = array('gallery' => $gallery, 'children' => array());
				$galleryMap[$gallery['post']['ID']] = &$galleryData;
			}
			if (0 == $gallery['post']['post_parent']) {
				$galleryTree["{$gallery['post']['menu_order']}-{$gallery['post']['ID']}"] = &$galleryData;
			} else {
				$galleryMap[$gallery['post']['post_parent']]['children'][$gallery['post']['ID']] = &$galleryData;
			}
			unset($galleryData);
		}

		// Adding children posts with lost parent to tree
		foreach ($galleryMap as &$galleryData) {
			if (!isset($galleryData['gallery']) && is_array($galleryData['children'])) {
				foreach ($galleryData['children'] as &$childGalleryData) {
					$childGallery = $childGalleryData['gallery'];
					$galleryTree["{$childGallery['post']['menu_order']}-{$childGallery['post']['ID']}"] = &$childGalleryData;
				}
			}
		}

		asort($galleryTree);
		return $galleryTree;
	}
}
