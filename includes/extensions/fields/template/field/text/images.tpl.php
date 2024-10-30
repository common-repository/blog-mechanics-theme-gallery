<?php 
wp_enqueue_media();
wp_enqueue_style("wp-jquery-ui-dialog");
wp_enqueue_script('jquery-ui-dialog');
wp_enqueue_script( TWOJ_GALLERY_ASSETS_PREFIX.'-field-type-gallery', TWOJ_GALLERY_FILEDS_URL.'asset/fields/gallery/script.js', array('jquery'), false, true);
wp_enqueue_style ( TWOJ_GALLERY_ASSETS_PREFIX.'-field-type-gallery', TWOJ_GALLERY_FILEDS_URL.'asset/fields/gallery/style.css', array( ), '' );

if ( $value == null || empty( $value ) || $value == ' ' || $value == '' ) {
	$value = ' ';
}
?>

<?php if ($label) : ?>
	<div class="field small-12 columns">
		<label>
			<?php echo $label; ?>
		</label>
	</div>
<?php endif; ?>

<div class="content small-12 columns small-centered text-center">

	<button type="button" class="success large button expanded twojGalleryFieldImagesButton">
		<?php _e('Manage Images','blog-mechanics-theme-gallery'); ?>
	</button>
	<input id="<?php echo $id; ?>" <?php echo $attributes; ?> type="hidden" name="<?php echo $name; ?>" value="<?php echo $value; ?>">
</div>
<div class="content small-12 columns small-centered">
	
</div>
<?php if ($description) : ?>
	<div class="content small-12 columns">
		<p class="help-text"><?php echo $description; ?></p>
	</div>
<?php endif; ?>

