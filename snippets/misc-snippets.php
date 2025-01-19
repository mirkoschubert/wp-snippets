<?php
/**
 * MISCELLANEOUS Snippets
 * 
 * 1. Loading scripts and styles
 * 2. Loading text domain
 * 3. Custom Body Class for Child Theme for better CSS use
 * 4. Stopping core auto update email notifications
 * 5. Adding SVG & WebP support for file uploads
 */

if (!defined('ABSPATH')) die();


/**
 * 1. MISC: Load all scripts and styles
 */
function child_enqueue_scripts() {
  wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');
  wp_enqueue_style('child-fonts', get_stylesheet_directory_uri() . '/assets/css/fonts.css');
  wp_enqueue_script('child-scripts', get_stylesheet_directory_uri() . '/assets/js/main.js', array(), null, true);
}
add_action( 'wp_enqueue_scripts', 'child_enqueue_scripts' );


/**
 * 2. MISC: Load all language files
 */
function child_languages() {
  // TODO: Alter textdomain from style.css config!
  load_child_theme_textdomain('textdomain', get_stylesheet_directory() . '/languages');
}
add_action( 'after_setup_theme', 'child_languages');


/**
 * 3. MISC: Custom Body Class for Child Theme
 */
function child_body_class( $classes ) {
  $classes[] = 'child';
  return $classes;
}
add_action( 'body_class', 'child_body_class' );


/**
 * 4. MISC: Stops core auto update email notifications
 * @since WordPress 5.5
 */
function child_stop_update_mails($send, $type, $core_update, $result) {
  if (!empty($type) && $type == 'success' ) { return false; }
  return true;
}
add_filter('auto_core_update_send_email', 'child_stop_update_mails', 10, 4); // core
add_filter('auto_plugin_update_send_email', '__return_false'); // plugins
add_filter('auto_theme_update_send_email', '__return_false'); // themes


/**
 * 5. MISC: Adds SVG & WebP support for file uploads
 */
function child_supported_filetypes($filetypes) {
  $new = array();
  $new['svg'] = 'image/svg';
  $new['webp'] = 'image/webp';
  return array_merge($filetypes, $new);
}
add_action('upload_mimes', 'child_supported_filetypes');
