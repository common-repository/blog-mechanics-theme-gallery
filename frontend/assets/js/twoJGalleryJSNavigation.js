/*  
 * 2J Gallery			http://2joomla.net/wordpress-plugins/2j-gallery
 * Version:           	2.2.6 - 57233
 * Author:            	2J Team (c)
 * Author URI:        	http://2joomla.net
 * License:           	GPL-2.0+
 * License URI:       	http://www.gnu.org/licenses/gpl-2.0.txt
 * Date:              	Thu, 26 Oct 2017 17:09:25 GMT
 */
 /* twoJGalleryJSNavigation */
;(function (window, $) {
     window.twoJGalleryJSNavigation = function (gallery) {
         twoJGalleryJSBlock.apply(this, arguments);

         var self = this;

         self.gallery.$element.on('onChangeGallery', function (event, args) {
             self.onChangeGallery(event, args)
         })
    };

    twoJGalleryJSNavigation.prototype = Object.create(twoJGalleryJSBlock.prototype);
    twoJGalleryJSNavigation.prototype.constructor = twoJGalleryJSNavigation;

    twoJGalleryJSNavigation.prototype.name = 'navigation';

    twoJGalleryJSNavigation.prototype.init = function () {
        var self = this;

        switch (self.view) {
            case 'list':
                break;
            case 'tree':
                this.initViewTree();
                break;
            case 'hidden':
                break;
        }

        $('a', self.$element).click(function () {
            var galleryId = $(this).attr('data-twoJG-id'); //.parent()

            self.gallery.$element.trigger('onChangeGallery', {gallery_id: galleryId});
            return false;
        });
    };
    twoJGalleryJSNavigation.prototype.initViewTree = function () {
    };
    twoJGalleryJSNavigation.prototype.onChangeGallery = function (event, args) {
        var self = this,
            itemClassActive = self.gallery.config.get('gallery/block/navigation/item/class-active');

        $('a.' + itemClassActive, self.$element).removeClass(itemClassActive);
        $('a[data-twoJG-id="' + args.gallery_id + '"]', self.$element).addClass(itemClassActive);
    }
})(window, jQuery);