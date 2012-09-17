<?php
// create custom plugin settings menu
add_action('admin_menu', 'fir_create_menu');

function fir_create_menu() {
	add_options_page('Image In RSS Feed', 'Image In Rss Feed', 'administrator', __FILE__, 'fir_settings_page');
	add_action('admin_init', 'register_firsettings');
}

function register_firsettings() {
	register_setting('fir-settings-group', 'fir_rss_image_size');
}

function fir_settings_page() {
	$fir_rss_image_size 			= get_option('fir_rss_image_size');
	if (empty($fir_rss_image_size)){
		update_option('fir_rss_image_size', 'thumbnail');
		$fir_rss_image_size	= get_option('fir_rss_image_size');
	}	
?>
<div class="wrap">
<h2>Featured Image In RSS Feed</h2>

    <form method="post" action="options.php">
	<?php settings_fields( 'fir-settings-group' ); ?>
	<table class="form-table">
        <tr valign="top">
            <th scope="row">Image Size</th>
            <td>
                <select name="fir_rss_image_size">
                   <option value="thumbnail" <?php echo $fir_rss_image_size == 'thumbnail'?'selected="selected"':''; ?>>Thumbnail</option>
                   <option value="medium" <?php echo $fir_rss_image_size == 'medium'?'selected="selected"':''; ?>>Medium</option>
                   <option value="large" <?php echo $fir_rss_image_size == 'large'?'selected="selected"':''; ?>>Large</option>
                   <option value="full" <?php echo $fir_rss_image_size == 'full'?'selected="selected"':''; ?>>Full Size</option>
                </select>
            </td>
        </tr>
       
    </table>
    
    <p class="submit">
    <input type="submit" name="submit-bpu" class="button-primary" value="<?php _e('Save Changes') ?>" />
    </p>
</form>
</div>
<?php } ?>