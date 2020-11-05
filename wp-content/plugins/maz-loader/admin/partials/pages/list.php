<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
$loader_table = new MZLDR_List_Table( $this->loader_model );
$page         = isset( $_REQUEST['page'] ) ? sanitize_key( $_REQUEST['page'] ) : 'maz-loader-list';
?>
<div class="col-md-12">
	<div class="mazloader-items">
		<?php
		$loader_table->prepare_items();
		?>
		<div class="wrap">
			<h2>
				<?php _e( 'Loaders', 'maz-loader' ); ?>
				<a class="add-new-h2" href="<?php echo esc_attr( get_admin_url( get_current_blog_id(), 'admin.php?page=maz-loader' ) ); ?>"><?php _e( 'Add new', 'maz-loader' ); ?></a>
			</h2>
			<form id="mazloader-list-table" method="GET">
				<input type="hidden" name="page" value="<?php echo esc_attr( $page ); ?>"/>
				<?php $loader_table->display(); ?>
			</form>

		</div>
	</div>
</div>
