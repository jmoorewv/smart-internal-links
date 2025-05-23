# Migration Guide: Smart Internal Links

## Important Notice

**Smart Internal Links 3.0.0 is a complete rewrite** with a new plugin name, updated settings structure, and modern PHP compatibility. **Manual migration is required** when upgrading from SEO Internal Links version 2.x to Smart Internal Links 3.x.

## Before You Begin

### Backup Your Site

**This is critical** - create a complete backup of your WordPress site before proceeding with the migration.

### Document Current Settings

Before deactivating the old version, take screenshots or notes of your current plugin settings:

- Custom keywords and their URLs
- Link limits ( max links per post, max single keyword, etc. )
- Excluded words and posts
- Which content types are enabled
- External link settings

## Migration Steps

### Step 1: Export Current Settings

1. Go to your current plugin settings page
2. **Copy all custom keywords** - these will need to be reformatted
3. **Note all numerical limits** ( maxlinks, maxsingle, etc. )
4. **List all excluded words and posts**
5. **Record which checkboxes are enabled**

### Step 2: Deactivate Old Plugin

1. Go to **Plugins > Installed Plugins**
2. **Deactivate** the old SEO Internal Links plugin
3. **Do not delete it yet** - keep it as backup until migration is complete

### Step 3: Install Smart Internal Links

1. Upload and activate Smart Internal Links
2. Go to **Settings > Smart Internal Links**
3. You'll see the new tabbed interface with default settings

### Step 4: Migrate Settings Manually

#### Content Types Tab

- Enable **Posts** and **Pages** as needed
- Configure **Allow links to self** options based on your old settings

#### Target Links Tab

- Enable **Posts**, **Pages**, **Categories**, **Tags** as needed
- Set **Minimum usage**

#### Exclusions Tab

- Enable **Prevent linking in heading tags** if you had this before
- Enable **Prevent linking in figure captions** ( new feature )

#### Settings Tab

- Enable **Process only single posts and pages**
- Enable **Process RSS feeds**
- Enable **Case sensitive matching**

#### Limits Tab

- Set **Max Links per Post**
- Set **Max Single**
- Set **Max Single URLs**

#### Ignore Rules Tab

- **Ignore Posts and Pages**: Copy your excluded post IDs/slugs
- **Ignore Keywords**: Copy your excluded words list

#### Custom Keywords Tab

**Important Format Change**: Custom keywords now use **pipe separators** instead of commas.

**Old format** ( SEO Internal Links ):

```
keyword1, keyword2, url
```

**New format** ( Smart Internal Links ):

```
keyword1 | keyword2 | url
```

**Migration example**:

```
# Old format
web development, programming, https://example.com/services/
SEO, search optimization, https://example.com/seo/

# New format
web development | programming | https://example.com/services/
SEO | search optimization | https://example.com/seo/
```

#### External Links Tab

- Enable **Add nofollow attribute**
- Enable **Open in new window**

### Step 5: Test and Verify

1. **Save all settings** using the new interface
2. **Test on a few posts** to ensure links are working correctly
3. **Check custom keywords** are functioning with the new pipe format
4. **Verify excluded content** is properly ignored
5. **Test link limits** are being respected

### Step 6: Clean Up

Once you've confirmed everything is working correctly:

1. **Delete the old plugin (SEO Internal Links)** from your plugins folder
2. **Clear any caching** if you use a caching plugin
3. **Update your documentation** with new settings locations

## Key Changes to Remember

### Settings Structure

- **New tabbed interface** replaces single-page settings
- **Toggle switches** replace old checkboxes
- **Better organization** with logical grouping

### Custom Keywords Format

- **Pipe separators** ( `|` ) instead of commas
- **Same functionality**, just different syntax
- **Multiple keywords per URL** still supported

### New Features Available

- **Figure caption exclusions**
- **Keyboard shortcuts** ( Ctrl+S to save )
- **Better mobile admin interface**
- **Enhanced tooltips and help text**

### Performance Improvements

- **Better caching system**
- **Optimized database queries**
- **PHP 8.x compatibility**

## Troubleshooting

### Links Not Appearing

1. Check that content types are enabled in **Content Types** tab
2. Verify target links are enabled in **Target Links** tab
3. Ensure keywords aren't in the **Ignore Rules**
4. Check that link limits aren't being exceeded

### Custom Keywords Not Working

1. Verify you're using **pipe separators** ( `|` )
2. Check for **trailing spaces** in keywords
3. Ensure URLs include **http://** or **https://**
4. Test keywords individually to isolate issues

### Settings Not Saving

1. Check you have **admin privileges**
2. Verify **nonce verification** isn't blocked by security plugins
3. Use **Ctrl+S keyboard shortcut** as alternative
4. Check browser console for JavaScript errors

### Performance Issues

1. **Disable comment processing** if not needed
2. **Reduce category/tag linking** on large sites
3. **Lower link limits** per post
4. **Check for caching conflicts**

## Support

If you encounter issues during migration:

- **GitHub Issues**: [Report migration problems](https://github.com/jmoorewv/smart-internal-links/issues)
- **Documentation**: Check plugin admin interface for detailed help
- **Website**: [jmoorewv.com](https://jmoorewv.com) for additional support

## Post-Migration Checklist

- [ ] All custom keywords converted to pipe format
- [ ] Link limits configured correctly
- [ ] Exclusion rules working as expected
- [ ] Content types and targets enabled properly
- [ ] External link settings configured
- [ ] Test posts showing appropriate internal links
- [ ] No errors in WordPress admin or logs
- [ ] Old plugin safely removed
- [ ] Settings documentation updated

---

**Remember**: This migration is **one-time only**. Once you've successfully migrated to version 3.0.0, future updates will preserve your settings automatically.
