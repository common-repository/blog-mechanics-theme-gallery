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


class TwoJGalleryFieldsFieldCheckboxGroup extends TwoJGalleryFieldsField{

	protected function normalize($values){
		if (!is_array($values)) {
			$values = array();
		}
		
		foreach ($values as $name => $value) {
			$value = parent::normalize($value);
			$values[$name] = $value ? 1 : 0;
		}

		return $values;
	}
}
