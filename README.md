# Orphans plugin for OctoberCMS

This plugin is based on a wordpress plugin created by [Marcin Pietrzak](https://github.com/iworks/sierotki) and ported to OctoberCMS.

The plugins works by replacing spaces before certain word to the non breaking html entity `&nbsp;` to make the writing compatible with polish language.

### Installation

Just activate the plugin and then setup models and tailor entries.

### Twig filter

Use the `|orphans` filter to remove orphans from the text.

### Use in template files

Use the `html_entities` or `content` twig filter to hide the escaped `$nbsp;` string

### Future development

1. Include a setting to support in rainlab.pages to automatically add spaces in content fields, snippets, templates and pages
2. Include a setting to support in rainlab.blog plugin
3. add behavior to allow users to extend their fields with this plugin
