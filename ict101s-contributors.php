<?php
/**
 * The plugin creates a meta box labelled 'Contributors' and populates it with checkboxes
 * with the names of the blog users. Authors select the checkboxes with the names corresponding
 * to their contributors. The list of the chosen contributor is diaplayed at the bottom of
 * each post.
 *
 * This file is read by WordPress to do the following:
 * Perform activation
 * Setup i18n
 * Require all the required classes for the plugin
 * Add all the action and filter hooks required
 *
 * @since             1.0.0
 * @package           ict101s-contributors
 *
 * @wordpress-plugin
 * Plugin Name: Add Contributors
 * Plugin URI: https://itcrackteam.com/101series/wordpress/plugins/ict101s-contributors
 * Description: Creates a metabox to select WordPress users to list as contributors in post.
 * Version: 1.0.0
 * Author: Babatope (Ben) Babajide
 * Author URI: https://itcrackteam.com/ben
 * Text Domain: ict101s-contributors
 * Domain Path: /languages
 * License: GPL3
 *
 * Copyright (c) 2019  Babatope (Ben) Babajide (E-mail: Ben@itcrackteam.com)
 *
 * Add Contributors is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * any later version.
 *
 * Add Contributors is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Add Contributors. If not, see http://www.gnu.org/licenses/gpl-3.0.html.
 */

/* includes all required classes    */
require_once __DIR__ . '/admin/class-contributors-admin.php';
require_once __DIR__ . '/public/class-contributors-public.php';
require_once __DIR__ . '/includes/class-activator.php';
require_once __DIR__ . '/includes/class-ict101s-i18n.php';

/* Performs activation activities */
register_activation_hook( __FILE__, [ '\ict101s\Activator', 'run_checks' ] );

/* Setup internationalization */
add_action( 'plugins_loaded', [ '\ict101s\Ict101s_I18n', 'ict101s_contributors_load_plugin_textdomain' ] );

/* Loads all actions and filters - Admin area. */
add_action( 'load-post.php', [ '\ict101s\Contributors_admin', 'ict_add_contributor_setup' ] );
add_action( 'load-post-new.php', [ '\ict101s\Contributors_admin', 'ict_add_contributor_setup' ] );
add_action( 'save_post', array( '\ict101s\Contributors_admin', 'update_contributors_meta_value' ), 10, 2 );

/* Loads all actions and filters - Public area. */
add_filter( 'the_content', [ '\ict101s\Contributors_public', 'display_contributors' ] );
add_action( 'wp_enqueue_scripts', [ '\ict101s\Contributors_public', 'enqueue_plugin_styles' ] );
