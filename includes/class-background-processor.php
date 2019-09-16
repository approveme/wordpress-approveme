<?php
namespace ApproveMe;
use WP_Background_Process;

class Background_Process extends WP_Background_Process {

	use Logger;

	/**
	 * @var string
	 */
	protected $action = 'approveme_process';

	/**
	 * Start time of current process.
	 *
	 * (default value: 0)
	 *
	 * @var int
	 * @access protected
	 */
	protected $start_time = 0;

	/**
	 * Cron_hook_identifier
	 *
	 * @var mixed
	 * @access protected
	 */
	protected $cron_hook_identifier = 'approveme_bp_cron';

	/**
	 * Cron_interval_identifier
	 *
	 * @var mixed
	 * @access protected
	 */
	protected $cron_interval_identifier = 'approveme_bp_cron_interval';

	/**
	 * Initiate new background process
	 */
	public function __construct() {
		parent::__construct();

		add_action( $this->cron_hook_identifier, array( $this, 'handle_cron_healthcheck' ) );
		add_filter( 'cron_schedules', array( $this, 'schedule_cron_healthcheck' ) );
	}

	/**
	 * Task
	 *
	 * Override this method to perform any actions required on each
	 * queue item. Return the modified item for further processing
	 * in the next pass through. Or, return false to remove the
	 * item from the queue.
	 *
	 * @param mixed $item Queue item to iterate over
	 *
	 * @return mixed
	 */
	protected function task( $item ) {
		$message = $this->get_message( $item->id . ' ' . $item->approveme_id . ': ' . $item->content );

		$this->really_long_running_task();
		$this->log( $message );

		return true;
	}

	/**
	 * Complete
	 *
	 * Override if applicable, but ensure that the below actions are
	 * performed, or, call parent::complete().
	 */
	protected function complete() {
		parent::complete();

		// Show notice to user or perform some other arbitrary task...
	}

	/**
	 * Is queue empty
	 *
	 * @return bool
	 */
	protected function is_queue_empty() {
		$events_db = new \ApproveMe\Database\Queries\Event();
		$unprocessed_count = $events_db->query( array( 'count' => true, 'status__in' => array(0) ) );

		return ( $unprocessed_count > 0 ) ? false : true;
	}

	/**
	 * Get batch
	 *
	 * @return stdClass Return the first batch from the queue
	 */
	protected function get_batch() {
		$events_db = new \ApproveMe\Database\Queries\Event();
		$events_for_batch = $events_db->query( array( 'number' => 5, 'status__in' => array(0), 'orderby' => 'id', 'order' => 'ASC' ) );

		$batch = array();
		foreach ( $events_for_batch as $event ) {
			$batch[] = $event;
		}

		return $batch;
	}

	/**
	 * Handle
	 *
	 * Pass each queue item to the task handler, while remaining
	 * within server memory and time limit constraints.
	 */
	protected function handle() {
		$this->lock_process();

		do {
			$batch = $this->get_batch();

			foreach ( $batch as $job ) {
				$task = $this->task( $job );

				if ( true === $task ) {
					$this->update( $job->id, $job );
				}

				if ( $this->time_exceeded() || $this->memory_exceeded() ) {
					// Batch limits reached.
					break;
				}
			}



		} while ( ! $this->time_exceeded() && ! $this->memory_exceeded() && ! $this->is_queue_empty() );

		$this->unlock_process();

		// Start next batch or complete process.
		if ( ! $this->is_queue_empty() ) {
			$this->dispatch();
		} else {
			$this->complete();
		}

		wp_die();
	}

	/**
	 * Update queue
	 *
	 * @param string $key Key.
	 * @param array  $data Data.
	 *
	 * @return $this
	 */
	public function update( $key, $data ) {
		if ( ! empty( $data ) ) {
			$events_db = new \ApproveMe\Database\Queries\Event();
			$events_db->update_item( $key, array( 'status' => 1, 'processed_at' => current_time( 'mysql' ) ) );
		}

		return $this;
	}

	/**
	 * Delete queue
	 *
	 * @param string $key Key.
	 *
	 * @return $this
	 */
	public function delete( $key ) {
		return $this;
	}

}