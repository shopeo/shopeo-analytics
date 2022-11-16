<?php
/**
 * Plugin Name: SHOPEO Analytics
 * Plugin URI: https://www.shopeo.cn/plugins/shopeo-analytics
 * Description: Integrate Baidu tongji and Google analytics.
 * Author: Shopeo
 * Version: 0.0.1
 * Author URI: https://www.shopeo.cn
 * License: GPL2+
 * Text Domain: shopeo-analytics
 * Domain Path: /languages
 * Requires at least: 5.9
 * Requires PHP: 5.6
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Define SHOPEO_ANALYTICS_PLUGIN_FILE.
if ( ! defined( 'SHOPEO_ANALYTICS_PLUGIN_FILE' ) ) {
	define( 'SHOPEO_ANALYTICS_PLUGIN_FILE', __FILE__ );
}

if ( ! defined( 'SHOPEO_ANALYTICS_PLUGIN_BASE' ) ) {
	define( 'SHOPEO_ANALYTICS_PLUGIN_BASE', plugin_basename( SHOPEO_ANALYTICS_PLUGIN_FILE ) );
}

if ( ! defined( 'SHOPEO_ANALYTICS_PATH' ) ) {
	define( 'SHOPEO_ANALYTICS_PATH', plugin_dir_path( SHOPEO_ANALYTICS_PLUGIN_FILE ) );
}

if ( ! function_exists( 'shopeo_analytics_sanitize' ) ) {
	function shopeo_analytics_sanitize( $input ) {
		$sanitary_values = array();

		if ( isset( $input['shopeo_analytics_code'] ) ) {
			$sanitary_values['shopeo_analytics_code'] = $input['shopeo_analytics_code'];
		}

		return $sanitary_values;
	}
}

if ( ! function_exists( 'shopeo_analytics_section_info' ) ) {
	function shopeo_analytics_section_info() {
		printf( __( 'Find the required setup information via <a target="_blank" href="%1$s">%2$s</a> or <a target="_blank" href="%3$s">%4$s</a>', 'shopeo-analytics' ), 'https://tongji.baidu.com/', 'tongji.baidu.com', 'https://analytics.google.com/', 'analytics.google.com' );
	}
}

if ( ! function_exists( 'shopeo_analytics_code_callback' ) ) {
	function shopeo_analytics_code_callback() {
		printf( '<textarea class="regular-text" rows="5" name="shopeo_analytics_options[shopeo_analytics_code]" id="shopeo_analytics_code">%s</textarea>', isset( get_option( 'shopeo_analytics_options' )['shopeo_analytics_code'] ) ? esc_attr( get_option( 'shopeo_analytics_options' )['shopeo_analytics_code'] ) : '' );
	}
}

if ( ! function_exists( 'shopeo_analytics_page_init' ) ) {
	function shopeo_analytics_page_init() {
		register_setting( 'shopeo_analytics_option_group', 'shopeo_analytics_options', 'shopeo_analytics_sanitize' );

		add_settings_section( 'shopeo_analytics_setting_section', __( 'Settings', 'shopeo-analytics' ), 'shopeo_analytics_section_info', 'options_shopeo_analytics' );

		add_settings_field( 'shopeo_analytics_code', __( 'Code', 'shopeo-analytics' ), 'shopeo_analytics_code_callback', 'options_shopeo_analytics', 'shopeo_analytics_setting_section' );
	}
}

add_action( 'admin_init', 'shopeo_analytics_page_init' );

if ( ! function_exists( 'shopeo_analytics_activate' ) ) {
	function shopeo_analytics_activate() {

	}
}

register_activation_hook( __FILE__, 'shopeo_analytics_activate' );


if ( ! function_exists( 'shopeo_analytics_deactivate' ) ) {
	function shopeo_analytics_deactivate() {
		delete_option( 'shopeo_analytics_options' );
	}
}

register_deactivation_hook( __FILE__, 'shopeo_analytics_deactivate' );

if ( ! function_exists( 'shopeo_analytics_load_textdomain' ) ) {
	function shopeo_analytics_load_textdomain() {
		load_plugin_textdomain( 'shopeo-analytics', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
	}
}

add_action( 'init', 'shopeo_analytics_load_textdomain' );

if ( ! function_exists( 'shopeo_analytics_manage_options' ) ) {
	function shopeo_analytics_manage_options() {
		?>
		<div class="wrap">
			<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
			<form action="options.php" method="post">
				<?php
				settings_fields( 'shopeo_analytics_option_group' );
				do_settings_sections( 'options_shopeo_analytics' );
				submit_button( __( 'Save Settings', 'shopeo-analytics' ) );
				?>
			</form>
		</div>
		<?php
	}
}

if ( ! function_exists( 'shopeo_analytics_options_page' ) ) {
	function shopeo_analytics_options_page() {
		add_options_page( __( 'SHOPEO Analytics', 'shopeo-analytics' ), __( 'Analytics', 'shopeo_analytics' ), 'manage_options', 'options_shopeo_analytics', 'shopeo_analytics_manage_options', 10 );
	}
}

add_action( 'admin_menu', 'shopeo_analytics_options_page' );

if ( ! function_exists( 'shopeo_analytics_head' ) ) {
	function shopeo_analytics_head() {
		if ( is_archive() || is_singular() || is_home() || is_404() || is_search() || is_front_page() ) {
			echo isset( get_option( 'shopeo_analytics_options' )['shopeo_analytics_code'] ) ? get_option( 'shopeo_analytics_options' )['shopeo_analytics_code'] : '';
		}
	}
}

add_action( 'wp_head', 'shopeo_analytics_head' );
