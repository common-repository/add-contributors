<?php
/**
 * Plugin file for ict101s-contributor
 *
 * @since    1.0.0
 * @package   ict101s-contributors\includes
 */

namespace ict101s;

/**
 * Code to setup i18n
 *
 * @since 1.0.0
 */
abstract class Ict101s_I18n {
		/**
		 * Code to load plugin text domain
		 *
		 * @since 1.0.0
		 */
	public static function ict101s_contributors_load_plugin_textdomain() {
		load_plugin_textdomain( 'ict101s-contributors', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}
}
