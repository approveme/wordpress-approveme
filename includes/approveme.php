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
	 * EDD API Object.
	 *
	 * @var object|Approveme_API\Client\Api
	 * @since 1.5
	 */
	public $api;

	/**
	 * ApproveMe Components array
	 *
	 * @var array
	 * @since 1.0
	 */
	public $components = array();

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

		// Return if already instantiated
		if ( self::is_instantiated() ) {
			return self::$instance;
		}

		// Setup the singleton
		self::setup_instance( $file );

		// Bootstrap
		self::$instance->setup_constants();
		self::$instance->setup_files();

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
		if ( ! empty( self::$instance ) && ( self::$instance instanceof ApproveMe ) ) {
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
	 * Setup plugin constants.
	 *
	 * @access private
	 * @since 1.4
	 * @return void
	 */
	private function setup_constants() {

		// Plugin version.
		if ( ! defined( 'APPROVEME_VERSION' ) ) {
			define( 'APPROVEME_VERSION', '0.1' );
		}

		// Plugin Root File.
		if ( ! defined( 'APPROVEME_PLUGIN_FILE' ) ) {
			define( 'APPROVEME_PLUGIN_FILE', self::$instance->file );
		}

		// Plugin Base Name.
		if ( ! defined( 'APPROVEME_PLUGIN_BASE' ) ) {
			define( 'APPROVEME_PLUGIN_BASE', plugin_basename( APPROVEME_PLUGIN_FILE ) );
		}

		// Plugin Folder Path.
		if ( ! defined( 'APPROVEME_PLUGIN_DIR' ) ) {
			define( 'APPROVEME_PLUGIN_DIR', plugin_dir_path( APPROVEME_PLUGIN_FILE ) );
		}

		// Plugin Folder URL.
		if ( ! defined( 'APPROVEME_PLUGIN_URL' ) ) {
			define( 'APPROVEME_PLUGIN_URL', plugin_dir_url( APPROVEME_PLUGIN_FILE ) );
		}

		// Make sure CAL_GREGORIAN is defined.
		if ( ! defined( 'CAL_GREGORIAN' ) ) {
			define( 'CAL_GREGORIAN', 1 );
		}
	}

	/**
	 * Include required files.
	 *
	 * @access private
	 * @since 1.4
	 * @return void
	 */
	private function setup_files() {
		$this->configure_api();

		// Admin
		if ( is_admin() ) {
			$this->include_admin();
		} else {
			$this->include_frontend();
		}

	}

	private function configure_api() {
		$api_config = new \ApprovemeAPI\Client\Configuration();
		$api_config->setAccessToken( APPROVEME_ACCESS_TOKEN );
	}

	/** Includes **************************************************************/

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
	private function include_frontend() {
	}

}

/**
 * Returns the instance of ApproveMe.
 *
 * The main function responsible for returning the one true Easy_Digital_Downloads
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
	return ApproveMe::instance();
}