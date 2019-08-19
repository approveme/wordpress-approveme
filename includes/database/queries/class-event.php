<?php
/**
 * Event Query Class.
 *
 * @package     ApproveMe
 * @subpackage  Database\Queries
 * @copyright   Copyright (c) 2018, ApproveMe, LLC
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 */
namespace ApproveMe\Database\Queries;

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

use ApproveMe\Database\Query;

/**
 * Class used for querying adjustments.
 *
 * @since 1.0
 *
 * @see \ApproveMe\Database\Queries\oAuthClient::__construct() for accepted arguments.
 */
class Event extends Query {

	/** Table Properties ******************************************************/

	/**
	 * Name of the database table to query.
	 *
	 * @since 1.0
	 * @access protected
	 * @var string
	 */
	protected $table_name = 'events';

	/**
	 * String used to alias the database table in MySQL statement.
	 *
	 * @since 1.0
	 * @access protected
	 * @var string
	 */
	protected $table_alias = 'e';

	/**
	 * Name of class used to setup the database schema
	 *
	 * @since 1.0
	 * @access protected
	 * @var string
	 */
	protected $table_schema = '\\ApproveMe\\Database\\Schemas\\Events';

	/** Item ******************************************************************/

	/**
	 * Name for a single item
	 *
	 * @since 1.0
	 * @access protected
	 * @var string
	 */
	protected $item_name = 'event';

	/**
	 * Plural version for a group of items.
	 *
	 * @since 1.0
	 * @access protected
	 * @var string
	 */
	protected $item_name_plural = 'events';

	/**
	 * Callback function for turning IDs into objects
	 *
	 * @since 1.0
	 * @access protected
	 * @var mixed
	 */
	protected $item_shape = '\\ApproveMe\\Database\\Rows\\Event';

	/** Cache *****************************************************************/

	/**
	 * Group to cache queries and queried items in.
	 *
	 * @since 1.0
	 * @access protected
	 * @var string
	 */
	protected $cache_group = 'events';

	/** Methods ***************************************************************/

	/**
	 * Sets up the event query, based on the query vars passed.
	 *
	 * @since 1.0
	 * @access protected
	 *
	 * @param string|array $query {
	 *     Optional. Array or query string of adjustment query parameters. Default empty.
	 *
	 *     @type int          $id                   An event ID to only return that event. Default empty.
	 *     @type array        $id__in               Array of event IDs to include. Default empty.
	 *     @type array        $id__not_in           Array of event IDs to exclude. Default empty.
	 *     @type string       $approveme_id         A approveme_id ID to only return that event. Default empty.
	 *     @type array        $approveme_id__in     Array of approveme_ids to include. Default empty.
	 *     @type array        $approveme_id__not_in Array of approveme_ids to exclude. Default empty.
	 *     @type int          $status               An status to only return events that match. Default empty.
	 *     @type array        $status__in           Array of status to include. Default empty.
	 *     @type array        $status__not_in       Array of status to exclude. Default empty.
	 *     @type string       $created_at           A event created_at to only return that event. Default empty.
	 *     @type array        $created_at__in       Array of event created_at to include. Default empty.
	 *     @type array        $created_at__not_in   Array of event created_at to exclude. Default empty.
	 *     @type string       $processed_at         A event processed_at to only return that event. Default empty.
	 *     @type array        $processed_at__in     Array of event processed_at to include. Default empty.
	 *     @type array        $processed_at__not_in Array of event processed_at to exclude. Default empty.
	 * }
	 */
	public function __construct( $query = array() ) {
		parent::__construct( $query );
	}
}
