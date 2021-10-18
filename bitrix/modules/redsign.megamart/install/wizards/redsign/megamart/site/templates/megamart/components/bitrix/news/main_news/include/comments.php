<?php

if($arParams['USE_COMMENTS'] =='Y'){

	$APPLICATION->IncludeComponent(
		'bitrix:catalog.comments',
		'sections',
		array(
			'ELEMENT_ID' => $elementId,
			'ELEMENT_CODE' => '',
			'IBLOCK_ID' => $arParams['IBLOCK_ID'],
			'SHOW_DEACTIVATED' => $arParams['SHOW_DEACTIVATED'],
			'URL_TO_COMMENT' => '',
			'WIDTH' => '',
			'COMMENTS_COUNT' => '5',
			'BLOG_USE' => 'Y',
			'FB_USE' => $arParams['FB_USE'],
			'FB_APP_ID' => $arParams['FB_APP_ID'],
			'VK_USE' => $arParams['VK_USE'],
			'VK_API_ID' => $arParams['VK_API_ID'],
			'CACHE_TYPE' => $arParams['CACHE_TYPE'],
			'CACHE_TIME' => $arParams['CACHE_TIME'],
			'CACHE_GROUPS' => $arParams['CACHE_GROUPS'],
			'BLOG_TITLE' => '',
			'BLOG_URL' => $arParams['COMMENTS_BLOG_CODE'],
			'PATH_TO_SMILE' => '',
			'EMAIL_NOTIFY' => $arParams['BLOG_EMAIL_NOTIFY'],
			'AJAX_POST' => 'Y',
			'SHOW_SPAM' => 'Y',
			'SHOW_RATING' => 'N',
			'FB_TITLE' => '',
			'FB_USER_ADMIN_ID' => '',
			'FB_COLORSCHEME' => 'light',
			'FB_ORDER_BY' => 'reverse_time',
			'VK_TITLE' => '',
			'TEMPLATE_THEME' => $arParams['~TEMPLATE_THEME'],
			'EXTERNAL_TABS' => 'N',
			'EXTERNAL_TABS_ACTIVE' => 'N',
			'USER_CONSENT' => $arParams['COMMENTS_USE_CONSENT'],
			'USER_CONSENT_ID' => $arParams['COMMENTS_CONSENT_ID']
		),
		$component,
		array('HIDE_ICONS' => 'Y')
	);
}