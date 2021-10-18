<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true)die();

if(!CModule::IncludeModule('iblock'))
	return;

$arFilterIBlocks = array(
	array(
		'IBLOCK_TYPE' => 'catalog',
		'IBLOCK_CODE' => 'catalog',
		'IBLOCK_XML_ID' => 'redsign_megamart_catalog_catalog_'.WIZARD_SITE_ID,
	),
	array(
		'IBLOCK_TYPE' => 'catalog',
		'IBLOCK_CODE' => 'services',
		'IBLOCK_XML_ID' => 'redsign_megamart_catalog_services_'.WIZARD_SITE_ID,
	),
	array(
		'IBLOCK_TYPE' => 'content',
		'IBLOCK_CODE' => 'news',
		'IBLOCK_XML_ID' => 'redsign_megamart_content_news_'.WIZARD_SITE_ID,
	),
	array(
		'IBLOCK_TYPE' => 'content',
		'IBLOCK_CODE' => 'articles',
		'IBLOCK_XML_ID' => 'redsign_megamart_content_articles_'.WIZARD_SITE_ID,
	),
	array(
		'IBLOCK_TYPE' => 'content',
		'IBLOCK_CODE' => 'sale_promotions',
		'IBLOCK_XML_ID' => 'redsign_megamart_content_sale_promotions_'.WIZARD_SITE_ID,
	),
	array(
		'IBLOCK_TYPE' => 'content',
		'IBLOCK_CODE' => 'brands',
		'IBLOCK_XML_ID' => 'redsign_megamart_content_brands_'.WIZARD_SITE_ID,
	),
	array(
		'IBLOCK_TYPE' => 'content',
		'IBLOCK_CODE' => 'staff',
		'IBLOCK_XML_ID' => 'redsign_megamart_content_staff_'.WIZARD_SITE_ID,
	),
);

$arrFilterIBlock = array(
	'catalog' => array(
		'RECOMMEND' => 'catalog',
		'DEALS_REF' => 'sale_promotions',
		'BRAND_REF' => 'brands',
		'ACCESSORIES' => 'catalog',
	),
	'sale_promotions' => array(
		'BIND_CATALOG' => 'catalog',
		'INTERESTING_NEWS' => 'news',
		'INTERESTING_ARTICLE' => 'articles',
		'INTERESTING_SALE' => 'sale_promotions',
		'BIND_SERVICE' => 'services',
		'BIND_STAFF' => 'staff',
	),
	'news' => array(
		'BIND_CATALOG' => 'catalog',
		'INTERESTING_NEWS' => 'news',
		'INTERESTING_ARTICLE' => 'articles',
		'INTERESTING_SALE' => 'sale_promotions',
		'BIND_SERVICE' => 'services',
		'BIND_STAFF' => 'staff',
		'BIND_STAFF2' => 'staff',
	),
	'articles' => array(
		'BIND_CATALOG' => 'catalog',
		'INTERESTING_NEWS' => 'news',
		'INTERESTING_ARTICLE' => 'articles',
		'INTERESTING_SALE' => 'sale_promotions',
		'BIND_SERVICE' => 'services',
		'BIND_STAFF' => 'staff',
		'BIND_STAFF2' => 'staff',
	),
);

$arrIBlockIDs = array();

foreach ($arFilterIBlocks as $arFilterIBlock)
{
	$rsIBlock = CIBlock::GetList(
		array(),
		array(
			'TYPE' => $arFilterIBlock['IBLOCK_TYPE'],
			'CODE' => $arFilterIBlock['IBLOCK_CODE'],
			'XML_ID' => $arFilterIBlock['IBLOCK_XML_ID']
		)
	);

	if ($arIBlock = $rsIBlock->Fetch())
	{
		$arrIBlockIDs[$arFilterIBlock['IBLOCK_CODE']] = $arIBlock['ID'];
	}
	unset($arIBlock, $rsIBlock);
}
unset($arFilterIBlock);

foreach ($arrFilterIBlock as $sIBlockCode => $arIBlockProps)
{
	foreach ($arIBlockProps as $sPropCode => $sIBlockLinkCode)
	{
		$dbProp = CIBlockProperty::GetList(
			array(),
			array(
				'IBLOCK_ID' => $arrIBlockIDs[$sIBlockCode],
				'CODE' => $sPropCode
			)
		);
		if ($arProp = $dbProp->GetNext())
		{
			$arFields = Array(
				'LINK_IBLOCK_ID' => $arrIBlockIDs[$sIBlockLinkCode],
			);

			$ibp = new CIBlockProperty;
			$ibp->Update($arProp['ID'], $arFields);
			unset($ibp);
		}
	}
	unset($sPropCode, $sIBlockLinkCode);
}
unset($sIBlockCode, $arIBlockProps);
