<?php
/**
 * Plugin file for ict101s-contributor
 *
 * @since    1.0.0
 * @package   ict101s-contributors\includes
 */

namespace ict101s;

/* Check if delete plugin was initiated */
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
		die;
}

/**
 * Code to perform some checks during activation
 *
 * @since 1.0.0
 */
abstract class Uninstall {
	/**
	 * Code to run all the functions to clean up all plugin data and settings
	 *
	 * @since 1.0.0
	 */
	public static function clean_up() {
		self::delete_contributor_metakey();
	}

	/**
	 * Code to delete contributors plugin metakeys and values from the wp_postmeta table
	 *
	 * @since 1.0.0
	 */
	private static function delete_contributor_metakey() {
		delete_post_meta_by_key( 'ict101s_contributor' );
	}

}
