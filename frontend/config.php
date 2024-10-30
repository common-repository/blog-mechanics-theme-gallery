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

class twoJGalleryConfig{

	const FILE_PATTERN = '/^[a-z0-9-]+\.json$/';
	const FILE_EXTENSION = '.json';

	protected static $defaultConfig;

	protected $config;


	public function __construct(WP_Post $post){
		if (!self::$defaultConfig) {
			self::readDefaultConfig();
		}

		$this->config = $this->merge(self::$defaultConfig, $this->readConfig($post));
		//print_r($this->config);
	}


	protected function readConfig(WP_Post $post){
		if (class_exists('twoJGalleryPostConfig') && method_exists('twoJGalleryPostConfig', 'getConfig')) {
			$postConfig = new twoJGalleryPostConfig();

			return $postConfig->getConfig($post);
			//return twoJGalleryPostConfig::prepare($post);
		}
		return array();
	}


	protected static function readDefaultConfig(){
		foreach (self::getDefaultConfigFiles() as $configName => $filePath) {
			// clear comment
			$content = explode("\n", file_get_contents($filePath));
			foreach ($content as $i => $line) {
				if (0 === strpos(trim($line), '//')) {
					// remove commented line
					$content[$i] = '';
				}
			}
			$content = implode("\n", $content);


			$configData = json_decode($content, true);
			if (!is_array($configData)) {
				throw new \Exception(sprintf('Wrong configuration %s', $filePath));
			}
			self::$defaultConfig[$configName] = $configData;
		}

		if (empty(self::$defaultConfig)) {
			throw new \Exception('Empty configuration');
		}
	}


	public static function getDefaultConfigFiles(){
		$dir = TWOJ_GALLERY_DIR . 'config/';
		$files = array();

		foreach (scandir($dir) as $file) {
			if (preg_match(self::FILE_PATTERN, $file)) {
				$configName = str_replace(self::FILE_EXTENSION, '', $file);
				$files[$configName] = $dir . $file;
			}
		}

		return $files;
	}


	protected function merge(array $array1, array $array2){
		$merged = $array1;

		foreach ($array2 as $key => & $value) {
			if (is_array($value) && isset($merged[$key]) && is_array($merged[$key])) {
				$merged[$key] = $this->merge($merged[$key], $value);
			} else {
				if (is_numeric($key)) {
					if (!in_array($value, $merged)) {
						$merged[] = $value;
					}
				} else {
					$merged[$key] = $value;
				}
			}
		}

		return $merged;
	}

	/**
	 * Set configuration value by path
	 */
	public function set($path, $value){
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

	/**
	 * Get configuration value by path
	 */
	public function get($path = null){
		$pieces = $path ? explode('/', $path) : array();
		$config = &$this->config;

		foreach ($pieces as $piece) {
			if (!isset($config[$piece])) {
				return null;
			}
			$config = &$config[$piece];
		}

		return $config;
	}
}
