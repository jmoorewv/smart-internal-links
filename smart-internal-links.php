<?php
/**
 * Plugin Name:     Smart Internal Links
 * Plugin URI:      https://jmoorewv.com
 * Description:     Smart Internal Links provides automatic SEO internal links for your site, keyword lists, nofollow and much more.
 * Version:         3.0.1
 * Author:          Jonathan Moore
 * Author URI:      https://jmoorewv.com
 * License:         GNU General Public License, v2 (or newer)
 * Text Domain:     smart-internal-links
 * Domain Path:     /languages
 * Requires PHP:    7.4
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Define plugin constants
define( 'SMART_LINKS_VERSION', '3.0.1' );
define( 'SMART_LINKS_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'SMART_LINKS_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'SMART_LINKS_PLUGIN_FILE', __FILE__ );

/**
 * Main Smart Internal Links Class
 */
final class Smart_Internal_Links {

    /**
     * @var Smart_Internal_Links|null The single instance of the class
     */
    private static ?self $_instance = null;

    /**
     * Main Smart_Internal_Links Instance
     *
     * Ensures only one instance of Smart_Internal_Links is loaded or can be loaded.
     *
     * @return Smart_Internal_Links - Main instance
     */
    public static function instance(): self {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Smart_Internal_Links Constructor.
     */
    public function __construct() {
        $this->includes();
        $this->init_hooks();
    }

    /**
     * Include required files
     *
     * @return void
     */
    private function includes(): void {
        // Core functionality
        require_once SMART_LINKS_PLUGIN_DIR . 'includes/class-smart-links-core.php';

        // Admin
        if ( is_admin() ) {
            require_once SMART_LINKS_PLUGIN_DIR . 'includes/class-smart-links-admin.php';
        }
    }

    /**
     * Hook into actions and filters
     *
     * @return void
     */
    private function init_hooks(): void {
        // Register activation hook
        register_activation_hook( __FILE__, [ $this, 'activate' ] );

        // Initialize the plugin
        add_action( 'plugins_loaded', [ $this, 'init' ] );
    }

    /**
     * Activation function
     *
     * @return void
     */
    public function activate(): void {
        // Create or update options with default values
        $core = new Smart_Links_Core();
        $core->install();

        // Clear caches
        $core->delete_cache( 0 );
    }

    /**
     * Initialize plugin when WordPress initializes
     *
     * @return void
     */
    public function init(): void {
        // Initialize the core functionality
        new Smart_Links_Core();

        // Initialize admin if in admin area
        if ( is_admin() ) {
            new Smart_Links_Admin();
        }
    }
}

/**
 * Returns the main instance of Smart_Internal_Links.
 *
 * @return Smart_Internal_Links
 */
function Smart_Internal_Links(): Smart_Internal_Links {
    return Smart_Internal_Links::instance();
}

// Let's get started
Smart_Internal_Links();
