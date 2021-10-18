<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use \Bitrix\Main\Application;
use \Bitrix\Main\Loader;
use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\Page\Asset;

Loc::loadMessages(__FILE__);

if (!Loader::includeModule('redsign.megamart'))
{
	ShowError(Loc::getMessage('RS_ERROR_MODULE_NOT_INSTALLED'));
	die();
}
elseif (!Loader::includeModule('redsign.devfunc'))
{
	ShowError(Loc::getMessage('RS_ERROR_DEVFUNC_NOT_INSTALLED'));
	die();
}

$documentRoot = Application::getDocumentRoot();
$request = Application::getInstance()->getContext()->getRequest();
$curPage = $APPLICATION->GetCurPage(true);

// get site data
$cacheTime = 86400;
$cacheId = 'CSiteGetByID'.SITE_ID;
$cacheDir = '/siteData/'.SITE_ID.'/';

$cache = Bitrix\Main\Data\Cache::createInstance();
if ($cache->initCache($cacheTime, $cacheId, $cacheDir)) {
	$arSiteData = $cache->getVars();
} elseif ($cache->startDataCache()) {

	$arSiteData = array();

	$rsSites = CSite::GetByID(SITE_ID);
	if ($arSite = $rsSites->Fetch()) {
		$arSiteData['SITE_NAME'] = $arSite['SITE_NAME'];
	}

	if (empty($arSiteData)) {
		$cache->abortDataCache();
	}

	$cache->endDataCache($arSiteData);
}

// Global constans
$APPLICATION->IncludeFile(
	'include/globals/constants.php',
	array(),
	array(
		'SHOW_BORDER' => false
	)
);

$sBodyClass = 'rs-megamart';
$sBodyClass .= ' '.RS_MM_CONTAINER_MAX_WIDTH;
$sBodyClass .= ' ff-'.RS_MM_USE_FONT_FAMILY;
$sBodyClass .= ' fs-'.RS_MM_FONT_SIZE;

$arJsOptions = [
    'siteDir' => SITE_DIR,
	'fixingCompactHeader' => RS_MM_FIX_HEADER == 'Y',
	'compactHeaderSelector' => '.js-compact-header',
	'defaultPopupType' => RS_MM_POPUP_TYPE
];

$asset = Asset::getInstance();
?><!DOCTYPE html>
<html xml:lang="<?=LANGUAGE_ID?>" lang="<?=LANGUAGE_ID?>">
<head>
	<?php $APPLICATION->IncludeFile(SITE_DIR."include/template/head_start.php", array(), array("MODE"=>"html"))?>
	<?php
	$asset->addString('<link href="'.CHTTP::URN2URI('/favicon.ico').'" rel="shortcut icon"  type="image/x-icon">');
	$asset->addString('<meta http-equiv="X-UA-Compatible" content="IE=edge">');
	$asset->addString('<meta name="viewport" content="width=device-width, initial-scale=1 shrink-to-fit">');

	CJSCore::Init(['ajax', 'ls', 'main.pageobject']);
	$asset->addString('<script data-skip-moving="true">
	(function () {
		window.RS = window.RS || {};
			window.RS.Options = '.CUtil::PhpToJSObject($arJsOptions).'
		})();
	</script>', true);
	$asset->addJs('//unpkg.com/imask', 2);
	$asset->addJs(SITE_TEMPLATE_PATH.'/assets/scripts/vendor.js');
	$asset->addJs(SITE_TEMPLATE_PATH.'/assets/scripts/main.js');
	$asset->addJs(SITE_TEMPLATE_PATH.'/assets/styles/custom.js');
	$asset->addJs(SITE_DIR.'assets/styles/custom.js');

	$asset->addCss(SITE_TEMPLATE_PATH.'/assets/styles/main.css');
	$asset->addCss(SITE_TEMPLATE_PATH.'/assets/styles/vbasket.css');
	$asset->addCss(SITE_TEMPLATE_PATH.'/assets/styles/print.css');
	$asset->addCss(SITE_TEMPLATE_PATH.'/assets/styles/custom.css');
	$asset->addCss(SITE_DIR.'assets/styles/custom.css');

	switch(RS_MM_USE_FONT_FAMILY) {
		case 'open_sans':
			$asset->addString('<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i&amp;subset=cyrillic-ext" rel="stylesheet">');
			break;

		case 'pt_sans':
			$asset->addString('<link href="https://fonts.googleapis.com/css?family=PT+Sans:400,400i,700,700i&amp;subset=cyrillic-ext" rel="stylesheet">');
			break;

		case 'roboto':
			$asset->addString('<link href="https://fonts.googleapis.com/css?family=Roboto:300,300i,400,400i,500,500i,700,700i,900,900i&amp;subset=cyrillic-ext" rel="stylesheet">');
			break;

		case 'custom':
			$sFontEmbedString = \Bitrix\Main\Config\Option::get('redsign.megamart', 'global_google_font_embed_code');
			$sFontName = \Bitrix\Main\Config\Option::get('redsign.megamart', 'global_google_font_name');

			if (!empty($sFontName) && !empty($sFontEmbedString)) {
				$asset->addString($sFontEmbedString);
				$asset->addString("<style>body { font-family: '".$sFontName."', sans-serif; }</style>");
			}

			break;
	}

	$APPLICATION->ShowHead();
	?>
	<title><?php
	    $APPLICATION->ShowTitle();
	    if (
	        $curPage != SITE_DIR.'index.php' &&
	        $arSiteData['SITE_NAME'] != ''
	    ) {
	        echo ' | '. $arSiteData['SITE_NAME'];
	    }
	?></title>
	<?php $APPLICATION->IncludeFile(SITE_DIR."include/template/head_end.php", array(), array("MODE"=>"html")); ?>
</head>
<body class="<?=$sBodyClass?>"<?$APPLICATION->ShowViewContent('bodyAttributes')?>>

	<?php $APPLICATION->IncludeFile(SITE_DIR."include/template/body_start.php", array(), array("MODE"=>"html")); ?>
	<div id="svg-icons" style="display: none;"></div>

	<script>
		<?php $sIconsFileHash = \Bitrix\Main\Config\Option::get('redsign.megamart', 'icons_rand'); ?>
		$('#svg-icons').setHtmlByUrl({url:'<?=SITE_DIR?>include/icons.svg?<?=$sIconsFileHash?>'});
	</script>

	<?php
	$APPLICATION->IncludeFile(
		"include/globals/init.php",
		array(),
		array(
			'SHOW_BORDER' => false
		)
	);
	?>

	<div class="l-page">
		<div id="panel"><?php $APPLICATION->ShowPanel(); ?></div>

		<div class="l-page__compact">
			<?php
			$APPLICATION->IncludeFile(
				"include/header/compact.php",
				array(),
				array('SHOW_BORDER' => false)
			);
			?>
		</div>

		<div class="l-page__header mb-md-6">
			<?php
			$APPLICATION->IncludeFile(
				'include/header/types/'.RS_MM_HEADER_TYPE.'.php',
				array(),
				array(
					'SHOW_BORDER' => false
				)
			);
			?>
		</div>

		<div class="l-page__main">
			<?php
			if (Loader::includeModule('redsign.megamart'))
			{
				$APPLICATION->AddBufferContent(array('\Redsign\MegaMart\MyTemplate', 'getSiteHead'));
			}

			if ($request->isAjaxRequest())
			{
				$APPLICATION->restartBuffer();
				?><div><?php
			}
