<?php
namespace ApproveMe\REST\v1;

/**
 * Implements REST routes and endpoints for Connecting.
 *
 * @since 1.0
 *
 */
class Connect extends Controller {

	/**
	 * Object type.
	 *
	 * @since 1.0
	 * @access public
	 * @var string
	 */
	public $object_type = 'approveme_connect';

	/**
	 * Route base for events.
	 *
	 * @since 1.0
	 * @access public
	 * @var string
	 */
	public $rest_base = 'connect';

	/**
	 * Registers Event routes.
	 *
	 * @since 1.0
	 * @access public
	 */
	public function register_routes() {

		// /connect/oauth
		register_rest_route( $this->namespace, '/' . $this->rest_base . '/oauth', array(
			array(
				'methods'             => \WP_REST_Server::CREATABLE,
				'callback'            => array( $this, 'process_oauth' ),
				'args'                => $this->get_collection_params(),
				'permission_callback' => function( $request ) {
					return true;
				}
			),
			'schema' => array( $this, 'get_public_item_schema' ),
		) );

		// /connect/callback
		register_rest_route( $this->namespace, '/' . $this->rest_base . '/callback', array(
			array(
				'methods'             => \WP_REST_Server::READABLE,
				'callback'            => array( $this, 'process_callback' ),
				'args'                => $this->get_collection_params(),
				'permission_callback' => function( $request ) {
					return true;
				}
			),
			'schema' => array( $this, 'get_public_item_schema' ),
		) );

	}

	/**
	 * Base endpoint to process oauth_callback
	 *
	 * @since 1.0
	 * @access public
	 *
	 * @param \WP_REST_Request $request Request arguments.
	 * @return \WP_REST_Response|\WP_Error Array of visits, otherwise WP_Error.
	 */
	public function process_oauth( $request ) {
		$collection_params = $this->get_collection_params();

		// Setup some defaults.
		$client_secret = '';
		$client_id = '';
		$name = '';
		$redirect = '';
		$user_id = 0;
		$default_account_id = '';

		$reqeust_params = $request->get_json_params();
		foreach ( $collection_params as $key => $param ) {
			$$key = isset( $reqeust_params[ $key ] ) ? $param['sanitize_callback']( $reqeust_params[ $key ] ) : false;
		}

		if ( empty( $user_id ) ) {
			$output = new \WP_REST_Response(
				array(
					'message' => 'Missing user ID',
					'success' => false,
				),
				400
			);
		} else if ( empty( $client_id ) ) {
			$output = new \WP_REST_Response(
				array(
					'message' => 'Missing client ID',
					'success' => false,
				),
				400
			);
		} else if ( empty( $client_secret ) ) {
			$output = new \WP_REST_Response(
				array(
					'message' => 'Missing client secret',
					'success' => false,
				),
				400
			);
		} else if ( empty( $default_account_id ) ) {
			$output = new \WP_REST_Response(
				array(
					'message' => 'Missing default account id',
					'success' => false,
				),
				400
			);
		} else {
			$clients = new \ApproveMe\Database\Queries\oAuthClient();
			$created = $clients->add_item(
				array(
					'name'               => $name,
					'user_id'            => $user_id,
					'client_id'          => $client_id,
					'secret'             => $client_secret,
					'created_at'         => current_time( 'mysql' ),
					'default_account_id' => $default_account_id,
				)
			);

			if ( false === $created ) {

				$output = new \WP_REST_Response(
					array(
						'message' => sprintf( __( 'An error occurred adding the oauth client.', 'approveme' ), $approveme_id ),
						'success' => false,
					),
					400
				);

			} else {
				$output = array(
					'success'  => true,
				);
			}

		}

		return $this->response( $output );
	}

	public function process_callback( $request ) {
		if ( ! empty( $_GET['code'] ) ) {
			$code = $_GET['code'];
			$grant_type = 'authorization_code';

			$clients = new \ApproveMe\Database\Queries\oAuthClient();
			$client  = $clients->query( array( 'orderby' => 'id', 'order' => 'DESC', 'number' => 1 ) );
			$client  = $client[0];

			$client_id = $client->client_id;
			$client_secret = $client->secret;
			$redirect_url = get_rest_url( null, 'approveme/v1/connect/callback' );

			$request = wp_remote_post(
				APPROVME_APP_URL . 'oauth/token',
				array(
					'body' => array(
						'grant_type'    => $grant_type,
						'code'          => $code,
						'client_id'     => $client_id,
						'client_secret' => $client_secret,
						'redirect_uri'  => $redirect_url,
					)
				)
			);

			if ( ! is_wp_error( $request ) ) {
				$response = wp_remote_retrieve_body( $request );
				$this->response( $response );
			}
		}
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

		$params['client_id'] = array(
			'description'       => __( 'The client id', 'approveme' ),
			'sanitize_callback' => 'absint',
			'validate_callback' => function( $param, $request, $key ) {
				return is_numeric( $param );
			},
			'required' => true,
		);

		$params['client_secret'] = array(
			'description'       => __( 'The client id', 'approveme' ),
			'sanitize_callback' => 'sanitize_text_field',
			'validate_callback' => function( $param, $request, $key ) {
				return is_string( $param );
			},
			'required' => true,
		);

		$params['name'] = array(
			'description'       => __( 'The client id', 'approveme' ),
			'sanitize_callback' => 'sanitize_text_field',
			'validate_callback' => function( $param, $request, $key ) {
				return is_string( $param );
			},
			'required' => true,
		);

		$params['redirect'] = array(
			'description'       => __( 'The client id', 'approveme' ),
			'sanitize_callback' => 'sanitize_text_field',
			'validate_callback' => function( $param, $request, $key ) {
				return is_string( $param );
			},
			'required' => true,
		);

		$params['user_id'] = array(
			'description'       => __( 'The client id', 'approveme' ),
			'sanitize_callback' => 'absint',
			'validate_callback' => function( $param, $request, $key ) {
				return is_numeric( $param );
			},
			'required' => true,
		);

		$params['default_account_id'] = array(
			'description'       => __( 'The client id', 'approveme' ),
			'sanitize_callback' => 'sanitize_text_field',
			'validate_callback' => function( $param, $request, $key ) {
				return is_string( $param );
			},
			'required' => true,
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
				'approveme_id'     => array(
					'description' => __( 'The ID within the ApproveMe app.', 'approveme' ),
					'type'        => 'string',
				),
				'content' => array(
					'description' => __( 'The event content.', 'approveme' ),
					'type'        => 'string',
				),
			),
		);

		return $this->add_additional_fields_schema( $schema );
	}

}