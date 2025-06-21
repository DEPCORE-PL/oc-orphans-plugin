# How-Tos

## How to Add Orphan Processing to a Custom Model

1. Go to **Settings > Orphans**.
2. Add your model class (e.g., `Acme\Blog\Models\Post`).
3. Add the fields you want to process (e.g., `content`).
4. Save settings. The plugin will now process these fields before saving.

## How to Enable Tailor Integration

1. Enable "Use in Tailor" in settings.
2. Add each Tailor model and the fields to process.
3. Save settings.

## Troubleshooting

- **Model not processed?**  
  Ensure the class name and field names are correct and the model exists.

- **Error on save?**  
  Check the backend for error messages. Update your model definitions if needed.

- **Want to process RainLab Pages or Blog?**  
  Enable the switches in settings. (Implementation may require plugin update.)

## Advanced: Custom Twig Filters

You can use the `orphans` filter in any Twig template:

```twig
{{ my_text|orphans|raw }}
```

## Example: Polish Typographic Rules

The plugin uses a list of Polish words (conjunctions, prepositions, etc.) and ensures they are not left at the end of a line. This is especially important for professional publishing and accessibility.

## Example: Handling Arrays/JSON

If a model field is an array or JSON, the plugin will encode it, process the string, and decode it back, ensuring all text is handled.

---

For more, see [API Reference](api.md) or [Usage](usage.md).
