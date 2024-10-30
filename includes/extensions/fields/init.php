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

define('TWOJ_GALLERY_FILEDS_PATH', 			dirname(__FILE__) . '/');
define('TWOJ_GALLERY_FILEDS_PATH_CONFIG', 	TWOJ_GALLERY_FILEDS_PATH . 'config/');
define('TWOJ_GALLERY_FILEDS_PATH_FIELD', 	TWOJ_GALLERY_FILEDS_PATH . 'include/TwoJGalleryFieldsField/');
define('TWOJ_GALLERY_FILEDS_TEMPLATE', 		TWOJ_GALLERY_FILEDS_PATH . 'template/');
define('TWOJ_GALLERY_FILEDS_URL', 			plugin_dir_url(__FILE__));
define('TWOJ_GALLERY_FILEDS_BODY_CLASS', 	'TwoJGalleryFields');

require_once TWOJ_GALLERY_FILEDS_PATH . 'include/TwoJGalleryFields.php';
require_once TWOJ_GALLERY_FILEDS_PATH . 'include/TwoJGalleryFieldsConfig.php';
require_once TWOJ_GALLERY_FILEDS_PATH . 'include/TwoJGalleryFieldsConfig/TwoJGalleryFieldsConfigReaderInterface.php';
require_once TWOJ_GALLERY_FILEDS_PATH . 'include/TwoJGalleryFieldsConfig/TwoJGalleryFieldsConfigReader.php';
require_once TWOJ_GALLERY_FILEDS_PATH . 'include/TwoJGalleryFieldsMetaBoxClass.php';
require_once TWOJ_GALLERY_FILEDS_PATH . 'include/TwoJGalleryFieldsFieldFactory.php';
require_once TWOJ_GALLERY_FILEDS_PATH . 'include/TwoJGalleryFieldsView.php';

TwoJGalleryFields::getInstance()->init();