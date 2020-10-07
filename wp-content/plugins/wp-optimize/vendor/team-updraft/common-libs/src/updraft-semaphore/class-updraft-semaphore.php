<?php

if (!defined('ABSPATH')) die('No direct access.');

/**
 * Semaphore Lock Management Library
 * Adapted from WP Social under the GPL 
 * Thanks to Alex King (https://github.com/crowdfavorite/wp-social)
 */

if (!class_exists('Updraft_Semaphore_2_2')) :

class Updraft_Semaphore_2_2 {

	/**
	 * Lock Broke
	 *
	 * @var boolean
	 */
	protected $lock_broke = false;

	/**
	 * Gained the lock successfully
	 *
	 * @var Integer
	 */
	protected $last_updated_lock = 0;

	/**
	 * Array of loggers
	 *
	 * @var array
	 */
	protected $loggers;

	/**
	 * Name of the lock variable
	 *
	 * @var String
	 */
	public $lock_name = 'lock';

	/**
	 * Initializes the semaphore object.
	 *
	 * @param string $semaphore Name of semaphore lock
	 * @return WP_Optimize_Semaphore
	 */
	public function __construct($semaphore = 'lock') {
		$this->lock_name = $semaphore;
		$this->ensure_semaphore_exists();
	}

	/**
	 * Alternate way to inititalise this for backward compat
	 *
	 * @static
	 * @return Updraft_Semaphore
	 */
	public static function factory() {
		return new self;
	}

	/**
	 * Call this method if (and *only* if) you previously gained the lock, and if you wish to retain it (so that it doesn't get broken by something else after the time in self::stuck_check() has expired)
	 *
	 * @param Boolean $not_more_often_than_every - require at least this number of seconds to have passed since the last update
	 *
	 * @return Boolean - whether any update took place (N.B. false can mean that one was not necessary)
	 */
	public function update_lock($not_more_often_than_every = 0) {
	
		if (!$this->last_updated_lock) return false;
		
		return $this->update_lock_time($not_more_often_than_every);
	
	}
	
	/**
	 * Ensures that the semaphore options exists before using them
	 */
	public function ensure_semaphore_exists() {
		// Make sure the options for semaphores exist
		global $wpdb;
		
		$results = $wpdb->get_results("
			SELECT option_id
				FROM $wpdb->options
				WHERE option_name IN ('updraft_locked_".$this->lock_name."', 'updraft_unlocked_".$this->lock_name."', 'updraft_last_lock_time_".$this->lock_name."', 'updraft_semaphore_".$this->lock_name."')
		");

		if (!is_array($results) || count($results) < 3) {
		
			if (is_array($results) && count($results) > 0) {
				$this->log(sprintf('Semaphore (%s, $s) in an impossible/broken state - fixing (%d)', $this->lock_name, $wpdb->options, count($results)));
			} else {
				$this->log(sprintf('Semaphore (%s, %s) being initialised', $this->lock_name, $wpdb->options));
			}
			
			$wpdb->query("
				DELETE FROM $wpdb->options
				WHERE option_name IN ('updraft_locked_".$this->lock_name."', 'updraft_unlocked_".$this->lock_name."', 'updraft_last_lock_time_".$this->lock_name."', 'updraft_semaphore_".$this->lock_name."')
			");
			
			$wpdb->query($wpdb->prepare("
				INSERT INTO $wpdb->options (option_name, option_value, autoload)
				VALUES
				('updraft_unlocked_".$this->lock_name."', '1', 'no'),
				('updraft_last_lock_time_".$this->lock_name."', '%s', 'no'),
				('updraft_semaphore_".$this->lock_name."', '0', 'no')
			", current_time('mysql', 1)));
		}
	}

	/**
	 * Attempts to start the lock. If the rename works, the lock is started.
	 *
	 * @return bool
	 */
	public function lock() {
		global $wpdb;

		// Attempt to set the lock
		$affected = $wpdb->query("
			UPDATE $wpdb->options
			   SET option_name = 'updraft_locked_".$this->lock_name."'
			 WHERE option_name = 'updraft_unlocked_".$this->lock_name."'
		");

		if ('0' == $affected && !$this->stuck_check()) {
			$this->log('Semaphore lock ('.$this->lock_name.', '.$wpdb->options.') failed (line '.__LINE__.')');
			$this->last_updated_lock = 0;
			return false;
		}

		// Check to see if all processes are complete
		$affected = $wpdb->query("
			UPDATE $wpdb->options
			   SET option_value = CAST(option_value AS UNSIGNED) + 1
			 WHERE option_name = 'updraft_semaphore_".$this->lock_name."'
			   AND option_value = '0'
		");
		if ('1' != $affected) {
			if (!$this->stuck_check()) {
				$this->log('Semaphore lock ('.$this->lock_name.', '.$wpdb->options.') failed (line '.__LINE__.')');
				$this->last_updated_lock = 0;
				return false;
			}

			// Reset the semaphore to 1
			$wpdb->query("
				UPDATE $wpdb->options
				   SET option_value = '1'
				 WHERE option_name = 'updraft_semaphore_".$this->lock_name."'
			");

			$this->log('Semaphore ('.$this->lock_name.', '.$wpdb->options.') reset to 1');
		}

		$this->update_lock_time();
	
		$this->log('Semaphore lock ('.$this->lock_name.') complete');

		return true;
	}

	/**
	 * Updates the lock to the current time (optionally, if it's not been updated in the last X seconds)
	 *
	 * @param Boolean $not_more_often_than_every - require at least this number of seconds to have passed since the last update
	 *
	 * @return Boolean - whether any update took place
	 */
	private function update_lock_time($not_more_often_than_every = 0) {
		
		if ($this->last_updated_lock && $this->last_updated_lock + $not_more_often_than_every > time()) return false;
		
		global $wpdb;
		
		// Set the lock time
		$wpdb->query($wpdb->prepare("
			UPDATE $wpdb->options
			   SET option_value = %s
			 WHERE option_name = 'updraft_last_lock_time_".$this->lock_name."'
		", current_time('mysql', 1)));
		$this->log('Set semaphore last lock ('.$this->lock_name.') time to '.current_time('mysql', 1));
		
		$this->last_updated_lock = time();
		
		return true;
	}

	/**
	 * Increment the semaphore.
	 *
	 * @param  array $filters
	 * @return Updraft_Semaphore
	 */
	public function increment(array $filters = array()) {
		global $wpdb;

		if (count($filters)) {
			// Loop through all of the filters and increment the semaphore
			foreach ($filters as $priority) {
				for ($i = 0, $j = count($priority); $i < $j; ++$i) {
					$this->increment();
				}
			}
		} else {
			$wpdb->query("
				UPDATE $wpdb->options
				   SET option_value = CAST(option_value AS UNSIGNED) + 1
				 WHERE option_name = 'updraft_semaphore_".$this->lock_name."'
			");
			$this->log('Incremented the semaphore ('.$this->lock_name.') by 1');
		}

		return $this;
	}

	/**
	 * Decrements the semaphore.
	 *
	 * @return void
	 */
	public function decrement() {
		global $wpdb;

		$wpdb->query("
			UPDATE $wpdb->options
			   SET option_value = CAST(option_value AS UNSIGNED) - 1
			 WHERE option_name = 'updraft_semaphore_".$this->lock_name."'
			   AND CAST(option_value AS UNSIGNED) > 0
		");
		$this->log('Decremented the semaphore ('.$this->lock_name.') by 1');
	}

	/**
	 * Unlocks the process.
	 *
	 * @return bool
	 */
	public function unlock() {
		global $wpdb;

		// Decrement for the master process.
		$this->decrement();

		$result = $wpdb->query("
			UPDATE $wpdb->options
			   SET option_name = 'updraft_unlocked_".$this->lock_name."'
			 WHERE option_name = 'updraft_locked_".$this->lock_name."'
		");

		if ('1' == $result) {
			$this->log('Semaphore ('.$this->lock_name.') unlocked');
			return true;
		}

		$this->log('Semaphore ('.$this->lock_name.', '.$wpdb->options.') still locked ('.$result.')');
		return false;
	}

	/**
	 * Check if semaphore is currently locked.
	 *
	 * @return bool
	 */
	public function is_locked() {
		global $wpdb;

		$result = $wpdb->get_results("
			SELECT option_name FROM $wpdb->options
			 WHERE option_name = 'updraft_locked_".$this->lock_name."'
		");

		return is_array($result) && count($result);
	}

	/**
	 * Attempts to jiggle the stuck lock loose.
	 *
	 * @return bool
	 */
	private function stuck_check() {
		global $wpdb;

		// Check to see if we already broke the lock.
		if ($this->lock_broke) {
			return true;
		}

		$current_time = current_time('mysql', 1);
		$timeout = gmdate('Y-m-d H:i:s', time()-(defined('UPDRAFT_SEMAPHORE_LOCK_WAIT') ? UPDRAFT_SEMAPHORE_LOCK_WAIT : 60));

		$affected = $wpdb->query($wpdb->prepare("
			UPDATE $wpdb->options
			   SET option_value = %s
			 WHERE option_name = 'updraft_last_lock_time_".$this->lock_name."'
			   AND option_value <= %s
		", $current_time, $timeout));

		if ('1' == $affected) {
			$this->log('Semaphore ('.$this->lock_name.', '.$wpdb->options.') was stuck, set lock time to '.$current_time);
			$this->lock_broke = true;
			return true;
		}

		return false;
	}

	/**
	 * Cleans up the DB of any residual data
	 */
	public function delete() {

		global $wpdb;

		$affected_options = $this->get_used_options();

		foreach ($affected_options as $option) {
			delete_option($option);
		}

		$this->log('Semaphore ('.$this->lock_name.', '.$wpdb->options.') was successfully cleaned up');
	}

	/**
	 * Cleans up the DB of any residual data
	 */
	private function get_used_options() {

		$options = array(
			"updraft_semaphore_{$this->lock_name}",
			"updraft_last_lock_time_{$this->lock_name}",
			"updraft_locked_{$this->lock_name}",
			"updraft_unlocked_{$this->lock_name}",
		);

		return $options;
	}

	/**
	 * Sets the logger for this instance.
	 *
	 * @param array $loggers - the loggers for this task
	 */
	public function set_loggers($loggers) {
		if (is_array($loggers)) {
			foreach ($loggers as $logger) {
				$this->add_logger($logger);
			}
		}
	}

	/**
	 * Add a logger to loggers list
	 *
	 * @param Object $logger - a logger for the instance
	 */
	public function add_logger($logger) {
		$this->_loggers[] = $logger;
	}

	/**
	 * Return list of loggers
	 *
	 * @return array
	 */
	public function get_loggers() {
		return $this->_loggers;
	}

	/**
	 * Captures and logs any interesting messages
	 *
	 * @param String $message    - the error message
	 * @param String $error_type - the error type
	 */
	public function log($message, $error_type = 'info') {

		if (isset($this->_loggers)) {
			foreach ($this->_loggers as $logger) {
				$logger->log($error_type, $message);
			}
		}
	}
} // End UpdraftPlus_Semaphore
endif;
