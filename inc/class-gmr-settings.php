<?php
/**
 * This file adds the options to the Admin.
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

add_action( 'admin_enqueue_scripts', 'gmr_loads_scripts', 10 );
/**
 * Enqueue admin scripts.
 */
function gmr_loads_scripts() {

	if ( strpos( $_SERVER['REQUEST_URI'], 'genesis-homepage-mobile' ) !== false ) {

		wp_enqueue_script( 'wpstudio-admin-scripts', plugin_dir_url( __FILE__ ) . '../js/gmr-admin-script.js', array( 'jquery' ) );

	}
}
/**
 * Register a metabox and default settings.
 *
 * @package Genesis\Admin
 */
class Gmr_Settings extends Genesis_Admin_Boxes {

	/**
	 * Create an archive settings admin menu item and settings page for relevant custom post types.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$settings_field   = 'gmr-settings';
		$page_id          = 'genesis-homepage-mobile';
		$menu_ops         = array(
			'submenu' => array(
				'parent_slug' => 'genesis',
				'page_title'  => __( 'Genesis Mobile Redirect', 'wpstudio-mobile-redirect' ),
				'menu_title'  => __( 'Mobile Redirect', 'wpstudio-mobile-redirect' ),
			),
		);
		$page_ops         = array(); // use defaults.
		$center           = current_theme_supports( 'genesis-responsive-viewport' ) ? 'mobile' : 'never';
		$default_settings = apply_filters(
			'gmr_settings_defaults',
			array(
				'set_link'    => 'page',
				'set_device'  => 'mobile',
				'link_target' => '',
				'max_width'   => '1024',
			)
		);
		$this->create( $page_id, $menu_ops, $page_ops, $settings_field, $default_settings );
		add_action( 'genesis_settings_sanitizer_init', array( $this, 'sanitizer_filters' ) );
	}
	/**
	 * Register each of the settings with a sanitization filter type.
	 *
	 * @since 1.0.0
	 *
	 * @uses genesis_add_option_filter() Assign filter to array of settings.
	 *
	 * @see \Genesis_Settings_Sanitizer::add_filter()
	 */
	public function sanitizer_filters() {
		genesis_add_option_filter(
			'no_html',
			$this->settings_field,
			array(
				'gmr_url',
				'max_width',
			)
		);
	}
	/**
	 * Register a metabox for the Plugin information.
	 */
	public function metaboxes() {

		add_meta_box( 'genesis-theme-settings-version', __( 'Plugin Information', 'gwpstudio-mobile-redirect' ), array( $this, 'info_box' ), $this->pagehook, 'main', 'high' );
		add_meta_box( 'gmr-settings', __( 'Plugin Settings', 'wpstudio-mobile-redirect' ), array( $this, 'gmr_settings' ), $this->pagehook, 'main', 'high' );

	}

	/**
	 * Create the metabox
	 */
	public function info_box() {

		echo '<ul>
		<li><strong style="width: 180px; margin: 0 40px 20px 0; display: inline-block; font-weight: 600;">' . __( 'Developed By:', 'glmb' ) . '</strong> <a href="https://www.wpstud.io<" target="_blank">WPStudio</a></li>
		<li><strong style="width: 180px; margin: 0 40px 20px 0; display: inline-block; font-weight: 600;">' . __( 'Follow on twitter:', 'glmb' ) . '</strong> <a href="https://twitter.com/wpstudiowp">https://twitter.com/wpstudiowp</a></li>
		<li><strong style="width: 180px; margin: 0 40px 20px 0; display: inline-block; font-weight: 600;">' . __( 'Contact:', 'glmb' ) . '</strong> <a href="mailto:info@wpstud.io">info@wpstud.io</a></li>
		</ul>';

	}

	/**
	 * Selection and input fields
	 */
	public function gmr_settings() {

		?>

		<p class="gmr-select-source">
			<label style="width: 180px; margin: 0 40px 0 0; font-weight: bold; display: inline-block;" for="<?php echo $this->get_field_id( 'Select source' ); ?>"><?php _e( 'Select source', 'wpstudio-mobile-redirect' ); ?>:</label>
			<label for="<?php echo $this->get_field_id( 'show_page' ); ?>"></label>
				<input type="radio" id="page-input" name="<?php echo $this->get_field_name( 'set_link' ); ?>" value="page" <?php checked( $this->get_field_value( 'set_link' ), 'page' ); ?> />
				<span><?php _e( 'Page', 'wpstudio-mobile-redirect' ); ?></span>
			</label>
			<label for="<?php echo $this->get_field_id( 'show_url' ); ?>"></label>
				<input type="radio" id="url-input" name="<?php echo $this->get_field_name( 'set_link' ); ?>" value="url" <?php checked( $this->get_field_value( 'set_link' ), "url" ); ?> />
				<span><?php _e( 'URL', 'wpstudio-mobile-redirect' ); ?></span>
			</label>
		</p>

		<p id="show_page">
			<label style="width: 180px; margin: 0 40px 0 0; font-weight: bold; display: inline-block;" for="<?php echo $this->get_field_id( 'link_target' ); ?>"><?php _e( 'Page:', 'wpstudio-mobile-redirect' ); ?>
			</label>
			<?php
			wp_dropdown_pages( array(
				'id'               => $this->get_field_id( 'link_target' ),
				'name'             => $this->get_field_name( 'link_target' ),
				'show_option_none' => __( 'Please select a page', 'wpstudio-mobile-redirect' ),
				'echo'             => 1,
				'selected'         => $this->get_field_value( 'link_target' ),
			) );
		?>
		</p>

		<p id="show_url" class="hidden">
			<label style="width: 180px; margin: 0 40px 0 0; font-weight: bold; display: inline-block;" for="<?php echo $this->get_field_id( 'gmr_url' ); ?>"><?php _e( 'URL', 'wpstudio-mobile-redirect' ); ?>
			</label>
			<input type=text" style="width: 230px;"" type="text" data-default-color="#ffffff" name="<?php echo $this->get_field_name( 'gmr_url' ); ?>" id="<?php echo $this->get_field_id( 'gmr_url' ); ?>?" value="<?php echo $this->get_field_value( 'gmr_url' ); ?>" />
		</p>

		<p class="gmr-set-device">
			<label style="width: 180px; margin: 0 40px 0 0; font-weight: bold; display: inline-block;" for="<?php echo $this->get_field_id( 'Show on:' ); ?>"><?php _e( 'Show on', 'wpstudio-mobile-redirect' ); ?>:</label>
			<label for="<?php echo $this->get_field_id( 'gmr_mobile' ); ?>"></label>
				<input type="radio" id="<?php echo $this->get_field_id( 'gmr_mobile' ); ?>" name="<?php echo $this->get_field_name( 'set_device' ); ?>" value="mobile" <?php checked( $this->get_field_value( 'set_device' ), 'mobile' ); ?> />
				<span><?php _e( 'Smartphone', 'wpstudio-mobile-redirect' ); ?></span>
			</label>
			</br>

			<label style="width: 180px; margin: 0 40px 0 0; font-weight: bold; display: inline-block;" for="<?php echo $this->get_field_id( 'gmr_tablet' ); ?>"></label>
				<input type="radio" id="<?php echo $this->get_field_id( 'gmr_tablet' ); ?>" name="<?php echo $this->get_field_name( 'set_device' ); ?>" value="tablet" <?php checked( $this->get_field_value( 'set_device' ), 'tablet' ); ?> />
				<span><?php _e( 'Smartphone & Tablet', 'wpstudio-mobile-redirect' ); ?></span>
			</label>
			</br>

			<label style="width: 180px; margin: 0 40px 0 0; font-weight: bold; display: inline-block;" for="<?php echo $this->get_field_id( 'show_url' ); ?>"></label>
				<input type="radio" id="custom-input" name="<?php echo $this->get_field_name( 'set_device' ); ?>" value="custom" <?php checked( $this->get_field_value( 'set_device' ), 'custom' ); ?> />
				<span><?php _e( 'Custom max width', 'wpstudio-mobile-redirect' ); ?></span>
			</label>
		</p>

		<p class="hidden" id="show-custom">
			<label style="width: 180px; margin: 0 40px 0 0; font-weight: bold; display: inline-block;" for="<?php echo $this->get_field_id( 'gmr_max_width' ); ?>"><?php _e( 'Max-width:', 'wpstudio-mobile-redirect' ); ?>
			</label>
			<input type="number" style="width: 230px;" data-default-color="#ffffff" name="<?php echo $this->get_field_name( 'max_width' ); ?>" value="<?php echo $this->get_field_value( 'max_width' ); ?>" />
		</p>
		<?php

	}

}
