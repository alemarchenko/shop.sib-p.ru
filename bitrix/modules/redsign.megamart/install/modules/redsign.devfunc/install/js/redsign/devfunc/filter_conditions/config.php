<?
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
{
	die();
}

return [
	'css' => 'dist/filter_conditions.bundle.css',
	'js' => 'dist/filter_conditions.bundle.js',
	'rel' => [
		'main.polyfill.core',
	],
	'skip_core' => true,
];