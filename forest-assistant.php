<?php
/**
 * Plugin Name: Forest Assistant
 * Plugin URI: https://github.com/Codestag/forest-assistant
 * Description: A plugin to assist Forest theme in adding widgets.
 * Author: Codestag
 * Author URI: https://codestag.com
 * Version: 1.0
 * Text Domain: forest-assistant
 * License: GPL2+
 * License URI: https://www.gnu.org/licenses/gpl-2.0.txt
 *
 * @package Forest
 */


// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Forest_Assistant' ) ) :
	/**
	 *
	 * @since 1.0
	 */
	class Forest_Assistant {

		/**
		 *
		 * @since 1.0
		 */
		private static $instance;

		/**
		 *
		 * @since 1.0
		 */
		public static function register() {
			if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Forest_Assistant ) ) {
				self::$instance = new Forest_Assistant();
				self::$instance->init();
				self::$instance->define_constants();
				self::$instance->includes();
			}
		}

		/**
		 *
		 * @since 1.0
		 */
		public function init() {

		// Enqueue styles & scripts.
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );

		}

		/**
		 *
		 * @since 1.0
		 */
		public function define_constants() {
			$this->define( 'FA_VERSION', '1.0' );
			$this->define( 'FA_DEBUG', true );
			$this->define( 'FA_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
			$this->define( 'FA_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
		}

		/**
		 *
		 * @param string $name
		 * @param string $value
		 * @since 1.0
		 */
		private function define( $name, $value ) {
			if ( ! defined( $name ) ) {
				define( $name, $value );
			}
		}

		/**
		 *
		 * @since 1.0
		 */
		public function includes() {
			// Widgets.
			require_once FA_PLUGIN_PATH . 'includes/widgets/class-forest-widget.php';
			require_once FA_PLUGIN_PATH . 'includes/widgets/widget-clients.php';
			require_once FA_PLUGIN_PATH . 'includes/widgets/widget-featured-portfolio.php';
			require_once FA_PLUGIN_PATH . 'includes/widgets/widget-latest-posts.php';
			require_once FA_PLUGIN_PATH . 'includes/widgets/widget-portfolio.php';
			require_once FA_PLUGIN_PATH . 'includes/widgets/widget-services-section.php';
			require_once FA_PLUGIN_PATH . 'includes/widgets/widget-services.php';
			require_once FA_PLUGIN_PATH . 'includes/widgets/widget-slider.php';
			require_once FA_PLUGIN_PATH . 'includes/widgets/widget-static-content.php';
			require_once FA_PLUGIN_PATH . 'includes/widgets/widget-team.php';
			require_once FA_PLUGIN_PATH . 'includes/widgets/widget-testimonials.php';

			/**
			 * Include Meta Boxes.
			 */
			require_once FA_PLUGIN_PATH . 'includes/meta/stag-admin-metaboxes.php';
			if ( false === forest_get_thememod_value( 'general_disable_seo_settings' ) ) {
				require_once FA_PLUGIN_PATH . 'includes/meta/meta-seo.php';
			}

			require_once FA_PLUGIN_PATH . 'includes/meta/meta-portfolio.php';
			require_once FA_PLUGIN_PATH . 'includes/meta/meta-slides.php';
			require_once FA_PLUGIN_PATH . 'includes/meta/meta-background.php';
			require_once FA_PLUGIN_PATH . 'includes/meta/meta-team.php';

			require_once FA_PLUGIN_PATH . 'includes/theme-shortcodes.php';
			require_once FA_PLUGIN_PATH . 'includes/shortcodes/contact-form.php';
		}

		/**
		 *
		 * @since 1.0.0
		 */
		public function enqueue_admin_styles( $hook ) {
			if ( 'post.php' === $hook || 'post-new.php' === $hook ) {
				wp_register_style( 'stag-admin-metabox', FA_PLUGIN_URL . 'assets/css/stag-admin-metabox.css', array( 'wp-color-picker' ), FA_VERSION );
				wp_enqueue_style( 'stag-admin-metabox' );
			}
		}

		/**
		 *
		 *
		 * @since 1.0.0
		 */
		public function enqueue_admin_scripts( $hook ) {
			if ( 'post.php' === $hook || 'post-new.php' === $hook || 'widgets.php' === $hook ) {
				wp_enqueue_media();
				wp_register_script( 'stag-admin-metabox', FA_PLUGIN_URL . 'assets/js/stag-admin-metabox.js', array( 'jquery', 'wp-color-picker' ), FA_VERSION );
				wp_enqueue_script( 'stag-admin-metabox' );
				wp_enqueue_style( 'wp-color-picker' );
			}
			return;
		}
	}
endif;


/**
 *
 * @since 1.0
 */
function forest_assistant() {
	return Forest_Assistant::register();
}

/**
 *
 * @since 1.0
 */
function forest_assistant_activation_notice() {
	echo '<div class="error"><p>';
	echo esc_html__( 'Forest Assistant requires Forest WordPress Theme to be installed and activated.', 'forest-assistant' );
	echo '</p></div>';
}

/**
 *
 *
 * @since 1.0
 */
function forest_assistant_activation_check() {
	$theme = wp_get_theme(); // gets the current theme
	if ( 'Forest' === $theme->name || 'Forest' === $theme->parent_theme ) {
		if ( function_exists( 'is_multisite' ) && is_multisite() ) {
			add_action( 'after_setup_theme', 'forest_assistant' );
		} else {
			forest_assistant();
		}
	} else {
		if ( ! function_exists( 'deactivate_plugins' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}
		deactivate_plugins( plugin_basename( __FILE__ ) );
		add_action( 'admin_notices', 'forest_assistant_activation_notice' );
	}
}

// Plugin loads.
forest_assistant_activation_check();
