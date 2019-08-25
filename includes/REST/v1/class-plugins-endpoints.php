<?php
namespace ApproveMe\REST\v1;

/**
 * Implements REST routes and endpoints for Visits.
 *
 * @since 1.0
 *
 */
class Plugins extends Controller {

	/**
	 * Object type.
	 *
	 * @since 1.0
	 * @access public
	 * @var string
	 */
	public $object_type = 'approveme_plugins';

	/**
	 * Route base for visits.
	 *
	 * @since 1.0
	 * @access public
	 * @var string
	 */
	public $rest_base = 'plugins';

	protected $supported_plugins = array();

	/**
	 * Registers Visit routes.
	 *
	 * @since 1.0
	 * @access public
	 */
	public function register_routes() {

		// /plugins/
		register_rest_route( $this->namespace, '/' . $this->rest_base, array(
			array(
				'methods'             => \WP_REST_Server::READABLE,
				'callback'            => array( $this, 'get_items' ),
				'args'                => $this->get_collection_params(),
				'permission_callback' => function( $request ) {
					return true;
				}
			),
			'schema' => array( $this, 'get_public_item_schema' ),
		) );

		$supported_plugins = $this->get_supported_plugins();
		foreach ( $supported_plugins as $id => $plugin ) {
			if ( ! class_exists( $plugin['callback_class'] ) ) {
				continue;
			}

			$plugin_class = new $plugin['callback_class'];

			if ( ! empty( $plugin['capabilities'] ) ) {
				foreach ( $plugin['capabilities'] as $capability => $capability_data ) {
					register_rest_route( $this->namespace, '/' . $this->rest_base . '/' . $id . '/' . $capability . '', array(
						array(
							'methods'             => $capability_data['methods'],
							'callback'            => array( $plugin_class, $capability ),
							'args'                => $capability_data['fields'],
							'permission_callback' => function( $request ) {
								return true;
							}
						),
					) );
				}
			}
		}

	}

	/**
	 * Base endpoint to retrieve all installed plugins.
	 *
	 * @since 1.0
	 * @access public
	 *
	 * @param \WP_REST_Request $request Request arguments.
	 * @return \WP_REST_Response|\WP_Error Array of visits, otherwise WP_Error.
	 */
	public function get_items( $request ) {

		require_once trailingslashit( ABSPATH ) . 'wp-admin/includes/plugin.php';
		$output  = array();
		$plugins = $this->get_supported_plugins();

		if ( ! empty( $plugins ) ) {
			foreach ( $plugins as $id => $plugin ) {
				$output[ $id ] = array(
					'id'           => $id,
					'name'         => $plugin['name'],
					'slug'         => $plugin['slug'],
				);

				// Clean up the capabilities.
				$clean_caps = $plugin['capabilities'];
				foreach ( $clean_caps as $cap => $cap_data ) {
					foreach ( $cap_data['fields'] as $field => $field_data ) {
						unset( $field_data['sanitize_callback'], $field_data['validate_callback'] );

						$clean_caps[ $cap ]['fields'][ $field ] = $field_data;
					}
				}

				$output[ $id ]['capabilities'] = $clean_caps;
			}
		}

		return $this->response( $output );

	}

	/**
	 * Retrieves the collection parameters for visits.
	 *
	 * @since 1.0
	 * @access public
	 * @return array
	 */
	public function get_collection_params() {
		$params = parent::get_collection_params();

		$params['context']['default'] = 'view';

		/*
		 * Pass top-level get_visits() args as query vars:
		 * /visits/?referral_status=pending&order=desc
		 */
		$params['name'] = array(
			'description'       => __( 'The  name of the plugin.', 'approveme' ),
			'sanitize_callback' => 'sanitize_text_field',
			'validate_callback' => function( $param, $request, $key ) {
				return is_string( $param );
			},
		);

		$params['slug'] = array(
			'description'       => __( 'The plugin slug.', 'approveme' ),
			'sanitize_callback' => 'sanitize_text_field',
			'validate_callback' => function( $param, $request, $key ) {
				return is_string( $param );
			},
		);

		$params['version'] = array(
			'description'       => __( 'The current version of the plugin.', 'approveme' ),
			'sanitize_callback' => 'sanitize_text_field',
			'validate_callback' => function( $param, $request, $key ) {
				return is_string( $param );
			},
		);

		$params['capabilities'] = array(
			'description'       => __( 'Array of capabilities that ApproveMe can use to interact with the plugin.', 'approveme' ),
			'sanitize_callback' => function( $param, $request, $key ) {
				return array_map( 'sanitize_text_field', $param );
			},
			'validate_callback' => function( $param, $request, $key ) {
				return is_array( $param );
			}
		);

		$params['capabilities'] = array(
			'description'       => __( 'Array of capabilities that ApproveMe can use to interact with the plugin.', 'approveme' ),
			'validate_callback' => function( $param, $request, $key ) {
				return is_array( $param );
			}
		);

		return $params;
	}

	/**
	 * Retrieves the schema for a single visit, conforming to JSON Schema.
	 *
	 * @access public
	 * @since  1.0
	 *
	 * @return array Item schema data.
	 */
	public function get_item_schema() {

		$schema = array(
			'$schema'    => 'http://json-schema.org/schema#',
			'title'      => $this->get_object_type(),
			'type'       => 'object',
			// Base properties for supported plugins.
			'properties' => array(
				'name'     => array(
					'description' => __( 'The plugin name.', 'approveme' ),
					'type'        => 'string',
				),
				'slug' => array(
					'description' => __( 'The plugin slug.', 'approveme' ),
					'type'        => 'string',
				),
				'version'  => array(
					'description' => __( 'The current version of the plugin.', 'approveme' ),
					'type'        => 'string',
				),
				'capabilities'   => array(
					'description' => __( 'Array of capabilities that ApproveMe can use to interact with the plugin.', 'approveme' ),
					'type'        => 'array',
				),
				'required_fields' => array(
					'description' => __( 'An array of the capabilities, and the required fields for each capability.', 'approveme' ),
					'type'        => 'array',
				),
			),
		);

		return $this->add_additional_fields_schema( $schema );
	}

	public function get_supported_plugins() {
		return apply_filters( 'approveme_rest_supported_plugins', array() );
	}
}