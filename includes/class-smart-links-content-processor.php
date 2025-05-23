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
     * @param string $text The content to process
     * @param bool $is_comment Whether this is comment text
     * @return string Processed content
     */
    public function process_text( string $text, bool $is_comment ): string {
        global $wpdb, $post;

        $links = 0;

        // First check if this is a feed
        if ( is_feed() ) {
            // If we should process feeds, continue processing
            // If not, return the text unprocessed
            if ( empty( $this->options['allowfeed'] ) ) {
                return $text;
            }
        }
        // If not a feed, check for single posts/pages option
        else if ( ! empty( $this->options['onlysingle'] ) && ! ( is_single() || is_page() ) ) {
            return $text;
        }

        // Check for ignored posts
        $ignore_post_array = [];
        if ( ! empty( $this->options['ignorepost'] ) ) {
            $ignore_post_array = $this->explode_trim( "|", $this->options['ignorepost'] );
        }

        if ( is_page( $ignore_post_array ) || is_single( $ignore_post_array ) ) {
            return $text;
        }

        // Set up variables to prevent self-linking
        $current_post_id = isset( $post->ID ) ? $post->ID : 0;
        $current_post_url = $current_post_id ? trailingslashit( get_permalink( $current_post_id ) ) : '';
        $current_post_title = '';

        if ( isset( $post->post_title ) ) {
            $current_post_title = empty( $this->options['casesens'] ) ?
                strtolower( $post->post_title ) : $post->post_title;
        }

        // For non-comments, check post/page specific conditions
        if ( ! $is_comment && isset( $post ) ) {
            if ( $post->post_type == 'post' && empty( $this->options['post'] ) ) {
                return $text;
            }

            if ( $post->post_type == 'page' && empty( $this->options['page'] ) ) {
                return $text;
            }
        }

        // Set up processing variables
        $maxlinks = isset( $this->options['maxlinks'] ) && intval( $this->options['maxlinks'] ) > 0 ?
            intval( $this->options['maxlinks'] ) : 0;

        $maxsingle = isset( $this->options['maxsingle'] ) && intval( $this->options['maxsingle'] ) > 0 ?
            intval( $this->options['maxsingle'] ) : -1;

        $maxsingleurl = isset( $this->options['maxsingleurl'] ) && intval( $this->options['maxsingleurl'] ) > 0 ?
            intval( $this->options['maxsingleurl'] ) : 0;

        $minusage = isset( $this->options['minusage'] ) && intval( $this->options['minusage'] ) > 0 ?
            intval( $this->options['minusage'] ) : 1;

        $urls = [];
        $arrignore = [];

        if ( ! empty( $this->options['ignore'] ) ) {
            $arrignore = $this->explode_trim( "|", $this->options['ignore'] );
        }

        // Process exclude heading
        if ( ! empty( $this->options['excludeheading'] ) ) {
            // Here insert special characters for headings
            $text = preg_replace_callback( '%(<h.*?>)(.*?)(</h.*?>)%si', function( $matches ) {
                return $matches[1] . insertspecialchars( $matches[2] ) . $matches[3];
            }, $text );
        }

        // Handle caption shortcodes
        if ( ! empty( $this->options['excludefigcaption'] ) ) {
            // Save caption shortcodes to prevent processing their contents
            $caption_pattern = '/\[caption.*?\](.*?)\[\/caption\]/s';
            $text = preg_replace_callback( $caption_pattern,
                function( $matches ) {
                    // Extract the image link part from the caption content
                    $content = $matches[1];
                    $img_pattern = '/(<a[^>]*>.*?<\/a>)(.*)/s';

                    if ( preg_match( $img_pattern, $content, $img_matches ) ) {
                        // First part is the image link, second part is the caption text
                        $img_link = $img_matches[1];
                        $caption_text = $img_matches[2];

                        // Special encode the caption text to prevent linkification
                        $encoded_text = insertspecialchars( $caption_text );

                        // Return the shortcode with protected caption text
                        return '[caption' . strstr( $matches[0], ']', true ) . ']'
                            . $img_link . $encoded_text . '[/caption]';
                    }

                    // If we couldn't parse it properly, return as is
                    return $matches[0];
                },
                $text
            );
        }

        $figcaptions = [];
        $figcaption_count = 0;

        if ( ! empty( $this->options['excludefigcaption'] ) ) {
            $text = preg_replace_callback( '%(<figcaption[^>]*>)(.*?)(</figcaption>)%si',
                function( $matches ) use ( &$figcaptions, &$figcaption_count ) {
                    $placeholder = "<!--FIGCAPTION_PLACEHOLDER_" . $figcaption_count . "-->";
                    $figcaptions[$figcaption_count] = $matches[0]; // Store the complete figcaption
                    $figcaption_count++;
                    return $placeholder;
                },
                $text
            );
        }

        // Prepare regexes based on case sensitivity
        $reg_post = empty( $this->options['casesens'] ) ?
            '/(?!(?:[^<\[]+[>\]]|[^>\]]+<\/a>))($name)/imsU' :
            '/(?!(?:[^<\[]+[>\]]|[^>\]]+<\/a>))($name)/msU';

        $reg = empty( $this->options['casesens'] ) ?
            '/(?!(?:[^<\[]+[>\]]|[^>\]]+<\/a>))\b($name)\b/imsU' :
            '/(?!(?:[^<\[]+[>\]]|[^>\]]+<\/a>))\b($name)\b/msU';

        $strpos_fnc = empty( $this->options['casesens'] ) ? 'stripos' : 'strpos';
        $text = " $text ";

        // Process posts and pages
        if ( ! empty( $this->options['lposts'] ) || ! empty( $this->options['lpages'] ) ) {

            if ( ! $posts = wp_cache_get( 'smart-links-posts', 'smart-internal-links' ) ) {
                $query = "SELECT post_title, ID, post_type FROM $wpdb->posts WHERE post_status = '%s' AND LENGTH(post_title) > 3 ORDER BY LENGTH(post_title) DESC";
                $query = $wpdb->prepare( $query, 'publish' );
                $posts = $wpdb->get_results( $query );

                wp_cache_add( 'smart-links-posts', $posts, 'smart-internal-links', 86400 );
            }

            $current_id = isset( $post->ID ) ? $post->ID : 0;

            foreach ( $posts as $postitem ) {
                // Skip if we've reached max links
                if ( $maxlinks > 0 && $links >= $maxlinks ) {
                    break;
                }

                // Skip if post type doesn't match enabled settings
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

                // Skip if title is in ignore list
                $post_title = empty( $this->options['casesens'] ) ?
                    strtolower( $postitem->post_title ) : $postitem->post_title;

                if ( in_array( $post_title, $arrignore ) ) {
                    continue;
                }

                // Check for self-link
                if ( $postitem->ID == $current_id ) {
                    // Skip if self-links aren't allowed for this post type
                    if ( ! $this->allow_self_links( $postitem->post_type ) ) {
                        continue;
                    }
                }

                $text = str_replace( "&amp;", "&", $text );

                if ( $strpos_fnc( $text, $postitem->post_title ) !== false ) {
                    $name = preg_quote( $postitem->post_title, '/' );
                    $regexp = str_replace( '$name', $name, $reg );
                    $replace = '<a title="$1" href="$$$url$$$">$1</a>';
                    $newtext = preg_replace( $regexp, $replace, $text, $maxsingle );

                    if ( $newtext != $text ) {
                        $url = get_permalink( $postitem->ID );
                        $links++;
                        $text = str_replace( '$$$url$$$', trim( $url ), $newtext );
                    }
                }
            }
        }

        // Process categories
        if ( ! empty( $this->options['lcats'] ) ) {
            if ( ! $categories = wp_cache_get( 'smart-links-categories', 'smart-internal-links' ) ) {
                $query = "SELECT $wpdb->terms.name, $wpdb->terms.term_id FROM $wpdb->terms
                          LEFT JOIN $wpdb->term_taxonomy ON $wpdb->terms.term_id = $wpdb->term_taxonomy.term_id
                          WHERE $wpdb->term_taxonomy.taxonomy = '%s'
                          AND LENGTH($wpdb->terms.name) > 3
                          AND $wpdb->term_taxonomy.count >= %d
                          ORDER BY LENGTH($wpdb->terms.name) DESC";

                $query = $wpdb->prepare( $query, 'category', $minusage );
                $categories = $wpdb->get_results( $query );

                wp_cache_add( 'smart-links-categories', $categories, 'smart-internal-links', 86400 );
            }

            foreach ( $categories as $cat ) {
                // Skip if we've reached max links
                if ( $maxlinks > 0 && $links >= $maxlinks ) {
                    break;
                }

                $cat_name = empty( $this->options['casesens'] ) ?
                    strtolower( $cat->name ) : $cat->name;

                // Skip if in ignore list
                if ( in_array( $cat_name, $arrignore ) ) {
                    continue;
                }

                if ( $strpos_fnc( $text, $cat->name ) !== false ) {
                    $name = preg_quote( $cat->name, '/' );
                    $regexp = str_replace( '$name', $name, $reg );
                    $replace = '<a title="$1" href="$$$url$$$">$1</a>';
                    $newtext = preg_replace( $regexp, $replace, $text, $maxsingle );

                    if ( $newtext != $text ) {
                        $url = get_category_link( $cat->term_id );
                        $links++;
                        $text = str_replace( '$$$url$$$', trim( $url ), $newtext );
                    }
                }
            }
        }

        // Process tags
        if ( ! empty( $this->options['ltags'] ) ) {
            if ( ! $tags = wp_cache_get( 'smart-links-tags', 'smart-internal-links' ) ) {
                $query = "SELECT $wpdb->terms.name, $wpdb->terms.term_id FROM $wpdb->terms
                          LEFT JOIN $wpdb->term_taxonomy ON $wpdb->terms.term_id = $wpdb->term_taxonomy.term_id
                          WHERE $wpdb->term_taxonomy.taxonomy = '%s'
                          AND LENGTH($wpdb->terms.name) > 3
                          AND $wpdb->term_taxonomy.count >= %d
                          ORDER BY LENGTH($wpdb->terms.name) DESC";

                $query = $wpdb->prepare( $query, 'post_tag', $minusage );
                $tags = $wpdb->get_results( $query );

                wp_cache_add( 'smart-links-tags', $tags, 'smart-internal-links', 86400 );
            }

            foreach ( $tags as $tag ) {
                // Skip if we've reached max links
                if ( $maxlinks > 0 && $links >= $maxlinks ) {
                    break;
                }

                $tag_name = empty( $this->options['casesens'] ) ?
                    strtolower( $tag->name ) : $tag->name;

                // Skip if in ignore list
                if ( in_array( $tag_name, $arrignore ) ) {
                    continue;
                }

                if ( $strpos_fnc( $text, $tag->name ) !== false ) {
                    $name = preg_quote( $tag->name, '/' );
                    $regexp = str_replace( '$name', $name, $reg );
                    $replace = '<a title="$1" href="$$$url$$$">$1</a>';
                    $newtext = preg_replace( $regexp, $replace, $text, $maxsingle );

                    if ( $newtext != $text ) {
                        $url = get_tag_link( $tag->term_id );
                        $links++;
                        $text = str_replace( '$$$url$$$', trim( $url ), $newtext );
                    }
                }
            }
        }

        // Process custom keywords
        if ( ! empty( $this->options['customkey'] ) ) {
            $kw_array = [];

            // Process custom keywords from settings
            foreach ( explode( "\n", $this->options['customkey'] ) as $line ) {
                $line = trim( $line );

                if ( empty( $line ) ) {
                    continue;
                }

                $chunks = array_map( 'trim', explode( "|", $line ) );
                $chunks_count = count( $chunks );

                // Skip invalid lines
                if ( $chunks_count < 2 ) {
                    continue;
                }

                // The last chunk is always the URL
                $url = trim( $chunks[$chunks_count - 1] );

                // All previous chunks are keywords
                for ( $i = 0; $i < $chunks_count - 1; $i++ ) {
                    $keyword = trim( $chunks[$i] );
                    if ( ! empty( $keyword ) ) {
                        $kw_array[$keyword] = $url;
                    }
                }
            }

            foreach ( $kw_array as $name => $url ) {
                // Skip if we've reached max links
                if ( $maxlinks > 0 && $links >= $maxlinks ) {
                    break;
                }

                // Check if this is a self-link ( targeting the current post )
                if ( isset( $post ) && is_object( $post ) ) {
                    $current_url = trailingslashit( get_permalink( $post->ID ) );
                    $check_url = trailingslashit( trim( $url ) );

                    if ( trim( $check_url ) == trim( $current_url ) ) {
                        // This is a self-link, check if they're allowed
                        if ( ! $this->allow_self_links( $post->post_type ) ) {
                            continue; // Skip if self-links aren't allowed
                        }
                    }
                }

                if ( $strpos_fnc( $text, $name ) !== false ) {
                    $name = preg_quote( $name, '/' );
                    $regexp = str_replace( '$name', $name, $reg );
                    $replace = "<a title=\"$1\" href=\"" . trim( $url ) . "\">$1</a>";
                    $newtext = preg_replace( $regexp, $replace, $text, $maxsingle );

                    if ( $newtext != $text ) {
                        $links++;
                        $text = $newtext;
                    }
                }
            }
        }

        // Cleanup heading exclusions
        if ( ! empty( $this->options['excludeheading'] ) ) {
            $text = preg_replace_callback( '%(<h.*?>)(.*?)(</h.*?>)%si', function( $matches ) {
                return $matches[1] . removespecialchars( $matches[2] ) . $matches[3];
            }, $text );
        }

        // Restore caption shortcode text
        if ( ! empty( $this->options['excludefigcaption'] ) ) {
            // Find caption shortcodes and restore their original text
            $caption_pattern = '/\[caption.*?\](.*?)\[\/caption\]/s';
            $text = preg_replace_callback( $caption_pattern,
                function( $matches ) {
                    // Extract the content
                    $content = $matches[1];
                    $img_pattern = '/(<a[^>]*>.*?<\/a>)(.*)/s';

                    if ( preg_match( $img_pattern, $content, $img_matches ) ) {
                        // First part is the image link, second part is the caption text
                        $img_link = $img_matches[1];
                        $caption_text = $img_matches[2];

                        // Decode the special characters in the caption text
                        $decoded_text = removespecialchars( $caption_text );

                        // Return the shortcode with restored caption text
                        return '[caption' . strstr( $matches[0], ']', true ) . ']'
                               . $img_link . $decoded_text . '[/caption]';
                    }

                    // If we couldn't parse it properly, return as is
                    return $matches[0];
                },
                $text
            );
        }

        // Add new condition for figcaptions
        if ( ! empty( $this->options['excludefigcaption'] ) ) {
            // Here insert special characters for figcaptions
            $text = preg_replace_callback( '%(<figcaption[^>]*>)(.*?)(</figcaption>)%si', function( $matches ) {
                return $matches[1] . removespecialchars( $matches[2] ) . $matches[3];
            }, $text );
        }

        // Only call stripslashes once if either option is enabled
        if ( ! empty( $this->options['excludeheading'] ) || ! empty( $this->options['excludefigcaption'] ) ) {
            $text = stripslashes( $text );
        }

        // Restore all figcaptions
        if ( ! empty( $this->options['excludefigcaption'] ) && ! empty( $figcaptions ) ) {
            for ( $i = 0; $i < $figcaption_count; $i++ ) {
                $text = str_replace( "<!--FIGCAPTION_PLACEHOLDER_" . $i . "-->", $figcaptions[$i], $text );
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
