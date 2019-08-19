<?php
/**
 * Component Functions
 *
 * This file includes functions for interacting with ApproveMe components. An ApproveMe
 * component is comprised of:
 *
 * - Database table/schema/query
 * - Object interface
 * - Optional meta-data
 *
 * Some examples of ApproveMe components are:
 *
 * - oAuthClients
 *
 * Add-ons and third party plugins are welcome to register their own component
 * in exactly the same way that ApproveMe does internally.
 *
 * @package     ApproveMe
 * @subpackage  Functions/Components
 * @since       3.0
*/

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Register a new ApproveMe component.
 *
 * @since 3.0
 *
 * @param string $name
 * @param array  $args
 */
function approveme_register_component( $name = '', $args = array() ) {

	// Sanitize the component name
	$name = sanitize_key( $name );

	// Bail if name or args are empty
	if ( empty( $name ) || empty( $args ) ) {
		return;
	}

	// Parse arguments
	$r = wp_parse_args( $args, array(
		'name'   => $name,
		'schema' => '\\ApproveMe\\Database\\Schema',
		'table'  => '\\ApproveMe\\Database\\Table',
		'query'  => '\\ApproveMe\\Database\\Query',
		'object' => '\\ApproveMe\\Database\\Row',
		'meta'   => false
	) );

	// Setup the component
	\ApproveMe\ApproveMe()->components[ $name ] = new ApproveMe\Component( $r );

	// Component registered
	do_action( 'approveme_registered_component', $name, $r, $args );
}

/**
 * Get an ApproveMe Component object
 *
 * @since 3.0
 * @param string $name
 *
 * @return mixed False if not exists, ApproveMe_Component if exists
 */
function approveme_get_component( $name = '' ) {
	$name = sanitize_key( $name );

	// Return component if exists, or false
	return isset( \ApproveMe\ApproveMe()->components[ $name ] )
		? \ApproveMe\ApproveMe()->components[ $name ]
		: false;
}

/**
 * Get an ApproveMe Component interface
 *
 * @since 3.0
 * @param string $component
 * @param string $interface
 *
 * @return mixed False if not exists, ApproveMeComponent interface if exists
 */
function approveme_get_component_interface( $component = '', $interface = '' ) {

	// Get component
	$c = approveme_get_component( $component );

	// Bail if no component
	if ( empty( $c ) ) {
		return $c;
	}

	// Return interface, or false if not exists
	return $c->get_interface( $interface );
}

/**
 * Setup all ApproveMe components
 *
 * @since 3.0
 */
function approveme_setup_components() {
	static $setup = false;

	// Never register components more than 1 time per request
	if ( false !== $setup ) {
		return;
	}

	// Register oAuthClient.
	approveme_register_component( 'oauthclient', array(
		'schema' => '\\ApproveMe\\Database\\Schema\\oAuthClients',
		'table'  => '\\ApproveMe\\Database\\Tables\\oAuthClients',
		'query'  => '\\ApproveMe\\Database\\Queries\\oAuthClient',
		'object' => '\\ApproveMe\\Database\\Rows\\oAuthClient',
		'meta'   => false,
	) );

	// Register oAuthAccessToken.
	approveme_register_component( 'oauthaccesstoken', array(
		'schema' => '\\ApproveMe\\Database\\Schema\\oAuthAccessTokens',
		'table'  => '\\ApproveMe\\Database\\Tables\\oAuthAccessTokens',
		'query'  => '\\ApproveMe\\Database\\Queries\\oAuthAccessToken',
		'object' => '\\ApproveMe\\Database\\Rows\\oAuthAccessToken',
		'meta'   => false,
	) );

	// Register Events.
	approveme_register_component( 'events', array(
		'schema' => '\\ApproveMe\\Database\\Schema\\Events',
		'table'  => '\\ApproveMe\\Database\\Tables\\Events',
		'query'  => '\\ApproveMe\\Database\\Queries\\Event',
		'object' => '\\ApproveMe\\Database\\Rows\\Event',
		'meta'   => false,
	) );

	// Set the locally static setup var.
	$setup = true;

	// Action to allow third party components to be setup.
	do_action( 'approveme_setup_components' );
}

/**
 * Install all component database tables
 *
 * This function installs all database tables used by all components (including
 * third-party and add-ons that use the Component API)
 *
 * This is used by unit tests and tools.
 *
 * @since 3.0
 */
function approveme_install_component_database_tables() {

	// Get the components
	$components = \ApproveMe\ApproveMe()->components;

	// Bail if no components setup yet
	if ( empty( $components ) ) {
		return;
	}

	// Drop all component tables
	foreach ( $components as $component ) {

		// Objects
		$object = $component->get_interface( 'table' );
		if ( $object instanceof \ApproveMe\Database\Table && ! $object->exists() ) {
			$object->install();
		}

		// Meta
		$meta = $component->get_interface( 'meta' );
		if ( $meta instanceof \ApproveMe\Database\Table && ! $meta->exists() ) {
			$meta->install();
		}
	}
}

/**
 * Uninstall all component database tables
 *
 * This function is destructive and disastrous, so do not call it directly
 * unless you fully intend to destroy all data (including third-party add-ons
 * that use the Component API)
 *
 * This is used by unit tests and tools.
 *
 * @since 3.0
 */
function approveme_uninstall_component_database_tables() {

	// Get the components
	$components = \ApproveMe\ApproveMe()->components;

	// Bail if no components setup yet
	if ( empty( $components ) ) {
		return;
	}

	// Drop all component tables
	foreach ( $components as $component ) {

		// Objects
		$object = $component->get_interface( 'table' );
		if ( $object instanceof \ApproveMe\Database\Table && $object->exists() ) {
			$object->uninstall();
		}

		// Meta
		$meta = $component->get_interface( 'meta' );
		if ( $meta instanceof \ApproveMe\Database\Table && $meta->exists() ) {
			$meta->uninstall();
		}
	}
}
