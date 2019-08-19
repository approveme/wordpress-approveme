<?php
/**
 * oAuth Client Query Class.
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
class oAuthClient extends Query {

	/** Table Properties ******************************************************/

	/**
	 * Name of the database table to query.
	 *
	 * @since 1.0
	 * @access protected
	 * @var string
	 */
	protected $table_name = 'oauth_clients';

	/**
	 * String used to alias the database table in MySQL statement.
	 *
	 * @since 1.0
	 * @access protected
	 * @var string
	 */
	protected $table_alias = 'oc';

	/**
	 * Name of class used to setup the database schema
	 *
	 * @since 1.0
	 * @access protected
	 * @var string
	 */
	protected $table_schema = '\\ApproveMe\\Database\\Schemas\\oAuthClients';

	/** Item ******************************************************************/

	/**
	 * Name for a single item
	 *
	 * @since 1.0
	 * @access protected
	 * @var string
	 */
	protected $item_name = 'oauth_client';

	/**
	 * Plural version for a group of items.
	 *
	 * @since 1.0
	 * @access protected
	 * @var string
	 */
	protected $item_name_plural = 'oauth_clients';

	/**
	 * Callback function for turning IDs into objects
	 *
	 * @since 1.0
	 * @access protected
	 * @var mixed
	 */
	protected $item_shape = '\\ApproveMe\\Database\\Rows\\oAuthClient';

	/** Cache *****************************************************************/

	/**
	 * Group to cache queries and queried items in.
	 *
	 * @since 1.0
	 * @access protected
	 * @var string
	 */
	protected $cache_group = 'oauth_clients';

	/** Methods ***************************************************************/

	/**
	 * Sets up the adjustment query, based on the query vars passed.
	 *
	 * @since 1.0
	 * @access protected
	 *
	 * @param string|array $query {
	 *     Optional. Array or query string of adjustment query parameters. Default empty.
	 *
	 *     @type int          $id                   A oAuthClient ID to only return that oAuthClient. Default empty.
	 *     @type array        $id__in               Array of oAuthClient IDs to include. Default empty.
	 *     @type array        $id__not_in           Array of oAuthClient IDs to exclude. Default empty.
	 *     @type string       $user_id              A user_id to only return that oAuthClient. Default empty.
	 *     @type array        $user_id__in          Array of oAuthClient user_ids to include. Default empty.
	 *     @type array        $user_id__not_in      Array of oAuthClient user_ids to exclude. Default empty.
	 *     @type string       $name                 A oAuthClient name to only return that oAuthClient. Default empty.
	 *     @type array        $name__in             Array of oAuthClient names to include. Default empty.
	 *     @type array        $name__not_in         Array of oAuthClient names to exclude. Default empty.
	 *     @type string       $secret               A oAuthClient secret to only return that oAuthClient. Default empty.
	 *     @type array        $secret_in            Array of oAuthClient secrets to include. Default empty.
	 *     @type array        $secret__not_in       Array of oAuthClient secrets to exclude. Default empty.
	 *     @type string       $created_at           A oAuthClient created_at to only return that oAuthClient. Default empty.
	 *     @type array        $created_at__in       Array of oAuthClient created_at to include. Default empty.
	 *     @type array        $created_at__not_in   Array of oAuthClient created_at to exclude. Default empty.
	 * }
	 */
	public function __construct( $query = array() ) {
		parent::__construct( $query );
	}
}
