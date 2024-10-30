/*  
 * 2J Gallery			http://2joomla.net/wordpress-plugins/2j-gallery
 * Version:           	2.2.6 - 57233
 * Author:            	2J Team (c)
 * Author URI:        	http://2joomla.net
 * License:           	GPL-2.0+
 * License URI:       	http://www.gnu.org/licenses/gpl-2.0.txt
 * Date:              	Thu, 26 Oct 2017 17:09:25 GMT
 */

(function ($) {
    $(document).ready(function () {
        $(document).foundation();

        $(".text-color .field input[data-colorpicker]").each(function () {
            var $this = $(this),
                options = JSON.parse($this.attr('data-options'));
            $this.ColorPickerSliders(options);
        });

        $('[data-dependents]').on('change', function () {
            var $this = $(this),
                attrDependents = $this.attr('data-dependents'),
                dependents = attrDependents ? JSON.parse(attrDependents) : {},
                tag = this.nodeName.toLowerCase(),
                type = 'input' === tag ? $this.attr('type') : tag,
                value = undefined;

            switch (type) {
                case 'checkbox':
                    value = $this.prop('checked') ? 1 : 0;
                    break;
                case 'radio':
                case 'select':
                    value = $this.val();
                    break;
            }

            if (dependents[value]) {
                $.each(dependents[value], function (action, selectors) {
                    $.each(selectors, function (i, selector) {
                        if ($.isFunction($(selector)[action])) {
                            $(selector)[action]();
                        }
                    })
                });
            }
        });
        
        $('.twoj-gallery-option-new').click( function(evn){
        	evn.preventDefault();
        });

        $('input[type="checkbox"][data-dependents]:checked').trigger('change');
        $('input[type="radio"][data-dependents]:checked').trigger('change');
        $('select[data-dependents]').trigger('change');

    });
})(jQuery);
