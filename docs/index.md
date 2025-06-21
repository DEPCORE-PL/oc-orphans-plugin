# Orphans Plugin for OctoberCMS

The Orphans plugin improves the typographic quality of Polish text in your OctoberCMS site by automatically replacing spaces before certain "orphan" words with non-breaking spaces (`&nbsp;`). This prevents single-letter words and short conjunctions from being left at the end of a line.

Inspired by [iworks/sierotki](https://github.com/iworks/sierotki) for WordPress.

## Features

- **Automatic Orphan Replacement:** Ensures proper non-breaking spaces in Polish text.
- **Twig Filter:** Use `|orphans` in your templates.
- **Model Integration:** Automatically process fields in your custom models and Tailor entries.
- **Configurable:** Choose which models and fields to process via backend settings.
- **Extensible:** Ready for integration with RainLab Pages and Blog.
- **Safe:** Skips script/style tags and handles arrays/JSON fields.

## Installation

1. Install the plugin via the OctoberCMS backend or composer:
   ```sh
   composer require depcore/orphans-plugin
   ```
2. Activate the plugin in the backend.
3. Configure your models and integrations in the backend settings.

## Requirements

- OctoberCMS 2.x or 3.x
- PHP 7.4+

---

See [Usage](usage.md) for practical examples, or [Configuration](configuration.md) for setup details.
