<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */

use \Bitrix\Main\Loader;
use \Redsign\MegaMart\ParametersUtils;

if ($arParams['ICON_PROP'] != '' && $arParams['ICON_PROP'] != '-')
{
	$arParams['ICON_PROP'] = array($arParams['IBLOCK_ID'] => $arParams['ICON_PROP']);
}
else
{
    $arParams['ICON_PROP'] = '';
}

if ($arParams['ICON_BODYMOVIN_PROP'] != '' && $arParams['ICON_BODYMOVIN_PROP'] != '-')
{
	$arParams['ICON_BODYMOVIN_PROP'] = array($arParams['IBLOCK_ID'] => $arParams['ICON_BODYMOVIN_PROP']);
}
else
{
    $arParams['ICON_BODYMOVIN_PROP'] = '';
}

if (Loader::includeModule('redsign.megamart'))
{
	$arParams['GRID_RESPONSIVE_SETTINGS'] = ParametersUtils::prepareGridSettings($arParams['~GRID_RESPONSIVE_SETTINGS']);
}

$arTemplateIcons = array(
    'align-center', 'align-justify', 'align-left', 'align-right', 'anchor', 'archive', 'arrow-down', 'arrow-left', 'arrow-right', 'arrow-thin-down', 'arrow-thin-left', 'arrow-thin-right', 'arrow-thin-up', 'arrow-up', 'article', 'backspace', 'basket', 'basketball', 'battery-empty', 'battery-full', 'battery-low', 'battery-medium', 'bell', 'blog', 'bluetooth', 'bold', 'bookmark', 'bookmarks', 'box', 'briefcase', 'brightness-low', 'brightness-max', 'brightness-medium', 'broadcast', 'browser-upload', 'browser', 'brush', 'calendar', 'camcorder', 'camera', 'card', 'cart', 'checklist', 'checkmark', 'chevron-down', 'chevron-left', 'chevron-right', 'chevron-up', 'clipboard', 'clock', 'clockwise', 'cloud-download', 'cloud-upload', 'cloud', 'code', 'contract-2', 'contract', 'conversation', 'copy', 'crop', 'cross', 'crosshair', 'cutlery', 'device-desktop', 'device-mobile', 'device-tablet', 'direction', 'disc', 'document-delete', 'document-edit', 'document-new', 'document-remove', 'document', 'dot', 'dots-2', 'dots-3', 'download', 'duplicate', 'enter', 'exit', 'expand-2', 'expand', 'experiment', 'export', 'feed', 'flag', 'flashlight', 'folder-open', 'folder', 'forward', 'gaming', 'gear', 'graduation', 'graph-bar', 'graph-line', 'graph-pie', 'headset', 'heart', 'help', 'home', 'hourglass', 'inbox', 'information', 'italic', 'jewel', 'lifting', 'lightbulb', 'link-broken', 'link', 'list', 'loading', 'location', 'lock-open', 'lock', 'mail', 'map', 'media-loop', 'media-next', 'media-pause', 'media-play', 'media-previous', 'media-record', 'media-shuffle', 'media-stop', 'medical', 'menu', 'message', 'meter', 'microphone', 'minus', 'monitor', 'move', 'music', 'network-1', 'network-2', 'network-3', 'network-4', 'network-5', 'pamphlet', 'paperclip', 'pencil', 'phone', 'photo-group', 'photo', 'pill', 'pin', 'plus', 'power', 'preview', 'print', 'pulse', 'question', 'reply-all', 'reply', 'return', 'retweet', 'rocket', 'scale', 'search', 'shopping-bag', 'skip', 'stack', 'star', 'stopwatch', 'store', 'suitcase', 'swap', 'tag-delete', 'tag', 'tags', 'thumbs-down', 'thumbs-up', 'ticket', 'time-reverse', 'to-do', 'toggles', 'trash', 'trophy', 'upload', 'user-group', 'user-id', 'user', 'vibrate', 'view-apps', 'view-list-large', 'view-list', 'view-thumb', 'volume-full', 'volume-low', 'volume-medium', 'volume-off', 'wallet', 'warning', 'web', 'weight', 'wifi', 'wrong', 'zoom-in', 'zoom-out',
);

if (is_array($arResult['ITEMS']) && count($arResult['ITEMS']) > 0)
{
    $arResult['SVG_ICONS'] = array();

    foreach ($arResult['ITEMS'] as $iItemKey => $arItem)
	{
        if ('N' != $arParams['DISPLAY_PICTURE'] && is_array($arItem['PREVIEW_PICTURE']))
		{
            $arResult['ITEMS'][$iItemKey]['PREVIEW_PICTURE']['RESIZE'] = CFile::ResizeImageGet(
                $arItem['PREVIEW_PICTURE'],
                array('width' => 200, 'height' => 200),
                BX_RESIZE_IMAGE_PROPORTIONAL,
                true
            );
		}

        if (
            $arItem['PROPERTIES'][$arParams['ICON_PROP'][$arItem['IBLOCK_ID']]]['VALUE'] != ''
            && !in_array($arItem['PROPERTIES'][$arParams['ICON_PROP'][$arItem['IBLOCK_ID']]]['VALUE'], $arTemplateIcons)
        ) {
            $arResult['SVG_ICONS'][] = $arItem['PROPERTIES'][$arParams['ICON_PROP'][$arItem['IBLOCK_ID']]]['VALUE'];
        }
    }

    if (is_array($arResult['SVG_ICONS']) && count($arResult['SVG_ICONS']) > 0)
	{
        $this->__component->arResult['SVG_ICONS'] = $arResult['SVG_ICONS'];
        $this->__component->SetResultCacheKeys(array('SVG_ICONS'));
    }
}
