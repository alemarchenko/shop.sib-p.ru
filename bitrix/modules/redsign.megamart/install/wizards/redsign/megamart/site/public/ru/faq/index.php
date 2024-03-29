<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Помощь");

?>
 <?$APPLICATION->IncludeComponent(
	"bitrix:news.list", 
	"accordion", 
	array(
		"ACTIVE_DATE_FORMAT" => "d.m.Y",
		"ADD_SECTIONS_CHAIN" => "N",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"CACHE_FILTER" => "N",
		"CACHE_GROUPS" => "Y",
		"CACHE_TIME" => "36000000",
		"CACHE_TYPE" => "A",
		"CHECK_DATES" => "Y",
		"COMPONENT_TEMPLATE" => "accordion",
		"DETAIL_URL" => "",
		"DISPLAY_BOTTOM_PAGER" => "N",
		"DISPLAY_DATE" => "Y",
		"DISPLAY_NAME" => "Y",
		"DISPLAY_PICTURE" => "Y",
		"DISPLAY_PREVIEW_TEXT" => "Y",
		"DISPLAY_TOP_PAGER" => "N",
		"FIELD_CODE" => array(
			0 => "",
			1 => "",
		),
		"FILTER_NAME" => "",
		"HIDE_LINK_WHEN_NO_DETAIL" => "N",
		"IBLOCK_ID" => "#CONTENT_FAQ_IBLOCK_ID#",
		"IBLOCK_TYPE" => "content",
		"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
		"INCLUDE_SUBSECTIONS" => "Y",
		"JOB_LINK" => "#SITE_DIR#include/forms/job/?element_id=#ELEMENT_ID#",
		"MESSAGE_404" => "",
		"NEWS_COUNT" => "20",
		"NOTE_PROP" => "-",
		"PAGER_BASE_LINK_ENABLE" => "N",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL" => "N",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_TEMPLATE" => ".default",
		"PAGER_TITLE" => "Новости",
		"PARENT_SECTION" => "",
		"PARENT_SECTION_CODE" => "",
		"PREVIEW_TRUNCATE_LEN" => "",
		"PROPERTY_CODE" => array(
			0 => "TYPE",
			1 => "NOTE",
			2 => "",
		),
		"SET_BROWSER_TITLE" => "N",
		"SET_LAST_MODIFIED" => "N",
		"SET_META_DESCRIPTION" => "N",
		"SET_META_KEYWORDS" => "N",
		"SET_STATUS_404" => "N",
		"SET_TITLE" => "N",
		"SHOW_404" => "N",
		"SOCIAL_SERVICES" => "vkontakte,facebook,odnoklassniki,twitter",
		"SORT_BY1" => "ACTIVE_FROM",
		"SORT_BY2" => "SORT",
		"SORT_ORDER1" => "DESC",
		"SORT_ORDER2" => "ASC",
		"STRICT_SECTION_CHECK" => "N",
		"RS_VACANCIES_B24_CRM_FORM_USE" => "Y",
		"RS_VACANCIES_B24_CRM_FORM_ID" => "9",
		"RS_VACANCIES_B24_CRM_FORM_SEC" => "2wf3vq",
		"RS_VACANCIES_B24_CRM_FORM_VACANCY_PARAM" => "my_param",
		"MESS_BTN_FILTER_ALL" => "Все вопросы"
	),
	false
);?>
<br><br><h3>Задать вопрос</h3>
<?$APPLICATION->IncludeComponent(
	"redsign:forms", 
	"form", 
	array(
		"AJAX_MODE" => "Y",
		"AJAX_OPTION_ADDITIONAL" => "",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "N",
		"COMPONENT_TEMPLATE" => "form",
		"EMAIL_TO" => "#SHOP_EMAIL#",
		"EVENT_TYPE" => "RS_FORM_FAQ",
		"FIELD_PARAMS" => "{\"66\":{\"validate\":\"\",\"validatePattern\":\"\",\"mask\":\"\"},\"67\":{\"validate\":\"\",\"validatePattern\":\"\",\"mask\":\"+ 7 (999) 999-99-99\"},\"68\":{\"validate\":\"\",\"validatePattern\":\"\",\"mask\":\"\"},\"69\":{\"validate\":\"\",\"validatePattern\":\"\",\"mask\":\"\"},\"70\":{\"validate\":\"\",\"validatePattern\":\"\",\"mask\":\"\"}}",
		"IBLOCK_ID" => "#FORMS_FAQ_IBLOCK_ID#",
		"IBLOCK_TYPE" => "forms",
		"SUCCESS_MESSAGE" => "Cпасибо, ваша заявка принята!",
		"USER_CONSENT" => "Y",
		"USER_CONSENT_ID" => "#USER_CONSENT_ID#",
		"USER_CONSENT_IS_CHECKED" => "Y",
		"USER_CONSENT_IS_LOADED" => "N",
		"USE_CAPTCHA" => "Y"
	),
	false
);?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>