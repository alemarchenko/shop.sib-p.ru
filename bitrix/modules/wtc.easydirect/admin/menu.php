<?
IncludeModuleLangFile(__FILE__);

$install_status = CModule::IncludeModuleEx("wtc.easydirect");
$POST_RIGHT=$APPLICATION->GetGroupRight("wtc.easydirect");
$APPLICATION->SetAdditionalCSS("/bitrix/panel/wtc_easydirect/wtc_easydirect.css");
if($APPLICATION->GetGroupRight("wtc.easydirect")>"D")
{
	$aMenu = array(
		"parent_menu" => "global_menu_services",
		"section" => "wtc_easydirect",
		"sort" => 200,
		"text" => GetMessage("EASYDIRECT_MENU_NAME"),
		"title" => GetMessage("EASYDIRECT_MENU_NAME"),
		"url" => "",
		"icon" => "wtc_easydirect_menu_icon",
		"page_icon" => "wtc_easydirect_page_icon",
		"items_id" => "menu_wtc_easydirect",
		"items" => array(
			array(
				//Companies
				"text" => GetMessage("EASYDIRECT_MENU_COMPANY"),
				"title" => GetMessage("EASYDIRECT_MENU_COMPANY"),
				"url" => "",
				"items_id" => "menu_company",
				"items" => array(
							array(
									//List of companies
									"text" => GetMessage("EASYDIRECT_MENU_LISTCOMP"),
									"title" => GetMessage("EASYDIRECT_MENU_LISTCOMP"),
									"url" => "wtc_easydirect_company.php?lang=".LANGUAGE_ID,
									"more_url"  => array("wtc_easydirect_company_edit.php")
							),
				            ($POST_RIGHT<"W"?array():
							array(
									//Import company
									"text" => GetMessage("EASYDIRECT_MENU_IMPORT"),
									"title" => GetMessage("EASYDIRECT_MENU_IMPORT"),
									"url" => "wtc_easydirect_company_import.php?lang=".LANGUAGE_ID,
							)),
					)
			),
		    ($POST_RIGHT<"W"?array():
			array(
				//Company create
				"text" => GetMessage("EASYDIRECT_MENU_CREATE"),
				"title" => GetMessage("EASYDIRECT_MENU_CREATE"),
				"url" => "",
				"items_id" => "menu_create",
				"items" => array(
							array(
									//create words
									"text" => GetMessage("EASYDIRECT_MENU_PODBOR"),
									"title" => GetMessage("EASYDIRECT_MENU_PODBOR"),
									"url" => "wtc_easydirect_podbor_phrases.php",
							),
							array(
									//create banners
									"text" => GetMessage("EASYDIRECT_MENU_SOSTAVL"),
									"title" => GetMessage("EASYDIRECT_MENU_SOSTAVL"),
									"url" => "wtc_easydirect_create_company.php",
							),				
        				    array(
            				        //templates
            				        "text" => GetMessage("EASYDIRECT_MENU_TEMPLATES"),
            				        "title" => GetMessage("EASYDIRECT_MENU_TEMPLATES"),
            				        "url" => "wtc_easydirect_templates.php",
        				            "more_url"  => array("wtc_easydirect_templates_edit.php")
        				    ),				
        				    array(
            				        //find goods for templates
            				        "text" => GetMessage("EASYDIRECT_MENU_TEMPLATES_FIND_GOODS"),
            				        "title" => GetMessage("EASYDIRECT_MENU_TEMPLATES_FIND_GOODS"),
            				        "url" => "wtc_easydirect_templates_create_ads.php",
            				        "more_url"  => array("wtc_easydirect_templates_find_goods.php","wtc_easydirect_templates_find_sections.php")
        				    ),
					)
			)),
			array(
				//TOOLS
				"text" => GetMessage("EASYDIRECT_MENU_TOOLS"),
				"title" => GetMessage("EASYDIRECT_MENU_TOOLS"),
				"url" => "",
				"items_id" => "menu_tools",
				"items" => array(
        				    array(
        				        //statistics
        				        "text" => GetMessage("EASYDIRECT_MENU_STATISTIC"),
        				        "title" => GetMessage("EASYDIRECT_MENU_STATISTIC"),
        				        "url" => "wtc_easydirect_stat.php?lang=".LANGUAGE_ID,
        				        "more_url"  => array("wtc_easydirect_stat.php")
        				    ),
							array(
								//find Low price
								"text" => GetMessage("EASYDIRECT_MENU_LOWPRICE"),
								"title" => GetMessage("EASYDIRECT_MENU_LOWPRICE"),
								"url" => "wtc_easydirect_tools_lowprice.php?lang=".LANGUAGE_ID,
						        "more_url"  => array("wtc_easydirect_tools_lowprice.php")
							),
        				    array(
        				        //find Low CTR
        				        "text" => GetMessage("EASYDIRECT_MENU_LOWCTR"),
        				        "title" => GetMessage("EASYDIRECT_MENU_LOWCTR"),
        				        "url" => "wtc_easydirect_tools_lowctr.php?lang=".LANGUAGE_ID,
        				        "more_url"  => array("wtc_easydirect_tools_lowctr.php")
        				    ),
        				    array(
        				        //find LowShows
        				        "text" => GetMessage("EASYDIRECT_MENU_LOWSHOWS"),
        				        "title" => GetMessage("EASYDIRECT_MENU_LOWSHOWS"),
        				        "url" => "wtc_easydirect_tools_lowshows.php?lang=".LANGUAGE_ID,
        				        "more_url"  => array("wtc_easydirect_tools_lowshows.php")
        				    ),				    
        				    ($POST_RIGHT<"W"?array():
        				    array(
        				        //List of methods
        				        "text" => GetMessage("EASYDIRECT_MENU_METOD"),
        				        "title" => GetMessage("EASYDIRECT_MENU_METOD"),
        				        "url" => "wtc_easydirect_metod.php?lang=".LANGUAGE_ID,
        				        "more_url"  => array("wtc_easydirect_metod_edit.php")
        				    )),
					)
			),
			array(
				//Monitoring and logs
				"text" => GetMessage("EASYDIRECT_MENU_MONITORING"),
				"title" => GetMessage("EASYDIRECT_MENU_MONITORING"),
				"url" => "",
				"items_id" => "menu_monitoring",
				"items" => array(
							array(
									//Line of company
									"text" => GetMessage("EASYDIRECT_MENU_LINE"),
									"title" => GetMessage("EASYDIRECT_MENU_LINE"),
									"url" => "wtc_easydirect_line.php?lang=".LANGUAGE_ID,
							        "more_url"  => array("wtc_easydirect_line.php")
							),
							array(
									//Execute statistics
									"text" => GetMessage("EASYDIRECT_MENU_STATSOAP"),
									"title" => GetMessage("EASYDIRECT_MENU_STATSOAP"),
									"url" => "wtc_easydirect_exchange_stat.php?lang=".LANGUAGE_ID,
							        "more_url"  => array("wtc_easydirect_exchange_stat.php")
							),
							array(
									//Log
									"text" => GetMessage("EASYDIRECT_MENU_LOG"),
									"title" => GetMessage("EASYDIRECT_MENU_LOG"),
									"url" => "wtc_easydirect_log.php?lang=".LANGUAGE_ID,
							        "more_url"  => array("wtc_easydirect_log.php")
							),
					)
			),
		    array(
		        //Settings
		        "text" => GetMessage("EASYDIRECT_MENU_SETTINGS"),
		        "title" => GetMessage("EASYDIRECT_MENU_SETTINGS"),
		        "url" => "/bitrix/admin/settings.php?mid=wtc.easydirect&mid_menu=1&lang=".LANGUAGE_ID,
		        "items_id" => "menu_settings",
		    ),
		)
	);

	if($install_status==3){
	    $aMenu['items']=array(
	        array(
	            //Settings
	            "text" => GetMessage("EASYDIRECT_MENU_SETTINGS"),
	            "title" => GetMessage("EASYDIRECT_MENU_SETTINGS"),
	            "url" => "/bitrix/admin/settings.php?mid=wtc.easydirect&mid_menu=1&lang=".LANGUAGE_ID,
	            "items_id" => "menu_settings",
	        )	        
	    );
	}
	
	return $aMenu;
}
return false;
?>