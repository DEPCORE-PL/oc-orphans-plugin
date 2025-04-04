<?php namespace Depcore\Orphans;

use Backend;
use Event;
use System\Classes\PluginBase;

class Plugin extends PluginBase
{
    public function pluginDetails()
    {
        return [
            'name' => 'depcore.orphans::lang.plugin.name',
            'description' => 'depcore.orphans::lang.plugin.description',
            'author' => 'depcore',
            'icon' => 'icon-leaf'
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
            \Log::info("A $type has been saved");
        });
    }

    public function replace( $content ) {
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