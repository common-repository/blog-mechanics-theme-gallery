/*  
 * 2J Gallery			http://2joomla.net/wordpress-plugins/2j-gallery
 * Version:           	2.2.6 - 57233
 * Author:            	2J Team (c)
 * Author URI:        	http://2joomla.net
 * License:           	GPL-2.0+
 * License URI:       	http://www.gnu.org/licenses/gpl-2.0.txt
 * Date:              	Thu, 26 Oct 2017 17:09:25 GMT
 */


jQuery(function(){
	var twoJgalleryDialogOptions = jQuery("#twoj_gallery_dialog_options");
	var bodyClass = twoJgalleryDialogOptions.data("body");
	if(bodyClass) jQuery("body").addClass(bodyClass);
	twoJgalleryDialogOptions.dialog({
		'dialogClass' : 'wp-dialog',
		'title': twoJgalleryDialogOptions.data('title'),
		'modal' : true,
		'autoOpen' : twoJgalleryDialogOptions.data('open'),
		'width': '450', 
	    'maxWidth': 450,
	    'height': 'auto',
	    'fluid': true, 
	    'resizable': false,
		'responsive': true,
		'draggable': false,
		'closeOnEscape' : true,
		'buttons' : [{
				'text'  : 	twoJgalleryDialogOptions.data('close'),
				'class' : 	'button button-link',
				'click' : 	function() { jQuery(this).dialog('close'); }
		},
		{
				'text' : 	twoJgalleryDialogOptions.data('info'),
				'class' : 	'button-primary',
				'click' : 	function(){
					window.open(twoj_gallery_js_text.urlOpen+"goPremium",'_blank');
					jQuery(this).dialog('close');
				}
		}
		],
		open: function( event, ui ) {}
	});
	window['twoJgalleryDialogOptions'] = twoJgalleryDialogOptions;
	jQuery(".ui-dialog-titlebar-close").addClass("ui-button ui-widget ui-state-default ui-corner-all ui-button-icon-only ui-dialog-titlebar-close");
	
	jQuery('.twoj-gallery-option-premium').click( function(event ){
		event.preventDefault();
		twoJgalleryDialogOptions.dialog("open");
	});
});