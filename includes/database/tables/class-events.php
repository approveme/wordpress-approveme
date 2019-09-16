<?php
/**
 * Events Table.
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
 * Setup the global "approveme_events" database table.
 *
 * @since 1.0
 */
final class Events extends Table {

	/**
	 * Table name.
	 *
	 * @access protected
	 * @since 1.0
	 * @var string
	 */
	protected $name = 'events';

	/**
	 * Database version.
	 *
	 * @access protected
	 * @since 1.0
	 * @var int
	 */
	protected $version = 201909150001;

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
	protected $upgrades = array(
		'201909150001',
	);

	/**
	 * Setup the database schema.
	 *
	 * @access protected
	 * @since 1.0
	 */
	protected function set_schema() {
		$this->schema = "id bigint(20) unsigned NOT NULL auto_increment,
			approveme_id text(100) NOT NULL default '',
			content mediumtext NOT NULL default '',
			status int(1) unsigned NOT NULL default '0',
			created_at timestamp,
			processed_at timestamp,
			PRIMARY KEY (id),
			UNIQUE (approveme_id (100))";
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

	protected function __201909150001() {
		@$this->get_db()->query( "ALTER TABLE {$this->table_name} ADD UNIQUE (approveme_id (100))" );
	}
}