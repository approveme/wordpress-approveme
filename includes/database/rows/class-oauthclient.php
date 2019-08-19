<?php
/**
 * oAuthClient Database Object Class.
 *
 * @package     ApproveMe
 * @subpackage  Database\Rows
 * @copyright   Copyright (c) 2018, ApproveMe, LLC
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 */
namespace ApproveMe\Database\Rows;

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

use ApproveMe\Database\Row;

/**
 * oAuthClient database row class.
 *
 * This class exists solely to encapsulate database schema changes, to help
 * separate the needs of the application layer from the requirements of the
 * database layer.
 *
 * For example, if a database column is renamed or a return value needs to be
 * formatted differently, this class will make sure old values are still
 * supported and new values do not conflict.
 *
 * @since 1.0
 */
class oAuthClient extends Row {

}
