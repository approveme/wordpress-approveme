<?php
/**
 * Admin Pages
 *
 * @package     ApproveMe
 * @subpackage  Admin/Pages
 * @copyright   Copyright (c) 2018, ApproveMe, LLC
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 */

use ApprovemeAPI\Client\Configuration;
// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

function approveme_admin_menu() {
	$capability = apply_filters( 'approveme_menu_access', 'manage_options' );

	add_options_page(
		__( 'ApproveMe', 'approveme' ),
		__( 'ApproveMe', 'approveme' ),
		$capability,
		'approveme',
		'approveme_main_page'
	);
}
add_action( 'admin_menu', 'approveme_admin_menu' );

function approveme_main_page() {
	$url = trailingslashit( APPROVEME_APP_URL ) . 'oauth/authorize';
	$url = add_query_arg( array(
		'callback_url' => get_rest_url( null, 'approveme/v1/connect/callback' ),
		'oauth_url'    => get_rest_url( null, 'approveme/v1/connect/oauth' ),
	), $url );
	?>
	<div class="wrap">
		<h1><?php _e( 'ApproveMe', 'approveme' ); ?></h1>
		<a class="button" href="<?php echo $url; ?>">
			<?php _e( 'Connect', 'approveme' ); ?>
		</a>
	</div>
	<?php
}