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

class twoJGalleryOptions{

	protected $post;

	protected $styles = array();

	public function __construct(WP_Post $post){
		$this->post = $post;
		$this->styleInit();
	}

	public function styleInit(){
		$this->styles['main'] 		= $this->getMainStyle();
		$this->styles['navigation'] = $this->getNavStyle();
		$this->styles['breadcrumbs'] = $this->getBreadcrumbsStyle();
		$this->styles['pagination'] = $this->getPaginationStyle();
	}

	public function getMeta($name, $return_array = true){
		return get_post_meta( $this->post->ID, 'twoj_gallery_'.$name, true );
	}

	public function getSize( $name, $type = 'px'){
		$value =  $this->getMeta($name);
		if($value){
			switch ($type) {
				case 'px':
						$value = (int)$value.'px';;			
					break;
				
				default:
					
					break;
			}

			
		}
		return $value;
	}

	public function getClass( $className = '', $addToClass='' ){
		if( !$styleName || !isset($this->class[$styleName]) ) return '';
		$style = $addToClass.' '.$this->class[$styleName];
		return $style ;
	}

	public function getClassTag( $className = '', $addToClass='' ){
		$style = $this->getClass($className, $addToClass );
		if($style) $style = ' class="'.$style.'" ';
		return $style ;
	}

	public function getStyle( $styleName = '', $addToStyle='' ){
		if( !$styleName || !isset($this->styles[$styleName]) ) return '';
		$style = $addToStyle.$this->styles[$styleName];
		if($style) $style = ' style="'.$style.'" ';
		return $style ;
	}

	public function getMainStyle(){

		$style = '';
		
		$maxWidth = $this->getMeta('maxWidth');
		if($maxWidth && count($maxWidth) && (int) $maxWidth['value']){
			$stringValue =  (int) $maxWidth['value'];
			$stringValue .= $maxWidth['type'] ? 'px' : '%' ;
			$style .= 'max-width: '.$stringValue.';';
		}

		$align =   $this->getMeta('align');
		if($align){
			switch ($align) {
				case 'left':
					$style .= 'margin-right: auto;';
					break;
				case 'right':
					$style .= 'margin-left: auto;';
					break;
				case 'center':
					$style .= 'margin: 0 auto;';
					break;
				default:
					break;
			}
		}

		return $style;
	}

	public function getNavStyle(){
		$style = '';
		if( $spaceAfterMenu =  $this->getSize('spaceAfterMenu') ){
			$style .= 'margin-bottom: '.$spaceAfterMenu.';';
		}
		return $style;
	}

	public function getBreadcrumbsStyle(){
		$style = '';
		if( $spaceBeforeBreadcrumbs =  $this->getSize('spaceBeforeBreadcrumbs') ){
			$style .= 'margin-top: '.$spaceBeforeBreadcrumbs.';';
		}
		return $style;
	}

	public function getPaginationStyle(){
		$style = '';
		if( $spaceBeforePagination =  $this->getSize('spaceBeforePagination') ){
			$style .= 'margin-top: '.$spaceBeforePagination.';';
		}
		return $style;
	}
	

}
