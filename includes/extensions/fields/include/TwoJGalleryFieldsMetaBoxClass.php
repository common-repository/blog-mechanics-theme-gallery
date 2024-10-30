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

class TwoJGalleryFieldsMetaBoxClass{

	const STATE_OPEN = 'open';

	const STATE_CLOSE = 'close';

	protected $settings;

	public function __construct(array $settings) {
		$this->settings = array_merge(
			array(
				'active' => true,
				'order' => 0,
				'settings' => array(),
				'view' => 'default',
				'fields' => array(),
				
				'content' => '',
				'contentBefore' => '',
				'contentAfter' => '',
			),
			$settings
		);
		// array_merge doesn't merge recursively, that's why merge settings separately
		$this->settings['settings'] = array_merge(
			array(
				'id' => '',
				'title' => '',
				'screen' => array(),
				'context' => 'advanced',
				'priority' => 'default',
				'callback_args' => null
			),
			$this->settings['settings']
		);

		if (!is_array($this->settings['settings']['screen'])) {
			$this->settings['settings']['screen'] = array($this->settings['settings']['screen']);
		}

		if ($this->settings['active']) {
			add_action('add_meta_boxes', array($this, 'registration'), absint($this->settings['order']));
			add_action('user_register', array($this, 'setDefaultState'));
			add_action('save_post', array($this, 'save'));
		}
	}

	public function registration(){
		add_meta_box(
			$this->settings['settings']['id'],
			$this->settings['settings']['title'],
			array($this, 'render'),
			$this->settings['settings']['screen'],
			$this->settings['settings']['context'],
			$this->settings['settings']['priority'],
			$this->settings['settings']['callback_args']
		);
	}

	public function setDefaultState($userId){

		foreach ($this->settings['settings']['screen'] as $screen) {
			$optionName = "closedpostboxes_{$screen}";
			$closedMetaBox = get_user_meta($userId, $optionName, true);
			$closedMetaBox = $closedMetaBox ? $closedMetaBox : array();

			if (self::STATE_OPEN === $this->settings['state']) {
				$keyMetaBox = array_search($this->settings['settings']['id'], $closedMetaBox);
				if (false !== $keyMetaBox) {
					unset($closedMetaBox[$keyMetaBox]);
				}
			} elseif (self::STATE_CLOSE == $this->settings['state']) {
				$closedMetaBox[] = $this->settings['settings']['id'];
				$closedMetaBox = array_unique($closedMetaBox);
			}

			update_user_meta($userId, $optionName, $closedMetaBox);
		}
	}

	public function render(WP_Post $post){

		$view = new TwoJGalleryFieldsView();
		$postMeta = get_post_meta($post->ID);
		$settings = $this->settings;
		$templatingFields = array('contentBefore', 'content', 'contentAfter');
		$nonce = '';

		foreach ($templatingFields as $templatingField) {
			if (!empty($settings[$templatingField])) {
				if (0 === strpos($settings[$templatingField], 'template::')) {
					$template = str_replace('template::', '', $settings[$templatingField]);
					$settings[$templatingField] = $view->content($template);
				}
			}
		}

		if (is_array($settings['fields'])) {
			foreach ($settings['fields'] as $key => $fieldSettings) {
				$field = TwoJGalleryFieldsFieldFactory::createField($post->ID, $fieldSettings);
				$fieldName = $field->get('prefix') && $field->get('name')
					? $field->get('prefix') . $field->get('name')
					: $field->get('name');
				$fieldValue = isset($postMeta[$fieldName])
					? reset($postMeta[$fieldName]) // get single meta
					: $field->get('default');
				$fieldValue = is_serialized($fieldValue) ? unserialize($fieldValue) : $fieldValue;

				$settings['fields'][$key] = $this->getFieldData($field, $fieldValue);
				$nonce .= $field->get('name');
			}
		}

		$nonceField = $this->createNonceField();
		$settings['fields'][] = $this->getFieldData($nonceField, wp_create_nonce($nonce));

		$view->render("metabox/{$this->settings['view']}", $settings);
	}

	protected function getFieldData(TwoJGalleryFieldsField $field, $value){
		return array(
			'type' => $field->get('type'),
			'view' => $field->get('view'),
			'is_lock' => $field->get('isLock'),
			'is_new' => $field->get('isNew'),
			'id' => $field->get('id'),
			'contentBefore' => $field->get('contentBefore'),
			'content' => $field->get('content'),
			'contentAfter' => $field->get('contentAfter'),
			'field' => $field->content($value),
		);
	}

	protected function createNonceField(){
		return TwoJGalleryFieldsFieldFactory::createField(
			0,
			array(
				'type' => 'hidden',
				'view' => 'default',
				'prefix' => "{$this->settings['settings']['id']}_",
				'name' => 'nonce',
			)
		);
	}

	public function save($postId) {
		if (defined( 'DOING_AUTOSAVE') && DOING_AUTOSAVE ) {
			return;
		}

		if ('post' !== strtolower($_SERVER['REQUEST_METHOD'])) {
			return;
		}

		$postType = $_POST['post_type'];
		if (!in_array($postType, $this->settings['settings']['screen'])) {
			return;
		}

		if (!current_user_can('edit_post', $postId)) {
			header('HTTP/1.0 403 Forbidden');
			die("Access denied");
		}

		$nonceField = $this->createNonceField();
		$nonceName = $nonceField->get('prefix') . $nonceField->get('name');
		$nonceValue = isset($_POST[$nonceName]) ? $_POST[$nonceName] : null;
		$nonce = '';
		foreach ($this->settings['fields'] as $fieldConfig) {
			$nonce .= $fieldConfig['name'];
		}
		if(!wp_verify_nonce($nonceValue, $nonce)) {
			wp_nonce_ays(null);
		}

		foreach ($this->settings['fields'] as $fieldConfig) {
			$field = TwoJGalleryFieldsFieldFactory::createField($postId, $fieldConfig);
			$fieldName = $field->get('prefix') . $field->get('name');
			$fieldValue = isset($_POST[$fieldName]) ? $_POST[$fieldName] : null;

			$field->save($fieldValue);
		}
	}
}
