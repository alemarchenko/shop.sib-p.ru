<?php

namespace Redsign\DevFunc\Iblock\CustomProperty;

use Bitrix\Iblock;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

class CustomFilter
{
	const USER_TYPE = 'redsign_custom_filter';
	const CONT_ID_PREFIX = 'custom_filter_';

	public static function getDescription()
	{
		return [
			'PROPERTY_TYPE' => Iblock\PropertyTable::TYPE_STRING,
			'USER_TYPE' => self::USER_TYPE,
			'DESCRIPTION' => Loc::getMessage('RS_DF_IBLOCK_CUSTOMPROPERTY_CUSTOMFILTER_DESCRIPTION'),
			'GetPropertyFieldHtml' => [__CLASS__, 'getPropertyFieldHtml'],
			'GetSettingsHTML' => [__CLASS__, 'getSettingsHtml'],
			'ConvertToDB' => [__CLASS__, 'convertToDB'],
			'ConvertFromDB' => [__CLASS__, 'convertFromDB']
		];
	}

	public static function getPropertyFieldHtml($arProperty, $arValue, $arControlName)
	{
		ob_start();

		if (Loader::includeModule('catalog'))
		{
			$sContId = self::CONT_ID_PREFIX.$arProperty['ID'];
			$conditions = $arValue['VALUE'];

			?>
			<?=\CJSCore::Init(['redsign.devfunc.filter_conditions'], true)?>
			<script>
				(function() {
					new RS.DevFunc.FilterConditions({
						contId: '<?=$sContId?>'
					});
				}());
			</script>
			<?php

			$condTree = new \CGlobalCondTree();
			$condTree->Init(
				BT_COND_MODE_DEFAULT,
				BT_COND_BUILD_CATALOG,
				[
					'FORM_NAME' => $arControlName['FORM_NAME'],
					'PREFIX' => $arControlName['VALUE'],
					'CONT_ID' => $sContId,
					'JS_NAME' => $sContId,
				]
			);

			$condTree->Show($conditions);
			?><div id="<?=$sContId?>"></div><?php
		}

		return ob_get_clean();
	}

	public static function getSettingsHtml($arProperty, $arControlName, &$arPropertyFields)
	{
		$arPropertyFields = [
			'HIDE' => [
				'MULTIPLE',
				'SEARCHABLE',
				'FILTRABLE',
				'WITH_DESCRIPTION',
				'MUTLIPLE_CNT',
				'SMART_FILTER',
				'ROW_COUNT', 
				'COL_COUNT'
			]
		];
	}

	public static function convertToDB($arProperty, $arValue)
	{
		if (
			!Loader::includeModule('catalog') ||
			is_null($arValue['VALUE'])
		)
		{
			return '';
		}
		
		$sContId = self::CONT_ID_PREFIX.$arProperty['ID'];

		$condTree = new \CGlobalCondTree();
		$condTree->Init(BT_COND_MODE_DEFAULT, BT_COND_BUILD_CATALOG, ['JS_NAME' => $sContId]);
		$conditions = $condTree->Parse($arValue['VALUE']);

		return is_array($conditions) ? \Bitrix\Main\Web\Json::encode($conditions) : '';
	}

	public static function ConvertFromDB($arProperty, $arValue)
	{
		try
		{
			$arValue['VALUE'] = \Bitrix\Main\Web\Json::decode($arValue['VALUE']);
		}
		catch(\Bitrix\Main\SystemException $e)
		{
			$arValue['VALUE'] = [];
		}

		return $arValue;
	}
}