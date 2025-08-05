# Changelog

All notable changes to Smart Internal Links will be documented in this file.

Smart Internal Links is a complete rewrite of the original SEO Internal Links plugin by Pankaj Jha, which had not been updated in over 9 years and suffered from PHP compatibility issues and security vulnerabilities.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

### Version 3.0.3 - 2025-08-04

#### Fixed

- **CRITICAL**: Fixed Max Links per Post = 0 not being treated as unlimited
- **CRITICAL**: Fixed Max Single = 0 not being treated as unlimited

### Version 3.0.2 - 2025-07-31

#### Fixed

- **CRITICAL**: Fixed auto-linking inside existing link tags that broke HTML structure

### Version 3.0.1 - 2025-07-26

#### Fixed

- **CRITICAL**: Fixed fatal errors with td-composer/page builder themes causing null content processing
- **CRITICAL**: Fixed caption shortcode handling preventing critical errors when "Prevent linking in figure captions" enabled
- **CRITICAL**: Fixed alt text being linked inside caption shortcodes when caption protection enabled
- **CRITICAL**: Fixed content not loading when Posts content type enabled with complex page builder themes
- Rewritten content processor with enhanced error handling and page builder compatibility
- Added robust caption protection using placeholder system instead of complex regex patterns
- Enhanced HTML attribute protection (alt text, title attributes, data attributes) from automatic linking
- Improved processing order: captions protected before attribute processing to prevent conflicts
- Added chunked post processing for better performance with large content libraries
- Better error handling with try/catch blocks around database operations
- Enhanced null content handling throughout processing pipeline
- Fixed settings preservation during plugin updates (carried over from 3.0.0 improvements)

#### Changed

- Improved content processor architecture for better theme compatibility
- Enhanced caption protection mechanism for more reliable exclusion
- Optimized processing order for better performance and reliability
- Page builder content (`[vc_`, `[tdb_`, `[tdc_` shortcodes) now properly skipped during processing

#### Technical

- Complete rewrite of caption handling to prevent PHP 8 compatibility issues
- Enhanced WordPress shortcode compatibility using proper parsing methods
- Improved regex patterns for better reliability and performance
- Added comprehensive input validation and type checking throughout

### Version 3.0.0 - 2025-05-22

#### Added

- Modern tabbed admin interface with toggle switches
- Keyboard shortcuts (Ctrl+S / Cmd+S to save)
- Tooltips and help text throughout admin interface
- Support for excluding figure captions from linking
- Visual feedback with loading overlays and notifications
- Form validation with error highlighting
- Responsive design for mobile admin access
- Enhanced custom keywords with pipe separator support
- Better external link detection and handling
- **Complete rewrite from scratch** to address 9+ years of technical debt from original SEO Internal Links plugin

#### Changed

- **BREAKING**: Custom keywords now use pipe (`|`) separator instead of comma
- **BREAKING**: Minimum PHP version requirement increased to 7.4
- Complete rewrite using object-oriented programming
- Improved admin interface organization with logical grouping
- Enhanced security with proper sanitization and nonce verification
- Optimized database queries and caching system
- Better regex patterns for PHP 8.x compatibility
- Modernized WordPress hooks and standards compliance
- Improved settings preservation logic in core class
- Enhanced settings sanitization to merge with existing values
- Better handling of checkbox fields and boolean values during updates

#### Fixed

- **CRITICAL**: Fixed fatal errors from deprecated `/e` modifier causing PHP 7.0+ crashes
- **CRITICAL**: Fixed numerous PHP 8.x compatibility issues inherited from 9+ year old codebase
- **CRITICAL**: Fixed cache naming inconsistency preventing cache clearing
- **CRITICAL**: Fixed settings not being preserved during plugin updates
- **CRITICAL**: Fixed settings being reset to defaults when plugin is reactivated
- **SECURITY**: Fixed potential SQL injection vulnerabilities from legacy code
- **SECURITY**: Added proper input sanitization and output escaping throughout
- Settings merging logic now properly preserves existing values while adding new defaults
- Custom keywords, link limits, and exclusion rules now survive plugin updates
- Installation process no longer overwrites existing settings
- Self-link detection and prevention logic completely rewritten
- Eliminated undefined variable warnings in error logs
- Fixed memory leaks from inefficient string operations inherited from original plugin
- Resolved inconsistent nonce verification

#### Removed

- Deprecated PHP 5.x compatibility
- Hardcoded database query limits
- Inline CSS and JavaScript (now properly enqueued)
- Legacy procedural code structure

#### Security

- Added proper capability checks for admin functions
- Implemented secure nonce handling for all forms
- Added input sanitization using WordPress functions
- Added output escaping to prevent XSS attacks
- Parameterized all database queries to prevent SQL injection

### Version 2.3.1 - 2016-02-03

- Initial Legacy Version (Original SEO Internal Links by Pankaj Jha)

---

## Version Numbering

This project uses [Semantic Versioning](https://semver.org/):

- **MAJOR** version when making incompatible API changes
- **MINOR** version when adding functionality in a backwards compatible manner
- **PATCH** version when making backwards compatible bug fixes

---

## Support

- **Issues**: [GitHub Issues](https://github.com/jmoorewv/smart-internal-links/issues)
- **Documentation**: Plugin admin interface includes comprehensive help
- **Website**: [jmoorewv.com](https://jmoorewv.com)
