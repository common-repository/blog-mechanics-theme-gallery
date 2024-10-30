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

class TwoJGalleryUpdate {
	public $posts = array();

	public $needUpdate = 1;

	public $oldDBVer = false;

	public $dbVer = false;

	public $fieldArray = array(
			'1.0.0' => array( 'start' => 0, ),
		); 

	public $functionArray = array();

	public function __construct(){

		$this->oldDBVer = get_option( '2JGalleryDBVer' );

		if(!$this->oldDBVer) $this->oldDBVer = 0;

		$this->dbVer = TWOJ_GALLERY_VERSION;

		if( $this->oldDBVer && $this->oldDBVer == $this->dbVer )  $this->needUpdate = false;

		if( $this->needUpdate ){
			delete_option("2JGalleryCheckAfterInstall");
			add_option( '2JGalleryCheckAfterInstall', '1' );

			delete_option('2JGalleryInstallTime');
			add_option( '2JGalleryInstallTime', time() );

			delete_option("2JGalleryDBVer");
			add_option( "2JGalleryDBVer", $this->dbVer );
			
			$this->posts = $this->getGalleryPost();
			$this->update();
		}
	}


	public function getGalleryPost(){
		$my_wp_query = new WP_Query();
 		return $my_wp_query->query( 
				array( 
					'post_type' => TWOJ_GALLERY_TYPE_POST, 
					'posts_per_page' => 999, 
				)
			);
	}
	
	public function fieldInit( $fields ){
		for($i=0;$i<count($this->posts);$i++){
			$postId = $this->posts[$i]->ID;
			if( count($fields) ){
				foreach($fields as $key => $value){
					add_post_meta( $postId, TWOJ_GALLERY.$key, $value, true );
				}
			}
		}
	}



	public function update(){
		if( count($this->fieldArray) ){
			foreach($this->fieldArray as $version => $fields){
				if( 
					version_compare( $version, $this->oldDBVer, '>') || 
					version_compare( $version, $this->dbVer, '<=') 
				){
					if( isset($fields) ) $this->fieldInit( $fields );
				}
			}
		}
	}
}
