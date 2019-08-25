<?php
/**
 * oAuth Clients Table.
 *
 * @package     ApproveMe
 * @subpackage  Database\Tables
 * @copyright   Copyright (c) 2018, ApproveMe, LLC
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 */
namespace ApproveMe\Database\Tables;

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

use ApproveMe\Database\Table;

/**
 * Setup the global "approveme_oauth_clients" database table.
 *
 * @since 1.0
 */
final class oAuthClients extends Table {

	/**
	 * Table name.
	 *
	 * @access protected
	 * @since 1.0
	 * @var string
	 */
	protected $name = 'oauth_clients';

	/**
	 * Database version.
	 *
	 * @access protected
	 * @since 1.0
	 * @var int
	 */
	protected $version = 201908180001;

	/**
	 * Array of upgrade versions and methods.
	 *
	 * When adding a change to the database, add the version to this array in the format of:
	 * <4 digit year><2 digit month><2 digit day><version>.
	 *
	 * The <version> is a 4 digit integer (0001, 0002, 0003, 0023)
	 *
	 * If you make multiple upgrades in the same date version, you just add the next 4 digit integer.
	 *
	 * To execute an upgrade, create a function prefixed with `__` followed by the version defined as described above. And
	 * Set the classes $version property to the highest version needed.
	 *
	 * @access protected
	 * @since 1.0
	 * @var array
	 */
	protected $upgrades = array();

	/**
	 * Setup the database schema.
	 *
	 * @access protected
	 * @since 1.0
	 */
	protected function set_schema() {
		$this->schema = "id bigint(20) unsigned NOT NULL auto_increment,
			user_id varchar(20) NOT NULL,
			name varchar(200) NOT NULL default '',
			secret varchar(200) NOT NULL default '',
			created_at timestamp,
			PRIMARY KEY (id)";
	}

	/**
	 * Create the table
	 *
	 * @since 1.0
	 *
	 * @return bool
	 */
	public function create() {

		$created = parent::create();
		return $created;

	}
}