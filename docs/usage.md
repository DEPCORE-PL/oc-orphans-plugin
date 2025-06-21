# Usage

## Using the Twig Filter

To process text in your templates, use the `orphans` filter:

```twig
{{ post.content|orphans|raw }}
```

This will replace spaces before orphan words with `&nbsp;`.

### Example Output

Input:
```
A to jest test.
```
Output:
```
A&nbsp;to jest test.
```

## Processing Model Fields Automatically

If configured in the plugin settings, the plugin will automatically process specified fields in your models and Tailor entries before saving.

**Example:**
- Add your model and fields in the settings UI.
- When you save a record, the specified fields will be processed.

## Manual Replacement in PHP

You can also call the replacement logic directly in PHP:

```php
use Depcore\Orphans\Plugin;

$plugin = new Plugin();
$processed = $plugin->replace($text);
```

## Advanced: Handling Arrays/JSON

If a model field is an array or JSON, the plugin will encode it, process the string, and decode it back, ensuring all text is handled.

---

See [Configuration](configuration.md) for setup, or [How-Tos](howtos.md) for recipes and troubleshooting.
