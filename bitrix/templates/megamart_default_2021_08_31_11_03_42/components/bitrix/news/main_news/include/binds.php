<?php
global $arBindItemsData;
if (is_array($arBindItemsData) && count($arBindItemsData) > 0) {
	foreach ($arBindItemsData as $arBindData) {

		$APPLICATION->IncludeComponent(
			'bitrix:main.include',
			'',
			array(
				'AREA_FILE_SHOW' => 'file',
				'PATH' => $arBindData['INCLUDE_FILE'],
				'IBLOCK_ID' => $arBindData['IBLOCK_ID'],
				'BLOCK_NAME' => $arBindData['BLOCK_NAME'],
				'FILTER' => $arBindData['FILTER']
			),
			$component,
			array('HIDE_ICONS' => false)
		);
	}
}
