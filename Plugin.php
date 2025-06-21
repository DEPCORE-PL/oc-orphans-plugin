<?php namespace Depcore\Orphans;

use Event;
use System\Classes\PluginBase;
use Depcore\Orphans\Models\PluginSetting as Settings;

/**
 * Class Plugin
 *
 * Main plugin class for the Orphans plugin.
 * Extends the base plugin functionality provided by PluginBase.
 *
 * @package Depcore\Orphans
 * @author Depcore
 */
class Plugin extends PluginBase
{

    /**
     * @var array $terms
     * Stores the terms used by the plugin.
     */
    protected $terms = array(
			'al.',
			'albo',
			'ale',
			'ależ',
			'b.',
			'bez',
			'bm.',
			'bp',
			'br.',
			'by',
			'bym',
			'byś',
			'bł.',
			'cyt.',
			'cz.',
			'czy',
			'czyt.',
			'dn.',
			'do',
			'doc.',
			'dr',
			'ds.',
			'dyr.',
			'dz.',
			'fot.',
			'gdy',
			'gdyby',
			'gdybym',
			'gdybyś',
			'gdyż',
			'godz.',
			'im.',
			'inż.',
			'jw.',
			'kol.',
			'komu',
			'ks.',
			'która',
			'którego',
			'której',
			'któremu',
			'który',
			'których',
			'którym',
			'którzy',
			'lecz',
			'lic.',
			'm.in.',
			'max',
			'mgr',
			'min',
			'moich',
			'moje',
			'mojego',
			'mojej',
			'mojemu',
			'mych',
			'mój',
			'na',
			'nad',
			'nie',
			'niech',
			'np.',
			'nr',
			'nr.',
			'nrach',
			'nrami',
			'nrem',
			'nrom',
			'nrowi',
			'nru',
			'nry',
			'nrze',
			'nrze',
			'nrów',
			'nt.',
			'nw.',
			'od',
			'oraz',
			'os.',
			'p.',
			'pl.',
			'pn.',
			'po',
			'pod',
			'pot.',
			'prof.',
			'przed',
			'przez',
			'pt.',
			'pw.',
			'pw.',
			'tak',
			'tamtej',
			'tamto',
			'tej',
			'tel.',
			'tj.',
			'to',
			'twoich',
			'twoje',
			'twojego',
			'twojej',
			'twych',
			'twój',
			'tylko',
			'ul.',
			'we',
			'wg',
			'woj.',
			'więc',
			'za',
			'ze',
			'śp.',
			'św.',
			'że',
			'żeby',
			'żebyś',
		);

    public function pluginDetails()
    {
        return [
            'name' => 'Orphans plugin',
            'description' => 'This plugin replaces orphans with non-breaking spaces',
            'author' => 'depcore',
            'icon' => 'ph ph-textbox'
        ];
    }

    /**
     * Registers custom Twig markup tags for use in frontend templates.
     *
     * This method allows you to define additional Twig filters, functions, or tags
     * that can be used within your CMS markup files. It should return an array
     * containing the custom tags to be registered.
     *
     * @return array The array of custom markup tags to register.
     */
    public function registerMarkupTags(){
        return [
            'filters' => [
                'orphans' => [$this, 'replace'],
            ]
        ];
    }

    /**
     * Boots the plugin and sets up event listeners and model extensions.
     *
     * - Listens for the 'cms.template.save' event and applies the `replace` method to the template markup.
     * - Retrieves custom model definitions from settings and, for each model:
     *   - Extends the model to bind a 'model.beforeSave' event.
     *   - On save, iterates over specified fields and applies the `replace` method to their values.
     *   - Handles both string and array field values, decoding JSON as needed.
     *   - Catches and displays errors related to model extension or field processing.
     * - If 'use_in_tailor' is enabled in settings:
     *   - Retrieves tailor model definitions and extends the `Tailor\Models\EntryRecord` model.
     *   - Binds a 'model.beforeSave' event to process specified fields with the `replace` method.
     *   - Handles errors and displays relevant messages for misconfiguration.
     *
     * @return void
     */
    public function boot(){

		Event::listen('cms.template.save', function ($editorExtension, $templateObject, $type) {
			$this->replace($templateObject->markup);
        });

		$customModels = Settings::get('your_models');
		if (is_array($customModels))
		foreach ($customModels as $model) {
			try {
				$modelClass = $model['model_class'];
				$modelFields = $model['model_fields'];
				if (!class_exists($modelClass)) return;

				$modelClass::extend(function($model) use ($modelFields) {
					$model->bindEvent('model.beforeSave', function() use ($model,$modelFields) {
						foreach ($modelFields as $field) {
							$field =$field['field_name'];
							if(!isset($model->$field) || $model->$field === null) return;
							if (is_array($model->$field)) {
								$model->$field = json_decode($this->replace(json_encode($model->$field)));
							}
							else{
								$model->$field = $this->replace($model->$field);
							}
						}
					});
				});
			} catch (\Exception $th) {
				\Flash::error( $th->getMessage() . "\rGo to Otpahns plugin settings (setting/orphans) and update your model definitions");
			}
		}

		if(Settings::get('use_in_tailor')){

			$useInTailor = Settings::get('use_in_tailor');
			if ($useInTailor) {
				$tailorModels = Settings::get('tailor_models');

				\Tailor\Models\EntryRecord::extend(function($model) use ($tailorModels) {
					$model->bindEvent('model.beforeSave', function() use ($model,$tailorModels) {
						foreach ($tailorModels as $tailorModel) {
							try {
								foreach ($tailorModel['model_fields'] as $field) {
									$field =$field['field_name'];
									if ($model->field !== null) {
										# code...
										$model->$field= $this->replace($model->$field);
									}
								}

							} catch (\SystemException $th) {
								\Flash::error( $th->getMessage() . "\rGo to Otpahns plugin settings (setting/orphans) and update your model definitions");
							}

						}

					});
				});
			}
		}
    }

	public function registerSettings()
	{
		return [
			'settings' => [
				'label' => 'Orphans',
				'description' => 'Manage which type of content shoud the plugin work with.',
				'category' => 'CATEGORY_CMS',
				'icon' => 'ph ph-textbox',
				'class' => Settings::class,
			]
		];
	}

    /**
     * Replaces specific content within the provided string.
     *
     * @param string $content The input content to be processed and replaced.
     * @return string The resulting content after performing replacements.
     */
    public function replace(String $content ) : string {
		if ( empty( $content ) ) {
			return $content;
		}
		$numbers = true;
		if ( $numbers ) {
			while ( preg_match( '/(\d) (\d)/', $content ) ) {
				$content = preg_replace( '/(\d) (\d)/', '$1&nbsp;$2', $content );
			}
		}




		preg_match_all( '@(<(script|style)[^>]*>.*?(</(script|style)>))@is', $content, $matches );
		$exceptions = '';

		if ( ! empty( $matches ) && ! empty( $matches[0] ) ) {
			$salt = 'kQc6T9fn5GhEzTM3Sxn7b9TWMV4PO0mOCV06Da7AQJzSJqxYR4z3qBlsW9rtFsWK';
			foreach ( $matches[0] as $one ) {
				$key = sprintf( '<!-- %s %s -->', $salt, md5( $one ) );
				$exceptions[ $key ] = $one;
				$re = sprintf( '@%s@', preg_replace( '/@/', '\@', preg_quote( $one, '/' ) ) );
				$content = preg_replace( $re, $key, $content );
			}
		}

		$re = '/^([aiouwz]|'.preg_replace( '/\./', '\.', implode( '|', $this->terms ) ).') +/i';
		$content = preg_replace( $re, '$1$2&nbsp;', $content );

		$re = '/([ >\(]+)([aiouwz]|'.preg_replace( '/\./', '\.', implode( '|', $this->terms ) ).') +/i';
		$content = preg_replace( $re, '$1$2&nbsp;', $content );

		$re = '/(&nbsp;)([aiouwz]) +/i';
		$content = preg_replace( $re, '$1$2&nbsp;', $content );

		$content = preg_replace( '/(\d+) (r\.)/', '$1&nbsp;$2', $content );

		return $content;
	}

}
