<?php
/**
 * Settings management for Smart Internal Links
 */

// Enable strict types
declare( strict_types=1 );

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Smart_Links_Settings Class
 */
class Smart_Links_Settings {

    /**
     * DB option name
     */
    private string $option_name = 'smart_internal_links';

    /**
     * Get all plugin settings
     *
     * @return array
     */
    public function get_settings(): array {
        $default_settings = $this->get_default_settings();
        $settings = get_option( $this->option_name, [] );

        // If no settings exist, return defaults
        if ( empty( $settings ) || ! is_array( $settings ) ) {
            return $default_settings;
        }

        // Merge with defaults, preserving existing values
        return array_merge( $default_settings, $settings );
    }

    /**
     * Get default settings
     *
     * @return array
     */
    public function get_default_settings(): array {
        return [
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
    }

    /**
     * Save settings
     *
     * @param array $data
     * @return array
     */
    public function save_settings( array $data ): array {
        // Get current settings to preserve any values not in the form
        $current_settings = $this->get_settings();

        // Update settings with form data, preserving structure
        $updated_settings = [
            'post'                           => $data['post'] ?? '',
            'postself'                       => $data['postself'] ?? '',
            'page'                           => $data['page'] ?? '',
            'pageself'                       => $data['pageself'] ?? '',
            'comment'                        => $data['comment'] ?? '',
            'excludeheading'                 => $data['excludeheading'] ?? '',
            'excludefigcaption'              => $data['excludefigcaption'] ?? '',
            'lposts'                         => $data['lposts'] ?? '',
            'lpages'                         => $data['lpages'] ?? '',
            'lcats'                          => $data['lcats'] ?? '',
            'ltags'                          => $data['ltags'] ?? '',
            'ignore'                         => isset( $data['ignore'] ) ? sanitize_textarea_field( $data['ignore'] ) : $current_settings['ignore'],
            'ignorepost'                     => isset( $data['ignorepost'] ) ? sanitize_textarea_field( $data['ignorepost'] ) : $current_settings['ignorepost'],
            'maxlinks'                       => isset( $data['maxlinks'] ) ? absint( $data['maxlinks'] ) : $current_settings['maxlinks'],
            'maxsingle'                      => isset( $data['maxsingle'] ) ? absint( $data['maxsingle'] ) : $current_settings['maxsingle'],
            'maxsingleurl'                   => isset( $data['maxsingleurl'] ) ? absint( $data['maxsingleurl'] ) : $current_settings['maxsingleurl'],
            'minusage'                       => isset( $data['minusage'] ) ? absint( $data['minusage'] ) : $current_settings['minusage'],
            'customkey'                      => isset( $data['customkey'] ) ? sanitize_textarea_field( $data['customkey'] ) : $current_settings['customkey'],
            'customkey_preventduplicatelink' => isset( $data['customkey_preventduplicatelink'] ),
            'nofoln'                         => $data['nofoln'] ?? '',
            'nofolo'                         => $data['nofolo'] ?? '',
            'blankn'                         => $data['blankn'] ?? '',
            'blanko'                         => $data['blanko'] ?? '',
            'onlysingle'                     => $data['onlysingle'] ?? '',
            'casesens'                       => $data['casesens'] ?? '',
            'allowfeed'                      => $data['allowfeed'] ?? '',
            // Preserve any other settings that might exist
            'notice'                         => $current_settings['notice'] ?? '1'
        ];

        // Merge with any other existing settings not handled above
        $final_settings = array_merge( $current_settings, $updated_settings );

        // Save settings
        update_option( $this->option_name, $final_settings );

        return $final_settings;
    }

    /**
     * Sanitize settings before saving
     *
     * @param array $settings
     * @return array
     */
    public function sanitize( array $settings ): array {
        // Get existing settings to merge with
        $existing_settings = get_option( $this->option_name, [] );

        // Start with existing settings
        $sanitized = is_array( $existing_settings ) ? $existing_settings : [];

        // Sanitize text fields
        if ( isset( $settings['ignore'] ) ) {
            $sanitized['ignore'] = sanitize_textarea_field( $settings['ignore'] );
        }

        if ( isset( $settings['ignorepost'] ) ) {
            $sanitized['ignorepost'] = sanitize_textarea_field( $settings['ignorepost'] );
        }

        if ( isset( $settings['customkey'] ) ) {
            $sanitized['customkey'] = sanitize_textarea_field( $settings['customkey'] );
        }

        // Sanitize numbers
        if ( isset( $settings['maxlinks'] ) ) {
            $sanitized['maxlinks'] = absint( $settings['maxlinks'] );
        }

        if ( isset( $settings['maxsingle'] ) ) {
            $sanitized['maxsingle'] = absint( $settings['maxsingle'] );
        }

        if ( isset( $settings['maxsingleurl'] ) ) {
            $sanitized['maxsingleurl'] = absint( $settings['maxsingleurl'] );
        }

        if ( isset( $settings['minusage'] ) ) {
            $sanitized['minusage'] = absint( $settings['minusage'] );
        }

        // Handle checkboxes - merge with existing
        $checkbox_fields = [
            'post', 'postself', 'page', 'pageself', 'comment',
            'excludeheading', 'excludefigcaption', 'lposts', 'lpages',
            'lcats', 'ltags', 'nofoln', 'nofolo', 'blankn', 'blanko',
            'onlysingle', 'casesens', 'allowfeed'
        ];

        foreach ( $checkbox_fields as $field ) {
            if ( isset( $settings[$field] ) ) {
                $sanitized[$field] = $settings[$field];
            }
        }

        // Handle boolean fields
        if ( isset( $settings['customkey_preventduplicatelink'] ) ) {
            $sanitized['customkey_preventduplicatelink'] = (bool) $settings['customkey_preventduplicatelink'];
        }

        return $sanitized;
    }

    /**
     * Get settings fields with their values
     *
     * @return array
     */
    public function get_settings_fields(): array {
        $settings = $this->get_settings();

        return [
            'post'                           => $settings['post'] == 'on' ? 'checked' : '',
            'postself'                       => $settings['postself'] == 'on' ? 'checked' : '',
            'page'                           => $settings['page'] == 'on' ? 'checked' : '',
            'pageself'                       => $settings['pageself'] == 'on' ? 'checked' : '',
            'comment'                        => $settings['comment'] == 'on' ? 'checked' : '',
            'excludeheading'                 => $settings['excludeheading'] == 'on' ? 'checked' : '',
            'excludefigcaption'              => $settings['excludefigcaption'] == 'on' ? 'checked' : '',
            'lposts'                         => $settings['lposts'] == 'on' ? 'checked' : '',
            'lpages'                         => $settings['lpages'] == 'on' ? 'checked' : '',
            'lcats'                          => $settings['lcats'] == 'on' ? 'checked' : '',
            'ltags'                          => $settings['ltags'] == 'on' ? 'checked' : '',
            'ignore'                         => $settings['ignore'],
            'ignorepost'                     => $settings['ignorepost'],
            'maxlinks'                       => $settings['maxlinks'],
            'maxsingle'                      => $settings['maxsingle'],
            'maxsingleurl'                   => $settings['maxsingleurl'],
            'minusage'                       => $settings['minusage'],
            'customkey'                      => stripslashes( $settings['customkey'] ),
            'customkey_preventduplicatelink' => $settings['customkey_preventduplicatelink'] ? 'checked' : '',
            'nofoln'                         => $settings['nofoln'] == 'on' ? 'checked' : '',
            'nofolo'                         => $settings['nofolo'] == 'on' ? 'checked' : '',
            'blankn'                         => $settings['blankn'] == 'on' ? 'checked' : '',
            'blanko'                         => $settings['blanko'] == 'on' ? 'checked' : '',
            'onlysingle'                     => $settings['onlysingle'] == 'on' ? 'checked' : '',
            'casesens'                       => $settings['casesens'] == 'on' ? 'checked' : '',
            'allowfeed'                      => $settings['allowfeed'] == 'on' ? 'checked' : '',
        ];
    }
}
