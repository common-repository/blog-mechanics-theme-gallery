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

 if ($navigationData) : ?>
	<div class="twoJGalleryCSSwrap">
		<?php foreach ($navigationData as $gallery) : ?>
			<a href="#" class="btn-2j <?php

				/* class button */
				if($galleryId == $gallery['post']['ID']) echo ' '.$itemClassActive;
				if($buttonColor) echo ' btn-2j-'.$buttonColor;
				if($buttonSize) echo ' btn-2j-'.$buttonSize;

			?>  "  data-twoJG-id="<?php echo $gallery['post']['ID']; ?>"  role="button">
					<?php echo apply_filters('the_title', $gallery['post']['post_title']); ?>
			</a>
		<?php endforeach; ?>
	</div>
<?php endif; ?>
