# API Reference

## Plugin Class

**Namespace:** `Depcore\Orphans\Plugin`

### Properties

- `protected array $terms`  
  List of orphan words to process (Polish conjunctions, prepositions, etc.).

### Methods

#### pluginDetails()
Returns plugin metadata (name, description, author, icon).

#### registerMarkupTags()
Registers the `orphans` Twig filter for use in templates.

#### boot()
- Hooks into `cms.template.save` to process template markup.
- Extends configured models and Tailor entries to process fields before save.
- Handles both string and array/JSON fields.
- Catches and flashes errors if model extension or field processing fails.

#### registerSettings()
Registers the backend settings page for the plugin.

#### replace(string $content): string
Processes the input string, replacing spaces before orphan words with `&nbsp;`.

**Parameters:**
- `$content` (string): The text to process.

**Returns:**
- (string): The processed text.

**Example:**

```php
$plugin = new \Depcore\Orphans\Plugin();
echo $plugin->replace('A to jest test.');
// Output: 'A&nbsp;to jest test.'
```

### Events
- Listens for `cms.template.save` and model `beforeSave` events.

---

See [How-Tos](howtos.md) for practical integration tips.
