<?php
namespace ApproveMe;

trait Logger {

	/**
	 * Really long running process
	 *
	 * @return int
	 */
	public function really_long_running_task() {
		return sleep( 5 );
	}

	/**
	 * Log
	 *
	 * @param string $message
	 */
	public function log( $message ) {
		error_log( $message );
	}

	/**
	 * Get lorem
	 *
	 * @param string $name
	 *
	 * @return string
	 */
	protected function get_message( $name ) {
		return $name;
	}

}