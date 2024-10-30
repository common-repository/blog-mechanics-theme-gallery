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


?>
<div class="wrap">
	<h2><?php 
		_e('2J Gallery', 'blog-mechanics-theme-gallery');
		echo ' ';
		_e('General Settings', 'blog-mechanics-theme-gallery'); 
	?></h2>

	<form method="post" action="options.php" novalidate="novalidate">
		<?php 
		settings_fields( 'twoj_gallery_options' ); 
		do_settings_sections( 'twoj_gallery_options' ); 
		 ?>
		<table class="form-table">
			<tr>
				<th scope="row"><?php _e('Editable Sliders', 'blog-mechanics-theme-gallery'); ?></th>
				<td>
					<fieldset>
						<legend class="screen-reader-text"><span><?php _e('Show Text', 'blog-mechanics-theme-gallery'); ?></span></legend>
						<label title='<?php _e('Enable', 'blog-mechanics-theme-gallery'); ?>'>
							<input type='radio' name='<?php echo TWOJ_GALLERY.'UI_Readonly'; ?>' value='1' <?php if( get_option(TWOJ_GALLERY.'UI_Readonly', '')=='1' ) echo " checked='checked'"; ?> /> 
							<?php echo __('Enable', 'blog-mechanics-theme-gallery'); ?>
						</label><br />
						<label title='<?php _e('Disable', 'blog-mechanics-theme-gallery'); ?>'>
							<input type='radio' name='<?php echo TWOJ_GALLERY.'UI_Readonly'; ?>' value='0' <?php if( !get_option(TWOJ_GALLERY.'UI_Readonly') ) echo " checked='checked'"; ?>  /> 
							<?php echo __('Disable', 'blog-mechanics-theme-gallery'); ?>
						</label><br />			
					</fieldset>
				</td>
			</tr>
			<tr>
				<td colspan="2" scope="row">
					<p class="description">
						<?php _e('this option enable/disable editable text fields for sliders in gallery options', 'blog-mechanics-theme-gallery'); ?>
					</p>
				</td>
			</tr>

			<tr>
				<th scope="row"><?php _e('Show  gallery options in media library', 'blog-mechanics-theme-gallery'); ?></th>
				<td>
					<fieldset>
						<legend class="screen-reader-text"><span><?php _e('Show  gallery options in media library', 'blog-mechanics-theme-gallery'); ?></span></legend>
						
						<label title='<?php _e('Show', 'blog-mechanics-theme-gallery'); ?>'>
							<input type='radio' name='<?php echo TWOJ_GALLERY.'ML_Options'; ?>' value='1' <?php if( get_option(TWOJ_GALLERY.'ML_Options', '')=='1' ) echo " checked='checked'"; ?> /> 
							<?php echo __('Show', 'blog-mechanics-theme-gallery'); ?>
						</label>
						<br />
						<label title='<?php _e('Hide', 'blog-mechanics-theme-gallery'); ?>'>
							<input type='radio' name='<?php echo TWOJ_GALLERY.'ML_Options'; ?>' value='0' <?php if( !get_option(TWOJ_GALLERY.'ML_Options') ) echo " checked='checked'"; ?>  /> 
							<?php echo __('Hide', 'blog-mechanics-theme-gallery'); ?>
						</label><br />			
					</fieldset>
				</td>
			</tr>

		</table>
		<br/>
		<br/>
		<br/>
		<br/>
		<p class="submit">
			<input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e('Save Changes', 'blog-mechanics-theme-gallery'); ?>"  />
		</p>

	</form>
</div>
<div class="">
	Copyright &copy; 2008-2017 2J Team <?php _e('All Rights Reserved', 'blog-mechanics-theme-gallery'); ?>
</div>