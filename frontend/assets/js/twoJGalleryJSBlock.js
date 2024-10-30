/*  
 * 2J Gallery			http://2joomla.net/wordpress-plugins/2j-gallery
 * Version:           	2.2.6 - 57233
 * Author:            	2J Team (c)
 * Author URI:        	http://2joomla.net
 * License:           	GPL-2.0+
 * License URI:       	http://www.gnu.org/licenses/gpl-2.0.txt
 * Date:              	Thu, 26 Oct 2017 17:09:25 GMT
 */
 /* twoJGalleryJSBlock */
;(function (window, $) {
    window.twoJGalleryJSBlock = function (gallery) {
        this.gallery = gallery;
        this.$element = $('.block.twoJG_' + this.name, this.gallery.$element);
        this.view = this.$element.attr('data-view');
    };

    twoJGalleryJSBlock.prototype.name = undefined;
    twoJGalleryJSBlock.prototype.view = undefined;
    twoJGalleryJSBlock.prototype.init = function () {};

    twoJGalleryJSBlock.prototype.load = function (args) {
        var self = this,
            blockName = self.name;

        // merge block args
        args[blockName] = $.extend(
            {
                view: self.$element.attr('data-view')
            },
            args[blockName]
        );
        // merge common args
        args = $.extend(
            {
                action: twoJGalleryJSConst.typePost+'_block',
                gallery_id: self.gallery.$element.attr('data-twoJG-id'),
                root_gallery_id: self.gallery.$element.attr('data-twoJG-root-id'),
                block: self.name
            },
            args
        );

        var hash = twoJGalleryJSCache.makeHash(args, 'block');
        var response = twoJGalleryJSCache.get(hash);
        if (undefined !== response) {
            self.onLoad(args, response);
        } else {
            $.ajax({
                url: twoJGalleryJSConst.ajaxUrl,
                method: 'get',
                data: args,
                success: function (response) {
                    self.onLoad(args, response);
                    twoJGalleryJSCache.set(hash, response);
                },
                error: function (jqXHR) {
                    self.$element.html(jqXHR.responseText);
                }
            });
        }
    };

    twoJGalleryJSBlock.prototype.onLoad = function (args, response) {
        this.$element.html(response);
        this.init();
    };
})(window, jQuery);