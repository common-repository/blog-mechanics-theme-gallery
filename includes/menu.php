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


class TwoJGalleryMenu{

	private $urlOpen = "http://www.2joomla.net/products_info/goto.php?type=wpgallery&content=";

	function __construct() {
		$this->hook();
	}

	public function hook(){
		add_action( 'init', 			array($this, 'fixMenu') );
		add_action( 'admin_menu',		array($this, 'addMenu') );
		add_action( 'in_admin_header',  array($this, 'includeScript') );
	}

	public function fixMenu(){
		$this->checkPostType('support');
		$this->checkPostType('demo');
		$this->checkPostType('guides');
		$this->checkPostType('goPremium');
	}

	public function checkPostType( $page='' ){
		$result = false;
		if( isset($_GET['post_type']) && $_GET['post_type']== TWOJ_GALLERY_TYPE_POST )  $result = true;
		if($page && $result){
				if( !isset($_GET['page']) || $_GET['page'] != TWOJ_GALLERY.$page ) $result = false;
		}
		if( $result ) wp_redirect( $this->urlOpen.$page );
	}

	public function addMenu(){
		add_submenu_page( 	
			'edit.php?post_type='.TWOJ_GALLERY_TYPE_POST, 
			'2J Gallery Settings', 
			'Settings', 
			'manage_options', 
			TWOJ_GALLERY.'settings', 
			array($this,'settingPage')
		);

		add_submenu_page( 
			'edit.php?post_type='.TWOJ_GALLERY_TYPE_POST, 
			'2J Gallery Support', 
			'Support', 
			'manage_options', 
			TWOJ_GALLERY.'support', 
			array($this,'pageInclude')
		);

		if(!TWOJ_GALLERY_FULL_VERSION){
			add_submenu_page( 
				'edit.php?post_type='.TWOJ_GALLERY_TYPE_POST, 
				'Buy Premium', 
				'Buy Premium', 
				'manage_options', 
				TWOJ_GALLERY.'goPremium', 
				array($this,'pageInclude')
			);
		}

		add_submenu_page( 
			'edit.php?post_type='.TWOJ_GALLERY_TYPE_POST, 
			'2J Gallery Demo', 
			'Gallery Demo', 
			'manage_options',
			TWOJ_GALLERY.'demo', 
			array($this,'pageInclude') 
		);

		add_submenu_page( 
			'edit.php?post_type='.TWOJ_GALLERY_TYPE_POST, 
			'2J Gallery Video Guides', 
			'Video Guides', 
			'manage_options', 
			TWOJ_GALLERY.'guides', 
			array($this,'pageInclude')
		);
	}

	public function settingPage(){	$this->pageInclude('options.php'); 	}

	function pageInclude( $fileName = '' ){
		if($fileName) twoj_gallery_include( $fileName );
	}

	function includeScript( ){
		wp_register_script( TWOJ_GALLERY_ASSETS_PREFIX.'-menu', TWOJ_GALLERY_ASSETS_JS_ADMIN_URL.'menu.js', array( 'jquery' ), TWOJ_GALLERY_VERSION, true);
		wp_localize_script( TWOJ_GALLERY_ASSETS_PREFIX.'-menu', 'twoj_gallery_js_text', array(
			'TWOJ_GALLERY_TYPE_POST' 	=> TWOJ_GALLERY_TYPE_POST,
			'TWOJ_GALLERY' 				=> TWOJ_GALLERY,
			'urlOpen' 					=> $this->urlOpen,
		));
		wp_enqueue_script( TWOJ_GALLERY_ASSETS_PREFIX.'-menu' ); 
	}
}


$menu = new TwoJGalleryMenu();

if(!function_exists('twoj_gallery_settings_submenu_page')){
	add_action( 'admin_init', 'twoj_gallery_settings_init' );
	function twoj_gallery_settings_init() {
		register_setting( 'twoj_gallery_options', TWOJ_GALLERY.'UI_Readonly' );
		register_setting( 'twoj_gallery_options', TWOJ_GALLERY.'ML_Options' );
	}
}
