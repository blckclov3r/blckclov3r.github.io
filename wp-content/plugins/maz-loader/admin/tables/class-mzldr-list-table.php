<?php
/**
 * Loaders List Table
 *
 * @link   https://www.feataholic.com
 * @author Feataholic
 * @since  1.1.8 Free
 *
 * @package MZLDR/Admin/Tables
 */
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

// Core WP List Table Class.
if ( ! class_exists( 'WP_List_Table' ) ) {
	include_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

/**
 * Renders a table to display all Loaders
 */
class MZLDR_List_Table extends WP_List_Table {


	/**
	 * The Loader Model
	 *
	 * @var class
	 */
	private $loader_model;

	/**
	 * Loaders to render per page
	 *
	 * @var integer
	 */
	private $per_page = 20;

	/**
	 * [REQUIRED] You must declare constructor and give some basic params
	 */
	public function __construct() {
		global $status, $page;

		$this->loader_model = new MZLDR_Loader_Model();

		parent::__construct(
			array(
				'singular' => 'loader',
				'plural'   => 'loaders',
				'ajax'     => false,
			)
		);
	}

	/**
	 * [REQUIRED] this is a default column renderer
	 *
	 * @param   row    $item
	 * @param   string $column_name
	 * @return  HTML
	 */
	public function column_default( $item, $column_name ) {
		return $item->$column_name;
	}

	/**
	 * Adds a link to the ID column
	 *
	 * @param  row $item
	 *
	 * @return HTML
	 */
	public function column_id( $item ) {
		?>
		<a href="?page=maz-loader&action=edit&loader_id=<?php echo esc_attr( $item->id ); ?>"><?php echo esc_html( $item->id ); ?></a>
		<?php
	}

	/**
	 * [OPTIONAL] this is example, how to render column with actions,
	 * when you hover row "Edit | Delete" links showed
	 *
	 * @param  $item - row (key, value array)
	 * @return HTML
	 */
	public function column_name( $item ) {
		return sprintf(
			'%s',
			sprintf( '<a href="?page=maz-loader&action=edit&loader_id=%s">%s</a>', esc_attr( $item->id ), esc_attr( $item->name ) )
		);
	}

	/**
	 * Publish column
	 *
	 * @param   row $item
	 *
	 * @return  void
	 */
	public function column_published( $item ) {
		$status     = (bool) $item->published;
		$new_status = $status ? 0 : 1;
		$class      = ( $status ) ? ' is-checked' : '';

		?>
		<a href="?page=maz-loader-list&action=update-publish-status&new_status=<?php echo esc_attr( $new_status ); ?>&loader_id=<?php echo esc_attr( $item->id ); ?>" class="mzldr-toggle-switch-link">
			<span class="mzldr-toggle-switch-outer<?php echo esc_attr( $class ); ?>">
				<label for="mzldr-loader-toggle-switch-<?php echo esc_attr( $item->id ); ?>"></label>
			</span>
		</a>
		<?php

	}

	/**
	 * Impressions column
	 *
	 * @param   row $item
	 *
	 * @return  void
	 */
	public function column_impressions( $item ) {
		?>
		<div class="mzldr-label color padding" title="<?php _e( 'Total number of people who have seen this loader', 'maz-loader' ); ?>"><?php echo esc_attr( $item->impressions ); ?></div>
		<?php
	}

	/**
	 * Modified at column
	 *
	 * @return  row  $item
	 *
	 * @return  void
	 */
	public function column_modified_at( $item ) {
		echo ! empty( $item->modified_at ) ? esc_attr( $item->modified_at ) : '-';
	}

	/**
	 * [REQUIRED] this is how checkbox column renders
	 *
	 * @param  $item - row (key, value array)
	 * @return HTML
	 */
	public function column_cb( $item ) {
		return sprintf(
			'<input type="checkbox" name="id[]" value="%s" />',
			esc_attr( $item->id )
		);
	}

	/**
	 * Actions Column
	 *
	 * @param   row $item
	 *
	 * @return  HTML
	 */
	public function column_actions( $item ) {
		// links going to /admin.php?page=[your_plugin_page][&other_params]
		// notice how we used $_REQUEST['page'], so action will be done on curren page
		// also notice how we use $this->_args['singular'] so in this example it will
		// be something like &person=2
		$actions = array(
			'shortcode' => sprintf( '<a href="#" title="' . __( 'Get the shortcode for the loader', 'maz-loader' ) . '" data-loader-id="' . esc_attr( $item->id ) . '" class="mzldr-button x-small mzldr-copy-loader-shortcode-btn">%s</a>&nbsp;&nbsp;', __( '<span class="dashicons dashicons-admin-page"></span>', 'maz-loader' ) ),
			'edit'      => sprintf( '<a href="?page=maz-loader&action=edit&loader_id=%s" title="' . __( 'Edit Loader', 'maz-loader' ) . '" class="mzldr-button x-small">%s</a>&nbsp;&nbsp;', esc_attr( $item->id ), __( '<span class="dashicons dashicons-edit"></span>', 'maz-loader' ) ),
			'delete'    => sprintf( '<a href="?page=maz-loader&action=delete&loader_id=%s" title="' . __( 'Delete Loader', 'maz-loader' ) . '" class="mzldr-button x-small mzldr-confirm-popup" data-mzldr-confirm="yes" data-mzldr-confirm-message="' . __( 'Are you sure you want to delete this loader? You can\'t undo this.', 'maz-loader' ) . '">%s</a>', esc_attr( $item->id ), __( '<span class="dashicons dashicons-dismiss"></span>', 'maz-loader' ) ),
		);
		return sprintf( $actions['shortcode'] . $actions['edit'] . $actions['delete'] );
	}

	/**
	 * [REQUIRED] This method return columns to display in table
	 * you can skip columns that you do not want to show
	 * like content, or description
	 *
	 * @return array
	 */
	public function get_columns() {
		return $columns = array(
			'cb'          => '<input type="checkbox" />', // Render a checkbox instead of text.
			'id'          => __( 'ID' ),
			'name'        => __( 'Name' ),
			'published'   => __( 'Published' ),
			'impressions' => __( 'Impressions' ),
			'created_at'  => __( 'Created' ),
			'modified_at' => __( 'Modified' ),
			'actions'     => __( 'Actions' ),
		);
	}

	/**
	 * [OPTIONAL] This method return columns that may be used to sort table
	 * all strings in array - is column names
	 * notice that true on name column means that its default sort
	 *
	 * @return array
	 */
	public function get_sortable_columns() {
		return $sortable = array(
			'id'          => array( 'id', false ),
			'name'        => array( 'name', false ),
			'published'   => array( 'published', false ),
			'impressions' => array( 'impressions', false ),
			'created_at'  => array( 'created_at', true ),
			'modified_at' => array( 'modified_at', false ),
		);
	}

	/**
	 * [OPTIONAL] Return array of bult actions if has any
	 *
	 * @return array
	 */
	public function get_bulk_actions() {
		$actions = array(
			'delete' => 'Delete',
		);
		return $actions;
	}

	/**
	 * [OPTIONAL] This method processes bulk actions
	 * it can be outside of class
	 * it can not use wp_redirect coz there is output already
	 * in this example we are processing delete action
	 * message about successful deletion will be shown on page in next part
	 */
	public function process_bulk_action() {
		global $wpdb;
		$table_name = $wpdb->prefix . 'mzldr_loaders'; // do not forget about tables prefix.
		if ( 'delete' === $this->current_action() ) {
			$ids = isset( $_REQUEST['id'] ) ? MZLDR_Helper::sanitize_int_array( $_REQUEST['id'] ) : array();
			if ( is_array( $ids ) ) {
				$ids = implode( ',', $ids );
			}
			if ( ! empty( $ids ) ) {
				$wpdb->query( "DELETE FROM $table_name WHERE id IN($ids)" );
			}
		}
	}

	/**
	 * [REQUIRED] This is the most important method
	 *
	 * It will get rows from database and prepare them to be showed in table
	 */
	public function prepare_items() {
		$total_items = $this->loader_model->getTotal();

		$per_page = $this->per_page; // constant, how much records will be shown per page.
		$columns  = $this->get_columns();
		$hidden   = array();
		$sortable = $this->get_sortable_columns();
		// here we configure table headers, defined in our methods.
		$this->_column_headers = array( $columns, $hidden, $sortable );
		// [OPTIONAL] process bulk action if any.
		$this->process_bulk_action();
		// will be used in pagination settings.
		// prepare query params, as usual current page, order by and order direction.
		$data = array();

		$allowed_orders = array( 'id', 'name', 'published', 'impressions', 'created_at', 'modified_at' );

		$orderby = ( isset( $_REQUEST['orderby'] ) && in_array( $_REQUEST['orderby'], array_keys( $this->get_sortable_columns() ) ) ) ? sanitize_key( $_REQUEST['orderby'] ) : 'created_at';

		if ( ! in_array( $orderby, $allowed_orders ) ) {
			$orderby = 'created_at';
		}

		$order            = ( isset( $_REQUEST['order'] ) && in_array( $_REQUEST['order'], array( 'asc', 'desc' ), true ) ) ? sanitize_key( $_REQUEST['order'] ) : 'desc';
		$data['order_by'] = 'ORDER BY ' . esc_attr( $orderby ) . ' ' . esc_attr( $order );

		$data['limit'] = 'LIMIT ' . esc_attr( $per_page );

		$paged          = isset( $_REQUEST['paged'] ) ? max( 0, intval( $_REQUEST['paged'] - 1 ) * $per_page ) : 0;
		$data['offset'] = 'OFFSET ' . esc_attr( $paged );

		$this->items = $this->loader_model->getLoaders( $data );

		// [REQUIRED] configure pagination.
		$this->set_pagination_args(
			array(
				'total_items' => $total_items, // total items defined above.
				'per_page'    => $per_page, // per page constant defined at top of method.
				'total_pages' => ceil( $total_items / $per_page ), // calculate pages count.
			)
		);
	}

}
