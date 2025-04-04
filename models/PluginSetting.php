<?php namespace Depcore\Orphans\Models;

use Model;

/**
 * PluginSetting Model
 *
 * @link https://docs.octobercms.com/3.x/extend/system/models.html
 */
class PluginSetting extends \System\Models\SettingModel
{
    public $settingsCode = 'depcore_orphans_settings';

    public $settingsFields = 'fields.yaml';

}
