<?php if (!empty($blockContent)) : ?>
	<div id="twoJGallery-<?php echo $gallery['post']['ID']; ?>" class="twoJGallery" 
		<?php echo $options->getStyle('main'); ?>
	    data-twoJG-root-id="<?php echo $gallery['post']['ID']; ?>"
	    data-twoJG-id="<?php echo $gallery['post']['ID']; ?>"
	    data-twoJG-cofig="jsonConfigId<?php echo $gallery['post']['ID']; ?>">

		<?php if ($blockNavigation) : ?>
			<div class="block twoJG_navigation row" 
				<?php echo $options->getStyle('navigation'); ?>
				data-view="<?php echo $blockNavigationView; ?>" >
				<?php echo $blockNavigation; ?>
			</div>
		<?php endif; ?>

		<?php if ($blockContent) : ?>
			<div class="block twoJG_content row" data-view="<?php echo $blockContentView; ?>" >
				<?php echo $blockContent; ?>
			</div>
		<?php endif; ?>

		<?php if ($blockBreadcrumbs) : ?>
			<div class="block twoJG_breadcrumbs row" 
				<?php echo $options->getStyle('breadcrumbs'); ?> 
				data-view="" >
				<?php echo $blockBreadcrumbs; ?>
			</div>
		<?php endif; ?>

		<?php if ( $blockPaginationView != twoJGalleryNavigation::VIEW_HIDDEN ) : // $blockPagination || ?> 
			<div class="block twoJG_paging row" 
				<?php echo $options->getStyle('pagination'); ?> 
				data-view="<?php echo $blockPaginationView; ?>" >
				<?php echo $blockPagination; ?>
			</div>
		<?php  endif; ?>
		<!-- Config Block -->
		<script type="text/javascript">
			var json2JGalleryConfig<?php echo $gallery['post']['ID']; ?> = <?php echo json_encode($config); ?>;
		</script>
	</div>
<?php endif; ?>
