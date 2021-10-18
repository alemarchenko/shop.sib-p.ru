<?
/**
 * This file is part of the wtc.easydirect module
 * @author The WebTechCom Studio,  http://www.webtechcom.ru
 * @copyright (c) The WebTechCom Studio. All Rights Reserved.
 */

require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");

//module include
$install_status = CModule::IncludeModuleEx("wtc.easydirect");
CModule::IncludeModule("iblock");
IncludeModuleLangFile(__FILE__);

//get POST_RIGHT
$POST_RIGHT = $APPLICATION->GetGroupRight("wtc.easydirect");
//Check POST_RIGHT
if ($POST_RIGHT < "R")
	$APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));

// ******************************************************************** //

if(isset($_POST["createAds"])){
if($_POST["IBLOCK_ID"]>0&&$_POST["template"]>0){
if($_POST["isSection"]=="Y") {
COption::SetOptionString("wtc.easydirect", 'last_template_section', $_POST["template"]);
}
else {
COption::SetOptionString("wtc.easydirect", 'last_template_goods', $_POST["template"]);
}
$IBLOCK_ID=$_POST["IBLOCK_ID"];
$goodsIDs=array();
foreach (explode("\n",$_POST['goods']) as $str){
foreach (explode(",",$str) as $value){
$value=trim($value);
if(strlen($value)) {$goodsIDs[]=$value;}
}
}
$goodsIDs=array_unique($goodsIDs);$arCatalog=array();
if (IsModuleInstalled("catalog")&&CModule::IncludeModule("catalog"))
{
$arCatalog=CCatalog::GetByID($IBLOCK_ID);
$defPriceTypeID=CEDirectCatalogItems::getDefaultPriceType();
} $url_prefix = EDIRECT_URL_PREFIX;
$arHost=explode(":",$_SERVER["HTTP_HOST"]);
$url_prefix .= $arHost[0];$GET_PROP_WITH_XML_ID=false;
if($_POST["MULTIPLY_BANNERS"]=="Y"){
$GET_PROP_WITH_XML_ID=true;
}$arElmentFieldsToReplace=array();
if($_POST["isSection"]=="Y"){
$res = CIBlock::GetByID($IBLOCK_ID);
$arIblock = $res->GetNext();foreach ($goodsIDs as $section){
$res = CIBlockSection::GetList(Array("ID"=>"ASC"), Array("IBLOCK_ID" => $IBLOCK_ID, "ID" => $section), false, array("UF_*"));
if($arSection = $res->GetNext()){
$arFieldsToReplace=array(
"IBLOCK.ID"=>$arSection["IBLOCK_ID"],
"IBLOCK.CODE"=>$arSection["IBLOCK_CODE"],
"IBLOCK.NAME"=>$arIblock["NAME"],
"IBLOCK.URL"=>$url_prefix.$arSection["LIST_PAGE_URL"],
"SECTION.ID"=>$section,
"SECTION.CODE"=>$arSection["CODE"],
"SECTION.NAME"=>$arSection["NAME"],
"SECTION.URL"=>$url_prefix.$arSection["SECTION_PAGE_URL"],
"IMAGE.ID"=>($arSection["DETAIL_PICTURE"]>0?$arSection["DETAIL_PICTURE"]:$arSection["PICTURE"])
);if($arSection["IBLOCK_SECTION_ID"]>0){
$resPSect = CIBlockSection::GetByID($arSection["IBLOCK_SECTION_ID"]);
if($arParentSection = $resPSect->GetNext()){
$arFieldsToReplace["PARENT_SECTION.NAME"]=$arParentSection["NAME"];
}
}$arUfSection=CEDirectTemplates::getReplaceValuesForUserTypeProps
($arSection); $arPropsFieldsToReplace=array();
$arOffersFields=array();
$res = CIBlockElement::GetList(Array(), Array("IBLOCK_ID"=>$IBLOCK_ID,"SECTION_ID"=>$section,"INCLUDE_SUBSECTIONS"=>"Y"));
while($ob = $res->GetNextElement())
{
$arFields = $ob->GetFields();
$arProps = $ob->GetProperties();if(!$arFieldsToReplace["IMAGE.ID"]) {$arFieldsToReplace["IMAGE.ID"]=($arFields["DETAIL_PICTURE"]>0?$arFields["DETAIL_PICTURE"]:$arFields["PREVIEW_PICTURE"]);}$arPropsFieldsToReplace["ELEMENT.NAME"][]=$arFields["NAME"];
if (count($arCatalog)>0)
{
if($arCatalog["OFFERS_IBLOCK_ID"]>0){
$resOrrefs = CIBlockElement::GetList(Array(), Array("IBLOCK_ID"=>$arCatalog["OFFERS_IBLOCK_ID"],"PROPERTY_".$arCatalog["OFFERS_PROPERTY_ID"]=>$arFields["ID"]));
while($obOrrefs = $resOrrefs->GetNextElement())
{
$arOffer = $obOrrefs->GetFields();
$arOfferProps = $obOrrefs->GetProperties();
$arOffersFields["OFFER.NAME"][]=$arOffer["NAME"];
CEDirectTemplates::getReplaceValuesForProps($arOffersFields,$arOfferProps,"OFFER_PROP",$GET_PROP_WITH_XML_ID);
}
}
}
CEDirectTemplates::getReplaceValuesForProps($arPropsFieldsToReplace,$arProps,"PROP",$GET_PROP_WITH_XML_ID);
}
$arPropsFieldsToReplace=array_merge($arPropsFieldsToReplace,$arUfSection);
if(count($arOffersFields)) {$arPropsFieldsToReplace=array_merge($arPropsFieldsToReplace,$arOffersFields);}$arElmentFieldsToReplace[]=array(
"arFieldsToReplace"=>$arFieldsToReplace,
"arPropsFieldsToReplace"=>$arPropsFieldsToReplace
);
}
}
}
else{
$res = CIBlockElement::GetList(Array(), Array("IBLOCK_ID"=>$IBLOCK_ID,"ID"=>$goodsIDs));
while($ob = $res->GetNextElement())
{
$arFields = $ob->GetFields();
$arProps = $ob->GetProperties();
$res1 = CIBlockSection::GetByID($arFields["IBLOCK_SECTION_ID"]);
$arSection = $res1->GetNext();$arFieldsToReplace=array(
"IBLOCK.ID"=>$arFields["IBLOCK_ID"],
"IBLOCK.CODE"=>$arFields["IBLOCK_CODE"],
"IBLOCK.NAME"=>$arFields["IBLOCK_NAME"],
"IBLOCK.URL"=>$url_prefix.$arFields["LIST_PAGE_URL"],
"SECTION.ID"=>$arFields["IBLOCK_SECTION_ID"
],
"SECTION.CODE"=>$arSection["CODE"],
"SECTION.NAME"=>$arSection["NAME"],
"SECTION.URL"=>$url_prefix.$arSection["SECTION_PAGE_URL"],
"ELEMENT.ID"=>$arFields["ID"],
"ELEMENT.CODE"=>$arFields["CODE"],
"ELEMENT.NAME"=>$arFields["NAME"],
"ELEMENT.URL"=>$url_prefix.$arFields["DETAIL_PAGE_URL"],
"IMAGE.ID"=>($arFields["DETAIL_PICTURE"]>0?$arFields["DETAIL_PICTURE"]:$arFields["PREVIEW_PICTURE"])
);if($arSection["IBLOCK_SECTION_ID"]>0){
$resPSect = CIBlockSection::GetByID($arSection["IBLOCK_SECTION_ID"]);
if($arParentSection = $resPSect->GetNext()){
$arFieldsToReplace["PARENT_SECTION.NAME"]=$arParentSection["NAME"];
}
}$arOffersFields=array(); 
if (count($arCatalog)>0)
{
$arOffersIDs=array();
if($arCatalog["OFFERS_IBLOCK_ID"]>0){
$resOrrefs = CIBlockElement::GetList(Array(), Array("IBLOCK_ID"=>$arCatalog["OFFERS_IBLOCK_ID"],"PROPERTY_".$arCatalog["OFFERS_PROPERTY_ID"]=>$arFields["ID"]));
while($obOrrefs = $resOrrefs->GetNextElement())
{
$arOffer = $obOrrefs->GetFields();
$arOfferProps = $obOrrefs->GetProperties();
$arOffersIDs[]=$arOffer["ID"];
$arOffersFields["OFFER.NAME"][]=$arOffer["NAME"];
CEDirectTemplates::getReplaceValuesForProps($arOffersFields,$arOfferProps,"OFFER_PROP",$GET_PROP_WITH_XML_ID);
}
}$resProductPrices = \Bitrix\Catalog\PriceTable::getList([
"filter" => ["=PRODUCT_ID" => $arFields["ID"]],
"order" => ["CATALOG_GROUP_ID" => "ASC"]
]);
while($arProductPrice=$resProductPrices->fetch()){
if($arProductPrice["CATALOG_GROUP_ID"]==$defPriceTypeID) {
$arFieldsToReplace["CATALOG.PRICE"]=round($arProductPrice["PRICE"]);
}
else{
$arFieldsToReplace["CATALOG.PRICE_".$arProductPrice["CATALOG_GROUP_ID"]]=round($arProductPrice["PRICE"]);
}
}$QUANTITY=0;
if(count($arOffersIDs)==0) $arOffersIDs[]=$arFields["ID"];
foreach (CEDirectCatalogItems::getProductQuantityByIDs($arOffersIDs) as $val){
$QUANTITY+=$val;
}
$arFieldsToReplace["CATALOG.QUANTITY"]=$QUANTITY;
}$arPropsFieldsToReplace=array();
CEDirectTemplates::getReplaceValuesForProps($arPropsFieldsToReplace,$arProps
,"PROP",$GET_PROP_WITH_XML_ID);if(count($arOffersFields)) {$arPropsFieldsToReplace=array_merge($arPropsFieldsToReplace,$arOffersFields);}$arElmentFieldsToReplace[]=array(
"arFieldsToReplace"=>$arFieldsToReplace,
"arPropsFieldsToReplace"=>$arPropsFieldsToReplace
);
}
}array_walk_recursive($arElmentFieldsToReplace, 'CEDirectTemplates::filterCatalogValue');$arBGrouopsFieldsToReplace=$EDirectMain->prepareFields($arElmentFieldsToReplace,$arCatalog);
$arNewCompany=array();$res=CEDirectTemplates::GetByID($_POST["template"]);
$arTemplate = $res->Fetch();$arMinus=array();
$arSitelinks=array();
$arGroups=array();
$cntGroup=10000;
$cntBanner=20000;
foreach ($arBGrouopsFieldsToReplace as $arFieldsToReplace){
$arMinus=array_merge($arMinus,CEDirectTemplates::compileRawWordString($arTemplate["MINUS_WORDS"],$arFieldsToReplace));
foreach (CAllEDirectTable::UnSerializeArrayField($arTemplate["SITELINKS"]) as $val){
$arProductSitelinks=CEDirectTemplates::multiplyArrays(
array(
"Title"=>CEDirectBanner::ucfirstCyrillic(CEDirectTemplates::compileTemplateString($val["Title"],$arFieldsToReplace)),
"Href"=>CEDirectTemplates::compileTemplateString($val["Href"],$arFieldsToReplace),
"Description"=>CEDirectBanner::ucfirstCyrillic(CEDirectTemplates::compileTemplateString($val["Description"],$arFieldsToReplace))
)
);
if(isset($arProductSitelinks[0])){
$arSitelinks[]=$arProductSitelinks[0];
}
}
$arProductBanners=CEDirectTemplates::compileTemplateStringsArray(
array(
"title"=>$arTemplate["TITLE"],
"title2"=>$arTemplate["TITLE2"],
"text"=>$arTemplate["TEXT"],
"display_url"=>$arTemplate["DISPLAY_URL"]
),
$arFieldsToReplace
);foreach ($arProductBanners as &$banner){
$banner["title"]=CEDirectBanner::ucfirstCyrillic($banner["title"]);
$banner["title2"]=CEDirectBanner::ucfirstCyrillic($banner["title2"]);
$banner["text"]=CEDirectBanner::ucfirstCyrillic($banner["text"]);
$banner["display_url"]=str_replace(" ","-",$banner["display_url"]);
}
unset($banner);
$href=CEDirectTemplates::compileTemplateString
($arTemplate["HREF"],$arFieldsToReplace);
$price=CEDirectTemplates::compileTemplateString($arTemplate["PRICE"],$arFieldsToReplace); 
for($i=0;$i<50&&$i<count($arProductBanners);$i++){
$arNewCompany["group"][$cntGroup]["banners"][$cntBanner]=$arProductBanners[$i];
$arNewCompany["group"][$cntGroup]["banners"][$cntBanner]["href"]=$href[0];
$arNewCompany["group"][$cntGroup]["banners"][$cntBanner]["price"]=$price[0];
$arNewCompany["group"][$cntGroup]["banners"][$cntBanner]["image"]=($arFieldsToReplace["SMART_FILTER_IMAGE.ID"]?$arFieldsToReplace["SMART_FILTER_IMAGE.ID"]:$arFieldsToReplace["IMAGE.ID"]);
$arNewCompany["group"][$cntGroup]["banners"][$cntBanner]["image_type"]="bitrix_file";
$cntBanner++;
}
$arNewCompany["group"][$cntGroup]["phrases"]=implode(PHP_EOL,CEDirectTemplates::compileRawWordString($arTemplate["PHRASES"],$arFieldsToReplace));$cntGroup++;
}
$arNewCompany["minuswords"]=implode(",", array_unique($arMinus));
$arNewCompany["Sitelinks"]=array_slice(CEDirectTemplates::uniqueMultidimArray($arSitelinks,"Href"),0,8);
$arNewCompany["href"]=$href[0];
$arNewCompany["display_url"]=str_replace(" ","-",$display_url[0]);
$arNewCompany["CType"]="YA";
$arNewCompany["forSEARCH"]="Y";
$arNewCompany["forRSYA"]="Y"; 
$arNewCompany["name"]=CEDirectBanner::ucfirstCyrillic($arFieldsToReplace["IBLOCK.NAME"]."/".$arFieldsToReplace["SECTION.NAME"]."/");
if($_POST["isSection"]=="Y") {$arNewCompany["name"].=GetMessage("EASYDIRECT_TMPL_CREATE_sections_name");}
else {$arNewCompany["name"].=GetMessage("EASYDIRECT_TMPL_CREATE_goods_name");}
if($_POST["MULTIPLY_BANNERS"]=="Y") {$arNewCompany["name"].="(1:n)";}
else {$arNewCompany["name"].="(1:1)";}$arFindWords=array();
$arReplaceWords=array();
$i=0;
foreach (explode("\n",$_POST['replacements']) as $str){
$parts=explode("=",$str);
if(count($parts)>1&&strlen($parts[0])){
$arFindWords[$i]=$parts[0];
$arReplaceWords[$i]=trim($parts[1],"\n\r");
$i++;
}
}
if(count($arFindWords)>0){
foreach ($arNewCompany["Sitelinks"] as &$link){
foreach ($link
 as &$value){
$value=str_replace($arFindWords, $arReplaceWords, $value);
}
}
$arFieldsToCheck=array("title","title2","text","display_url");
foreach ($arNewCompany["group"] as &$group){
foreach ($group["banners"] as &$banner){
foreach ($arFieldsToCheck as $field){
$banner[$field]=str_replace($arFindWords, $arReplaceWords, $banner[$field]);
}
}
$group["phrases"]=str_replace($arFindWords, $arReplaceWords, $group["phrases"]);
}
}$is_break=0;
if($install_status!=1&&isset($_SESSION["WTCED_COUNT_CREATE_COMP"])&&$_SESSION["WTCED_COUNT_CREATE_COMP"]>9) {$is_break=1;}
if($is_break!=1&&CEDirectCompany::saveDataInFile("save",$arNewCompany)){
$countBaners=0;
foreach ($arNewCompany["group"] as $val){
$countBaners+=count($val["banners"]);
}
$countGroups=count($arNewCompany["group"]);
$message = new CAdminMessage(
Array(
"TYPE"=>"OK",
"MESSAGE" => GetMessage("EASYDIRECT_TMPL_CREATE_ok_title",Array ("#BCNT#" => $countBaners,"#GCNT#"=>$countGroups)),
"DETAILS"=> GetMessage("EASYDIRECT_TMPL_CREATE_ok_message"),
"HTML"=>true
)
); 
}
if($is_break) {$message = new CAdminMessage(GetMessage("EASYDIRECT_TMPL_CREATE_err2"));}
if($install_status!=1){
if(isset($_SESSION["WTCED_COUNT_CREATE_COMP"])) {$_SESSION["WTCED_COUNT_CREATE_COMP"]++;}
else {$_SESSION["WTCED_COUNT_CREATE_COMP"]=1;}
} }
else $message = new CAdminMessage(GetMessage("EASYDIRECT_TMPL_CREATE_err1"));
}if($_GET["genstart"]=="Y"){
$IBLOCK_ID=($_POST["IBLOCK_ID"]>0?$_POST["IBLOCK_ID"]:$_SESSION["WTCED_IBID_TO_CREATE_ADS"]);
$isSection=($_POST["isSection"]?$_POST["isSection"]:$_SESSION["WTCED_IS_SECTION_TO_CREATE_ADS"]);
$goods=($_POST["goods"]?$_POST["goods"]:implode(",",$_SESSION["WTCED_IDS_TO_CREATE_ADS"]));$res = CIBlock::GetByID($IBLOCK_ID);
if($ar_res = $res->GetNext())
{
$IBLOCK_NAME=$ar_res["NAME"];
}
}//                SHOW DATA                                             
// ******************************************************************** //
// SET TITLE
$APPLICATION->SetTitle(GetMessage("EASYDIRECT_TMPL_CREATE_title"));
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");
?>
<? 
if($message) {echo $message->Show();}
else if($install_status!=1){
    CAdminMessage::ShowMessage(
        Array(
            "TYPE"=>"ERROR",
            "MESSAGE" => GetMessage("EASYDIRECT_TMPL_CREATE_demo"),
            "DETAILS"=> GetMessage("EASYDIRECT_TMPL_CREATE_demo_txt"),
        )
    );        
}
?>

<?
//show code to append HELP Button/Link to Title
echo CEDirectHelp::showLink(__FILE__);
?>

<?if($_GET["genstart"]=="Y"){ ?>
<form action="" method="post" name="formCreateForTmpl">
    <table  class="wtc-easydirect-show-data-table">
   		<tr><th colspan="2"><?=GetMessage("EASYDIRECT_TMPL_CREATE_table_title")?></th></tr>
    	<tr>
        	<td><?=GetMessage("EASYDIRECT_TMPL_CREATE_iblock_id")?>:</td>
        	<td><?=$IBLOCK_NAME?><input type="hidden" name="IBLOCK_ID" value="<?=$IBLOCK_ID?>"></td>
        </tr>		  
    	<tr>
        	<td><?=GetMessage("EASYDIRECT_TMPL_CREATE_template")?>:</td>
        	<td>
        		<select name="template">
            		<?
            		//load last Template
            		$selectTmpl=0;
            		if($_POST["template"]>0){$selectTmpl=$_POST["template"];}
            		else if($isSection=="Y"){$selectTmpl=COption::GetOptionString("wtc.easydirect", "last_template_section");}
            		else {$selectTmpl=COption::GetOptionString("wtc.easydirect", "last_template_goods");}
            		//-------------------
            		
            		$rsData = CEDirectTemplates::GetList(
            		    array("NAME"=>"ASC"), 
            		    array("IBLOCK_ID"=>$IBLOCK_ID,"FOR_SECTIONS"=>$isSection), 
            		    false, 
            		    array("ID","NAME")
            		    );            		
            		while($arRes = $rsData->Fetch()){
            			echo '<option value="'.$arRes['ID'].'"'.($selectTmpl==$arRes['ID']?" selected":"").'>'.$arRes['NAME'].'</option>';
            		}
            		?>
        		</select>
        	</td>
        </tr>		
        <tr>
            <td><?echo GetMessage("EASYDIRECT_TMPL_CREATE_multiply_banners")?></td>
            <td><input type="checkbox" name="MULTIPLY_BANNERS" value="Y"<?if($_POST["MULTIPLY_BANNERS"] == "Y") {echo " checked";}?>></td>
        </tr>	
		<tr>
		  <td><?=GetMessage("EASYDIRECT_TMPL_CREATE_elements_ids")?></td>
		  <td><textarea rows="3" cols="50" name="goods"><?=$goods?></textarea></td>
	    </tr>	
		<tr>
		  <td><?=GetMessage("EASYDIRECT_TMPL_CREATE_replacements")?></td>
		  <td><textarea rows="10" cols="50" name="replacements"><?=$_POST["replacements"]?></textarea></td>
	    </tr>		    
		<tr><td colspan="2" align="right">
    		<input type="hidden" name="isSection" value="<?=$isSection?>">		
    		<input type="submit" name="createAds" value="<?=GetMessage("EASYDIRECT_TMPL_CREATE_btn")?>">
    	</td></tr>		
    </table>
</form>
<?}
else{ ?>
<div class="adm-list-table-top">
	<a href="/bitrix/admin/wtc_easydirect_templates_find_goods.php?lang=<?=LANG?>" class="adm-btn" title="<?=GetMessage("EASYDIRECT_TMPL_CREATE_goods")?>"><?=GetMessage("EASYDIRECT_TMPL_CREATE_goods")?></a>
	<a href="/bitrix/admin/wtc_easydirect_templates_find_sections.php?lang=<?=LANG?>" class="adm-btn" title="<?=GetMessage("EASYDIRECT_TMPL_CREATE_sections")?>"><?=GetMessage("EASYDIRECT_TMPL_CREATE_sections")?></a>
</div>
<br>
<?} ?>
<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");
?>