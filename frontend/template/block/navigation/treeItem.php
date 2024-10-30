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

$children = count( $treeBranch['children'] ) ;
if($homeButton) $children = false;

$linkClass = 'btn-2j ';
if( $children ) $linkClass .= ' dropdown-2j-toggle';
if( $galleryId == $treeBranch['gallery']['post']['ID'] ) $linkClass .= ' '.$itemClassActive;

if($buttonColor) 	$linkClass .= ' btn-2j-'.$buttonColor;
if($buttonSize) 	$linkClass .= ' btn-2j-'.$buttonSize;

$dataAttr = ' data-twoJG-id="'.$treeBranch['gallery']['post']['ID'].'"';
if( $children ) $dataAttr .= ' data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-hover="dropdown" ';
$dataAttr .= ' class="'.$linkClass.'"';

if($children){ ?>
	<div class="btn-2j-group">
<?php } ?>
		<a href="#" <?php echo $dataAttr; ?>>
			<?php echo apply_filters('the_title', $treeBranch['gallery']['post']['post_title']); ?> 
			<?php if($children){ ?>
				 <span class="caret-2j"></span>
			<?php } ?>
		</a>
		<?php if($children){ ?>
			<ul class="dropdown-2j-menu">
				<?php foreach( $treeBranch['children'] as $branch ){
					include TWOJ_GALLERY_TEMPLATE_DIR . 'block/navigation/tree/branch.php';
				} ?>
			</ul>
		<?php } ?>
<?php if($children){ ?>
	</div>
<?php } ?>
