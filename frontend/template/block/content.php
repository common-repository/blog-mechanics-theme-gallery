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

 if (!empty($items)) : 

	$gridId = time() . '-' . mt_rand();
?>
	<div id="view-<?php echo $gridId; ?>" class="js-grid-view"></div>
	<div id="playlist-<?php echo $gridId; ?>" class="js-playlist" style="display: none">
		<ul data-category-name="gallery-0-<?php echo $gridId; ?>">
			<?php foreach($items as $item) : ?>
				<li
					class="js-twoJG-grid-item"
					data-twoJG-id="<?php echo $item['ID']; ?>"
					data-twoJG-type="<?php echo $item['type']; ?>"
					data-twoJG-link-type="<?php echo $item['link']['type']; ?>"
					data-url="<?php echo $item['link']['value']; ?>"
					data-thumbnail="<?php echo $item['image']['preview']['url']; ?>"
				<?php if ($item['title']) : ?>
					data-caption="<?php echo esc_js( apply_filters('the_title', $item['title']) ); ?>"
				<?php endif; ?>
				<?php if( 2==3 && $item['type'] == 'image' ){ ?>
					data-width="<?php echo $item['image']['size']['width']; ?>"
					data-height="<?php echo $item['image']['size']['height']; ?>"
				<?php } ?>
				>	
					<img src="<?php echo $item['image']['preview']['url']; ?>" alt="">

					<?php if ( !$hoverTextHide && ( $item['title'] || $item['description'] ))  : ?>
						<div data-thumbnail-content1="">
							
							<?php if ($item['title']) : ?>
								<div class="center<?php echo $textColor=='light' ? 'White' : 'Dark' ; ?>">
									<?php echo apply_filters('the_title', $item['title']); ?>
								</div>
							<?php endif; ?>

							<?php if ($item['description']) : ?>
								<div class="centerNormal<?php echo $textColor=='light' ? 'White' : 'Dark' ; ?>"> 
									<?php echo $item['description']; ?>
								</div>
							<?php endif; ?>
						
						</div>
					<?php endif; ?>

					<?php if ($item['description']) : ?>
						<div class="description" data-lightbox-desc="">
							<p class="gallery1DecHeader"></p>
							<p class="gallery1DescP">
								<?php echo  $item['description'];//apply_filters('the_content',); ?>
							</p>
						</div>
					<?php endif; ?>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
<?php endif; ?>
