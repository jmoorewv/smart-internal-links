# Contributing to Smart Internal Links

Thank you for your interest in contributing to Smart Internal Links! This document provides guidelines for contributing to the project.

## How to Contribute

### Reporting Bugs

Before creating bug reports, please check the existing issues to avoid duplicates. When creating a bug report, include:

- **WordPress Version**
- **PHP Version**
- **Plugin Version**
- **Clear Description** of the issue
- **Steps to Reproduce** the problem
- **Expected vs Actual Behavior**
- **Screenshots** (if applicable)
- **Error Messages** from logs

### Suggesting Features

Feature requests are welcome! Please:

1. Check existing issues and discussions first
2. Clearly describe the feature and its benefits
3. Explain the use case and how it improves SEO or user experience
4. Consider backward compatibility implications

### Code Contributions

#### Before You Start

1. Fork the repository
2. Create a feature branch from `main`
3. Set up a local WordPress development environment
4. Install the plugin in development mode

#### Development Setup

```bash
# Clone your fork
git clone https://github.com/jmoorewv/smart-internal-links.git
cd smart-internal-links

# Create feature branch
git checkout -b feature/your-feature-name
```

#### Coding Standards

**PHP Standards:**
- Follow [WordPress Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/php/)
- Use PHP 7.4+ features and strict typing (`declare(strict_types=1)`)
- All classes and methods must have PHPDoc comments
- Use meaningful variable and function names
- Prefer spaces after `(` and before `)` in function calls

**Security Requirements:**
- Sanitize all user inputs using WordPress functions
- Escape all outputs using `esc_html()`, `esc_attr()`, etc.
- Use nonces for all form submissions
- Verify user capabilities with `current_user_can()`
- Use `$wpdb->prepare()` for all database queries

**Code Structure:**
- Keep classes focused and single-purpose
- Use dependency injection where appropriate
- Follow WordPress hook conventions
- Write testable code with minimal dependencies

#### Testing

Before submitting:

1. **Manual Testing:**
   - Test on WordPress 5.0+ and latest version
   - Test on PHP 7.4, 8.0, 8.1, 8.2, 8.3
   - Test with various themes and plugins
   - Test admin interface on mobile devices

2. **Functionality Testing:**
   - Verify automatic linking works correctly
   - Test custom keywords functionality
   - Check exclusion rules work properly
   - Ensure settings save and load correctly
   - Test performance with large content volumes

3. **Security Testing:**
   - Verify input sanitization
   - Check output escaping
   - Test nonce verification
   - Ensure proper capability checks

#### Pull Request Process

1. **Commit Messages:**
   ```
   feat: add figure caption exclusion option
   fix: resolve cache naming inconsistency bug
   security: add proper input sanitization
   docs: update README installation instructions
   ```

2. **Pull Request Description:**
   - Clearly describe what the PR does
   - Reference any related issues
   - Include testing steps
   - Note any breaking changes
   - Add screenshots for UI changes

3. **Code Review:**
   - Address all review feedback
   - Keep commits focused and atomic
   - Update documentation as needed

## Code Examples

### Adding a New Setting

```php
// In class-smart-links-settings.php
public function get_default_settings(): array {
    return [
        // ... existing settings
        'new_feature' => 'on',
    ];
}

// In settings-page.php
<div class="field-row">
    <label class="toggle-switch">
        <input type="checkbox" name="new_feature" <?php echo esc_attr( $fields['new_feature'] ); ?> />
        <span class="slider"></span>
        <span class="label-text"><?php _e( 'Enable New Feature', 'smart-internal-links' ); ?></span>
    </label>
</div>
```

### Processing Content

```php
// In class-smart-links-content-processor.php
public function process_new_feature( string $content ): string {
    // Always sanitize inputs
    $content = wp_kses_post( $content );

    // Process content safely
    $processed = $this->safe_processing_function( $content );

    // Return escaped output if needed
    return $processed;
}
```

## Documentation

When contributing code:

- Update inline PHPDoc comments
- Update README.md if functionality changes
- Update CHANGELOG.md following the established format
- Add help text in admin interface for new features

## Getting Help

- **Questions**: Open a GitHub Discussion
- **Issues**: Create a GitHub Issue
- **Contact**: Email through jmoorewv.com

## Recognition

Contributors will be acknowledged in:
- CHANGELOG.md for significant contributions
- Plugin credits for major features
- GitHub contributors list

Thank you for helping make Smart Internal Links better!
