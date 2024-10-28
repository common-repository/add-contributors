<?php
/**
 * Plugin file for ict101s-contributor
 *
 * @since    1.0.0
 * @package   ict101s-contributors\includes
 */

namespace ict101s;

/**
 * Code to perform some checks during activation
 *
 * @since 1.0.0
 */
abstract class Activator {
	/**
	 * Code to run all the functions to perform activation checks and settings
	 *
	 * @since 1.0.0
	 */
	public static function run_checks() {
		self::check_wordpress_version();
	}

	/**
	 * Code to check WordPress version
	 *
	 * @since 1.0.0
	 */
	private static function check_wordpress_version() {
		if ( version_compare( get_bloginfo( 'version' ), '5.2.2', '<' ) ) {
			wp_die(
				'You must update WordPress to use this plugin.',
				'Activation Error',
				array( 'back_link' => true )
			);
		}
	}
}
