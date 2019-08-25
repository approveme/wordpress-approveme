<?php
namespace ApproveMe;

/**
 * Initializes a REST API for ApproveMe.
 *
 * @since 1.0
 */
class REST {

	/**
	 * REST Authentication.
	 *
	 * @access protected
	 * @since  1.0
	 * @var    \ApproveMe\REST\v1\Plugins
	 */
	public $plugins;

	/**
	 * Constructor.
	 *
	 * @access public
	 * @since  1.0
	 */
	public function __construct() {
		$this->setup_endpoints();
	}

	/**
	 * Sets up REST components.
	 *
	 * @access private
	 * @since  1.0
	 */
	private function setup_endpoints() {
		$this->plugins = new \ApproveMe\REST\v1\Plugins;
	}

}
