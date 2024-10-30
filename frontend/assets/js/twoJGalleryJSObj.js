/*  
 * 2J Gallery			http://2joomla.net/wordpress-plugins/2j-gallery
 * Version:           	2.2.6 - 57233
 * Author:            	2J Team (c)
 * Author URI:        	http://2joomla.net
 * License:           	GPL-2.0+
 * License URI:       	http://www.gnu.org/licenses/gpl-2.0.txt
 * Date:              	Thu, 26 Oct 2017 17:09:25 GMT
 */
/* core gallery */
;(function(window, $) {
    window.twoJGalleryJSObj = function($element) {
        this.$element = $element;
        this.config = new twoJGalleryJSConfig(this.getId());
        this.blocks = {};

        var self = this;
        var intervalId = setInterval(function () {
            if (self.config.isReady) {
                clearInterval(intervalId);

                if (!self.config.error) {
                    self.init();
                }
            }
        }, 100);
    };

    twoJGalleryJSObj.prototype.namespace = 'gallery';

    twoJGalleryJSObj.prototype.init = function () {
        var self = this,
            style = this.config.get('gallery/css');

        self.$element.on('onChangeGallery.' + self.namespace, function (event, args) {
            self.onChangeGallery(event, args)
        });

        var blockNavigation = new twoJGalleryJSNavigation(this);
        self.blocks[blockNavigation.name] = blockNavigation;

        var blockContent = new twoJGalleryJSContent(this);
        self.blocks[blockContent.name] = blockContent;

        var blockBreadcrumbs = new twoJGalleryJSBreadcrumbs(this);
        self.blocks[blockBreadcrumbs.name] = blockBreadcrumbs;

        var blockPagination = new twoJGalleryJSPagination(this);
        self.blocks[blockPagination.name] = blockPagination;

        $.each(self.blocks, function (name, block) {
            block.init();
        });

        if (style) {
            var css = style.replace(/GALLERYID/g, self.$element.attr('id')),
                head = document.getElementsByTagName('head')[0],
                tagStyle = document.createElement('style');

            tagStyle.setAttribute('type', 'text/css');
            if (tagStyle.styleSheet) {   // IE
                tagStyle.styleSheet.cssText = css;
            } else {                     // the world
                tagStyle.appendChild(document.createTextNode(css));
            }
            head.appendChild(tagStyle);
        }
    };

    twoJGalleryJSObj.prototype.getId = function() {
        return parseInt(this.$element.attr('data-twoJG-id'));
    };

    twoJGalleryJSObj.prototype.onChangeGallery = function (event, args) {
        var self = this;
        self.$element.attr('data-twoJG-id', args.gallery_id);
    }
})(window, jQuery);
