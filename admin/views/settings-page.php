<?php
/**
 * Admin settings page template
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Get settings fields
$fields = $this->settings->get_settings_fields();
?>

<div class="wrap smart-links-settings">
    <div class="smart-links-header">
        <h1><?php _e( 'Smart Internal Links', 'smart-internal-links' ); ?></h1>
        <p class="description">
            <?php _e( 'Configure how your content is automatically interlinked to improve SEO and user experience.', 'smart-internal-links' ); ?>
        </p>
    </div>

    <form method="post" action="">
        <?php wp_nonce_field( 'smart_internal_links_nonce' ); ?>

        <div class="smart-links-container">
            <div class="smart-links-sidebar">
                <ul class="smart-links-tabs">
                    <li class="active"><a href="#content-types"><?php _e( 'Content Types', 'smart-internal-links' ); ?></a></li>
                    <li><a href="#target-links"><?php _e( 'Target Links', 'smart-internal-links' ); ?></a></li>
                    <li><a href="#exclusions"><?php _e( 'Exclusions', 'smart-internal-links' ); ?></a></li>
                    <li><a href="#settings"><?php _e( 'Settings', 'smart-internal-links' ); ?></a></li>
                    <li><a href="#limits"><?php _e( 'Limits', 'smart-internal-links' ); ?></a></li>
                    <li><a href="#ignore-rules"><?php _e( 'Ignore Rules', 'smart-internal-links' ); ?></a></li>
                    <li><a href="#custom-keywords"><?php _e( 'Custom Keywords', 'smart-internal-links' ); ?></a></li>
                    <li><a href="#external-links"><?php _e( 'External Links', 'smart-internal-links' ); ?></a></li>
                </ul>
            </div>

            <div class="smart-links-content">
                <!-- Content Types -->
                <div id="content-types" class="smart-links-tab-content active">
                    <div class="smart-links-card">
                        <h2><?php _e( 'Content Types', 'smart-internal-links' ); ?></h2>
                        <p class="section-description">
                            <?php _e( 'Select which content types should be processed for automatic internal linking.', 'smart-internal-links' ); ?>
                        </p>

                        <div class="smart-links-field-group">
                            <div class="field-row with-subfield">
                                <label class="toggle-switch">
                                    <input type="checkbox" name="post" <?php echo esc_attr( $fields['post'] ); ?> />
                                    <span class="slider"></span>
                                    <span class="label-text"><?php _e( 'Posts', 'smart-internal-links' ); ?></span>
                                </label>

                                <div class="subfield">
                                    <label class="toggle-switch">
                                        <input type="checkbox" name="postself" <?php echo esc_attr( $fields['postself'] ); ?> />
                                        <span class="slider"></span>
                                        <span class="label-text"><?php _e( 'Allow links to self', 'smart-internal-links' ); ?></span>
                                    </label>
                                </div>
                            </div>

                            <div class="field-row with-subfield">
                                <label class="toggle-switch">
                                    <input type="checkbox" name="page" <?php echo esc_attr( $fields['page'] ); ?> />
                                    <span class="slider"></span>
                                    <span class="label-text"><?php _e( 'Pages', 'smart-internal-links' ); ?></span>
                                </label>

                                <div class="subfield">
                                    <label class="toggle-switch">
                                        <input type="checkbox" name="pageself" <?php echo esc_attr( $fields['pageself'] ); ?> />
                                        <span class="slider"></span>
                                        <span class="label-text"><?php _e( 'Allow links to self', 'smart-internal-links' ); ?></span>
                                    </label>
                                </div>
                            </div>

                            <div class="field-row">
                                <label class="toggle-switch">
                                    <input type="checkbox" name="comment" <?php echo esc_attr( $fields['comment'] ); ?> />
                                    <span class="slider"></span>
                                    <span class="label-text"><?php _e( 'Comments', 'smart-internal-links' ); ?></span>
                                </label>
                                <span class="tooltip" title="<?php _e( 'Processing comments may slow down your site performance', 'smart-internal-links' ); ?>">?</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Target Links -->
                <div id="target-links" class="smart-links-tab-content">
                    <div class="smart-links-card">
                        <h2><?php _e( 'Target Links', 'smart-internal-links' ); ?></h2>
                        <p class="section-description">
                            <?php _e( 'The targets SEO Internal links should consider. The match will be based on post/page title or category/tag name, case insensitive.', 'smart-internal-links' ); ?>
                        </p>

                        <div class="smart-links-field-group">
                            <div class="field-row">
                                <label class="toggle-switch">
                                    <input type="checkbox" name="lposts" <?php echo esc_attr( $fields['lposts'] ); ?> />
                                    <span class="slider"></span>
                                    <span class="label-text"><?php _e( 'Posts', 'smart-internal-links' ); ?></span>
                                </label>
                            </div>

                            <div class="field-row">
                                <label class="toggle-switch">
                                    <input type="checkbox" name="lpages" <?php echo esc_attr( $fields['lpages'] ); ?> />
                                    <span class="slider"></span>
                                    <span class="label-text"><?php _e( 'Pages', 'smart-internal-links' ); ?></span>
                                </label>
                            </div>

                            <div class="field-row">
                                <label class="toggle-switch">
                                    <input type="checkbox" name="lcats" <?php echo esc_attr( $fields['lcats'] ); ?> />
                                    <span class="slider"></span>
                                    <span class="label-text"><?php _e( 'Categories', 'smart-internal-links' ); ?></span>
                                </label>
                                <span class="tooltip" title="<?php _e( 'May slow down performance', 'smart-internal-links' ); ?>">?</span>
                            </div>

                            <div class="field-row">
                                <label class="toggle-switch">
                                    <input type="checkbox" name="ltags" <?php echo esc_attr( $fields['ltags'] ); ?> />
                                    <span class="slider"></span>
                                    <span class="label-text"><?php _e( 'Tags', 'smart-internal-links' ); ?></span>
                                </label>
                                <span class="tooltip" title="<?php _e( 'May slow down performance', 'smart-internal-links' ); ?>">?</span>
                            </div>

                            <div class="field-row">
                                <label>
                                    <span class="label-text"><?php _e( 'Minimum usage:', 'smart-internal-links' ); ?></span>
                                    <input type="number" name="minusage" value="<?php echo esc_attr( $fields['minusage'] ); ?>" min="0" />
                                </label>
                                <span class="tooltip" title="<?php _e( 'Minimum number of times a keyword must be used to create a link', 'smart-internal-links' ); ?>">?</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Exclusions -->
                <div id="exclusions" class="smart-links-tab-content">
                    <div class="smart-links-card">
                        <h2><?php _e( 'Exclusions', 'smart-internal-links' ); ?></h2>
                        <p class="section-description">
                            <?php _e( 'Set rules to prevent linking in specific content areas.', 'smart-internal-links' ); ?>
                        </p>

                        <div class="smart-links-field-group">
                            <div class="field-row">
                                <label class="toggle-switch">
                                    <input type="checkbox" name="excludeheading" <?php echo esc_attr( $fields['excludeheading'] ); ?> />
                                    <span class="slider"></span>
                                    <span class="label-text"><?php _e( 'Prevent linking in heading tags (h1-h6)', 'smart-internal-links' ); ?></span>
                                </label>
                            </div>

                            <div class="field-row">
                                <label class="toggle-switch">
                                    <input type="checkbox" name="excludefigcaption" <?php echo esc_attr( $fields['excludefigcaption'] ); ?> />
                                    <span class="slider"></span>
                                    <span class="label-text"><?php _e( 'Prevent linking in figure captions', 'smart-internal-links' ); ?></span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Settings -->
                <div id="settings" class="smart-links-tab-content">
                    <div class="smart-links-card">
                        <h2><?php _e( 'Settings', 'smart-internal-links' ); ?></h2>
                        <p class="section-description">
                            <?php _e( 'Configure general behavior settings for the automatic linking process.', 'smart-internal-links' ); ?>
                        </p>

                        <div class="smart-links-field-group">
                            <div class="field-row">
                                <label class="toggle-switch">
                                    <input type="checkbox" name="onlysingle" <?php echo esc_attr( $fields['onlysingle'] ); ?> />
                                    <span class="slider"></span>
                                    <span class="label-text"><?php _e( 'Process only single posts and pages', 'smart-internal-links' ); ?></span>
                                </label>
                            </div>

                            <div class="field-row">
                                <label class="toggle-switch">
                                    <input type="checkbox" name="allowfeed" <?php echo esc_attr( $fields['allowfeed'] ); ?> />
                                    <span class="slider"></span>
                                    <span class="label-text"><?php _e( 'Process RSS feeds', 'smart-internal-links' ); ?></span>
                                </label>
                            </div>

                            <div class="field-row">
                                <label class="toggle-switch">
                                    <input type="checkbox" name="casesens" <?php echo esc_attr( $fields['casesens'] ); ?> />
                                    <span class="slider"></span>
                                    <span class="label-text"><?php _e( 'Case sensitive matching', 'smart-internal-links' ); ?></span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Limits -->
                <div id="limits" class="smart-links-tab-content">
                    <div class="smart-links-card">
                        <h2><?php _e( 'Limits', 'smart-internal-links' ); ?></h2>
                        <p class="section-description">
                            <?php _e( 'Set limits on the number of links that can be created.', 'smart-internal-links' ); ?>
                        </p>

                        <div class="smart-links-field-group">
                            <div class="field-row">
                                <label>
                                    <span class="label-text"><?php _e( 'Max Links per Post', 'smart-internal-links' ); ?></span>
                                    <input type="number" name="maxlinks" value="<?php echo esc_attr( $fields['maxlinks'] ); ?>" min="0" />
                                </label>
                                <span class="tooltip" title="<?php _e( 'Maximum number of different links per post. Set to 0 for no limit.', 'smart-internal-links' ); ?>">?</span>
                            </div>

                            <div class="field-row">
                                <label>
                                    <span class="label-text"><?php _e( 'Max Single', 'smart-internal-links' ); ?></span>
                                    <input type="number" name="maxsingle" value="<?php echo esc_attr( $fields['maxsingle'] ); ?>" min="0" />
                                </label>
                                <span class="tooltip" title="<?php _e( 'Maximum number of links created with the same keyword. Set to 0 for no limit.', 'smart-internal-links' ); ?>">?</span>
                            </div>

                            <div class="field-row">
                                <label>
                                    <span class="label-text"><?php _e( 'Max Single URLs', 'smart-internal-links' ); ?></span>
                                    <input type="number" name="maxsingleurl" value="<?php echo esc_attr( $fields['maxsingleurl'] ); ?>" min="0" />
                                </label>
                                <span class="tooltip" title="<?php _e( 'Limit number of same URLs the plugin will link to. Works only when Max Single above is set to 1. Set to 0 for no limit.', 'smart-internal-links' ); ?>">?</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ignore Rules -->
                <div id="ignore-rules" class="smart-links-tab-content">
                    <div class="smart-links-card">
                        <h2><?php _e( 'Ignore Rules', 'smart-internal-links' ); ?></h2>
                        <p class="section-description">
                            <?php _e( 'Configure specific content that should be excluded from the automatic linking process.', 'smart-internal-links' ); ?>
                        </p>

                        <div class="smart-links-field-group">
                            <div class="field-row textarea-field">
                                <label><?php _e( 'Ignore Posts and Pages:', 'smart-internal-links' ); ?></label>
                                <p class="field-description">
                                    <?php _e( 'You may wish to forbid automatic linking on certain posts or pages. Separate them by a pipe ( | ). (id | slug or name)', 'smart-internal-links' ); ?>
                                </p>
                                <textarea name="ignorepost" rows="5"><?php echo esc_textarea( $fields['ignorepost'] ); ?></textarea>
                            </div>

                            <div class="field-row textarea-field">
                                <label><?php _e( 'Ignore Keywords:', 'smart-internal-links' ); ?></label>
                                <p class="field-description">
                                    <?php _e( 'You may wish to ignore certain words or phrases from automatic linking. Separate them by a pipe ( | ).', 'smart-internal-links' ); ?>
                                </p>
                                <textarea name="ignore" rows="5"><?php echo esc_textarea( $fields['ignore'] ); ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Custom Keywords -->
                <div id="custom-keywords" class="smart-links-tab-content">
                    <div class="smart-links-card">
                        <h2><?php _e( 'Custom Keywords', 'smart-internal-links' ); ?></h2>
                        <p class="section-description">
                            <?php _e( 'Define custom keywords to automatically link to specific URLs.', 'smart-internal-links' ); ?>
                        </p>

                        <div class="smart-links-field-group">
                            <div class="field-row textarea-field">
                                <p class="field-description">
                                    <?php _e( 'Here you can enter manually the extra keywords you want to automatically link. Use a pipe ( | ) to separate keywords and add target url at the end. Use a new line for new url and set of keywords. You can have these keywords link to any url, not only your site.', 'smart-internal-links' ); ?>
                                </p>

                                <div class="example-box">
                                    <h4><?php _e( 'Example:', 'smart-internal-links' ); ?></h4>
                                    <code>
                                        jonathan moore | https://jmoorewv.com/about/<br>
                                        python | php | javascript | https://jmoorewv.com/category/guides/programming/
                                    </code>
                                </div>

                                <textarea name="customkey" rows="10"><?php echo esc_textarea( $fields['customkey'] ); ?></textarea>

                                <div class="field-row checkbox-field">
                                    <label class="toggle-switch">
                                        <input type="checkbox" name="customkey_preventduplicatelink" <?php echo esc_attr( $fields['customkey_preventduplicatelink'] ); ?> />
                                        <span class="slider"></span>
                                        <span class="label-text"><?php _e( 'Prevent duplicate links', 'smart-internal-links' ); ?></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- External Links -->
                <div id="external-links" class="smart-links-tab-content">
                    <div class="smart-links-card">
                        <h2><?php _e( 'External Links', 'smart-internal-links' ); ?></h2>
                        <p class="section-description">
                            <?php _e( 'Configure how external links should be handled.', 'smart-internal-links' ); ?>
                        </p>

                        <div class="smart-links-field-group">
                            <div class="field-row">
                                <label class="toggle-switch">
                                    <input type="checkbox" name="nofolo" <?php echo esc_attr( $fields['nofolo'] ); ?> />
                                    <span class="slider"></span>
                                    <span class="label-text"><?php _e( 'Add nofollow attribute', 'smart-internal-links' ); ?></span>
                                </label>
                            </div>

                            <div class="field-row">
                                <label class="toggle-switch">
                                    <input type="checkbox" name="blanko" <?php echo esc_attr( $fields['blanko'] ); ?> />
                                    <span class="slider"></span>
                                    <span class="label-text"><?php _e( 'Open in new window', 'smart-internal-links' ); ?></span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="smart-links-footer">
            <p class="keyboard-shortcut-tip"><?php _e( 'Tip: Use CTRL+S (or CMD+S on Mac) to quickly save your settings.', 'smart-internal-links' ); ?></p>
            <button type="submit" name="submit" value="<?php _e( 'Save Changes', 'smart-internal-links' ); ?>" class="button-primary"><?php _e( 'Save Changes', 'smart-internal-links' ); ?></button>
        </div>
    </form>
</div>
