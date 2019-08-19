<?php
/**
 * oAuthAccessToken Query Class.
 *
 * @package     ApproveMe
 * @subpackage  Database\Queries
 * @copyright   Copyright (c) 2018, ApproveMe, LLC
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       3.0
 */
namespace ApproveMe\Database\Queries;

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

use ApproveMe\Database\Query;

/**
 * Class used for querying adjustments.
 *
 * @since 3.0
 *
 * @see \ApproveMe\Database\Queries\oAuthAccessToken::__construct() for accepted arguments.
 */
class oAuthAccessToken extends Query {

	/** Table Properties ******************************************************/

	/**
	 * Name of the database table to query.
	 *
	 * @since 3.0
	 * @access protected
	 * @var string
	 */
	protected $table_name = 'oauth_access_tokens';

	/**
	 * String used to alias the database table in MySQL statement.
	 *
	 * @since 3.0
	 * @access protected
	 * @var string
	 */
	protected $table_alias = 'oat';

	/**
	 * Name of class used to setup the database schema
	 *
	 * @since 3.0
	 * @access protected
	 * @var string
	 */
	protected $table_schema = '\\ApproveMe\\Database\\Schemas\\oAuthAccessTokens';

	/** Item ******************************************************************/

	/**
	 * Name for a single item
	 *
	 * @since 3.0
	 * @access protected
	 * @var string
	 */
	protected $item_name = 'oauth_access_token';

	/**
	 * Plural version for a group of items.
	 *
	 * @since 3.0
	 * @access protected
	 * @var string
	 */
	protected $item_name_plural = 'oauth_access_tokens';

	/**
	 * Callback function for turning IDs into objects
	 *
	 * @since 3.0
	 * @access protected
	 * @var mixed
	 */
	protected $item_shape = '\\ApproveMe\\Database\\Rows\\oAuthAccessToken';

	/** Cache *****************************************************************/

	/**
	 * Group to cache queries and queried items in.
	 *
	 * @since 3.0
	 * @access protected
	 * @var string
	 */
	protected $cache_group = 'oauth_access_tokens';

	/** Methods ***************************************************************/

	/**
	 * Sets up the adjustment query, based on the query vars passed.
	 *
	 * @since 3.0
	 * @access protected
	 *
	 * @param string|array $query {
	 *     Optional. Array or query string of adjustment query parameters. Default empty.
	 *
	 *     @type int          $id                   A oAuthAccessToken ID to only return that oAuthAccessToken. Default empty.
	 *     @type array        $id__in               Array of oAuthAccessToken IDs to include. Default empty.
	 *     @type array        $id__not_in           Array of oAuthAccessToken IDs to exclude. Default empty.
	 *     @type string       $client_id            A client_id to only return that oAuthAccessToken. Default empty.
	 *     @type array        $client_id_id__in     Array of oAuthAccessToken client_ids to include. Default empty.
	 *     @type array        $client_id__not_in    Array of oAuthAccessToken client_ids to exclude. Default empty.
	 *     @type string       $created_at           A oAuthAccessToken created_at to only return that oAuthAccessToken. Default empty.
	 *     @type array        $created_at__in       Array of oAuthAccessToken created_at to include. Default empty.
	 *     @type array        $created_at__not_in   Array of oAuthAccessToken created_at to exclude. Default empty.
	 *     @type string       $expires_at           A oAuthAccessToken expires_at to only return that oAuthAccessToken. Default empty.
	 *     @type array        $expires_at__in       Array of oAuthAccessToken expires_at to include. Default empty.
	 *     @type array        $expires_at__not_in   Array of oAuthAccessToken expires_at to exclude. Default empty.
	 * }
	 */
	public function __construct( $query = array() ) {
		parent::__construct( $query );
	}
}
