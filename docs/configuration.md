# Configuration

## Backend Settings

Go to **Settings > Orphans** in the OctoberCMS backend.

### Custom Models

- Add each model you want to process.
- For each model, specify the fully qualified class name and the fields to process.

**Example:**
| Model class                | Fields         |
|----------------------------|---------------|
| Acme\Blog\Models\Post      | content, excerpt |
| Acme\News\Models\Article   | body          |

### Tailor Integration

- Enable "Use in Tailor" to process Tailor entries.
- Add each Tailor model and the fields to process.

### RainLab Integrations

- Switches exist for RainLab Pages and Blog.
- (Implementation pending in plugin code.)

## Settings YAML Reference

The settings form is defined in `models/pluginsetting/fields.yaml` and supports:

- Repeater for custom models and fields.
- Switches for Tailor, RainLab Pages, and Blog.
- Repeater for Tailor models and fields (shown only if Tailor integration is enabled).

## Example: fields.yaml

```yaml
your_models:
  label: Your models
  type: repeater
  form:
    fields:
      model_class:
        label: Model class
        type: text
      model_fields:
        label: Model fields
        type: repeater
        form:
          fields:
            field_name:
              label: Field name
              type: text
```

---

See [How-Tos](howtos.md) for practical recipes.
