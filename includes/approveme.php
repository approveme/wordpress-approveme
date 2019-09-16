<?php

namespace ApproveMe;

use ApprovemeAPI\Client\Api;

/**
 * ApproveMe Class.
 *
 * @since 1.4
 * @since 1.0 Refactored and restructured to work with ApproveMe_Requirements_Check.
 */
class Base {

	/**
	 * @var ApproveMe The one true Easy_Digital_Downloads
	 *
	 * @since 1.4
	 */
	private static $instance;

	/**
	 * EDD loader file.
	 *
	 * @since 1.0
	 * @var string
	 */
	private $file = '';

	/**
	 * ApproveMe API Object.
	 *
	 * @var object|Approveme_API\Client\Api
	 * @since 1.5
	 */
	public $api;

	/**
	 * @var object|ApproveMe\Rest\
	 * @since 1.0
	 */
	public $rest;


	/**
	 * ApproveMe Components array
	 *
	 * @var array
	 * @since 1.0
	 */
	public $components = array();

	public $background_processor;

	/**
	 * Main ApproveMe Instance.
	 *
	 * Insures that only one instance of ApproveMe exists in memory at any one
	 * time. Also prevents needing to define globals all over the place.
	 *
	 * @since 1.4
	 * @since 1.0 Accepts $file parameter to work with ApproveMe_Requirements_Check
	 *
	 * @static
	 * @staticvar array $instance
	 *
	 * @uses ApproveMe::setup_constants() Setup constants.
	 * @uses ApproveMe::setup_files() Setup required files.
	 * @see ApproveMe()
	 *
	 * @return object|ApproveMe The one true ApproveMe
	 */
	public static function instance( $file = '' ) {

		if ( ! empty( self::$instance->file ) ) {
			$file = self::$instance->file;
		}

		// Return if already instantiated
		if ( self::is_instantiated() ) {
			return self::$instance;
		}

		// Setup the singleton
		self::setup_instance( $file );

		// Bootstrap
		self::$instance->setup_files();
		self::$instance->setup_application();

		// Return the instance
		return self::$instance;
	}

	/**
	 * Throw error on object clone.
	 *
	 * The whole idea of the singleton design pattern is that there is a single
	 * object therefore, we don't want the object to be cloned.
	 *
	 * @since 1.6
	 * @access protected
	 * @return void
	 */
	public function __clone() {
		// Cloning instances of the class is forbidden.
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'approveme' ), '1.0' );
	}

	/**
	 * Disable un-serializing of the class.
	 *
	 * @since 1.6
	 * @access protected
	 * @return void
	 */
	public function __wakeup() {
		// Unserializing instances of the class is forbidden.
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'approveme' ), '1.0' );
	}

	/**
	 * Return whether the main loading class has been instantiated or not.
	 *
	 * @since 1.0
	 *
	 * @return boolean True if instantiated. False if not.
	 */
	private static function is_instantiated() {

		// Return true if instance is correct class
		if ( ! empty( self::$instance ) && ( self::$instance instanceof \ApproveMe\Base ) ) {
			return true;
		}

		// Return false if not instantiated correctly
		return false;
	}

	/**
	 * Setup the singleton instance
	 *
	 * @since 1.0
	 * @param string $file
	 */
	private static function setup_instance( $file = '' ) {
		self::$instance       = new Base;
		self::$instance->file = $file;
	}

	/**
	 * Include required files.
	 *
	 * @access private
	 * @since 1.4
	 * @return void
	 */
	private function setup_files() {
		$this->configure_apis();
		$this->include_utilities();
		$this->include_components();

		// Admin
		if ( is_admin() ) {
			$this->include_admin();
		} else {
			$this->include_frontend();
		}

		add_action( 'init', array( $this, 'setup_background_processor' ) );

	}

	private function setup_application() {
		approveme_setup_components();
	}

	private function configure_apis() {

		// Load REST components.
		require_once APPROVEME_PLUGIN_DIR . 'includes/REST/class-rest.php';
		require_once APPROVEME_PLUGIN_DIR . 'includes/REST/rest-functions.php';

		// REST API Endpoints.
		require_once APPROVEME_PLUGIN_DIR . 'includes/REST/v1/class-rest-controller.php';
		require_once APPROVEME_PLUGIN_DIR . 'includes/REST/v1/class-plugins-endpoints.php';
		require_once APPROVEME_PLUGIN_DIR . 'includes/REST/v1/class-events-endpoints.php';

		// Background processor.
		require APPROVEME_PLUGIN_DIR . 'includes/class-logger.php';
		require APPROVEME_PLUGIN_DIR . 'includes/class-background-processor.php';

		$api_config = new \ApprovemeAPI\Client\Configuration();
		$api_config->setAccessToken( APPROVEME_ACCESS_TOKEN );

		$this->rest = new REST();

	}

	public function setup_background_processor() {
		$background_processor = new \ApproveMe\Background_Process();
		$background_processor->dispatch();
	}

	/** Includes **************************************************************/

	private function include_utilities() {
		require_once APPROVEME_PLUGIN_DIR . 'includes/class-base-object.php';
	}

	private function include_components() {
		// Component helpers are loaded before everything
		require_once APPROVEME_PLUGIN_DIR . 'includes/interface-approveme-exception.php';
		require_once APPROVEME_PLUGIN_DIR . 'includes/component-functions.php';
		require_once APPROVEME_PLUGIN_DIR . 'includes/class-component.php';

		// Database Engine
		require_once APPROVEME_PLUGIN_DIR . 'includes/database/engine/class-base.php';
		require_once APPROVEME_PLUGIN_DIR . 'includes/database/engine/class-column.php';
		require_once APPROVEME_PLUGIN_DIR . 'includes/database/engine/class-schema.php';
		require_once APPROVEME_PLUGIN_DIR . 'includes/database/engine/class-query.php';
		require_once APPROVEME_PLUGIN_DIR . 'includes/database/engine/class-row.php';
		require_once APPROVEME_PLUGIN_DIR . 'includes/database/engine/class-table.php';

		// Database Schemas
		require_once APPROVEME_PLUGIN_DIR . 'includes/database/schemas/class-oauthclients.php';
		require_once APPROVEME_PLUGIN_DIR . 'includes/database/schemas/class-oauth-access-tokens.php';
		require_once APPROVEME_PLUGIN_DIR . 'includes/database/schemas/class-events.php';

		// Database Objects
		require_once APPROVEME_PLUGIN_DIR . 'includes/database/rows/class-oauthclient.php';
		require_once APPROVEME_PLUGIN_DIR . 'includes/database/rows/class-oauth-access-token.php';
		require_once APPROVEME_PLUGIN_DIR . 'includes/database/rows/class-event.php';

		// Database Tables
		require_once APPROVEME_PLUGIN_DIR . 'includes/database/tables/class-oauthclients.php';
		require_once APPROVEME_PLUGIN_DIR . 'includes/database/tables/class-oauth-access-tokens.php';
		require_once APPROVEME_PLUGIN_DIR . 'includes/database/tables/class-events.php';

		// Database Table Query Interfaces
		require_once APPROVEME_PLUGIN_DIR . 'includes/database/queries/class-compare.php';
		require_once APPROVEME_PLUGIN_DIR . 'includes/database/queries/class-oauthclient.php';
		require_once APPROVEME_PLUGIN_DIR . 'includes/database/queries/class-oauth-access-token.php';
		require_once APPROVEME_PLUGIN_DIR . 'includes/database/queries/class-event.php';
	}

	/**
	 * Setup administration
	 *
	 * @since 1.0
	 */
	private function include_admin() {
		require_once APPROVEME_PLUGIN_DIR . 'includes/admin/admin-pages.php';
	}

	/**
	 * Setup frontend
	 *
	 * @since 1.0
	 */
	private function include_frontend() {}

}

/**
 * Returns the instance of ApproveMe.
 *
 * The main function responsible for returning the one true ApproveMe
 * instance to functions everywhere.
 *
 * Use this function like you would a global variable, except without needing
 * to declare the global.
 *
 * Example: <?php $approveme = ApproveMe(); ?>
 *
 * @since 1.4
 * @return ApproveMe The one true ApproveMe instance.
 */
function ApproveMe() {
	return Base::instance();
}