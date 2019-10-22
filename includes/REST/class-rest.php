<?php
namespace ApproveMe;

/**
 * Initializes a REST API for ApproveMe.
 *
 * @since 1.0
 */
class REST {

	/**
	 * REST plugins.
	 *
	 * @access protected
	 * @since  1.0
	 * @var    \ApproveMe\REST\v1\Plugins
	 */
	public $plugins;

	/**
	 * REST Events
	 *
	 * @access protected
	 * @since 1.0
	 * @var \ApproveMe\REST\v1\Events
	 */
	public $events;

	/**
	 * REST Connect
	 * 
	 * @access protected
	 * @since 1.0
	 * @var \ApproveMe\REST\v1\Connect
	 */
	public $connect;

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
		$this->events  = new \ApproveMe\REST\v1\Events;
		$this->connect = new \ApproveMe\REST\v1\Connect;
	}

}
