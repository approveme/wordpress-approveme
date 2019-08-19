<?php
/**
 * Adjustments Schema Class.
 *
 * @package     ApproveMe
 * @subpackage  Database\Schemas
 * @copyright   Copyright (c) 2018, ApproveMe, LLC
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       3.0
 */
namespace ApproveMe\Database\Schemas;

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

use ApproveMe\Database\Schema;

/**
 * Adjustments Schema Class.
 *
 * @since 3.0
 */
final class oAuthClients extends Schema {

	/**
	 * Array of database column objects.
	 *
	 * @since 3.0
	 * @var array
	 */
	public $columns = array(

		// id
		array(
			'name'       => 'id',
			'type'       => 'bigint',
			'length'     => '20',
			'unsigned'   => true,
			'extra'      => 'auto_increment',
			'primary'    => true,
			'sortable'   => true,
		),

		// user_id
		array(
			'name'       => 'user_id',
			'type'       => 'varchar',
			'length'     => '20',
			'searchable' => true,
			'sortable'   => true,
		),

		// name
		array(
			'name'       => 'name',
			'type'       => 'varchar',
			'length'     => '200',
			'searchable' => true,
			'sortable'   => true,
		),

		// secret
		array(
			'name'       => 'secret',
			'type'       => 'varchar',
			'length'     => '200',
			'searchable' => true,
			'sortable'   => true,
		),

		// created_at
		array(
			'name'       => 'created_at',
			'type'       => 'timestamp',
			'length'     => '20',
			'default'    => 'draft',
			'date_query' => true,
			'searchable' => true,
			'sortable'   => true,
		),

	);
}
