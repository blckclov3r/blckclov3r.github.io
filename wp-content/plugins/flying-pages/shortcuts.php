<?php

// Add links in plugins list
function flying_pages_add_action_links($links) {
    $plugin_shortcuts = array(
        '<a href="'.admin_url('options-general.php?page=flying-pages').'">Settings</a>',
        '<a href="https://www.buymeacoffee.com/gijovarghese" target="_blank" style="color:#3db634;">Buy developer a coffee</a>'
    );
    return array_merge($links, $plugin_shortcuts);
}