<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
$title_contenteditable = false;
$page_title            = get_admin_page_title();

switch ( $this->page ) {
	case 'maz-loader-admin':
		$this->page = 'dashboard';
		break;
	case 'maz-loader':
		if ( is_object( $this->loader ) && count( (array) $this->loader ) ) {
			$page_title = $this->loader->name;
		} else {
			$next_loader_id = $this->loader_model->getLastId() + 1;
			$page_title     = 'MAZ Loader #' . $next_loader_id;
		}
		$this->page            = 'new';
		$title_contenteditable = true;
		break;
	case 'maz-loader-list':
		$this->page = 'list';
		break;
	case 'maz-loader-settings':
		$this->page = 'settings';
		break;
	default:
		$this->page = 'dashboard';
		break;
}
?>
<?php require_once plugin_dir_path( dirname( __FILE__ ) ) . 'partials/static/header.php'; ?>
<?php require_once plugin_dir_path( dirname( __FILE__ ) ) . 'partials/pages/' . esc_attr( $this->page ) . '.php'; ?>
<?php
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'partials/static/footer.php';
