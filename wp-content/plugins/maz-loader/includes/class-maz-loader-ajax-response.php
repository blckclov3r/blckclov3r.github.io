<?php

/**
 * Handles the Ajax response
 *
 * @link       https://www.feataholic.com
 * @since      1.1.8 Free
 *
 * @package    MZLDR
 * @subpackage MZLDR/includes
 */

/**
 * Handles the Ajax response.
 *
 * @since      1.1.8 Free
 * @package    MZLDR
 * @subpackage MZLDR/includes
 * @author     Your Name <email@example.com>
 */
class MZLDR_Ajax_Response {

	/**
	 * @var  string  The Response Messsage
	 */
	private $message;

	/**
	 * @var  boolean  The Response error
	 */
	private $error;

	/**
	 * @var  array  Extra params to be thrown into the response
	 */
	private $params;

	/**
	 * Constructor
	 *
	 * @param   string   $message
	 * @param   boolean  $error
	 *
	 * @return  void
	 */
	function __construct( $message = '', $error = false ) {
		$this->message = $message;

		$this->error = $error;

		$this->params = array();
	}

	/**
	 * Sets the message
	 *
	 * @param   string
	 *
	 * @return  void
	 */
	public function setMessage( $message ) {
		$this->message = $message;
	}

	/**
	 * Returns the message
	 *
	 * @return  string
	 */
	public function getMessage() {
		return $this->message;
	}

	/**
	 * Sets the error
	 *
	 * @param   boolean
	 *
	 * @return  void
	 */
	public function setError( $error ) {
		$this->error = $error;
	}

	/**
	 * Returns the error
	 *
	 * @return  boolean
	 */
	public function getError() {
		return $this->error;
	}

	/**
	 * Appends parameters to the response
	 *
	 * @param   string
	 *
	 * @return  void
	 */
	public function addToResponse( $params ) {
		$this->params = $params;
	}

	/**
	 * Returns the response
	 *
	 * @return  array
	 */
	public function getResponse() {
		return array(
			'message' => $this->message,
			'error'   => $this->error,
			'params'  => $this->params,
		);
	}

	/**
	 * Returns the response
	 *
	 * @return  array
	 */
	public function send() {
		return $this->getResponse();
	}
}
