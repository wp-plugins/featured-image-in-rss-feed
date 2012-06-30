<?php
// create custom plugin settings menu
add_action('admin_menu', 'fir_create_menu');

if ($_POST['license_submit']){
	$license_key_return = wp_remote_fopen('http://dineshkarki.com.np/license/validate_key.php?license_key='.$_POST['fir_license_key']);
	$license_key_return = json_decode($license_key_return);
	if (!empty($license_key_return)){
		if ($license_key_return->status == 'success'){
			update_option('fir_license_key_status', 'activated');
			update_option('fir_license_key_valid_till', $license_key_return->valid_till);
			update_option('fir_license_key_code', $_POST['fir_license_key']);
		}
		$license_message = $license_key_return->msg;
		$license_status	 = $license_key_return->status;
	} else {
		$license_status	 = 'error';
		$license_message = 'Sorry there was an error. Please try again.';
	}
	
	if ($license_status	== 'error'){
		$lic_notice_class	= 'error';
	} else {
		$lic_notice_class	= 'updated';
	}	
}

$fir_license_key_status		= get_option('fir_license_key_status');
$fir_license_key_valid_till	= get_option('fir_license_key_valid_till');

switch ($fir_license_key_status){
	case 'trial':
		if (strtotime($fir_license_key_valid_till) <= strtotime(date('Y-m-d'))){
			add_action('admin_notices', 'fir_license_notice_trial_expired');
			update_option('fir_license_key_status', 'trial_expired');
		} else {
			add_action('admin_notices', 'fir_license_notice_trial');
		}	
	break;
	
	case 'trial_expired':
		add_action('admin_notices', 'fir_license_notice_trial_expired');
	break;
	
	case 'activated':
		if (strtotime($fir_license_key_valid_till) < strtotime(date('Y-m-d'))){
			add_action('admin_notices', 'fir_license_notice_expired');
			update_option('fir_license_key_status', 'expired');
		}
	break;
	
	case 'expired':
		add_action('admin_notices', 'fir_license_notice_expired');
	break;
	
	default:
		update_option('fir_license_key_status', 'trial');
		$trial_valid_till = date('Y-m-d', strtotime("+31 days"));
		update_option('fir_license_key_valid_till', $trial_valid_till);
		update_option('fir_license_key_code', '');
		add_action('admin_notices', 'fir_license_notice_trial');
	break;	
}
$fir_license_key_status		= get_option('fir_license_key_status');

function fir_license_notice_expired(){
    echo '<div class="error">
       <p>Your License Key of <b>Featured Image In RSS Feed</b> has been expired. Offer your price and get the license key from <a href="http://dineshkarki.com.np/license/" target="_blank">Dnesscarkey</a>. If you already have the license key click <a href="options-general.php?page=featured-image-in-rss-feed/plugin_interface.php">here</a> to activate.</p></div>';
}


function fir_license_notice_trial(){
    $fir_license_key_valid_till	= get_option('fir_license_key_valid_till');
	$today_date 				= time();
    $expire_date 				= strtotime($fir_license_key_valid_till);
    $datediff 					= $expire_date - $today_date;
    $remainingDays 				= floor($datediff/(60*60*24));
	echo '<div class="error">
       <p>You are using <b>Featured Image In RSS Feed</b> as a trial. Offer your price ($0 - $100) and get the license key from <a href="http://dineshkarki.com.np/license/" target="_blank">Dnesscarkey</a>. If you already have the license key click <a href="options-general.php?page=featured-image-in-rss-feed/plugin_interface.php">here</a> to activate.</p>
	   <p><strong>Remaining Days :</strong> '.$remainingDays.' </p></div>';
}

function fir_license_notice_trial_expired(){
    echo '<div class="error">
       <p>Your Trial Period of <b>Featured Image In RSS Feed</b> has been expired. Offer your price and get the license key from <a href="http://dineshkarki.com.np/license/" target="_blank">Dnesscarkey</a>. If you already have the license key click <a href="options-general.php?page=featured-image-in-rss-feed/plugin_interface.php">here</a> to activate.</p>
    </div>';
}

function fir_create_menu() {
	add_options_page('Image In RSS Feed', 'Image In Rss Feed', 'administrator', __FILE__, 'fir_settings_page');
	add_action('admin_init', 'register_firsettings');
}

function register_firsettings() {
	register_setting('fir-settings-group', 'fir_rss_image_size');
}

function fir_settings_page() {
	global $fir_license_key_status;
	global $license_message;
	global $lic_notice_class;
	
	$fir_rss_image_size 			= get_option('fir_rss_image_size');
	if (empty($fir_rss_image_size)){
		update_option('fir_rss_image_size', 'thumbnail');
		$fir_rss_image_size	= get_option('fir_rss_image_size');
	}	
?>
<div class="wrap">
<h2>Featured Image In RSS Feed</h2>

<style>
fieldset.license_key { border:1px solid #060; padding:5px;-webkit-border-radius: 5px;border-radius: 5px; padding-bottom:10px;}
fieldset.license_key legend{ color:#060; font-size:15px; font-weight:bold; padding-left:5px; padding-right:5px;}
fieldset.license_key input[type=text]{ margin-right:20px;}
</style>
<?php


if (!empty($license_message)){
	echo '<div class="'.$lic_notice_class.'"><p>'.$license_message.'</p></div>';
}
?>

<fieldset class="license_key">
<legend>License Key</legend>
<form method="post" action="">
<table class="form-table">
        <tr valign="top">
        <td>Status : </td><td><strong><?php echo ucfirst(str_replace('_',' ', $fir_license_key_status)); ?></strong></td>
        </tr>
        
		<?php if ($fir_license_key_status != 'activated'): ?>
        <tr valign="top">
        <td>Key</td>
        <td>
        <input type="text" maxlength="40" style="width:300px;" name="fir_license_key" /><input name="license_submit" class="button-primary" type="submit" value="Activate" />
        </td>
        </tr>
        <?php endif; ?>
        
        
</table>
</form>
</fieldset>

<br/>

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