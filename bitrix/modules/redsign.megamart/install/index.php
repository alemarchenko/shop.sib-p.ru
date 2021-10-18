<?php
use Bitrix\Main\EventManager;
use Bitrix\Main\ModuleManager;
use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);


Class redsign_megamart extends CModule
{
	var $MODULE_ID = "redsign.megamart";
	var $MODULE_VERSION;
	var $MODULE_VERSION_DATE;
	var $MODULE_NAME;
	var $MODULE_DESCRIPTION;
	var $MODULE_CSS;
	var $MODULE_GROUP_RIGHTS = "Y";

	function __construct()
	{
		$arModuleVersion = array();

		include(__DIR__.'/version.php');

		$this->MODULE_VERSION = $arModuleVersion["VERSION"];
		$this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];

		$this->MODULE_NAME = GetMessage("RS_MM_INSTALL_NAME");
		$this->MODULE_DESCRIPTION = GetMessage("RS_MM_INSTALL_DESCRIPTION");
		$this->PARTNER_NAME = GetMessage("RS_MM_SPER_PARTNER");
		$this->PARTNER_URI = GetMessage("RS_MM_PARTNER_URI");
	}


	function InstallDB($install_wizard = true)
	{
		global $DB, $DBType, $APPLICATION;

		ModuleManager::registerModule($this->MODULE_ID);
		RegisterModuleDependences("main", "OnBeforeProlog", "redsign.megamart", "\Redsign\MegaMart\MyTemplate", "ShowPanel");
		COption::SetOptionString("redsign.megamart", "wizard_version", "1");

		return true;
	}

	function UnInstallDB($arParams = Array())
	{
		global $DB, $DBType, $APPLICATION;

		ModuleManager::unregisterModule($this->MODULE_ID);
		UnRegisterModuleDependences("main", "OnBeforeProlog", "redsign.megamart", "\Redsign\MegaMart\MyTemplate", "ShowPanel");
		return true;
	}

	function InstallEvents()
	{
		EventManager::getInstance()->registerEventHandler(
			'redsign.tuning',
			'onBeforeGetReadyMacros',
			$this->MODULE_ID,
			'Redsign\\MegaMart\\MyTemplate',
			'rsTuningOnBeforeGetReadyMacros'
		);

		return true;
	}

	function UnInstallEvents()
	{
		EventManager::getInstance()->unRegisterEventHandler(
			'redsign.tuning',
			'onBeforeGetReadyMacros',
			$this->MODULE_ID,
			'Redsign\\MegaMart\\MyTemplate',
			'rsTuningOnBeforeGetReadyMacros'
		);

		return true;
	}

	function InstallFiles()
	{
		CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/redsign.megamart/install/modules", $_SERVER["DOCUMENT_ROOT"]."/bitrix/modules", true, true);
		CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/redsign.megamart/install/components", $_SERVER["DOCUMENT_ROOT"]."/bitrix/components", true, true);
		//CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/redsign.megamart/install/wizards/bitrix/eshop.mobile", $_SERVER["DOCUMENT_ROOT"]."/bitrix/wizards/bitrix/eshop.mobile", true, true);
		//CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/redsign.megamart/install/images",  $_SERVER["DOCUMENT_ROOT"]."/bitrix/images/redsign.megamart", true, true);

		CopyDirFiles($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/redsign.megamart/install/js', $_SERVER['DOCUMENT_ROOT'].'/bitrix/js', true, true);
		CopyDirFiles($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/redsign.megamart/install/panel', $_SERVER['DOCUMENT_ROOT'].'/bitrix/panel', true, true);

		return true;
	}

	function UnInstallFiles()
	{
		//DeleteDirFilesEx("/bitrix/images/redsign.megamart/");//images
		DeleteDirFilesEx("/bitrix/js/redsign.megamart/");
		DeleteDirFilesEx("/bitrix/wizards/redsign/megamart/");
		DeleteDirFilesEx("/bitrix/panel/redsign.megamart/");

		return true;
	}

	function InstallPublic()
	{
		return true;
	}

	function UnInstallPublic()
	{
		return true;
	}

	function InstallOptions()
	{
		return true;
	}

	function UnInstallOptions()
	{
		COption::RemoveOption('redsign.megamart');
		return true;
	}

	function DoInstall()
	{
		global $APPLICATION, $step;

		$this->InstallFiles();
		$this->InstallDB(false);
		$this->InstallEvents();
		$this->InstallPublic();

		return true;
	}

	function DoUninstall()
	{
		global $APPLICATION, $step;

		$this->UnInstallDB();
		$this->UnInstallFiles();
		$this->UnInstallEvents();

		return true;
	}
}
?>