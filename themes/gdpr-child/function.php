<?php

/**
 * Implement Child Theme Scripts and overwrite Google Fonts
 */
function gdpr_child_scripts() {
  wp_enqueue_style('gdpr-style', get_template_directory_uri() . '/style.css', array(), null); // original style.css
  wp_enqueue_style( 'gdpr-child-style', get_stylesheet_directory_uri() . '/style.css', 'gdpr-style', null ); // child style.css
  wp_enqueue_style('gdpr-fonts', get_stylesheet_directory_uri() . '/css/fonts.css'); // Localize Google Fonts
}
add_action( 'wp_enqueue_scripts' , 'gdpr' );


/**
 * Custom Body Class for Child Theme
 */
function gdpr_child_body_class( $classes ) {
  $classes[] = 'child';
  return $classes;
}
add_action( 'body_class', 'gdpr_child_body_class' );


// INFO: Comments (external links & comments IP)

/**
 * Makes every comment and comment author link truely external (except 'respond')
 */
function gdpr_child_external_comment_links( $content ){
  return str_replace( "<a ", "<a target='_blank' ", $content );
}
add_filter( "comment_text", "gdpr_child_external_comment_links" );
add_filter( "get_comment_author_link", "gdpr_child_external_comment_links" );


/**
 * Removes IP addresses from comments (old entries have to be deleted by hand)
 */
function gdpr_child_remove_comments_ip( $comment_author_ip ) {
  return '';
}
add_filter( 'pre_comment_user_ip', 'gdpr_child_remove_comments_ip' );


// INFO: Disable oEmbeds

/**
 * Disable oEmbeds
 */
function gdpr_child_disable_embeds() {
  remove_action( 'rest_api_init', 'wp_oembed_register_route' ); // JSON API
  add_filter( 'embed_oembed_discover', '__return_false' ); // Auto Discover
  remove_filter( 'oembed_dataparse', 'wp_filter_oembed_result', 10 ); // Results
  remove_action( 'wp_head', 'wp_oembed_add_discovery_links' ); // Discovery Links
  remove_action( 'wp_head', 'wp_oembed_add_host_js' ); // Frontend JS
  add_filter( 'tiny_mce_plugins', 'gdpr_child_disable_embeds_tinymce_plugin' ); // TinyMCE
  add_filter( 'rewrite_rules_array', 'gdpr_child_disable_embeds_rewrites' ); // Rerite Rules
  remove_filter( 'pre_oembed_result', 'wp_filter_pre_oembed_result', 10 ); // oEmbeds Preloader
}
add_action( 'init', 'gdpr_child_disable_embeds', 9999 );


/**
 * Remove oEmbed TinyMCE Plugin
 */
function gdpr_child_disable_embeds_tinymce_plugin( $plugins ) {
  return array_diff( $plugins, array('wpembed') );
}


/**
 * Disable oEmbeds rewrite rules
 */
function gdpr_child_disable_embeds_rewrites( $rules ) {
  foreach( $rules as $rule => $rewrite ) {
    if (false !== strpos($rewrite, 'embed=true')) {
      unset($rules[$rule]);
    }
  }
  return $rules;
}


// INFO: Disable Emojis 

/**
 * Disable the emoji's
 */
function gdpr_child_disable_emojis() {
  remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
  remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
  remove_action( 'wp_print_styles', 'print_emoji_styles' );
  remove_action( 'admin_print_styles', 'print_emoji_styles' ); 
  remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
  remove_filter( 'comment_text_rss', 'wp_staticize_emoji' ); 
  remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
  add_filter( 'tiny_mce_plugins', 'gdpr_child_disable_emojis_tinymce' );
  add_filter( 'wp_resource_hints', 'gdpr_child_disable_emojis_remove_dns_prefetch', 10, 2 );
}
add_action( 'init', 'gdpr_child_disable_emojis' );


/**
* Remove the tinymce emoji plugin.
*/
function gdpr_child_disable_emojis_tinymce( $plugins ) {
  if ( is_array( $plugins ) ) {
    return array_diff( $plugins, array( 'wpemoji' ) );
  } else {
    return array();
  }
}


/**
* Remove DNS prefetching (Emojis).
*/
function gdpr_child_disable_emojis_remove_dns_prefetch( $urls, $relation_type ) {
  if ( 'dns-prefetch' == $relation_type ) {
    $emoji_svg_url = apply_filters( 'emoji_svg_url', 'https://s.w.org/images/core/emoji/2/svg/' );
    $urls = array_diff( $urls, array( $emoji_svg_url ) );
  }
  return $urls;
}


// INFO: Remove global DNS Prefetching

/**
 * Remove DNS Prefetching (a.w.org)
 */
function gdpr_child_remove_dns_prefetch() {
  remove_action('wp_head', 'wp_resource_hints', 2);
}
add_action( 'init', 'gdpr_child_remove_dns_prefetch');


// INFO: Disable WordPress REST API

/**
* Throw an Error if someone tries to access the REST API
* Whitelist solution
*/
function zine_child_disable_rest_api($access) {

  if (!is_whitelisted($whitelist)) {
    return new WP_Error('rest_disabled', __('The REST API on this site has been disabled.'), array('status' => rest_authorization_required_code()));
  }
}
add_filter( 'rest_authentication_errors', 'zine_child_disable_rest_api' );

// Gets the current route
function get_current_namespace() {

  if (isset($_REQUEST['rest_route'])) {
    $route = ltrim($_REQUEST['rest_route'], '/');
  } elseif (get_option('permalink_structure')) {
    $pos = strlen(get_rest_url());
    $route = substr(get_home_url() . urldecode($_SERVER['REQUEST_URI']), $pos);
    $route = trim($route, '/');
  }
  return substr($route, 0, strpos($route, '/'));
}

// Checks if route is whitelisted
function is_whitelisted() {

  $whitelist = array('mwl', 'shariff'); // INFO: Change whitelist here!
  $namespace = get_current_namespace();

  foreach ($whitelist as $ns) {
    if ($ns == $namespace) return true;
  }
  return false;
}

// Remove REST API info from head and headers
remove_action( 'xmlrpc_rsd_apis', 'rest_output_rsd' );
remove_action( 'wp_head', 'rest_output_link_wp_head', 10 );
remove_action( 'template_redirect', 'rest_output_link_header', 11 );