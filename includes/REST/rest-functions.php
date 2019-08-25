<?php

/**
 * Register plugin support for the ApproveMe App.
 *
 * @param string $id
 * @param string $name
 * @param string $slug
 * @param string $class
 * @param array  $capabilities
 *
 * @return bool
 */
function approveme_add_plugin_support( $id = '', $name = '', $slug = '', $class = '', $capabilities = array() ) {
	if ( empty( $id ) || empty( $slug ) || empty( $capabilities ) || ! is_array( $capabilities ) ) {
		return false;
	}

	$plugin_data = array(
		'id'             => $id,
		'name'           => $name,
		'slug'           => $slug,
		'callback_class' => $class,
		'capabilities'   => $capabilities,
	);

	add_filter( 'approveme_rest_supported_plugins', function ( $supported_plugins ) use ( $plugin_data ) {
		// Only add the plugins once.
		if ( ! empty( $supported_plugins[ $plugin_data['id'] ] ) ) {
			return $supported_plugins;
		}

		$supported_plugins[ $plugin_data['id'] ] = $plugin_data;

		return $supported_plugins;
	} );

	return true;
}