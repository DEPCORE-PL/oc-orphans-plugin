<?php namespace Depcore\Orphans;

use Backend;
use Event;
use System\Classes\PluginBase;
use Depcore\Orphans\Models\PluginSetting as Settings;

class Plugin extends PluginBase
{
    public function pluginDetails()
    {
        return [
            'name' => 'Orphans plugin',
            'description' => 'This plugin replaces orphans with non-breaking spaces',
            'author' => 'depcore',
            'icon' => 'ph ph-textbox'
        ];
    }

    public function registerMarkupTags(){
        return [
            'filters' => [
                'orphans' => [$this, 'replace'],
            ]
        ];
    }

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
				'description' => 'Manage which type of content shoud the plugin work wtih.',
				'category' => 'CATEGORY_CMS',
				'icon' => 'ph ph-textbox',
				'class' => Settings::class,
			]
		];
	}

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


		$terms = array(
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

		$re = '/^([aiouwz]|'.preg_replace( '/\./', '\.', implode( '|', $terms ) ).') +/i';
		$content = preg_replace( $re, '$1$2&nbsp;', $content );

		$re = '/([ >\(]+)([aiouwz]|'.preg_replace( '/\./', '\.', implode( '|', $terms ) ).') +/i';
		$content = preg_replace( $re, '$1$2&nbsp;', $content );

		$re = '/(&nbsp;)([aiouwz]) +/i';
		$content = preg_replace( $re, '$1$2&nbsp;', $content );

		$content = preg_replace( '/(\d+) (r\.)/', '$1&nbsp;$2', $content );

		return $content;
	}

}