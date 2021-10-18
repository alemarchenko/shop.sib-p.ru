<?php

use Bitrix\Main;
use Bitrix\Main\Config\Option;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Context;
use Bitrix\Main\Mail\Event;
use Bitrix\Sale;
use Bitrix\Sale\Delivery;
use Bitrix\Sale\Order;
use Bitrix\Sale\PaySystem;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
	die();

/**
 * @var $APPLICATION CMain
 * @var $USER CUser
 */

Loc::loadMessages(__FILE__);

if (!Loader::includeModule("sale"))
{
	ShowError(Loc::getMessage("SOA_MODULE_NOT_INSTALL"));

	return;
}


class buyOneClick extends \CBitrixComponent
{
	public function onPrepareComponentParams($arParams)
	{
		global $APPLICATION,$USER;

		if (isset($arParams['CUSTOM_SITE_ID']))
		{
			$this->setSiteId($arParams['CUSTOM_SITE_ID']);
		}


		$arParams['ALLOW_AUTO_REGISTER'] = $arParams['ALLOW_AUTO_REGISTER'] === 'Y' ? 'Y' : 'N';

		if (!isset($arParams['CURRENT_PAGE']))
		{
			$arParams['CURRENT_PAGE'] = $APPLICATION->GetCurPage();
		}

		$siteId = $this->getSiteId();

		$this->arResult = [
			'PERSON_TYPE' => [],
			'PAY_SYSTEM' => [],
			'ORDER_PROP' => [],
			'DELIVERY' => [],
			'ERROR' => [],
			'BASKET_ITEMS' => [],
			'AUTH' => [],
			'SMS_AUTH' => [],
			'GOOD_SEND' => '',
			'LAST_ERROR' => '',
		];

		if ($arParams['EVENT_TYPE'] == '')
			$arParams['EVENT_TYPE'] = 'REDSIGN_BUY_ONE_CLICK';

		$arParams['REQUEST_PARAM_NAME'] = 'redsign_buy1click';

		if ($USER->IsAuthorized())
			$arParams['ALFA_USE_CAPTCHA'] = 'N';

		if (!isset($arParams['SHOW_FIELDS']) || !is_array($arParams['SHOW_FIELDS']))
		{
			$arParams['SHOW_FIELDS'] = [];
		}

		if (!isset($arParams['REQUIRED_FIELDS']) || !is_array($arParams['REQUIRED_FIELDS']))
		{
			$arParams['REQUIRED_FIELDS'] = [];
		}

		$this->arResult['AUTH']['new_user_registration'] = Option::get('main', 'new_user_registration', 'Y', $siteId) === 'Y' ? 'Y' : 'N';

		$userRegistrationEmailConfirmation = Option::get('main', 'new_user_registration_email_confirmation', 'N', $siteId);
		$this->arResult['AUTH']['new_user_registration_email_confirmation'] = $userRegistrationEmailConfirmation === 'Y' ? 'Y' : 'N';
		$this->arResult['AUTH']['new_user_email_required'] = Option::get('main', 'new_user_email_required', '', $siteId) === 'Y' ? 'Y' : 'N';

		$userPhoneAuth = Option::get('main', 'new_user_phone_auth', 'N', $siteId) === 'Y';
		$this->arResult['AUTH']['new_user_phone_auth'] = $userPhoneAuth ? 'Y' : 'N';

		$userPhoneAuthRequired = $userPhoneAuth && Option::get('main', 'new_user_phone_required', 'N', $siteId) === 'Y';
		$this->arResult['AUTH']['new_user_phone_required'] = $userPhoneAuthRequired ? 'Y' : 'N';

		if (
			$arParams['ALLOW_AUTO_REGISTER'] === 'Y'
			&& (
				$this->arResult['AUTH']['new_user_registration_email_confirmation'] === 'Y'
				|| $this->arResult['AUTH']['new_user_registration'] === 'N'
			)
		)
		{
			$arParams['ALLOW_AUTO_REGISTER'] = 'N';
		}

		$arParams['ALLOW_APPEND_ORDER'] = $arParams['ALLOW_APPEND_ORDER'] === 'N' ? 'N' : 'Y';
		$arParams['SEND_NEW_USER_NOTIFY'] = $arParams['SEND_NEW_USER_NOTIFY'] === 'N' ? 'N' : 'Y';
		return $arParams;
	}

	protected function initProperties()
	{
		$registry = Sale\Registry::getInstance(Sale\Registry::REGISTRY_TYPE_ORDER);
		/** @var \Bitrix\Sale\PropertyBase $propertyClassName */
		$propertyClassName = $registry->getPropertyClassName();

		$propertyIterator = $propertyClassName::getList([
			'select' => [
				'ID', 'PERSON_TYPE_ID', 'NAME', 'TYPE', 'REQUIRED', 'DEFAULT_VALUE', 'SORT',
				'USER_PROPS', 'IS_LOCATION', 'PROPS_GROUP_ID', 'DESCRIPTION', 'IS_EMAIL', 'IS_PROFILE_NAME',
				'IS_PAYER', 'IS_LOCATION4TAX', 'IS_FILTERED', 'CODE', 'IS_ZIP', 'IS_PHONE', 'IS_ADDRESS',
				'ACTIVE', 'UTIL', 'INPUT_FIELD_LOCATION', 'MULTIPLE', 'SETTINGS',
			],
			'filter' => [
				'=PERSON_TYPE_ID' => $this->arParams['ALFA_SALE_PERSON'],
				'=ID' => $this->arParams['SHOW_FIELDS'],
			],
			'order' => ['SORT' => 'ASC'],
		]);

		while ($property = $propertyIterator->fetch())
		{
			if ($property['IS_LOCATION'] == 'Y')
				continue;

			$property['REQUIRED_FIELDS'] = 'N';

			if ($property['REQUIRED'] == 'Y' || in_array($property['ID'], $this->arParams['REQUIRED_FIELDS']))
			{
				$property['REQUIRED_FIELDS'] = 'Y';
			}

			$this->arResult['SHOW_FIELDS'][] = $property;
		}
	}

	public function addMailTypeRedsign($eventType)
	{

		//create mail template (deprecated)
		global $DB, $DBType, $APPLICATION;

		$return = false;
		$et = new CEventType;
		$EventTypeID = $et->Add([
			'LID' => 'ru',
			'EVENT_NAME' => $eventType,
			'NAME' => Loc::getMessage('RS_DEVCOM_CRB1C_INSTALL_EVENT_TYPE_NAME'),
			'DESCRIPTION' => Loc::getMessage('RS_DEVCOM_CRB1C_INSTALL_EVENT_TYPE_DESCRIPTION')
		]);

		if ($EventTypeID > 0)
		{
			$arSites = [];
			$rsSites = CSite::GetList($by='sort', $order='desc', []);
			while ($arSite = $rsSites->Fetch())
			{
				$arSites[] = $arSite['LID'];
			}
			$arr['ACTIVE'] = 'Y';
			$arr['EVENT_NAME'] = $eventType;
			$arr['LID'] = $arSites;
			$arr['EMAIL_FROM'] = '#AUTHOR_EMAIL#';
			$arr['EMAIL_TO'] = '#EMAIL_TO#';
			$arr['BCC'] = '';
			$arr['SUBJECT'] = '#THEME#';
			$arr['BODY_TYPE'] = 'text';
			$arr['MESSAGE'] = Loc::getMessage('RS_DEVCOM_CRB1C_INSTALL_EVENT_TEMPLATE_BODY');

			$emess = new CEventMessage;
			$EventTemplateID = $emess->Add($arr);

			if ($EventTemplateID > 0)
				$return = true;
		}
		else
		{
			$return = false;
		}

		return $return;
	}

	protected function captchaInclude()
	{
		include_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/classes/general/captcha.php');
		$cpt = new CCaptcha();
		$captchaPass = Option::get('main', 'captcha_password', '');

		if (strlen($captchaPass) <= 0)
		{
			$captchaPass = randString(10);
			Option::set('main', 'captcha_password', $captchaPass);
		}

		$cpt->SetCodeCrypt($captchaPass);

		return htmlspecialchars( $cpt->GetCodeCrypt() );

	}


	public function getPropertyByCode($propertyCollection, $code)
	{
		foreach ($propertyCollection as $property)
		{
			if ($property->getField('ORDER_PROPS_ID') == $code)
				return $property;
		}
	}

	protected function itemInfo($item)
	{
		global $USER;

		// fu***ng measure ratio, can't get from ::getlist
		$itemAdd = \Bitrix\Catalog\ProductTable::getCurrentRatioWithMeasure(intval($item['ID']));
		$itemInfo['QUANTITY'] = $itemAdd[$item['ID']]['RATIO'];
		$itemInfo['NAME'] = $item['NAME'];
		$itemInfo['PRODUCT_XML_ID'] = $item['XML_ID'];
		$itemInfo['DETAIL_PAGE_URL'] = $item['DETAIL_PAGE_URL'];
		$itemInfo['CATALOG_XML_ID'] = $item['IBLOCK_EXTERNAL_ID'];

		$arPrice = CCatalogProduct::GetOptimalPrice(
			$item['ID'],
			$itemInfo['QUANTITY'],
			$USER->GetUserGroup
		);

		if (isset($arPrice['RESULT_PRICE']))
		{
			//check $order->setBasket for discount
			//$itemInfo['PRICE'] = $arPrice['RESULT_PRICE']['BASE_PRICE'];
			$itemInfo['PRICE'] = $arPrice['RESULT_PRICE']['DISCOUNT_PRICE'];
			$itemInfo['CURRENCY'] = $arPrice['PRICE']['CURRENCY'];
		}

		$itemInfo['PRODUCT_PROVIDER_CLASS'] = 'CCatalogProductProvider';

		return $itemInfo;
	}

	protected function addBasketItems($basket)
	{
		$itemsData = [];

		if (is_array($this->buy1click) && count($this->buy1click))
		{
			foreach ($this->buy1click as $code => $value)
			{
				if (strpos($code, 'QUANTITY_') !== false)
				{
					$id = (int)substr($code, 9);

					if (!isset($itemsData[$id]))
					{
						$itemsData[$id] = [];
					}

					$itemsData[$id]['POST_QUANTITY'] = $value;
				}
			}
		}
		else
		{
			$idsItems = explode(',', $this->buy1click);
			foreach ($idsItems as $arItemStr)
			{
				$arItemStr = (int)$arItemStr;

				if ($arItemStr < 1)
					continue;

				$itemsData[$arItemStr] = [];
			}
		}

		$countIds = count($itemsData);

		$arSelect = ['ID', 'NAME', 'xml_id', 'DETAIL_PAGE_URL', 'IBLOCK_EXTERNAL_ID'];
		$arFilterId = ['ID' => array_keys($itemsData)];
		$res = CIBlockElement::GetList([], $arFilterId, false, ['nPageSize'=>$countIds], $arSelect);

		while ($ob = $res->GetNextElement())
		{
			$arFields = $ob->GetFields();
			$basketItem = $basket->createItem('catalog', $arFields['ID']);
			$fieldItem = $this->itemInfo($arFields);

			if ($itemsData[$arFields['ID']]['POST_QUANTITY'])
			{
				$fieldItem['QUANTITY'] = $itemsData[$arFields['ID']]['POST_QUANTITY'];
			}

			$basketItem->setFields($fieldItem);
		}

		return $basket;
	}

	private function doOrder()
	{
		global $USER;

		$arResult =& $this->arResult;
		//$userId = $this->arParams['USER_ORDER'];

		//if user !authorized, do anonym order
		// \Bitrix\Sale\Compatible\DiscountCompatibility::stopUsageCompatible();

		$needToRegister = !$USER->IsAuthorized() && $this->arParams["ALLOW_AUTO_REGISTER"] == "Y";
		$saveToSession = false;

		if ($needToRegister)
		{
			[$userId, $saveToSession] = $this->autoRegisterUser();
		}
		else
		{
			$userId = $USER->GetID() ? $USER->GetID() : CSaleUser::GetAnonymousUserID();
		}

		$order = Order::create($this->siteId, $userId);

		$basket = Sale\Basket::create($this->siteId);
		$basket = $basket->getOrderableItems();

		$basketItems = $this->addBasketItems($basket);

		$order->setPersonTypeId($this->arParams['ALFA_SALE_PERSON']);

		$order->setBasket($basketItems);

		/* delivery */

		$shipmentCollection = $order->getShipmentCollection();
		$shipment = $shipmentCollection->createItem();
		$shipmentItemCollection = $shipment->getShipmentItemCollection();
		$shipment->setField('CURRENCY', $order->getCurrency());
		foreach ($order->getBasket() as $item)
		{
			$shipmentItem = $shipmentItemCollection->createItem($item);
			$shipmentItem->setQuantity($item->getQuantity());
		}
		$arDeliveryServiceAll = Delivery\Services\Manager::getRestrictedObjectsList($shipment);
		$shipmentCollection = $shipment->getCollection();

		if (!empty($arDeliveryServiceAll))
		{
			reset($arDeliveryServiceAll);
			$deliveryObj = current($arDeliveryServiceAll);

			if ($deliveryObj->isProfile())
			{
				$name = $deliveryObj->getNameWithParent();
			}
			else
			{
				$name = $deliveryObj->getName();
			}

			$shipment->setFields([
				'DELIVERY_ID' => $deliveryObj->getId(),
				'DELIVERY_NAME' => $name,
				'CURRENCY' => $order->getCurrency()
			]);

			$shipmentCollection->calculateDelivery();
		}
		else
		{
			$service = Delivery\Services\Manager::getById(Delivery\Services\EmptyDeliveryService::getEmptyDeliveryServiceId());
			$shipment->setFields([
				'DELIVERY_ID' => $service['ID'],
				'DELIVERY_NAME' => $service['NAME'],
				'CURRENCY' => $order->getCurrency(),
			]);
		}
		/* /Delivery */

		/* Payment */
		$arPaySystemServiceAll = [];
		$paySystemId = 1;
		$paymentCollection = $order->getPaymentCollection();

		$remainingSum = $order->getPrice() - $paymentCollection->getSum();

		if ($remainingSum > 0 || $order->getPrice() == 0)
		{
			$extPayment = $paymentCollection->createItem();
			$extPayment->setField('SUM', $remainingSum);
			$arPaySystemServices = PaySystem\Manager::getListWithRestrictions($extPayment);

			$arPaySystemServiceAll += $arPaySystemServices;

			if (array_key_exists($paySystemId, $arPaySystemServiceAll))
			{
				$arPaySystem = $arPaySystemServiceAll[$paySystemId];
			}
			else
			{
				reset($arPaySystemServiceAll);
				$arPaySystem = current($arPaySystemServiceAll);
			}

			if (!empty($arPaySystem))
			{
				$extPayment->setFields([
					'PAY_SYSTEM_ID' => $arPaySystem['ID'],
					'PAY_SYSTEM_NAME' => $arPaySystem['NAME']
				]);
			}
			else
			{
				$extPayment->delete();
			}
		}

		$order->doFinalAction(true);

		$propertyCollection = $order->getPropertyCollection();

		foreach ($arResult['SHOW_FIELDS'] as $arField)
		{
			if (strlen(trim($this->request[$arField['CODE']] != '')) > 0)
			{
				$property = $this->getPropertyByCode($propertyCollection, $arField['ID']);
				$val = htmlspecialcharsbx(trim($this->request[$arField['CODE']]));
				if ($property)
				{
					$property->setValue($val);
				}
			}
		}

		$order->setField('CURRENCY', $order->getCurrency());

		$res = $order->save();

		if ($res->isSuccess())
		{
			$orderId = $res->getId();

			if ($this->arParams['USER_CONSENT'] === 'Y')
			{
				$params = [];
				if ($this->request->get('backurl'))
				{
					$params['URL'] = $this->request->get('backurl');
				}

				Main\UserConsent\Consent::addByContext(
					$this->arParams['USER_CONSENT_ID'], 'sale/order', $orderId, $params
				);
			}

			$arOrder = CSaleOrder::GetByID($orderId);
			$this->arResult['GOOD_ORDER_ID'] = $arOrder['ACCOUNT_NUMBER'];
			$this->arResult['GOOD_ORDER_TEXT'] = Loc::getMessage('RS_DEVCOM_CRB1C_SUCCESS_ORDER_ID', ['#ORDER_ID#'=>$arOrder['ACCOUNT_NUMBER']]);

			$this->arResult['GOOD_SEND'] = 'Y';
		}
		else
		{
			$this->addError($res);
		}

		if (empty($arResult['ERROR']))
		{
			if ($saveToSession)
			{
				if (!is_array($_SESSION['SALE_ORDER_ID']))
				{
					$_SESSION['SALE_ORDER_ID'] = [];
				}

				$_SESSION['SALE_ORDER_ID'][] = $res->getId();
			}
		}
	}

	public function doAction()
	{
		$arResult =& $this->arResult;

		$arEventFields = [];
		$arEventFields['C_FIELDS']['THEME'] = Loc::getMessage('RS_DEVCOM_CRB1C_THEME');
		$arEventFields['C_FIELDS']['EMAIL_TO'] = trim($this->arParams['ALFA_EMAIL_TO']);
		$arEventFields['LID'] = $this->siteId;

		foreach ($arResult['SHOW_FIELDS'] as $key => $arField)
		{
			$arEventFields['C_FIELDS'][$arField['EVENT_FIELD_NAME']] = trim($this->request[$arField['CODE']]);

			if (in_array($arField['ID'], $this->arParams['REQUIRED_FIELDS']))
			{
					if (strlen(trim($this->request[$arField['CODE']])) <= 1)
					{
						$this->addError(Loc::getMessage('RS_DEVCOM_CRB1C_ERROR_EMPTY_REQUIRED_FIELDS'));
					}
					elseif ($arField['IS_EMAIL'] == 'Y')
					{
						if (!filter_var(trim($this->request[$arField['CODE']]), FILTER_VALIDATE_EMAIL))
						{
							$this->addError(Loc::getMessage('RS_DEVCOM_CRB1C_ERROR_WRONG_EMAIL'));
						}
					}

			}
		}

		if (\Bitrix\Main\ModuleManager::isModuleInstalled('sale') && !empty($this->buy1click))
		{
			if (empty($arResult['ERROR']))
			{
				$this->doOrder();
			}

		}
		else
		{
			if (strlen($this->request['RS_AUTHOR_ORDER_LIST']) < 5)
			{
				$this->addError(Loc::getMessage('RS_DEVCOM_CRB1C_ERROR_ITEM'));
			}

			if (empty($arResult['ERROR']))
			{
				$arFilter = ['TYPE_ID' => $this->arParams['EVENT_TYPE'], 'LID' => 'ru'];
				$rsET = CEventType::GetList($arFilter);
				if (!$arET = $rsET->Fetch())
				{
					$this->addMailTypeRedsign($this->arParams['EVENT_TYPE']);
				}

				$arEventFields['LID'] = $this->arParams['EVENT_TYPE'];

				try {
					$sendMess = Event::send($arEventFields);
				}
				catch (Exception $e)
				{
					echo 'ooops, some trouble '.$e->getMessage();
				}
			}
		}
	}

	protected function buyClickAjax()
	{
		global $APPLICATION, $USER;
		$arResult =& $this->arResult;

		$arRequest = $this->request->getValues();
		CUtil::decodeURIComponent($arRequest);
		$this->request->setValues($arRequest);

		if (check_bitrix_sessid() && !isset($this->request['PARAMS_HASH']) || $arResult['PARAMS_HASH'] === $this->request['PARAMS_HASH'])
		{
			if ($this->arParams['ALFA_USE_CAPTCHA'] == 'Y')
			{
				if (strlen($this->request['captcha_word']) < 1 && strlen($this->request['captcha_sid']) < 1)
				{
					$this->addError(Loc::getMessage('RS_DEVCOM_CRB1C_ERROR_CAPTCHA_EMPRTY'));
				}
				elseif (!$APPLICATION->CaptchaCheckCode($this->request['captcha_word'], $this->request['captcha_sid']))
				{
					$this->addError(Loc::getMessage('RS_DEVCOM_CRB1C_ERROR_CAPTCHA_WRONG'));
				}
			}

			if (empty($arResult['ERROR']))
			{
				$this->doAction();
			}

			foreach ($arResult['SHOW_FIELDS'] as $key => $arField)
			{
				$arResult['SHOW_FIELDS'][$key]['HTML_VALUE'] = $this->request[$arField['CODE']];
			}

			foreach ($arResult['SYSTEM_FIELDS'] as $key => $arField)
			{
				$arResult['SYSTEM_FIELDS'][$key]['HTML_VALUE'] = $this->request[$arField['CODE']];
			}
		}
		else
		{
			$this->addError(Loc::getMessage('RS_DEVCOM_CRB1C_ERROR_OLD_SESS'));
		}
	}

	public function executeComponent()
	{
		global $APPLICATION,$USER;

		$arResult =& $this->arResult;

		$this->buy1click = $this->request->get('RS_ORDER_IDS');

		if (is_array($this->buy1click))
		{
			if (is_array($this->buy1click) && count($this->buy1click))
			{
				foreach ($this->buy1click as $code => $value)
				{
					$arResult['SYSTEM_FIELDS'][] = [
						'CODE' => 'RS_ORDER_IDS['.$code.']',
						'HTML_VALUE' => $value,
					];
				}
			}
		}
		else
		{
			$arResult['SYSTEM_FIELDS'][] = [
				'CODE' => 'RS_ORDER_IDS',
				'HTML_VALUE' => $this->buy1click,
			];
		}

		if ($this->request->get('backurl'))
		{
			$arResult['BACKURL'] = $this->request->get('backurl');
		}

		$this->setFrameMode(false);
		$this->context = Main\Application::getInstance()->getContext();
		$this->checkSession = check_bitrix_sessid();
		$this->siteId = \Bitrix\Main\Context::getCurrent()->getSite();

		if ($this->arParams['USER_CONSENT'] === 'Y')
		{
			$this->obtainUserConsentInfo();
		}

		$isAjaxRequest = $this->request['redsign_buy1click'] == 'Y';
		$arResult['PARAMS_HASH'] = md5(serialize($this->arParams).$this->GetTemplateName());
		$arResult['ACTION_URL'] = $APPLICATION->GetCurPage();
		$arResult['FORM_NAME'] = 'form_'.(\Bitrix\Main\Security\Random::getString(6));


		$arResult['SYSTEM_FIELDS'][] = [
			'CODE' => 'RS_AUTHOR_ORDER_LIST',
		];

		foreach ($arResult['SYSTEM_FIELDS'] as $key => $arFields)
		{
			if ($this->request[$arFields['CODE']])
			{
				$arResult['SYSTEM_FIELDS'][$key]['HTML_VALUE'] = $this->request[$arFields['CODE']];
			}
		}
		unset($key, $arFields);

		$this->initProperties();

		if (
			$this->arParams['ALLOW_AUTO_REGISTER'] == 'Y'
			&& $this->arResult['AUTH']['new_user_email_required'] === 'Y'
		)
		{
			$this->arParams['ALLOW_AUTO_REGISTER'] = 'N';
			if (is_array($this->arResult['SHOW_FIELDS']) && count($this->arResult['SHOW_FIELDS']))
			{
				foreach ($this->arResult['SHOW_FIELDS'] as $arProperty)
				{
					if ($arProperty['IS_EMAIL'] == 'Y')
					{
						$this->arParams['ALLOW_AUTO_REGISTER'] = 'Y';
						break;
					}
				}
			}
		}

		if ($this->arParams['ALFA_USE_CAPTCHA'] == 'Y')
		{
			$arResult['CAPTCHA_CODE'] = $arResult['CATPCHA_CODE'] = $this->captchaInclude(); // TODO remove CATPCHA_CODE
		}

		if ($isAjaxRequest)
			$this->buyClickAjax();

		if (!empty($arResult['ERROR']))
		{
			$arResult['LAST_ERROR'] = reset($this->arResult['ERROR']);
		}

		$this->includeComponentTemplate();
	}

	protected function addError($res, $type = 'MAIN')
	{
		if ($res instanceof Result)
		{
			$errors = $res->getErrorMessages();
		}
		else
		{
			$errors = [$res];
		}

		foreach ($errors as $error)
		{
			if (!in_array($error, $this->arResult['ERROR'], true))
			{
				$this->arResult['ERROR'][] = $error;
			}
		}
	}

	/**
	 * Returns array of order properties from request
	 *
	 * @return array
	 */
	protected function getPropertyValuesFromRequest()
	{
		$orderProperties = [];

		foreach ($this->arResult['SHOW_FIELDS'] as $arField)
		{

			$v = $this->request[$arField['CODE']];
			$orderPropId = $arField['ID'];

			$orderProperties[$orderPropId] = $v;
		}

		// foreach ($this->request->getFileList() as $k => $arFileData)
		// {
		// 	if (strpos($k, "ORDER_PROP_") !== false)
		// 	{
		// 		$orderPropId = intval(substr($k, strlen("ORDER_PROP_")));

		// 		if (is_array($arFileData))
		// 		{
		// 			foreach ($arFileData as $param_name => $value)
		// 			{
		// 				if (is_array($value))
		// 				{
		// 					foreach ($value as $nIndex => $val)
		// 					{
		// 						if (strlen($arFileData["name"][$nIndex]) > 0)
		// 						{
		// 							$orderProperties[$orderPropId][$nIndex][$param_name] = $val;
		// 						}

		// 						if (!isset($orderProperties[$orderPropId][$nIndex]['ID']))
		// 						{
		// 							$orderProperties[$orderPropId][$nIndex]['ID'] = '';
		// 						}
		// 					}
		// 				}
		// 				else
		// 				{
		// 					$orderProperties[$orderPropId][$param_name] = $value;

		// 					if (!isset($orderProperties[$orderPropId]['ID']))
		// 					{
		// 						$orderProperties[$orderPropId]['ID'] = '';
		// 					}
		// 				}
		// 			}
		// 		}
		// 	}
		// }

		return $orderProperties;
	}

	/**
	 * Generation of user registration fields (login, password, etc)
	 *
	 * @param array $userProps
	 * @return array
	 * @throws Main\ArgumentNullException
	 */
	public function generateUserData($userProps = [])
	{
		global $USER;

		$userEmail = isset($userProps['EMAIL']) ? trim((string)$userProps['EMAIL']) : '';
		$newLogin = $userEmail;

		if (empty($userEmail))
		{
			$newEmail = false;
			$normalizedPhone = $this->getNormalizedPhone($userProps['PHONE']);

			if (!empty($normalizedPhone))
			{
				$newLogin = $normalizedPhone;
			}
		}
		else
		{
			$newEmail = $userEmail;
		}

		if (empty($newLogin))
		{
			$newLogin = randString(5).mt_rand(0, 99999);
		}

		$pos = strpos($newLogin, '@');
		if ($pos !== false)
		{
			$newLogin = substr($newLogin, 0, $pos);
		}

		if (strlen($newLogin) > 47)
		{
			$newLogin = substr($newLogin, 0, 47);
		}

		$newLogin = str_pad($newLogin, 3, '_');

		$dbUserLogin = CUser::GetByLogin($newLogin);
		if ($userLoginResult = $dbUserLogin->Fetch())
		{
			do
			{
				$newLoginTmp = $newLogin.mt_rand(0, 99999);
				$dbUserLogin = CUser::GetByLogin($newLoginTmp);
			} while ($userLoginResult = $dbUserLogin->Fetch());

			$newLogin = $newLoginTmp;
		}

		$newName = '';
		$newLastName = '';
		$payerName = isset($userProps['PAYER']) ? trim((string)$userProps['PAYER']) : '';

		if (!empty($payerName))
		{
			$arNames = explode(' ', $payerName);

			if (isset($arNames[1]))
			{
				$newName = $arNames[1];
				$newLastName = $arNames[0];
			}
			else
			{
				$newName = $arNames[0];
			}
		}

		$groupIds = [];
		$defaultGroups = Option::get('main', 'new_user_registration_def_group', '');

		if (!empty($defaultGroups))
		{
			$groupIds = explode(',', $defaultGroups);
		}

		$arPolicy = $USER->GetGroupPolicy($groupIds);

		$passwordMinLength = (int)$arPolicy['PASSWORD_LENGTH'];
		if ($passwordMinLength <= 0)
		{
			$passwordMinLength = 6;
		}

		$passwordChars = [
			'abcdefghijklnmopqrstuvwxyz',
			'ABCDEFGHIJKLNMOPQRSTUVWXYZ',
			'0123456789',
		];
		if ($arPolicy['PASSWORD_PUNCTUATION'] === 'Y')
		{
			$passwordChars[] = ",.<>/?;:'\"[]{}\|`~!@#\$%^&*()-_+=";
		}

		$newPassword = $newPasswordConfirm = randString($passwordMinLength + 2, $passwordChars);

		return [
			'NEW_EMAIL' => $newEmail,
			'NEW_LOGIN' => $newLogin,
			'NEW_NAME' => $newName,
			'NEW_LAST_NAME' => $newLastName,
			'NEW_PASSWORD' => $newPassword,
			'NEW_PASSWORD_CONFIRM' => $newPasswordConfirm,
			'GROUP_ID' => $groupIds,
		];
	}

	protected function getNormalizedPhone($phone)
	{
		if ($this->arParams['USE_PHONE_NORMALIZATION'] === 'Y')
		{
			$phone = NormalizePhone((string)$phone, 3);
		}

		return $phone;
	}

	protected function getNormalizedPhoneForRegistration($phone)
	{
		return Main\UserPhoneAuthTable::normalizePhoneNumber($phone) ?: '';
	}

	/**
	 * Creating new user and logging in
	 *
	 * @param $userProps
	 * @return bool|int
	 */
	protected function registerAndLogIn($userProps)
	{
		$userId = false;
		$userData = $this->generateUserData($userProps);

		$fields = [
			'LOGIN' => $userData['NEW_LOGIN'],
			'NAME' => $userData['NEW_NAME'],
			'LAST_NAME' => $userData['NEW_LAST_NAME'],
			'PASSWORD' => $userData['NEW_PASSWORD'],
			'CONFIRM_PASSWORD' => $userData['NEW_PASSWORD_CONFIRM'],
			'EMAIL' => $userData['NEW_EMAIL'],
			'GROUP_ID' => $userData['GROUP_ID'],
			'ACTIVE' => 'Y',
			'LID' => $this->getSiteId(),
			'PERSONAL_PHONE' => isset($userProps['PHONE']) ? $this->getNormalizedPhone($userProps['PHONE']) : '',
			'PERSONAL_ZIP' => isset($userProps['ZIP']) ? $userProps['ZIP'] : '',
			'PERSONAL_STREET' => isset($userProps['ADDRESS']) ? $userProps['ADDRESS'] : '',
		];

		if ($this->arResult['AUTH']['new_user_phone_auth'] === 'Y')
		{
			$fields['PHONE_NUMBER'] = isset($userProps['PHONE']) ? $userProps['PHONE'] : '';
		}

		$user = new CUser;
		$addResult = $user->Add($fields);

		if (intval($addResult) <= 0)
		{
			$this->addError(Loc::getMessage('RS_DEVCOM_CRB1C_ERROR_REG').((strlen($user->LAST_ERROR) > 0) ? ': '.$user->LAST_ERROR : ''));
		}
		else
		{
			global $USER;

			$userId = intval($addResult);
			$USER->Authorize($addResult);

			if ($USER->IsAuthorized())
			{
				if ($this->arParams['SEND_NEW_USER_NOTIFY'] == 'Y')
				{
					CUser::SendUserInfo($USER->GetID(), $this->getSiteId(), Loc::getMessage('INFO_REQ'), true);
				}
			}
			else
			{
				$this->addError(Loc::getMessage('RS_DEVCOM_CRB1C_ERROR_REG_CONFIRM'));
			}
		}

		return $userId;
	}

	/**
	 * Returns array of user id and 'save to session' flag (true if 'unique user e-mails' option
	 * active and we already have this e-mail)
	 *
	 * @return array
	 * @throws Main\ArgumentNullException
	 */
	protected function autoRegisterUser()
	{
		$personType = (int)$this->arParams['ALFA_SALE_PERSON'] ?: $this->request->get('PERSON_TYPE');
		if ($personType <= 0)
		{
			$personTypes = PersonType::load($this->getSiteId());
			foreach ($personTypes as $type)
			{
				$personType = $type['ID'];
				break;
			}

			unset($personTypes, $type);
		}

		$userProps = Sale\PropertyValue::getMeaningfulValues($personType, $this->getPropertyValuesFromRequest());
		$userId = false;
		$saveToSession = false;

		if (
			$this->arParams['ALLOW_APPEND_ORDER'] === 'Y'
			&& (
				Option::get('main', 'new_user_email_uniq_check', '') === 'Y'
				|| Option::get('main', 'new_user_phone_auth', '') === 'Y'
			)
			&& ($userProps['EMAIL'] != '' || $userProps['PHONE'] != '')
		)
		{
			$existingUserId = 0;

			if ($userProps['EMAIL'] != '')
			{
				$res = Bitrix\Main\UserTable::getRow([
					'filter' => [
						'=ACTIVE' => 'Y',
						'=EMAIL' => $userProps['EMAIL'],
					],
					'select' => ['ID'],
				]);
				if (isset($res['ID']))
				{
					$existingUserId = (int)$res['ID'];
				}
			}

			if ($existingUserId == 0 && !empty($userProps['PHONE']))
			{
				$normalizedPhone = $this->getNormalizedPhone($userProps['PHONE']);
				$normalizedPhoneForRegistration = $this->getNormalizedPhoneForRegistration($userProps['PHONE']);

				if (!empty($normalizedPhone))
				{
					$res = Bitrix\Main\UserTable::getRow([
						'filter' => [
							'ACTIVE' => 'Y',
							[
								'LOGIC' => 'OR',
								'=PHONE_AUTH.PHONE_NUMBER' => $normalizedPhoneForRegistration,
								'=PERSONAL_PHONE' => $normalizedPhone,
								'=PERSONAL_MOBILE' => $normalizedPhone,
							],
						],
						'select' => ['ID'],
					]);
					if (isset($res['ID']))
					{
						$existingUserId = (int)$res['ID'];
					}
				}
			}

			if ($existingUserId > 0)
			{
				$userId = $existingUserId;
				$saveToSession = true;
			}
			else
			{
				$userId = $this->registerAndLogIn($userProps);
			}
		}
		elseif ($userProps['EMAIL'] != '' || Option::get('main', 'new_user_email_required', '') === 'N')
		{
			$userId = $this->registerAndLogIn($userProps);
		}
		else
		{
			$this->addError(Loc::getMessage('RS_DEVCOM_CRB1C_ERROR_EMAIL'));
		}

		return [$userId, $saveToSession];
	}

	/**
	 * Obtains all order fields filled by user.
	 */
	protected function obtainUserConsentInfo()
	{
		$propertyNames = [];

		$propertyIterator = Sale\Property::getList([
			'select' => ['NAME'],
			'filter' => [
				'ID' => $this->arParams['SHOW_FIELDS'],
				'ACTIVE' => 'Y',
				'UTIL' => 'N',
				'PERSON_TYPE_SITE.SITE_ID' => $this->getSiteId(),
			],
			'order' => [
				'SORT' => 'ASC',
				'ID' => 'ASC',
			],
			'runtime' => [
				new \Bitrix\Main\Entity\ReferenceField(
					'PERSON_TYPE_SITE',
					'Bitrix\Sale\Internals\PersonTypeSiteTable',
					['=this.PERSON_TYPE_ID' => 'ref.PERSON_TYPE_ID']
				),
			],
		]);
		while ($property = $propertyIterator->fetch())
		{
			$propertyNames[] = $property['NAME'];
		}

		$this->arResult['USER_CONSENT_PROPERTY_DATA'] = $propertyNames;
	}
}
