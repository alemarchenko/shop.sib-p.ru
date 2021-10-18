<?php

namespace Redsign\Ravorite;

use \Bitrix\Main\Engine\Contract\Controllerable;
use \Bitrix\Main\Loader;

class FavoriteList extends \CBitrixComponent implements Controllerable
{
	const PARAM_TITLE_MASK = '/^[A-Za-z_][A-Za-z01-9_]*$/';
	const LIKES_COUNT_PROP = 'LIKES_COUNT';

	public function onPrepareComponentParams($params)
	{
		$this->useSale = Loader::includeModule('sale');

		$params['ACTION_VARIABLE'] = isset($params['ACTION_VARIABLE']) ? trim($params['ACTION_VARIABLE']) : '';
		if ($params['ACTION_VARIABLE'] == '' || !preg_match(self::PARAM_TITLE_MASK, $params['ACTION_VARIABLE']))
		{
			$params['ACTION_VARIABLE'] = 'action';
		}

		$params['PRODUCT_ID_VARIABLE'] = isset($params['PRODUCT_ID_VARIABLE']) ? trim($params['PRODUCT_ID_VARIABLE']) : '';
		if ($params['PRODUCT_ID_VARIABLE'] == '' || !preg_match(self::PARAM_TITLE_MASK, $params['PRODUCT_ID_VARIABLE']))
		{
			$params['PRODUCT_ID_VARIABLE'] = 'id';
		}

		return $params;
	}

	public function configureActions()
	{
		return [
			'add' => [
				'prefilters' => [],
			],
			'remove' => [
				'prefilters' => [],
			],
			'refresh' => [
				'prefilters' => [],
			],
		];
	}

	public function addAction($post)
	{
		$actionResult = [
			'STATUS' => 'OK',
			'ACTION' => 'ADD',
			'TOTAL' => 0,
			'LIKES_COUNT' => 0,
			'MESSAGE' => '',
		];

		$iElementId = (int)$post['element_id'];
		if ($iElementId <= 0)
		{
			$actionResult['STATUS'] = 'ERROR';
			$actionResult['MESSAGE'] = 'field id is empty';
		}

		if (!Loader::includeModule('redsign.favorite'))
		{
			$actionResult['STATUS'] = 'ERROR';
			$actionResult['MESSAGE'] = 'module redsign.favorite not installed';
		}

		$userId = $this->getUserId();
		$bSuccessful = true;

		if ($userId)
		{
			$dbRes = \CRSFavorite::GetList(
				[],
				[
					'FUSER_ID' => $userId,
					'ELEMENT_ID' => $iElementId
				]
			);

			if ($arFields = $dbRes->Fetch())
			{
				$actionResult['MESSAGE'] = 'already added';
				$bSuccessful = false;
			}
			else
			{
				$arFields = [
					'FUSER_ID' => $this->getUserId(),
					'ELEMENT_ID' => $iElementId,
					'PRODUCT_ID' => 0,
				];

				\CRSFavorite::Add($arFields);
			}
		}
		else
		{
			if (
				is_array($_SESSION[\CRSFavorite::SESSION_CODE])
				&& ($i = array_search($iElementId, $_SESSION[\CRSFavorite::SESSION_CODE])) !== false
			){
				$actionResult['MESSAGE'] = 'already added';
				$bSuccessful = false;
			}
			else
			{
				$_SESSION[\CRSFavorite::SESSION_CODE][] = $iElementId;
			}
		}

		if (Loader::includeModule('iblock'))
		{
			$arOrder = [];
			$arFilter = [
				'=ID' => $iElementId,
			];
			$arGroupBy = false;
			$arNavStartParams = false;
			$arSelectFields = [
				'ID',
				'PROPERTY_'.self::LIKES_COUNT_PROP
			];

			$dbElement = \CIBlockElement::GetList($arOrder, $arFilter, $arGroupBy, $arNavStartParams, $arSelectFields);

			if ($arElement = $dbElement->GetNext())
			{
				if (array_key_exists('PROPERTY_'.self::LIKES_COUNT_PROP.'_VALUE', $arElement))
				{
					$iElementCount = (int)$arElement['PROPERTY_'.self::LIKES_COUNT_PROP.'_VALUE'] > 0
						? $arElement['PROPERTY_'.self::LIKES_COUNT_PROP.'_VALUE']
						: 0 ;

					if ($bSuccessful)
					{
						$iElementCount++;

						\CIBlockElement::SetPropertyValueCode(
							$arElement['ID'],
							self::LIKES_COUNT_PROP,
							$iElementCount
						);
					}

					$actionResult['LIKES_COUNT'] = $iElementCount;
				}
			}
		}

		return $actionResult;
	}

	public function removeAction($post)
	{
		$actionResult = [
			'STATUS' => 'OK',
			'ACTION' => 'REMOVE',
			'TOTAL' => 0,
			'LIKES_COUNT' => 0,
			'MESSAGE' => '',
		];

		$iElementId = (int)$post['element_id'];
		if ($iElementId <= 0)
		{
			$actionResult['STATUS'] = 'ERROR';
			$actionResult['MESSAGE'] = 'field id is empty';
		}

		if (!Loader::includeModule('redsign.favorite'))
		{
			$actionResult['STATUS'] = 'ERROR';
			$actionResult['MESSAGE'] = 'module redsign.favorite not installed';
		}

		$userId = $this->getUserId();
		$bSuccessful = true;

		if ($userId)
		{
			$dbRes = \CRSFavorite::GetList(
				[],
				[
					'FUSER_ID' => $userId,
					'ELEMENT_ID' => $iElementId
				]
			);

			if ($arFields = $dbRes->Fetch())
			{
				\CRSFavorite::Delete($arFields['ID']);
			}
			else
			{
				$actionResult['MESSAGE'] = 'not exist';
				$bSuccessful = false;
			}
		}
		else
		{
			if (
				is_array($_SESSION[\CRSFavorite::SESSION_CODE])
				&& ($i = array_search($iElementId, $_SESSION[\CRSFavorite::SESSION_CODE])) !== false
			){
				unset($_SESSION[\CRSFavorite::SESSION_CODE][$i]);
			}
			else
			{
				$actionResult['MESSAGE'] = 'not exist';
				$bSuccessful = false;
			}
		}

		if (Loader::includeModule('iblock'))
		{
			$arOrder = [];
			$arFilter = [
				'=ID' => $iElementId,
			];
			$arGroupBy = false;
			$arNavStartParams = false;
			$arSelectFields = [
				'ID',
				'PROPERTY_'.self::LIKES_COUNT_PROP
			];

			$dbElement = \CIBlockElement::GetList($arOrder, $arFilter, $arGroupBy, $arNavStartParams, $arSelectFields);

			if ($arElement = $dbElement->GetNext())
			{
				if (array_key_exists('PROPERTY_'.self::LIKES_COUNT_PROP.'_VALUE', $arElement))
				{
					$iElementCount = (int)$arElement['PROPERTY_'.self::LIKES_COUNT_PROP.'_VALUE'] > 0
						? $arElement['PROPERTY_'.self::LIKES_COUNT_PROP.'_VALUE']
						: 0 ;

					if ($bSuccessful)
					{
						$iElementCount = --$iElementCount > 0
							? $iElementCount
							: 0;

						\CIBlockElement::SetPropertyValueCode(
							$arElement['ID'],
							self::LIKES_COUNT_PROP,
							$iElementCount
						);
					}

					$actionResult['LIKES_COUNT'] = $iElementCount;
				}
			}
		}

		return $actionResult;
	}

	public function updateAction($post)
	{
	}

	private function getUserId()
	{
		$userId = false;
		if ($this->useSale)
		{
			$userId = \CSaleBasket::GetBasketUserID();
		}
		else
		{
			global $USER;
			if ($USER->IsAuthorized())
			{
				$userId = $USER->getId();
			}
		}

		return $userId;
	}

	public function executeComponent()
	{
		$userId = $this->getUserId();

		if (!Loader::includeModule('redsign.favorite'))
			return;

		$ELEMENT_ID = (int) $this->request->get($this->arParams['PRODUCT_ID_VARIABLE']);

		if ($this->request->get($this->arParams['ACTION_VARIABLE']) == 'RefreshFavorite')
		{
			if ($userId)
			{
				$dbRes = \CRSFavorite::GetList(array(), array('FUSER_ID' => $userId));
				while ($arFields = $dbRes->Fetch())
				{
					$deleteTmp = $this->request->get('DELETE_'.$arFields['ELEMENT_ID']) == 'Y' ? 'Y' : 'N';
					if ($deleteTmp == 'Y')
					{
						\CRSFavorite::Delete($arFields['ID']);
					}
				}
			}
			else
			{
				$_SESSION[\CRSFavorite::SESSION_CODE] = array();
			}
		}
		elseif ($this->request->get($this->arParams['ACTION_VARIABLE']) == 'add2favorite' && $ELEMENT_ID > 0)
		{
			$res = RSFavoriteAddDel($ELEMENT_ID);
		}

		if ($userId)
		{
			$arFavorite = [];
			$arOrder = [];
			$arFilter = [
				'FUSER_ID' => $userId,
			];
			$res = \CRSFavorite::GetList($arOrder, $arFilter);
			while ($data = $res->Fetch())
			{
				$arFavorite[] = $data;
			}

			$this->arResult['ITEMS'] = $arFavorite;
		}
		else
		{
			if (is_array($_SESSION[\CRSFavorite::SESSION_CODE]) && count($_SESSION[\CRSFavorite::SESSION_CODE]) > 0)
			{
				foreach ($_SESSION[\CRSFavorite::SESSION_CODE] as $arItemId)
				{
					$this->arResult['ITEMS'][] = [
						'ELEMENT_ID' => $arItemId
					];
				}
			}
			else
			{
				$this->arResult['ITEMS'] = $_SESSION[\CRSFavorite::SESSION_CODE] = [];
			}
		}

		$this->arResult['COUNT'] = count($this->arResult['ITEMS']);

		$this->includeComponentTemplate();
	}
}