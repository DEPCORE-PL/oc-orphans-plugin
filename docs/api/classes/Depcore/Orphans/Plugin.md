***

# Plugin

Class Plugin

Main plugin class for the Orphans plugin.
Extends the base plugin functionality provided by PluginBase.

* Full name: `\Depcore\Orphans\Plugin`
* Parent class: [`PluginBase`](../../System/Classes/PluginBase.md)



## Properties


### terms



```php
protected array $terms
```






***

## Methods


### pluginDetails



```php
public pluginDetails(): mixed
```












***

### registerMarkupTags

Registers custom Twig markup tags for use in frontend templates.

```php
public registerMarkupTags(): array
```

This method allows you to define additional Twig filters, functions, or tags
that can be used within your CMS markup files. It should return an array
containing the custom tags to be registered.







**Return Value:**

The array of custom markup tags to register.




***

### boot

Boots the plugin and sets up event listeners and model extensions.

```php
public boot(): void
```

- Listens for the 'cms.template.save' event and applies the `replace` method to the template markup.
- Retrieves custom model definitions from settings and, for each model:
  - Extends the model to bind a 'model.beforeSave' event.
  - On save, iterates over specified fields and applies the `replace` method to their values.
  - Handles both string and array field values, decoding JSON as needed.
  - Catches and displays errors related to model extension or field processing.
- If 'use_in_tailor' is enabled in settings:
  - Retrieves tailor model definitions and extends the `Tailor\Models\EntryRecord` model.
  - Binds a 'model.beforeSave' event to process specified fields with the `replace` method.
  - Handles errors and displays relevant messages for misconfiguration.










***

### registerSettings



```php
public registerSettings(): mixed
```












***

### replace

Replaces specific content within the provided string.

```php
public replace(string $content): string
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$content` | **string** | The input content to be processed and replaced. |


**Return Value:**

The resulting content after performing replacements.




***


***
> Automatically generated on 2025-06-21
