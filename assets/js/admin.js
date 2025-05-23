/**
 * JavaScript for Smart Internal Links admin interface
 */
jQuery( document ).ready( function( $ ) {
    /**
     * Tab navigation functionality
     */
    function initTabs() {
        // Initial tab state from URL hash if present
        var initialTab = window.location.hash;
        if ( initialTab ) {
            switchToTab( initialTab );
        }

        // Tab click handler
        $( '.smart-links-tabs a' ).on( 'click', function( e ) {
            e.preventDefault();

            var targetId = $( this ).attr( 'href' );
            switchToTab( targetId );

            // Update URL hash for browser history
            window.location.hash = targetId;
        } );
    }

    /**
     * Switch to specified tab
     */
    function switchToTab( tabId ) {
        // Remove active class from all tabs and tab content
        $( '.smart-links-tabs li' ).removeClass( 'active' );
        $( '.smart-links-tab-content' ).removeClass( 'active' );

        // Add active class to selected tab and content
        $( '.smart-links-tabs a[href="' + tabId + '"]' ).parent().addClass( 'active' );
        $( tabId ).addClass( 'active' );
    }

    /**
     * Initialize tooltips
     */
    function initTooltips() {
        $( '.tooltip' ).hover( function() {
            // On hover, store the title and create tooltip
            var title = $( this ).attr( 'title' );
            if ( title ) {
                $( this ).data( 'tipText', title ).removeAttr( 'title' );
                $( '<p class="tooltip-box"></p>' )
                    .text( title )
                    .appendTo( 'body' )
                    .fadeIn( 'fast' );
            }
        }, function() {
            // On hover out, remove tooltip and restore title
            if ( $( this ).data( 'tipText' ) ) {
                $( this ).attr( 'title', $( this ).data( 'tipText' ) );
                $( '.tooltip-box' ).remove();
            }
        } ).mousemove( function( e ) {
            // Position tooltip near cursor
            var mousex = e.pageX + 20;
            var mousey = e.pageY + 10;
            $( '.tooltip-box' ).css( { top: mousey, left: mousex } );
        } );
    }

    /**
     * Form validation
     */
    function initFormValidation() {
        // The loading spinner
        var $loadingOverlay = $( '<div class="smart-links-loading"><span class="spinner is-active"></span> Saving settings...</div>' );

        $( '.smart-links-settings form' ).on( 'submit', function( e ) {
            // Validate number inputs
            var isValid = true;

            // Check if numbers are valid
            $( 'input[type="number"]' ).each( function() {
                var value = $( this ).val();
                var min = $( this ).attr( 'min' );

                if ( value === '' || isNaN( parseInt( value ) ) ) {
                    isValid = false;
                    $( this ).addClass( 'error' );
                } else if ( min !== undefined && parseInt( value ) < parseInt( min ) ) {
                    isValid = false;
                    $( this ).addClass( 'error' );
                } else {
                    $( this ).removeClass( 'error' );
                }
            } );

            if ( !isValid ) {
                e.preventDefault();
                alert( 'Please correct the highlighted fields before saving.' );
                return false;
            }

            // Show loading overlay if validation passed
            $loadingOverlay.appendTo( '.smart-links-settings' );

            // Show notification
            var $notification = $( '<div class="shortcut-notification">Settings are being saved...</div>' );
            $( 'body' ).append( $notification );

            setTimeout( function() {
                $notification.fadeOut( 300, function() {
                    $( this ).remove();
                } );
            }, 2000 );

            // Continue with form submission
            return true;
        } );
    }

    /**
     * Dependency handling for related options
     */
    function initDependencies() {
        // Toggle visibility of subfields based on parent field
        $( 'input[name="post"]' ).on( 'change', function() {
            if ( $( this ).is( ':checked' ) ) {
                $( 'input[name="postself"]' ).closest( '.subfield' ).slideDown( 200 );
            } else {
                $( 'input[name="postself"]' ).prop( 'checked', false ).closest( '.subfield' ).slideUp( 200 );
            }
        } ).trigger( 'change' );

        $( 'input[name="page"]' ).on( 'change', function() {
            if ( $( this ).is( ':checked' ) ) {
                $( 'input[name="pageself"]' ).closest( '.subfield' ).slideDown( 200 );
            } else {
                $( 'input[name="pageself"]' ).prop( 'checked', false ).closest( '.subfield' ).slideUp( 200 );
            }
        } ).trigger( 'change' );

        // Disable related options if parent is unchecked
        $( 'input[name="maxsingle"]' ).on( 'change', function() {
            var maxSingleValue = parseInt( $( this ).val() );
            var $maxSingleUrl = $( 'input[name="maxsingleurl"]' );

            if ( maxSingleValue === 1 ) {
                $maxSingleUrl.prop( 'disabled', false );
            } else {
                $maxSingleUrl.prop( 'disabled', true );
            }
        } ).trigger( 'change' );
    }

    /**
     * Show/hide custom keyword section tips
     */
    function initCustomKeywordsTips() {
        // Create a toggleable helper for custom keywords
        var $keywordsSection = $( '#custom-keywords' );
        var $exampleBox = $keywordsSection.find( '.example-box' );

        // Add toggle link
        $( '<a href="#" class="toggle-example">Show examples</a>' )
            .insertBefore( $exampleBox )
            .on( 'click', function( e ) {
                e.preventDefault();
                if ( $exampleBox.is( ':visible' ) ) {
                    $exampleBox.slideUp( 200 );
                    $( this ).text( 'Show examples' );
                } else {
                    $exampleBox.slideDown( 200 );
                    $( this ).text( 'Hide examples' );
                }
            } );

        // Initially hide the example box
        $exampleBox.hide();
    }

    /**
     * Save tab state between page loads
     */
    function saveTabState() {
        // When form is submitted, save current tab to localStorage
        $( '.smart-links-settings form' ).on( 'submit', function() {
            var currentTab = $( '.smart-links-tab-content.active' ).attr( 'id' );
            localStorage.setItem( 'seo_links_current_tab', currentTab );
        } );

        // If no hash in URL but localStorage has a value, use it
        if ( !window.location.hash && localStorage.getItem( 'seo_links_current_tab' ) ) {
            switchToTab( '#' + localStorage.getItem( 'seo_links_current_tab' ) );
        }
    }

    /**
     * Enable keyboard shortcuts
     */
    function initKeyboardShortcuts() {
        // Use CTRL+S (or Command+S on Mac) to save the settings
        $( document ).on( 'keydown', function( e ) {
            // Only capture this shortcut if we're on the settings page
            if ( !$( '.smart-links-settings' ).length ) {
                return true;
            }

            // Check if CTRL or Command key is pressed along with 'S'
            if ( ( e.ctrlKey || e.metaKey ) && ( e.key === 's' || e.key === 'S' ) ) {
                // This is crucial to prevent the browser's save dialog
                e.preventDefault();
                e.stopPropagation();

                // Show quick visual feedback
                $( '.button-primary' ).addClass( 'button-flash' );
                setTimeout( function() {
                    $( '.button-primary' ).removeClass( 'button-flash' );
                }, 200 );

                // Instead of directly submitting the form, click the save button
                // This ensures any click handlers attached to the button still work
                $( '.button-primary' ).trigger( 'click' );

                // Return false as an additional safeguard
                return false;
            }
        } );
    }

    /**
     * Initialize all functionality
     */
    function init() {
        initTabs();
        initTooltips();
        initFormValidation();
        initDependencies();
        initCustomKeywordsTips();
        saveTabState();
        initKeyboardShortcuts();

        // Add CSS for dynamic elements
        $( '<style>\
            .smart-links-loading {\
                position: fixed;\
                top: 0;\
                left: 0;\
                right: 0;\
                bottom: 0;\
                background: rgba(255,255,255,0.7);\
                z-index: 9999;\
                display: flex;\
                align-items: center;\
                justify-content: center;\
                flex-direction: column;\
            }\
            .smart-links-loading span.spinner {\
                float: none;\
                margin: 0 0 10px;\
            }\
            input.error {\
                border-color: #dc3232;\
                box-shadow: 0 0 2px rgba(220,50,50,.8);\
            }\
            .toggle-example {\
                display: inline-block;\
                margin-bottom: 10px;\
                text-decoration: none;\
                font-size: 13px;\
            }\
            .shortcut-notification {\
                position: fixed;\
                bottom: 20px;\
                right: 20px;\
                background: #2a2a2a;\
                color: white;\
                padding: 10px 15px;\
                border-radius: 4px;\
                box-shadow: 0 2px 8px rgba(0,0,0,.3);\
                z-index: 9999;\
                font-size: 13px;\
            }\
            .button-flash {\
                background: #4caf50 !important;\
                border-color: #388e3c !important;\
                transition: all 0.2s ease;\
            }\
        </style>' ).appendTo( 'head' );
    }

    // Run initialization
    init();
} );
