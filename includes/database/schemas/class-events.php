<?php
/**
 * Events Schema Class.
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
final class Events extends Schema {

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

		// approveme_id
		array(
			'name'       => 'approveme_id',
			'type'       => 'text',
			'length'     => '100',
			'searchable' => true,
			'sortable'   => true,
		),

		// content
		array(
			'name'       => 'content',
			'type'       => 'mediumtext',
			'searchable' => false,
			'sortable'   => false,
		),

		// status
		array(
			'name'       => 'status',
			'type'       => 'int',
			'length'     => '1',
			'unsigned'   => true,
			'searchable' => true,
			'sortable'   => true,
		),

		// created_at
		array(
			'name'       => 'created_at',
			'type'       => 'timestamp',
			'default'    => 'draft',
			'date_query' => true,
			'searchable' => true,
			'sortable'   => true,
		),

		// processed_at
		array(
			'name'       => 'processed_at',
			'type'       => 'timestamp',
			'default'    => 'draft',
			'date_query' => true,
			'searchable' => true,
			'sortable'   => true,
		),

	);
}
