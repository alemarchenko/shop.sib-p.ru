<?
/**
 * This file is part of the wtc.easydirect module
 * @author The WebTechCom Studio,  http://www.webtechcom.ru
 * @copyright (c) The WebTechCom Studio. All Rights Reserved.
 */

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

/**
 * Class CEDirectHelp
 * return Help links by sections
 * @category module wtc.easydirect Help
 */
class CEDirectHelp
{
 	/**
	 * Show link by admin section
	 * Add help link By admin Title
	 *
	 * @param string $filepath      get link to this file path __FILE__
	 * @return atring   crate link JS code
	 */	
	public static function showLink($filepath)
	{
	    if(EDIRECT_SHOW_HELP_BUTTONS==false) return ""; 
	        
	    $link=CEDirectHelp::getLinkByFileName(basename($filepath,".php"));
	    if($link===0) return "";
	    
        $createLinkCode="<script>
        BX.ready(function(){
            var object = BX.create( {
                tag: 'a',
                props: { title: '".GetMessage("EDIRECT_HELP_LINK_TITLE")."', href: '".$link."', target: '_blank'},
                attrs: {className: 'wtc-easydirect-help-link'},
                html: '<i>?</i><span>".GetMessage("EDIRECT_HELP_LINK_TEXT")."</span>'
            });
            BX('adm-title').append(object);
        });
        </script>";
        
        return $createLinkCode;
	}
	
	/**
	 * Return help link bu FIle name
	 *
	 * @param string $filepath      get link to this file path __FILE__
	 * @return string    link 
	 */
	public static function getLinkByFileName($filename)
	{
	    $siteUrl="http://www.easydirect.ru/doc";
	    $arRelation=array(
	        "wtc_easydirect_company" => "/kampanii/spisok-kampaniy/",
	        "wtc_easydirect_company_edit" => "/kampanii/vkladka-obyavleniya/",
	        "wtc_easydirect_company_import" => "/zapusk-modulya/import-kompaniy/",
	        "wtc_easydirect_create_company" => "/sozdanie-obyavleniy/sostavlenie-obyavleniy/",
	        "wtc_easydirect_exchange_stat" => "/instrumenty/monitoring/",
	        "wtc_easydirect_line" => "/instrumenty/monitoring/",
	        "wtc_easydirect_log" => "/instrumenty/monitoring/",
	        "wtc_easydirect_metod_edit" => "/metody-rascheta-stavok/ustanovka-storonnikh-metodov/",
	        "wtc_easydirect_metod" => "/metody-rascheta-stavok/ustanovka-storonnikh-metodov/",
	        "wtc_easydirect_podbor_phrases" => "/sozdanie-obyavleniy/podbor-klichevykh-slov/",
	        "wtc_easydirect_stat"=> "/instrumenty/statistika/",
	        "wtc_easydirect_templates_create_ads"=>"/generatsiya-obyavleniy/generatsiya-po-tovaram/",
	        "wtc_easydirect_templates_edit"=>"/generatsiya-obyavleniy/sozdanie-shablonov/",
	        "wtc_easydirect_templates"=>"/generatsiya-obyavleniy/sozdanie-shablonov/",
	        "wtc_easydirect_templates_find_goods"=>"/generatsiya-obyavleniy/generatsiya-po-tovaram/",
	        "wtc_easydirect_templates_find_sections"=>"/generatsiya-obyavleniy/generatsiya-po-razdelam/",
	        "wtc_easydirect_tools_lowctr"=>"/instrumenty/poisk-nizkikh-ctr/",
	        "wtc_easydirect_tools_lowprice"=>"/instrumenty/poisk-nizkikh-stavok/",
	        "wtc_easydirect_tools_lowshows"=>"/kampanii/obedinenie-obyavleniy/"
	    );
	    
	    if(isset($arRelation[$filename])) return $siteUrl.$arRelation[$filename];
	    else return 0;
	}
	
}
?> 
