<?php
/**
 * Plugin Name: Genesis Mobile Redirect
 * Plugin URI: http://wpstud.io/plugins
 * Description: A simple-to-use plugin for setting a different homepage for mobile devices.
 * Version: 1.1
 * Author: Frank Schrijvers
 * Author URI: http://www.wpstud.io
 * Text Domain: wpstudio-mobile-redirect
 * License: GPLv2
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2, as
 * published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

require_once ABSPATH . 'wp-admin/includes/plugin.php';

add_action( 'init', 'wpstudio_load_gmr_textdomain' );
/**
 * Callback on the `plugins_loaded` hook.
 * Loads the plugin text domain via load_plugin_textdomain()
 *
 * @uses load_plugin_textdomain()
 * @since 1.0.0
 *
 * @access public
 * @return void
 */
function wpstudio_load_gmr_textdomain() {
	load_plugin_textdomain( 'wpstudio-mobile-redirect', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}


add_action( 'genesis_setup', 'gmr_init' );
/**
 * Load required files.
 */
function gmr_init() {

	require dirname( __FILE__ ) . '/inc/class-gmr-settings.php';
	new Gmr_Settings();

}

add_action( 'wp_head', 'gmr_params', 10 );
/**
 * Init scripts.
 */
function gmr_params() {

	$gmr_page_id   = genesis_get_option( 'link_target', 'gmr-settings' );
	$gmr_set_link  = genesis_get_option( 'set_link', 'gmr-settings' );
	$gmr_page      = get_permalink( $gmr_page_id );
	$gmr_url       = genesis_get_option( 'gmr_url', 'gmr-settings' );
	$gmr_max_width = genesis_get_option( 'max_width', 'gmr-settings' );
	$gmr_device    = genesis_get_option( 'set_device', 'gmr-settings' );

	if ( 'page' === $gmr_set_link ) {
		$mobile_homepage = $gmr_page;
	}

	if ( 'url' === $gmr_set_link ) {
		$mobile_homepage = $gmr_url;
	}

	if ( 'mobile' === $gmr_device ) {
		$max_width = '480';
	}
	if ( 'tablet' === $gmr_device ) {
		$max_width = '1024';
	}
	if ( 'custom' === $gmr_device ) {
		$max_width = $gmr_max_width;
	}

	if ( ! empty( $gmr_page_id ) ) {
	?>

	<script>
	if ( window.location.pathname == '/' && jQuery(window).width() <= <?php echo( wp_json_encode ( $max_width ) ); ?> ) {

		window.location = <?php echo( wp_json_encode( $mobile_homepage ) ); ?>;

	}
	</script>

	<?php

	}

}
