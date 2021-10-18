<?
/**
 * This file is part of the wtc.easydirect module
 * @author The WebTechCom Studio,  http://www.webtechcom.ru
 * @copyright (c) The WebTechCom Studio. All Rights Reserved.
 */

define("NO_KEEP_STATISTIC", true);
define("NO_AGENT_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_js.php");

//module include
CModule::IncludeModule("wtc.easydirect");
IncludeModuleLangFile(__FILE__);
// ******************************************************************** //
//                PREPARE  DATA                          //
// ******************************************************************** //
//get max sort
$rsData =CEDirectPodborPhrases::GetList(array("SORT"=>"DESC"), array("<SORT"=>100000),false,array("SORT"));
$maxSort=500;
if ($arPhrase=$rsData->Fetch()) {
    $maxSort=$arPhrase["SORT"];
}


//WORDS
$arReplace=array("find"=>array(),"replace"=>array());
$arSelectTmpl="<b>#WORD#</b>";
$arFilter=array("TYPE"=>"S");
if(strlen($_GET['q'])>2) {
    if(!EDIRECT_UTFSITE) $_GET['q']=iconv("utf-8", "windows-1251", $_GET['q']);
    $arFilter["?NAME"]=$_GET['q'];
    //select replace
    foreach(explode(" ", $_GET['q']) as $oneword){
        $arReplace["find"][]=$oneword;
        $arReplace["replace"][]=str_replace("#WORD#",$oneword,$arSelectTmpl);
    }
}
if($_GET["minusfilter"]==1){
    $arMinus=array();
    $rsData =CEDirectPodborPhrases::GetList(array("ID"=>"ASC"), array("TYPE"=>"M"));
    while ($arPhrase=$rsData->Fetch()) {
        $arFilter["%NAME"][]=$arPhrase["NAME"];
        //select replace
        $arReplace["find"][]=$arPhrase["NAME"];
        $arReplace["replace"][]=str_ireplace("#WORD#",$arPhrase["NAME"],$arSelectTmpl);
    }
}
$rsData =CEDirectPodborPhrases::GetList(array("SORT"=>"ASC","SHOWS_QUOTES"=>"DESC","SHOWS"=>"DESC"), $arFilter);
$vivod=array();
$i=1;
$oldSort=500;
while ($arPhrase=$rsData->Fetch()) {
    $slovosm="";
    $arPhrase['NAME']=str_replace($arReplace["find"], $arReplace["replace"], $arPhrase['NAME']);
    foreach (explode(" ",$arPhrase['NAME']) as $value){
        $slovosm.='<a class="null" onclick="addMinus(\''.strip_tags($value).'\')" href="#stayhere">'.$value.'</a> ';
    }

    //group TR
    if($oldSort!=$arPhrase["SORT"]){
        $vivod[]=array("","&nbsp;","<b>--".GetMessage("EASYDIRECT_PODBOR_TBL_phrase_group")." ".$arPhrase["SORT"]."--</b>","","","","");
        $oldSort=$arPhrase["SORT"];
    }

    $vivod[]=array(
        $i,
        '<a class="del-phrase" id="phrase'.$arPhrase['ID'].'" onclick="delPhrase('.$arPhrase['ID'].')" href="#stayhere">&nbsp;X&nbsp;</a>',
        $slovosm,
        $arPhrase['SHOWS'],
        $arPhrase['SHOWS_QUOTES'],
        '<a class="edit-phrase" onclick="editPhrase('.$arPhrase['ID'].',true)" href="#stayhere">&nbsp;E&nbsp;</a>',
        '<input type="checkbox" class="delchecked" name="delPhrases[]" value="'.$arPhrase['ID'].'">'
    );
    $i++;
}

$obTblWords=new CEDirectShowTbl(
    array("N","&nbsp;",GetMessage("EASYDIRECT_PODBOR_TBL_phrase"),GetMessage("EASYDIRECT_PODBOR_TBL_shows"),GetMessage("EASYDIRECT_PODBOR_TBL_shows_quotes"),"&nbsp;",'<input onclick="selAllPhrases(this);" id="phrasedelals" type="checkbox" name="" value="">'),
    $vivod,
    array("id"=>"WtcEasydirectPhrasesTbl","style"=>"min-width: 420px;"),
    "PhrasesTbl"
    );


//MINUS
$arFilter=array("TYPE"=>"M");
$rsData =CEDirectPodborPhrases::GetList(array("NAME"=>"ASC"), $arFilter);
$vivod=array();
$i=1;
while ($arPhrase=$rsData->Fetch()) {
    $vivod[]=array(
        $i,
        '<a class="del-phrase" id="phrase'.$arPhrase['ID'].'" onclick="delPhrase('.$arPhrase['ID'].')" href="#stayhere">&nbsp;X&nbsp;</a>',
        $arPhrase['NAME'],
        '<a class="edit-phrase" onclick="editPhrase('.$arPhrase['ID'].',true)" href="#stayhere">&nbsp;E&nbsp;</a>',
        '<input type="checkbox" class="delchecked" name="delPhrases[]" value="'.$arPhrase['ID'].'">'
    );
    $i++;
}

$obTblMinus=new CEDirectShowTbl(
    array("N",'<a class="del-phrase" href="#stayhere" onclick="delAllMinusPhrases()" title="'.GetMessage("EASYDIRECT_PODBOR_TBL_phrase_delallminus").'">&nbsp;X&nbsp;</a>',GetMessage("EASYDIRECT_PODBOR_TBL_phrase"),"&nbsp;",'<input onclick="selAllPhrases(this);" id="phrasedelals" type="checkbox" name="" value="">'), 
    $vivod, 
    array("id"=>"WtcEasydirectMinusTbl","style"=>"min-width: 300px;"),
    "MinusTbl"
    );

?>

<?
// ******************************************************************** //
//               SHOW DATA                                             //
// ******************************************************************** //
$obTblWords->CheckListMode();
$obTblMinus->CheckListMode();

header('Content-Type: text/html; charset='.LANG_CHARSET);
?>

<form method="POST" action="">
<table>
	<tr><td><b><?=GetMessage("EASYDIRECT_PODBOR_TBL_phrases_name")?></b></td><td>&nbsp;</td><td><b><?=GetMessage("EASYDIRECT_PODBOR_TBL_minus_name")?></b></td></tr>
	<tr><td valign="top">
        <?       
        echo $obTblWords->ShowTbl(true);
        ?>
		<br>
        <div id="btnGroup" style="float: left;"><a href="#stayhere" onclick="showGroupForm()" class="adm-btn"><?=GetMessage("EASYDIRECT_PODBOR_TBL_phrases_togroup")?></a></div>&nbsp;
        <div id="formGroup" style="float: left; display: none;" >
            <?=GetMessage("EASYDIRECT_PODBOR_TBL_phrases_group_name")?> (<?=GetMessage("EASYDIRECT_PODBOR_TBL_phrases_group_info")?>): <input type="text" name="groupName" size="6" value="<?=($maxSort+1)?>"><input type="submit" name="phrasesToGroup" value="<?=GetMessage("EASYDIRECT_PODBOR_TBL_phrases_togroup_send")?>">
        </div>&nbsp;
		<div style="float: right;"><input type="submit" name="delPhrasesBut" value="<?=GetMessage("EASYDIRECT_PODBOR_TBL_phrases_delsel")?>"></div>
	</td>
	<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
	<td valign="top">
	    <?
        echo $obTblMinus->ShowTbl(true);
        ?>
        <br><div style="float: left;"><input type="submit" name="delForMinusBut" value="<?=GetMessage("EASYDIRECT_PODBOR_TBL_phrases_delforminus")?>"></div>&nbsp;
        <div style="float: right;"><input type="submit" name="delPhrasesBut" value="<?=GetMessage("EASYDIRECT_PODBOR_TBL_phrases_delsel")?>"></div>
	</td></tr>
</table>  	
</form>

<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin_js.php");
?>