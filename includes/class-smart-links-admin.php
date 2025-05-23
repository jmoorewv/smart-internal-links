<?php
/**
 * Admin functionality for Smart Internal Links
 */

// Enable strict types
declare( strict_types=1 );

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Smart_Links_Admin Class
 */
class Smart_Links_Admin {

    /**
     * Settings instance
     */
    private Smart_Links_Settings $settings;

    /**
     * Constructor
     */
    public function __construct() {
        $this->settings = new Smart_Links_Settings();
        $this->init_hooks();
    }

    /**
     * Initialize hooks
     *
     * @return void
     */
    private function init_hooks(): void {
        // Add menu item
        add_action( 'admin_menu', [ $this, 'add_admin_menu' ] );

        // Add settings link to plugins page
        add_filter( 'plugin_action_links_' . plugin_basename( SMART_LINKS_PLUGIN_FILE ), [ $this, 'add_settings_link' ] );

        // Register settings
        add_action( 'admin_init', [ $this, 'register_settings' ] );

        // Enqueue admin styles and scripts
        add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_admin_assets' ] );
    }

    /**
     * Add menu item
     *
     * @return void
     */
    public function add_admin_menu(): void {
        add_options_page(
            __( 'Smart Internal Links', 'smart-internal-links' ),
            __( 'Smart Internal Links', 'smart-internal-links' ),
            'manage_options',
            'smart-internal-links',
            [ $this, 'display_settings_page' ]
        );
    }

    /**
     * Add settings link to plugin listing
     *
     * @param array $links
     * @return array
     */
    public function add_settings_link( array $links ): array {
        $settings_link = '<a href="' . admin_url( 'options-general.php?page=smart-internal-links' ) . '">' . __( 'Settings', 'smart-internal-links' ) . '</a>';
        array_unshift( $links, $settings_link );
        return $links;
    }

    /**
     * Register settings
     *
     * @return void
     */
    public function register_settings(): void {
        register_setting(
            'smart_internal_links_options',
            'smart_internal_links',
            [ $this->settings, 'sanitize' ]
        );
    }

    /**
     * Enqueue admin styles and scripts
     *
     * @param string $hook
     * @return void
     */
    public function enqueue_admin_assets( string $hook ): void {
        if ( 'settings_page_smart-internal-links' !== $hook ) {
            return;
        }

        // Enqueue CSS
        wp_enqueue_style(
            'smart-internal-links-admin',
            SMART_LINKS_PLUGIN_URL . 'assets/css/admin.css',
            [],
            SMART_LINKS_VERSION
        );

        // Enqueue JS
        wp_enqueue_script(
            'smart-internal-links-admin',
            SMART_LINKS_PLUGIN_URL . 'assets/js/admin.js',
            [ 'jquery' ],
            SMART_LINKS_VERSION,
            true
        );
    }

    /**
     * Display settings page
     *
     * @return void
     */
    public function display_settings_page(): void {
        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }

        // Check if form is submitted
        if ( isset( $_POST['submit'] ) ) {
            check_admin_referer( 'smart_internal_links_nonce' );

            // Save settings
            $this->settings->save_settings( $_POST );

            // Clear cache
            $core = new Smart_Links_Core();
            $core->delete_cache( 0 );

            echo '<div class="notice notice-success is-dismissible"><p>' . __( 'Settings saved.', 'smart-internal-links' ) . '</p></div>';
        }

        // Load settings template
        include_once SMART_LINKS_PLUGIN_DIR . 'admin/views/settings-page.php';
    }
}

/**
 * Include Settings class
 */
require_once SMART_LINKS_PLUGIN_DIR . 'includes/class-smart-links-settings.php';
