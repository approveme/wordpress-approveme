<?php
/**
 * oAuthAccessTokens Schema Class.
 *
 * @package     ApproveMe
 * @subpackage  Database\Schemas
 * @copyright   Copyright (c) 2018, ApproveMe, LLC
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 */
namespace ApproveMe\Database\Schemas;

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

use ApproveMe\Database\Schema;

/**
 * oAuthAccessTokens Schema Class.
 *
 * @since 1.0
 */
final class oAuthAccessTokens extends Schema {

	/**
	 * Array of database column objects.
	 *
	 * @since 1.0
	 * @var array
	 */
	public $columns = array(

		// id
		array(
			'name'       => 'id',
			'type'       => 'varchar',
			'length'     => '100',
			'sortable'   => true,
			'searchable' => true,
		),

		// client_id
		array(
			'name'       => 'client_id',
			'type'       => 'bigint',
			'length'     => '20',
			'searchable' => true,
			'sortable'   => true,
		),

		// created_at
		array(
			'name'       => 'created_at',
			'type'       => 'timestamp',
			'sortable'   => true,
		),

		// expires_at
		array(
			'name'       => 'expires_at',
			'type'       => 'timestamp',
			'sortable'   => true,
		),

	);
}
