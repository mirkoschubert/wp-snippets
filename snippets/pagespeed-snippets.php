<?php
/**
 * PAGESPEED Snippets
 * 
 * 1. Disabling self pingback
 * 2. Frontend Dashicons removal
 * 3. CSS & JS version query strings removal
 * 4. Shortlink removal
 * 5. Preloading (local) fonts
 */

if (!defined('ABSPATH')) die();


/**
 * 1. SPEED: Disable Self Pingback
 */
function child_disable_pingback( &$links ) {
  foreach ( $links as $l => $link ) {
    if (0 === strpos($link, get_option('home'))) unset($links[$l]);
  }
}
add_action('pre_ping', 'child_disable_pingback');


/**
 * 2. SPEED: Remove Dashicons on Frontend
 */
function child_dequeue_dashicons() {
  if (current_user_can( 'update_core' )) {
    return;
  }
  wp_deregister_style('dashicons');
}
add_action( 'wp_enqueue_scripts', 'child_dequeue_dashicons' );


/**
 * 3. SPEED: Remove CSS & JS version query strings
 */
function child_remove_query_strings( $src ) {
if( strpos( $src, '?ver=' ) )
 $src = remove_query_arg( 'ver', $src );
return $src;
}
add_filter( 'style_loader_src', 'child_remove_query_strings', 10, 2 );
add_filter( 'script_loader_src', 'child_remove_query_strings', 10, 2 );


/**
 * 4. SPEED: Remove Shortlink from Head
 */
function child_remove_shortlink() {
  remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);
}
add_action('init', 'child_remove_shortlink');


/**
 * 5. SPEED: Preload some of the biggest fonts for speed
 */
function child_preload_fonts() {
  // TODO: Copy "above the fold" fonts into the array!
  $fonts = array(
    '/wp-content/fonts/example-1.woff2',
    '/wp-content/fonts/example-1.woff2'
  )

  foreach ($fonts as $font) {
    $font_type = 'font/' . substr($font, strrpos($font, ".") + 1);
    $font_path = (substr($font, 0, 4 ) === "http") ? $font : get_site_url() . $font;
    echo '<link rel="preload" href="' . $font_path . '" as="font" type="' . $font_type . '" crossorigin />';
  }
}
add_action('wp_head', 'child_preload_fonts');
