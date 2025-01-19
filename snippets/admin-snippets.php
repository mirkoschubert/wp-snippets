<?php
/**
 * ADMIN Snippets
 * 
 * 1. Altering admin menu entries
 * 2. Altering admin menu order
 */

if (!defined('ABSPATH')) die();


/**
 * 1. ADMIN: Alter admin menu entries
 */
function child_alter_admin_menu() {
  // TODO: Change to your liking!

  // Move Menu Page (without submenus) to Options submenu
  add_submenu_page('options-general.php', 'Limit Login Attempts', 'Limit Login Attempts', 'manage_options', 'limit-login-attempts');
  
  // Remove Menu Page
  remove_menu_page('limit-login-attempts');
}
add_action('admin_menu', 'child_alter_admin_menu', 20);


/**
 * 2. ADMIN Alter admin menu order
 */
function child_custom_menu_order($menu_ord) {
  // TODO: Change to your liking!

  if (!$menu_ord) return true;
  
  return array(
    'index.php', // Dashboard
    'separator1', // First separator
    'edit.php', // Posts
    'upload.php', // Media
    'edit.php?post_type=page', // Pages
    'edit-comments.php', // Comments
    'separator2', // Second separator
    'themes.php', // Appearance
    'plugins.php', // Plugins
    'users.php', // Users
    'tools.php', // Tools
    'options-general.php', // Settings
    'separator-last', // Last separator
    'wpseo_dashboard', // Yoast SEO
    'borlabs-cookie', // Borlabs
    'backwpup', // BackWPup
  );
}
add_filter('custom_menu_order', '__return_true', 20, 1);
add_filter('menu_order', 'child_custom_menu_order', 20, 1);
