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

class TwoJGalleryFields{

	protected static $instance;

	protected $config;

	protected function __construct(){
		$this->config = new TwoJGalleryFieldsConfig();
	}

	public static function getInstance(){
		if (!self::$instance) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	public function init(){
		add_action('init', array($this, 'addMetaBoxes'));
		add_action('admin_enqueue_scripts', array($this, 'enqueueScripts'));
		add_filter('admin_body_class', array($this, 'adminBodyClass'));
	}

	public function addMetaBoxes(){
		foreach ((array)$this->config->get('metabox') as $name => $metaBoxConfig) {
			new TwoJGalleryFieldsMetaBoxClass($metaBoxConfig);
		}
	}

	public function enqueueScripts(){

		$screen = get_current_screen();
		if ('post' !== $screen->base) {
			return;
		}

		wp_enqueue_style('TwoJGalleryFields_app_css', 					TWOJ_GALLERY_FILEDS_URL . '/asset/core/css/app.css', array(), 1);
		wp_enqueue_style('colorpickersliders_colorpicker_css', 			TWOJ_GALLERY_FILEDS_URL . '/asset/jquery-colorpickersliders/jquery.colorpickersliders.css', array('TwoJGalleryFields_app_css'), 1);

		wp_enqueue_script('TwoJGalleryFields_foundation_js', 			TWOJ_GALLERY_FILEDS_URL . '/asset/foundation/foundation.min.js', array('jquery'), false, true);
		wp_enqueue_script('colorpickersliders_tinycolor_js', 			TWOJ_GALLERY_FILEDS_URL . '/asset/tinycolor/tinycolor.js', array('TwoJGalleryFields_foundation_js'), false, true);
		wp_enqueue_script('colorpickersliders_colorpickersliders_js', 	TWOJ_GALLERY_FILEDS_URL . '/asset/jquery-colorpickersliders/jquery.colorpickersliders.js', array('TwoJGalleryFields_foundation_js'), false, true);
		wp_enqueue_script('TwoJGalleryFields_app_js', 					TWOJ_GALLERY_FILEDS_URL . '/asset/core/js/app.js', array('TwoJGalleryFields_foundation_js'), false, true);
	}


	public function adminBodyClass($classes){
		return $classes . ' ' . TWOJ_GALLERY_FILEDS_BODY_CLASS;
	}

	public function getConfig(){
		return $this->config;
	}
}
