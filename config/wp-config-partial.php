<?php
/**
 * This is only part of the wp-config.php!
 * These are only best practices I use to optimize the WordPress config.
 */

/**
 * SECURITY
 */
define('WP_SITEURL', 'https://example.com'); // Attacker can't change the URL by himself
define('WP_HOME', 'https://example.com'); // Attacker can't change the URL by himself
define('DISALLOW_FILE_EDIT', true); // Attacker can't change the themes & plugins by himself
define('FORCE_SSL_ADMIN', true); // No HTTP downgrading for the backend

/**
 * DEBUG
 * Use it only when you're propramming!
 */
define( 'WP_DEBUG', true );
define( 'WP_DEBUG_LOG', false );
define( 'WP_DEBUG_DISPLAY', true );

/**
 * OPTIONS
 */
define('WP_POST_REVISIONS', 3 ); // Reduces the mess with the post revisions (set it to false if your want to turn it off)
define('WP_ALLOW_MULTISITE', true); // Enable WordPress MU
define('EMPTY_TRASH_DAYS', 30); // Empties trash after 30 days (use it wisely!)