<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * MAZ Loader Model.
 *
 * @link       https://www.feataholic.com
 * @since      1.1.8 Free
 *
 * @package    MZLDR
 * @subpackage MZLDR/admin
 */

/**
 * MAZ Loader Model.
 *
 * @package    MZLDR
 * @subpackage MZLDR/admin
 * @author     Your Name <email@example.com>
 */
class MZLDR_Loader_Model {

	private $wpdb;

	public function __construct() {
		global $wpdb;

		$this->wpdb = $wpdb;
	}

	/**
	 * Saves the Loader into the db
	 *
	 * @param   array $payload
	 *
	 * @return  int    The ID of the Loader
	 */
	public function save( $payload ) {
		$user_id = get_current_user_id();
		$table   = $this->wpdb->prefix . 'mzldr_loaders';

		$loader_data = array(
			'data'        => $payload['fields'],
			'settings'    => $payload['loader_settings'],
			'appearance'  => $payload['loader_appearance'],
			'custom_code' => isset($payload['loader_custom_code']) ? $payload['loader_custom_code'] : '',
			'publish_settings' => isset($payload['loader_publish_settings']) ? $payload['loader_publish_settings'] : '',
		);

		$data = array(
			'user_id'   => $user_id,
			'name'      => sanitize_text_field( $payload['name'] ),
			'data'      => json_encode( $loader_data ),
			'published' => true,
		);

		$format = array( '%d', '%s', '%s', '%d' );

		$this->wpdb->insert( $table, $data, $format );

		$my_id = $this->wpdb->insert_id;

		return $my_id;
	}

	/**
	 * Updates the Loader
	 *
	 * @param   array $payload
	 *
	 * @return  int    The ID of the Loader
	 */
	public function update( $payload ) {
		$user_id = get_current_user_id();
		$table   = $this->wpdb->prefix . 'mzldr_loaders';

		$loader_data = array(
			'data'        => $payload['fields'],
			'settings'    => $payload['loader_settings'],
			'appearance'  => $payload['loader_appearance'],
			'custom_code' => isset($payload['loader_custom_code']) ? $payload['loader_custom_code'] : '',
			'publish_settings' => isset($payload['loader_publish_settings']) ? $payload['loader_publish_settings'] : '',
		);

		$data = array(
			'name'        => sanitize_text_field( $payload['name'] ),
			'data'        => json_encode( $loader_data ),
			'modified_at' => date( 'Y-m-d H:i:s' ),
		);

		$where_data = array(
			'user_id' => $user_id,
			'id'      => $payload['loader_id'],
		);

		$format = array( '%d', '%s', '%s', '%d' );

		$this->wpdb->update( $table, $data, $where_data );

		return $payload['loader_id'];
	}

	/**
	 * Return all Loaders
	 *
	 * @param   array $query_data
	 *
	 * @return  array
	 */
	public function getLoaders( $query_data = array() ) {
		$user_id = get_current_user_id();

		$table = $this->wpdb->prefix . 'mzldr_loaders';

		$impressions_table = $this->wpdb->prefix . 'mzldr_impressions';

		$query_data = implode( ' ', $query_data );

		$sql = 'SELECT
                    *,
					(
						SELECT count(i.id)
						FROM ' . $impressions_table . ' as i
						WHERE i.loader_id = l.id
					) as impressions
                FROM ' . $table . ' as l
                ' . $query_data;

		$loaders = $this->wpdb->get_results( $sql );

		return $loaders;
	}

	/**
	 * Return last Loader
	 *
	 * @return  array
	 */
	public function getLatestLoader() {
		$where = array(
			'where' => 'WHERE published = 1',
			'order' => 'ORDER BY id DESC',
			'limit' => 'LIMIT 1',
		);

		return $this->getLoaders( $where );
	}

	/**
	 * Deletes the loader
	 *
	 * @param int $id loader id
	 */
	public function delete( $id ) {
		$user_id = get_current_user_id();

		$this->wpdb->delete(
			"{$this->wpdb->prefix}mzldr_loaders",
			array(
				'id'      => $id,
				'user_id' => $user_id,
			),
			array(
				'%d',
				'%d',
			)
		);

		if ( $this->wpdb->last_error ) {
			return false;
		}

		return true;
	}

	/**
	 * Updates the publish status of the loader
	 *
	 * @param   int     $loader_id
	 * @param   boolean $new_status
	 *
	 * @return  boolean
	 */
	public function updatePublishStatus( $loader_id, $new_status ) {
		$user_id = get_current_user_id();

		$this->wpdb->update(
			$this->wpdb->prefix . 'mzldr_loaders',
			array(
				'published'   => $new_status,
				'modified_at' => date( 'Y-m-d H:i:s' ),
			),
			array(
				'id'      => $loader_id,
				'user_id' => $user_id,
			)
		);

		if ( $this->wpdb->last_error ) {
			return false;
		}

		return true;
	}

	/**
	 * Returns the number of loaders
	 *
	 * @return int
	 */
	public function getTotal() {
		return count( $this->getLoaders() );
	}

	/**
	 * Returns the id of the last loader added
	 *
	 * @return int
	 */
	public function getLastId() {
		$data = array(
			'ORDER' => 'ORDER BY created_at DESC',
			'LIMIT' => 'LIMIT 1',
		);

		$loader = $this->getLoaders( $data );

		if ( ! count( $loader ) ) {
			return 0;
		}

		return $loader[0]->id;
	}

	/**
	 * Parse the loader data and return an array with it's data
	 *
	 * @param   object $loader
	 *
	 * @return  object
	 */
	public function parseLoaderData( $loader ) {

		$data              = $loader->data;
		$data              = json_decode( $data );
		$data->user_id     = $loader->user_id;
		$data->name        = $loader->name;
		$data->published   = $loader->published;
		$data->created_at  = $loader->created_at;
		$data->modified_at = $loader->modified_at;

		return $data;
	}

	/**
	 * Checks whether the loader has an animation enabled
	 * 
	 * @param   object   $loader
	 * 
	 * @return  boolean
	 */
	public function loaderContainsAnimation($loader) {
		$data = $loader->data;
		$data = json_decode($data);

		if (!count((array) $data->data)) {
			return false;
		}

		foreach ($data->data as $d) {
			if (isset($d->animation) && $d->animation != 'none') {
				return true;
			}
		}
		return false;
	}
	/**
	 * Checks whether the loader contains a transition
	 * 
	 * @param   object   $loader
	 * 
	 * @return  boolean
	 */
	public function loaderContainsTransition($loader) {
		$data = $loader->data;
		$data = json_decode($data);

		if (isset($data->appearance->display_transition) &&
			$data->appearance->display_transition == 'on') {
			return true;
		}
		
		return false;
	}

}
