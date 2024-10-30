<?php

$isHasChildren = !empty($branch['children']);
$liClass = '';
/*$liClass = $isHasChildren
	? (isset($parentBranch) ? 'dropdown-submenu' : 'dropdown')
	: '';*/
if ($galleryId == $branch['gallery']['post']['ID']) $liClass .= ' '.$itemClassActive;
?>
<li class="<?php echo $liClass; ?>" >
	<a href="#" data-twoJG-id="<?php echo $branch['gallery']['post']['ID']; ?>" class="">
	   <?php echo apply_filters('the_title', $branch['gallery']['post']['post_title']); ?>
	</a>
</li>
	<?php if($isHasChildren) : ?>
			<?php $parentBranch = $branch; ?>
			<?php foreach ($parentBranch['children'] as $branch) : ?>
				<?php include __FILE__; ?>
				<?php unset($parentBranch); ?>
			<?php endforeach; ?>
	<?php endif; ?>

