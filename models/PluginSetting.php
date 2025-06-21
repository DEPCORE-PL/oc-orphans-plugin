<?php namespace Depcore\Orphans\Models;


/**
 * Class PluginSetting
 *
 * Represents the settings model for the Orphans plugin.
 * Extends the base SettingModel provided by the OctoberCMS System module.
 *
 * This class is used to manage and persist plugin-specific configuration options.
 */
class PluginSetting extends \System\Models\SettingModel
{
    public $settingsCode = 'depcore_orphans_settings';

    public $settingsFields = 'fields.yaml';

}
