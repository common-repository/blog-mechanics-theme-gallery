<div class="twoJGalleryCSSwrap">	
	<div class="row">
<?php

$count = count($breadcrumbs);
if($count){
?> 
	<ul class="breadcrumb-2j">
<?php
$i = 0;
?>
	<?php foreach ($breadcrumbs as $breadcrumb) : $i++ ?>
		<?php if (empty($breadcrumb['id'])) : ?>
			<li class="active">
		<?php else : ?>
			<li><a href="#"  data-twoJG-id="<?php echo $breadcrumb['id']; ?>" >
		<?php endif; /*class="twoJG_breadcrumb"*/ ?>

		<?php echo $breadcrumb['title']; ?>
		
		

		<?php if (empty($breadcrumb['id'])) : ?>
				<?php if($up){ ?>
					<a href="#" class="btn-2j btn-2j-default  up" data-twoJG-id="<?php echo $up['id']; ?>">
						<?php echo $up['title']; ?>
					</a>
				<?php } ?>
			</li>
		<?php else: ?>
			</a></li>
		<?php endif; ?>

		<?php if ($i < $count) { /*echo $separator;*/ } ?>
	<?php endforeach; ?>
	</ul>
<?php } ?>
	</div>
</div>
