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

// Exit if accessed directly

defined( 'ABSPATH' ) || exit;

function approveme_admin_menu() {
	$capability = apply_filters( 'approveme_menu_access', 'manage_options' );

	add_menu_page(
		__( 'ApproveMe', 'approveme' ),
		__( 'ApproveMe', 'approveme' ),
		$capability,
		'approveme',
		'approveme_main_page',
		'dashicons-media-text',
		6
	);
	add_submenu_page(
		'approveme',
		__( 'Documents', 'approveme' ),
		__( 'Documents', 'approveme' ),
		$capability,
		'approveme',
		'approveme_main_page'
	);
	add_submenu_page(
		'approveme',
		__( 'Settings', 'approveme' ),
		__( 'Settings', 'approveme' ),
		$capability,
		'approveme-settings',
		'approveme_settings'
	);
}
add_action( 'admin_menu', 'approveme_admin_menu' );

function approveme_admin_styles() {
	//var_dump(APPROVEME_PLUGIN_DIR . 'assets/admin/styles.css'); exit;
	wp_register_style( 'approveme-admin', APPROVEME_PLUGIN_URL . '/assets/admin/styles.css', false, APPROVEME_VERSION );
	wp_enqueue_style( 'approveme-admin' );
}
add_action( 'admin_enqueue_scripts', 'approveme_admin_styles' );

function approveme_main_page() {
	/** Test document data */
	$documents = file_get_contents( APPROVEME_PLUGIN_DIR . '/assets/documents.json' );
	$documents = json_decode( $documents );

	?>
	<div class="wrap">
		<h1><?php _e( 'ApproveMe Documents', 'approveme' ); ?></h1>
		<div class="approveme-documents">
			<?php foreach ( $documents as $document ) : ?>
				<?php approveme_document_item( $document ); ?>
			<?php endforeach; ?>
		</div>
	</div>
	<?php
}

function approveme_document_item( $document ) {
	?>
	<div class="approveme-document" data-id="<?php echo esc_attr( $document->id ); ?>">
		<img src="<?php echo esc_attr( APPROVEME_PLUGIN_URL . '/assets/images/document-placeholder.png' ); ?>" alt="<?php echo esc_attr( $document->name ); ?>">
		<div class="container">
			<span class="document-title"><?php echo esc_html( $document->name ); ?></span>
			<a href="#" title="<?php echo esc_attr( 'Send Document', 'approveme' ); ?>">
				<span class="approveme-send dashicons dashicons-share"></span>
			</a>
			<a href="#" title="<?php echo esc_attr( 'Open Document', 'approveme' ); ?>">
				<span class="dashicons dashicons-media-document"></span>
			</a>
		</div>
	</div>
	<?php
}

function approveme_settings() {

}