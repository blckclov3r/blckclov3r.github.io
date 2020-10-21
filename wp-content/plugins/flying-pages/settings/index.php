<?php
// Register settings menu
function flying_pages_register_settings_menu() {
    add_options_page('Flying Pages', 'Flying Pages', 'manage_options', 'flying-pages', 'flying_pages_settings_view');
}
add_action('admin_menu', 'flying_pages_register_settings_menu');

// Settings page
function flying_pages_settings_view() {
    include('settings.php');
    include('compatibility.php');
    include('faq.php');
    include('optimize-more.php');
    
    $active_tab = isset($_GET['tab']) ? $_GET['tab'] : "settings";
?>

<h2>Flying Pages by WP Speed Matters Settings</h2>
<h2 class="nav-tab-wrapper">
    <a href="?page=flying-pages&tab=settings" class="nav-tab <?php echo $active_tab == 'settings' ? 'nav-tab-active' : ''; ?>">Settings</a>
    <a href="?page=flying-pages&tab=compatibility" class="nav-tab <?php echo $active_tab == 'compatibility' ? 'nav-tab-active' : ''; ?>">Compatibility</a>
    <a href="?page=flying-pages&tab=faq" class="nav-tab <?php echo $active_tab == 'faq' ? 'nav-tab-active' : ''; ?>">FAQ</a>
    <a href="?page=flying-pages&tab=optimize-more" class="nav-tab <?php echo $active_tab == 'optimize-more' ? 'nav-tab-active' : ''; ?>">Optimize More!</a>
</h2>

<?php
    switch ($active_tab) {
        case 'settings':
            flying_pages_settings();
            break;
        case 'compatibility':
            flying_pages_compatibility();
            break;
        case 'faq':
            flying_pages_faq();
            break;
        case 'optimize-more':
            flying_pages_optimize_more();
            break;
        default:
            flying_pages_settings();
  
    }
}
?>