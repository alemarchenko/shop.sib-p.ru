<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use \Bitrix\Main\Loader;
use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\Application;


Loc::loadMessages(__FILE__);

$request = Application::getInstance()->getContext()->getRequest();

if (!Loader::includeModule('redsign.favorite')) {
    ShowError(Loc::getMessage('RS.FAVORITE.RFL.FAVORITE_NOT_INSTALLED'));
    return false;
}

CJSCore::Init('rs_favorite');

$iUserId = false;
$bSaleIncluded = Loader::includeModule('sale');

$arParams['ACTION_VARIABLE'] = $arParams['ACTION_VARIABLE'] != ''
    ? $arParams['ACTION_VARIABLE']
    : 'action';
$arParams['PRODUCT_ID_VARIABLE'] = $arParams['PRODUCT_ID_VARIABLE'] != ''
    ? $arParams['PRODUCT_ID_VARIABLE']
    : 'id';
    
$ELEMENT_ID = intval($request->get($arParams['PRODUCT_ID_VARIABLE']));

if ($bSaleIncluded) {
	//ShowError(Loc::getMessage('RS.FAVORITE.RFL.SALE_NOT_INSTALLED'));
    $iUserId = CSaleBasket::GetBasketUserID();
} else {

    global $USER;
    if ($USER->IsAuthorized()) {
        $iUserId = $USER->getId();
    }

}

if ($request->get($arParams['ACTION_VARIABLE']) == 'RefreshFavorite') {
    
    if ($iUserId) {
        $dbRes = CRSFavorite::GetList(array(), array('FUSER_ID' => $iUserId));
        while ($arFields = $dbRes->Fetch()) {
            $deleteTmp = $request->get("DELETE_".$arFields['ELEMENT_ID']) == 'Y' ? 'Y' : 'N';
            if ($deleteTmp == 'Y') {
                CRSFavorite::Delete($arFields['ID']);
            }
        }
    } else {
        $_SESSION[CRSFavorite::SESSION_CODE] = array();
    }



} elseif($request->get($arParams['ACTION_VARIABLE']) == 'add2favorite' && $ELEMENT_ID > 0) {
    $res = RSFavoriteAddDel($ELEMENT_ID);
}

if ($iUserId) {

    $arFavorite = array();
    $arOrder = array();
    $arFilter = array(
        "FUSER_ID" => $iUserId,
    );
    $res = CRSFavorite::GetList($arOrder, $arFilter);
    while ($data = $res->Fetch()) {
        $arFavorite[] = $data;
    }

    $arResult["ITEMS"] = $arFavorite;

} else {
    if (is_array($_SESSION[CRSFavorite::SESSION_CODE]) && count($_SESSION[CRSFavorite::SESSION_CODE]) > 0) {
        foreach($_SESSION[CRSFavorite::SESSION_CODE] as $arItemId) {
            $arResult["ITEMS"][] = array(
                'ELEMENT_ID' => $arItemId
            );
        }
        
    } else {
        $arResult["ITEMS"] = $_SESSION[CRSFavorite::SESSION_CODE] = array();
    }
    

}

$arResult["COUNT"] = count($arResult["ITEMS"]);
    
$this->IncludeComponentTemplate();
