=== Smart Internal Links ===
Contributors: jmoorewv
Donate link: https://jmoorewv.com
Tags: seo, internal links, automatic linking, content optimization, link building
Requires at least: 5.0
Tested up to: 6.4
Requires PHP: 7.4
Stable tag: 3.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Automatically create internal links within your WordPress content to improve SEO and user experience with modern PHP 8.x compatibility.

== Description ==

Smart Internal Links automatically creates internal links within your WordPress content to improve SEO performance and enhance user navigation. This is a complete rewrite of the original [SEO Internal Links](https://wordpress.org/plugins/seo-internal-links/) plugin by Pankaj Jha, which had not been updated in over 9 years and suffered from PHP compatibility issues and security vulnerabilities. This modern version is rebuilt from the ground up with PHP 8.x compatibility, enhanced security, and current WordPress standards. The plugin intelligently identifies keywords and phrases that match your existing posts, pages, categories, and tags, then creates relevant internal links.

**Key Features:**

* **Automatic Linking**: Creates internal links based on your existing content
* **Custom Keywords**: Define specific keywords that link to particular URLs
* **Smart Targeting**: Links to posts, pages, categories, and tags
* **Modern Interface**: Clean, tabbed admin interface with toggle switches
* **Link Control**: Set limits on links per post and per keyword
* **Exclusion Rules**: Prevent linking in headings, captions, or specific content
* **External Link Handling**: Add nofollow and target="_blank" to external links
* **Performance Optimized**: Efficient caching and database queries
* **Mobile Responsive**: Admin interface works on all devices
* **Settings Preservation**: Your settings survive plugin updates automatically

**Perfect For:**

* Bloggers wanting to improve internal link structure
* Content marketers focused on SEO optimization
* Website owners looking to increase page views
* Anyone wanting automated internal linking without manual work

**Modern Technology:**

* Full PHP 8.x compatibility ( PHP 7.4, 8.0, 8.1, 8.2, 8.3 )
* Object-oriented architecture for better performance
* WordPress coding standards compliant
* Enhanced security with proper sanitization

== Installation ==

**⚠️ Upgrading from SEO Internal Links 2.3.1?**

**DO NOT proceed with standard installation.** This version requires manual migration:

1. **Backup your entire site**
2. **Read MIGRATION.md completely**
3. **Follow the step-by-step migration process**
4. **Test thoroughly before going live**

**Fresh Installation:**

1. Upload the plugin files to `/wp-content/plugins/smart-internal-links/` directory, or install through WordPress admin directly
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to Settings > Smart Internal Links to configure the plugin
4. Select which content types to process ( Posts, Pages, Comments )
5. Choose target links ( Posts, Pages, Categories, Tags )
6. Set your link limits and exclusion rules
7. Save settings and your content will automatically include internal links

**Manual Installation:**

1. Upload the plugin folder to `/wp-content/plugins/`
2. Activate the plugin through the **Plugins** menu in WordPress

== Configuration ==

After activation, go to **Settings > Smart Internal Links** to configure the plugin.

**Content Types:**
Choose which content types to process:
* **Posts**: Enable automatic linking in blog posts
* **Pages**: Enable automatic linking in static pages
* **Comments**: Enable automatic linking in comments ( may impact performance )
* **Allow Self-Links**: Let posts/pages link to themselves

**Target Links:**
Select what content should be considered for linking:
* **Posts**: Link to other posts based on titles
* **Pages**: Link to pages based on titles
* **Categories**: Link to category archives
* **Tags**: Link to tag archives
* **Minimum Usage**: Set minimum times a keyword must appear

**Custom Keywords:**
Define specific keywords that should link to particular URLs:
`jonathan moore | https://jmoorewv.com/about/`
`python | php | javascript | https://jmoorewv.com/category/guides/programming/`

**Limits & Controls:**
* **Max Links per Post**: Limit total links created per post
* **Max Single Keyword**: Limit how many times the same keyword links
* **Max Single URL**: Limit links to the same destination

**Exclusions:**
Prevent linking in specific areas:
* **Headings**: Skip h1-h6 tags
* **Figure Captions**: Skip image captions
* **Ignore Keywords**: Exclude specific words from linking
* **Ignore Posts**: Skip specific posts/pages by ID or slug

== Usage Examples ==

**Basic Setup:**
1. Enable **Posts** and **Pages** in Content Types
2. Enable **Posts** and **Pages** in Target Links
3. Set **Max Links per Post** to 3
4. Save settings

**Custom Keywords:**
Add custom keywords in the format: `keyword1 | keyword2 | URL`
`web development | programming | https://example.com/services/`
`SEO | search optimization | https://example.com/seo-guide/`

**Exclusions:**
To prevent linking certain words:
`about, contact, privacy, terms`

To ignore specific posts ( by ID or slug ):
`1 | about-us | contact-page`

== Keyboard Shortcuts ==

* **CTRL+S** ( or **CMD+S** on Mac ): Save settings from any tab

== Performance Considerations ==

* **Comments Processing**: May slow down sites with many comments
* **Categories/Tags**: Can impact performance on sites with many terms
* **Caching**: Plugin uses WordPress object cache for optimal performance

== Frequently Asked Questions ==

= I'm upgrading from SEO Internal Links 2.3.1 - what do I need to know? =

**This is a complete rewrite requiring manual migration.** Your settings will NOT automatically transfer. Before upgrading:

1. **Backup your site completely**
2. **Document all your current settings** (custom keywords, limits, exclusions)
3. **Read MIGRATION.md** in the plugin directory for detailed instructions
4. **Custom keywords format changed** from comma separators to pipe separators (|)

= Does this plugin work with PHP 8? =

Yes! Smart Internal Links is fully compatible with PHP 7.4, 8.0, 8.1, 8.2, and 8.3.

= Will this slow down my website? =

The plugin is optimized for performance with efficient caching. However, processing comments or large numbers of categories/tags may impact performance on some sites.

= Can I control which words get linked? =

Yes! You can define custom keywords, set ignore lists, exclude specific posts/pages, and prevent linking in headings or captions.

= How do I add custom keywords? =

Go to Settings > Smart Internal Links > Custom Keywords tab. Use the format: `keyword1 | keyword2 | URL`

Example: `web development | programming | https://example.com/services/`

= Can I prevent the plugin from linking certain words? =

Yes, use the Ignore Keywords field to list words that should never be linked, separated by pipes ( | ).

= Will it create too many links? =

You can set maximum limits for total links per post and links per keyword to maintain natural content flow.

= Does it work with custom post types? =

Currently supports standard Posts and Pages. Custom post type support may be added in future versions.

= Can I undo the links? =

The plugin processes content dynamically without permanently modifying your database. Deactivating the plugin removes all automatic links.

= What happens to my custom keywords during updates? =

Starting with version 3.0.0, all your custom keywords, link limits, exclusion rules, and other settings are automatically preserved during plugin updates.

= Why do my links not appear? =

1. Check that content types are enabled
2. Verify target links are configured
3. Ensure keywords aren't in the ignore list
4. Check link limits aren't exceeded

= I'm having performance issues, what should I do? =

1. Disable comment processing if not needed
2. Reduce category/tag linking on large sites
3. Lower link limits per post
4. Use object caching if available

= My custom keywords aren't working, what's wrong? =

1. Verify pipe ( `|` ) separator format
2. Check for trailing spaces
3. Ensure URLs are complete with `http://` or `https://`

== Screenshots ==

1. Modern tabbed admin interface with toggle switches
2. Content Types configuration panel
3. Custom Keywords setup with examples
4. Link limits and exclusion settings
5. Before and after content showing automatic internal links

== Changelog ==

= 3.0.0 - BREAKING CHANGES =
**⚠️ Manual migration required from SEO Internal Links 2.x**

* Complete rewrite with modern PHP 7.4+ / 8.x compatibility
* New tabbed admin interface with toggle switches and tooltips
* Enhanced security with proper sanitization and nonce verification
* Improved performance with optimized caching and database queries
* Better regex patterns for PHP 8 compatibility
* Added figure caption exclusions
* Fixed cache naming inconsistency bug that prevented cache clearing
* **BREAKING**: Updated custom keywords to use pipe ( | ) separators instead of commas
* **BREAKING**: Minimum PHP version requirement increased to 7.4
* Added keyboard shortcuts ( Ctrl+S / Cmd+S to save )
* Responsive design for mobile admin access
* Enhanced self-link detection and prevention
* Fixed potential SQL injection vulnerabilities
* Added proper input sanitization and output escaping
* Modernized WordPress hooks and standards compliance
* Fixed settings not being preserved during plugin updates
* Fixed settings being reset to defaults when plugin is reactivated
* Fixed custom keywords, link limits, and exclusion rules being lost during updates
* Improved settings merging logic now properly preserves existing values while adding new defaults
* Better handling of checkbox fields and boolean values during updates
* Changed installation process no longer overwrites existing settings

= 2.3.1 ( SEO Internal Links ) =
* Legacy version with basic functionality by Pankaj Jha
* Simple admin interface
* Custom keywords with comma separators
* Basic automatic linking features

== Upgrade Notice ==

= 3.0.0 =
**BREAKING CHANGES**: Major rewrite with PHP 8.x compatibility and enhanced security. This is a complete rewrite of the original SEO Internal Links plugin. Custom keywords format changed from comma to pipe separators. Manual migration required from SEO Internal Links 2.x - backup recommended before upgrading. See MIGRATION.md for detailed upgrade instructions.

== Support ==

For support, feature requests, or bug reports:

* GitHub: https://github.com/jmoorewv/smart-internal-links
* Website: https://jmoorewv.com
* WordPress.org Support Forum

== Privacy ==

This plugin does not collect, store, or transmit any personal data. It only processes your existing WordPress content to create internal links.

== Credits ==

* Current Version: Jonathan Moore ( jmoorewv.com )
* Original Concept: Pankaj Jha ( OnlineWebApplication.com )

Special thanks to the WordPress community for coding standards and best practices.

== Disclaimer ==

This plugin automatically modifies your content to add internal links. While it's designed to be safe and reversible, always backup your site before installation and test thoroughly on a staging environment.
