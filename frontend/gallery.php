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


class twoJGallery{

	const POST_TYPE = TWOJ_GALLERY_TYPE_POST;

	protected $config;

	protected $options;

	protected $blockNavigation;

	protected $blockContent;

	protected $blockBreadcrumbs;

	protected $blockPagination;

	/**
	 * Gallery data:
	 * - post: post fields
	 * - post_meta: all post_meta fields
	 */
	protected $gallery;

	protected $subGalleryIds;

	protected $subGalleries;

	protected $imageIds;

	protected $itemIds;


	public function __construct(WP_Post $post){
		
		new twoJGalleryRegistrator();

		$this->config = new twoJGalleryConfig($post);
		//echo $post->ID;
		$this->options = new twoJGalleryOptions($post);
		//print_r($this->options);
		$this->gallery = array(
			'post' => (array)$post,
			'post_meta' => $this->getPostMeta($post->ID)
		);
	}


	public function getConfig(){
		return $this->config;
	}

	public function getOptions(){
		return $this->options;
	}


	public function getContent(){
		$view = new twoJGalleryView();
		$blockNavigationView 	= $this->config->get('gallery/block/navigation/view');
		$blockContentView 		= $this->config->get('gallery/block/content/view');
		$blockPaginationView 	= $this->config->get('gallery/block/pagination/view');

		return $view->content(
			'gallery.php',
			array(
				'gallery' 				=> $this->gallery,
				'blockNavigationView' 	=> $blockNavigationView,
				'blockNavigation' 		=> $this->getBlockNavigation()->getContent($blockNavigationView),
				'blockContentView' 		=> $blockContentView,
				'blockContent' 			=> $this->getBlockContent()->getContent($blockContentView),
				'blockBreadcrumbs' 		=> $this->getBlockBreadcrumbs()->getContent(),
				'blockPaginationView' 	=> $blockPaginationView,
				'blockPagination' 		=> $this->getBlockPagination()->getContent($blockPaginationView),

				'options' 				=> $this->options,

				'config' 				=> $this->config->get(),

				//'mainDivStyle' 			=> $this->getMainDivStyle(),
			)
		);
	}

	

	public function getBlockNavigation(){
		if (!$this->blockNavigation) {
			$this->blockNavigation = new twoJGalleryNavigation($this);
		}
		return $this->blockNavigation;
	}


	public function getBlockContent(){
		if (!$this->blockContent) {
			$this->blockContent = new twoJGalleryContent($this);
		}
		return $this->blockContent;
	}


	public function getBlockBreadcrumbs(){
		if (!$this->blockBreadcrumbs) {
			$this->blockBreadcrumbs = new twoJGalleryBreadcrumbs($this);
		}
		return $this->blockBreadcrumbs;
	}


	public function getBlockPagination(){
		if (!$this->blockPagination) {
			$this->blockPagination = new twoJGalleryPagination($this);
		}
		return $this->blockPagination;
	}

	/**
	 * Get field value from gallery data by path
	 */
	public function getData($path = ''){
		return twojg_array_get_by_path($this->gallery, $path);
	}


	protected function getSubGalleryIds(){

		if (!$this->subGalleryIds) {
			$this->subGalleryIds = array();
			foreach ($this->getSubGalleries() as $subGallery) {
				$this->subGalleryIds[] = $subGallery['post']['ID'];
			}
		}

		return $this->subGalleryIds;
	}


	public function getSubGalleries(){

		if (!$this->subGalleries) {
			$subGalleryPosts = get_pages(array(
				'sort_column' 	=> 'menu_order',
				'order' 		=> 'ASC',
				'child_of' 		=> $this->gallery['post']['ID'],
				'post_type' 	=> twoJGallery::POST_TYPE,
				'post_status' 	=> 'publish'
			));
			$galleryIds = array();

			// fetch full postmeta for all posts in one query
			foreach ($subGalleryPosts as $subGalleryPost) {
				$galleryIds[] = $subGalleryPost->ID;
			}
			update_postmeta_cache($galleryIds);
			
			$this->subGalleries = array();
			foreach ($subGalleryPosts as $subGalleryPost) {
				$this->subGalleries[$subGalleryPost->ID] = array(
					'post' => (array)$subGalleryPost,
					'post_meta' => $this->getPostMeta($subGalleryPost->ID)
				);
			}
		}

		return $this->subGalleries;
	}


	public function getImageIds(){

		if (!$this->imageIds) {
			$galleries= array_merge( array($this->gallery) ); //, $this->getSubGalleries()
			$this->imageIds = array();

			foreach ($galleries as $gallery) {
				$images = $this->getPostMetaImages($gallery);
				$this->imageIds = array_merge($this->imageIds, $images);
			}
		}

		return $this->imageIds;
	}


	public function getItemIds(){

		switch ($this->getBlockContent()->getView()) {
			case twoJGalleryContent::VIEW_SUBGALLERIES_IMAGES:
				return array_merge($this->getSubGalleryIds(), $this->getImageIds());
			case twoJGalleryContent::VIEW_SUBGALLERIES:
				return $this->getSubGalleryIds();
			case twoJGalleryContent::VIEW_IMAGES:
			default:
				return $this->getImageIds();
		}
	}

	/**
	 * Get items data
	 */
	public function getItems(array $itemIds){
		$contentView = $this->getBlockContent()->getView();
		$imagePreviewSize = $this->config->get('gallery/block/content/item/image/preview/size');
		$imageLightboxSize = $this->config->get('gallery/block/content/item/image/lightbox/size');
		$linkTypes = array(
			twoJGalleryContent::GALLERY_LINK_TYPE_SELF,
			twoJGalleryContent::GALLERY_LINK_TYPE_BLANK,
			twoJGalleryContent::GALLERY_LINK_TYPE_VIDEO
		);
		$itemFields = $this->config->get('gallery/block/content/item/fields');
		$postArgs = array(
			'include' 		=> $itemIds,
			'post_type' 	=> array(self::POST_TYPE, 'attachment'),
			'post_status' 	=> array('publish', 'inherit'),
			'numberposts' 	=> -1,
		);
		$postsData= array();
		$items = array();

		// if $itemIds is empty wp will fetch all posts
		if (!empty($itemIds)) {
			// fetch full postmeta for all posts in one query
			update_postmeta_cache($itemIds);
			foreach (get_posts($postArgs) as $post) {
				$postsData[$post->ID] = array(
					'post' => (array)$post,
					'post_meta' => $this->getPostMeta($post->ID)
				);
			}
		}

		foreach ($itemIds as $itemId) {
			$postData = $postsData[$itemId];
			$isGallery = self::POST_TYPE == $postData['post']['post_type'];
			$thumbnailId = $this->getThumbnailId($postData);
			$linkValue = twojg_array_get_by_path($postData, $this->config->get('gallery/block/content/item/link/value'));
			$linkType = $linkValue
				? twojg_array_get_by_path($postData, $this->config->get('gallery/block/content/item/link/type'))
				: null;

			// prepare required fields
			$items[$itemId] = array(
				'ID' => $postData['post']['ID'],
				'type' => $isGallery ? 'gallery' : 'image',
				'image' => array(
					'preview' => array(
						'url' => wp_get_attachment_image_url($thumbnailId, $imagePreviewSize)
					)
				),
				'link' => array(
					'value' => twoJGalleryContent::VIEW_SUBGALLERIES_IMAGES === $contentView && $linkValue  			//VIEW_SUBGALLERIES
						? $linkValue
						: wp_get_attachment_image_url($thumbnailId, $imageLightboxSize),
					'type' => twoJGalleryContent::VIEW_SUBGALLERIES_IMAGES === $contentView && $linkValue				//VIEW_SUBGALLERIES
						? (in_array($linkType, $linkTypes) ? $linkType : twoJGalleryContent::GALLERY_LINK_TYPE_SELF)
						: ($isGallery && twoJGalleryContent::VIEW_SUBGALLERIES !== $contentView ? 'gallery' : 'image')
				),
				'style' => $this->getStyle($thumbnailId, $postData)
			);

			
			if(	2==3 && !$isGallery && 
				isset( $postsData[$itemId]['post_meta']['_wp_attachment_metadata']) && 
				isset( $postsData[$itemId]['post_meta']['_wp_attachment_metadata'][0]) 
			){
				$items[$itemId]['image']['size'] = array(
					'width'		=>	$postsData[$itemId]['post_meta']['_wp_attachment_metadata'][0]['width'],
					'height'	=>	$postsData[$itemId]['post_meta']['_wp_attachment_metadata'][0]['height'],
				);

			}

			// prepare fields from configuration
			foreach ($itemFields as $fieldName => $fieldPath) {
				$items[$itemId][$fieldName] = false === $fieldPath
					? false
					: twojg_array_get_by_path($postData, $fieldPath);
			}
		}

		return $items;
	}

	/**
	 * Get and unserialize post meta
	 */
	protected function getPostMeta($postId){
		$postMeta = get_post_meta($postId);

		foreach ($postMeta as $key => $value)  {
			foreach ($value as $i => $item) {
				if (is_serialized($item)) {
					$postMeta[$key][$i] = unserialize($item);
				}
			}
		}

		return $postMeta;
	}

	/**
	 * Get images from post meta
	 */
	protected function getPostMetaImages(array $data){
		$images = twojg_array_get_by_path($data, $this->config->get('gallery/gallery/images'));
		$images = is_string($images) ? explode(',', $images) : array();
		return array_filter(array_map('absint', $images));
	}

	/**
	 * Get thumbnail id
	 */
	protected function getThumbnailId(array $data){
		if ('attachment' === $data['post']['post_type']) {
			return $data['post']['ID'];
		}

		/*if (twoJGalleryContent::VIEW_SUBGALLERIES != $this->getBlockContent()->getView()) {
			$thumbnailId = get_post_thumbnail_id($data['post']['ID']);
			return $thumbnailId ? $thumbnailId : $this->config->get('gallery/default/gallery/thumbnail_id');
		}*/

		$imageType = $this->config->get('gallery/block/content/view_setting/subgalleries/image_type');
		$images = $this->getPostMetaImages($data);
		switch ($imageType) {
			case twoJGalleryContent::GALLERY_IMAGE_TYPE_SELECTED:
				$image = twojg_array_get_by_path(
					$data,
					$this->config->get('gallery/block/content/view_setting/subgalleries/image_selected')
				);
				$thumbnailId = in_array($image, $images) ? $image : null;
				break;
			case twoJGalleryContent::GALLERY_IMAGE_TYPE_RANDOM:
				$imageKey = empty($images) ? null : array_rand($images, 1);
				$thumbnailId = null === $imageKey ? null : $images[$imageKey];
				break;
			case twoJGalleryContent::GALLERY_IMAGE_TYPE_FIRST:
			default:
				$thumbnailId = empty($images) ? null : reset($images);
				break;
		}
		
		return $thumbnailId ? $thumbnailId : $this->config->get('gallery/default/gallery/thumbnail_id');
	}

	/**
	 * Get styles of item fields
	 */
	protected function getStyle($thumbnailId, array $data){
		// get default style
		$style = $this->config->get('gallery/default/block/content/item/style');

		// get item style
		$itemStyle = $this->getPostMetaStyle($data);
		foreach ($itemStyle as $fieldName => $types) {
			foreach ($types as $type => $params) {
				$style[$fieldName][$type] = array_merge(
					isset($style[$fieldName][$type]) ? $style[$fieldName][$type]: array(),
					$params
				);
			}
		}

		// get thumbnail style
		if ($thumbnailId !== $data['post']['ID']) {
			$thumbnailData = array('post_meta' => $this->getPostMeta($thumbnailId));
			$thumbnailStyle = $this->getPostMetaStyle($thumbnailData);
			foreach ($thumbnailStyle as $fieldName => $types) {
				foreach ($types as $type => $params) {
					$style[$fieldName][$type] = array_merge(
						isset($style[$fieldName][$type]) ? $style[$fieldName][$type]: array(),
						$params
					);
				}
			}
		}

		return $style;
	}

	/**
	 * Get styles from post meta
	 */
	protected function getPostMetaStyle(array $data){
		$style = array();

		foreach ($this->config->get('gallery/block/content/item/style') as $fieldName => $config) {
			foreach ($config as $type => $params) {
				foreach ($params as $paramName => $paramPath) {
					$param = twojg_array_get_by_path(
						$data,
						$this->config->get("gallery/block/content/item/style/{$fieldName}/{$type}/{$paramName}")
					);
					if (null !== $param) {
						$style[$fieldName][$type][$paramName] = $param;
					}
				}
			}
		}

		return $style;
	}
}
