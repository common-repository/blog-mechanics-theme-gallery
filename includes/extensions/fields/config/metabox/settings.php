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

return array(
	'active' => true,
	'order' => 10,
	'settings' => array(
		'id' => 'twoj_gallery',
		'title' => '2J Gallery Settings',
		'screen' => array(TWOJ_GALLERY_TYPE_POST),
		'context' => 'advanced',
		'priority' => 'default',
		'callback_args' => null,
	),
	'view' => 'default',
	'state' => 'open',
	'style' => null,
	'fields' => array(
		array(
			'type' => 'composite',
			'view' => 'default',
			'name' => 'maxWidth',
			'default' => null,  //'.__('', 'blog-mechanics-theme-gallery').'
			'label' => '<h5>'.__('Max Width Gallery', 'blog-mechanics-theme-gallery').'</h5>',
			'description' => 'in our gallery we use smart algorithm for the size calculation. In Max Width option you define maximum allowed size of the gallery box',

			'fields' => array(
				array(
					'type' => 'text',
					'view' => 'default/llc4',
					'name' => 'value',
					'default' => 100,
					'attributes' => array(),
					'label' => null,
				),
				array(
					'type' => 'checkbox',
					'view' => 'switch/c2',
					'name' => 'type',
					'default' => null,
					'attributes' => array(),
					'options' => array(
						'size' => 'large',
						'onLabel' => 'px',
						'offLabel' => '%',
					),
				),
			)
		),

		array(
			'type' => 'radio',
			'view' => 'buttons-group',
			'is_new' => '1',	
			'name' => 'previewSize',
			'default' => 'medium',
			'label' => '<h5>'.__('Gallery Thumbnails Quality', 'blog-mechanics-theme-gallery').'</h5>',
			'description' => ' here you can customize thumbnails quality, depend of this value you will have different thumbnails resolution. Please check values for the thumbnails resolutions <a href="'. admin_url( 'options-media.php' ).'" target="_blank">here</a>',

			'options' => array(
				'values' => array(
					array(
						'value' => 'thumbnail',
						'label' => 'Small',
					),
					array(
						'value' => 'medium',
						'label' => 'Medium',
					),
					array(
						'value' => 'medium_large',
						'label' => 'Large',
					),
					array(
						'value' => 'original',
						'label' => 'Full',
					)
				),
			),
		),


		array(
			'type' => 'radio',
			'view' => 'buttons-group',		
			'name' => 'gridType',
			'default' => 'classicVertical',
			'label' => '<h5>'.__('Gallery Layout Mode', 'blog-mechanics-theme-gallery').'</h5>',
			'description' => 'here you can define gallery thumbnails layout modes for classical with square shape thumbnails or custom grid layout',

			'options' => array(
				'values' => array(
					array(
						'value' => 'classicVertical',
						'label' => 'Classic',
					),
					
					array(
						'value' => 'dynamicVertical',
						'label' => 'Dynamic',
					),
				),
			),
		),


		array(
			'type' => 'radio',
			'view' => 'buttons-group',
			'name' => 'galleryThumbnail',
			'default' => 'first',
			'label' => '<h5>'.__('General Gallery Thumbnail', 'blog-mechanics-theme-gallery').'</h5>',
			'description' => 'here you can select which gallery image will be used as general thumbnail in gallery listings',
			'options' => array(
				'values' => array(
					array(
						'value' => 'first',
						'label' => 'first',
					),
					array(
						'value' => 'random',
						'label' => 'random',
					)
				),
			),
		),


		array(
			'type' => 'radio',
			'view' => 'buttons-group',		
			'name' => 'align',
			'default' => 'disable',
			'label' => '<h5>'.__('Gallery Align', 'blog-mechanics-theme-gallery').'</h5>',
			'description' => 'here you can align whole gallery block depend of your need or disable alignment option if you do not need it',
			'options' => array(
				'values' => array(
					array(
						'value' => 'left',
						'label' => 'Left',
					),
					array(
						'value' => 'center',
						'label' => 'Center',
					),
					array(
						'value' => 'right',
						'label' => 'Right',
					),
					array(
						'value' => 'disable',
						'label' => 'Disabled',
					)
				),
			),
		),


		array(
			'type' => 'text',
			'view' => 'color',
			'name' => 'backgroundColor',
			'default' => '#ffffff',
			'attributes' => array(
				'placeholder' => '#ffffff',
				'readonly' => 'readonly',
			),
			'label' => '<h5>'.__('Gallery Background Color', 'blog-mechanics-theme-gallery').'</h5>',
			'description' => 'here you can define background color for whole gallery block',
			'contentAfter' => '<hr/>',
			'options' => array(
				'color-format' => 'hex',
				'colorpicker' => array(
					'previewontriggerelement' => true,
					'flat' => false,
					'order' => array(
						'rgb' => 1,
					),
					
				),
			),
		),


		array(
			'type' => 'text',
			'view' => 'group',
			'name' => 'thumbnailMaxWidth',
			'default' => 278,
			'label' => '<h5>Thumbnail Max Width</h5>',
			'description' => 'here you can define maximum width value for the gallery thumbnails in pixels',
			'cb_sanitize' => 'strip_tags',
			'options' => array(
				'rightLabel' 	=> 'px',
				'column'		=> '4',
			),
		),


		array(
			'type' => 'text',
			'view' => 'group',
			'name' => 'thumbnailMaxHeight',
			'default' => 188,
			'label' => '<h5>Thumbnail Max Height</h5>',
			'description' => 'here you can define maximum height value for the gallery thumbnails in pixels',
			'cb_sanitize' => 'strip_tags',
			'options' => array(
				'rightLabel' 	=> 'px',
				'column'		=> '4',
			),
		),


		array(
			'type' => 'text',
			'view' => 'slider',
			'name' => 'thumbnailsHorizontalOffset',
			'default' => 0,
			'attributes' => array(
				'placeholder' => '0',
				'readonly' => get_option( TWOJ_GALLERY.'UI_Readonly' )==1 ?  null : 'readonly',
			),
			'label' => '<h5>Horizontal Offset</h5>',
			'description' => 'thumbnails horizontal offset for the general offset for outsize border of the general thumbnails block',
			'options' => array(
				'textAfter' => 'px',
				'data-start' => 0,
				'data-end' => 100,
				'step' => 1,
			),
		),


		array(
			'type' => 'text',
			'view' => 'slider',
			'name' => 'thumbnailsVerticalOffset',
			'default' => 0,
			'attributes' => array(
				'placeholder' => '0',
				'readonly' => get_option( TWOJ_GALLERY.'UI_Readonly' )==1 ?  null : 'readonly',
			),
			'label' => '<h5>Vertical Offset</h5>',
			'description' => 'thumbnails vertical offset for the general offset for outsize border of the general thumbnails block',
			'options' => array(
				'textAfter' => 'px',
				'data-start' => 0,
				'data-end' => 100,
				'step' => 1,
			),
		),


		array(
			'type' => 'text',
			'view' => 'slider',
			'name' => 'horizontalSpaceBetweenThumbnails',
			'default' => 0,
			'attributes' => array(
				'placeholder' => '0',
				'readonly' => get_option( TWOJ_GALLERY.'UI_Readonly' )==1 ?  null : 'readonly',
			),
			'label' => '<h5>Horizontal Thumbnails Spacing</h5>',
			'description' => 'horizontal spacing between gallery thumbnails in grid',
			'options' => array(
				'textBefore' => '',
				'textAfter' => 'px',
				'data-start' => 0,
				'data-end' => 100,
				'step' => 1,
			),
		),


		array(
			'type' => 'text',
			'view' => 'slider',
			'name' => 'verticalSpaceBetweenThumbnails',
			'default' => 0,
			'attributes' => array(
				'placeholder' => '0',
				'readonly' => get_option( TWOJ_GALLERY.'UI_Readonly' )==1 ?  null : 'readonly',
			),
			'label' => '<h5>Vertical Thumbnails Spacing</h5>',
			'description' => 'vertical spacing between gallery thumbnails in grid',
			'options' => array(
				'textAfter' => 'px',
				'data-start' => 0,
				'data-end' => 100,
				'step' => 1,
			),
		),
		

		array(
			'type' => 'text',
			'view' => 'color',
			'name' => 'thumbnailBackgroundColor',
			'default' => '#ffffff',
			'attributes' => array(
				'placeholder' => '#ffffff',
				'readonly' => 'readonly',
			),
			'label' => '<h5>Thumbnail Background Color</h5>',
			'description' => 'here you can select background color for gallery thumbnails',
			'contentAfter' => '<hr/>',
			'options' => array(
				'color-format' => 'hex',
				'colorpicker' => array(
					'previewontriggerelement' => true,
					'flat' => false,
					'order' => array(
						'rgb' => 1,
					)
				),
			),
		),


		array(
			'type' => 'radio',
			'view' => 'buttons-group',	
			'name' => 'gridPreset',
			'default' => 'animtext',
			'label' => '<h5>Hover Effect</h5>',
			'description' => 'here you can select hover animation effect for gallery thumbnails',			
			'options' => array(
				'values' => array(
					array(
						'value' => 'animtext',
						'label' => 'Animated',
					),
					array(
						'value' => 'movetext',
						'label' => 'Move',
					),
					array(
						'value' => 'scaletext',
						'label' => 'Scale',
					),
					array(
						'value' => 'scaleTextInverse',
						'label' => 'Inverse Scale',
					),
				),
			),
		),

		array(
			'type' => 'text',
			'view' => 'color',
			'name' => 'thumbnailOverlayColor',
			'default' => '#FFFFFF',
			'attributes' => array(
				'placeholder' => '#333333',
				'readonly' => 'readonly',
			),
			'label' => '<h5>Hover Overlay Color</h5>',
			'description' => 'here you can select background color for gallery thumbnails hover overlay',
			'options' => array(
				'color-format' => 'hex',
				'colorpicker' => array(
					'previewontriggerelement' => true,
					'flat' => false,
					'order' => array(
						'rgb' => 1,
					)
				),
			),
		),

		array(
			'type' => 'radio',
			'view' => 'buttons-group',
			'name' => 'hoverTextHide',
			'default' => '0',
			'is_new' => '1',
			'label' => '<h5>Hover Text</h5>',
			'description' => 'with this option you can show/hide text from the thumbnail in hover or static case , depend on type of selected hover effect',
			'options' => array(
				'values' => array(
					array(
						'value' => '0',
						'label' => 'Show',
					),
					array(
						'value' => '1',
						'label' => 'Hide',
					)
				),
			),
		),

		array(
			'type' => 'radio',
			'view' => 'buttons-group',
			'name' => 'textColor',
			'default' => 'dark',
			'label' => '<h5>Hover Text Color</h5>',
			'description' => 'here you can switch gallery thumbnail hover text style between pre-defined styles',
			'contentAfter' => '<hr/>',
			'options' => array(
				'values' => array(
					array(
						'value' => 'dark',
						'label' => 'Dark',
					),
					array(
						'value' => 'light',
						'label' => 'Light',
					)
				),
			),
		),


		array(
			'type' => 'radio',
			'view' => 'buttons-group',	
			'name' => 'navigationView',
			'default' => 'list',
			'label' => '<h5>Navigation View</h5>',
			'description' => 'please select style of the gallery navigation menu from few pre-configured gallery menu modes',
			'options' => array(
				'values' => array(
					array(
						'value' => 'list',
						'label' => 'List',
					),
					array(
						'value' => 'tree',
						'label' => 'Tree',
					),
					array(
						'value' => 'hidden',
						'label' => 'Hide',
					)
				),
			),
		),


		array(
			'type' => 'radio',
			'view' => 'buttons-group',
			'name' => 'buttonSize',
			'default' => '',
			'contentBefore' => "",
			'label' => '<h5>Navigation Size</h5>',
			'description' => 'here you can select size of the gallery navigation menu buttons',
			'options' => array(
				'values' => array(
					array(
						'value' => 'lg',
						'label' => 'Large',
					),
					array(
						'value' => '',
						'label' => 'Default',
					),
					array(
						'value' => 'sm',
						'label' => 'Small',
					),
					array(
						'value' => 'xs',
						'label' => 'Extra small',
					)
				),
			),
		),
		array(
			'type' => 'radio',
			'view' => 'buttons-group',	
			'name' => 'buttonColor',
			'default' => 'default',
			'label' => '<h5>Navigation Color</h5>',
			'description' => 'here you can select color for gallery thumbnails navigation menu buttons',
			'options' => array(
				'values' => array(
					array(
						'value' => 'default',
						'label' => 'Gray',
					),
					array(
						'value' => 'info',
						'label' => 'Blue',
					),
					array(
						'value' => 'primary',
						'label' => 'Dark Blue',
					),
					array(
						'value' => 'success',
						'label' => 'Green',
					),
					array(
						'value' => 'warning',
						'label' => 'Orange',
					),
					array(
						'value' => 'danger',
						'label' => 'Red',
					),
					array(
						'value' => 'link',
						'label' => 'Link',
					)
				),
			),
		),
		
		
		array(
			'type' => 'text',
			'view' => 'slider',
			'name' => 'spaceAfterMenu',
			'default' => 10,
			'attributes' => array(
				'placeholder' => '0',
				'readonly' => get_option( TWOJ_GALLERY.'UI_Readonly' )==1 ?  null : 'readonly',
			),
			'label' => '<h5>Navigation Offset</h5>',
		    'description' => 'here you can select spacing between gallery navigation menu and main gallery block with thumbnails',
			'contentAfter' => '<hr/>',
			'options' => array(
				'textAfter' => 'px',
				'data-start' => 0,
				'data-end' => 400,
				'step' => 1,
			),
		),
		
		
		array(
			'type' => 'radio',
			'view' => 'buttons-group',
			'prefix' => null,
			'name' => 'breadcrumbs',
			'default' => 'show',
			'attributes' => array(),
			'label' => '<h5>Breadcrumbs</h5>',
			'description' => 'here you can turn on/off breadcrumbs navigation element below gallery main thumbnails block',
			'options' => array(
				'values' => array(
					array(
						'value' => 'show',
						'label' => 'Show',
					),
					array(
						'value' => 'hidden',
						'label' => 'Hide',
					)
				),
			),
		),

		array(
			'type' => 'radio',
			'view' => 'buttons-group',
			'prefix' => null,
			'is_new' => '1',
			'name' => 'breadcrumbsUpButton',
			'default' => '0',
			'attributes' => array(),
			'label' => '<h5>Button "Up"</h5>',
			'description' => '',
			'options' => array(
				'values' => array(
					array(
						'value' => '1',
						'label' => 'Show',
					),
					array(
						'value' => '0',
						'label' => 'Hide',
					)
				),
			),
		),

		array(
			'type' => 'text',
			'view' => 'slider',
			'name' => 'spaceBeforeBreadcrumbs',
			'default' => 10,
			'attributes' => array(
				'placeholder' => '0',
				'readonly' => get_option( TWOJ_GALLERY.'UI_Readonly' )==1 ?  null : 'readonly',
			),
			'label' => '<h5>Breadcrumbs Offset</h5>',
			'description' => 'here you can select spacing between gallery breadcrumbs navigation element and main gallery block with thumbnails',
			'contentAfter' => '<hr/>',
			'options' => array(
				'textAfter' => 'px',
				'data-start' => 0,
				'data-end' => 400,
				'step' => 1,
			),
		),
		array(
			'type' => 'text',
			'view' => 'group',
			'name' => 'per_page',
			'default' => 11,
			'is_lock' => !TWOJ_GALLERY_FULL_VERSION,
			'label' => '<h5>Images Per Page</h5>',
			'description' => 'here you can select amount of galler images per page for pagination functionality',
			'cb_sanitize' => 'strip_tags',
			'options' => array(
				'column'		=> '3',
			),
		),


		array(
			'type' => 'text',
			'view' => 'slider',
			'name' => 'spaceBeforePagination',
			'default' => 10,
			'attributes' => array(
				'placeholder' => '0',
				'readonly' => get_option( TWOJ_GALLERY.'UI_Readonly' )==1 ?  null : 'readonly',
			),
			'label' => '<h5>Pagination Offset</h5>',
			'description' => 'here you can select spacing between gallery pagination navigation element and main gallery block with thumbnails',
			'options' => array(
				'textAfter' => 'px',
				'data-start' => 0,
				'data-end' => 400,
				'step' => 1,
			),
		),
		array(
			'type' => 'radio',
			'view' => 'buttons-group',
			'name' => 'paginationSize',
			'default' => '',
			'label' => '<h5>Pagination Size</h5>',
			'description' => 'here you can select size for the gallery pagination buttons, from pre-defined list',
			'contentAfter' => '<hr/>',
			'options' => array(
				'values' => array(
					array(
						'value' => 'lg',
						'label' => 'Large',
					),
					array(
						'value' => '',
						'label' => 'Default',
					),
					array(
						'value' => 'sm',
						'label' => 'Small',
					)
				),
			),
		),
		array(
			'type' => 'radio',
			'view' => 'buttons-group',
			'name' => 'lightboxSkin',
			'default' => 'dark',
			'label' => '<h5>Lightbox Skin</h5>',
			'description' => 'here you can select theme for the gallery lightbox from the list of the pre-defined styles',
			'options' => array(
				'values' => array(
					array(
						'value' => 'dark',
						'label' => 'Dark',
					),
					array(
						'value' => 'light',
						'label' => 'Light',
					)
				),
			),
		),
		
		
	),
);
