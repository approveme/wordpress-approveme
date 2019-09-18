<?php
namespace ApproveMe\REST\v1;

/**
 * Implements REST routes and endpoints for Events.
 *
 * @since 1.0
 *
 */
class Events extends Controller {

	/**
	 * Object type.
	 *
	 * @since 1.0
	 * @access public
	 * @var string
	 */
	public $object_type = 'approveme_events';

	/**
	 * Route base for events.
	 *
	 * @since 1.0
	 * @access public
	 * @var string
	 */
	public $rest_base = 'events';

	/**
	 * Registers Event routes.
	 *
	 * @since 1.0
	 * @access public
	 */
	public function register_routes() {

		// /events/add
		register_rest_route( $this->namespace, '/' . $this->rest_base . '/create', array(
			array(
				'methods'             => \WP_REST_Server::CREATABLE,
				'callback'            => array( $this, 'create_event' ),
				'args'                => $this->get_collection_params(),
				'permission_callback' => function( $request ) {
					return true;
				}
			),
			'schema' => array( $this, 'get_public_item_schema' ),
		) );

	}

	/**
	 * Base endpoint to create an event.
	 *
	 * @since 1.0
	 * @access public
	 *
	 * @param \WP_REST_Request $request Request arguments.
	 * @return \WP_REST_Response|\WP_Error Array of visits, otherwise WP_Error.
	 */
	public function create_event( $request ) {

		$collection_params = $this->get_collection_params();

		// Setup some defaults.
		$approveme_id = '';
		$content      = '';

		foreach ( $collection_params as $key => $param ) {
			$$key = isset( $_POST[ $key ] ) ? $param['sanitize_callback']( $_POST[ $key ] ) : false;
		}

		if ( ! empty( $approveme_id ) && ! empty( $content ) ) {
			$events = new \ApproveMe\Database\Queries\Event();
			$created = $events->add_item(
				array(
					'approveme_id' => $approveme_id,
					'content'      => $content,
					'status'       => 0,
					'created_at'   => current_time( 'mysql' ),
					'processed_at' => null,
				)
			);

			if ( false === $created ) {

				// See if the ID already exists.
				$existing_event = $events->get_item_by( 'approveme_id', $approveme_id );
				if ( ! empty( $existing_event ) ) {
					$output = new \WP_REST_Response(
						array(
							'message' => sprintf( __( 'Event %s already in the queue.', 'approveme' ), $approveme_id ),
							'success' => false,
						),
						409
					);
				} else {
					$output = new \WP_REST_Response(
						array(
							'message' => sprintf( __( 'An error occurred adding the event to the queue.', 'approveme' ), $approveme_id ),
							'success' => false,
						),
						400
					);
				}

			} else {
				$output = array(
					'success'  => true,
					'event_id' => $created,
				);
			}

		} else {
			$output = new \WP_REST_Response(
				array(
					'message' => __( 'Required fields missing.', 'approveme' ),
					'success' => false,
				),
				400
			);
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

		$params['approveme_id'] = array(
			'description'       => __( 'ID of the event in the ApproveMe App.', 'approveme' ),
			'sanitize_callback' => 'sanitize_text_field',
			'validate_callback' => function( $param, $request, $key ) {
				return is_string( $param );
			},
		);

		$params['content'] = array(
			'description'       => __( 'The event content.', 'approveme' ),
			'sanitize_callback' => 'sanitize_text_field',
			'validate_callback' => function( $param, $request, $key ) {
				return is_string( $param );
			},
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