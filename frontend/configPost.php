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

class twoJGalleryPostConfig extends twojGalleryPostConfigParent  {
	
	protected $config = array();

	public function __construct(){

	}

	public function getConfig(WP_Post $post){
		$galleryId = isset($_GET['root_gallery_id']) ? absint($_GET['root_gallery_id']) : null;

		if(!$galleryId){
			$postRoot = $post;
		} else {
			$postRoot = get_post($galleryId);
			if (!$postRoot || is_wp_error($postRoot)) {
				$postRoot = $post;
			}
		}

		$this->set('gallery/block/navigation/item/buttonSize', 		get_post_meta( $postRoot->ID, 'twoj_gallery_buttonSize', true ) );
		$this->set('gallery/block/navigation/item/buttonColor', 	get_post_meta( $postRoot->ID, 'twoj_gallery_buttonColor', true ) );
		$this->set('gallery/block/navigation/view', 				get_post_meta( $postRoot->ID, 'twoj_gallery_navigationView', true ), true );
		
        $this->set('gallery/block/content/item/image/preview/size', get_post_meta( $postRoot->ID, 'twoj_gallery_previewSize', true ) , true);

		if( $preset = get_post_meta( $postRoot->ID, 'twoj_gallery_gridPreset', true ) ){
			$this->set('gallery/block/content/grid/setting/preset', $preset);
			$this->set('gallery/block/content/grid/setting/textVerticalAlign',  "center" );

			switch ($preset) {
				case 'animtext':
						$this->set('gallery/block/content/grid/setting/textAnimationType',  "scale" );
					break;

				case 'curtain':
						$this->set('gallery/block/content/grid/setting/thumbnailOverlayOpacity',  "1" );	
						$this->set('gallery/block/content/grid/setting/imageTransitionDirection',  "left" );
					break;

				case 'movetext':
					break;

				case '3D':					break;					
				case 'scaletext':			break;										
				case 'scaleTextInverse': 	break;	

				case 'media':
						$this->set('gallery/block/content/grid/setting/textVerticalAlign',  "bottom" );
					break;

				case 'media2':				break;
			}

		}
		
		$this->set('gallery/block/content/grid/setting/gridType', 							get_post_meta( $postRoot->ID, 'twoj_gallery_gridType', true ), true );

		$this->set('gallery/block/content/grid/setting/horizontalSpaceBetweenThumbnails', 	(int) get_post_meta( $postRoot->ID, 'twoj_gallery_horizontalSpaceBetweenThumbnails', true ) );
		$this->set('gallery/block/content/grid/setting/verticalSpaceBetweenThumbnails', 	(int) get_post_meta( $postRoot->ID, 'twoj_gallery_verticalSpaceBetweenThumbnails', true ) );

		$this->set('gallery/block/content/grid/setting/backgroundColor', 					get_post_meta( $postRoot->ID, 'twoj_gallery_backgroundColor', true ) );
		$this->set('gallery/block/content/grid/setting/thumbnailBackgroundColor', 			get_post_meta( $postRoot->ID, 'twoj_gallery_thumbnailBackgroundColor', true ) );
		$this->set('gallery/block/content/grid/setting/thumbnailOverlayColor', 				get_post_meta( $postRoot->ID, 'twoj_gallery_thumbnailOverlayColor', true ) );
		
		$this->set('gallery/block/content/grid/setting/thumbnailMaxWidth', 					(int) get_post_meta( $postRoot->ID, 'twoj_gallery_thumbnailMaxWidth', true ) );
		$this->set('gallery/block/content/grid/setting/thumbnailMaxHeight', 				(int) get_post_meta( $postRoot->ID, 'twoj_gallery_thumbnailMaxHeight', true ) );
	
		$this->set('gallery/block/content/grid/setting/thumbnailsHorizontalOffset', 		(int) get_post_meta( $postRoot->ID, 'twoj_gallery_thumbnailsHorizontalOffset', true ) );
		$this->set('gallery/block/content/grid/setting/thumbnailsVerticalOffset', 			(int) get_post_meta( $postRoot->ID, 'twoj_gallery_thumbnailsVerticalOffset', true ) );
		
		$this->set('gallery/block/breadcrumbs/view', 										get_post_meta( $postRoot->ID, 'twoj_gallery_breadcrumbs', true), true  );
		$this->set('gallery/block/breadcrumbs/up/is_show', 									(int) get_post_meta( $postRoot->ID, 'twoj_gallery_breadcrumbsUpButton', true), true  );

		if( $paginationSize = get_post_meta( $postRoot->ID, 'twoj_gallery_paginationSize', true ) ){
			$this->set('gallery/block/pagination/numbering/setting/listStyle', "pagination-2j pagination-2j-".$paginationSize);			
		}
		
		$this->set('gallery/block/content/lightbox/setting/skin', 							get_post_meta( $postRoot->ID, 'twoj_gallery_lightboxSkin', true ), true  );
		$this->set('gallery/block/content/grid/setting/textColor', 							get_post_meta( $postRoot->ID, 'twoj_gallery_textColor', true ) );
		
		$this->set('gallery/block/content/grid/setting/hoverTextHide', 						get_post_meta( $postRoot->ID, 'twoj_gallery_hoverTextHide', true ) );

		$this->set('gallery/block/content/view_setting/subgalleries/image_type', 			get_post_meta( $postRoot->ID, 'twoj_gallery_galleryThumbnail', true ), true  );

		if(TWOJ_GALLERY_FULL_VERSION){
			$this->getConfigPremium( $postRoot->ID );	
		}

		return $this->config;
	}

	function getConfigNext(){
		
	}

	public function set($path, $value, $require = false){
		if($require && !$value) return ;
		$pieces = explode('/', $path);
		$lastPiece = array_pop($pieces);
		$config = &$this->config;

		foreach ($pieces as $piece) {
			if (!isset($config[$piece]) || !is_array($config[$piece])) {
				$config[$piece] = array();
			}
			$config = &$config[$piece];
		}
		$config[$lastPiece] = $value;
	}
}
