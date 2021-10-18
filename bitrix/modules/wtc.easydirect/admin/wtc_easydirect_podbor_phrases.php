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
if ($POST_RIGHT < "W")
	$APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));

// ******************************************************************** //
//                 PROCESS  DATA                            //
// ******************************************************************** //
$delPhrasesCnt=0;
if(isset($_POST['submitAddPhrases'])){
    if($_POST['addfromcompany']>0){
        //get phrases from banners
        $resp=CEDirectPhrase::GetListEx(array("BANNER_GROUP.ID"=>"ASC"),array("COMPANY.ID"=>$_POST['addfromcompany']));
        $SORT=500;
        $BANNER_GROUP_ID=0;
        while ($arElement=$resp->Fetch()) {
            if($BANNER_GROUP_ID!=$arElement['BANNER_GROUP_ID']){
                $BANNER_GROUP_ID=$arElement['BANNER_GROUP_ID'];
                $SORT=$BANNER_GROUP_ID;
            }
            //delete all specsimv and minus words
            $arElement['NAME']=CEDirectPhrase::stripPhrase($arElement['NAME']);
            $arFields=array(
                "NAME" => $arElement['NAME'],
                "SHOWS" => 0,
                "SHOWS_QUOTES" => 0,
                "TYPE" => "S",
                "SORT"=>$SORT
            );
            CEDirectPodborPhrases::Add($arFields);            
        }
        //get minus words        
        $arCampaign=$obYaExchange->getCompanyParams($_POST['addfromcompany']);
        if(is_array($arCampaign)){
            $arCampaign=$arCampaign[0];
            if(is_array($arCampaign['NegativeKeywords']['Items'])&&count($arCampaign['NegativeKeywords']['Items'])) {
                foreach ($arCampaign['NegativeKeywords']['Items'] as $minus){
                    $arFields=array(
                        "NAME" =>trim($minus),
                        "TYPE" => "M",
                    );
                    CEDirectPodborPhrases::Add($arFields);
                }        
            }
        }
    }
    else 
    {
        $phrases=explode("\n",$_POST['phrases']);
        $SORT=500;
        foreach ($phrases as $phrase){
            $phrase=trim($phrase);
            if(preg_match("/--([^0-9]*)([0-9]*)--/",$phrase,$regs)) {$SORT=$regs[2]; continue;}
            if(strlen($phrase)>2){
                //import shows
                $shows=0;
                $showsq=0;
                if(preg_match("/([^0-9]+)\t([0-9]+)(\t*)([0-9]*)/",$phrase,$regs)){
                    $phrase=$regs[1];
                    $shows=$regs[2];
                    $showsq=$regs[4];
                }
                
                //type of words            
                if($_POST["addToMinus"]=="Y") $type="M";
                else $type="S";
                
                $arFields=array(
                    "NAME" => trim($phrase),
                    "SHOWS" => $shows,
                    "SHOWS_QUOTES" => $showsq,
                    "TYPE" => $type,
                    "SORT"=>$SORT
                );
                CEDirectPodborPhrases::Add($arFields);
            }
        }
    }
}
else if($_POST["delPhrasesBut"]){
    foreach ($_POST["delPhrases"] as $phraseID){
        if(CEDirectPodborPhrases::Delete($phraseID)) $delPhrasesCnt++;
    }
}
else if($_POST["phrasesToGroup"]){
   $groupName=intval($_POST["groupName"]);
   if($groupName>0){
       foreach ($_POST["delPhrases"] as $phraseID){
           CEDirectPodborPhrases::Update($phraseID,array("SORT"=>$groupName));
       }       
   }
}
else if($_POST["delForMinusBut"]){
    $rsData =CEDirectPodborPhrases::GetList(array("ID"=>"ASC"), array("TYPE"=>"M"));
    while ($arPhrase=$rsData->Fetch()) {
        $delPhrasesCnt+=CEDirectPodborPhrases::DeleteForMatch($arPhrase["NAME"]);
    }
}
else if($_POST["delManualPhrasesBut"]){
    if($_POST['delfromcompany']>0){
        $delPhrasesCnt=0;
        //get phrases from banners
        $resp=CEDirectPhrase::GetListEx(array("BANNER_GROUP.ID"=>"ASC"),array("COMPANY.ID"=>$_POST['delfromcompany']));
        while ($arElement=$resp->Fetch()) {
            $rsData =CEDirectPodborPhrases::GetList(array(), array("NAME"=>array(CEDirectPhrase::stripPhrase($arElement['NAME']),$arElement['NAME']),"TYPE"=>"S"));
            while ($arPhrase=$rsData->Fetch()) {
                CEDirectPodborPhrases::Delete($arPhrase["ID"]);
                $delPhrasesCnt++;
            }
        }
    }
    else {
        $phrases=explode("\n",$_POST['delManualPhrases']);
        foreach ($phrases as $phrase){
            $phrase=trim($phrase);
            if(strlen($phrase)>2){
                $delPhrasesCnt+=CEDirectPodborPhrases::DeleteForMatch($phrase);
            }
        }
    }
}

//CNT Message
if($delPhrasesCnt>0) $message=GetMessage("EASYDIRECT_PODBOR_delete_mess",array("#COUNT#"=>$delPhrasesCnt));


// ******************************************************************** //
//                SHOW DATA                                                 //
// ******************************************************************** //
// SET TITLE
$APPLICATION->SetTitle(GetMessage("EASYDIRECT_PODBOR_title"));
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");
?>
<?
//-------SHOW MESSAGE---------------
if(strlen($message)>5) CAdminMessage::ShowMessage(array("MESSAGE"=>$message, "TYPE"=>"OK", "DETAILS"=>"", "HTML"=>true));
//-------SHOW MESSAGE---------------
?>

<?
//show code to append HELP Button/Link to Title
echo CEDirectHelp::showLink(__FILE__);
?>

<?php CJSCore::Init(array("jquery"));?>
<script language="JavaScript">
$.ajaxSetup({cache: false}); 

//----interface------------
function showAddForm(){
	hideAllMessage();
	$('#addPhrasesForm').show();
	$('#showTbl').hide();
}
function showMassDel(){
	$('#massdel').show();
	$('#filterTableBlock').hide();
}
function showError(message){
	$('#errorMessage').find(".adm-info-message-title").html(message);
	$('#errorMessage').show();
}
function showGreenMessage(message,wait){
	if(wait){$('#greenMessage').find(".adm-info-message-icon").addClass("wait-icon");}
	else {$('#greenMessage').find(".adm-info-message-icon").removeClass("wait-icon");}
	$('#greenMessage').find(".adm-info-message-title").html(message);
	$('#greenMessage').show();
}
function selAllPhrases(mainCheckbox){
	if($(mainCheckbox).attr('checked')){
		$(mainCheckbox).parent().parent().parent().find('.delchecked').attr('checked',true);
	}
	else{
		$(mainCheckbox).parent().parent().parent().find('.delchecked').attr('checked',false);
	}
}
function hideAllMessage(){
    $('#errorMessage').hide();
    $('#reportMessage').hide();
    $('#greenMessage').hide();
    $('.adm-info-message-wrap').hide();
}

//-----Word GROUPS----
function showGroupForm(){
	$('#btnGroup').hide();
	$('#formGroup').show();
}

//---------------------------

//------work functions---
function addNewPhrasesToCompany(){
	hideAllMessage();
	showGreenMessage('<?=GetMessage("EASYDIRECT_PODBOR_start_add_mess")?>',true);
	$.post(
			"/bitrix/admin/wtc_easydirect_ajax_podbor_exec.php",
			{CMD: "addPhrasesToCompany"}, 
			function(data){
				data=JSON.parse(data);
				if(data["ERROR"]==0)
				{
					showGreenMessage(data["ERROR_TXT"],false);
				}
				else 
				{
					$('#greenMessage').hide();
					showError(data["ERROR_TXT"]);
				}					
			}
			);
}

function addMinus(phrase){
	$.post(
			"/bitrix/admin/wtc_easydirect_ajax_podbor_exec.php",
			{CMD: "addMinus", NAME: phrase}, 
			function(data){
				data=JSON.parse(data);
				if(data["ERROR"]==0)
				{
					$("#WtcEasydirectMinusTbl").append(data["APPEND_STR"]);
				}
			}
			);
}

function delPhrase(id){
	$.post(
			"/bitrix/admin/wtc_easydirect_ajax_podbor_exec.php",
			{CMD: "delPhrase", ID: id}, 
			function(data){
				data=JSON.parse(data);
				if(data["ERROR"]==0)
				{
					$("#phrase"+data["ID"]).parent().parent().remove();
				}
			}
			);	
}

function delAllMinusPhrases(){
	if(confirm('<?=GetMessage("EASYDIRECT_PODBOR_delallminus_note")?>')){
    	$.post(
    			"/bitrix/admin/wtc_easydirect_ajax_podbor_exec.php",
    			{CMD: "delAllMinusPhrases"}, 
    			function(data){
    				data=JSON.parse(data);
    				if(data["ERROR"]==0)
    				{
    					showPhrasesTbl();
    				}
    			}
    			);	
	}
}

function editPhrase(id,isshow){
	$.post(
			"/bitrix/admin/wtc_easydirect_ajax_podbor_exec.php",
			{CMD: "editPhrase", ID: id, ISSHOW: isshow, 
				NAME: $("#editName"+id).val(),
				SHOWS: $("#editShows"+id).val(),
				SHOWS_QUOTES: $("#editShowsQuotes"+id).val()
			}, 
			function(data){
				data=JSON.parse(data);
				if(data["ERROR"]==0)
				{
					$("#phrase"+data["ID"]).parent().parent().html(data["APPEND_STR"]);
					$("#editName"+data["ID"]).keydown('keydown', function(e) {
    						if (e.keyCode === 13) {
    							$(this).parent().parent().find(".adm-btn").click();
        						return false;
    						}
						});
				}
				else 
				{
					showError(data["ERROR_TXT"]);
				}	
			}
			);			
}

function clearAll(){
	$.post(
			"/bitrix/admin/wtc_easydirect_ajax_podbor_exec.php",
			{CMD: "clearAll"},
			function(){showPhrasesTbl();}			
			);
}

function startGetReports(checkQuotes){
	hideAllMessage();
	
	if( !$('#reportMessage').is(':visible') )
	{
		$('#reportMessage').show();
		$('#showTbl').hide();
	}
	
	$.post(
			"/bitrix/admin/wtc_easydirect_ajax_podbor_exec.php",
			{CMD: "clearReports",CHECK_QUOTES: checkQuotes},
			function(data){
				data=JSON.parse(data);
				if(data["CHECK_QUOTES"]) getWsreportInfo(true);
				else getWsreportInfo();
			}	
			);
}

function getWsreportInfo(checkQuotes)
{
	$.post(
			"/bitrix/admin/wtc_easydirect_ajax_podbor_exec.php",
			{CMD: "getWsreport",CHECK_QUOTES: checkQuotes},
			function(data){
				data=JSON.parse(data);
				if(data["ERROR"]==0)
				{
					if(data["CNT"]==0)
					{
						$('#reportMessage').hide();
						$('#showTbl').show();
						showPhrasesTbl();
					}
					else 
					{
						$("#wsreportCnt").html(data["CNT"]);
						if(data["CHECK_QUOTES"]) setTimeout("getWsreportInfo(true)",10000);
						else setTimeout("getWsreportInfo()",10000);
					}
				}
				else 
				{
					$('#reportMessage').hide();
					showError(data["ERROR_TXT"]);
				}	
			}
			);
}

var timeout_id;
function filterPhrases(){	
	var q;
	q=$("#search").val();
	if(q.length>2){
		if(timeout_id) clearTimeout(timeout_id);
		timeout_id=setTimeout("showPhrasesTbl()",500);
	}
	else if(q.length==0){
		filterClear();
	}
}

function filterClear(){	
	$("#search").val("");
	showPhrasesTbl();
}

function setRegion(value){
	hideAllMessage();
	$.post(
			"/bitrix/admin/wtc_easydirect_ajax_podbor_exec.php",
			{CMD: "setRegion",REGION: value}, 
			function(data){
				data=JSON.parse(data);
				if(data["ERROR"]==0)
				{
					showGreenMessage(data["ERROR_TXT"],false);
				}			
			}
			);
}

function showPhrasesTbl(){
	var q;
	q=encodeURI($("#search").val());
	q=q.replace(/&/g, '%26');
	
	var minusfilter;
	minusfilter=0;
	if($("#filterByMinus").attr("checked") == 'checked') {minusfilter=1;}
	
	$("#phrasesMainTbl").html('<img src="/bitrix/themes/.default/images/wait.gif">');
	$("#phrasesMainTbl").load("/bitrix/admin/wtc_easydirect_ajax_podbor_tbl.php","q="+q+"&minusfilter="+minusfilter);
}

$(document).ready(function() {
	showPhrasesTbl();
});
</script>

<?//================BUTTONS=================== ?>
<div class="adm-list-table-top">
	<a href="#stayhere" onclick="showAddForm()" class="adm-btn adm-btn-green adm-btn-add" title="<?=GetMessage("EASYDIRECT_PODBOR_step1")?>"><?=GetMessage("EASYDIRECT_PODBOR_step1")?></a>
	<a href="#stayhere" onclick="startGetReports()" class="adm-btn" title="<?=GetMessage("EASYDIRECT_PODBOR_step2")?>"><?=GetMessage("EASYDIRECT_PODBOR_step2")?></a>
	<a href="#stayhere" onclick="startGetReports(true)" class="adm-btn" title="<?=GetMessage("EASYDIRECT_PODBOR_step3")?>"><?=GetMessage("EASYDIRECT_PODBOR_step3")?></a>
	<a href="wtc_easydirect_create_company.php" class="adm-btn" title="<?=GetMessage("EASYDIRECT_PODBOR_step4")?>"><?=GetMessage("EASYDIRECT_PODBOR_step4")?></a>
	<a href="#stayhere" onclick="addNewPhrasesToCompany()" class="adm-btn" title="<?=GetMessage("EASYDIRECT_PODBOR_step4_1_tit")?>"><?=GetMessage("EASYDIRECT_PODBOR_step4_1")?></a>	
	<a href="#stayhere" onclick="if(confirm('<?=GetMessage("EASYDIRECT_PODBOR_clear_all_conf")?>')){clearAll();}" class="adm-btn" title="<?=GetMessage("EASYDIRECT_PODBOR_step5")?>"><font color="red"><?=GetMessage("EASYDIRECT_PODBOR_step5")?></font></a>
</div>
<br>
<?//===========MESSAGE======================= ?>
<div id="errorMessage" class="adm-info-message-wrap adm-info-message-red" style="display: none;">
	<div class="adm-info-message">
		<div class="adm-info-message-title"></div>
		<div class="adm-info-message-icon"></div>
	</div>
</div>

<?//===========REPORT MESSAGE GREEN============== ?>
<div id="reportMessage" class="adm-info-message-wrap adm-info-message-green" style="display: none;">
	<div class="adm-info-message">
		<div class="adm-info-message-title">
		  <?=GetMessage("EASYDIRECT_PODBOR_report_message")?>
		</div>
		<div class="adm-info-message-icon"></div>
	</div>
</div>

<?//===========GREEN MESSAGE============== ?>
<div id="greenMessage" class="adm-info-message-wrap adm-info-message-green" style="display: none;">
	<div class="adm-info-message">
		<div class="adm-info-message-title">
		</div>
		<div class="adm-info-message-icon"></div>
	</div>
</div>

<?//================ADD PHRASES=================== ?>
<div id="addPhrasesForm" style="display: none;">
	<form method="post">
	  <table class="sale-personal-order-list data-table">
			<tr><td><?=GetMessage("EASYDIRECT_PODBOR_add_phrase_info")?>&nbsp;&nbsp;&nbsp;<input type="checkbox" name="addToMinus" value="Y"> <?=GetMessage("EASYDIRECT_PODBOR_addtominus")?></td></tr>
			<tr><td><textarea rows="20" cols="70" name="phrases"></textarea></td></tr>
			<tr><td><?=GetMessage("EASYDIRECT_PODBOR_addfromcompany")?><br></td></tr>
			<tr><td>
                		<select name="addfromcompany">
                			<option value="0"><?=GetMessage("EASYDIRECT_PODBOR_notloadfromcompany")?></option>
                    		<?
                        		$rsData = CEDirectCompany::GetList(array("NAME"=>"ASC"), array(), false, array("ID","NAME"));
                        		while($arRes = $rsData->Fetch()){
                        			echo '<option value="'.$arRes['ID'].'">'.$arRes['NAME'].'</option>';
                        		}
                    		?>
                		</select>  			
			</td></tr>
			<tr><td>&nbsp;</td></tr>
			<tr><td><a href="wtc_easydirect_podbor_phrases.php" class="adm-btn">< <?=GetMessage("EASYDIRECT_PODBOR_back")?></a><input style="float: right;" type="submit" name="submitAddPhrases" value="<?=GetMessage("EASYDIRECT_PODBOR_add_phrase_btn")?>"></td></tr>
	  </table> 
	</form>
</div>
<?//================SHOW TBL=================== ?>
<div id="showTbl">
    <?//=== FILTER ====?>
    <table class="adm-filter-main-table" id="filterTableBlock">
            <tr>
    			<td class="adm-filter-main-table-cell">
    				<div class="adm-filter-tabs-block">
    					<span class="adm-filter-tab adm-filter-tab-active"><?=GetMessage("EASYDIRECT_PODBOR_filter")?></span>
    				</div>
    			</td>
    		</tr>
    		<tr>
    			<td class="adm-filter-main-table-cell">
    				<div class="adm-filter-content">		
    					<div class="adm-filter-content-table-wrap" style="height: auto; overflow-y: visible;">
    						<table class="adm-filter-content-table" style="display: table;" cellspacing="0">
     						    <tr>
                                	<td class="adm-filter-item-left"><?=GetMessage("EASYDIRECT_PODBOR_phrase_region")?>:</td>
        	                        <td class="adm-filter-item-center">
        	                           <div class="adm-filter-alignment"><div class="adm-filter-box-sizing"><div class="adm-select-wrap">
                                           <select onchange="setRegion(this.value)" class="adm-select">
                                            <option value="0"><?=GetMessage("EASYDIRECT_PODBOR_phrase_region_all")?></option>
                                            <?
                                                if(strlen(EDIRECT_TOKEN)>3){
                                                    $YASearchXML=new CEDirectYaXml();
                                                    foreach ($YASearchXML->getYaCityRegions() as $key=>$val){
                                                        echo '<option value="'.$key.'"'.(EDIRECT_PODBOR_PHRASE_REGION==$key?" selected":"").'>'.$val.'</option>';
                                                    }
                                                }
                                            ?>
                                           </select>
        	                           </div></div></div>
        	                        </td>
                                </tr>                                   
     						    <tr>
                                	<td class="adm-filter-item-left"><?=GetMessage("EASYDIRECT_PODBOR_filterByMinus")?>:</td>
        	                        <td class="adm-filter-item-center">
        	                           <div class="adm-filter-alignment"><div class="adm-filter-box-sizing"><div class="adm-input-wrap">
        	                               <input onclick="showPhrasesTbl()" type="checkbox" id="filterByMinus" name="filterByMinus" value="1">
        	                           </div></div></div>
        	                        </td>
                                </tr>   
        						<tr>
                                	<td class="adm-filter-item-left"><?=GetMessage("EASYDIRECT_PODBOR_filter_phrase")?>:</td>
        	                        <td class="adm-filter-item-center">
        	                           <div class="adm-filter-alignment"><div class="adm-filter-box-sizing"><div class="adm-input-wrap">
        	                               <input onkeyup="filterPhrases()" type="text" name="q" id="search" size="27" value="" class="adm-input">
        	                           </div></div></div>
        	                        </td>
                                </tr>
                            </table>
    					</div>
    					<div class="adm-filter-bottom-separate" id="tbl_ed_company_filter_bottom_separator" style="display: block;"></div>
    					<div class="adm-filter-bottom">
    						<input type="submit" onclick="filterPhrases(); return false;" class="adm-btn" value="<?=GetMessage("EASYDIRECT_PODBOR_filter_search")?>">
    						<input type="submit" onclick="filterClear(); return false;" class="adm-btn" value="<?=GetMessage("EASYDIRECT_PODBOR_filter_clear")?>">
        					<div class="adm-filter-setting-block">
    					       <a onclick="showMassDel()" class="adm-btn" style="width: auto !important;" href="#stayhere"><?=GetMessage("EASYDIRECT_PODBOR_mass_del_link")?></a>
    					    </div>
    					<div>
    				</div>
    			</td>
    		</tr>
    </table>    
    <?//=== MASS DELL FORM ====?>
    <div id="massdel" style="display: none;">
            <?=GetMessage("EASYDIRECT_PODBOR_mass_del_info")?><br>
            <form method="post">
                <textarea rows="15" cols="50" name="delManualPhrases"></textarea><br>
    			<?=GetMessage("EASYDIRECT_PODBOR_delfromcompany")?><br>
        		<select name="delfromcompany">
        			<option value="0"><?=GetMessage("EASYDIRECT_PODBOR_notdelfromcompany")?></option>
            		<?
                		$rsData = CEDirectCompany::GetList(array("NAME"=>"ASC"), array(), false, array("ID","NAME"));
                		while($arRes = $rsData->Fetch()){
                			echo '<option value="'.$arRes['ID'].'">'.$arRes['NAME'].'</option>';
                		}
            		?>
        		</select>  			               
                <br><br>
                <a href="wtc_easydirect_podbor_phrases.php" class="adm-btn">< <?=GetMessage("EASYDIRECT_PODBOR_back")?></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <input type="submit" name="delManualPhrasesBut" value="<?=GetMessage("EASYDIRECT_PODBOR_mass_del_btn")?>">
            </form>
            <hr class="wtc_easydirect_line_hr">
    </div>
    
    <?//=== PHRASES TABLE ====?>
    <br>
    <div id="phrasesMainTbl"><img src="/bitrix/themes/.default/images/wait.gif"></div>
</div>

<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");
?>