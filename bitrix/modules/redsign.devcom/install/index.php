<?
global $MESS;
$strPath2Lang = str_replace("\\", "/", __FILE__);
$strPath2Lang = substr($strPath2Lang, 0, strlen($strPath2Lang)-strlen("/install/index.php"));
include(GetLangFileName($strPath2Lang."/lang/", "/install/index.php"));

Class redsign_devcom extends CModule
{
	var $MODULE_ID = "redsign.devcom";
	var $MODULE_VERSION;
	var $MODULE_VERSION_DATE;
	var $MODULE_NAME;
	var $MODULE_DESCRIPTION;
	var $MODULE_CSS;
	var $MODULE_GROUP_RIGHTS = "Y";

	function redsign_devcom()
	{
		$arModuleVersion = array();

		$path = str_replace("\\", "/", __FILE__);
		$path = substr($path, 0, strlen($path) - strlen("/index.php"));
		include($path."/version.php");

		$this->MODULE_VERSION = $arModuleVersion["VERSION"];
		$this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];

		$this->MODULE_NAME = GetMessage("REDSIGN_DEVCOM_INSTALL_NAME");
		$this->MODULE_DESCRIPTION = GetMessage("REDSIGN_DEVCOM_INSTALL_DESCRIPTION");
		$this->PARTNER_NAME = GetMessage("REDSIGN_DEVCOM_SPER_PARTNER");
		$this->PARTNER_URI = GetMessage("REDSIGN_DEVCOM_PARTNER_URI");
	}

	function InstallDB($install_wizard = true)
	{
		global $DB, $DBType, $APPLICATION;
		RegisterModule("redsign.devcom");
		return true;
	}

	function UnInstallDB($arParams = Array())
	{
		global $DB, $DBType, $APPLICATION;
		UnRegisterModule("redsign.devcom");

		return true;
	}

	function InstallEvents()
	{
		return true;
	}

	function UnInstallEvents()
	{
		return true;
	}

	function InstallFiles()
	{
		CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/redsign.devcom/install/copyfiles/components", $_SERVER["DOCUMENT_ROOT"]."/bitrix/components", true, true);


		return true;
	}

	function UnInstallFiles()
	{
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
		COption::RemoveOption('redsign.devcom');
		return true;
	}

	function DoInstall()
	{
		global $APPLICATION, $step;
		$this->InstallDB();
		$this->InstallEvents();
		$this->InstallOptions();
		$this->InstallFiles();
		$this->InstallPublic();
		$APPLICATION->IncludeAdminFile(GetMessage("REDSIGN_DEVCOM_INSTALL_TITLE"), $_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/redsign.devcom/install/install.php");
	}

	function DoUninstall()
	{
		global $APPLICATION, $step;
		$this->UnInstallFiles();
		$this->UnInstallEvents();
		$this->UnInstallOptions();
		$this->UnInstallDB();
		$this->UnInstallPublic();


		$APPLICATION->IncludeAdminFile(GetMessage("REDSIGN_DEVCOM_UNINSTALL_TITLE"), $_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/redsign.devcom/install/uninstall.php");
	}
}
?>