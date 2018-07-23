<?php

/**
 * Implement Child Theme Scripts and overwrite Google Fonts
 */
function gdpr_child_scripts() {
  wp_enqueue_style('gdpr-style', get_template_directory_uri() . '/style.css', array(), null); // original style.css
  wp_enqueue_style( 'gdpr-child-style', get_stylesheet_directory_uri() . '/style.css', 'yoko-style', null ); // child style.css
  wp_enqueue_style('gdpr-fonts', get_stylesheet_directory_uri() . '/css/fonts.css');
}
add_action( 'wp_enqueue_scripts' , 'gdpr' );


/**
 * Disable oEmbeds
 */
function gdpr_child_disable_embeds() {
  remove_action( 'rest_api_init', 'wp_oembed_register_route' ); // JSON API
  add_filter( 'embed_oembed_discover', '__return_false' ); // Auto Discover
  remove_filter( 'oembed_dataparse', 'wp_filter_oembed_result', 10 ); // Results
  remove_action( 'wp_head', 'wp_oembed_add_discovery_links' ); // Discovery Links
  remove_action( 'wp_head', 'wp_oembed_add_host_js' ); // Frontend JS
  add_filter( 'tiny_mce_plugins', 'disable_embeds_tiny_mce_plugin' ); // TinyMCE
  add_filter( 'rewrite_rules_array', 'disable_embeds_rewrites' ); // Rerite Rules
  remove_filter( 'pre_oembed_result', 'wp_filter_pre_oembed_result', 10 ); // oEmbeds Preloader
}
add_action( 'init', 'gdpr_child_disable_embeds', 9999 );