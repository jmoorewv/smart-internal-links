<?php
/**
 * Core functionality for Smart Internal Links
 */

// Enable strict types
declare( strict_types=1 );

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Smart_Links_Core Class
 */
class Smart_Links_Core {

    /**
     * DB option name
     */
    private string $option_name = 'smart_internal_links';

    /**
     * Options array
     */
    private array $options;

    /**
     * Constructor
     */
    public function __construct() {
        $this->options = $this->get_options();

        // Initialize hooks only if we have options
        if ( ! empty( $this->options ) ) {
            $this->init_hooks();
        }
    }

    /**
     * Initialize hooks
     *
     * @return void
     */
    private function init_hooks(): void {
        // Setup content filters
        if ( ! empty( $this->options['post'] ) || ! empty( $this->options['page'] ) ) {
            add_filter( 'the_content', [ $this, 'process_content' ], 10 );
        }

        if ( ! empty( $this->options['allowfeed'] ) ) {
            add_filter( 'the_excerpt_rss', [ $this, 'process_content' ], 10 );
            add_filter( 'the_content_feed', [ $this, 'process_content' ], 10 );
        }

        if ( ! empty( $this->options['comment'] ) ) {
            add_filter( 'comment_text', [ $this, 'process_comments' ], 10 );
        }

        // Final cleanup filter - ONLY apply if self-links are not allowed
        if ( empty( $this->options['postself'] ) || empty( $this->options['pageself'] ) ) {
            add_filter( 'the_content', [ $this, 'remove_self_links' ], 99 );
        }

        // Cache management hooks
        add_action( 'create_category', [ $this, 'delete_cache' ] );
        add_action( 'edit_category', [ $this, 'delete_cache' ] );
        add_action( 'edit_post', [ $this, 'delete_cache' ] );
        add_action( 'save_post', [ $this, 'delete_cache' ] );

        // Disable wptexturize to prevent it from interfering with our links
        add_filter( 'run_wptexturize', '__return_false' );
    }

    /**
     * Get plugin options
     *
     * @return array
     */
    public function get_options(): array {
        $default_options = [
            'post'                           => 'on',
            'postself'                       => '',
            'page'                           => 'on',
            'pageself'                       => '',
            'comment'                        => '',
            'excludeheading'                 => 'on',
            'excludefigcaption'              => 'on',
            'lposts'                         => 'on',
            'lpages'                         => 'on',
            'lcats'                          => '',
            'ltags'                          => '',
            'ignore'                         => 'about',
            'ignorepost'                     => 'contact',
            'maxlinks'                       => 3,
            'maxsingle'                      => 1,
            'minusage'                       => 1,
            'customkey'                      => '',
            'customkey_preventduplicatelink' => false,
            'nofoln'                         => '',
            'nofolo'                         => '',
            'blankn'                         => '',
            'blanko'                         => '',
            'onlysingle'                     => 'on',
            'casesens'                       => '',
            'allowfeed'                      => '',
            'maxsingleurl'                   => '1',
            'notice'                         => '1'
        ];

        // Get existing settings from database
        $saved = get_option( $this->option_name, [] );

        // Only merge and update if saved settings exist and are different from defaults
        if ( ! empty( $saved ) && is_array( $saved ) ) {
            // Merge saved settings with defaults, keeping existing values
            $merged_options = array_merge( $default_options, $saved );

            // Only update database if something actually changed
            if ( $merged_options !== $saved ) {
                update_option( $this->option_name, $merged_options );
            }

            return $merged_options;
        }

        // No saved settings exist, create with defaults
        update_option( $this->option_name, $default_options );
        return $default_options;
    }

    /**
     * Installation function
     *
     * @return void
     */
    public function install(): void {
        // Get existing options to preserve them
        $existing_options = get_option( $this->option_name, [] );

        // If we already have settings, don't overwrite them
        if ( ! empty( $existing_options ) && is_array( $existing_options ) ) {
            // Just ensure we have the new defaults merged in
            $this->get_options();
            return;
        }

        // No existing settings, create defaults
        $this->get_options();
    }

    /**
     * Process post/page content
     *
     * @param string $content
     * @return string
     */
    public function process_content( $content ) {
        // Handle null, empty, or non-string content
        if ( $content === null || $content === false || $content === '' ) {
            return (string) $content;
        }

        // Ensure we have a string
        if ( ! is_string( $content ) ) {
            $content = (string) $content;
        }

        // Skip processing if content is just whitespace
        if ( trim( $content ) === '' ) {
            return $content;
        }

        try {
            $processor = new Smart_Links_Content_Processor( $this->options );
            $processed_content = $processor->process_text( $content, false );

            // If processing failed, return original content
            if ( $processed_content === null || $processed_content === false ) {
                return $content;
            }

            $processed_content = $this->apply_external_link_attributes( $processed_content );
            $processed_content = $this->clean_links( $processed_content );

            return $processed_content;
        } catch ( Exception $e ) {
            // If any error occurs, return original content
            return $content;
        }
    }

    /**
     * Process comments
     *
     * @param string $content
     * @return string
     */
    public function process_comments( $content ) {
        // Handle null, empty, or non-string content
        if ( $content === null || $content === false || $content === '' ) {
            return (string) $content;
        }

        // Ensure we have a string
        if ( ! is_string( $content ) ) {
            $content = (string) $content;
        }

        // Skip processing if content is just whitespace
        if ( trim( $content ) === '' ) {
            return $content;
        }

        try {
            $processor = new Smart_Links_Content_Processor( $this->options );
            $processed_content = $processor->process_text( $content, true );

            // If processing failed, return original content
            if ( $processed_content === null || $processed_content === false ) {
                return $content;
            }

            $processed_content = $this->apply_external_link_attributes( $processed_content );
            $processed_content = $this->clean_links( $processed_content );

            return $processed_content;
        } catch ( Exception $e ) {
            // If any error occurs, return original content
            return $content;
        }
    }

    /**
     * Apply attributes to external links based on settings
     *
     * @param string $content
     * @return string
     */
    private function apply_external_link_attributes( string $content ): string {
        // Get the site URL for comparison
        $site_url = parse_url( get_bloginfo( 'wpurl' ) );
        $site_host = $site_url['host'] ?? '';

        // Apply target="_blank" to external links
        if ( ! empty( $this->options['blanko'] ) ) {
            $content = preg_replace_callback(
                '/<a\s+([^>]*?href=["\'])([^"\']+)(["\'][^>]*)>/i',
                function( $matches ) use ( $site_host ) {
                    $url = $matches[2];
                    $url_parts = parse_url( $url );

                    // If the URL doesn't have a host part, it's a relative URL ( internal )
                    if ( ! isset( $url_parts['host'] ) ) {
                        return $matches[0]; // Return unchanged
                    }

                    // If the host matches our site, it's internal
                    if ( isset( $url_parts['host'] ) && $url_parts['host'] == $site_host ) {
                        return $matches[0]; // Return unchanged
                    }

                    // External link - add target="_blank"
                    return '<a ' . $matches[1] . $matches[2] . $matches[3] . ' target="_blank">';
                },
                $content
            );
        }

        // Apply rel="nofollow" to external links
        if ( ! empty( $this->options['nofolo'] ) ) {
            $content = preg_replace_callback(
                '/<a\s+([^>]*?href=["\'])([^"\']+)(["\'][^>]*)>/i',
                function( $matches ) use ( $site_host ) {
                    $url = $matches[2];
                    $url_parts = parse_url( $url );

                    // If the URL doesn't have a host part, it's a relative URL ( internal )
                    if ( ! isset( $url_parts['host'] ) ) {
                        return $matches[0]; // Return unchanged
                    }

                    // If the host matches our site, it's internal
                    if ( isset( $url_parts['host'] ) && $url_parts['host'] == $site_host ) {
                        return $matches[0]; // Return unchanged
                    }

                    // Check if rel attribute already exists
                    if ( strpos( $matches[3], ' rel=' ) !== false ) {
                        // Add nofollow to existing rel attribute
                        $result = preg_replace( '/\srel=(["\'])(.*?)(["\'])/', ' rel=$1$2 nofollow$3',
                            $matches[0] );
                        return $result;
                    } else {
                        // Add new rel attribute
                        return '<a ' . $matches[1] . $matches[2] . $matches[3] . ' rel="nofollow">';
                    }
                },
                $content
            );
        }

        return $content;
    }

    /**
     * Clean up links - fix spaces in URLs and remove self-links
     *
     * @param string $content
     * @return string
     */
    public function clean_links( string $content ): string {
        // Fix spaces in URLs
        $content = preg_replace_callback( '/<a\s+([^>]*href=["\'])\s+([^"\']+)(["\'][^>]*)>/i', function( $matches ) {
            return '<a ' . $matches[1] . $matches[2] . $matches[3] . '>';
        }, $content );

        return $content;
    }

    /**
     * Check if self-links should be allowed for current post type
     *
     * @return bool True if self-links are allowed, false otherwise
     */
    public function allow_self_links(): bool {
        global $post;

        if ( ! is_object( $post ) ) {
            return false;
        }

        if ( $post->post_type === 'post' && ! empty( $this->options['postself'] ) ) {
            return true;
        }

        if ( $post->post_type === 'page' && ! empty( $this->options['pageself'] ) ) {
            return true;
        }

        return false;
    }

    /**
     * Final check to remove any self-links that might have been created
     * Only runs if self-links are not allowed
     *
     * @param string $content
     * @return string
     */
    public function remove_self_links( string $content ): string {
        global $post;

        // Only process if we have a post object
        if ( ! is_object( $post ) ) {
            return $content;
        }

        // If self-links are allowed for this post type, don't remove them
        if ( $this->allow_self_links() ) {
            return $content;
        }

        // Get the current post permalink
        $permalink = get_permalink( $post->ID );
        if ( empty( $permalink ) ) {
            return $content;
        }

        // Different variations of the permalink to check
        $permalink_variations = [
            trim( $permalink ),
            trim( str_replace( 'https://', 'http://', $permalink ) ),
            trim( str_replace( 'http://', 'https://', $permalink ) ),
            trim( trailingslashit( $permalink ) ),
            trim( untrailingslashit( $permalink ) )
        ];

        // Regular expression pattern to match links to the current post
        $pattern = '/<a\s+[^>]*href=[\'"]([^\'"]+)[\'"][^>]*>(.*?)<\/a>/i';

        // Process the content to find and remove self-links
        $processed_content = preg_replace_callback( $pattern, function( $matches ) use ( $permalink_variations ) {
            $link_url = $matches[1];
            $link_text = $matches[2];

            // Check if this is a self-link
            foreach ( $permalink_variations as $url ) {
                if ( trim( $link_url ) == $url || trim( urldecode( $link_url ) ) == $url ) {
                    // This is a self-link - return just the text without the link
                    return $link_text;
                }
            }

            // Not a self-link, return the original match
            return $matches[0];
        }, $content );

        return $processed_content ?? $content;
    }

    /**
     * Check if a URL is a self-link by comparing paths
     *
     * @param string $url The URL to check
     * @return bool True if the URL is a self-link, false otherwise
     */
    public function is_self_link( string $url ): bool {
        global $post;

        // If we have no post to compare against, it can't be a self-link
        if ( ! is_object( $post ) ) {
            return false;
        }

        // If self-links are allowed for this post type, don't block them
        if ( $this->allow_self_links() ) {
            return false;
        }

        // Get the current post permalink
        $permalink = get_permalink( $post->ID );
        if ( empty( $permalink ) ) {
            return false;
        }

        // Clean up URLs for comparison
        $url = trim( $url );
        $permalink = trim( $permalink );

        // Parse URLs to get paths
        $url_parts = parse_url( $url );
        $permalink_parts = parse_url( $permalink );

        // If we can't parse the URLs, fall back to string comparison
        if ( ! isset( $url_parts['path'] ) || ! isset( $permalink_parts['path'] ) ) {
            return trailingslashit( $url ) == trailingslashit( $permalink );
        }

        // Compare paths
        $url_path = trailingslashit( trim( $url_parts['path'] ) );
        $permalink_path = trailingslashit( trim( $permalink_parts['path'] ) );

        return $url_path == $permalink_path;
    }

    /**
     * Delete the cache
     *
     * @param int $id
     * @return void
     */
    public function delete_cache( int $id ): void {
        wp_cache_delete( 'smart-links-categories', 'smart-internal-links' );
        wp_cache_delete( 'smart-links-tags', 'smart-internal-links' );
        wp_cache_delete( 'smart-links-posts', 'smart-internal-links' );
    }

    /**
     * Helper function to explode and trim values
     *
     * @param string $separator
     * @param string $text
     * @return array
     */
    public function explode_trim( string $separator, string $text ): array {
        $arr = explode( $separator, $text );
        $ret = [];

        foreach( $arr as $e ) {
            $ret[] = trim( $e );
        }

        return $ret;
    }
}

/**
 * Include the Content Processor
 */
require_once SMART_LINKS_PLUGIN_DIR . 'includes/class-smart-links-content-processor.php';

/**
 * Helper functions for special character handling
 */
function insertspecialchars( string $str ): string {
    $strarr = str2arr( $str );
    $str = implode( "<!---->", $strarr );
    return $str;
}

function removespecialchars( string $str ): string {
    $strarr = explode( "<!---->", $str );
    $str = implode( "", $strarr );
    $str = stripslashes( $str );
    return $str;
}

function str2arr( string $str ): array {
    $chararray = [];
    for( $i = 0; $i < strlen( $str ); $i++ ) {
        $chararray[] = $str[$i];
    }
    return $chararray;
}
