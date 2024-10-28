<?php
/**
 * Plugin file for ict101s-contributor
 *
 * @since    1.0.0
 * @package   ict101s-contributors\includes
 */

/* Checks if delete plugin was initiated */
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	die;
}

/* Includes the required class    */
require_once __DIR__ . '/includes/class-uninstall.php';

/* Fires function for cleanup of plugin data and settings */
\ict101s\Uninstall::clean_up();
