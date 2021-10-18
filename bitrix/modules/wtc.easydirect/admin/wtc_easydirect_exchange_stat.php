<?
/**
 * This file is part of the wtc.easydirect module
 * @author The WebTechCom Studio,  http://www.webtechcom.ru
 * @copyright (c) The WebTechCom Studio. All Rights Reserved.
 */

require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");

//module include
CModule::IncludeModule("wtc.easydirect");
IncludeModuleLangFile(__FILE__);

//get POST_RIGHT
$POST_RIGHT = $APPLICATION->GetGroupRight("wtc.easydirect");
//Check POST_RIGHT
if ($POST_RIGHT < "R")
	$APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));

// ******************************************************************** //
//                 PREPARE  DATA                            //
// ******************************************************************** //
//----------Units tbl----------------
$ballsV5=$obYaExchange->getballsV5();
$strBalls="";
$vivodUnits=array(array(
    "<b>".($ballsV5===0?GetMessage("EASYDIRECT_STAT_noballs"):number_format($ballsV5[1], 0, '', ' '))."</b>",
    ($ballsV5[2]?number_format($ballsV5[2], 0, '', ' '):"-"),
    number_format($obYaExchange->getballs(), 0, '', ' ')
));
$obTblUnits=new CEDirectShowTbl(array(GetMessage("EASYDIRECT_STAT_UNIT_tbl1"),GetMessage("EASYDIRECT_STAT_UNIT_tbl2"),GetMessage("EASYDIRECT_STAT_UNIT_tbl3")), $vivodUnits);

//----------Functions tbl----------------
$rsData =CEDirectYaExchangeStat::GetList(array("CALL_CNT"=>"DESC","UNITS_COST"=>"DESC"), array());
$vivod=array();
while ($arStat=$rsData->Fetch()) {
	$vivod[]=array(
			$arStat['NAME'],
			$arStat['CALL_CNT'],
	        ($arStat['UNITS_COST']>0?$arStat['UNITS_COST']:"-")
	);
}
$obTbl=new CEDirectShowTbl(array(GetMessage("EASYDIRECT_STAT_tbl1"),GetMessage("EASYDIRECT_STAT_tbl2"),GetMessage("EASYDIRECT_STAT_tbl3")), $vivod);

// ******************************************************************** //
//                SHOW DATA                                                 //
// ******************************************************************** //
// SET TITLE
$APPLICATION->SetTitle(GetMessage("EASYDIRECT_STAT_title"));
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");
?>

<?
//show code to append HELP Button/Link to Title
echo CEDirectHelp::showLink(__FILE__);
?>

<?
    echo $obTblUnits->ShowTbl();
    echo "<br>";
	echo $obTbl->ShowTbl();
?>

<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");
?>