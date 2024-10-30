/*  
 * 2J Gallery			http://2joomla.net/wordpress-plugins/2j-gallery
 * Version:           	2.2.6 - 57233
 * Author:            	2J Team (c)
 * Author URI:        	http://2joomla.net
 * License:           	GPL-2.0+
 * License URI:       	http://www.gnu.org/licenses/gpl-2.0.txt
 * Date:              	Thu, 26 Oct 2017 17:09:25 GMT
 */
/* twoJGalleryJSConfig */

;(function (window, $) {
     window.twoJGalleryJSConfig = function (galleryId) {
        this.config = undefined;
        this.error = undefined;
        this.isReady = false;

        this.read(galleryId);
    };

    twoJGalleryJSConfig.prototype.read = function (galleryId) {
        var self = this;

        if (!this.isReady) {
        	if( typeof( window['json2JGalleryConfig'+galleryId]) == 'object' ){
        		self.isReady = true;
	            self.config = window['json2JGalleryConfig'+galleryId];
        	} else {
	            $.ajax({
	                url: twoJGalleryJSConst.ajaxUrl,
	                method: 'get',
	                data: {
	                    action: twoJGalleryJSConst.typePost + '_config',
	                    gallery_id: galleryId
	                },
	                dataType: 'json',
	                success: function (response) {
	                    self.isReady = true;
	                    self.config = response;
	                },
	                error: function (jqXHR) {
	                    self.isReady = true;
	                    self.error = jqXHR.responseText;
	                    self.config = {};
	                }
	            });
	        }
        }
    };

    twoJGalleryJSConfig.prototype.get = function (name) {
        var sections = name.split('/'),
            config = this.config;

        $.each(sections, function (i, section) {
            config = config[section];
            if (undefined === config) {
                return false;
            }
        });

        return config;
    };
})(window, jQuery);
