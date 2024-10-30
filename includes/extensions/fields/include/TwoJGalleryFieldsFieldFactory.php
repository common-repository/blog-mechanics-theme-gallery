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

class TwoJGalleryFieldsFieldFactory{

	const DEFAULT_CLASS_FIELD = 'TwoJGalleryFieldsField';

	protected function __construct() {}
	protected function __clone() {}

	public static function createField($postId, array $settings){
		
		if (empty($settings['type'])) {
			throw new Exception('Empty field type');
		}
		if (empty($settings['view'])) {
			throw new Exception('Empty field view');
		}

		$type = ucfirst(preg_replace_callback(
			'/(?:-|_)([a-z0-9])/i',
			function($matches) {
				return strtoupper($matches[1]);
			},
			$settings['type']
		));
		$view = ucfirst(preg_replace_callback(
			'/(?:-|_|\/)([a-z0-9])/i',
			function($matches) {
				return strtoupper($matches[1]);
			},
			$settings['view']
		));
		$classesChain = array(
			self::DEFAULT_CLASS_FIELD . $type . $view,
			self::DEFAULT_CLASS_FIELD . $type,
			self::DEFAULT_CLASS_FIELD
		);

		require_once TWOJ_GALLERY_FILEDS_PATH_FIELD . 'TwoJGalleryFieldsField.php';
		require_once TWOJ_GALLERY_FILEDS_PATH_FIELD . 'TwoJGalleryFieldsFieldCheckboxGroup.php';
		foreach ($classesChain as $className) {
			if (file_exists(TWOJ_GALLERY_FILEDS_PATH_FIELD . "{$className}.php")) {
				require_once TWOJ_GALLERY_FILEDS_PATH_FIELD . "{$className}.php";
				return new $className($postId, $settings);
			}
		}
		throw new Exception("Can't find field class");
	}
}
