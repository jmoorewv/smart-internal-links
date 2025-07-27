<?php
/**
 * Content processor for Smart Internal Links
 */

// Enable strict types
declare( strict_types=1 );

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Smart_Links_Content_Processor Class
 */
class Smart_Links_Content_Processor {

    /**
     * Plugin options
     */
    private array $options;

    /**
     * Constructor
     *
     * @param array $options
     */
    public function __construct( array $options ) {
        $this->options = $options;
    }

    /**
     * Helper function to check if self-links are allowed for the current post type
     *
     * @param string $post_type
     * @return bool
     */
    private function allow_self_links( string $post_type ): bool {
        if ( $post_type === 'post' && ! empty( $this->options['postself'] ) ) {
            return true;
        }

        if ( $post_type === 'page' && ! empty( $this->options['pageself'] ) ) {
            return true;
        }

        return false;
    }

    /**
     * Process text content for automatic linking
     *
     * @param mixed $text The content to process
     * @param bool $is_comment Whether this is comment text
     * @return string Processed content
     */
    public function process_text( $text, bool $is_comment ): string {
        // Ultra-defensive input handling
        if ( $text === null || $text === false ) {
            return '';
        }
        
        if ( ! is_string( $text ) && ! is_numeric( $text ) ) {
            return '';
        }
        
        $text = (string) $text;
        
        if ( $text === '' || strlen( $text ) === 0 ) {
            return $text;
        }

        // Skip processing if we're in admin or doing AJAX
        if ( is_admin() || ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {
            return $text;
        }

        // Skip if this appears to be page builder content
        if ( strpos( $text, '[vc_' ) !== false || strpos( $text, '[tdb_' ) !== false || strpos( $text, '[tdc_' ) !== false ) {
            return $text;
        }

        global $wpdb, $post;

        // Basic setup
        $links = 0;
        $maxlinks = isset( $this->options['maxlinks'] ) && intval( $this->options['maxlinks'] ) > 0 ? intval( $this->options['maxlinks'] ) : 3;
        $maxsingle = isset( $this->options['maxsingle'] ) && intval( $this->options['maxsingle'] ) > 0 ? intval( $this->options['maxsingle'] ) : 1;

        // Simple feed check
        if ( is_feed() && empty( $this->options['allowfeed'] ) ) {
            return $text;
        }

        // Simple single post check
        if ( ! empty( $this->options['onlysingle'] ) && ! ( is_single() || is_page() ) ) {
            return $text;
        }

        // Check post type permissions
        if ( ! $is_comment && isset( $post ) && is_object( $post ) ) {
            if ( $post->post_type == 'post' && empty( $this->options['post'] ) ) {
                return $text;
            }
            if ( $post->post_type == 'page' && empty( $this->options['page'] ) ) {
                return $text;
            }
        }

        // Get ignore list
        $arrignore = [];
        if ( ! empty( $this->options['ignore'] ) ) {
            $ignore_parts = explode( '|', $this->options['ignore'] );
            foreach ( $ignore_parts as $part ) {
                $trimmed = trim( $part );
                if ( ! empty( $trimmed ) ) {
                    $arrignore[] = $trimmed;
                }
            }
        }

        // Simple case sensitivity setup
        $strpos_fnc = empty( $this->options['casesens'] ) ? 'stripos' : 'strpos';
        
        // Add spaces for word boundary detection
        $text = " $text ";

        // Process heading exclusions safely
        if ( ! empty( $this->options['excludeheading'] ) ) {
            $text = preg_replace_callback( 
                '/(<h[1-6][^>]*>)(.*?)(<\/h[1-6]>)/i', 
                function( $matches ) {
                    return $matches[1] . '<!--HEADING_PROTECTED-->' . $matches[2] . '<!--/HEADING_PROTECTED-->' . $matches[3];
                }, 
                $text 
            );
        }

        // Process caption exclusions safely - BEFORE any other attribute protection
        $caption_placeholders = [];
        $caption_counter = 0;
        if ( ! empty( $this->options['excludefigcaption'] ) ) {
            // Replace ALL content inside caption shortcodes with placeholders
            $text = preg_replace_callback( 
                '/(\[caption[^\]]*\])(.*?)(\[\/caption\])/s', 
                function( $matches ) use ( &$caption_placeholders, &$caption_counter ) {
                    $placeholder = '<!--CAPTION_PLACEHOLDER_' . $caption_counter . '-->';
                    // Store entire caption with ALL its original content protected
                    $caption_placeholders[$caption_counter] = $matches[0];
                    $caption_counter++;
                    return $placeholder;
                }, 
                $text 
            );
        }

        // Protect HTML attributes (alt text, title text, etc.) from linking - AFTER caption protection
        $attribute_placeholders = [];
        $attribute_counter = 0;
        $text = preg_replace_callback(
            '/(alt|title|data-[^=]*)\s*=\s*["\']([^"\']*)["\']/',
            function( $matches ) use ( &$attribute_placeholders, &$attribute_counter ) {
                $placeholder = '<!--ATTR_PLACEHOLDER_' . $attribute_counter . '-->';
                $attribute_placeholders[$attribute_counter] = $matches[0];
                $attribute_counter++;
                return $placeholder;
            },
            $text
        );

        // Process custom keywords first (safest and most important)
        if ( ! empty( $this->options['customkey'] ) ) {
            $kw_array = [];
            
            $lines = explode( "\n", $this->options['customkey'] );
            foreach ( $lines as $line ) {
                $line = trim( $line );
                if ( empty( $line ) ) {
                    continue;
                }
                
                $parts = explode( '|', $line );
                if ( count( $parts ) < 2 ) {
                    continue;
                }
                
                $url = trim( array_pop( $parts ) );
                
                foreach ( $parts as $keyword ) {
                    $keyword = trim( $keyword );
                    if ( ! empty( $keyword ) && ! in_array( strtolower( $keyword ), array_map( 'strtolower', $arrignore ) ) ) {
                        $kw_array[$keyword] = $url;
                    }
                }
            }

            foreach ( $kw_array as $keyword => $url ) {
                if ( $maxlinks > 0 && $links >= $maxlinks ) {
                    break;
                }

                if ( $strpos_fnc( $text, $keyword ) !== false ) {
                    // Skip if keyword is in protected heading
                    if ( strpos( $text, '<!--HEADING_PROTECTED-->' . $keyword . '<!--/HEADING_PROTECTED-->' ) !== false ) {
                        continue;
                    }
                    
                    // Simple word boundary replacement
                    $pattern = empty( $this->options['casesens'] ) 
                        ? '/\b' . preg_quote( $keyword, '/' ) . '\b/i'
                        : '/\b' . preg_quote( $keyword, '/' ) . '\b/';
                    
                    $replacement = '<a href="' . esc_url( $url ) . '">$0</a>';
                    
                    $newtext = preg_replace( $pattern, $replacement, $text, $maxsingle );
                    
                    if ( $newtext && $newtext !== $text ) {
                        $text = $newtext;
                        $links++;
                    }
                }
            }
        }

        if ( ( ! empty( $this->options['lposts'] ) || ! empty( $this->options['lpages'] ) ) && $links < $maxlinks ) {
            // Process posts in chunks to handle large content libraries
            $posts = wp_cache_get( 'smart-links-posts-full', 'smart-internal-links' );
            
            if ( ! $posts ) {
                try {
                    // Get all published posts, no date restrictions
                    $query = $wpdb->prepare( 
                        "SELECT post_title, ID, post_type FROM $wpdb->posts 
                        WHERE post_status = %s 
                        AND LENGTH(post_title) > 3 
                        ORDER BY LENGTH(post_title) DESC",
                        'publish'
                    );
                    $posts = $wpdb->get_results( $query );
                    
                    if ( ! is_array( $posts ) ) {
                        $posts = [];
                    }
                    
                    // Cache for 24 hours
                    wp_cache_add( 'smart-links-posts-full', $posts, 'smart-internal-links', 86400 );
                } catch ( Exception $e ) {
                    $posts = [];
                }
            }

            $current_id = isset( $post->ID ) ? $post->ID : 0;

            if ( is_array( $posts ) && ! empty( $posts ) ) {
                // Process posts in chunks to avoid memory issues
                $chunk_size = 100;
                $processed_count = 0;
                
                for ( $chunk_start = 0; $chunk_start < count( $posts ); $chunk_start += $chunk_size ) {
                    $chunk = array_slice( $posts, $chunk_start, $chunk_size );
                    
                    foreach ( $chunk as $postitem ) {
                        if ( ! is_object( $postitem ) || ! isset( $postitem->post_title, $postitem->ID, $postitem->post_type ) ) {
                            continue;
                        }

                        if ( $maxlinks > 0 && $links >= $maxlinks ) {
                            break 2; // Break out of both loops
                        }

                        // Check if we should process this post type
                        $process_post = false;
                        if ( $postitem->post_type == 'post' && ! empty( $this->options['lposts'] ) ) {
                            $process_post = true;
                        }
                        if ( $postitem->post_type == 'page' && ! empty( $this->options['lpages'] ) ) {
                            $process_post = true;
                        }

                        if ( ! $process_post ) {
                            continue;
                        }

                        // Skip self-links unless explicitly allowed
                        if ( $postitem->ID == $current_id ) {
                            $allow_self = false;
                            if ( $postitem->post_type == 'post' && ! empty( $this->options['postself'] ) ) {
                                $allow_self = true;
                            }
                            if ( $postitem->post_type == 'page' && ! empty( $this->options['pageself'] ) ) {
                                $allow_self = true;
                            }
                            if ( ! $allow_self ) {
                                continue;
                            }
                        }

                        // Skip if title is in ignore list
                        $post_title_check = empty( $this->options['casesens'] ) 
                            ? strtolower( $postitem->post_title ) 
                            : $postitem->post_title;

                        if ( in_array( $post_title_check, array_map( 'strtolower', $arrignore ) ) ) {
                            continue;
                        }

                        if ( $strpos_fnc( $text, $postitem->post_title ) !== false ) {
                            // Skip if title is in protected heading
                            if ( strpos( $text, '<!--HEADING_PROTECTED-->' . $postitem->post_title . '<!--/HEADING_PROTECTED-->' ) !== false ) {
                                continue;
                            }
                            
                            $pattern = empty( $this->options['casesens'] ) 
                                ? '/(?!(?:[^<\[]+[>\]]|[^>\]]+<\/a>))\b' . preg_quote( $postitem->post_title, '/' ) . '\b/i'
                                : '/(?!(?:[^<\[]+[>\]]|[^>\]]+<\/a>))\b' . preg_quote( $postitem->post_title, '/' ) . '\b/';
                            
                            $url = get_permalink( $postitem->ID );
                            if ( ! $url ) {
                                continue;
                            }
                            
                            $replacement = '<a title="' . esc_attr( $postitem->post_title ) . '" href="' . esc_url( $url ) . '">$0</a>';
                            
                            $newtext = preg_replace( $pattern, $replacement, $text, $maxsingle );
                            
                            if ( $newtext && $newtext !== $text ) {
                                $text = $newtext;
                                $links++;
                                $processed_count++;
                            }
                        }
                    }
                }
            }
        }

        // Clean up heading protection and restore content in correct order
        if ( ! empty( $this->options['excludeheading'] ) ) {
            $text = str_replace( ['<!--HEADING_PROTECTED-->', '<!--/HEADING_PROTECTED-->'], '', $text );
        }
        
        // Restore HTML attributes first (for content outside captions)
        if ( ! empty( $attribute_placeholders ) ) {
            for ( $i = 0; $i < $attribute_counter; $i++ ) {
                $placeholder = '<!--ATTR_PLACEHOLDER_' . $i . '-->';
                $text = str_replace( $placeholder, $attribute_placeholders[$i], $text );
            }
        }
        
        // Restore captions LAST - they contain their original attributes untouched
        if ( ! empty( $this->options['excludefigcaption'] ) && ! empty( $caption_placeholders ) ) {
            for ( $i = 0; $i < $caption_counter; $i++ ) {
                $placeholder = '<!--CAPTION_PLACEHOLDER_' . $i . '-->';
                $text = str_replace( $placeholder, $caption_placeholders[$i], $text );
            }
        }

        return trim( $text );
    }

    /**
     * Helper function to explode and trim values
     *
     * @param string $separator
     * @param string $text
     * @return array
     */
    private function explode_trim( string $separator, string $text ): array {
        if ( empty( $text ) ) {
            return [];
        }

        $arr = explode( $separator, $text );
        $ret = [];

        foreach ( $arr as $e ) {
            $ret[] = trim( $e );
        }

        return $ret;
    }
}
