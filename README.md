# Smart Internal Links

Smart Internal Links automatically creates internal links within your WordPress content to improve SEO performance and enhance user navigation. This is a complete rewrite of the original [SEO Internal Links](https://wordpress.org/plugins/seo-internal-links/) plugin by Pankaj Jha, which had not been updated in over 9 years and suffered from PHP compatibility issues and security vulnerabilities. This modern version is rebuilt from the ground up with PHP 8.x compatibility, enhanced security, and current WordPress standards. The plugin intelligently identifies keywords and phrases that match your existing posts, pages, categories, and tags, then creates relevant internal links.

## Features

- **Automatic Linking**: Automatically creates internal links based on your content
- **Custom Keywords**: Define custom keywords that link to specific URLs
- **Smart Targeting**: Link to posts, pages, categories, and tags
- **Link Limits**: Control the maximum number of links per post
- **External Link Handling**: Add `nofollow` and `target="_blank"` to external links
- **Exclusion Rules**: Prevent linking in headings, captions, or specific content
- **Modern Interface**: Clean, tabbed admin interface with toggle switches
- **Performance Optimized**: Efficient caching and database queries
- **Settings Preservation**: Your settings survive plugin updates automatically

## Requirements

- **WordPress**: 5.0 or higher
- **PHP**: 7.4, 8.0, 8.1, 8.2, or 8.3
- **MySQL**: 5.6 or higher

## Installation

### ⚠️ Upgrading from SEO Internal Links 2.3.1?

**DO NOT proceed with standard installation.** This version requires manual migration:

1. **Backup your entire site**
2. **Read [MIGRATION.md](MIGRATION.md) completely**
3. **Follow the step-by-step migration process**
4. **Test thoroughly before going live**

### Fresh Installation

1. Download the plugin ZIP file
2. Go to **Plugins > Add New** in your WordPress admin
3. Click **Upload Plugin** and select the ZIP file
4. Click **Install Now** and then **Activate**

### Manual Installation

1. Upload the plugin folder to `/wp-content/plugins/`
2. Activate the plugin through the **Plugins** menu in WordPress

## Configuration

After activation, go to **Settings > Smart Internal Links** to configure the plugin.

### Content Types

Choose which content types to process:

- **Posts**: Enable automatic linking in blog posts
- **Pages**: Enable automatic linking in static pages
- **Comments**: Enable automatic linking in comments (may impact performance)
- **Allow Self-Links**: Let posts/pages link to themselves

### Target Links

Select what content should be considered for linking:

- **Posts**: Link to other posts based on titles
- **Pages**: Link to pages based on titles
- **Categories**: Link to category archives
- **Tags**: Link to tag archives
- **Minimum Usage**: Set minimum times a keyword must appear

### Custom Keywords

Define specific keywords that should link to particular URLs:

```
jonathan moore | https://jmoorewv.com/about/
python | php | javascript | https://jmoorewv.com/category/guides/programming/
```

### Limits & Controls

- **Max Links per Post**: Limit total links created per post
- **Max Single Keyword**: Limit how many times the same keyword links
- **Max Single URL**: Limit links to the same destination

### Exclusions

Prevent linking in specific areas:

- **Headings**: Skip h1-h6 tags
- **Figure Captions**: Skip image captions
- **Ignore Keywords**: Exclude specific words from linking
- **Ignore Posts**: Skip specific posts/pages by ID or slug

## Usage Examples

### Basic Setup

1. Enable **Posts** and **Pages** in Content Types
2. Enable **Posts** and **Pages** in Target Links
3. Set **Max Links per Post** to 3
4. Save settings

### Custom Keywords

Add custom keywords in the format: `keyword1 | keyword2 | URL`

```
web development | programming | https://example.com/services/
SEO | search optimization | https://example.com/seo-guide/
```

### Exclusions

To prevent linking certain words:

```
about, contact, privacy, terms
```

To ignore specific posts (by ID or slug):

```
1 | about-us | contact-page
```

## Keyboard Shortcuts

- **CTRL+S** (or **CMD+S** on Mac): Save settings from any tab

## Performance Considerations

- **Comments Processing**: May slow down sites with many comments
- **Categories/Tags**: Can impact performance on sites with many terms
- **Caching**: Plugin uses WordPress object cache for optimal performance

## Troubleshooting

### Links Not Appearing

1. Check that content types are enabled
2. Verify target links are configured
3. Ensure keywords aren't in the ignore list
4. Check link limits aren't exceeded

### Performance Issues

1. Disable comment processing if not needed
2. Reduce category/tag linking on large sites
3. Lower link limits per post
4. Use object caching if available

### Custom Keywords Not Working

1. Verify pipe (`|`) separator format
2. Check for trailing spaces
3. Ensure URLs are complete with `http://` or `https://`

## Changelog

## [3.0.1] - 2025-07-26

### Fixed

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

### Changed

- Improved content processor architecture for better theme compatibility
- Enhanced caption protection mechanism for more reliable exclusion
- Optimized processing order for better performance and reliability
- Page builder content (`[vc_`, `[tdb_`, `[tdc_` shortcodes) now properly skipped during processing

### Technical

- Complete rewrite of caption handling to prevent PHP 8 compatibility issues
- Enhanced WordPress shortcode compatibility using proper parsing methods
- Improved regex patterns for better reliability and performance
- Added comprehensive input validation and type checking throughout

### Version 3.0.0 - BREAKING CHANGES

**⚠️ Manual migration required from SEO Internal Links 2.x**

- Complete rewrite with modern PHP 7.4+ / 8.x compatibility
- New tabbed admin interface with toggle switches
- Enhanced security with proper sanitization and nonce verification
- Improved performance with optimized caching
- Better regex patterns for PHP 8 compatibility
- Added figure caption exclusions
- Fixed cache naming inconsistency bug
- **BREAKING**: Updated custom keywords to use pipe separators
- **BREAKING**: Minimum PHP version requirement increased to 7.4
- Fixed settings not being preserved during plugin updates
- Fixed settings being reset to defaults when plugin is reactivated
- Improved settings merging logic now properly preserves existing values

### Version 2.3.1 (SEO Internal Links)

- Legacy version by Pankaj Jha

## Contributing

Contributions are welcome! Please feel free to submit issues and pull requests.

### Development Setup

1. Clone the repository
2. Install WordPress development environment
3. Activate the plugin in your test site
4. Make changes and test thoroughly

### Code Standards

- Follow WordPress Coding Standards
- Use PHP 7.4+ features and strict typing
- Document all functions with PHPDoc
- Write secure, sanitized code

## Support

- **Issues**: Report bugs via GitHub Issues
- **Documentation**: See plugin settings pages for detailed help
- **WordPress.org**: Plugin support forum (if published)

## License

This plugin is licensed under the GNU General Public License v2 or later.

## Credits

- **Current Version**: Jonathan Moore ([jmoorewv.com](https://jmoorewv.com))
- **Original Concept**: Pankaj Jha (OnlineWebApplication.com)

## Disclaimer

This plugin automatically modifies your content to add internal links. While it's designed to be safe and reversible, always backup your site before installation and test thoroughly on a staging environment.

---

**Download**: [Latest Release](https://github.com/jmoorewv/smart-internal-links/releases) | **Issues**: [Report a Bug](https://github.com/jmoorewv/smart-internal-links/issues) | **Website**: [jmoorewv.com](https://jmoorewv.com)
