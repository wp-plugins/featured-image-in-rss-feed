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
	$fir_add_tags_to_page 			= get_option('fir_rss_image_size');
	
	if (empty($fir_rss_image_size)){
		update_option('fir_rss_image_size', 'thumbnail');
		$fir_rss_image_size	= get_option('fir_rss_image_size');
	}
	
?>
<div class="wrap">
<h2>Featured Image In RSS Feed</h2>
<br/>
<div style="-webkit-border-radius: 5px;-moz-border-radius: 5px;border-radius: 5px; border:1px solid #e9e9e9; padding:10px;-webkit-box-shadow: 0px 0px 1px 2px #969696;-moz-box-shadow: 0px 0px 1px 2px #969696;box-shadow: 0px 0px 1px 2px #969696; text-align:center; font-size:14px;"> 
<em><b>This Plugin is FREE and always will be. Support this plugin by <a href="http://dineshkarki.com.np/donate" target="_blank">DONATING</a>. Any Amount is Appreciated.</b></em>
</div>

    <form method="post" action="options.php">
	<?php settings_fields( 'fir-settings-group' ); ?>
	<table class="form-table">
        <tr valign="top">
            <th scope="row">Image Size</th>
            <td>
                <select name="fir_rss_image_size"  />
                   <option value="thumbnail" <?php echo $fir_rss_image_size == 'thumbnail'?'selected="selected"':''; ?>>Thumbnail</option>
                   <option value="medium" <?php echo $fir_rss_image_size == 'medium'?'selected="selected"':''; ?>>Medium</option>
                   <option value="thumbnail" <?php echo $fir_rss_image_size == 'large'?'selected="selected"':''; ?>>Large</option>
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